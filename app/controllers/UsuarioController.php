<?php

use Carbon\Carbon;

class UsuarioController extends BaseController {
    public function index(){
        if(Auth::user()->rol_id == 1){
            $miuseragent=$_SERVER['HTTP_USER_AGENT'];
            $moviles=array("Mobile","iPhone","iPod","BlackBerry","Opera Mini","Sony","MOT","Nokia","samsung");
            $detector=0;
            $numMoviles=count($moviles);

            for($i=0;$i<$numMoviles;$i++){
                $comprobar=strpos($miuseragent,$moviles[$i]);
                if($comprobar!=""){
                    $detector=1;
                }
            }

            if($detector==1){
                $usuarios = Usuario::where('rol_id', 1)->get();
                return View::make("usuario.indexMovil", compact("usuarios"));
            }else{
                $usuarios = Usuario::where('rol_id', 1)->get();
                return View::make("usuario.index", compact("usuarios"));
            }
        }else
            return View::make("errors.permisoDenegado");
    }
    
    public function create(){
        if(Auth::user()->rol_id == 1){
            $miuseragent=$_SERVER['HTTP_USER_AGENT'];
            $moviles=array("Mobile","iPhone","iPod","BlackBerry","Opera Mini","Sony","MOT","Nokia","samsung");
            $detector=0;
            $numMoviles=count($moviles);

            for($i=0;$i<$numMoviles;$i++){
                $comprobar=strpos($miuseragent,$moviles[$i]);
                if($comprobar!=""){
                    $detector=1;
                }
            }

            if($detector==1){
                return View::make("usuario.createMovil");
            }else{
                return View::make("usuario.create");
            }
        }else
            return View::make("errors.permisoDenegado");
    }
    
    public function store(){
        if(Auth::user()->rol_id == 1){
            $cadenaRandom = str_random(10);
            $data = Input::all();
            $data["password"] = "sakjcbbywgriuedlsd";
            $data["verify_password"] = $cadenaRandom;

            $user = new Usuario();
            $validator = Usuario::validarUsuario($data);

            if ($validator->fails())
                return Redirect::back()->with('message', 'No se pudo crear el registro, revisa los campos requeridos y sus condiciones')->withInput($data)->withErrors($validator->messages());

            $user->fill($data);
            $user->password = Hash::make($data['password']);
            $user->metadata = Crypt::encrypt($data['password']);
            $user->verify_password = $cadenaRandom;
            $user->enabled = true;
            $user->save();

            $bitacora = new Bitacora();
            $bitacora->accion_realizada = "Creación de usuario";
            $bitacora->fecha_accion = Carbon::now();
            $bitacora->accion_realizada_id = $user->id;
            $bitacora->modulo_id = 1;
            $bitacora->usuario_id = Auth::user()->id;
            $bitacora->save();
            
            try{
                Mail::send('emails.envioEnlaceUsuarioInterno', ['user' => $user], function($message) use ($user){
                        $message->to($user->email, $user->nombre.' '.$user->apellido_paterno)->subject('Enlace de acceso a SIRVOLAB');
                });
            }catch(Exception $e){
                return View::make("emails.falla", compact("e"));
            }

            return Redirect::to('usuario/index/')->with('message', 'Registro Creado');
        }else
            return View::make("errors.permisoDenegado");
    }

    public function edit($id){
        if(Auth::user()->rol_id == 1){
            $miuseragent=$_SERVER['HTTP_USER_AGENT'];
            $moviles=array("Mobile","iPhone","iPod","BlackBerry","Opera Mini","Sony","MOT","Nokia","samsung");
            $detector=0;
            $numMoviles=count($moviles);

            for($i=0;$i<$numMoviles;$i++){
                $comprobar=strpos($miuseragent,$moviles[$i]);
                if($comprobar!=""){
                    $detector=1;
                }
            }

            if($detector==1){
                $register = Usuario::find($id);
                return View::make('usuario.editMovil', compact("register"));
            }else{
                $register = Usuario::find($id);
                return View::make('usuario.edit', compact("register"));
            }
        }else
            return View::make("errors.permisoDenegado");
    }

    public function update($id){
        if(Auth::user()->rol_id == 1){
            $id = Crypt::decrypt($id);
            $user = Usuario::find($id);
            $data = Input::all();
            $validator = Usuario::validarUsuarioUpdate($data);

            if ($validator->fails())
                return Redirect::back()->with('message', 'No se pudo actualizar el registro, revisa los campos requeridos y sus condiciones')->withInput($data)->withErrors($validator->messages());

            $user->fill($data);
            $user->enabled = isset($data["enabled"])? true : null;
            $user->default_password = isset($data["default_password"])? true : null;
            $user->save();

            $bitacora = new Bitacora();
            $bitacora->accion_realizada = "Actualización de usuario";
            $bitacora->fecha_accion = Carbon::now();
            $bitacora->accion_realizada_id = $user->id;
            $bitacora->modulo_id = 1;
            $bitacora->usuario_id = Auth::user()->id;
            $bitacora->save();

            return Redirect::to('usuario/index/')->with('message', 'Registro Actualizado');
        }else
            return View::make("errors.permisoDenegado");
    }

    public function generarEnviarToken($id){
        if(Auth::user()->rol_id == 1){
            try{
                $id = Crypt::decrypt($id);
                $usuario = Usuario::find($id);
                $convocatoria = Convocatoria::find($usuario->convocatoria_id);
                $estatusConvocatoriaCerrada = 6;
                $mensaje = "No es un registro asociado a una convocatoria";
                
                if($convocatoria){
                    if($convocatoria->convocatoria_estatus_id != $estatusConvocatoriaCerrada){
                        $usuario->token_autorizacion = str_random(10);
                        $usuario->vigencia_token_autorizacion = Carbon::now()->addHour();
                        $usuario->save();
                        
                        $bitacora = new Bitacora();
                        $bitacora->accion_realizada = "Token de acceso creado para: ".$usuario->curp;
                        $bitacora->fecha_accion = Carbon::now();
                        $bitacora->accion_realizada_id = $usuario->convocatoria_id;
                        $bitacora->modulo_id = 2;
                        $bitacora->usuario_id = Auth::user()->id;
                        $bitacora->save();
                        
                        Mail::send('emails.envioTokenUsuarioInterno', ['usuario' => $usuario], function($message) use ($usuario){
                            $message->to($usuario->correo_electronico_notificaciones, $usuario->nombre.' '.$usuario->apellido_paterno)->subject('Notificación SIRVOLAB');
                        });
                        
                        return Redirect::to('/convocatoria/asignarComite/'.$usuario->convocatoria_id)->with('messageUpdate', 'Token creado y enviado');
                    }else{
                        return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                    }
                }else{
                    return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                }
            }catch (Exception $e){
                return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $e);
            }
        }else
            return View::make("errors.permisoDenegado");
    }
    
    public function enviarFormularioEdit($id, $tipo){
        if(Auth::user()->rol_id == 1){
            try{
                $id = Crypt::decrypt($id);
                $convocatoriaPadron = ConvocatoriaPadron::find($id);
                $convocatoria = Convocatoria::find($convocatoriaPadron->convocatoria_id);
                $estatusConvocatoriaRegistro = 2;
                $mensaje = "No es un registro asociado a una convocatoria";
                
                if($convocatoria){
                    if(!$convocatoria->convocatoria_estatus_id == $estatusConvocatoriaRegistro){   
                        return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                    }
                }else{
                    return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                }
            }catch (Exception $e){
                return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
            }
            
            if($convocatoriaPadron->correo_electronico == null){
                return Redirect::to('/convocatoria/indexPadronConsulta/'.Crypt::encrypt($convocatoriaPadron->convocatoria_id).'/t/'.$tipo)->with('messageError', 'El usuario no ha registrado un correo electrónico válido');
            }

            try{
                Mail::send('emails.envioEnlaceParaEdicionRegistro', ['convocatoriaPadron' => $convocatoriaPadron], function($message) use ($convocatoriaPadron){
                        $message->to($convocatoriaPadron->correo_electronico, $convocatoriaPadron->nombre_completo)->subject('Enlace de edición de registro en SIRVOLAB');
                });
            }catch(Exception $e){
                return View::make("emails.falla", compact("e"));
            }

            $bitacora = new Bitacora();
            $bitacora->accion_realizada = "Token de votación enviado de manera manual a: ".$convocatoriaPadron->curp;
            $bitacora->fecha_accion = Carbon::now();
            $bitacora->accion_realizada_id = $convocatoriaPadron->convocatoria_id;
            $bitacora->modulo_id = 2;
            $bitacora->usuario_id = Auth::user()->id;
            $bitacora->save();
            
            return Redirect::to('/convocatoria/indexPadronConsulta/'.Crypt::encrypt($convocatoriaPadron->convocatoria_id).'/t/'.$tipo)->with('messageUpdate', 'Enlace de acceso enviado a usuario');
        }else
            return View::make("errors.permisoDenegado");
    }
    
    public function enviarFormularioEditComite($id, $tipo){
        if(Auth::user()->rol_id == 2 || Auth::user()->rol_id == 3){
            try{
                $id = Crypt::decrypt($id);
                $convocatoriaPadron = ConvocatoriaPadron::find($id);
                $convocatoria = Convocatoria::find($convocatoriaPadron->convocatoria_id);
                $estatusConvocatoriaRegistro = 2;
                $mensaje = "No es un registro asociado a una convocatoria";

                if($convocatoria){
                    if(!$convocatoria->convocatoria_estatus_id == $estatusConvocatoriaRegistro){   
                        return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                    }
                }else{
                    return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                }
            }catch (Exception $e){
                return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
            }
            
            if($convocatoriaPadron->correo_electronico == null){
                return Redirect::to('/convocatoria/indexPadronComiteConsulta/'.$tipo)->with('messageError', 'El usuario no ha registrado un correo electrónico válido');
            }

            try{
                Mail::send('emails.envioEnlaceParaEdicionRegistro', ['convocatoriaPadron' => $convocatoriaPadron], function($message) use ($convocatoriaPadron){
                        $message->to($convocatoriaPadron->correo_electronico, $convocatoriaPadron->nombre_completo)->subject('Enlace de edición de registro en SIRVOLAB');
                });
            }catch(Exception $e){
                return View::make("emails.falla", compact("e"));
            }

            $bitacora = new Bitacora();
            $bitacora->accion_realizada = "Token de votación enviado de manera manual a: ".$convocatoriaPadron->curp;
            $bitacora->fecha_accion = Carbon::now();
            $bitacora->accion_realizada_id = $convocatoriaPadron->convocatoria_id;
            $bitacora->modulo_id = 2;
            $bitacora->usuario_id = Auth::user()->id;
            $bitacora->save();
            
            return Redirect::to('/convocatoria/indexPadronComiteConsulta/'.$tipo)->with('messageUpdate', 'Enlace de acceso enviado a usuario');
        }else
            return View::make("errors.permisoDenegado");
    }
    
    public function enviarTokenVoto($id, $tipo){
        if(Auth::user()->rol_id == 1){
            try{
                $id = Crypt::decrypt($id);
                $convocatoriaPadron = ConvocatoriaPadron::find($id);
                $convocatoria = Convocatoria::find($convocatoriaPadron->convocatoria_id);
                $estatusConvocatoriaConsulta = 4;
                $mensaje = "No es un registro asociado a una convocatoria";
                
                if($convocatoria){
                    if(!$convocatoria->convocatoria_estatus_id == $estatusConvocatoriaConsulta){   
                        return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                    }
                }else{
                    return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                }
            }catch (Exception $e){
                return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
            }
            
            if($convocatoriaPadron->correo_electronico == null){
                return Redirect::to('/convocatoria/indexPadronConsulta/'.Crypt::encrypt($convocatoriaPadron->convocatoria_id).'/t/'.$tipo)->with('messageError', 'El usuario no ha registrado un correo electrónico válido');
            }

            try{
                Mail::send('emails.envioEnlaceParaVotacion', ['convocatoriaPadron' => $convocatoriaPadron], function($message) use ($convocatoriaPadron){
                        $message->to($convocatoriaPadron->correo_electronico, $convocatoriaPadron->nombre_completo)->subject('Enlace de votación en SIRVOLAB');
                });
            }catch(Exception $e){
                return View::make("emails.falla", compact("e"));
            }

            $bitacora = new Bitacora();
            $bitacora->accion_realizada = "Token de votación enviado de manera manual a: ".$convocatoriaPadron->curp;
            $bitacora->fecha_accion = Carbon::now();
            $bitacora->accion_realizada_id = $convocatoriaPadron->convocatoria_id;
            $bitacora->modulo_id = 2;
            $bitacora->usuario_id = Auth::user()->id;
            $bitacora->save();
            
            return Redirect::to('/convocatoria/indexPadronConsulta/'.Crypt::encrypt($convocatoriaPadron->convocatoria_id).'/t/'.$tipo)->with('messageUpdate', 'Enlace de acceso enviado a usuario');
        }else
            return View::make("errors.permisoDenegado");
    }
    
    public function enviarTokenVotoComite($id, $tipo){
        if(Auth::user()->rol_id == 2 || Auth::user()->rol_id == 3){
            try{
                $id = Crypt::decrypt($id);
                $convocatoriaPadron = ConvocatoriaPadron::find($id);
                $convocatoria = Convocatoria::find($convocatoriaPadron->convocatoria_id);
                $estatusConvocatoriaConsulta = 4;
                $mensaje = "No es un registro asociado a una convocatoria";
                
                if($convocatoria){
                    if(!$convocatoria->convocatoria_estatus_id == $estatusConvocatoriaConsulta){   
                        return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                    }
                }else{
                    return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                }
            }catch (Exception $e){
                return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
            }
                        
            if($convocatoriaPadron->correo_electronico == null){
                return Redirect::to('/convocatoria/indexPadronComiteConsulta/'.$tipo)->with('messageError', 'El usuario no ha registrado un correo electrónico válido');
            }

            try{
                Mail::send('emails.envioEnlaceParaVotacion', ['convocatoriaPadron' => $convocatoriaPadron], function($message) use ($convocatoriaPadron){
                        $message->to($convocatoriaPadron->correo_electronico, $convocatoriaPadron->nombre_completo)->subject('Enlace de votación en SIRVOLAB');
                });
            }catch(Exception $e){
                return View::make("emails.falla", compact("e"));
            }

            $bitacora = new Bitacora();
            $bitacora->accion_realizada = "Token de votación enviado de manera manual a: ".$convocatoriaPadron->curp;
            $bitacora->fecha_accion = Carbon::now();
            $bitacora->accion_realizada_id = $convocatoriaPadron->convocatoria_id;
            $bitacora->modulo_id = 2;
            $bitacora->usuario_id = Auth::user()->id;
            $bitacora->save();
            
            return Redirect::to('/convocatoria/indexPadronComiteConsulta/'.$tipo)->with('messageUpdate', 'Enlace de acceso enviado a usuario');
        }else
            return View::make("errors.permisoDenegado");
    }
    
    public function enviarTokenVotoComitePorCURP($id){
        if(Auth::user()->rol_id == 2 || Auth::user()->rol_id == 3){
            try{
                $id = Crypt::decrypt($id);
                $convocatoriaPadron = ConvocatoriaPadron::find($id);
                $convocatoria = Convocatoria::find($convocatoriaPadron->convocatoria_id);
                $estatusConvocatoriaConsulta = 4;
                $mensaje = "No es un registro asociado a una convocatoria";
                
                if($convocatoria){
                    if(!$convocatoria->convocatoria_estatus_id == $estatusConvocatoriaConsulta){   
                        return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                    }
                }else{
                    return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                }
            }catch (Exception $e){
                return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
            }
            
            $curp = null;

            if($convocatoriaPadron->correo_electronico == null){
                return Redirect::to('/convocatoria/busquedaPorCURP/')->with('mensaje', 'El usuario no ha registrado un correo electrónico válido')->with('curp', $curp);
            }

            try{
                Mail::send('emails.envioEnlaceParaVotacion', ['convocatoriaPadron' => $convocatoriaPadron], function($message) use ($convocatoriaPadron){
                        $message->to($convocatoriaPadron->correo_electronico, $convocatoriaPadron->nombre_completo)->subject('Enlace de votación en SIRVOLAB');
                });
            }catch(Exception $e){
                return View::make("emails.falla", compact("e"));
            }

            $bitacora = new Bitacora();
            $bitacora->accion_realizada = "Token de votación enviado de manera manual a: ".$convocatoriaPadron->curp;
            $bitacora->fecha_accion = Carbon::now();
            $bitacora->accion_realizada_id = $convocatoriaPadron->convocatoria_id;
            $bitacora->modulo_id = 2;
            $bitacora->usuario_id = Auth::user()->id;
            $bitacora->save();
            
            return Redirect::to('/convocatoria/busquedaPorCURP/')->with('mensaje', 'Enlace de acceso enviado a usuario')->with('curp', $curp);
        }else
            return View::make("errors.permisoDenegado");
    }
    
    public function enviarEnlaceAcceso($id){
        if(Auth::user()->rol_id == 1){
            $usuarioVerficacionCorreo = Usuario::find($id);
            
            $usuarioVerficacionCorreo->default_password = true;
            $usuarioVerficacionCorreo->save();
            $convocatoria = Convocatoria::find($usuarioVerficacionCorreo->convocatoria_id);

            try{
                Mail::send('emails.envioEnlaceUsuarioInterno', ['user' => $usuarioVerficacionCorreo, 'convocatoria' => $convocatoria], function($message) use ($usuarioVerficacionCorreo, $convocatoria){
                        $message->to($usuarioVerficacionCorreo->correo_electronico_notificaciones, $usuarioVerficacionCorreo->nombre.' '.$usuarioVerficacionCorreo->apellido_paterno)->subject('Enlace de acceso a SIRVOLAB');
                });
            }catch(Exception $e){
                return View::make("emails.falla", compact("e"));
            }

            $bitacora = new Bitacora();
            $bitacora->accion_realizada = "Envío de enlace para acceso de usuario: ".$usuarioVerficacionCorreo->curp;
            $bitacora->fecha_accion = Carbon::now();
            $bitacora->accion_realizada_id = $usuarioVerficacionCorreo->convocatoria_id;
            $bitacora->modulo_id = 2;
            $bitacora->usuario_id = Auth::user()->id;
            $bitacora->save();
            
            return Redirect::to('/convocatoria/asignarComite/'.$usuarioVerficacionCorreo->convocatoria_id)->with('messageUpdate', 'Enlace de acceso enviado a usuario');
        }else
            return View::make("errors.permisoDenegado");
    }
    
    public function bitacora($id){
        if(Auth::user()->rol_id == 1){
            $miuseragent=$_SERVER['HTTP_USER_AGENT'];
            $moviles=array("Mobile","iPhone","iPod","BlackBerry","Opera Mini","Sony","MOT","Nokia","samsung");
            $detector=0;
            $numMoviles=count($moviles);

            for($i=0;$i<$numMoviles;$i++){
                $comprobar=strpos($miuseragent,$moviles[$i]);
                if($comprobar!=""){
                    $detector=1;
                }
            }

            if($detector==1){
                $register = Bitacora::where("accion_realizada_id", $id)->where("modulo_id", 1)->get();
                return View::make('usuario.bitacoraMovil', compact("register"));
            }else{
                $register = Bitacora::where("accion_realizada_id", $id)->where("modulo_id", 1)->get();
                return View::make('usuario.bitacora', compact("register"));
            }
        }else
            return View::make("errors.permisoDenegado");
    }
}