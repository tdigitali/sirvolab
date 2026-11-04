<?php

use Carbon\Carbon;

class VotacionController extends BaseController {
    public function voto($id, $cadena){
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
            $convocatoria = Convocatoria::find($convocatoriaPadron->convocatoria_id);
            $mensaje = null;
            $fechaActual = Carbon::now();
        }catch (Exception $e){
            $mensaje = "No es un registro asociado a una convocatoria";

            if($detector == 1){
                return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
            }else{
                return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
            }
        }

        if($convocatoria){
            if($fechaActual > $convocatoria->fecha_consulta_inicio && $fechaActual < $convocatoria->fecha_consulta_fin && $convocatoria->convocatoria_estatus_id == 4){
                if($convocatoriaPadron){
                    if(!$convocatoriaPadron->cadena_validacion == $cadena){
                        if($detector == 1){
                            return View::make("errors.permisoDenegadoMovil");
                        }else{
                            return View::make("errors.permisoDenegado");
                        }
                    }
                    $convocatoria = Convocatoria::find($convocatoriaPadron->convocatoria_id);

                    $boletas = Boleta::where('convocatoria_id', $convocatoria->id)->orderBy('numero_boleta', 'ASC')->get();
                    foreach($boletas as $boleta){
                        $validaVoto = Voto::where('boleta_id', $boleta->id)->where('convocatoria_padron_id', $convocatoriaPadron->id)->first();

                        if(!$validaVoto){
                            if($detector == 1){
                                if($convocatoria->aprobacion_confirmacion_contrato){
                                    return View::make("voto.votoMovil")->with('convocatoriaPadron', $convocatoriaPadron)->with('boleta', $boleta);
                                }else{
                                    if($convocatoria->seleccionar_dos_cargos)
                                        return View::make("voto.votoCargosMovil")->with('convocatoriaPadron', $convocatoriaPadron)->with('boleta', $boleta);
                                    else
                                        return View::make("voto.votoMovil")->with('convocatoriaPadron', $convocatoriaPadron)->with('boleta', $boleta);
                                }
                            }else{
                                if($convocatoria->aprobacion_confirmacion_contrato){
                                    return View::make("voto.voto")->with('convocatoriaPadron', $convocatoriaPadron)->with('boleta', $boleta);
                                }else{
                                    if($convocatoria->seleccionar_dos_cargos)
                                        return View::make("voto.votoCargos")->with('convocatoriaPadron', $convocatoriaPadron)->with('boleta', $boleta);
                                    else
                                        return View::make("voto.voto")->with('convocatoriaPadron', $convocatoriaPadron)->with('boleta', $boleta);
                                }
                            }
                        }
                    }

                    if($detector == 1){
                        return View::make("voto.confirmacionVotoMovil");
                    }else{
                        return View::make("voto.confirmacionVoto");
                    }
                }else{
                    $mensaje = 'No es un miembro del padrón';

                    if($detector == 1){
                        return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
                    }else{
                        return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                    }
                }
            }else{
                $mensaje = "No se cumplen las condiciones necesarias para votar en esta convocatoria";

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
    }
    
    public function realizarVoto($id, $can){
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
            $can = Crypt::decrypt($can);
            $convocatoriaPadron = ConvocatoriaPadron::find($id);
            $boletaCandidato = BoletaCandidato::find($can);
            $convocatoria = Convocatoria::find($convocatoriaPadron->convocatoria_id);
            $mensaje = null;
            $fechaActual = Carbon::now();
        }catch (Exception $e){
            $mensaje = "No es un registro asociado a una convocatoria";

            if($detector == 1){
                return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
            }else{
                return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
            }
        }

        if($convocatoria){
            if($fechaActual > $convocatoria->fecha_consulta_inicio && $fechaActual < $convocatoria->fecha_consulta_fin && $convocatoria->convocatoria_estatus_id == 4){
                if($convocatoriaPadron){
                    $convocatoria = Convocatoria::find($convocatoriaPadron->convocatoria_id);
                    if($boletaCandidato && $convocatoriaPadron){
                        $validaVoto = Voto::where('boleta_id', $boletaCandidato->boleta_id)->where('convocatoria_padron_id', $convocatoriaPadron->id)->first();
                        if(!$validaVoto){
                            $boletaCandidato->conteo = $boletaCandidato->conteo + 1;
                            $boletaCandidato->save();

                            $voto = new Voto();
                            $voto->convocatoria_padron_id = $convocatoriaPadron->id;
                            $voto->boleta_id = $boletaCandidato->boleta_id;
                            $voto->convocatoria_id = $convocatoria->id;
                            $voto->save();
                            
                            $conteo = new Conteo();
                            $conteo->boleta_id = $boletaCandidato->boleta_id;
                            $conteo->convocatoria_id = $convocatoria->id;
                            $conteo->boleta_candidato_id = $boletaCandidato->id;
                            $conteo->save();
                        }else{
                            $mensaje = "Ya realizó el voto de esta boleta";

                            if($detector == 1){
                                return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
                            }else{
                                return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                            }
                        }
                    }else{
                        $mensaje = "No es un registro asociado a una convocatoria";
                        
                        if($detector == 1){
                            return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
                        }else{
                            return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                        }
                    }
                    
                    $boletas = Boleta::where('convocatoria_id', $convocatoria->id)->orderBy('numero_boleta', 'ASC')->get();
                    foreach($boletas as $boleta){
                        $validaVoto = Voto::where('boleta_id', $boleta->id)->where('convocatoria_padron_id', $convocatoriaPadron->id)->first();

                        if(!$validaVoto){
                            if($detector == 1){
                                return View::make("voto.votoMovil")->with('convocatoriaPadron', $convocatoriaPadron)->with('boleta', $boleta);
                            }else{
                                return View::make("voto.voto")->with('convocatoriaPadron', $convocatoriaPadron)->with('boleta', $boleta);
                            }
                        }
                    }

                    if($detector == 1){
                        return View::make("voto.confirmacionVotoMovil");
                    }else{
                        return View::make("voto.confirmacionVoto");
                    }
                }else{
                    $mensaje = 'No es un miembro del padrón';

                    if($detector == 1){
                        return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
                    }else{
                        return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                    }
                }
            }else{
                $mensaje = "No se cumplen las condiciones necesarias para votar en esta convocatoria";
                
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
    }
    
    public function realizarVotoPorCargo($id){
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
            $data = Input::all();
            $id = Crypt::decrypt($id);
            $convocatoriaPadron = ConvocatoriaPadron::find($id);
            $boletaCandidato1 = BoletaCandidato::find($data["seleccion1"]);
            $boletaCandidato2 = BoletaCandidato::find($data["seleccion2"]);
            
            if(!$boletaCandidato1 || !$boletaCandidato2){
                return Redirect::back()->with('message', 'No se pudo realizar el voto, selecciona 2 opciones del listado');
            }
            
            $convocatoria = Convocatoria::find($convocatoriaPadron->convocatoria_id);
            $mensaje = null;
            $fechaActual = Carbon::now();
        }catch (Exception $e){
            $mensaje = "No es un registro asociado a una convocatoria";

            if($detector == 1){
                return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
            }else{
                return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
            }
        }

        if($convocatoria){
            if($fechaActual > $convocatoria->fecha_consulta_inicio && $fechaActual < $convocatoria->fecha_consulta_fin && $convocatoria->convocatoria_estatus_id == 4){
                if($convocatoriaPadron){
                    $convocatoria = Convocatoria::find($convocatoriaPadron->convocatoria_id);
                    if($boletaCandidato1 && $boletaCandidato2 && $convocatoriaPadron){
                        $validaVoto1 = Voto::where('boleta_id', $boletaCandidato1->boleta_id)->where('convocatoria_padron_id', $convocatoriaPadron->id)->first();
                        $validaVoto2 = Voto::where('boleta_id', $boletaCandidato2->boleta_id)->where('convocatoria_padron_id', $convocatoriaPadron->id)->first();
                        if(!$validaVoto1 && !$validaVoto2){
                            //$boletaCandidato->conteo = $boletaCandidato->conteo + 1;
                            //$boletaCandidato->save();

                            $voto = new Voto();
                            $voto->convocatoria_padron_id = $convocatoriaPadron->id;
                            $voto->boleta_id = $boletaCandidato1->boleta_id;
                            $voto->convocatoria_id = $convocatoria->id;
                            $voto->save();

                            $conteo = new Conteo();
                            $conteo->boleta_id = $boletaCandidato1->boleta_id;
                            $conteo->convocatoria_id = $convocatoria->id;
                            $conteo->boleta_candidato_id = $boletaCandidato1->id;
                            $conteo->save();
                            
                            $conteo = new Conteo();
                            $conteo->boleta_id = $boletaCandidato2->boleta_id;
                            $conteo->convocatoria_id = $convocatoria->id;
                            $conteo->boleta_candidato_id = $boletaCandidato2->id;
                            $conteo->save();
                        }else{
                            $mensaje = "Ya realizó el voto de esta boleta";

                            if($detector == 1){
                                return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
                            }else{
                                return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                            }
                        }
                    }else{
                        $mensaje = "No es un registro asociado a una convocatoria";
                        
                        if($detector == 1){
                            return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
                        }else{
                            return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                        }
                    }
                    
                    $boletas = Boleta::where('convocatoria_id', $convocatoria->id)->orderBy('numero_boleta', 'ASC')->get();
                    foreach($boletas as $boleta){
                        $validaVoto = Voto::where('boleta_id', $boleta->id)->where('convocatoria_padron_id', $convocatoriaPadron->id)->first();

                        if(!$validaVoto){
                            if($detector == 1){
                                return View::make("voto.votoCargosMovil")->with('convocatoriaPadron', $convocatoriaPadron)->with('boleta', $boleta);
                            }else{
                                return View::make("voto.votoCargos")->with('convocatoriaPadron', $convocatoriaPadron)->with('boleta', $boleta);
                            }
                        }
                    }

                    if($detector == 1){
                        return View::make("voto.confirmacionVotoMovil");
                    }else{
                        return View::make("voto.confirmacionVoto");
                    }
                }else{
                    $mensaje = 'No es un miembro del padrón';

                    if($detector == 1){
                        return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
                    }else{
                        return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                    }
                }
            }else{
                $mensaje = "No se cumplen las condiciones necesarias para votar en esta convocatoria";
                
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
    }
    
    public function anularVoto($id, $bol){
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
            $bol = Crypt::decrypt($bol);
            $convocatoriaPadron = ConvocatoriaPadron::find($id);
            $boleta = Boleta::find($bol);
            $convocatoria = Convocatoria::find($convocatoriaPadron->convocatoria_id);
            $mensaje = null;
            $fechaActual = Carbon::now();
        }catch (Exception $e){
            $mensaje = "No es un registro asociado a una convocatoria";
            
            if($detector == 1){
                return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
            }else{
                return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
            }
        }

        if($convocatoria){
            if($fechaActual > $convocatoria->fecha_consulta_inicio && $fechaActual < $convocatoria->fecha_consulta_fin && $convocatoria->convocatoria_estatus_id == 4){
                if($convocatoriaPadron){
                    $convocatoria = Convocatoria::find($convocatoriaPadron->convocatoria_id);
                    if($boleta && $convocatoriaPadron){
                        $validaVoto = Voto::where('boleta_id', $boleta->id)->where('convocatoria_padron_id', $convocatoriaPadron->id)->first();
                        if(!$validaVoto){
                            $boleta->nulos = $boleta->nulos + 1;
                            $boleta->save();

                            $voto = new Voto();
                            $voto->convocatoria_padron_id = $convocatoriaPadron->id;
                            $voto->boleta_id = $boleta->id;
                            $voto->convocatoria_id = $convocatoria->id;
                            $voto->save();
                            
                            $conteo = new Conteo();
                            $conteo->boleta_id = $boleta->id;
                            $conteo->convocatoria_id = $convocatoria->id;
                            $conteo->boleta_candidato_id = null;
                            $conteo->save();
                        }else{
                            $mensaje = "Ya realizó el voto de esta boleta";

                            if($detector == 1){
                                return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
                            }else{
                                return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                            }
                        }
                    }else{
                        $mensaje = "No es un registro asociado a una convocatoria";
                        
                        if($detector == 1){
                            return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
                        }else{
                            return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                        }
                    }
                    
                    $boletas = Boleta::where('convocatoria_id', $convocatoria->id)->orderBy('numero_boleta', 'ASC')->get();
                    foreach($boletas as $boleta){
                        $validaVoto = Voto::where('boleta_id', $boleta->id)->where('convocatoria_padron_id', $convocatoriaPadron->id)->first();

                        if(!$validaVoto){
                            if($detector == 1){
                                return View::make("voto.votoMovil")->with('convocatoriaPadron', $convocatoriaPadron)->with('boleta', $boleta);
                            }else{
                                return View::make("voto.voto")->with('convocatoriaPadron', $convocatoriaPadron)->with('boleta', $boleta);
                            }
                        }
                    }

                    if($detector == 1){
                        return View::make("voto.confirmacionVotoMovil");
                    }else{
                        return View::make("voto.confirmacionVoto");
                    }
                }else{
                    $mensaje = 'No es miembro del padrón';
                    
                    if($detector == 1){
                        return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
                    }else{
                        return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                    }
                }
            }else{
                $mensaje = "No se cumplen las condiciones necesarias para votar en esta convocatoria";
                
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
    }
    
    public function index(){
        $miuseragent = $_SERVER['HTTP_USER_AGENT'];
        $moviles = array("Mobile","iPhone","iPod","BlackBerry","Opera Mini","Sony","MOT","Nokia","samsung");
        $detector = 0;
        $numMoviles = count($moviles);
        $banderaError = null;

        for($i=0;$i<$numMoviles;$i++){
            $comprobar = strpos($miuseragent,$moviles[$i]);
            if($comprobar != ""){
                $detector = 1;
            }
        }

        if(Auth::user()->rol_id == 2 || Auth::user()->rol_id == 3){
            $convocatoria = Convocatoria::find(Auth::user()->convocatoria_id);
            if($convocatoria->convocatoria_estatus_id == 5){
                $boletas = Boleta::where('convocatoria_id', Auth::user()->convocatoria_id)->get();
                $resultadoFin = array();
                
                foreach($boletas as $boleta){
                    $resultado = 0;
                    foreach(BoletaCandidato::where('boleta_id', $boleta->id)->get() as $boletaCandidato){
                        $conteo = count(Conteo::where('boleta_candidato_id', $boletaCandidato->id)->get());
                        
                        if($conteo > $resultado)
                            $resultado = $conteo;
                    }
                    $resultadoFin[] = $resultado;
                }
                
                if($detector == 1){
                    return View::make("voto.indexMovil", compact("boletas"), compact("resultadoFin"))->with("banderaError", $banderaError);
                }else{
                    return View::make("voto.index", compact("boletas"), compact("resultadoFin"))->with("banderaError", $banderaError);
                }
            }else{
                $mensajeDeValidacion = "No se ha autrizado el cierre de la convocatoria";
                
                if($detector == 1){
                    return View::make('errors.autorizacionDenegadaMovil', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion)->with("banderaError", $banderaError);
                }else{
                    return View::make('errors.autorizacionDenegada', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion)->with("banderaError", $banderaError);
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
    
    public function descargaActa($id){
        if(Auth::user()->rol_id == 2 || Auth::user()->rol_id == 3){
            $convocatoria = Convocatoria::find(Auth::user()->convocatoria_id);
            $boleta = Boleta::find($id);
            $cadenaRandom = str_random(10);

            if($convocatoria->aprobacion_confirmacion_contrato){
                $formatoActa = public_path().'/acta/Acta_de_Resultados.docx';
            }else{
                $formatoActa = public_path().'/acta/Acta_de_Resultados_Cargos.docx';
            }

            $actaResultados = public_path().'/acta/Acta_'.$cadenaRandom.'.docx';
            copy($formatoActa, $actaResultados);
            $boleta->ruta_acta = $actaResultados;
            $boleta->save();
            $dataFile = "word/document.xml";
            $zip = new ZipArchive;
            $banderaVotoAFavor = null;
            $tokenNombreConvocatoria = $convocatoria->nombre;
            $tokenSindicato  = $convocatoria->nombre_sindicato;
            $tokenBoleta = $boleta->cargo_postulacion;
            $tokenFechaInicial  = $convocatoria->fecha_consulta_inicio;
            $tokenFechafinal  = $convocatoria->fecha_consulta_fin;
            $tokenAdminProcesos = null;
            $tokenComite = null;
            $tokentotalVotos = null;

            foreach(Usuario::where('convocatoria_id', $convocatoria->id)->where('rol_id', 2)->get() as $usuario){
                $tokenAdminProcesos  = $tokenAdminProcesos.$usuario->nombre.' '.$usuario->apellido_paterno.' '.$usuario->apellido_materno.' / ';
            }

            foreach(Usuario::where('convocatoria_id', $convocatoria->id)->where('rol_id', 3)->get() as $usuario){
                $tokenComite  = $tokenComite.$usuario->nombre.' '.$usuario->apellido_paterno.' '.$usuario->apellido_materno.' / ';
            }

            $tokenDerechoAVoto  = count(ConvocatoriaPadron::where('convocatoria_id', $convocatoria->id)->get());
            $tokenPersonasRegistradas  = count(ConvocatoriaPadron::where('convocatoria_id', $convocatoria->id)->whereNotNull('fecha_validacion')->get());
            $tokenVotosAFavor = null;
            $tokenVotosEnContra = null;

            foreach(BoletaCandidato::where('boleta_id', $boleta->id)->orderBy('id', 'ASC')->get() as $boletaCandidato){
                if(!$banderaVotoAFavor){
                    $tokenVotosAFavor  = count(Conteo::where('boleta_candidato_id', $boletaCandidato->id)->get());
                    $banderaVotoAFavor = true;
                }else
                    $tokenVotosEnContra  = count(Conteo::where('boleta_candidato_id', $boletaCandidato->id)->get());
            }

            $tokenvotosNulos  = count(Conteo::where('boleta_candidato_id', null)->where('boleta_id', $boleta->id)->get());
            $tokentotalVotos  = $tokenVotosAFavor + $tokenVotosEnContra + $tokenvotosNulos;
            
            $hoy = Carbon::now();
            $dia = Carbon::parse($hoy)->format('j');
            $mes = Carbon::parse($hoy)->format('n');
            $anio = Carbon::parse($hoy)->format('Y');

            if($mes == 1 )$mes = 'enero';
            if($mes == 2 )$mes = 'febrero';
            if($mes == 3 )$mes = 'marzo';
            if($mes == 4 )$mes = 'abril';
            if($mes == 5 )$mes = 'mayo';
            if($mes == 6 )$mes = 'junio';
            if($mes == 7 )$mes = 'julio';
            if($mes == 8 )$mes = 'agosto';
            if($mes == 9 )$mes = 'septiembre';
            if($mes == 10 )$mes = 'octubre';
            if($mes == 11 )$mes = 'noviembre';
            if($mes == 12 )$mes = 'diciembre';
            
            $tokenfechaActual = 'Ciudad de México a '.$dia.' de '.$mes.' de '.$anio;
            $votosAFavorCan1 = null;
            $votosAFavorCan2 = null;
            $votosAFavorCan3 = null;
            $votosAFavorCan4 = null;
            $votosAFavorCan5 = null;
            $votosAFavorCan6 = null;
            $votosAFavorCan7 = null;
            $votosAFavorCan8 = null;
            $votosAFavorCan9 = null;
            
            if(!$convocatoria->aprobacion_confirmacion_contrato){
                $cont = 1;
                $votos1 = 0;
                $votos2 = 0;
                $votos3 = 0;
                $votos4 = 0;
                $votos5 = 0;
                $votos6 = 0;
                $votos7 = 0;
                $votos8 = 0;
                $votos9 = 0;

                foreach(BoletaCandidato::where('boleta_id', $boleta->id)->get() as $boletaCandidato){
                    if($cont == 1){
                        $votosAFavorCan1 = $boletaCandidato->nombre_completo.' = '.count(Conteo::where('boleta_candidato_id', $boletaCandidato->id)->get());
                        $votos1 = count(Conteo::where('boleta_candidato_id', $boletaCandidato->id)->get());
                    }
                    
                    if($cont == 2){
                        $votosAFavorCan2 = $boletaCandidato->nombre_completo.' = '.count(Conteo::where('boleta_candidato_id', $boletaCandidato->id)->get());
                        $votos2 = count(Conteo::where('boleta_candidato_id', $boletaCandidato->id)->get());
                    }
                    
                    if($cont == 3){
                        $votosAFavorCan3 = $boletaCandidato->nombre_completo.' = '.count(Conteo::where('boleta_candidato_id', $boletaCandidato->id)->get());
                        $votos3 = count(Conteo::where('boleta_candidato_id', $boletaCandidato->id)->get());
                    }
                    
                    if($cont == 4){
                        $votosAFavorCan4 = $boletaCandidato->nombre_completo.' = '.count(Conteo::where('boleta_candidato_id', $boletaCandidato->id)->get());
                        $votos4 = count(Conteo::where('boleta_candidato_id', $boletaCandidato->id)->get());
                    }
                    
                    if($cont == 5){
                        $votosAFavorCan5 = $boletaCandidato->nombre_completo.' = '.count(Conteo::where('boleta_candidato_id', $boletaCandidato->id)->get());
                        $votos5 = count(Conteo::where('boleta_candidato_id', $boletaCandidato->id)->get());
                    }
                    
                    if($cont == 6){
                        $votosAFavorCan6 = $boletaCandidato->nombre_completo.' = '.count(Conteo::where('boleta_candidato_id', $boletaCandidato->id)->get());
                        $votos6 = count(Conteo::where('boleta_candidato_id', $boletaCandidato->id)->get());
                    }
                    
                    if($cont == 7){
                        $votosAFavorCan7 = $boletaCandidato->nombre_completo.' = '.count(Conteo::where('boleta_candidato_id', $boletaCandidato->id)->get());
                        $votos7 = count(Conteo::where('boleta_candidato_id', $boletaCandidato->id)->get());
                    }
                    
                    if($cont == 8){
                        $votosAFavorCan8 = $boletaCandidato->nombre_completo.' = '.count(Conteo::where('boleta_candidato_id', $boletaCandidato->id)->get());
                        $votos8 = count(Conteo::where('boleta_candidato_id', $boletaCandidato->id)->get());
                    }
                    
                    if($cont == 9){
                        $votosAFavorCan9 = $boletaCandidato->nombre_completo.' = '.count(Conteo::where('boleta_candidato_id', $boletaCandidato->id)->get());
                        $votos9 = count(Conteo::where('boleta_candidato_id', $boletaCandidato->id)->get());
                    }
                    
                    $cont = $cont + 1;
                }

                if($convocatoria->seleccionar_dos_cargos){
                    $tokenvotosNulos = $tokenvotosNulos * 2;
                }

                $tokentotalVotos = $votos1 + $votos2 + $votos3 + $votos4 + $votos5 + $votos6 + $votos7 + $votos8 + $votos9 + $tokenvotosNulos;
            }

            if ($zip->open($actaResultados, ZipArchive::CREATE) !== TRUE) {
                return Redirect::back()->with('message', 'No se pudo actualizar el registro');
            }

            $xml = $zip->getFromName('word/document.xml');
            $xml = str_replace('@nombreConvocatoria', $tokenNombreConvocatoria, $xml);
            $xml = str_replace('@sindicato', $tokenSindicato, $xml);
            $xml = str_replace('@fechaInicial', $tokenFechaInicial, $xml);
            $xml = str_replace('@fechaFinal', $tokenFechafinal, $xml);
            $xml = str_replace('@adminProcesos', $tokenAdminProcesos, $xml);
            $xml = str_replace('@comite', $tokenComite, $xml);
            $xml = str_replace('@derechoAVoto', $tokenDerechoAVoto, $xml);
            $xml = str_replace('@personasRegistradas', $tokenPersonasRegistradas, $xml);
            $xml = str_replace('@votosAFavor', $tokenVotosAFavor, $xml);
            $xml = str_replace('@votosEnContra', $tokenVotosEnContra, $xml);
            $xml = str_replace('@votosNulos', $tokenvotosNulos, $xml);
            $xml = str_replace('@totalVotos', $tokentotalVotos, $xml);
            $xml = str_replace('@boleta', $tokenBoleta, $xml);

            if(!$convocatoria->aprobacion_confirmacion_contrato){
                $xml = str_replace('@canResultado1', $votosAFavorCan1, $xml);
                $xml = str_replace('@canResultado2', $votosAFavorCan2, $xml);
                $xml = str_replace('@canResultado3', $votosAFavorCan3, $xml);
                $xml = str_replace('@canResultado4', $votosAFavorCan4, $xml);
                $xml = str_replace('@canResultado5', $votosAFavorCan5, $xml);
                $xml = str_replace('@canResultado6', $votosAFavorCan6, $xml);
                $xml = str_replace('@canResultado7', $votosAFavorCan7, $xml);
                $xml = str_replace('@canResultado8', $votosAFavorCan8, $xml);
                $xml = str_replace('@canResultado9', $votosAFavorCan9, $xml);
            }
            $xml = str_replace('@fechaActual', $tokenfechaActual, $xml);
            $zip->addFromString('word/document.xml', $xml);
            $zip->close();

            return Response::download($actaResultados);
        }else
            return View::make("errors.permisoDenegado");        
    }
    
    public function cargaActa(){
        $miuseragent = $_SERVER['HTTP_USER_AGENT'];
        $moviles = array("Mobile","iPhone","iPod","BlackBerry","Opera Mini","Sony","MOT","Nokia","samsung");
        $detector = 0;
        $numMoviles = count($moviles);
        $mensaje = null;

        for($i=0;$i<$numMoviles;$i++){
            $comprobar = strpos($miuseragent,$moviles[$i]);
            if($comprobar != ""){
                $detector = 1;
            }
        }

        if(Auth::user()->rol_id == 2 || Auth::user()->rol_id == 3){
            if(Input::hasFile("ruta_acta")){
                $convocatoria = Convocatoria::find(Auth::user()->convocatoria_id);
                $path = "/actaFirmada/".$convocatoria->id."/";
                $banderaError = null;
                $mensaje = '¡El acta ha sido cargada exitosamente!';

            try{
                if(!File::isDirectory(public_path().$path)){
                    File::makeDirectory(public_path().$path);
                }

                $cadenaRandom = str_random(15);
                $archivo = Input::file("ruta_acta");
                $nombreArchivo = $cadenaRandom.'.'.$archivo->getClientOriginalExtension();;
                $archivo->move(public_path().$path, $nombreArchivo);
                $convocatoria->ruta_acta = $path.$nombreArchivo;
                $convocatoria->save();

                $bitacora = new Bitacora();
                $bitacora->accion_realizada = "Carga de acta de cierre de consulta";
                $bitacora->fecha_accion = Carbon::now();
                $bitacora->accion_realizada_id = $convocatoria->id;
                $bitacora->modulo_id = 2;
                $bitacora->usuario_id = Auth::user()->id;
                $bitacora->save();

                if($detector == 1){
                    return View::make("voto.resultadoActaMovil")->with("banderaError", $banderaError)->with("mensaje", $mensaje);
                }else{
                    return View::make("voto.resultadoActa")->with("banderaError", $banderaError)->with("mensaje", $mensaje);
                }
            }catch(Exception $e){
                $banderaError = true;
                $mensaje = 'Ha ocurrido un error en la carga del acta: '.$e;

                if($detector == 1){
                    return View::make("voto.resultadoActaMovil")->with("banderaError", $banderaError)->with("mensaje", $mensaje);
                }else{
                    return View::make("voto.resultadoActa")->with("banderaError", $banderaError)->with("mensaje", $mensaje);
                }
            }
        }else{
            $mensaje = 'No se cargó el acta de la consulta';
            $banderaError = true;
            if($detector==1){
                return View::make("voto.resultadoActaMovil")->with("banderaError", $banderaError)->with("mensaje", $mensaje);
            }else{
                return View::make("voto.resultadoActa")->with("banderaError", $banderaError)->with("mensaje", $mensaje);
            }
        }
        }else
            return View::make("errors.permisoDenegado");        
    }
    
    public function descargaActaFirmada($id, $cad){
        if(Auth::user()->rol_id == 1){
            $convocatoria = Convocatoria::find($id);
            
            if($convocatoria->ruta_acta != null && $convocatoria->ruta_acta != '')
                $actaFirmada = public_path().$convocatoria->ruta_acta;
            else
                return Redirect::to('convocatoria/index/');
            
            return Response::download($actaFirmada);
        }
    }
    
    public function cerrarConvocatoria($id){
        if(Auth::user()->rol_id == 1){
            $convocatoria = Convocatoria::find($id);
            
            if($convocatoria->convocatoria_estatus_id == 5){
                $convocatoria->convocatoria_estatus_id = 6;
                $convocatoria->save();
                
                $usuarios = Usuario::where('convocatoria_id', $convocatoria->id)->get();
                
                foreach($usuarios as $usuario){
                    $usuario->convocatoria_id = null;
                    $usuario->enabled = null;
                    $usuario->password = Hash::make(str_random(10));
                    $usuario->save();
                }
                
                $bitacora = new Bitacora();
                $bitacora->accion_realizada = "Cierre de convocatoria";
                $bitacora->fecha_accion = Carbon::now();
                $bitacora->accion_realizada_id = $convocatoria->id;
                $bitacora->modulo_id = 2;
                $bitacora->usuario_id = Auth::user()->id;
                $bitacora->save();                
                
            }

            $actaFirmada = public_path().$convocatoria->ruta_acta;
            return Redirect::to('convocatoria/indexCerradas/');
        }
    }
}