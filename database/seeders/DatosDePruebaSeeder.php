<?php

namespace Database\Seeders;

use App\Models\Ambiente;
use App\Models\Bitacora;
use App\Models\Boleta;
use App\Models\BoletaCandidato;
use App\Models\Conteo;
use App\Models\Convocatoria;
use App\Models\ConvocatoriaAutorizacion;
use App\Models\ConvocatoriaCentroTrabajo;
use App\Models\ConvocatoriaPadron;
use App\Models\ConvocatoriaPadronCargado;
use App\Models\EntidadFederativa;
use App\Models\MotivoRechazo;
use App\Models\Rol;
use App\Models\Usuario;
use App\Models\Voto;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use RuntimeException;
use ZipArchive;

/**
 * Datos INVENTADOS para el servidor de pruebas: usuarios de cada rol,
 * una convocatoria en cada fase, padrón con CURPs ficticias, boletas,
 * votos y archivos de ejemplo. Ningún dato corresponde a personas reales.
 *
 *   php artisan db:seed --class=DatosDePruebaSeeder
 *
 * Por seguridad se niega a correr en producción o sobre una base que ya
 * tenga usuarios.
 */
class DatosDePruebaSeeder extends Seeder
{
    public const CONTRASENA = 'Pruebas2026!';

    private Generator $faker;

    private string $hash;

    public function run(): void
    {
        if (app()->environment('production')) {
            throw new RuntimeException('DatosDePruebaSeeder no se ejecuta con APP_ENV=production.');
        }
        if (DB::table('usuario')->exists()) {
            throw new RuntimeException('La tabla usuario ya tiene registros: los datos de prueba solo se cargan en una base vacía.');
        }

        $this->faker = Faker::create('es_MX');
        $this->faker->seed(2026);
        $this->hash = Hash::make(self::CONTRASENA);

        $this->catalogos();
        $this->archivosGenerales();

        Usuario::forceCreate($this->datosUsuario('admin@pruebas.test', 1, null, ['nombre' => 'Administrador', 'apellido_paterno' => 'De Pruebas']));

        $ahora = Carbon::now();
        $fases = [
            1 => ['Configuración', 'contrato'],
            2 => ['Registro y validación', 'contrato'],
            3 => ['Configuración de boletas', 'contrato'],
            4 => ['Consulta (aprobación de contrato)', 'contrato'],
            5 => ['Resultados y cierre', 'contrato'],
            6 => ['Cerrada', 'contrato'],
        ];
        $numero = 1;
        foreach ($fases as $estatus => [$descripcion, $tipo]) {
            $this->convocatoria($numero++, $estatus, $descripcion, $tipo, $ahora);
        }
        // Consulta de elección de cargos (se eligen dos candidatos).
        $this->convocatoria($numero, 4, 'Consulta (elección de directiva)', 'cargos', $ahora);
    }

    private function catalogos(): void
    {
        if (! Rol::exists()) {
            foreach ([1 => 'Administrador SIRVOLAB', 2 => 'Administrador de procesos', 3 => 'Comité', 4 => 'Validador'] as $id => $nombre) {
                Rol::forceCreate(compact('id', 'nombre'));
            }
        }
        if (! DB::table('convocatoria_estatus')->exists()) {
            foreach ([1 => 'Configuración de convocatoria', 2 => 'Registro y validación', 3 => 'Configuración de boleta(s)', 4 => 'Consulta', 5 => 'Resultados y cierre', 6 => 'Cerrada'] as $id => $nombre) {
                DB::table('convocatoria_estatus')->insert(compact('id', 'nombre') + ['created_at' => now(), 'updated_at' => now()]);
            }
        }
        if (! EntidadFederativa::exists()) {
            $entidades = ['Aguascalientes', 'Baja California', 'Baja California Sur', 'Campeche', 'Coahuila', 'Colima', 'Chiapas', 'Chihuahua',
                'Ciudad de México', 'Durango', 'Guanajuato', 'Guerrero', 'Hidalgo', 'Jalisco', 'Estado de México', 'Michoacán', 'Morelos',
                'Nayarit', 'Nuevo León', 'Oaxaca', 'Puebla', 'Querétaro', 'Quintana Roo', 'San Luis Potosí', 'Sinaloa', 'Sonora', 'Tabasco',
                'Tamaulipas', 'Tlaxcala', 'Veracruz', 'Yucatán', 'Zacatecas'];
            foreach ($entidades as $i => $nombre) {
                EntidadFederativa::forceCreate(['id' => $i + 1, 'nombre' => $nombre]);
            }
        }
        if (! MotivoRechazo::exists()) {
            foreach (['La identificación no es legible', 'La fotografía no coincide con la identificación', 'La identificación está vencida', 'Los datos no coinciden con el padrón'] as $i => $nombre) {
                MotivoRechazo::forceCreate(['id' => $i + 1, 'nombre' => $nombre]);
            }
        }
        // Dirección base que el sistema usa para armar los enlaces de los correos.
        Ambiente::updateOrCreate(['id' => 1], ['nombre' => rtrim(config('app.url'), '/').'/']);
    }

    private function convocatoria(int $n, int $estatus, string $descripcion, string $tipo, Carbon $ahora): void
    {
        // Fechas coherentes con la fase en la que está la convocatoria.
        $desplazamiento = [1 => 10, 2 => 0, 3 => -5, 4 => -10, 5 => -20, 6 => -40][$estatus];
        $base = $ahora->copy()->addDays($desplazamiento);
        $fechas = [
            'fecha_registro_validacion_inicio' => $base->copy()->subDays(3)->setTime(8, 0),
            'fecha_registro_validacion_fin' => $base->copy()->addDays(3)->setTime(18, 0),
            'fecha_consulta_inicio' => $base->copy()->addDays($estatus === 4 ? 9 : 7)->setTime(8, 0),
            'fecha_consulta_fin' => $base->copy()->addDays($estatus === 4 ? 12 : 8)->setTime(18, 0),
        ];
        if ($estatus === 4) {
            $fechas['fecha_registro_validacion_inicio'] = $ahora->copy()->subDays(15)->setTime(8, 0);
            $fechas['fecha_registro_validacion_fin'] = $ahora->copy()->subDays(8)->setTime(18, 0);
            $fechas['fecha_consulta_inicio'] = $ahora->copy()->subDay()->setTime(8, 0);
            $fechas['fecha_consulta_fin'] = $ahora->copy()->addDays(2)->setTime(18, 0);
        }

        $c = Convocatoria::forceCreate(array_merge(array_map(fn ($f) => $f->format('Y-m-d H:i:s'), $fechas), [
            'nombre' => "Prueba $n - $descripcion",
            'nombre_sindicato' => 'Sindicato Ficticio de '.$this->faker->randomElement(['Trabajadores de la Industria', 'Empleados de Servicios', 'Obreros del Ramo', 'Trabajadores Unidos']).' '.$n,
            'convocatoria_estatus_id' => $estatus,
            'convocatoria_activa' => true,
            'numero_autorizadores_minimo' => 2,
            'aprobacion_confirmacion_contrato' => $tipo === 'contrato' ? true : null,
            'seleccionar_dos_cargos' => $tipo === 'cargos' ? true : null,
            'razon_termino' => null,
        ]));
        $this->bitacora('Creación de convocatoria', $c->id, 1);

        // Comité: administrador de procesos, dos miembros y dos validadores.
        $cerrada = $estatus === 6;
        $comite = [];
        foreach ([[2, 'procesos'], [3, 'comite1'], [3, 'comite2'], [4, 'validador1'], [4, 'validador2']] as [$rol, $alias]) {
            $comite[$alias] = Usuario::forceCreate($this->datosUsuario("c$n.$alias@pruebas.test", $rol, $cerrada ? null : $c->id, $cerrada ? ['enabled' => null] : []));
        }
        // Autorizaciones de las fases ya superadas (el mínimo es de 2 autorizadores).
        for ($fase = 1; $fase < min($estatus, 5); $fase++) {
            foreach (['procesos', 'comite1'] as $alias) {
                ConvocatoriaAutorizacion::forceCreate(['usuario_id' => $comite[$alias]->id, 'convocatoria_id' => $c->id, 'fecha_autorizacion' => $base->copy()->subDays(10 - $fase), 'fase_autorizada' => $fase]);
            }
        }

        $centros = collect(['Planta Norte', 'Planta Sur', 'Oficinas Centrales'])
            ->map(fn ($nombre) => ConvocatoriaCentroTrabajo::forceCreate(['nombre' => $nombre, 'convocatoria_id' => $c->id]));

        $votantes = $this->padron($c, $estatus, $centros->all(), $comite['validador1']);
        if ($estatus >= 3) {
            $this->boletas($c, $tipo, $estatus, $votantes);
        }
        if ($cerrada) {
            $c->forceFill(['ruta_acta' => '/actaFirmada/'.$c->id.'/acta_firmada_prueba.docx'])->save();
            File::ensureDirectoryExists(public_path('actaFirmada/'.$c->id));
            File::copy(public_path('acta/Acta_de_Resultados.docx'), public_path('actaFirmada/'.$c->id.'/acta_firmada_prueba.docx'));
        }
    }

    /** @return ConvocatoriaPadron[] votantes validados */
    private function padron(Convocatoria $c, int $estatus, array $centros, Usuario $validador): array
    {
        $ruta = '/archivos/'.$c->id.'/';
        File::ensureDirectoryExists(public_path($ruta));
        $imagenes = [];
        foreach (['ine_frente', 'ine_reverso', 'selfie'] as $tipo) {
            $imagenes[$tipo] = $ruta.$tipo.'_prueba.png';
            $this->imagenDePrueba(public_path($imagenes[$tipo]), strtoupper(str_replace('_', ' ', $tipo)));
        }

        $validados = [];
        $filasExcel = [];
        for ($i = 1; $i <= 40; $i++) {
            $sexo = $this->faker->randomElement(['male', 'female']);
            $nombre = mb_strtoupper($this->faker->firstName($sexo).' '.$this->faker->lastName().' '.$this->faker->lastName());
            $entidad = $this->faker->numberBetween(1, 32);
            $centro = $this->faker->randomElement($centros);
            $curp = $this->curp($sexo === 'male' ? 'H' : 'M');
            $filasExcel[] = [$curp, $centro->nombre, EntidadFederativa::find($entidad)->nombre];

            $datos = [
                'curp' => $curp,
                'nombre_completo' => $nombre,
                'centro_trabajo_id' => $centro->id,
                'entidad_federativa_id' => $entidad,
                'convocatoria_id' => $c->id,
                'fecha_carga' => now()->subDays(20),
                'cadena_validacion' => $this->faker->regexify('[A-Za-z0-9]{5}'),
            ];

            // A partir de la fase 2 una parte del padrón ya se registró.
            $situacion = $estatus === 1 ? 'cargado' : $this->faker->randomElement(['cargado', 'registrado', 'validado', 'validado', 'validado', 'rechazado']);
            if ($estatus === 2 && $situacion === 'validado' && $i % 3 === 0) {
                $situacion = 'registrado';
            }
            if ($situacion !== 'cargado') {
                $datos += [
                    'fecha_registro' => now()->subDays(12),
                    'correo_electronico' => "votante$i.c{$c->id}@pruebas.test",
                    'ruta_ine_frente' => $imagenes['ine_frente'],
                    'ruta_ine_reverso' => $imagenes['ine_reverso'],
                    'ruta_selfie_con_ine' => $imagenes['selfie'],
                ];
            }
            if ($situacion === 'validado') {
                $datos += ['validador_id' => $validador->id, 'fecha_validacion' => now()->subDays(11)];
            }
            if ($situacion === 'rechazado') {
                $datos += ['validador_id' => $validador->id, 'motivo_rechazo_id' => $this->faker->numberBetween(1, 4), 'fecha_rechazo' => now()->subDays(11)];
            }

            $votante = ConvocatoriaPadron::forceCreate($datos);
            if ($situacion === 'validado') {
                $validados[] = $votante;
            }
        }

        // Archivo de padrón "cargado", con el mismo formato que espera el sistema.
        $libro = new Spreadsheet;
        $libro->getActiveSheet()->fromArray([['CURP', 'Centro de trabajo', 'Entidad federativa'], ...$filasExcel]);
        File::ensureDirectoryExists(public_path('archivosPadron/'.$c->id));
        IOFactory::createWriter($libro, 'Xlsx')->save(public_path('archivosPadron/'.$c->id.'/padron_prueba.xlsx'));
        ConvocatoriaPadronCargado::forceCreate(['ruta' => '/archivosPadron/'.$c->id.'/padron_prueba.xlsx', 'convocatoria_id' => $c->id, 'fecha_carga' => now()->subDays(20), 'registros_cargados' => 40]);
        $this->bitacora('Padrón cargado', $c->id, 2);

        return $validados;
    }

    private function boletas(Convocatoria $c, string $tipo, int $estatus, array $validados): void
    {
        if ($tipo === 'contrato') {
            $boleta = Boleta::forceCreate(['cargo_postulacion' => '¿Aprueba el contenido del contrato colectivo de trabajo?', 'numero_boleta' => 1, 'convocatoria_id' => $c->id, 'nulos' => 0]);
            $opciones = [['Sí', '#37ff00'], ['No', '#ff0000']];
        } else {
            $boleta = Boleta::forceCreate(['cargo_postulacion' => 'Secretaría General', 'numero_boleta' => 1, 'convocatoria_id' => $c->id, 'nulos' => 0]);
            $opciones = [];
            foreach (['#1f77b4', '#ff7f0e', '#2ca02c', '#9467bd'] as $color) {
                $opciones[] = [mb_strtoupper($this->faker->firstName().' '.$this->faker->lastName()), $color];
            }
        }

        $candidatos = [];
        foreach ($opciones as [$nombre, $color]) {
            $candidatos[] = BoletaCandidato::forceCreate([
                'curp' => $tipo === 'contrato' ? $nombre : $this->curp('H'),
                'nombre_completo' => $nombre,
                'planilla_color' => $color,
                'boleta_id' => $boleta->id,
                'convocatoria_id' => $c->id,
                'conteo' => 0,
            ]);
        }

        if ($estatus < 4) {
            return;
        }

        // En consulta vota la mitad; en resultados y cerrada, casi todos.
        $porcentaje = $estatus === 4 ? 50 : 85;
        foreach ($validados as $votante) {
            if ($this->faker->numberBetween(1, 100) > $porcentaje) {
                continue;
            }
            Voto::forceCreate(['convocatoria_padron_id' => $votante->id, 'boleta_id' => $boleta->id, 'convocatoria_id' => $c->id]);

            if ($this->faker->numberBetween(1, 100) <= 5) {
                $boleta->increment('nulos');
                Conteo::forceCreate(['boleta_id' => $boleta->id, 'convocatoria_id' => $c->id, 'boleta_candidato_id' => null]);

                continue;
            }
            $elegidos = $tipo === 'cargos' ? $this->faker->randomElements($candidatos, 2) : [$this->faker->randomElement($candidatos)];
            foreach ($elegidos as $candidato) {
                $candidato->increment('conteo');
                Conteo::forceCreate(['boleta_id' => $boleta->id, 'convocatoria_id' => $c->id, 'boleta_candidato_id' => $candidato->id]);
            }
        }
    }

    private function datosUsuario(string $email, int $rol, ?int $convocatoria, array $extra = []): array
    {
        $sexo = $this->faker->randomElement(['male', 'female']);

        return array_merge([
            'nombre' => mb_strtoupper($this->faker->firstName($sexo)),
            'apellido_paterno' => mb_strtoupper($this->faker->lastName()),
            'apellido_materno' => mb_strtoupper($this->faker->lastName()),
            'curp' => $this->curp($sexo === 'male' ? 'H' : 'M'),
            'email' => $email,
            'correo_electronico_notificaciones' => $email,
            'password' => $this->hash,
            'verify_password' => $this->faker->regexify('[A-Za-z0-9]{10}'),
            'rol_id' => $rol,
            'enabled' => true,
            'default_password' => null,
            'convocatoria_id' => $convocatoria,
        ], $extra);
    }

    /** CURP con formato válido y datos al azar (no corresponde a nadie). */
    private function curp(string $sexo): string
    {
        $estados = ['AS', 'BC', 'CL', 'CS', 'DF', 'GT', 'JC', 'MC', 'NL', 'OC', 'PL', 'QT', 'SP', 'VZ', 'YN'];

        return strtoupper(
            $this->faker->regexify('[A-Z][AEIOU][A-Z]{2}')
            .$this->faker->dateTimeBetween('-60 years', '-18 years')->format('ymd')
            .$sexo
            .$this->faker->randomElement($estados)
            .$this->faker->regexify('[B-DF-HJ-NP-TV-Z]{3}[0-9A-Z][0-9]')
        );
    }

    private function bitacora(string $accion, int $id, int $modulo): void
    {
        Bitacora::forceCreate(['accion_realizada' => $accion, 'fecha_accion' => now(), 'accion_realizada_id' => $id, 'modulo_id' => $modulo, 'usuario_id' => 1]);
    }

    private function imagenDePrueba(string $ruta, string $texto): void
    {
        $img = imagecreatetruecolor(640, 400);
        imagefill($img, 0, 0, imagecolorallocate($img, 220, 226, 224));
        $tinta = imagecolorallocate($img, 16, 49, 43);
        imagerectangle($img, 10, 10, 629, 389, $tinta);
        imagestring($img, 5, 200, 170, $texto, $tinta);
        imagestring($img, 5, 170, 200, 'DOCUMENTO DE PRUEBA - NO ES REAL', $tinta);
        imagepng($img, $ruta);
        imagedestroy($img);
    }

    /**
     * Plantillas de acta y aviso de privacidad de ejemplo. Las reales de
     * producción no están en el repositorio; si se copian a public/acta y
     * public/archivos, no se sobrescriben.
     */
    private function archivosGenerales(): void
    {
        File::ensureDirectoryExists(public_path('acta'));
        $marcadores = ['@nombreConvocatoria', '@sindicato', '@boleta', '@fechaInicial', '@fechaFinal', '@adminProcesos', '@comite',
            '@derechoAVoto', '@personasRegistradas', '@votosAFavor', '@votosEnContra', '@votosNulos', '@totalVotos', '@fechaActual'];
        $this->docx(public_path('acta/Acta_de_Resultados.docx'), 'ACTA DE RESULTADOS (PLANTILLA DE PRUEBA)', $marcadores);
        $cargos = array_merge($marcadores, array_map(fn ($i) => "@canResultado$i", range(1, 9)));
        $this->docx(public_path('acta/Acta_de_Resultados_Cargos.docx'), 'ACTA DE RESULTADOS POR CARGOS (PLANTILLA DE PRUEBA)', $cargos);

        File::ensureDirectoryExists(public_path('archivos'));
        if (! File::exists(public_path('archivos/aviso_privacidad_SIRVOLAB.pdf'))) {
            File::put(public_path('archivos/aviso_privacidad_SIRVOLAB.pdf'), $this->pdf('Aviso de privacidad de PRUEBA (no es el documento oficial).'));
        }
    }

    private function docx(string $ruta, string $titulo, array $marcadores): void
    {
        if (File::exists($ruta)) {
            return;
        }
        $parrafo = fn (string $texto) => '<w:p><w:r><w:t xml:space="preserve">'.htmlspecialchars($texto, ENT_XML1).'</w:t></w:r></w:p>';
        $cuerpo = $parrafo($titulo);
        foreach ($marcadores as $m) {
            // Etiqueta y marcador en el mismo <w:t> para que str_replace los encuentre.
            $cuerpo .= $parrafo(ltrim($m, '@').': '.$m);
        }

        $zip = new ZipArchive;
        $zip->open($ruta, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $zip->addFromString('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types"><Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/><Default Extension="xml" ContentType="application/xml"/><Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/></Types>');
        $zip->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships"><Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/></Relationships>');
        $zip->addFromString('word/document.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?><w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"><w:body>'.$cuerpo.'</w:body></w:document>');
        $zip->close();
    }

    private function pdf(string $texto): string
    {
        $contenido = 'BT /F1 14 Tf 72 720 Td ('.addcslashes($texto, '()\\').') Tj ET';
        $objetos = [
            '<< /Type /Catalog /Pages 2 0 R >>',
            '<< /Type /Pages /Kids [3 0 R] /Count 1 >>',
            '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R /Resources << /Font << /F1 5 0 R >> >> >>',
            '<< /Length '.strlen($contenido)." >>\nstream\n$contenido\nendstream",
            '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>',
        ];
        $pdf = "%PDF-1.4\n";
        $posiciones = [];
        foreach ($objetos as $i => $obj) {
            $posiciones[] = strlen($pdf);
            $pdf .= ($i + 1)." 0 obj\n$obj\nendobj\n";
        }
        $xref = strlen($pdf);
        $pdf .= "xref\n0 ".(count($objetos) + 1)."\n0000000000 65535 f \n";
        foreach ($posiciones as $p) {
            $pdf .= sprintf("%010d 00000 n \n", $p);
        }

        return $pdf.'trailer << /Size '.(count($objetos) + 1)." /Root 1 0 R >>\nstartxref\n$xref\n%%EOF\n";
    }
}
