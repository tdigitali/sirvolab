<?php
ini_set('max_execution_time','10000');
//ini_set('post_max_size','15MB');
//ini_set('upload_max_filesize','40MB');
use Carbon\Carbon;

class RegistroValidacionController extends BaseController {
    public function registro($cad){
        $miuseragent = $_SERVER['HTTP_USER_AGENT'];
        $moviles = array("Mobile","iPhone","iPod","BlackBerry","Opera Mini","Sony","MOT","Nokia","samsung");
        $detector = 0;
        $numMoviles = count($moviles);

        for($i=0;$i<$numMoviles;$i++){
            $comprobar = strpos($miuseragent,$moviles[$i]);
            if($comprobar != ""){
                $detector = 1;
            }
        }

        try{
            $id = Crypt::decrypt($cad);
            $convocatoria = Convocatoria::find($id);
            $fechaActual = Carbon::now();
            $mensaje = null;

            if($convocatoria){
                if($fechaActual > $convocatoria->fecha_registro_validacion_inicio && $fechaActual < $convocatoria->fecha_registro_validacion_fin && $convocatoria->convocatoria_estatus_id == 2){
                    if($detector == 1){
                        return View::make("registro.entradaCURPMovil", compact("convocatoria"));
                    }else{
                        return View::make("registro.entradaCURP", compact("convocatoria"));
                    }
                }else{
                    $mensaje = "No se cumplen las condiciones necesarias para registrarte a esta convocatoria";
                    
                    if($detector == 1){
                        return View::make("errors.sinPeriodoRegistroVigenteMovil", compact("convocatoria"))->with('mensaje', $mensaje);
                    }else{
                        return View::make("errors.sinPeriodoRegistroVigente", compact("convocatoria"))->with('mensaje', $mensaje);
                    }
                }
            }else{
                $mensaje = "No hay ninguna convocatoria asociada";

                if($detector == 1){
                    return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
                }else{
                    return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                }
            }
        }catch (Exception $e){
            $mensaje = "No hay ninguna convocatoria asociada";

            if($detector == 1){
                return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
            }else{
                return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
            }
        }
    }

    public function validaCURP($id){
        $miuseragent = $_SERVER['HTTP_USER_AGENT'];
        $moviles = array("Mobile","iPhone","iPod","BlackBerry","Opera Mini","Sony","MOT","Nokia","samsung");
        $detector = 0;
        $numMoviles = count($moviles);

        for($i=0;$i<$numMoviles;$i++){
            $comprobar = strpos($miuseragent,$moviles[$i]);
            if($comprobar != ""){
                $detector = 1;
            }
        }

        try{
            $id = Crypt::decrypt($id);
            $convocatoria = Convocatoria::find($id);
            $mensaje = null;
        }catch (Exception $e){
            $mensaje = "No hay ninguna convocatoria asociada";

            if($detector == 1){
                return View::make("errors.sinPeriodoRegistroMovil")->with('message', $mensaje);
            }else{
                return View::make("errors.sinPeriodoRegistroVigente")->with('message', $mensaje);
            }
        }

        $data = Input::all();
        $convocatoriaPadron = ConvocatoriaPadron::where('convocatoria_id', $convocatoria->id)->where('curp', trim(strtoupper($data["curp"])))->first();
        $correoElectronico = null;
        $correoElectronico2 = null;
        
        if($convocatoriaPadron){
            if($convocatoriaPadron->fecha_registro != null){
                $mensaje = 'El votante ya realizó el registro';

                if($detector == 1){
                    return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
                }else{
                    return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                }
            }
            if($convocatoria){
                if($convocatoria->convocatoria_estatus_id == 2){
                    if($detector == 1){
                        return View::make("registro.formularioMovil", compact("convocatoriaPadron"), compact("correoElectronico"))->with('mensaje', $mensaje)->with('correoElectronico2', $correoElectronico2);
                    }else{
                        return View::make("registro.formulario", compact("convocatoriaPadron"), compact("correoElectronico"))->with('mensaje', $mensaje)->with('correoElectronico2', $correoElectronico2);
                    }
                }else{
                    return Redirect::back()->with('message', 'Aún no está disponible el registro para la convocatoria')->withInput($data);
                }
            }else{
                $mensaje = "No hay ninguna convocatoria asociada";

                if($detector == 1){
                    return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('message', $mensaje);
                }else{
                    return View::make("errors.sinPeriodoRegistroVigente")->with('message', $mensaje);
                }
            }
        }else{
            return Redirect::back()->with('message', 'El CURP ingresado no se encuentra en el padrón de la convocatoria')->withInput($data);
        }
    }
    
    public function formularioEdit($id, $cadena){
        $miuseragent = $_SERVER['HTTP_USER_AGENT'];
        $moviles = array("Mobile","iPhone","iPod","BlackBerry","Opera Mini","Sony","MOT","Nokia","samsung");
        $detector = 0;
        $numMoviles = count($moviles);

        for($i=0;$i<$numMoviles;$i++){
            $comprobar = strpos($miuseragent,$moviles[$i]);
            if($comprobar != ""){
                $detector = 1;
            }
        }

        try{
            $id = Crypt::decrypt($id);
            $cadena = Crypt::decrypt($cadena);
            $convocatoriaPadron = ConvocatoriaPadron::find($id);
            $mensaje = null;
            $correoElectronico = $convocatoriaPadron->correo_electronico;
            $correoElectronico2 = $convocatoriaPadron->correo_electronico;
        }catch (Exception $e){
            $mensaje = "No es un registro asociado a una convocatoria";

            if($detector == 1){
                return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
            }else{
                return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
            }
        }

        if($convocatoriaPadron){
            if(!$convocatoriaPadron->cadena_validacion == $cadena){
                if($detector == 1){
                    return View::make("errors.permisoDenegadoMovil");
                }else{
                    return View::make("errors.permisoDenegado");
                }
            }
        }else{
            $mensaje = 'No es miembro del padrón';

            if($detector == 1){
                return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
            }else{
                return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
            }
        }

        if($convocatoriaPadron){
            if($convocatoriaPadron->motivo_rechazo_id != null && $convocatoriaPadron->fecha_rechazo != null){
                if(Convocatoria::find($convocatoriaPadron->convocatoria_id)->convocatoria_estatus_id == 2){
                    if($detector == 1){
                        return View::make("registro.formularioEditMovil", compact("convocatoriaPadron"))->with('mensaje', $mensaje)->with('correoElectronico', $correoElectronico)->with('correoElectronico2', $correoElectronico2);
                    }else{
                        return View::make("registro.formularioEdit", compact("convocatoriaPadron"))->with('mensaje', $mensaje)->with('correoElectronico', $correoElectronico)->with('correoElectronico2', $correoElectronico2);
                    }
                }else{
                    $mensaje = 'Ya terminó el período de registro';

                    if($detector == 1){
                        return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
                    }else{
                        return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                    }
                }
            }else{
                $mensaje = 'No fue posible validar el registro';
                
                if($detector == 1){
                    return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje)->with('correoElectronico2', $correoElectronico2);
                }else{
                    return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje)->with('correoElectronico2', $correoElectronico2);
                }
            }
        }else{
            $mensaje = 'No fue posible validar el registro';
            
            if($detector == 1){
                return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje)->with('correoElectronico2', $correoElectronico2);
            }else{
                return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje)->with('correoElectronico2', $correoElectronico2);
            }
        }
    }
    
    public function registrarse($id){
        $miuseragent = $_SERVER['HTTP_USER_AGENT'];
        $moviles = array("Mobile","iPhone","iPod","BlackBerry","Opera Mini","Sony","MOT","Nokia","samsung");
        $detector = 0;
        $numMoviles = count($moviles);

        for($i=0;$i<$numMoviles;$i++){
            $comprobar = strpos($miuseragent,$moviles[$i]);
            if($comprobar != ""){
                $detector = 1;
            }
        }

        try{
            $id = Crypt::decrypt($id);
            $convocatoriaPadron = ConvocatoriaPadron::find($id);
            $data = Input::all();
            $correoElectronico = strtolower(str_replace(' ', '', $data['correo_electronico']));
            $correoElectronico2 = strtolower(str_replace(' ', '', $data['correo_electronico_2']));
        }catch (Exception $e){
            $mensaje = "No hay ninguna convocatoria asociada";
            
            if($detector == 1){
                return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
            }else{
                return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
            }
        }

        if($convocatoriaPadron){
            if($convocatoriaPadron->fecha_registro != null){
                $mensaje = 'El votante ya realizó el registro';
                
                if($detector == 1){
                    return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
                }else{
                    return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                }
            }
        }
        
        if($correoElectronico){
            if(!strpos($correoElectronico, '@')){
                $mensaje = 'Falta la arroba en el correo electrónico, deberá cargar los archivos nuevamente';

                if($detector == 1){
                    return View::make("registro.formularioMovil", compact("convocatoriaPadron"), compact("correoElectronico"))->with('mensaje', $mensaje)->with('correoElectronico2', $correoElectronico2);
                }else{
                    return View::make("registro.formulario", compact("convocatoriaPadron"), compact("correoElectronico"))->with('mensaje', $mensaje)->with('correoElectronico2', $correoElectronico2);
                }
            }
        }

        if(!$correoElectronico){
            $mensaje = 'No fue capturado el correo electrónico para su registro, deberá cargar los archivos nuevamente';

            if($detector == 1){
                return View::make("registro.formularioMovil", compact("convocatoriaPadron"), compact("correoElectronico"))->with('mensaje', $mensaje)->with('correoElectronico2', $correoElectronico2);
            }else{
                return View::make("registro.formulario", compact("convocatoriaPadron"), compact("correoElectronico"))->with('mensaje', $mensaje)->with('correoElectronico2', $correoElectronico2);
            }
        }
        
        if($correoElectronico != $correoElectronico2){
            $mensaje = 'Los campos de correo electrónico no corresponden, deberá cargar los archivos nuevamente';

            if($detector == 1){
                return View::make("registro.formularioMovil", compact("convocatoriaPadron"), compact("correoElectronico"))->with('mensaje', $mensaje)->with('correoElectronico2', $correoElectronico2);
            }else{
                return View::make("registro.formulario", compact("convocatoriaPadron"), compact("correoElectronico"))->with('mensaje', $mensaje)->with('correoElectronico2', $correoElectronico2);
            }
        }

        $validaCorreo = ConvocatoriaPadron::where('convocatoria_id', $convocatoriaPadron->convocatoria_id)->where('correo_electronico', $correoElectronico)->first();

        if($validaCorreo){
            $mensaje = 'No se puede registrar el mismo correo para esta convocatoria, deberá cargar los archivos nuevamente';
            if($detector == 1){
                return View::make("registro.formularioMovil", compact("convocatoriaPadron"), compact("correoElectronico"))->with('mensaje', $mensaje)->with('correoElectronico2', $correoElectronico2);
            }else{
                return View::make("registro.formulario", compact("convocatoriaPadron"), compact("correoElectronico"))->with('mensaje', $mensaje)->with('correoElectronico2', $correoElectronico2);
            }
        }

        if(Input::hasFile("memoria_documental_ruta_1") && Input::hasFile("memoria_documental_ruta_2") && Input::hasFile("memoria_documental_ruta_3")){
            $path = "/archivos/".$convocatoriaPadron->convocatoria_id."/";
            $convocatoriaPadron->fill($data);
            $convocatoriaPadron->fecha_registro = Carbon::now();
            $convocatoriaPadron->correo_electronico = $correoElectronico;

            try{
                if(!File::isDirectory(public_path().$path)){
                    File::makeDirectory(public_path().$path);
                }

                for($x = 1; $x < 4; $x++){
                    if(Input::hasFile("memoria_documental_ruta_".$x)){
                        $cadenaRandom = str_random(15);
                        $archivo = Input::file("memoria_documental_ruta_".$x);

                        if(strtolower($archivo->getClientOriginalExtension().'') != "jpeg" && strtolower($archivo->getClientOriginalExtension().'') != "jpg" &&
                            strtolower($archivo->getClientOriginalExtension().'') != "png" && strtolower($archivo->getClientOriginalExtension().'') != "tiff" &&
                                strtolower($archivo->getClientOriginalExtension().'') != "bmp" && strtolower($archivo->getClientOriginalExtension().'') != "gif"){

                            $mensaje = 'El archivo no es de algún formato permitido';
                            if($detector == 1){
                                return View::make("registro.formularioMovil", compact("convocatoriaPadron"))->with('mensaje', $mensaje)->with('correoElectronico', $correoElectronico)->with('correoElectronico2', $correoElectronico2);
                            }else{
                                return View::make("registro.formulario", compact("convocatoriaPadron"))->with('mensaje', $mensaje)->with('correoElectronico', $correoElectronico)->with('correoElectronico2', $correoElectronico2);
                            }
                        }

                        $nombreArchivo = $cadenaRandom.'.'.$archivo->getClientOriginalExtension();;
                        $archivo->move(public_path().$path, $nombreArchivo);

                        if($x == 1)
                            $convocatoriaPadron->ruta_ine_frente = $path.$nombreArchivo;
                        if($x == 2)
                            $convocatoriaPadron->ruta_ine_reverso = $path.$nombreArchivo;
                        if($x == 3)
                            $convocatoriaPadron->ruta_selfie_con_ine = $path.$nombreArchivo;
                    }
                }
            }catch(Exception $e){
                $mensaje = 'Los archivos contienen un error';
                if($detector == 1){
                    return View::make("registro.formularioMovil", compact("convocatoriaPadron"), compact("correoElectronico"))->with('mensaje', $mensaje)->with('correoElectronico2', $correoElectronico2);
                }else{
                    return View::make("registro.formulario", compact("convocatoriaPadron"), compact("correoElectronico"))->with('mensaje', $mensaje)->with('correoElectronico2', $correoElectronico2);
                }
            }

            $convocatoriaPadron->save();
            if($detector == 1){
                return View::make("registro.confirmacionMovil", compact("convocatoriaPadron"));
            }else{
                return View::make("registro.confirmacion", compact("convocatoriaPadron"));
            }
        }else
            $mensaje = 'No se cargaron las tres imagenes solicitadas';
            
        if($detector==1){
            return View::make("registro.formularioMovil", compact("convocatoriaPadron"), compact("correoElectronico"))->with('mensaje', $mensaje)->with('correoElectronico2', $correoElectronico2);
        }else{
            return View::make("registro.formulario", compact("convocatoriaPadron"), compact("correoElectronico"))->with('mensaje', $mensaje)->with('correoElectronico2', $correoElectronico2);
        }
    }
    
    public function registrarseUpdate($id){
        $miuseragent = $_SERVER['HTTP_USER_AGENT'];
        $moviles = array("Mobile","iPhone","iPod","BlackBerry","Opera Mini","Sony","MOT","Nokia","samsung");
        $detector = 0;
        $numMoviles = count($moviles);

        for($i=0;$i<$numMoviles;$i++){
            $comprobar = strpos($miuseragent,$moviles[$i]);
            if($comprobar != ""){
                $detector = 1;
            }
        }

        try{
            $id = Crypt::decrypt($id);
            $convocatoriaPadron = ConvocatoriaPadron::find($id);
            $data = Input::all();
            $correoElectronico = strtolower(str_replace(' ', '', $data['correo_electronico']));
            $correoElectronico2 = strtolower(str_replace(' ', '', $data['correo_electronico_2']));
        }catch (Exception $e){
            $mensaje = "No hay ninguna convocatoria asociada";
            
            if($detector == 1){
                return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('message', $mensaje);
            }else{
                return View::make("errors.sinPeriodoRegistroVigente")->with('message', $mensaje);
            }
        }
        
        if(!$correoElectronico){
            $mensaje = 'No fue capturado el correo electrónico para su registro, deberá cargar los archivos nuevamente';

            if($detector == 1){
                return View::make("registro.formularioEditMovil", compact("convocatoriaPadron"))->with('mensaje', $mensaje)->with('correoElectronico', $correoElectronico)->with('correoElectronico2', $correoElectronico2);
            }else{
                return View::make("registro.formularioEdit", compact("convocatoriaPadron"))->with('mensaje', $mensaje)->with('correoElectronico', $correoElectronico)->with('correoElectronico2', $correoElectronico2);
            }
        }

        if($correoElectronico != $correoElectronico2){
            $mensaje = 'Los campos de correo electrónico no corresponden, deberá cargar los archivos nuevamente';

            if($detector == 1){
                return View::make("registro.formularioEditMovil", compact("convocatoriaPadron"))->with('mensaje', $mensaje)->with('correoElectronico', $correoElectronico)->with('correoElectronico2', $correoElectronico2);
            }else{
                return View::make("registro.formularioEdit", compact("convocatoriaPadron"))->with('mensaje', $mensaje)->with('correoElectronico', $correoElectronico)->with('correoElectronico2', $correoElectronico2);
            }
        }
        
        if($correoElectronico){
            if(!strpos($correoElectronico, '@')){
                $mensaje = 'Falta la arroba en el correo electrónico, deberá cargar los archivos nuevamente';

                if($detector == 1){
                    return View::make("registro.formularioMovil", compact("convocatoriaPadron"), compact("correoElectronico"))->with('mensaje', $mensaje)->with('correoElectronico2', $correoElectronico2);
                }else{
                    return View::make("registro.formulario", compact("convocatoriaPadron"), compact("correoElectronico"))->with('mensaje', $mensaje)->with('correoElectronico2', $correoElectronico2);
                }
            }
        }

        $validaCorreo = ConvocatoriaPadron::where('convocatoria_id', $convocatoriaPadron->convocatoria_id)->where('correo_electronico', $correoElectronico)->first();
        
        if($validaCorreo){
            if($validaCorreo->id != $convocatoriaPadron->id){
                $mensaje = 'No se puede registrar el mismo correo para esta convocatoria';
                
                if($detector == 1){
                    return View::make("registro.formularioEditMovil", compact("convocatoriaPadron"))->with('mensaje', $mensaje)->with('correoElectronico', $correoElectronico)->with('correoElectronico2', $correoElectronico2);
                }else{
                    return View::make("registro.formularioEdit", compact("convocatoriaPadron"))->with('mensaje', $mensaje)->with('correoElectronico', $correoElectronico)->with('correoElectronico2', $correoElectronico2);
                }
                
            }
        }

        if(Input::hasFile("memoria_documental_ruta_1") && Input::hasFile("memoria_documental_ruta_2") && Input::hasFile("memoria_documental_ruta_3")){
            $path = "/archivos/".$convocatoriaPadron->convocatoria_id."/";
            $convocatoriaPadron->fill($data);
            $convocatoriaPadron->fecha_registro = Carbon::now();
            $convocatoriaPadron->correo_electronico = $correoElectronico;

            try{
                if(!File::isDirectory(public_path().$path)){
                    File::makeDirectory(public_path().$path);
                }

                for($x = 1; $x < 4; $x++){
                    if(Input::hasFile("memoria_documental_ruta_".$x)){
                        $cadenaRandom = str_random(15);
                        $archivo = Input::file("memoria_documental_ruta_".$x);

                        if(strtolower($archivo->getClientOriginalExtension().'') != "jpeg" && strtolower($archivo->getClientOriginalExtension().'') != "jpg" &&
                            strtolower($archivo->getClientOriginalExtension().'') != "png" && strtolower($archivo->getClientOriginalExtension().'') != "tiff" &&
                                strtolower($archivo->getClientOriginalExtension().'') != "bmp" && strtolower($archivo->getClientOriginalExtension().'') != "gif"){

                            $mensaje = 'El archivo no es de algún formato permitido';
                            if($detector == 1){
                                return View::make("registro.formularioMovil", compact("convocatoriaPadron"))->with('mensaje', $mensaje)->with('correoElectronico', $correoElectronico)->with('correoElectronico2', $correoElectronico2);
                            }else{
                                return View::make("registro.formulario", compact("convocatoriaPadron"))->with('mensaje', $mensaje)->with('correoElectronico', $correoElectronico)->with('correoElectronico2', $correoElectronico2);
                            }
                        }

                        $nombreArchivo = $cadenaRandom.'.'.$archivo->getClientOriginalExtension();
                        $archivo->move(public_path().$path, $nombreArchivo);

                        if($x == 1)
                            $convocatoriaPadron->ruta_ine_frente = $path.$nombreArchivo;
                        if($x == 2)
                            $convocatoriaPadron->ruta_ine_reverso = $path.$nombreArchivo;
                        if($x == 3)
                            $convocatoriaPadron->ruta_selfie_con_ine = $path.$nombreArchivo;
                    }
                }
            }catch(Exception $e){
                $mensaje = 'Los archivos contienen un error';

                if($detector == 1){
                    return View::make("registro.formularioEdit", compact("convocatoriaPadronMovil"))->with('mensaje', $mensaje)->with('correoElectronico', $correoElectronico)->with('correoElectronico2', $correoElectronico2);
                }else{
                    return View::make("registro.formularioEdit", compact("convocatoriaPadron"))->with('mensaje', $mensaje)->with('correoElectronico', $correoElectronico)->with('correoElectronico2', $correoElectronico2);
                }
            }

            $convocatoriaPadron->motivo_rechazo_id = null;
            $convocatoriaPadron->fecha_rechazo = null;
            $convocatoriaPadron->save();
            
            if($detector == 1){
                return View::make("registro.confirmacionMovil", compact("convocatoriaPadron"));
            }else{
                return View::make("registro.confirmacion", compact("convocatoriaPadron"));
            }
        }else
            $mensaje = 'No se cargaron las tres imagenes solicitadas';
        
            if($detector == 1){
                return View::make("registro.formularioEditMovil", compact("convocatoriaPadron"))->with('mensaje', $mensaje)->with('correoElectronico', $correoElectronico)->with('correoElectronico2', $correoElectronico2);
            }else{
                return View::make("registro.formularioEdit", compact("convocatoriaPadron"))->with('mensaje', $mensaje)->with('correoElectronico', $correoElectronico)->with('correoElectronico2', $correoElectronico2);
            }
    }
    
    public function validacion(){
        $miuseragent = $_SERVER['HTTP_USER_AGENT'];
        $moviles = array("Mobile","iPhone","iPod","BlackBerry","Opera Mini","Sony","MOT","Nokia","samsung");
        $detector = 0;
        $numMoviles = count($moviles);

        for($i=0;$i<$numMoviles;$i++){
            $comprobar = strpos($miuseragent,$moviles[$i]);
            if($comprobar != ""){
                $detector = 1;
            }
        }

        if(Auth::user()->rol_id == 2 || Auth::user()->rol_id == 3 || Auth::user()->rol_id == 4){
            if(Convocatoria::find(Auth::user()->convocatoria_id)->convocatoria_estatus_id == 2){
                $validaRegistroNoTerminado = ConvocatoriaPadron::where('convocatoria_id', Auth::user()->convocatoria_id)->where('validador_id', Auth::user()->id)->where('fecha_validacion', null)->where('motivo_rechazo_id', null)->where('fecha_rechazo', null)->first();

                if($validaRegistroNoTerminado){
                    $convocatoriaPadron = $validaRegistroNoTerminado;

                    if($detector == 1){
                        return View::make('registro.validacionMovil', compact("convocatoriaPadron"));
                    }else{
                        return View::make('registro.validacion', compact("convocatoriaPadron"));
                    }
                }

                $convocatoriaPadron = ConvocatoriaPadron::where('convocatoria_id', Auth::user()->convocatoria_id)->where('validador_id', null)->whereNotNull('fecha_registro')->first();

                if($convocatoriaPadron){
                    $convocatoriaPadron->validador_id = Auth::user()->id;
                    $convocatoriaPadron->save();

                    $bitacora = new Bitacora();
                    $bitacora->accion_realizada = "Asignación de votante a validación";
                    $bitacora->fecha_accion = Carbon::now();
                    $bitacora->accion_realizada_id = $convocatoriaPadron->convocatoria_id;
                    $bitacora->modulo_id = 2;
                    $bitacora->usuario_id = Auth::user()->id;
                    $bitacora->save();

                    if($detector == 1){
                        return View::make('registro.validacionMovil', compact("convocatoriaPadron"));
                    }else{
                        return View::make('registro.validacion', compact("convocatoriaPadron"));
                    }
                }else{
                    $mensaje = 'No hay registros por validar';

                    if($detector == 1){
                        return View::make("errors.sinRegistrosPorValidarMovil")->with('mensaje', $mensaje);
                    }else{
                        return View::make("errors.sinRegistrosPorValidar")->with('mensaje', $mensaje);
                    }
                }
            }else{
                $mensajeDeValidacion = "El período de registro y validación ya terminó";

                if($detector == 1){
                    return View::make('errors.autorizacionDenegadaMovil', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion);
                }else{
                    return View::make('errors.autorizacionDenegada', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion);
                }
            }
        }else{
            if($detector == 1){
                return View::make("errors.permisoDenegadoMovil");
            }else{
                return View::make("errors.permisoDenegado");
            }
        }
    }
    
    public function aprobacion($id){
        $miuseragent = $_SERVER['HTTP_USER_AGENT'];
        $moviles = array("Mobile","iPhone","iPod","BlackBerry","Opera Mini","Sony","MOT","Nokia","samsung");
        $detector = 0;
        $numMoviles = count($moviles);

        for($i=0;$i<$numMoviles;$i++){
            $comprobar = strpos($miuseragent,$moviles[$i]);
            if($comprobar != ""){
                $detector = 1;
            }
        }

        if(Auth::user()->rol_id == 2 || Auth::user()->rol_id == 3 || Auth::user()->rol_id == 4){
            try{
                $id = Crypt::decrypt($id);
                $convocatoriaPadron = ConvocatoriaPadron::find($id);
                $data = Input::all();
            }catch (Exception $e){
                $mensaje = "No hay ninguna convocatoria asociada";
                
                if($detector == 1){
                    return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('message', $mensaje);
                }else{
                    return View::make("errors.sinPeriodoRegistroVigente")->with('message', $mensaje);
                }
            }

            if(isset($data["informacion_valida"])){
                $convocatoriaPadron->fecha_validacion = Carbon::now();
                $convocatoriaPadron->save();
            }else{
                return Redirect::back()->with('message', 'No verificó la casilla de información correcta')->withInput($data);
            }
            
            $bitacora = new Bitacora();
            $bitacora->accion_realizada = "Validación de votante: ".$convocatoriaPadron->curp;
            $bitacora->fecha_accion = Carbon::now();
            $bitacora->accion_realizada_id = $convocatoriaPadron->convocatoria_id;
            $bitacora->modulo_id = 2;
            $bitacora->usuario_id = Auth::user()->id;
            $bitacora->save();
            
            try{
                Mail::send('emails.envioConfirmacionValidacion', ['convocatoriaPadron' => $convocatoriaPadron], function($message) use ($convocatoriaPadron){
                        $message->to($convocatoriaPadron->correo_electronico)->subject('Confirmación de registro a SIRVOLAB');
                });
            }catch(Exception $e){
                return View::make("emails.falla", compact("e"));
            }
            
            $resultado = "El votante: ".$convocatoriaPadron->curp." ha sido validado y podrá votar el día de la consulta.";
            
            if($detector == 1){
                return View::make("registro.resultadoValidacionMovil", compact("resultado", $resultado));
            }else{
                return View::make("registro.resultadoValidacion", compact("resultado", $resultado));
            }
        }else{
            if($detector == 1){
                return View::make("errors.permisoDenegadoMovil");
            }else{
                return View::make("errors.permisoDenegado");
            }
        }
    }
    
    public function rechazo($id, $motivoId){
        $miuseragent = $_SERVER['HTTP_USER_AGENT'];
        $moviles = array("Mobile","iPhone","iPod","BlackBerry","Opera Mini","Sony","MOT","Nokia","samsung");
        $detector = 0;
        $numMoviles = count($moviles);

        for($i=0;$i<$numMoviles;$i++){
            $comprobar = strpos($miuseragent,$moviles[$i]);
            if($comprobar != ""){
                $detector = 1;
            }
        }
    
        if(Auth::user()->rol_id == 2 || Auth::user()->rol_id == 3 || Auth::user()->rol_id == 4){
            $convocatoriaPadron = ConvocatoriaPadron::find($id);
            $convocatoriaPadron->motivo_rechazo_id = $motivoId;
            $convocatoriaPadron->fecha_rechazo = Carbon::now();
            $convocatoriaPadron->save();
            $motivo = MotivoRechazo::find($convocatoriaPadron->motivo_rechazo_id)->nombre;
            
            $bitacora = new Bitacora();
            $bitacora->accion_realizada = "Rechazo de votante: ".$convocatoriaPadron->curp.", ".$motivo;
            $bitacora->fecha_accion = Carbon::now();
            $bitacora->accion_realizada_id = $convocatoriaPadron->convocatoria_id;
            $bitacora->modulo_id = 2;
            $bitacora->usuario_id = Auth::user()->id;
            $bitacora->save();
            
            try{
                Mail::send('emails.envioRechazo', ['convocatoriaPadron' => $convocatoriaPadron], function($message) use ($convocatoriaPadron){
                        $message->to($convocatoriaPadron->correo_electronico)->subject('Rechazo de registro a SIRVOLAB');
                });
            }catch(Exception $e){
                return View::make("emails.falla", compact("e"));
            }

            $resultado = $motivo.", por lo que el registro queda rechazado.";
            
            if($detector==1){
                return View::make("registro.resultadoValidacionMovil", compact("resultado", $resultado));
            }else{
                return View::make("registro.resultadoValidacion", compact("resultado", $resultado));
            }
        }else{
            if($detector == 1){
                return View::make("errors.permisoDenegadoMovil");
            }else{
                return View::make("errors.permisoDenegado");
            }
        }
    }

    public function bitacora($id){
        $miuseragent = $_SERVER['HTTP_USER_AGENT'];
        $moviles = array("Mobile","iPhone","iPod","BlackBerry","Opera Mini","Sony","MOT","Nokia","samsung");
        $detector = 0;
        $numMoviles = count($moviles);

        for($i=0;$i<$numMoviles;$i++){
            $comprobar = strpos($miuseragent,$moviles[$i]);
            if($comprobar != ""){
                $detector = 1;
            }
        }

        if(Auth::user()->rol_id == 1){
            $register = Bitacora::where("accion_realizada_id", $id)->where("modulo_id", 2)->get();
            return View::make('convocatoria.bitacora', compact("register"));
        }else{
            if($detector == 1){
                return View::make("errors.permisoDenegadoMovil");
            }else{
                return View::make("errors.permisoDenegado");
            }
        }
    }
}