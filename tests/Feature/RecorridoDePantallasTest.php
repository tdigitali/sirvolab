<?php

namespace Tests\Feature;

use App\Models\Bitacora;
use App\Models\Boleta;
use App\Models\BoletaCandidato;
use App\Models\Convocatoria;
use App\Models\ConvocatoriaPadronCargado;
use App\Models\Voto;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Recorre todas las pantallas (GET) del sistema con cada rol, en escritorio
 * y en móvil, y verifica que ninguna termine en error 500. Es la red de
 * seguridad principal de la migración: cualquier incompatibilidad de PHP 8
 * o de Laravel 13 en controladores o vistas aparece aquí.
 */
class RecorridoDePantallasTest extends TestCase
{
    private Convocatoria $convocatoria;

    private array $ids = [];

    private function prepararDatos(int $estatus): void
    {
        $this->convocatoria = $this->crearConvocatoria($estatus);
        $c = $this->convocatoria;

        $registrado = $this->crearVotante($c, [
            'fecha_registro' => Carbon::now()->format('Y-m-d H:i:s'),
            'correo_electronico' => 'votante@example.com',
        ]);
        $validado = $this->crearVotante($c, [
            'curp' => 'BBBB800101HDFCCC01',
            'fecha_registro' => Carbon::now()->format('Y-m-d H:i:s'),
            'fecha_validacion' => Carbon::now()->format('Y-m-d H:i:s'),
            'correo_electronico' => 'validado@example.com',
        ]);
        $this->crearVotante($c, [
            'curp' => 'CCCC800101HDFDDD02',
            'fecha_rechazo' => Carbon::now()->format('Y-m-d H:i:s'),
            'motivo_rechazo_id' => 1,
        ]);
        $boleta = Boleta::forceCreate(['cargo_postulacion' => 'Secretario General', 'numero_boleta' => 1, 'convocatoria_id' => $c->id, 'nulos' => 0]);
        $candidato = BoletaCandidato::forceCreate(['curp' => 'Sí', 'nombre_completo' => 'Sí', 'planilla_color' => '#37ff00', 'boleta_id' => $boleta->id, 'convocatoria_id' => $c->id]);
        BoletaCandidato::forceCreate(['curp' => 'No', 'nombre_completo' => 'No', 'planilla_color' => '#ff0000', 'boleta_id' => $boleta->id, 'convocatoria_id' => $c->id]);
        Voto::forceCreate(['convocatoria_padron_id' => $validado->id, 'boleta_id' => $boleta->id, 'convocatoria_id' => $c->id]);
        $cargado = ConvocatoriaPadronCargado::forceCreate(['ruta' => '/archivosPadron/x.xlsx', 'convocatoria_id' => $c->id, 'fecha_carga' => Carbon::now(), 'registros_cargados' => 3]);
        Bitacora::forceCreate(['accion_realizada' => 'Creación de convocatoria', 'fecha_accion' => Carbon::now(), 'accion_realizada_id' => $c->id, 'modulo_id' => 2, 'usuario_id' => 1]);

        $this->ids = compact('registrado', 'validado', 'boleta', 'candidato', 'cargado');
    }

    /** Rutas GET protegidas, con parámetros ya resueltos. */
    private function rutasProtegidas(): array
    {
        $c = $this->convocatoria;
        $enc = fn ($v) => Crypt::encrypt($v);
        $usuarioComite = $this->ids['comite'];

        $rutas = [
            '/menu/principal', '/menu/comite', '/menu/validador',
            '/usuario/index', '/usuario/create', '/usuario/edit/'.$usuarioComite->id,
            '/usuario/bitacora/'.$usuarioComite->id,
            '/convocatoria/index', '/convocatoria/indexCerradas',
            '/convocatoria/indexPadron/'.$enc($c->id),
            '/convocatoria/indexPadronComite/',
            '/convocatoria/cargaPadron/'.$enc($c->id),
            '/convocatoria/create', '/convocatoria/edit/'.$c->id, '/convocatoria/show/'.$c->id,
            '/convocatoria/asignarComite/'.$c->id, '/convocatoria/asignarValidador',
            '/convocatoria/autorizacion/',
            '/convocatoria/bitacora/'.$c->id, '/convocatoria/bitacoraShow/'.$c->id,
            '/convocatoria/busquedaPorCURP/',
            '/convocatoria/terminarConvocatoria/'.$enc($c->id),
            '/validacion/',
            '/boleta/index', '/boleta/create', '/boleta/edit/'.$this->ids['boleta']->id,
            '/boleta/asignarCandidato/'.$this->ids['boleta']->id,
            '/consulta/index',
        ];

        foreach ([1, 2, 3, 4, 5] as $tipo) {
            $rutas[] = '/convocatoria/indexPadronConsulta/'.$enc($c->id).'/t/'.$tipo;
            $rutas[] = '/convocatoria/indexPadronComiteConsulta/'.$tipo;
        }

        return $rutas;
    }

    public static function casos(): array
    {
        $casos = [];
        foreach ([1, 2, 3, 4] as $rol) {
            foreach ([1, 2, 3, 4, 5] as $estatus) {
                foreach (['escritorio', 'movil'] as $dispositivo) {
                    $casos["rol $rol / estatus $estatus / $dispositivo"] = [$rol, $estatus, $dispositivo === 'movil'];
                }
            }
        }

        return $casos;
    }

    #[DataProvider('casos')]
    public function test_ninguna_pantalla_falla(int $rol, int $estatus, bool $movil): void
    {
        $this->prepararDatos($estatus);
        $this->ids['comite'] = $this->crearUsuario(2, ['convocatoria_id' => $this->convocatoria->id]);
        $this->crearUsuario(3, ['convocatoria_id' => $this->convocatoria->id]);
        $this->crearUsuario(4, ['convocatoria_id' => $this->convocatoria->id]);

        $usuario = $rol === 2
            ? $this->ids['comite']
            : $this->crearUsuario($rol, ['convocatoria_id' => $rol === 1 ? null : $this->convocatoria->id]);

        if ($movil) {
            $this->comoMovil();
        }
        $this->actingAs($usuario);

        $errores = [];
        foreach ($this->rutasProtegidas() as $ruta) {
            $respuesta = $this->get($ruta);
            if ($respuesta->getStatusCode() >= 500) {
                $errores[] = $ruta.' => '.$respuesta->getStatusCode().' '.$this->resumenExcepcion($respuesta);
            }
        }

        $this->assertSame([], $errores, implode("\n", $errores));
    }

    public function test_pantallas_publicas_no_fallan(): void
    {
        foreach ([2, 4] as $estatus) {
            $this->prepararDatos($estatus);
            $p = $this->ids['validado'];
            $rutas = [
                '/', '/login/', '/registrarse/',
                '/registro/'.Crypt::encrypt($this->convocatoria->id).'/',
                '/registro/no-es-valido/',
                '/voto/'.Crypt::encrypt($p->id).'/cad/'.Crypt::encrypt($p->cadena_validacion),
                '/formularioEdit/'.Crypt::encrypt($p->id).'/cad/'.Crypt::encrypt($p->cadena_validacion),
                '/cambiarContrasena/u/correo@example.com/d/abc',
            ];
            foreach ([false, true] as $movil) {
                if ($movil) {
                    $this->comoMovil();
                }
                foreach ($rutas as $ruta) {
                    $respuesta = $this->get($ruta);
                    $this->assertLessThan(500, $respuesta->getStatusCode(), $ruta.' '.$this->resumenExcepcion($respuesta));
                }
            }
        }
    }

    private function resumenExcepcion($respuesta): string
    {
        $e = $respuesta->exception;

        return $e ? get_class($e).': '.mb_substr($e->getMessage(), 0, 300) : '';
    }
}
