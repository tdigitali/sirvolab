<?php

use Carbon\Carbon;

class BoletaController extends BaseController {
    public function index(){
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

        if(Auth::user()->rol_id == 2 || Auth::user()->rol_id == 3){
            $boletas = Boleta::where('convocatoria_id', Auth::user()->convocatoria_id)->get();
            
            if($detector==1){
                return View::make("boleta.indexMovil", compact("boletas"));
            }else{
                return View::make("boleta.index", compact("boletas"));
            }
        }else{
            if($detector == 1){
                return View::make("errors.permisoDenegadoMovil");
            }else{
                return View::make("errors.permisoDenegado");
            }
        }
    }
    
    public function indexCentro($id){
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
            $boletas = Boleta::where('convocatoria_id', $id)->get();
            
            if($detector==1){
                return View::make("boleta.indexCentroMovil", compact("boletas"));
            }else{
                return View::make("boleta.indexCentro", compact("boletas"));
            }
        }else{
            if($detector == 1){
                return View::make("errors.permisoDenegadoMovil");
            }else{
                return View::make("errors.permisoDenegado");
            }
        }
    }
    
    public function create(){
        $miuseragent = $_SERVER['HTTP_USER_AGENT'];
        $moviles = array("Mobile","iPhone","iPod","BlackBerry","Opera Mini","Sony","MOT","Nokia","samsung");
        $detector = 0;
        $numMoviles = count($moviles);

        for($i=0;$i<$numMoviles;$i++){
            $comprobar=strpos($miuseragent,$moviles[$i]);
            if($comprobar != ""){
                $detector = 1;
            }
        }

        if(Auth::user()->rol_id == 2){
            $convocatoria = Convocatoria::find(Auth::user()->convocatoria_id);
            
            if($convocatoria->convocatoria_estatus_id == 3){
                if($detector==1){    
                    return View::make("boleta.createMovil");
                }else{
                    return View::make("boleta.create");
                }
            }else{
                $mensajeDeValidacion = $convocatoria->convocatoria_estatus_id < 3 ? "Necesita cerrar la fase de registro y validación para crear boletas" : "No se pueden crear boletas una vez iniciada la fase de consulta";

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
    
    public function store(){
        $miuseragent = $_SERVER['HTTP_USER_AGENT'];
        $moviles = array("Mobile","iPhone","iPod","BlackBerry","Opera Mini","Sony","MOT","Nokia","samsung");
        $detector = 0;
        $numMoviles = count($moviles);

        for($i=0;$i<$numMoviles;$i++){
            $comprobar=strpos($miuseragent,$moviles[$i]);
            if($comprobar != ""){
                $detector = 1;
            }
        }

        if(Auth::user()->rol_id == 2){
            $convocatoria = Convocatoria::find(Auth::user()->convocatoria_id);

            if($convocatoria->convocatoria_estatus_id == 3){
                $data = Input::all();
                $boleta = new Boleta();
                $validator = Boleta::validarBoleta($data);

                if ($validator->fails())
                    return Redirect::back()->with('message', 'No se pudo crear el registro, revisa los campos requeridos y sus condiciones')->withInput($data)->withErrors($validator->messages());

                $boleta->fill($data);
                $boleta->convocatoria_id = Auth::user()->convocatoria_id;
                $boleta->save();

                $bitacora = new Bitacora();
                $bitacora->accion_realizada = "Creación de boleta";
                $bitacora->fecha_accion = Carbon::now();
                $bitacora->accion_realizada_id = Auth::user()->convocatoria_id;
                $bitacora->modulo_id = 2;
                $bitacora->usuario_id = Auth::user()->id;
                $bitacora->save();
                
                if($convocatoria->aprobacion_confirmacion_contrato != null && $convocatoria->aprobacion_confirmacion_contrato != ''){
                    $boletaCandidato = new BoletaCandidato();
                    $boletaCandidato->fill($data);
                    $boletaCandidato->nombre_completo = 'Sí';
                    $boletaCandidato->curp = 'Sí';
                    $boletaCandidato->planilla_color = '#37ff00';
                    $boletaCandidato->boleta_id = $boleta->id;
                    $boletaCandidato->convocatoria_id = $boleta->convocatoria_id;
                    $boletaCandidato->save();

                    $boletaCandidato = new BoletaCandidato();
                    $boletaCandidato->fill($data);
                    $boletaCandidato->nombre_completo = 'No';
                    $boletaCandidato->curp = 'No';
                    $boletaCandidato->planilla_color = '#ff0000';
                    $boletaCandidato->boleta_id = $boleta->id;
                    $boletaCandidato->convocatoria_id = $boleta->convocatoria_id;
                    $boletaCandidato->save();

                    $bitacora = new Bitacora();
                    $bitacora->accion_realizada = "Creación de aprobación/revisión en boleta";
                    $bitacora->fecha_accion = Carbon::now();
                    $bitacora->accion_realizada_id = $boleta->convocatoria_id;
                    $bitacora->modulo_id = 2;
                    $bitacora->usuario_id = Auth::user()->id;
                    $bitacora->save();
                }

                return Redirect::to('boleta/index/')->with('message', 'Registro Creado');
            }else{
                $mensajeDeValidacion = $convocatoria->convocatoria_estatus_id < 3 ? "Necesita cerrar la fase de registro y validación para crear boletas" : "No se pueden crear boletas una vez iniciada la fase de consulta";
                
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
    
    public function show($id){
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
        
        if(Auth::user()->rol_id == 2 || Auth::user()->rol_id == 1){
            $boleta = Boleta::find($id);

            if($detector == 1){
                return View::make('boleta.showMovil', compact("boleta"));
            }else{
                return View::make('boleta.show', compact("boleta"));
            }
        }else{
            if($detector == 1){
                return View::make("errors.permisoDenegadoMovil");
            }else{
                return View::make("errors.permisoDenegado");
            }
        }
    }

    public function edit($id){
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
        
        if(Auth::user()->rol_id == 2){
            $convocatoria = Convocatoria::find(Auth::user()->convocatoria_id);
            $register = Boleta::find($id);
            
            if($convocatoria->convocatoria_estatus_id == 3){
                if($detector == 1){    
                    return View::make('boleta.editMovil', compact("register"));
                }else{
                    return View::make('boleta.edit', compact("register"));
                }
            }else{
                $mensajeDeValidacion = "No se pueden editar boletas una vez iniciada la fase de consulta";
                
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

    public function update($id){
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

        if(Auth::user()->rol_id == 2){
            $convocatoria = Convocatoria::find(Auth::user()->convocatoria_id);
            
            if($convocatoria->convocatoria_estatus_id == 3){
                $id = Crypt::decrypt($id);
                $boleta = Boleta::find($id);
                $data = Input::all();
                $validator = Boleta::validarBoleta($data);

                if ($validator->fails())
                    return Redirect::back()->with('message', 'No se pudo actualizar el registro, revisa los campos requeridos y sus condiciones')->withInput($data)->withErrors($validator->messages());

                $boleta->fill($data);
                $boleta->save();

                $bitacora = new Bitacora();
                $bitacora->accion_realizada = "Actualización de boleta";
                $bitacora->fecha_accion = Carbon::now();
                $bitacora->accion_realizada_id = $boleta->id;
                $bitacora->modulo_id = 2;
                $bitacora->usuario_id = Auth::user()->id;
                $bitacora->save();

                return Redirect::to('boleta/index/')->with('message', 'Registro Actualizado');
            }else{
                $mensajeDeValidacion = "No se pueden editar boletas una vez iniciada la fase de consulta";
                
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
    
    public function eliminaBoleta($id){
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

        if(Auth::user()->rol_id == 2){
            $convocatoria = Convocatoria::find(Auth::user()->convocatoria_id);
            
            if($convocatoria->convocatoria_estatus_id == 3){
                $boleta = Boleta::find($id);
                $boletaEliminada = $boleta->convocatoria_id;
                $boleta->deleted_at = Carbon::now();
                $boleta->save();

                $bitacora = new Bitacora();
                $bitacora->accion_realizada = "Eliminación de boleta";
                $bitacora->fecha_accion = Carbon::now();
                $bitacora->accion_realizada_id = $boletaEliminada;
                $bitacora->modulo_id = 2;
                $bitacora->usuario_id = Auth::user()->id;
                $bitacora->save();

                return Redirect::to('/boleta/index/')->with('mensaje', 'Boleta Eliminada');
            }else{
                $mensajeDeValidacion = "No se pueden eliminar boletas una vez iniciada la fase de consulta";
                
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

    public function asignarCandidato($id){
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

        if(Auth::user()->rol_id == 2){
            $convocatoria = Convocatoria::find(Auth::user()->convocatoria_id);
            
            if($convocatoria->convocatoria_estatus_id == 3){
                $register = Boleta::find($id);
                $candidatos = BoletaCandidato::where('boleta_id', $register->id)->get();

                if($detector==1){    
                    return View::make('boleta.asignarCandidatoMovil', compact("register"), compact("candidatos"));
                }else{
                    return View::make('boleta.asignarCandidato', compact("register"), compact("candidatos"));
                }
            }else{
                $mensajeDeValidacion = "No se pueden asignar candidatos una vez iniciada la fase de consulta";
                
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
    
    public function asignarCandidatoUpdate($id){
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

        if(Auth::user()->rol_id == 2){
            $convocatoria = Convocatoria::find(Auth::user()->convocatoria_id);
            
            if($convocatoria->convocatoria_estatus_id == 3){
                try{
                    $id = Crypt::decrypt($id);
                    $boleta = Boleta::find($id);
                }catch (Exception $e){
                    $mensaje = "No hay ninguna convocatoria asociada";
                    return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                }

                $data = Input::all();
                $data["curp"] = strtoupper(trim($data["curp"]));
                $validaCURP = BoletaCandidato::where('curp', $data["curp"])->where('boleta_id', $boleta->id)->first();

                if($validaCURP)
                    return Redirect::back()->with('message', 'El candidato ya está asignado a esta boleta')->withInput($data);

                $boletaCandidato = new BoletaCandidato();
                $validator = BoletaCandidato::validarBoletaCandidato($data);

                if($validator->fails())
                    return Redirect::back()->with('message', 'No se pudo actualizar el registro, revisa los campos requeridos y sus condiciones')->withInput($data)->withErrors($validator->messages());

                $boletaCandidato->fill($data);
                $boletaCandidato->boleta_id = $boleta->id;
                $boletaCandidato->convocatoria_id = $boleta->convocatoria_id;
                $boletaCandidato->save();

                $bitacora = new Bitacora();
                $bitacora->accion_realizada = "Asignación de candidato a boleta: ".$boletaCandidato->curp;
                $bitacora->fecha_accion = Carbon::now();
                $bitacora->accion_realizada_id = $boleta->convocatoria_id;
                $bitacora->modulo_id = 2;
                $bitacora->usuario_id = Auth::user()->id;
                $bitacora->save();

                return Redirect::to('/boleta/asignarCandidato/'.$boleta->id)->with('messageUpdate', 'Registro Agregado');
            }else{
                $mensajeDeValidacion = "No se pueden editar candidatos una vez iniciada la fase de consulta";
                
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
    
    public function eliminarCandidato($id){
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

        if(Auth::user()->rol_id == 2){
            $convocatoria = Convocatoria::find(Auth::user()->convocatoria_id);
            
            if($convocatoria->convocatoria_estatus_id == 3){
                $boletaCandidato = BoletaCandidato::find($id);
                $convocatoria = Boleta::find($boletaCandidato->boleta_id)->convocatoria_id;
                $boletaCandidatoEliminado = $boletaCandidato->boleta_id;
                $boletaCandidato->deleted_at = Carbon::now();
                $boletaCandidato->save();

                $bitacora = new Bitacora();
                $bitacora->accion_realizada = "Eliminación de candidato en boleta";
                $bitacora->fecha_accion = Carbon::now();
                $bitacora->accion_realizada_id = $convocatoria;
                $bitacora->modulo_id = 2;
                $bitacora->usuario_id = Auth::user()->id;
                $bitacora->save();

                return Redirect::to('/boleta/asignarCandidato/'.$boletaCandidatoEliminado)->with('messageUpdate', 'Registro Actualizado');
            }else{
                $mensajeDeValidacion = "No se pueden editar candidatos una vez iniciada la fase de consulta";
                
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
    
    public static function validaCURP($curp){
        $param = array(
            "consultaCurpProfedet"  => '***REDACTADO***',
            "curp"  => $curp
        );
        $error = null;
        $url = "https://jovenesconstruyendoelfuturo.stps.gob.mx/bc/_testCurpRen2_bk.php";
        
        try{
            $datap = curl_init();
            curl_setopt($datap, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            curl_setopt($datap, CURLOPT_URL, $url);
            curl_setopt($datap, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($datap, CURLOPT_POST, true);
            curl_setopt($datap, CURLOPT_POSTFIELDS, json_encode($param));
            @$res = curl_exec($datap);
            $httpcode = curl_getinfo($datap, CURLINFO_HTTP_CODE);

            if(curl_errno($datap)){
               $error = $error.'Curl error: '.curl_error($datap);
            }

            curl_close($datap); 
            $renapo = json_decode($res, true);
        }catch (Exception $e){
            $resultado = "5";
            return $resultado;
        }

        if($renapo){
            if($renapo['statusCurp'] == 'BD' || $renapo['statusCurp'] == 'BSU'){
                $resultado = ($renapo['statusCurp'] == 'BSU') ? "1" : "2";
                return $resultado;
            }
            if($renapo['statusOper'] == "NO EXITOSO"){
                $resultado = "3";
                return $resultado;
            }
        }else{
            $resultado = "4";
            return $resultado;
        }
        $resultado = $renapo;
        return $resultado;
    }
}