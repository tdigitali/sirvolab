<?php

namespace Tests\Feature;

use App\Models\Bitacora;
use App\Models\Convocatoria;
use App\Models\ConvocatoriaAutorizacion;
use App\Models\ConvocatoriaPadron;
use App\Models\ConvocatoriaPadronCargado;
use App\Models\Usuario;
use App\Models\Voto;
use App\Services\Renapo;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Crypt;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Tests\TestCase;

class AdministracionTest extends TestCase
{
    private function datosConvocatoria(array $cambios = []): array
    {
        return array_merge([
            'nombre' => 'Legitimación de contrato',
            'nombre_sindicato' => 'Sindicato Nacional',
            'fecha_registro_validacion_inicio' => '2030-01-10',
            'fecha_registro_validacion_inicio_hora' => '08',
            'fecha_registro_validacion_inicio_minu' => '00',
            'fecha_registro_validacion_fin' => '2030-01-20',
            'fecha_registro_validacion_fin_hora' => '18',
            'fecha_registro_validacion_fin_minu' => '30',
            'fecha_consulta_inicio' => '2030-02-01',
            'fecha_consulta_inicio_hora' => '09',
            'fecha_consulta_inicio_minu' => '00',
            'fecha_consulta_fin' => '2030-02-02',
            'fecha_consulta_fin_hora' => '17',
            'fecha_consulta_fin_minu' => '00',
            'numero_autorizadores_minimo' => '2',
        ], $cambios);
    }

    public function test_crear_convocatoria_concatena_fechas_y_registra_bitacora(): void
    {
        $this->actingAs($this->crearUsuario(1))
            ->post('/convocatoria/save', $this->datosConvocatoria())
            ->assertRedirect('/convocatoria/index');

        $c = Convocatoria::firstOrFail();
        $this->assertSame(1, (int) $c->convocatoria_estatus_id);
        $this->assertSame('2030-01-10 08:00', substr($c->fecha_registro_validacion_inicio, 0, 16));
        $this->assertSame('2030-02-02 17:00', substr($c->fecha_consulta_fin, 0, 16));
        $this->assertTrue(Bitacora::where('accion_realizada', 'Creación de convocatoria')->exists());
    }

    public function test_crear_convocatoria_con_fechas_invertidas_regresa_al_formulario_con_datos(): void
    {
        $admin = $this->crearUsuario(1);

        $this->actingAs($admin)
            ->from('/convocatoria/create')
            ->post('/convocatoria/save', $this->datosConvocatoria(['fecha_consulta_fin' => '2030-01-01']))
            ->assertRedirect('/convocatoria/create')
            ->assertSessionHas('message');

        $this->assertSame(0, Convocatoria::count());

        // El formulario se vuelve a llenar con lo capturado (old input).
        $html = $this->get('/convocatoria/create')->assertOk()->getContent();
        $this->assertStringContainsString('value="Legitimación de contrato"', $html);
        $this->assertMatchesRegularExpression('/<option value="18" selected="selected">18<\/option>/', $html);
    }

    public function test_validacion_de_convocatoria_usa_mensajes_en_espanol(): void
    {
        $this->actingAs($this->crearUsuario(1))
            ->from('/convocatoria/create')
            ->post('/convocatoria/save', $this->datosConvocatoria(['nombre' => '']))
            ->assertSessionHasErrors(['nombre' => 'El campo de nombre es requerido.']);
    }

    public function test_usuario_no_administrador_no_puede_crear_convocatorias(): void
    {
        $this->actingAs($this->crearUsuario(4))
            ->post('/convocatoria/save', $this->datosConvocatoria())
            ->assertOk()
            ->assertViewIs('errors.permisoDenegado');

        $this->assertSame(0, Convocatoria::count());
    }

    public function test_alta_de_administrador_envia_correo(): void
    {
        $this->actingAs($this->crearUsuario(1))
            ->post('/usuario/save', [
                'nombre' => 'Ana',
                'apellido_paterno' => 'López',
                'apellido_materno' => '',
                'rol_id' => '1',
                'email' => 'ana@example.com',
            ])
            ->assertRedirect('/usuario/index');

        $usuario = Usuario::where('email', 'ana@example.com')->firstOrFail();
        $this->assertTrue((bool) $usuario->enabled);
        // apellido_materno vacío pasa la validación 'between:1,50' como en Laravel 4.
        $this->assertSame('', $usuario->apellido_materno);

        $correos = $this->correosEnviados();
        $this->assertCount(1, $correos);
        $this->assertSame('Enlace de acceso a SIRVOLAB', $correos[0]->getOriginalMessage()->getSubject());
    }

    public function test_asignar_comite_consulta_renapo_y_envia_enlace(): void
    {
        $c = $this->crearConvocatoria(1);

        $this->actingAs($this->crearUsuario(1))
            ->post('/convocatoria/asignarComiteUpdate/'.Crypt::encrypt($c->id), [
                'email' => ' comite@example.com ',
                'curp' => 'gaxx800101hdfxxx01',
                'rol_id' => '2',
                'correo_electronico_notificaciones' => 'avisos@example.com',
            ])
            ->assertRedirect('/convocatoria/asignarComite/'.$c->id);

        $u = Usuario::where('email', 'comite@example.com')->firstOrFail();
        $this->assertSame('GAXX800101HDFXXX01', $u->curp);
        $this->assertSame('PERSONA', $u->nombre);
        $this->assertSame($c->id, (int) $u->convocatoria_id);
        $this->assertSame('avisos@example.com', $this->correosEnviados()[0]->getEnvelope()->getRecipients()[0]->getAddress());
    }

    public function test_asignar_comite_con_curp_dada_de_baja(): void
    {
        Renapo::fake(fn () => ['statusCurp' => 'BD', 'statusOper' => 'EXITOSO']);
        $c = $this->crearConvocatoria(1);

        $this->actingAs($this->crearUsuario(1))
            ->post('/convocatoria/asignarComiteUpdate/'.Crypt::encrypt($c->id), [
                'email' => 'comite@example.com', 'curp' => 'GAXX800101HDFXXX01', 'rol_id' => '2',
                'correo_electronico_notificaciones' => 'avisos@example.com',
            ])
            ->assertSessionHas('message', 'La CURP está dada de baja por defunción en RENAPO');

        $this->assertFalse(Usuario::where('email', 'comite@example.com')->exists());
    }

    private function archivoPadron(array $filas): UploadedFile
    {
        $libro = new Spreadsheet;
        $hoja = $libro->getActiveSheet();
        $hoja->fromArray(['CURP', 'Centro de trabajo', 'Entidad federativa'], null, 'A1');
        $hoja->fromArray($filas, null, 'A2');
        $ruta = tempnam(sys_get_temp_dir(), 'padron').'.xlsx';
        IOFactory::createWriter($libro, 'Xlsx')->save($ruta);

        return new UploadedFile($ruta, 'padron.xlsx', null, null, true);
    }

    public function test_carga_de_padron_desde_excel(): void
    {
        $c = $this->crearConvocatoria(1);
        $archivo = $this->archivoPadron([
            ['aaaa800101hdfbbb01', 'Planta Norte', 'Ciudad de México'],
            ['BBBB800101HDFCCC02', 'Planta Sur', 'Ciudad de México'],
        ]);

        $this->actingAs($this->crearUsuario(1))
            ->post('/convocatoria/cargaPadronUpdate/'.Crypt::encrypt($c->id), ['ruta_padron' => $archivo])
            ->assertRedirect()
            ->assertSessionHas('message', 'Padrón Registrado');

        $padron = ConvocatoriaPadron::orderBy('id')->get();
        $this->assertSame(['AAAA800101HDFBBB01', 'BBBB800101HDFCCC02'], $padron->pluck('curp')->all());
        $this->assertSame('PERSONA DE PRUEBA', $padron[0]->nombre_completo);
        $this->assertSame(5, strlen($padron[0]->cadena_validacion));
        $this->assertSame(2, (int) ConvocatoriaPadronCargado::first()->registros_cargados);
        $this->assertFileExists(public_path(ConvocatoriaPadronCargado::first()->ruta));
    }

    public function test_carga_de_padron_se_detiene_en_curp_invalida(): void
    {
        $c = $this->crearConvocatoria(1);
        $archivo = $this->archivoPadron([
            ['AAAA800101HDFBBB01', 'Planta Norte', 'Ciudad de México'],
            ['CORTA', 'Planta Norte', 'Ciudad de México'],
        ]);

        $this->actingAs($this->crearUsuario(1))
            ->post('/convocatoria/cargaPadronUpdate/'.Crypt::encrypt($c->id), ['ruta_padron' => $archivo])
            ->assertSessionHas('message', 'Hay un valor incorrecto en la celda: A3, el CURP tiene un formato inválido: CORTA');

        $this->assertSame(1, ConvocatoriaPadron::count());
        $this->assertSame(1, (int) ConvocatoriaPadronCargado::first()->registros_cargados);
    }

    public function test_descarga_de_padron_en_excel(): void
    {
        $c = $this->crearConvocatoria(4);
        $votante = $this->crearVotante($c, ['fecha_validacion' => Carbon::now()]);
        Voto::forceCreate(['convocatoria_padron_id' => $votante->id, 'boleta_id' => 1, 'convocatoria_id' => $c->id]);
        $this->actingAs($this->crearUsuario(1));

        $respuesta = $this->get('/convocatoria/descargarTodo/'.Crypt::encrypt($c->id));
        $respuesta->assertOk();
        $respuesta->assertDownload('Descarga Padron.xls');
        $contenido = $respuesta->streamedContent();
        $this->assertStringStartsWith("\xD0\xCF\x11\xE0", $contenido, 'Debe ser un .xls (Excel 97-2003)');

        $ruta = tempnam(sys_get_temp_dir(), 'xls');
        file_put_contents($ruta, $contenido);
        $hoja = IOFactory::load($ruta)->getActiveSheet();
        $this->assertSame('CURP', $hoja->getCell('A1')->getValue());
        $this->assertSame($votante->curp, $hoja->getCell('A2')->getValue());
        $this->assertSame('Planta Norte', $hoja->getCell('C2')->getValue());
        $this->assertNotSame('', (string) $hoja->getCell('K2')->getValue());
    }

    public function test_autorizacion_de_fase_con_token(): void
    {
        $c = $this->crearConvocatoria(1);
        $this->crearVotante($c);
        $comite = $this->crearUsuario(2, [
            'convocatoria_id' => $c->id,
            'token_autorizacion' => 'TOKEN12345',
            'vigencia_token_autorizacion' => Carbon::now()->addHour(),
        ]);

        $this->actingAs($comite)
            ->get('/convocatoria/autorizacion/autorizarFaseUno/TOKEN12345')
            ->assertOk()
            ->assertViewHas('mensajeExito', 'Se ha realizado exitosamente la autorización.');

        $this->assertSame(2, (int) $c->fresh()->convocatoria_estatus_id);
        $this->assertTrue(ConvocatoriaAutorizacion::where('usuario_id', $comite->id)->exists());

        // Segundo intento: antes usaba compact("x", $x), que en PHP 8 fallaba.
        $this->get('/convocatoria/autorizacion/autorizarFaseUno/TOKEN12345')
            ->assertOk()
            ->assertViewIs('errors.autorizacionDenegada')
            ->assertSee('Usted ya autorizó esta fase');
    }

    public function test_autorizacion_con_token_incorrecto(): void
    {
        $c = $this->crearConvocatoria(1);
        $comite = $this->crearUsuario(2, ['convocatoria_id' => $c->id, 'token_autorizacion' => 'BUENO', 'vigencia_token_autorizacion' => Carbon::now()->addHour()]);

        $this->actingAs($comite)
            ->get('/convocatoria/autorizacion/autorizarFaseUno/MALO')
            ->assertViewHas('mensajeDeValidacion', 'El token ingresado no es correcto, intente nuevamente.');

        $this->assertSame(1, (int) $c->fresh()->convocatoria_estatus_id);
    }

    public function test_cerrar_convocatoria_desactiva_usuarios(): void
    {
        $c = $this->crearConvocatoria(5);
        $comite = $this->crearUsuario(2, ['convocatoria_id' => $c->id]);

        $this->actingAs($this->crearUsuario(1))
            ->get('/consulta/cerrarConvocatoria/'.$c->id)
            ->assertRedirect('/convocatoria/indexCerradas');

        $this->assertSame(6, (int) $c->fresh()->convocatoria_estatus_id);
        $this->assertNull($comite->fresh()->enabled);
        $this->assertNull($comite->fresh()->convocatoria_id);
    }
}
