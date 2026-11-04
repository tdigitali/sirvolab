<?php

namespace Tests\Feature;

use Tests\TestCase;

class AutenticacionTest extends TestCase
{
    public function test_muestra_login_de_escritorio_con_token_csrf(): void
    {
        $respuesta = $this->get('/');

        $respuesta->assertOk();
        $respuesta->assertSee('name="_token"', false);
        $respuesta->assertSee('action="http://localhost/login"', false);
    }

    public function test_muestra_login_movil_segun_user_agent(): void
    {
        $escritorio = $this->get('/login/')->getContent();
        $movil = $this->comoMovil()->get('/login/')->getContent();

        $this->assertNotSame($escritorio, $movil);
    }

    public function test_invitado_es_redirigido_al_login_con_mensaje(): void
    {
        $this->get('/menu/principal')
            ->assertRedirect('/login')
            ->assertSessionHas('message_error', 'Inicie sesión por favor');
    }

    public function test_login_redirige_segun_el_rol(): void
    {
        $destinos = [1 => '/menu/principal', 2 => '/menu/comite', 3 => '/menu/comite', 4 => '/menu/validador'];

        foreach ($destinos as $rol => $destino) {
            $usuario = $this->crearUsuario($rol);

            $this->post('/login/', ['user' => $usuario->email, 'passwords' => 'secreto123'])
                ->assertRedirect($destino);

            $this->post('/logout');
            auth()->logout();
        }
    }

    public function test_login_con_contrasena_incorrecta(): void
    {
        $usuario = $this->crearUsuario(1);

        $this->from('/login/')
            ->post('/login/', ['user' => $usuario->email, 'passwords' => 'otra'])
            ->assertRedirect('/login')
            ->assertSessionHas('message', 'El usuario o la contraseña es incorrecta.');
        $this->assertGuest();
    }

    public function test_usuario_desactivado_recibe_mensaje(): void
    {
        $usuario = $this->crearUsuario(1, ['enabled' => null]);

        $this->from('/login/')
            ->post('/login/', ['user' => $usuario->email, 'passwords' => 'secreto123'])
            ->assertSessionHas('message', 'Su cuenta ha sido desactivada, en caso de error contacte al administrador.');
    }

    public function test_logout(): void
    {
        $this->actingAs($this->crearUsuario(1))
            ->get('/logout')
            ->assertRedirect('/login');
        $this->assertGuest();
    }
}
