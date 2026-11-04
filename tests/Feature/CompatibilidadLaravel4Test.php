<?php

namespace Tests\Feature;

use App\Models\Usuario;
use App\Support\Html\FormBuilder;
use Illuminate\Encryption\Encrypter;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * Garantías de compatibilidad con datos y enlaces generados por la versión
 * Laravel 4.2 que sigue en producción.
 */
class CompatibilidadLaravel4Test extends TestCase
{
    /** Llave de ejemplo con el mismo formato que la de app/config/app.php (32 caracteres). */
    private const LLAVE_L4 = 'abcdefghijklmnopqrstuvwxyz012345';

    /**
     * Reproduce exactamente Illuminate\Encryption\Encrypter::encrypt() de
     * Laravel 4.2 (mcrypt RIJNDAEL-128 en CBC con relleno PKCS7 manual).
     */
    private function cifrarComoLaravel4(mixed $valor, string $llave): string
    {
        $iv = random_bytes(16);
        $datos = serialize($valor);
        $relleno = 16 - (strlen($datos) % 16);
        $datos .= str_repeat(chr($relleno), $relleno);

        $cifrado = openssl_encrypt($datos, 'AES-256-CBC', $llave, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);

        $value = base64_encode($cifrado);
        $iv = base64_encode($iv);
        $mac = hash_hmac('sha256', $iv.$value, $llave);

        return base64_encode(json_encode(compact('iv', 'value', 'mac')));
    }

    public function test_descifra_valores_generados_por_laravel_4_con_la_misma_llave(): void
    {
        $encrypter = new Encrypter(self::LLAVE_L4, 'AES-256-CBC');

        $this->assertSame(1234, $encrypter->decrypt($this->cifrarComoLaravel4(1234, self::LLAVE_L4)));
        $this->assertSame('k3Y9x', $encrypter->decrypt($this->cifrarComoLaravel4('k3Y9x', self::LLAVE_L4)));
    }

    public function test_enlace_de_voto_enviado_por_la_version_anterior_sigue_funcionando(): void
    {
        config(['app.key' => self::LLAVE_L4]);
        $this->app->forgetInstance('encrypter');
        Crypt::clearResolvedInstances();

        $c = $this->crearConvocatoria(4);
        $votante = $this->crearVotante($c, ['fecha_validacion' => now()]);

        $enlace = '/voto/'.$this->cifrarComoLaravel4($votante->id, self::LLAVE_L4)
            .'/cad/'.$this->cifrarComoLaravel4($votante->cadena_validacion, self::LLAVE_L4);

        // Sin boletas se llega directo a la confirmación: lo importante es que
        // el enlace se descifró y no cayó en "No es un registro asociado".
        $this->get($enlace)->assertOk()->assertViewIs('voto.confirmacionVoto');
    }

    public function test_contrasenas_guardadas_por_laravel_4_siguen_siendo_validas(): void
    {
        // Laravel 4 usaba bcrypt con costo 10.
        $usuario = $this->crearUsuario(1, ['password' => password_hash('Clave2016!', PASSWORD_BCRYPT, ['cost' => 10])]);

        $this->post('/login/', ['user' => $usuario->email, 'passwords' => 'Clave2016!'])
            ->assertRedirect('/menu/principal');
        $this->assertAuthenticatedAs($usuario);
    }

    public function test_los_datos_se_escapan_pero_el_html_de_las_vistas_no(): void
    {
        $c = $this->crearConvocatoria(2, ['nombre' => '<script>alert(1)</script>']);

        $html = $this->actingAs($this->crearUsuario(1))->get('/convocatoria/index')->getContent();

        $this->assertStringNotContainsString('<script>alert(1)</script>', $html);
        $this->assertStringContainsString('&lt;script&gt;alert(1)&lt;/script&gt;', $html);
        // Fragmento HTML fijo de la vista (antes {{ }} sin escapar): se sigue mostrando como HTML.
        $this->assertStringContainsString('<font style="color: #10312B; font-weight: bold;">Registro y Validación</font>', $html);
    }

    public function test_formularios_post_incluyen_token_csrf_y_rechazan_peticiones_sin_el(): void
    {
        $this->assertStringContainsString('name="_token"', $this->get('/login/')->getContent());

        // Fuera del entorno de pruebas, Laravel 13 valida el token en todos los POST.
        $this->assertContains('web', Route::getRoutes()->match(request()->create('/login/', 'POST'))->middleware());
    }

    public function test_form_builder_reproduce_el_html_de_laravel_4(): void
    {
        $form = app(FormBuilder::class);

        $this->assertSame(
            '<input style="color: grey" name="nombre" type="text" value="Ana">',
            $form->text('nombre', 'Ana', ['style' => 'color: grey'])
        );
        $this->assertSame(
            '<select id="h" name="hora"><option value="00">00</option><option value="10" selected="selected">10</option></select>',
            $form->select('hora', ['00' => '00', '10' => '10'], '10', ['id' => 'h'])
        );
        $this->assertSame(
            '<input class="ui checkbox" checked="checked" name="enabled" type="checkbox">',
            $form->checkbox('enabled', null, 1, ['class' => 'ui checkbox'])
        );
        $this->assertSame(
            '<input name="enabled" type="checkbox">',
            $form->checkbox('enabled', null, null)
        );
        $this->assertSame(
            '<textarea style="color: grey" maxlength="200" rows="4" name="razon_termino" cols="50">&lt;b&gt;</textarea>',
            $form->textArea('razon_termino', '<b>', ['style' => 'color: grey', 'maxlength' => '200', 'rows' => '4'])
        );
        $this->assertSame('<input name="password" type="password" value="">', $form->password('password', null));
        $this->assertStringStartsWith(
            '<form method="POST" action="http://localhost/boleta/save" accept-charset="UTF-8" class="ui form"><input name="_token" type="hidden"',
            $form->open(['action' => 'BoletaController@store', 'class' => 'ui form'])
        );
    }

    public function test_form_builder_repuebla_campos_con_old_input(): void
    {
        $this->actingAs($this->crearUsuario(1));
        $this->get('/usuario/create');
        session()->flashInput(['nombre' => 'Capturado', 'enabled' => null]);

        $form = app(FormBuilder::class);
        $this->assertStringContainsString('value="Capturado"', $form->text('nombre', 'Original'));
        // Si el formulario se reenvió sin la casilla, queda desmarcada aunque el valor por defecto sea true.
        $this->assertStringNotContainsString('checked', $form->checkbox('enabled', null, true));
    }

    public function test_usuario_es_el_modelo_de_autenticacion(): void
    {
        $this->assertSame(Usuario::class, config('auth.providers.users.model'));
        $this->assertSame('usuario', (new Usuario)->getTable());
    }
}
