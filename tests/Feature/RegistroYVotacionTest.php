<?php

namespace Tests\Feature;

use App\Models\Boleta;
use App\Models\BoletaCandidato;
use App\Models\Conteo;
use App\Models\ConvocatoriaPadron;
use App\Models\Voto;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;
use ZipArchive;

class RegistroYVotacionTest extends TestCase
{
    public function test_registro_completo_de_un_votante(): void
    {
        $c = $this->crearConvocatoria(2);
        $votante = $this->crearVotante($c, ['curp' => 'AAAA800101HDFBBB01']);

        $this->get('/registro/'.Crypt::encrypt($c->id).'/')
            ->assertOk()->assertViewIs('registro.entradaCURP');

        $this->post('/validaCURP/'.Crypt::encrypt($c->id), ['curp' => ' aaaa800101hdfbbb01 '])
            ->assertOk()->assertViewIs('registro.formulario');

        $this->post('/registrarse/'.Crypt::encrypt($votante->id).'/', [
            'correo_electronico' => 'Votante@Example.com ',
            'correo_electronico_2' => 'votante@example.com',
            'memoria_documental_ruta_1' => UploadedFile::fake()->image('ine_frente.jpg'),
            'memoria_documental_ruta_2' => UploadedFile::fake()->image('ine_reverso.png'),
            'memoria_documental_ruta_3' => UploadedFile::fake()->image('selfie.jpg'),
        ])->assertOk()->assertViewIs('registro.confirmacion');

        $votante->refresh();
        $this->assertSame('votante@example.com', $votante->correo_electronico);
        $this->assertNotNull($votante->fecha_registro);
        foreach (['ruta_ine_frente', 'ruta_ine_reverso', 'ruta_selfie_con_ine'] as $campo) {
            $this->assertStringStartsWith('/archivos/'.$c->id.'/', $votante->$campo);
            $this->assertFileExists(public_path($votante->$campo));
        }
    }

    public function test_registro_rechaza_archivos_que_no_son_imagen(): void
    {
        $c = $this->crearConvocatoria(2);
        $votante = $this->crearVotante($c);

        $this->post('/registrarse/'.Crypt::encrypt($votante->id).'/', [
            'correo_electronico' => 'a@example.com',
            'correo_electronico_2' => 'a@example.com',
            'memoria_documental_ruta_1' => UploadedFile::fake()->create('virus.php', 1),
            'memoria_documental_ruta_2' => UploadedFile::fake()->image('b.png'),
            'memoria_documental_ruta_3' => UploadedFile::fake()->image('c.png'),
        ])->assertViewHas('mensaje', 'El archivo no es de algún formato permitido');

        $this->assertNull($votante->fresh()->fecha_registro);
    }

    public function test_curp_fuera_del_padron(): void
    {
        $c = $this->crearConvocatoria(2);

        $this->from('/registro/x')
            ->post('/validaCURP/'.Crypt::encrypt($c->id), ['curp' => 'ZZZZ800101HDFZZZ09'])
            ->assertSessionHas('message', 'El CURP ingresado no se encuentra en el padrón de la convocatoria');
    }

    public function test_validador_aprueba_registro_y_se_envia_correo(): void
    {
        $c = $this->crearConvocatoria(2);
        $votante = $this->crearVotante($c, ['fecha_registro' => Carbon::now(), 'correo_electronico' => 'v@example.com']);
        $validador = $this->crearUsuario(4, ['convocatoria_id' => $c->id]);

        $this->actingAs($validador)->get('/validacion/')
            ->assertOk()->assertViewIs('registro.validacion');
        $this->assertSame($validador->id, (int) $votante->fresh()->validador_id);

        // compact("resultado", $resultado) fallaba en PHP 8.
        $this->post('/validacion/aprobacion/'.Crypt::encrypt($votante->id), ['informacion_valida' => 'on'])
            ->assertOk()
            ->assertViewIs('registro.resultadoValidacion')
            ->assertViewHas('resultado', 'El votante: '.$votante->curp.' ha sido validado y podrá votar el día de la consulta.');

        $this->assertNotNull($votante->fresh()->fecha_validacion);
        $this->assertSame('Confirmación de registro a SIRVOLAB', $this->correosEnviados()[0]->getOriginalMessage()->getSubject());
    }

    public function test_validador_rechaza_registro(): void
    {
        $c = $this->crearConvocatoria(2);
        $votante = $this->crearVotante($c, ['fecha_registro' => Carbon::now(), 'correo_electronico' => 'v@example.com']);

        $this->actingAs($this->crearUsuario(4, ['convocatoria_id' => $c->id]))
            ->get('/validacion/rechazo/'.$votante->id.'/mot/1')
            ->assertOk()
            ->assertViewHas('resultado', 'La identificación no es legible, por lo que el registro queda rechazado.');

        $this->assertSame(1, (int) $votante->fresh()->motivo_rechazo_id);
        $this->assertCount(1, $this->correosEnviados());
    }

    private function prepararConsulta(array $convocatoria = []): array
    {
        $c = $this->crearConvocatoria(4, $convocatoria);
        $votante = $this->crearVotante($c, ['fecha_validacion' => Carbon::now()]);
        $boleta = Boleta::forceCreate(['cargo_postulacion' => '¿Aprueba el contrato?', 'numero_boleta' => 1, 'convocatoria_id' => $c->id, 'nulos' => 0]);
        $si = BoletaCandidato::forceCreate(['curp' => 'Sí', 'nombre_completo' => 'Sí', 'planilla_color' => '#37ff00', 'boleta_id' => $boleta->id, 'convocatoria_id' => $c->id, 'conteo' => 0]);
        $no = BoletaCandidato::forceCreate(['curp' => 'No', 'nombre_completo' => 'No', 'planilla_color' => '#ff0000', 'boleta_id' => $boleta->id, 'convocatoria_id' => $c->id, 'conteo' => 0]);

        return [$c, $votante, $boleta, $si, $no];
    }

    private function enlaceDeVoto(ConvocatoriaPadron $votante): string
    {
        return '/voto/'.Crypt::encrypt($votante->id).'/cad/'.Crypt::encrypt($votante->cadena_validacion);
    }

    public function test_votacion_registra_un_solo_voto_por_boleta(): void
    {
        [$c, $votante, $boleta, $si] = $this->prepararConsulta();

        $this->get($this->enlaceDeVoto($votante))
            ->assertOk()->assertViewIs('voto.voto')->assertSee('¿Aprueba el contrato?');

        $this->get('/realizarVoto/'.Crypt::encrypt($votante->id).'/'.Crypt::encrypt($si->id))
            ->assertOk()->assertViewIs('voto.confirmacionVoto');

        $this->assertSame(1, Voto::count());
        $this->assertSame(1, Conteo::where('boleta_candidato_id', $si->id)->count());
        $this->assertSame(1, (int) $si->fresh()->conteo);

        $this->get('/realizarVoto/'.Crypt::encrypt($votante->id).'/'.Crypt::encrypt($si->id))
            ->assertViewHas('mensaje', 'Ya realizó el voto de esta boleta');
        $this->assertSame(1, Voto::count());

        $this->get($this->enlaceDeVoto($votante))->assertViewIs('voto.confirmacionVoto');
    }

    public function test_voto_nulo(): void
    {
        [$c, $votante, $boleta] = $this->prepararConsulta();

        $this->comoMovil()
            ->get('/anularVoto/'.Crypt::encrypt($votante->id).'/'.Crypt::encrypt($boleta->id))
            ->assertOk()->assertViewIs('voto.confirmacionVotoMovil');

        $this->assertSame(1, (int) $boleta->fresh()->nulos);
        $this->assertSame(1, Conteo::whereNull('boleta_candidato_id')->count());
    }

    public function test_voto_por_dos_cargos(): void
    {
        [$c, $votante, $boleta, $si, $no] = $this->prepararConsulta(['aprobacion_confirmacion_contrato' => null, 'seleccionar_dos_cargos' => true]);

        $this->get($this->enlaceDeVoto($votante))->assertViewIs('voto.votoCargos');

        $this->post('/realizarVotoPorCargo/'.Crypt::encrypt($votante->id).'/', ['seleccion1' => $si->id, 'seleccion2' => $no->id])
            ->assertViewIs('voto.confirmacionVoto');

        $this->assertSame(1, Voto::count());
        $this->assertSame(2, Conteo::count());
    }

    public function test_fuera_del_periodo_no_se_puede_votar(): void
    {
        [$c, $votante] = $this->prepararConsulta(['fecha_consulta_fin' => Carbon::now()->subMinute()->format('Y-m-d H:i:s')]);

        $this->get($this->enlaceDeVoto($votante))
            ->assertViewIs('errors.sinPeriodoRegistroVigente')
            ->assertViewHas('mensaje', 'No se cumplen las condiciones necesarias para votar en esta convocatoria');
    }

    public function test_acta_de_resultados_en_word(): void
    {
        [$c, $votante, $boleta, $si] = $this->prepararConsulta();
        Conteo::forceCreate(['boleta_id' => $boleta->id, 'convocatoria_id' => $c->id, 'boleta_candidato_id' => $si->id]);
        $c->forceFill(['convocatoria_estatus_id' => 5])->save();

        // Plantilla mínima de Word con los marcadores que reemplaza el sistema.
        mkdir(public_path('acta'), 0777, true);
        $zip = new ZipArchive;
        $zip->open(public_path('acta/Acta_de_Resultados.docx'), ZipArchive::CREATE);
        $zip->addFromString('word/document.xml', '<w:t>@nombreConvocatoria|@boleta|@votosAFavor|@votosEnContra|@votosNulos|@totalVotos|@derechoAVoto</w:t>');
        $zip->close();

        $respuesta = $this->actingAs($this->crearUsuario(2, ['convocatoria_id' => $c->id]))
            ->get('/consulta/descargaActa/'.$boleta->id);

        $respuesta->assertOk();
        $zip->open($respuesta->baseResponse->getFile()->getPathname());
        $this->assertSame('<w:t>Consulta de contrato colectivo|¿Aprueba el contrato?|1|0|0|1|1</w:t>', $zip->getFromName('word/document.xml'));
        $zip->close();
    }

    public function test_el_resultado_de_la_consulta_cuenta_votos(): void
    {
        [$c, $votante, $boleta, $si] = $this->prepararConsulta();
        Conteo::forceCreate(['boleta_id' => $boleta->id, 'convocatoria_id' => $c->id, 'boleta_candidato_id' => $si->id]);
        $c->forceFill(['convocatoria_estatus_id' => 5])->save();

        $this->actingAs($this->crearUsuario(2, ['convocatoria_id' => $c->id]))
            ->get('/consulta/index')
            ->assertOk()
            ->assertViewHas('resultadoFin', [1]);
    }
}
