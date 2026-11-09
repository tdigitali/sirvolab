<?php

namespace Tests;

use App\Models\Ambiente;
use App\Models\Convocatoria;
use App\Models\ConvocatoriaCentroTrabajo;
use App\Models\ConvocatoriaPadron;
use App\Models\EntidadFederativa;
use App\Models\MotivoRechazo;
use App\Models\Rol;
use App\Models\Usuario;
use App\Services\Renapo;
use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    public const UA_ESCRITORIO = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/130.0 Safari/537.36';

    protected string $rutaPublica;

    public const UA_MOVIL = 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148';

    protected function setUp(): void
    {
        parent::setUp();

        // Las cargas (INE, padrón, actas) se guardan bajo public_path(): en las
        // pruebas se usa un directorio temporal para no ensuciar public/.
        $this->rutaPublica = sys_get_temp_dir().'/sirvolab-public-'.uniqid();
        mkdir($this->rutaPublica, 0777, true);
        $this->app->usePublicPath($this->rutaPublica);

        $this->withHeader('User-Agent', self::UA_ESCRITORIO);
        $this->sembrarCatalogos();

        // Nunca llamar al servicio real de RENAPO desde las pruebas.
        Renapo::fake(fn (string $curp) => [
            'CURP' => $curp,
            'nombres' => 'PERSONA',
            'apellido1' => 'DE',
            'apellido2' => 'PRUEBA',
            'statusCurp' => 'RCN',
            'statusOper' => 'EXITOSO',
        ]);
    }

    protected function tearDown(): void
    {
        Renapo::fake(null);
        (new Filesystem)->deleteDirectory($this->rutaPublica);
        parent::tearDown();
    }

    /**
     * Candado: las pruebas reinician la base de datos antes de correr. Nunca
     * deben hacerlo sobre otra cosa que SQLite en memoria (por ejemplo, si
     * alguien corre las pruebas en un servidor o contenedor con
     * DB_CONNECTION=mysql en sus variables de entorno).
     */
    protected function beforeRefreshingDatabase(): void
    {
        $conexion = config('database.default');
        $base = config("database.connections.$conexion.database");

        if ($conexion !== 'sqlite' || $base !== ':memory:') {
            throw new \RuntimeException(
                "Pruebas detenidas: usarían la base '$base' (conexión '$conexion'). "
                .'Solo se permiten con SQLite en memoria para no borrar datos reales.'
            );
        }
    }

    /**
     * Usa el esquema inferido de tests/database/migrations (el proyecto no
     * trae migraciones de sus tablas reales).
     */
    protected function migrateFreshUsing(): array
    {
        return [
            '--drop-views' => false,
            '--path' => 'tests/database/migrations',
        ];
    }

    protected function sembrarCatalogos(): void
    {
        Ambiente::forceCreate(['id' => 1, 'nombre' => 'http://localhost/']);

        foreach ([1 => 'Administrador SIRVOLAB', 2 => 'Administrador de procesos', 3 => 'Comité', 4 => 'Validador'] as $id => $nombre) {
            Rol::forceCreate(compact('id', 'nombre'));
        }

        foreach ([1 => 'Configuración', 2 => 'Registro y validación', 3 => 'Configuración de boletas', 4 => 'Consulta', 5 => 'Resultados', 6 => 'Cerrada'] as $id => $nombre) {
            DB::table('convocatoria_estatus')->insert(compact('id', 'nombre'));
        }

        EntidadFederativa::forceCreate(['id' => 9, 'nombre' => 'Ciudad de México']);
        MotivoRechazo::forceCreate(['id' => 1, 'nombre' => 'La identificación no es legible']);
    }

    protected function crearUsuario(int $rol, array $atributos = []): Usuario
    {
        return Usuario::forceCreate(array_merge([
            'nombre' => 'Usuario',
            'apellido_paterno' => 'Rol'.$rol,
            'apellido_materno' => 'Prueba',
            'email' => 'rol'.$rol.'-'.uniqid().'@example.com',
            'correo_electronico_notificaciones' => 'notificaciones'.$rol.'@example.com',
            'curp' => 'XEXX010101HDFXXX0'.$rol,
            'password' => Hash::make('secreto123'),
            'rol_id' => $rol,
            'enabled' => true,
        ], $atributos));
    }

    protected function crearConvocatoria(int $estatus, array $atributos = []): Convocatoria
    {
        return Convocatoria::forceCreate(array_merge([
            'nombre' => 'Consulta de contrato colectivo',
            'nombre_sindicato' => 'Sindicato de Prueba',
            'fecha_registro_validacion_inicio' => Carbon::now()->subDays(2)->format('Y-m-d H:i:s'),
            'fecha_registro_validacion_fin' => Carbon::now()->addDays(2)->format('Y-m-d H:i:s'),
            'fecha_consulta_inicio' => Carbon::now()->subDay()->format('Y-m-d H:i:s'),
            'fecha_consulta_fin' => Carbon::now()->addDay()->format('Y-m-d H:i:s'),
            'convocatoria_estatus_id' => $estatus,
            'convocatoria_activa' => true,
            'numero_autorizadores_minimo' => 1,
            'aprobacion_confirmacion_contrato' => true,
        ], $atributos));
    }

    protected function crearVotante(Convocatoria $convocatoria, array $atributos = []): ConvocatoriaPadron
    {
        $centro = ConvocatoriaCentroTrabajo::forceCreate(['nombre' => 'Planta Norte', 'convocatoria_id' => $convocatoria->id]);

        return ConvocatoriaPadron::forceCreate(array_merge([
            'curp' => 'AAAA800101HDFBBB0'.random_int(0, 9),
            'nombre_completo' => 'JUAN PEREZ LOPEZ',
            'centro_trabajo_id' => $centro->id,
            'entidad_federativa_id' => 9,
            'convocatoria_id' => $convocatoria->id,
            'fecha_carga' => Carbon::now()->format('Y-m-d H:i:s'),
            'cadena_validacion' => 'abcde',
        ], $atributos));
    }

    protected function comoMovil(): static
    {
        return $this->withHeader('User-Agent', self::UA_MOVIL);
    }

    /** Correos enviados con el transporte 'array' (MAIL_MAILER=array en phpunit.xml). */
    protected function correosEnviados(): array
    {
        return app('mailer')->getSymfonyTransport()->messages()->all();
    }
}
