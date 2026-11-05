<?php

namespace Tests\Feature;

use App\Models\Convocatoria;
use App\Models\ConvocatoriaPadron;
use App\Models\Usuario;
use App\Models\Voto;
use Database\Seeders\DatosDePruebaSeeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Tests\TestCase;

class DatosDePruebaTest extends TestCase
{
    /** El TestCase ya siembra catálogos; aquí se parte de una base vacía. */
    protected function sembrarCatalogos(): void
    {
    }

    public function test_genera_una_convocatoria_por_fase_con_datos_coherentes(): void
    {
        $this->seed(DatosDePruebaSeeder::class);

        $this->assertSame([1, 2, 3, 4, 4, 5, 6], Convocatoria::orderBy('convocatoria_estatus_id')->pluck('convocatoria_estatus_id')->map(fn ($v) => (int) $v)->all());
        $this->assertSame(280, ConvocatoriaPadron::count());
        $this->assertSame(0, ConvocatoriaPadron::where('convocatoria_id', 1)->whereNotNull('fecha_registro')->count(), 'En fase 1 nadie se ha registrado');
        $this->assertGreaterThan(0, Voto::count());
        $this->assertSame(0, Voto::whereIn('convocatoria_id', Convocatoria::where('convocatoria_estatus_id', '<', 4)->pluck('id'))->count());
        $this->assertFileExists(public_path('acta/Acta_de_Resultados.docx'));
        $this->assertFileExists(public_path('archivos/aviso_privacidad_SIRVOLAB.pdf'));
        $this->assertStringStartsWith('%PDF', file_get_contents(public_path('archivos/aviso_privacidad_SIRVOLAB.pdf')));

        // Ningún correo inventado puede llegar a alguien real.
        $correos = Usuario::pluck('email')->merge(ConvocatoriaPadron::whereNotNull('correo_electronico')->pluck('correo_electronico'));
        $this->assertTrue($correos->every(fn ($c) => str_ends_with($c, '@pruebas.test')));
    }

    public function test_con_los_datos_de_prueba_se_puede_entrar_y_votar(): void
    {
        $this->seed(DatosDePruebaSeeder::class);

        $this->post('/login/', ['user' => 'admin@pruebas.test', 'passwords' => DatosDePruebaSeeder::CONTRASENA])
            ->assertRedirect('/menu/principal');
        $this->get('/convocatoria/index')->assertOk()->assertSee('Prueba 4 - Consulta');
        auth()->logout();

        // Un votante validado de la consulta en curso que todavía no vota.
        $consulta = Convocatoria::where('nombre', 'like', 'Prueba 4 -%')->firstOrFail();
        $votante = ConvocatoriaPadron::where('convocatoria_id', $consulta->id)->whereNotNull('fecha_validacion')
            ->whereNotIn('id', Voto::pluck('convocatoria_padron_id'))->firstOrFail();

        $this->get('/voto/'.encrypt($votante->id).'/cad/'.encrypt($votante->cadena_validacion))
            ->assertOk()->assertViewIs('voto.voto');
    }

    public function test_no_se_ejecuta_sobre_una_base_con_usuarios(): void
    {
        DB::table('usuario')->insert(['email' => 'real@example.com']);

        $this->expectException(RuntimeException::class);
        $this->seed(DatosDePruebaSeeder::class);
    }
}
