<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\Boleta;
use App\Models\BoletaCandidato;
use App\Models\Convocatoria;
use App\Services\Renapo;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;

class BoletaController extends BaseController
{
    public function index(){
        $detector = $this->detectarMovil();

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
        $detector = $this->detectarMovil();

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
        $detector = $this->detectarMovil();

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
        $detector = $this->detectarMovil();

        if(Auth::user()->rol_id == 2){
            $convocatoria = Convocatoria::find(Auth::user()->convocatoria_id);

            if($convocatoria->convocatoria_estatus_id == 3){
                $data = Request::all();
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
        $detector = $this->detectarMovil();

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
        $detector = $this->detectarMovil();

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
        $detector = $this->detectarMovil();

        if(Auth::user()->rol_id == 2){
            $convocatoria = Convocatoria::find(Auth::user()->convocatoria_id);

            if($convocatoria->convocatoria_estatus_id == 3){
                $id = Crypt::decrypt($id);
                $boleta = Boleta::find($id);
                $data = Request::all();
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
        $detector = $this->detectarMovil();

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
        $detector = $this->detectarMovil();

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
        $detector = $this->detectarMovil();

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

                $data = Request::all();
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
        $detector = $this->detectarMovil();

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

    /**
     * Consulta la CURP en RENAPO (ver App\Services\Renapo).
     */
    public static function validaCURP($curp){
        return Renapo::validaCURP($curp);
    }
}
