<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class AuthController extends BaseController
{
    public function showLogin(){
        $detector = $this->detectarMovil();

        if($detector==1){
            return View::make('authMovil');
        }else{
            return View::make('auth');
        }
    }

    public function signIn(){
        $data = Request::all();

        if(Auth::attempt(['email' => $data['user'], 'password' => $data['passwords']])){
            if(Auth::user()->enabled == null)
                return Redirect::back()->with('message', 'Su cuenta ha sido desactivada, en caso de error contacte al administrador.');
            if(Auth::user()->rol_id == 1)
                return Redirect::to('/menu/principal/');
            if(Auth::user()->rol_id == 2 || Auth::user()->rol_id == 3)
                return Redirect::to('/menu/comite/');
            if(Auth::user()->rol_id == 4)
                return Redirect::to('/menu/validador/');
        }else{
            return Redirect::back()->with('message', 'El usuario o la contraseña es incorrecta.');
        }
    }

    public function logOut(){
            Auth::logout();

            return Redirect::to('/login')->with('message_success', Session::get("lang.mensaje_contenido_exito"));
    }

    public function cambiarContrasena($idU,$idD){
        $detector = $this->detectarMovil();

        if($detector==1){
            $register = Usuario::where('email',$idU)->where('verify_password',$idD)->where('default_password', true)->first();
            return View::make("cambiarContrasenaMovil",compact("register"));
        }else{
            $register = Usuario::where('email',$idU)->where('verify_password',$idD)->where('default_password', true)->first();
            return View::make("cambiarContrasena",compact("register"));
        }
    }

    public function cambiarContrasenaSave($id){
        $id = Crypt::decrypt($id);
        $user = Usuario::find($id);
        $data = Request::all();

        if(strcmp(Request::input('password'), Request::input('password_confirmation'))!= 0)
            return Redirect::back()->with('message', 'No se pudo actualizar el registro, las contraseñas no coinciden')->withInput($data);

        $validator = Usuario::validarUsuarioPassword($data);

        if ($validator->fails())
            return Redirect::back()->with('message', 'No se pudo actualizar el registro, revisa los campos requeridos y sus condiciones')->withInput($data)->withErrors($validator->messages());

        $user->password = Hash::make($data["password"]);
        $user->default_password = null;
        $user->save();

        $bitacora = new Bitacora();
        $bitacora->accion_realizada = "Cambio de Contraseña";
        $bitacora->fecha_accion = Carbon::now();
        $bitacora->accion_realizada_id = $user->id;
        $bitacora->modulo_id = 1;
        $bitacora->usuario_id = $user->id;
        $bitacora->save();

        if(Auth::attempt(['email' => $user->email, 'password' => $data['password']])){
            if(Auth::user()->enabled == null)
                return Redirect::back()->with('message', 'Su cuenta ha sido desactivada, en caso de error contacte al administrador.');

            if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 2 || Auth::user()->rol_id == 3 || Auth::user()->rol_id == 4)
                return Redirect::to('/menu/principal/');
        }
    }

    public function registrarse(){
        $detector = $this->detectarMovil();
        if($detector==1){
            return View::make("registrarseMovil");
        }else{
            return View::make("registrarse");
        }
    }

	public function aviso($cad){
		$aviso = public_path().'/archivos/aviso_privacidad_SIRVOLAB.pdf';
		return Response::download($aviso);
    }
}