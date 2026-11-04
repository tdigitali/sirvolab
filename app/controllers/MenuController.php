<?php

class MenuController extends \BaseController {
    public function principal(){
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
            if($detector == 1){
                return View::make("menu.principalMovil");
            }else{
                return View::make("menu.principal");
            }
        }

        if(Auth::user()->rol_id == 2 || Auth::user()->rol_id == 3){
            $convocatoria = Convocatoria::find(Auth::user()->convocatoria_id);

            if($convocatoria){
                if($detector == 1){
                    return View::make("menu.comiteMovil", compact('convocatoria'));
                }else{
                    return View::make("menu.comite", compact('convocatoria'));
                }
            }else{
                if($detector == 1){
                    return View::make("errors.permisoDenegadoMovil");
                }else{
                    return View::make("errors.permisoDenegado");
                }
            }
        }

        if(Auth::user()->rol_id == 4){
            $convocatoria = Convocatoria::find(Auth::user()->convocatoria_id);

            if($convocatoria){
                if($detector == 1){
                    return View::make("menu.validadorMovil", compact('convocatoria'));
                }else{
                    return View::make("menu.validador", compact('convocatoria'));
                }
            }else{
                if($detector == 1){
                    return View::make("errors.permisoDenegadoMovil");
                }else{
                    return View::make("errors.permisoDenegado");
                }
            }
        }
        
        if($detector == 1){
            return View::make("errors.permisoDenegadoMovil");
        }else{
            return View::make("errors.permisoDenegado");
        }
    }
    
    public function comite(){
        $convocatoria = Convocatoria::find(Auth::user()->convocatoria_id);
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
            if($detector == 1){
                if($convocatoria){
                    return View::make("menu.comiteMovil", compact('convocatoria'));
                }else{
                    if($detector == 1){
                        return View::make("errors.permisoDenegadoMovil");
                    }else{
                        return View::make("errors.permisoDenegado");
                    }
                }
            }else{
                if($convocatoria){
                    return View::make("menu.comite", compact('convocatoria'));
                }else{
                    if($detector == 1){
                        return View::make("errors.permisoDenegadoMovil");
                    }else{
                        return View::make("errors.permisoDenegado");
                    }
                }
            }
        }
    }
    
    public function validador(){
        $convocatoria = Convocatoria::find(Auth::user()->convocatoria_id);
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

        if(Auth::user()->rol_id == 4){
            if($detector == 1){
                if($convocatoria){
                    return View::make("menu.validadorMovil", compact('convocatoria'));
                }else{
                    if($detector == 1){
                        return View::make("errors.permisoDenegadoMovil");
                    }else{
                        return View::make("errors.permisoDenegado");
                    }
                }
            }else{
                if($convocatoria){
                    return View::make("menu.validador", compact('convocatoria'));
                }else{
                    if($detector == 1){
                        return View::make("errors.permisoDenegadoMovil");
                    }else{
                        return View::make("errors.permisoDenegado");
                    }
                }
            }
        }
    }
}
