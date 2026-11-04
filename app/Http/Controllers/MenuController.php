<?php

namespace App\Http\Controllers;

use App\Models\Convocatoria;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class MenuController extends BaseController
{
    public function principal(){
        $detector = $this->detectarMovil();

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
        $detector = $this->detectarMovil();

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
        $detector = $this->detectarMovil();

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
