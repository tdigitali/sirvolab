<?php

namespace App\Http\Controllers;

use App\Models\Bitacora;
use App\Models\Boleta;
use App\Models\BoletaCandidato;
use App\Models\Convocatoria;
use App\Models\ConvocatoriaAutorizacion;
use App\Models\ConvocatoriaCentroTrabajo;
use App\Models\ConvocatoriaPadron;
use App\Models\ConvocatoriaPadronCargado;
use App\Models\EntidadFederativa;
use App\Models\MotivoRechazo;
use App\Models\Usuario;
use App\Models\Voto;
use App\Services\Renapo;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ConvocatoriaController extends BaseController
{
    public function __construct()
    {
        // Antes era un ini_set() al inicio del archivo: cargas de padrón y
        // archivos pueden tardar mucho (consulta a RENAPO por cada CURP).
        ini_set('max_execution_time', '10000');
    }

    public function pruebaRENAPO(){
        $curp = "MIMA951207MBCLNN07";
        $error = null;

        $renapo = Renapo::consultar(strtoupper($curp));
        $temp = ($renapo && $renapo != null) ? '{"success": true, "data": ' . json_encode($renapo) . '}' : '{"success": false}';

        if($renapo){
            if($renapo['statusCurp'] == 'BD' || $renapo['statusCurp'] == 'BSU'){
                $error = ($renapo['statusCurp'] == 'BSU') ? "La CURP está dada de baja en RENAPO" : "La CURP está dada de baja por defunción";
            }
        }else{
            $error = "No se pudo localizar la curp";
        }

        return View::make('convocatoria.pruebaRENAPO')->with("curp", $curp)->with("response", $temp)->with("error", $error);
    }

    public function index(){
        $detector = $this->detectarMovil();

        if(Auth::user()->rol_id == 1){
            if($detector == 1){
                $convocatorias = Convocatoria::where('convocatoria_estatus_id', '!=', 6)->get();
                return View::make("convocatoria.indexMovil", compact("convocatorias"));
            }else{
                $convocatorias = Convocatoria::where('convocatoria_estatus_id', '!=', 6)->get();
                return View::make("convocatoria.index", compact("convocatorias"));
            }
        }else{
            if($detector == 1){
                return View::make("errors.permisoDenegadoMovil");
            }else{
                return View::make("errors.permisoDenegado");
            }
        }
    }

    public function indexCerradas(){
        $detector = $this->detectarMovil();

        if(Auth::user()->rol_id == 1){
            if($detector == 1){
                $convocatorias = Convocatoria::where('convocatoria_estatus_id', 6)->get();
                return View::make("convocatoria.indexCerradasMovil", compact("convocatorias"));
            }else{
                $convocatorias = Convocatoria::where('convocatoria_estatus_id', 6)->get();
                return View::make("convocatoria.indexCerradas", compact("convocatorias"));
            }
        }else{
            if($detector == 1){
                return View::make("errors.permisoDenegadoMovil");
            }else{
                return View::make("errors.permisoDenegado");
            }
        }
    }

    public function indexPadron($id){
        $detector = $this->detectarMovil();

        if(Auth::user()->rol_id == 1){
            try{
                $id = Crypt::decrypt($id);
                $convocatoria = Convocatoria::find($id);
                //$convocatoriaPadron = ConvocatoriaPadron::where('convocatoria_id', $id)->get();
            }catch (Exception $e){
                $mensaje = "No hay ninguna convocatoria asociada";

                if($detector == 1){
                    return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
                }else{
                    return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                }
            }

            if($detector == 1){
                //return View::make("convocatoria.indexPadronMovil", compact("convocatoriaPadron"), compact("convocatoria"));
                return View::make("convocatoria.indexPadronMovil", compact("convocatoria"));
            }else{
                //return View::make("convocatoria.indexPadron", compact("convocatoriaPadron"), compact("convocatoria"));
                return View::make("convocatoria.indexPadron", compact("convocatoria"));
            }
        }else{
            if($detector == 1){
                return View::make("errors.permisoDenegadoMovil");
            }else{
                return View::make("errors.permisoDenegado");
            }
        }
    }

    public function indexPadronConsulta($id, $tipo){
        $detector = $this->detectarMovil();

        if(Auth::user()->rol_id == 1){
            try{
                $id = Crypt::decrypt($id);
                $convocatoria = Convocatoria::find($id);
                $convocatoriaPadron = null;
                if($tipo == 1){
                    $convocatoriaPadron = ConvocatoriaPadron::where('convocatoria_id', $id)->whereNotNull('fecha_registro')->paginate(200);
                }
                if($tipo == 2){
                    $convocatoriaPadron = ConvocatoriaPadron::where('convocatoria_id', $id)->where('fecha_registro', null)->paginate(200);
                }
                if($tipo == 3){
                    $convocatoriaPadron = ConvocatoriaPadron::where('convocatoria_id', $id)->whereNotNull('fecha_rechazo')->paginate(200);
                }
                if($tipo == 4){
                    $convocatoriaPadron = ConvocatoriaPadron::where('convocatoria_id', $id)->whereNotNull('fecha_validacion')->paginate(200);
                }
                if($tipo == 5){
                    $convocatoriaPadron = Voto::where('convocatoria_id', $id)->paginate(200);
                }

            }catch (Exception $e){
                $mensaje = "No hay ninguna convocatoria asociada";

                if($detector == 1){
                    return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
                }else{
                    return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                }
            }

            if($detector == 1){
                return View::make("convocatoria.indexPadronConsultaMovil", compact("convocatoriaPadron"), compact("convocatoria"))->with('tipo', $tipo);
            }else{
                return View::make("convocatoria.indexPadronConsulta", compact("convocatoriaPadron"), compact("convocatoria"))->with('tipo', $tipo);
            }
        }else{
            if($detector == 1){
                return View::make("errors.permisoDenegadoMovil");
            }else{
                return View::make("errors.permisoDenegado");
            }
        }
    }

    public function indexPadronComite(){
        $detector = $this->detectarMovil();

        if(Auth::user()->rol_id == 2 || Auth::user()->rol_id == 3){
            $convocatoria = Convocatoria::find(Auth::user()->convocatoria_id);
            $convocatoriaPadron = ConvocatoriaPadron::where('convocatoria_id', $convocatoria->id)->get();

            if($convocatoria->convocatoria_estatus_id != 6){
                if($detector == 1){
                    return View::make("convocatoria.indexPadronComiteMovil", compact("convocatoriaPadron"), compact("convocatoria"));
                }else{
                    return View::make("convocatoria.indexPadronComite", compact("convocatoriaPadron"), compact("convocatoria"));
                }
            }else{
                if($detector == 1){
                    return View::make("errors.permisoDenegadoMovil");
                }else{
                    return View::make("errors.permisoDenegado");
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

    public function indexPadronComiteConsulta($tipo){
        $detector = $this->detectarMovil();

        if(Auth::user()->rol_id == 2 || Auth::user()->rol_id == 3){
            try{
                $convocatoria = Convocatoria::find(Auth::user()->convocatoria_id);
                $convocatoriaPadron = null;
                if($tipo == 1){
                    $convocatoriaPadron = ConvocatoriaPadron::where('convocatoria_id', $convocatoria->id)->whereNotNull('fecha_registro')->paginate(200);
                }
                if($tipo == 2){
                    $convocatoriaPadron = ConvocatoriaPadron::where('convocatoria_id', $convocatoria->id)->where('fecha_registro', null)->paginate(200);
                }
                if($tipo == 3){
                    $convocatoriaPadron = ConvocatoriaPadron::where('convocatoria_id', $convocatoria->id)->whereNotNull('fecha_rechazo')->paginate(200);
                }
                if($tipo == 4){
                    $convocatoriaPadron = ConvocatoriaPadron::where('convocatoria_id', $convocatoria->id)->whereNotNull('fecha_validacion')->paginate(200);
                }
                if($tipo == 5){
                    $convocatoriaPadron = Voto::where('convocatoria_id', $convocatoria->id)->paginate(200);
                }

            }catch (Exception $e){
                $mensaje = "No hay ninguna convocatoria asociada";

                if($detector == 1){
                    return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
                }else{
                    return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                }
            }

            if($detector == 1){
                return View::make("convocatoria.indexPadronComiteConsultaMovil", compact("convocatoriaPadron"), compact("convocatoria"))->with('tipo', $tipo);
            }else{
                return View::make("convocatoria.indexPadronComiteConsulta", compact("convocatoriaPadron"), compact("convocatoria"))->with('tipo', $tipo);
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

        if(Auth::user()->rol_id == 1){
            if($detector == 1){
                return View::make("convocatoria.createMovil");
            }else{
                return View::make("convocatoria.create");
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

        if(Auth::user()->rol_id == 1){
            $data = Request::all();
            $convocatoria = new Convocatoria();
            $validator = Convocatoria::validarConvocatoria($data, null);
            $horaInicial = $data['fecha_registro_validacion_inicio_hora'];
            $message = null;

            if($validator->fails())
                return Redirect::back()->with('message', 'No se pudo crear el registro, revisa los campos requeridos y sus condiciones')->with('horaInicial', $horaInicial)->withInput($data)->withErrors($validator->messages());

            $convocatoria->fill($data);
            $convocatoria->convocatoria_estatus_id = 1;
            $convocatoria->convocatoria_activa = true;
            $convocatoria->fecha_registro_validacion_inicio = $convocatoria->fecha_registro_validacion_inicio.' '.$data['fecha_registro_validacion_inicio_hora'].':'.$data['fecha_registro_validacion_inicio_minu'];
            $convocatoria->fecha_registro_validacion_fin = $convocatoria->fecha_registro_validacion_fin.' '.$data['fecha_registro_validacion_fin_hora'].':'.$data['fecha_registro_validacion_fin_minu'];
            $convocatoria->fecha_consulta_inicio = $convocatoria->fecha_consulta_inicio.' '.$data['fecha_consulta_inicio_hora'].':'.$data['fecha_consulta_inicio_minu'];
            $convocatoria->fecha_consulta_fin = $convocatoria->fecha_consulta_fin.' '.$data['fecha_consulta_fin_hora'].':'.$data['fecha_consulta_fin_minu'];

            //Segmento de validación de registro validación inicio
            if($convocatoria->fecha_registro_validacion_fin < $convocatoria->fecha_registro_validacion_inicio){
                return Redirect::back()->with('message', 'No se pudo crear el registro, la fecha de fin de registro validación es menor a la fecha de inicio de registro validación')->withInput($data)->withErrors($validator->messages());
            }
            if($convocatoria->fecha_consulta_inicio < $convocatoria->fecha_registro_validacion_inicio){
                return Redirect::back()->with('message', 'No se pudo crear el registro, la fecha de inicio de la consulta es menor a la fecha de inicio de registro validación')->withInput($data)->withErrors($validator->messages());
            }
            if($convocatoria->fecha_consulta_fin < $convocatoria->fecha_registro_validacion_inicio){
                return Redirect::back()->with('message', 'No se pudo crear el registro, la fecha de fin de la consulta es menor a la fecha de inicio de registro validación')->withInput($data)->withErrors($validator->messages());
            }

            //Segmento de validación de registro validación fin
            if($convocatoria->fecha_consulta_inicio < $convocatoria->fecha_registro_validacion_fin){
                return Redirect::back()->with('message', 'No se pudo crear el registro, la fecha de inicio de la consulta es menor a la fecha de fin de registro validación')->withInput($data)->withErrors($validator->messages());
            }
            if($convocatoria->fecha_consulta_fin < $convocatoria->fecha_registro_validacion_fin){
                return Redirect::back()->with('message', 'No se pudo crear el registro, la fecha de fin de la consulta es menor a la fecha de fin de registro validación')->withInput($data)->withErrors($validator->messages());
            }

            //Segmento de validación de inicio de la consulta
            if($convocatoria->fecha_consulta_fin < $convocatoria->fecha_consulta_inicio){
                return Redirect::back()->with('message', 'No se pudo crear el registro, la fecha de fin de la consulta es menor a la fecha de inicio de la consulta')->withInput($data)->withErrors($validator->messages());
            }

            $convocatoria->save();

            $bitacora = new Bitacora();
            $bitacora->accion_realizada = "Creación de convocatoria";
            $bitacora->fecha_accion = Carbon::now();
            $bitacora->accion_realizada_id = $convocatoria->id;
            $bitacora->modulo_id = 2;
            $bitacora->usuario_id = Auth::user()->id;
            $bitacora->save();

            return Redirect::to('convocatoria/index/');
        }else{
            if($detector == 1){
                return View::make("errors.permisoDenegadoMovil");
            }else{
                return View::make("errors.permisoDenegado");
            }
        }
    }

    public function terminarConvocatoria($id){
        $detector = $this->detectarMovil();

        if(Auth::user()->rol_id == 1){
            $id = Crypt::decrypt($id);
            $register = Convocatoria::find($id);

            if($detector == 1){
                return View::make('convocatoria.terminarConvocatoriaMovil', compact("register"));
            }else{
                return View::make('convocatoria.terminarConvocatoria', compact("register"));
            }
        }else{
            if($detector == 1){
                return View::make("errors.permisoDenegadoMovil");
            }else{
                return View::make("errors.permisoDenegado");
            }
        }
    }

    public function terminarConvocatoriaUpdate($id){
        if(Auth::user()->rol_id == 1){
            $data = Request::all();
            $id = Crypt::decrypt($id);

            $validator = Convocatoria::validarTerminarConvocatoria($data, null);

            if($validator->fails())
                return Redirect::back()->with('message', 'Debes escribir la razón del termino anticipado')->withInput($data)->withErrors($validator->messages());

            $convocatoria = Convocatoria::find($id);
            $convocatoria->razon_termino = $data["razon_termino"];
            $convocatoria->convocatoria_estatus_id = 6;
            $convocatoria->save();

            $usuarios = Usuario::where('convocatoria_id', $convocatoria->id)->get();

            foreach($usuarios as $usuario){
                $usuario->convocatoria_id = null;
                $usuario->enabled = null;
                $usuario->password = Hash::make(Str::random(10));
                $usuario->save();
            }

            $bitacora = new Bitacora();
            $bitacora->accion_realizada = "Cierre de convocatoria Anticipado";
            $bitacora->fecha_accion = Carbon::now();
            $bitacora->accion_realizada_id = $convocatoria->id;
            $bitacora->modulo_id = 2;
            $bitacora->usuario_id = Auth::user()->id;
            $bitacora->save();

            return Redirect::to('convocatoria/indexCerradas/');
        }
    }

    public function edit($id){
        $detector = $this->detectarMovil();

        if(Auth::user()->rol_id == 1){
            if($detector == 1){
                $register = Convocatoria::find($id);
                return View::make('convocatoria.editMovil', compact("register"));
            }else{
                $register = Convocatoria::find($id);
                return View::make('convocatoria.edit', compact("register"));
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

        if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 2 || Auth::user()->rol_id == 3){
            if($detector == 1){
                $register = Convocatoria::find($id);
                return View::make('convocatoria.showMovil', compact("register"));
            }else{
                $register = Convocatoria::find($id);
                return View::make('convocatoria.show', compact("register"));
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

        if(Auth::user()->rol_id == 1){
            $id = Crypt::decrypt($id);
            $convocatoria = Convocatoria::find($id);
            $data = Request::all();
            $validator = Convocatoria::validarConvocatoria($data, $id);

            if ($validator->fails())
                return Redirect::back()->with('message', 'No se pudo actualizar el registro, revisa los campos requeridos y sus condiciones')->withInput($data)->withErrors($validator->messages());

            $convocatoria->fill($data);
            $convocatoria->aprobacion_confirmacion_contrato = isset($data["aprobacion_confirmacion_contrato"])? true : null;
			$convocatoria->seleccionar_dos_cargos = isset($data["seleccionar_dos_cargos"])? true : null;
            $convocatoria->fecha_registro_validacion_inicio = $convocatoria->fecha_registro_validacion_inicio.' '.$data['fecha_registro_validacion_inicio_hora'].':'.$data['fecha_registro_validacion_inicio_minu'];
            $convocatoria->fecha_registro_validacion_fin = $convocatoria->fecha_registro_validacion_fin.' '.$data['fecha_registro_validacion_fin_hora'].':'.$data['fecha_registro_validacion_fin_minu'];
            $convocatoria->fecha_consulta_inicio = $convocatoria->fecha_consulta_inicio.' '.$data['fecha_consulta_inicio_hora'].':'.$data['fecha_consulta_inicio_minu'];
            $convocatoria->fecha_consulta_fin = $convocatoria->fecha_consulta_fin.' '.$data['fecha_consulta_fin_hora'].':'.$data['fecha_consulta_fin_minu'];

            //Segmento de validación de registro validación inicio
            if($convocatoria->fecha_registro_validacion_fin < $convocatoria->fecha_registro_validacion_inicio){
                return Redirect::back()->with('message', 'No se pudo actualizar el registro, la fecha de fin de registro validación es menor a la fecha de inicio de registro validación')->withInput($data)->withErrors($validator->messages());
            }
            if($convocatoria->fecha_consulta_inicio < $convocatoria->fecha_registro_validacion_inicio){
                return Redirect::back()->with('message', 'No se pudo actualizar el registro, la fecha de inicio de la consulta es menor a la fecha de inicio de registro validación')->withInput($data)->withErrors($validator->messages());
            }
            if($convocatoria->fecha_consulta_fin < $convocatoria->fecha_registro_validacion_inicio){
                return Redirect::back()->with('message', 'No se pudo actualizar el registro, la fecha de fin de la consulta es menor a la fecha de inicio de registro validación')->withInput($data)->withErrors($validator->messages());
            }

            //Segmento de validación de registro validación fin
            if($convocatoria->fecha_consulta_inicio < $convocatoria->fecha_registro_validacion_fin){
                return Redirect::back()->with('message', 'No se pudo actualizar el registro, la fecha de inicio de la consulta es menor a la fecha de fin de registro validación')->withInput($data)->withErrors($validator->messages());
            }
            if($convocatoria->fecha_consulta_fin < $convocatoria->fecha_registro_validacion_fin){
                return Redirect::back()->with('message', 'No se pudo actualizar el registro, la fecha de fin de la consulta es menor a la fecha de fin de registro validación')->withInput($data)->withErrors($validator->messages());
            }

            //Segmento de validación de inicio de la consulta
            if($convocatoria->fecha_consulta_fin < $convocatoria->fecha_consulta_inicio){
                return Redirect::back()->with('message', 'No se pudo actualizar el registro, la fecha de fin de la consulta es menor a la fecha de inicio de la consulta')->withInput($data)->withErrors($validator->messages());
            }

            $convocatoria->save();

            $bitacora = new Bitacora();
            $bitacora->accion_realizada = "Actualización de convocatoria";
            $bitacora->fecha_accion = Carbon::now();
            $bitacora->accion_realizada_id = $convocatoria->id;
            $bitacora->modulo_id = 2;
            $bitacora->usuario_id = Auth::user()->id;
            $bitacora->save();

            return Redirect::to('convocatoria/index/');
        }else{
            if($detector == 1){
                return View::make("errors.permisoDenegadoMovil");
            }else{
                return View::make("errors.permisoDenegado");
            }
        }
    }

    public function asignarComite($id){
        $detector = $this->detectarMovil();

        if(Auth::user()->rol_id == 1){
            if($detector == 1){
                $register = Convocatoria::find($id);
                $usuarios = Usuario::where('convocatoria_id', $register->id)->get();

                return View::make('convocatoria.asignarComiteMovil', compact("register"), compact("usuarios"));
            }else{
                $register = Convocatoria::find($id);
                $usuarios = Usuario::where('convocatoria_id', $register->id)->get();

                return View::make('convocatoria.asignarComite', compact("register"), compact("usuarios"));
            }
        }else{
            if($detector == 1){
                return View::make("errors.permisoDenegadoMovil");
            }else{
                return View::make("errors.permisoDenegado");
            }
        }
    }

    public function asignarComiteUpdate($id){
        $detector = $this->detectarMovil();

        if(Auth::user()->rol_id == 1){
            try{
                $id = Crypt::decrypt($id);
                $convocatoria = Convocatoria::find($id);
            }catch (Exception $e){
                $mensaje = "No hay ninguna convocatoria asociada";

                if($detector == 1){
                    return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
                }else{
                    return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                }
            }

            $data = Request::all();
            $data["email"] = trim($data["email"]);
            $data["curp"] = trim(strtoupper($data["curp"]));
            $validaCorreo = Usuario::where('email', $data["email"])->first();

            if($validaCorreo){
                if($validaCorreo->rol_id == 1)
                    return Redirect::back()->with('message', 'No puede agregar a una convocatoria a un administrador del sistema')->withInput($data);
            }

            if($validaCorreo){
                if($validaCorreo->convocatoria_id != null){
                    if(Convocatoria::find($validaCorreo->convocatoria_id)->convocatoria_estatus_id != 6){
                        return Redirect::back()->with('message', 'No puede agregar un usuario que se encuentra en una convocatoria activa')->withInput($data);
                    }
                }
            }

            $cadenaRandom = Str::random(10);
            $data["password"] = Str::random(10);
            $data["verify_password"] = $cadenaRandom;
            $usuario = new Usuario();
            $validator = Usuario::validarComite($data);

            if($validator->fails())
                return Redirect::back()->with('message', 'No se pudo actualizar el registro, revisa los campos requeridos y sus condiciones')->withInput($data)->withErrors($validator->messages());

            $validaCURP = static::validaCURP($data["curp"]);

            if($validaCURP == "1"){
                $message = "La CURP está dada de baja en RENAPO";
                return Redirect::to('/convocatoria/asignarComite/'.$convocatoria->id)->with('message', $message);
            }
            if($validaCURP == "2"){
                $message = "La CURP está dada de baja por defunción en RENAPO";
                return Redirect::to('/convocatoria/asignarComite/'.$convocatoria->id)->with('message', $message);
            }
            if($validaCURP == "3"){
                $message = "La CURP ingresada no fue encontrada en RENAPO";
                return Redirect::to('/convocatoria/asignarComite/'.$convocatoria->id)->with('message', $message);
            }
            if($validaCURP == "4"){
                $message = "La CURP ingresada no fue encontrada en RENAPO";
                return Redirect::to('/convocatoria/asignarComite/'.$convocatoria->id)->with('message', $message);
            }

            $usuario->fill($data);
            $usuario->password = Hash::make($data['password']);
            $usuario->metadata = Crypt::encrypt($data['password']);
            $usuario->verify_password = $cadenaRandom;
            $usuario->enabled = true;
            $usuario->nombre = $validaCURP['nombres'];
            $usuario->apellido_paterno = $validaCURP['apellido1'];
            $usuario->apellido_materno = $validaCURP['apellido2'];
            $usuario->curp = $data["curp"];
            $usuario->cambio_contrasena = null;
            $usuario->convocatoria_id = $convocatoria->id;
            $usuario->default_password = true;
            $usuario->correo_electronico_notificaciones = $data['correo_electronico_notificaciones'];

            $usuarioVerficacion = Usuario::where('convocatoria_id', $convocatoria->id)->where('curp', $data["curp"])->first();
            $usuarioVerficacionCorreo = Usuario::where('email', $usuario->email)->first();

            if($usuarioVerficacion)
                return Redirect::back()->with('message', 'El usuario ya está asignado a esta convocatoria')->withInput($data)->withErrors($validator->messages());

            if($usuarioVerficacionCorreo){
                //Hacer la validación para convocatoria cerrada
                $usuarioVerficacionCorreo->curp = $data["curp"];
                $usuarioVerficacionCorreo->nombre = $validaCURP['nombres'];
                $usuarioVerficacionCorreo->apellido_paterno = $validaCURP['apellido1'];
                $usuarioVerficacionCorreo->apellido_materno = $validaCURP['apellido2'];
                $usuarioVerficacionCorreo->password = Hash::make($data['password']);
                $usuarioVerficacionCorreo->metadata = Crypt::encrypt($data['password']);
                $usuarioVerficacionCorreo->verify_password = $cadenaRandom;
                $usuarioVerficacionCorreo->enabled = true;
                $usuarioVerficacionCorreo->cambio_contrasena = null;
                $usuarioVerficacionCorreo->convocatoria_id = $convocatoria->id;
                $usuarioVerficacionCorreo->token_autorizacion = null;
                $usuarioVerficacionCorreo->rol_id = $data["rol_id"];
                $usuarioVerficacionCorreo->vigencia_token_autorizacion = null;
                $usuarioVerficacionCorreo->default_password = true;
                $usuarioVerficacionCorreo->correo_electronico_notificaciones = $data['correo_electronico_notificaciones'];
                $usuarioVerficacionCorreo->save();

                $bitacora = new Bitacora();
                $bitacora->accion_realizada = "Asignación de comité a convocatoria: ".$data["curp"];
                $bitacora->fecha_accion = Carbon::now();
                $bitacora->accion_realizada_id = $convocatoria->id;
                $bitacora->modulo_id = 2;
                $bitacora->usuario_id = Auth::user()->id;
                $bitacora->save();

                try{
                    Mail::send('emails.envioEnlaceUsuarioInterno', ['user' => $usuario, 'convocatoria' => $convocatoria], function($message) use ($usuario, $convocatoria){
                        $message->to($usuario->correo_electronico_notificaciones, $usuario->nombre.' '.$usuario->apellido_paterno)->subject('Enlace de acceso a SIRVOLAB');
                    });
                }catch(Exception $e){
                    return View::make("emails.falla", compact("e"));
                }

                return Redirect::to('/convocatoria/asignarComite/'.$convocatoria->id)->with('messageUpdate', 'Registro Actualizado');
            }

            $usuario->save();

            $bitacora = new Bitacora();
            $bitacora->accion_realizada = "Asignación de comité a convocatoria: ".$data["curp"];
            $bitacora->fecha_accion = Carbon::now();
            $bitacora->accion_realizada_id = $convocatoria->id;
            $bitacora->modulo_id = 2;
            $bitacora->usuario_id = Auth::user()->id;
            $bitacora->save();

            try{
                Mail::send('emails.envioEnlaceUsuarioInterno', ['user' => $usuario, 'convocatoria' => $convocatoria], function($message) use ($usuario, $convocatoria){
                        $message->to($usuario->correo_electronico_notificaciones, $usuario->nombre.' '.$usuario->apellido_paterno)->subject('Enlace de acceso a SIRVOLAB');
                });
            }catch(Exception $e){
                return View::make("emails.falla", compact("e"));
            }

            return Redirect::to('/convocatoria/asignarComite/'.$convocatoria->id)->with('messageUpdate', 'Registro Actualizado');
        }else{
            if($detector == 1){
                return View::make("errors.permisoDenegadoMovil");
            }else{
                return View::make("errors.permisoDenegado");
            }
        }
    }

    public function asignarValidador(){
        $detector = $this->detectarMovil();

        if(Auth::user()->rol_id == 2){
            if($detector == 1){
                $register = Convocatoria::find(Auth::user()->convocatoria_id);
                $usuarios = Usuario::where('convocatoria_id', $register->id)->where('rol_id', 4)->get();

                return View::make('convocatoria.asignarValidadorMovil', compact("register"), compact("usuarios"));
            }else{
                $register = Convocatoria::find(Auth::user()->convocatoria_id);
                $usuarios = Usuario::where('convocatoria_id', $register->id)->where('rol_id', 4)->get();

                return View::make('convocatoria.asignarValidador', compact("register"), compact("usuarios"));
            }
        }else{
            if($detector == 1){
                return View::make("errors.permisoDenegadoMovil");
            }else{
                return View::make("errors.permisoDenegado");
            }
        }
    }

    public function asignarValidadorUpdate($id){
        $detector = $this->detectarMovil();

        if(Auth::user()->rol_id == 2){
            try{
                $id = Crypt::decrypt($id);
                $convocatoria = Convocatoria::find($id);
            }catch (Exception $e){
                $mensaje = "No hay ninguna convocatoria asociada";

                if($detector == 1){
                    return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
                }else{
                    return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                }
            }

            $data = Request::all();
            $data["email"] = trim($data["email"]);
            $data["curp"] = trim(strtoupper($data["curp"]));
            $data["correo_electronico_notificaciones"] = trim($data["correo_electronico_notificaciones"]);
            $data["rol_id"] = 4;
            $validaCorreo = Usuario::where('email', $data["email"])->first();

            if($validaCorreo){
                if($validaCorreo->rol_id == 1)
                    return Redirect::back()->with('message', 'No puede agregar a una convocatoria a un administrador del sistema')->withInput($data);
            }

            if($validaCorreo){
                if($validaCorreo->convocatoria_id != null){
                    if(Convocatoria::find($validaCorreo->convocatoria_id)->convocatoria_estatus_id != 6){
                        return Redirect::back()->with('message', 'No puede agregar un usuario que se encuentra en una convocatoria activa')->withInput($data);
                    }
                }
            }

            $cadenaRandom = Str::random(10);
            $data["password"] = Str::random(10);
            $data["verify_password"] = $cadenaRandom;
            $usuario = new Usuario();
            $validator = Usuario::validarComite($data);

            if($validator->fails())
                return Redirect::back()->with('message', 'No se pudo actualizar el registro, revisa los campos requeridos y sus condiciones')->withInput($data)->withErrors($validator->messages());

            $validaCURP = static::validaCURP($data["curp"]);

            if($validaCURP == "1"){
                $message = "La CURP está dada de baja en RENAPO";
                return Redirect::back()->with('message', $message)->withInput($data);
            }
            if($validaCURP == "2"){
                $message = "La CURP está dada de baja por defunción en RENAPO";
                return Redirect::back()->with('message', $message)->withInput($data);
            }
            if($validaCURP == "3"){
                $message = "La CURP ingresada no fue encontrada en RENAPO";
                return Redirect::back()->with('message', $message)->withInput($data);
            }
            if($validaCURP == "4"){
                $message = "La CURP ingresada no fue encontrada en RENAPO";
                return Redirect::back()->with('message', $message)->withInput($data);
            }

            $usuario->fill($data);
            $usuario->password = Hash::make($data['password']);
            $usuario->metadata = Crypt::encrypt($data['password']);
            $usuario->verify_password = $cadenaRandom;
            $usuario->enabled = true;
            $usuario->nombre = $validaCURP['nombres'];
            $usuario->apellido_paterno = $validaCURP['apellido1'];
            $usuario->apellido_materno = $validaCURP['apellido2'];
            $usuario->curp = $data["curp"];
            $usuario->cambio_contrasena = null;
            $usuario->convocatoria_id = $convocatoria->id;
            $usuario->default_password = true;
            $usuario->correo_electronico_notificaciones = $data['correo_electronico_notificaciones'];

            $usuarioVerficacion = Usuario::where('convocatoria_id', $convocatoria->id)->where('curp', $data["curp"])->first();
            $usuarioVerficacionCorreo = Usuario::where('email', $usuario->email)->first();

            if($usuarioVerficacion)
                return Redirect::back()->with('message', 'El usuario ya está asignado a esta convocatoria')->withInput($data)->withErrors($validator->messages());

            if($usuarioVerficacionCorreo){
                //Hacer la validación para convocatoria cerrada
                $usuarioVerficacionCorreo->correo_electronico_notificaciones = $data['correo_electronico_notificaciones'];
                $usuarioVerficacionCorreo->curp = $data["curp"];
                $usuarioVerficacionCorreo->nombre = $validaCURP['nombres'];
                $usuarioVerficacionCorreo->apellido_paterno = $validaCURP['apellido1'];
                $usuarioVerficacionCorreo->apellido_materno = $validaCURP['apellido2'];
                $usuarioVerficacionCorreo->password = Hash::make($data['password']);
                $usuarioVerficacionCorreo->metadata = Crypt::encrypt($data['password']);
                $usuarioVerficacionCorreo->verify_password = $cadenaRandom;
                $usuarioVerficacionCorreo->enabled = true;
                $usuarioVerficacionCorreo->cambio_contrasena = null;
                $usuarioVerficacionCorreo->convocatoria_id = $convocatoria->id;
                $usuarioVerficacionCorreo->token_autorizacion = null;
                $usuarioVerficacionCorreo->rol_id = $data["rol_id"];
                $usuarioVerficacionCorreo->vigencia_token_autorizacion = null;
                $usuarioVerficacionCorreo->default_password = true;
                $usuarioVerficacionCorreo->save();

                $bitacora = new Bitacora();
                $bitacora->accion_realizada = "Asignación de validador a convocatoria: ".$data["curp"];
                $bitacora->fecha_accion = Carbon::now();
                $bitacora->accion_realizada_id = $convocatoria->id;
                $bitacora->modulo_id = 2;
                $bitacora->usuario_id = Auth::user()->id;
                $bitacora->save();

                try{
                    Mail::send('emails.envioEnlaceUsuarioInterno', ['user' => $usuarioVerficacionCorreo, 'convocatoria' => $convocatoria], function($message) use ($usuarioVerficacionCorreo){
                            $message->to($usuarioVerficacionCorreo->correo_electronico_notificaciones, $usuarioVerficacionCorreo->nombre.' '.$usuarioVerficacionCorreo->apellido_paterno)->subject('Enlace de acceso a SIRVOLAB');
                    });
                }catch(Exception $e){
                    return View::make("emails.falla", compact("e"));
                }

                return Redirect::to('/convocatoria/asignarValidador/')->with('messageUpdate', 'Registro Actualizado');
            }

            $usuario->save();

            $bitacora = new Bitacora();
            $bitacora->accion_realizada = "Asignación de validador a convocatoria: ".$data["curp"];
            $bitacora->fecha_accion = Carbon::now();
            $bitacora->accion_realizada_id = $convocatoria->id;
            $bitacora->modulo_id = 2;
            $bitacora->usuario_id = Auth::user()->id;
            $bitacora->save();

            try{
                Mail::send('emails.envioEnlaceUsuarioInterno', ['user' => $usuario, 'convocatoria' => $convocatoria], function($message) use ($usuario){
                        $message->to($usuario->correo_electronico_notificaciones, $usuario->nombre.' '.$usuario->apellido_paterno)->subject('Enlace de acceso a SIRVOLAB');
                });
            }catch(Exception $e){
                return View::make("emails.falla", compact("e"));
            }

            return Redirect::to('/convocatoria/asignarValidador/')->with('messageUpdate', 'Registro Actualizado');
        }else{
            if($detector == 1){
                return View::make("errors.permisoDenegadoMovil");
            }else{
                return View::make("errors.permisoDenegado");
            }
        }
    }

    public function cambiaPermiso($id){
        $detector = $this->detectarMovil();

        if(Auth::user()->rol_id == 1){
            try{
                $id = Crypt::decrypt($id);
                $usuario = Usuario::find($id);
                $convocatoria = Convocatoria::find($usuario->convocatoria_id);
                $estatusConvocatoriaCerrada = 6;
                $mensaje = "No es un registro asociado a una convocatoria";

                if($convocatoria){
                    if($convocatoria->convocatoria_estatus_id != $estatusConvocatoriaCerrada){
                        if($usuario->rol_id == 2){
                            $usuario->rol_id = 3;
                        }else
                            $usuario->rol_id = 2;

                        $usuario->save();

                        $bitacora = new Bitacora();
                        $bitacora->accion_realizada = "Actualización de permisos en asignación de usuario a convocatoria: ".$usuario->curp;
                        $bitacora->fecha_accion = Carbon::now();
                        $bitacora->accion_realizada_id = $usuario->convocatoria_id;
                        $bitacora->modulo_id = 2;
                        $bitacora->usuario_id = Auth::user()->id;
                        $bitacora->save();

                        return Redirect::to('/convocatoria/asignarComite/'.$usuario->convocatoria_id)->with('messageUpdate', 'Cambio de perfil realizado');
                    }else{
                        if($detector == 1){
                            return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
                        }else{
                            return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                        }
                    }
                }else{
                    if($detector == 1){
                        return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
                    }else{
                        return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                    }
                }
            }catch (Exception $e){
                if($detector == 1){
                    return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $e);
                }else{
                    return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $e);
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

    public function eliminaAsignacion($id){
        $detector = $this->detectarMovil();

        if(Auth::user()->rol_id == 1){
            try{
                $id = Crypt::decrypt($id);
                $usuario = Usuario::find($id);
                $convocatoria = Convocatoria::find($usuario->convocatoria_id);
                $estatusConvocatoriaCerrada = 6;
                $mensaje = "No es un registro asociado a una convocatoria";

                if($convocatoria){
                    if($convocatoria->convocatoria_estatus_id != $estatusConvocatoriaCerrada){
                        $convocatoriaEliminada = $usuario->convocatoria_id;
                        $usuario->convocatoria_id = null;
                        $usuario->save();

                        $bitacora = new Bitacora();
                        $bitacora->accion_realizada = "Eliminación de registro en asignación de usuario a convcatoria: ".$usuario->curp;
                        $bitacora->fecha_accion = Carbon::now();
                        $bitacora->accion_realizada_id = $convocatoriaEliminada;
                        $bitacora->modulo_id = 2;
                        $bitacora->usuario_id = Auth::user()->id;
                        $bitacora->save();

                        return Redirect::to('/convocatoria/asignarComite/'.$convocatoriaEliminada)->with('messageUpdate', 'Miembro de comité eliminado');
                    }else{
                        if($detector == 1){
                            return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
                        }else{
                            return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                        }
                    }
                }else{
                    if($detector == 1){
                        return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
                    }else{
                        return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                    }
                }
            }catch (Exception $e){
                if($detector == 1){
                    return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $e);
                }else{
                    return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $e);
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

    public function eliminaValidador($id){
        $detector = $this->detectarMovil();

        if(Auth::user()->rol_id == 2){
            $usuario = Usuario::find($id);
            $convocatoriaEliminada = $usuario->convocatoria_id;
            $usuario->convocatoria_id = null;
            $usuario->save();

            $bitacora = new Bitacora();
            $bitacora->accion_realizada = "Eliminación de registro en asignación de usuario a convcatoria: ".$usuario->curp;
            $bitacora->fecha_accion = Carbon::now();
            $bitacora->accion_realizada_id = $convocatoriaEliminada;
            $bitacora->modulo_id = 2;
            $bitacora->usuario_id = Auth::user()->id;
            $bitacora->save();

            return Redirect::to('/convocatoria/asignarValidador/')->with('messageUpdate', 'Registro Actualizado');
        }else{
            if($detector == 1){
                return View::make("errors.permisoDenegadoMovil");
            }else{
                return View::make("errors.permisoDenegado");
            }
        }
    }

    public function cargaPadron($id){
        $detector = $this->detectarMovil();

        if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 2){
            try{
                $id = Crypt::decrypt($id);
                $convocatoria = Convocatoria::find($id);
                $convocatoriaPadronesCargado = ConvocatoriaPadronCargado::where('convocatoria_id', $convocatoria->id)->get();
                $estatusConvocatoriaConsulta = 2;
                $mensaje = "No es un registro asociado a una convocatoria";

                if($convocatoria){
                    if(!$convocatoria->convocatoria_estatus_id == $estatusConvocatoriaConsulta){
                        if($detector == 1){
                            return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
                        }else{
                            return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                        }
                    }
                }else{
                    if($detector == 1){
                        return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                    }else{
                        return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                    }
                }
            }catch (Exception $e){
                if($detector == 1){
                    return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $e);
                }else{
                    return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $e);
                }
            }

            if($detector == 1){
                return View::make('convocatoria.cargaPadronMovil', compact("convocatoria"), compact("convocatoriaPadronesCargado"))->with('message', null);
            }else{
                return View::make('convocatoria.cargaPadron', compact("convocatoria"), compact("convocatoriaPadronesCargado"))->with('message', null);
            }
        }else{
            if($detector == 1){
                return View::make("errors.permisoDenegadoMovil");
            }else{
                return View::make("errors.permisoDenegado");
            }
        }
    }

    public function cargaPadronUpdate($id){
        $detector = $this->detectarMovil();

        if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 2){
            try{
                $id = Crypt::decrypt($id);
                $convocatoria = Convocatoria::find($id);
            }catch (Exception $e){
                $mensaje = "No hay ninguna convocatoria asociada";

                if($detector == 1){
                    return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                }else{
                    return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                }
            }
            try{
                $filaError = null;
                $cadenaRandom = Str::random(15);
                $archivo = null;
                $nombreArchivo = null;
                $inputFileName = null;
                $inputFileType = null;
                $objReader = null;
                $objPHPExcel = null;
                $registros_cargados = 0;
                $validaCURPX = null;
                $message = null;
                $path = "/archivosPadron/".$convocatoria->id."/";

                if(!File::isDirectory(public_path().$path)){
                    File::makeDirectory(public_path().$path, 0777, true);
                }

                if(Request::hasFile("ruta_padron")){
                    $archivo = Request::file("ruta_padron");
                    $nombreArchivo = $cadenaRandom.'.'.$archivo->getClientOriginalExtension();
                    $archivo->move(public_path().$path, $nombreArchivo);
                    $inputFileName = public_path().$path.$nombreArchivo;
                    $inputFileType = IOFactory::identify($inputFileName);
                    $objReader = IOFactory::createReader($inputFileType);
                    $objPHPExcel = $objReader->load($inputFileName);
                }else{
                    return Redirect::to('convocatoria/cargaPadron/'.Crypt::encrypt($convocatoria->id))->with('message', 'No se adjuntó ningún archivo para la carga del padrón');
                }

                $sheet = $objPHPExcel->getSheet(0);
                $highestRow = $sheet->getHighestRow();
                $highestColumn = $sheet->getHighestColumn();
                $guardados = 0;

                for($row = 2; $row <= $highestRow; $row++){
                    $filaError = $row;
                    $nuevoCentroTrabajo = new ConvocatoriaCentroTrabajo();
                    $curp = trim(strtoupper($sheet->getCell([1, $row])));
                    $centroTrabajo = trim($sheet->getCell([2, $row]));
                    $entidad_federativa = trim($sheet->getCell([3, $row]));
                    $validaCURP = ConvocatoriaPadron::where('curp', $curp)->where('convocatoria_id', $convocatoria->id)->first();
                    $validaCentroTrabajo = ConvocatoriaCentroTrabajo::where('nombre', $centroTrabajo)->where('convocatoria_id', $convocatoria->id)->first();
                    $validaEntidadFederativa = EntidadFederativa::where('nombre', $entidad_federativa)->first();

                    if($validaCURP == null){
                        if(strlen($curp) != 18){
                            if($registros_cargados > 0){
                                $convocatoriaPadronCargado = new ConvocatoriaPadronCargado();
                                $convocatoriaPadronCargado->ruta = $path.$nombreArchivo;
                                $convocatoriaPadronCargado->convocatoria_id = $convocatoria->id;
                                $convocatoriaPadronCargado->fecha_carga = Carbon::now();
                                $convocatoriaPadronCargado->registros_cargados = $registros_cargados;
                                $convocatoriaPadronCargado->save();

                                $bitacora = new Bitacora();
                                $bitacora->accion_realizada = "Padrón cargado";
                                $bitacora->fecha_accion = Carbon::now();
                                $bitacora->accion_realizada_id = $convocatoria->id;
                                $bitacora->modulo_id = 2;
                                $bitacora->usuario_id = Auth::user()->id;
                                $bitacora->save();
                            }
                            return Redirect::to('convocatoria/cargaPadron/'.Crypt::encrypt($convocatoria->id))->with('message', 'Hay un valor incorrecto en la celda: '.'A'.$row.', el CURP tiene un formato inválido: '.$curp);
                        }

                        if($validaCentroTrabajo == null){
                            $nuevoCentroTrabajo->nombre = $centroTrabajo;
                            $nuevoCentroTrabajo->convocatoria_id = $convocatoria->id;
                            $nuevoCentroTrabajo->save();

                            $bitacora = new Bitacora();
                            $bitacora->accion_realizada = "Creación de centro de trabajo";
                            $bitacora->fecha_accion = Carbon::now();
                            $bitacora->accion_realizada_id = $convocatoria->id;
                            $bitacora->modulo_id = 2;
                            $bitacora->usuario_id = Auth::user()->id;
                            $bitacora->save();
                        }

                        if($validaEntidadFederativa == null){
                            if($registros_cargados > 0){
                                $convocatoriaPadronCargado = new ConvocatoriaPadronCargado();
                                $convocatoriaPadronCargado->ruta = $path.$nombreArchivo;
                                $convocatoriaPadronCargado->convocatoria_id = $convocatoria->id;
                                $convocatoriaPadronCargado->fecha_carga = Carbon::now();
                                $convocatoriaPadronCargado->registros_cargados = $registros_cargados;
                                $convocatoriaPadronCargado->save();

                                $bitacora = new Bitacora();
                                $bitacora->accion_realizada = "Padrón cargado";
                                $bitacora->fecha_accion = Carbon::now();
                                $bitacora->accion_realizada_id = $convocatoria->id;
                                $bitacora->modulo_id = 2;
                                $bitacora->usuario_id = Auth::user()->id;
                                $bitacora->save();
                            }
                            return Redirect::to('convocatoria/cargaPadron/'.Crypt::encrypt($convocatoria->id))->with('message', 'Hay un valor incorrecto: '.'C'.$row.', la entidad federativa está incorrecta: '.$entidad_federativa);
                        }

                        $validaCURPX = static::validaCURP($curp);
                        $curpFallida = null;

                        if($validaCURPX == "1"){
                            $curpFallida = true;
                            $message = "La CURP está dada de baja en RENAPO";
                        }
                        if($validaCURPX == "2"){
                            $curpFallida = true;
                            $message = "La CURP está dada de baja por defunción en RENAPO";
                        }
                        if($validaCURPX == "3"){
                            $curpFallida = true;
                            $message = "Hay problema con el registro o la consulta a RENAPO";
                        }
                        if($validaCURPX == "4"){
                            $curpFallida = true;
                            $message = "Hay problema con el registro o la consulta a RENAPO";
                        }
                        if($validaCURPX == "5"){
                            $curpFallida = true;
                            $message = "Se terminó el tiempo de espera para la consulta";
                        }

                        if($curpFallida){
                            if($registros_cargados > 0){
                                $convocatoriaPadronCargado = new ConvocatoriaPadronCargado();
                                $convocatoriaPadronCargado->ruta = $path.$nombreArchivo;
                                $convocatoriaPadronCargado->convocatoria_id = $convocatoria->id;
                                $convocatoriaPadronCargado->fecha_carga = Carbon::now();
                                $convocatoriaPadronCargado->registros_cargados = $registros_cargados;
                                $convocatoriaPadronCargado->save();

                                $bitacora = new Bitacora();
                                $bitacora->accion_realizada = "Padrón cargado";
                                $bitacora->fecha_accion = Carbon::now();
                                $bitacora->accion_realizada_id = $convocatoria->id;
                                $bitacora->modulo_id = 2;
                                $bitacora->usuario_id = Auth::user()->id;
                                $bitacora->save();
                            }
                            return Redirect::back()->with('message', $message.', dentro de la celda: '.'A'.$row);
                        }

                        $convocatoriaPadron = new ConvocatoriaPadron();
                        $convocatoriaPadron->curp = $curp;
                        $convocatoriaPadron->nombre_completo = $validaCURPX['nombres'].' '.$validaCURPX['apellido1'].' '.$validaCURPX['apellido2'];
                        $convocatoriaPadron->centro_trabajo_id = $validaCentroTrabajo == null ? $nuevoCentroTrabajo->id : $validaCentroTrabajo->id;
                        $convocatoriaPadron->entidad_federativa_id = $validaEntidadFederativa->id;
                        $convocatoriaPadron->convocatoria_id = $convocatoria->id;
                        $convocatoriaPadron->fecha_carga = Carbon::now();
                        $convocatoriaPadron->cadena_validacion = Str::random(5);
                        $convocatoriaPadron->save();

                        $bitacora = new Bitacora();
                        $bitacora->accion_realizada = "Creación de votante: ".$curp;
                        $bitacora->fecha_accion = Carbon::now();
                        $bitacora->accion_realizada_id = $convocatoria->id;
                        $bitacora->modulo_id = 2;
                        $bitacora->usuario_id = Auth::user()->id;
                        $bitacora->save();

                        $registros_cargados = $registros_cargados + 1;
                    }
                }

                $convocatoriaPadronCargado = new ConvocatoriaPadronCargado();
                $convocatoriaPadronCargado->ruta = $path.$nombreArchivo;
                $convocatoriaPadronCargado->convocatoria_id = $convocatoria->id;
                $convocatoriaPadronCargado->fecha_carga = Carbon::now();
                $convocatoriaPadronCargado->registros_cargados = $registros_cargados;
                $convocatoriaPadronCargado->save();

                $bitacora = new Bitacora();
                $bitacora->accion_realizada = "Padrón cargado";
                $bitacora->fecha_accion = Carbon::now();
                $bitacora->accion_realizada_id = $convocatoria->id;
                $bitacora->modulo_id = 2;
                $bitacora->usuario_id = Auth::user()->id;
                $bitacora->save();

                return Redirect::to('convocatoria/cargaPadron/'.Crypt::encrypt($convocatoria->id))->with('message', 'Padrón Registrado');
            }catch(Exception $e){
                return Redirect::to('/convocatoria/cargaPadron/'.Crypt::encrypt($convocatoria->id))->with('message','Error al leer o guardar el archivo: '.$e->getMessage().' '.(is_string($validaCURPX) ? $validaCURPX : '').' '.$message.' fila error:'.$filaError);
            }
        }else{
            if($detector == 1){
                return View::make("errors.permisoDenegadoMovil");
            }else{
                return View::make("errors.permisoDenegado");
            }
        }
    }

    public function eliminaVotante($id, $tipo){
        $detector = $this->detectarMovil();

        if(Auth::user()->rol_id == 1){
            try{
                $id = Crypt::decrypt($id);
                $convocatoriaPadron = ConvocatoriaPadron::find($id);
                $convocatoria = Convocatoria::find($convocatoriaPadron->convocatoria_id);

                if($convocatoria){
                    if(!($convocatoria->convocatoria_estatus_id == 1 || $convocatoria->convocatoria_estatus_id == 2)){
                        $mensaje = "No puedes eliminar registros del padrón en esta etapa de la convocatoria";

                        if($detector == 1){
                            return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
                        }else{
                            return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                        }
                    }
                }else{
                    $mensaje = "No es una convocatoria asociada";

                    if($detector == 1){
                        return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
                    }else{
                        return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                    }
                }

                $convocatoriaPadron->deleted_at = Carbon::now();
                $convocatoriaPadron->save();

                $bitacora = new Bitacora();
                $bitacora->accion_realizada = "Eliminación de registro de votante: ".$convocatoriaPadron->curp;
                $bitacora->fecha_accion = Carbon::now();
                $bitacora->accion_realizada_id = $convocatoriaPadron->convocatoria_id;
                $bitacora->modulo_id = 2;
                $bitacora->usuario_id = Auth::user()->id;
                $bitacora->save();

                return Redirect::to('/convocatoria/indexPadronConsulta/'.Crypt::encrypt($convocatoriaPadron->convocatoria_id).'/t/'.$tipo)->with('message', 'Registro Eliminado');
            }catch (Exception $e){
                $mensaje = "No fue posible validar el registro";

                if($detector == 1){
                    return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
                }else{
                    return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
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

    public function descargaPadron($id){
        $detector = $this->detectarMovil();

        if(Auth::user()->rol_id == 1  || Auth::user()->rol_id == 2){
            $convocatoriaPadronCargado = ConvocatoriaPadronCargado::find($id);
            $file = public_path().$convocatoriaPadronCargado->ruta;

            return Response::download($file);
        }else{
            if($detector == 1){
                return View::make("errors.permisoDenegadoMovil");
            }else{
                return View::make("errors.permisoDenegado");
            }
        }
    }

    public function autorizacion(){
        $detector = $this->detectarMovil();

        if(Auth::user()->rol_id == 2 || Auth::user()->rol_id == 3){
            $mensajeDeValidacion = null;
            $mensajeExito = null;
            $convocatoria = Convocatoria::find(Auth::user()->convocatoria_id);
            $usuariosConvocatoria = Usuario::where('convocatoria_id', $convocatoria->id)->get();
            $tieneAdminProcesos = Usuario::where('convocatoria_id', $convocatoria->id)->where('rol_id', 2)->get();
            $tieneComite = Usuario::where('convocatoria_id', $convocatoria->id)->where('rol_id', 3)->get();

            if((count($tieneAdminProcesos) > 0)){
                if((count($tieneAdminProcesos) + count($tieneComite)) >= $convocatoria->numero_autorizadores_minimo){
                }else{
                    $mensajeDeValidacion = "No tiene el personal mínimo requerido para las autorizaciones del comité";

                    if($detector == 1){
                        return View::make('errors.autorizacionDenegadaMovil', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion);
                    }else{
                        return View::make('errors.autorizacionDenegada', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion);
                    }
                }
            }else{
                $mensajeDeValidacion = "No cuenta con administrador de procesos para esta convocatoria";

                if($detector == 1){
                    return View::make('errors.autorizacionDenegadaMovil', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion)->with('mensajeExito', $mensajeExito);
                }else{
                    return View::make('errors.autorizacionDenegada', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion)->with('mensajeExito', $mensajeExito);
                }
            }

            if($convocatoria->convocatoria_estatus_id == 1){
                if(count(ConvocatoriaPadron::where('convocatoria_id', $convocatoria->id)->get()) > 0){
                    if($detector == 1){
                        return View::make('convocatoria.autorizacionMovil', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion)->with('mensajeExito', $mensajeExito);
                    }else{
                        return View::make('convocatoria.autorizacion', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion)->with('mensajeExito', $mensajeExito);
                    }
                }else{
                    $mensajeDeValidacion = "No se ha cargado el padrón";

                    if($detector == 1){
                        return View::make('errors.autorizacionDenegadaMovil', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion);
                    }else{
                        return View::make('errors.autorizacionDenegada', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion);
                    }
                }
            }

            if($convocatoria->convocatoria_estatus_id == 2){
                if(count(ConvocatoriaPadron::where('convocatoria_id', $convocatoria->id)->get()) > 0){
                    if(count(ConvocatoriaPadron::where('convocatoria_id', $convocatoria->id)->whereNotNull('fecha_validacion')->get()) > 0){
                        if($detector==1){
                            return View::make('convocatoria.autorizacionFaseDosMovil', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion)->with('mensajeExito', $mensajeExito);
                        }else{
                            return View::make('convocatoria.autorizacionFaseDos', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion)->with('mensajeExito', $mensajeExito);
                        }
                    }else{
                        $mensajeDeValidacion = "No se ha validado ningún registro del padrón";

                        if($detector == 1){
                            return View::make('errors.autorizacionDenegadaMovil', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion);
                        }else{
                            return View::make('errors.autorizacionDenegada', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion);
                        }
                    }
                }else{
                    $mensajeDeValidacion = "No se ha cargado el padrón";

                    if($detector == 1){
                        return View::make('errors.autorizacionDenegadaMovil', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion);
                    }else{
                        return View::make('errors.autorizacionDenegada', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion);
                    }
                }
            }

            if($convocatoria->convocatoria_estatus_id == 3){
                if(count(ConvocatoriaPadron::where('convocatoria_id', $convocatoria->id)->whereNotNull('fecha_validacion')->get()) > 0){
                    $boletas = Boleta::where('convocatoria_id', $convocatoria->id)->get();

                    if($boletas){
                        if(count($boletas) > 0){
                            foreach($boletas as $boleta){
                                $boletaCandidato = BoletaCandidato::where('boleta_id', $boleta->id)->first();

                                if(!$boletaCandidato){
                                    $mensajeDeValidacion = "No se ha asignado un candidato para la boleta: ".$boleta->cargo_postulacion;

                                    if($detector == 1){
                                        return View::make('errors.autorizacionDenegadaMovil', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion);
                                    }else{
                                        return View::make('errors.autorizacionDenegada', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion);
                                    }
                                }
                            }
                        }else{
                            $mensajeDeValidacion = "No se ha creado una boleta para la consulta";

                            if($detector == 1){
                                return View::make('errors.autorizacionDenegadaMovil', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion);
                            }else{
                                return View::make('errors.autorizacionDenegada', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion);
                            }
                        }
                    }else{
                        $mensajeDeValidacion = "No se ha creado una boleta para la consulta";

                        if($detector == 1){
                            return View::make('errors.autorizacionDenegadaMovil', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion);
                        }else{
                            return View::make('errors.autorizacionDenegada', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion);
                        }
                    }

                    if($detector == 1){
                        return View::make('convocatoria.autorizacionFaseTresMovil', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion)->with('mensajeExito', $mensajeExito);
                    }else{
                        return View::make('convocatoria.autorizacionFaseTres', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion)->with('mensajeExito', $mensajeExito);
                    }
                }else{
                    $mensajeDeValidacion = "No se ha validado ningún registro del padrón";

                    if($detector == 1){
                        return View::make('errors.autorizacionDenegada', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion);
                    }else{
                        return View::make('errors.autorizacionDenegada', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion);
                    }
                }
            }

            if($convocatoria->convocatoria_estatus_id == 4){
                if($convocatoria->fecha_consulta_fin < Carbon::now()){
                    if($detector == 1){
                        return View::make('convocatoria.autorizacionFaseCuatroMovil', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion)->with('mensajeExito', $mensajeExito);
                    }else{
                        return View::make('convocatoria.autorizacionFaseCuatro', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion)->with('mensajeExito', $mensajeExito);
                    }
                }else{
                    $mensajeDeValidacion = "Aún no ha terminado el período de la consulta";

                    if($detector == 1){
                        return View::make('errors.autorizacionDenegadaMovil', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion);
                    }else{
                        return View::make('errors.autorizacionDenegada', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion);
                    }
                }
            }
            if($convocatoria->convocatoria_estatus_id > 4){
                $mensajeDeValidacion = "Ya no hay autorizaciones por realizar en esta convocatoria";

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

    public function autorizarFaseUno($cad){
        $detector = $this->detectarMovil();

        if(Auth::user()->rol_id == 2 || Auth::user()->rol_id == 3){
            $convocatoria = Convocatoria::find(Auth::user()->convocatoria_id);
            $usuario = Usuario::find(Auth::user()->id);
            $mensajeExito = null;
            $mensajeDeValidacion = null;
            $fase_autorizada = 1;
            $validaAutorizacion = ConvocatoriaAutorizacion::where('convocatoria_id', $convocatoria->id)->where('usuario_id', $usuario->id)->where('fase_autorizada', $fase_autorizada)->first();

            if($validaAutorizacion){
                $mensajeDeValidacion = "Usted ya autorizó esta fase en la siguiente fecha: ".$validaAutorizacion->fecha_autorizacion;

                if($detector == 1){
                    return View::make('errors.autorizacionDenegadaMovil', compact("convocatoria"), compact("mensajeDeValidacion"));
                }else{
                    return View::make('errors.autorizacionDenegada', compact("convocatoria"), compact("mensajeDeValidacion"));
                }
            }
            if($cad != null){
                if($usuario->token_autorizacion == $cad){
                    if($usuario->vigencia_token_autorizacion > Carbon::now()){
                        $convocatoriaAutorizacion = new ConvocatoriaAutorizacion();
                        $convocatoriaAutorizacion->usuario_id = $usuario->id;
                        $convocatoriaAutorizacion->convocatoria_id = $convocatoria->id;
                        $convocatoriaAutorizacion->fecha_autorizacion = Carbon::now();
                        $convocatoriaAutorizacion->fase_autorizada = 1;
                        $convocatoriaAutorizacion->save();

                        $bitacora = new Bitacora();
                        $bitacora->accion_realizada = "Autorización de inicio de registro y validación: ".$usuario->curp;
                        $bitacora->fecha_accion = Carbon::now();
                        $bitacora->accion_realizada_id = $convocatoria->id;
                        $bitacora->modulo_id = 2;
                        $bitacora->usuario_id = Auth::user()->id;
                        $bitacora->save();

                        $conteoAutorizadores = ConvocatoriaAutorizacion::where('convocatoria_id', $convocatoria->id)->where('fase_autorizada', $fase_autorizada)->get();

                        if(count($conteoAutorizadores) >= $convocatoria->numero_autorizadores_minimo){
                            if($convocatoria->convocatoria_estatus_id == 1){
                                $convocatoria->convocatoria_estatus_id = 2;
                                $convocatoria->save();

                                $bitacora = new Bitacora();
                                $bitacora->accion_realizada = "Cambio de estatus de convocatoria a registro y validación";
                                $bitacora->fecha_accion = Carbon::now();
                                $bitacora->accion_realizada_id = $convocatoria->id;
                                $bitacora->modulo_id = 2;
                                $bitacora->usuario_id = Auth::user()->id;
                                $bitacora->save();
                            }
                        }

                        $usuario->vigencia_token_autorizacion = null;
                        $usuario->save();

                        if($detector==1){
                            $mensajeExito = "Se ha realizado exitosamente la autorización.";
                            return View::make('convocatoria.autorizacionMovil', compact("convocatoria"), compact("mensajeExito"))->with("mensajeDeValidacion", $mensajeDeValidacion);
                        }else{
                            $mensajeExito = "Se ha realizado exitosamente la autorización.";
                            return View::make('convocatoria.autorizacion', compact("convocatoria"), compact("mensajeExito"))->with("mensajeDeValidacion", $mensajeDeValidacion);
                        }
                    }else{
                        $mensajeDeValidacion = "La vigencia del token ha terminado.";

                        if($detector == 1){
                            return View::make('errors.autorizacionDenegadaMovil', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion);
                        }else{
                            return View::make('errors.autorizacionDenegada', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion);
                        }
                    }
                }else{
                    if($detector==1){
                        $mensajeDeValidacion = "El token ingresado no es correcto, intente nuevamente.";
                        return View::make('convocatoria.autorizacionMovil', compact("convocatoria"), compact("mensajeDeValidacion"))->with("mensajeExito", $mensajeExito);
                    }else{
                        $mensajeDeValidacion = "El token ingresado no es correcto, intente nuevamente.";
                        return View::make('convocatoria.autorizacion', compact("convocatoria"), compact("mensajeDeValidacion"))->with("mensajeExito", $mensajeExito);
                    }
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

    public function autorizarFaseDos($cad){
        $detector = $this->detectarMovil();

        if(Auth::user()->rol_id == 2 || Auth::user()->rol_id == 3){
            $convocatoria = Convocatoria::find(Auth::user()->convocatoria_id);
            $usuario = Usuario::find(Auth::user()->id);
            $mensajeExito = null;
            $mensajeDeValidacion = null;
            $fase_autorizada = 2;
            $validaAutorizacion = ConvocatoriaAutorizacion::where('convocatoria_id', $convocatoria->id)->where('usuario_id', $usuario->id)->where('fase_autorizada', $fase_autorizada)->first();

            if($validaAutorizacion){
                $mensajeDeValidacion = "Usted ya autorizó esta fase en la siguiente fecha: ".$validaAutorizacion->fecha_autorizacion;

                if($detector == 1){
                    return View::make('errors.autorizacionDenegadaMovil', compact("convocatoria"), compact("mensajeDeValidacion"));
                }else{
                    return View::make('errors.autorizacionDenegada', compact("convocatoria"), compact("mensajeDeValidacion"));
                }
            }
            if($cad != null){
                if($usuario->token_autorizacion == $cad){
                    if($usuario->vigencia_token_autorizacion > Carbon::now()){
                        $convocatoriaAutorizacion = new ConvocatoriaAutorizacion();
                        $convocatoriaAutorizacion->usuario_id = $usuario->id;
                        $convocatoriaAutorizacion->convocatoria_id = $convocatoria->id;
                        $convocatoriaAutorizacion->fecha_autorizacion = Carbon::now();
                        $convocatoriaAutorizacion->fase_autorizada = 2;
                        $convocatoriaAutorizacion->save();

                        $bitacora = new Bitacora();
                        $bitacora->accion_realizada = "Autorización de cierre de registro y validación: ".$usuario->curp;
                        $bitacora->fecha_accion = Carbon::now();
                        $bitacora->accion_realizada_id = $convocatoria->id;
                        $bitacora->modulo_id = 2;
                        $bitacora->usuario_id = Auth::user()->id;
                        $bitacora->save();

                        $conteoAutorizadores = ConvocatoriaAutorizacion::where('convocatoria_id', $convocatoria->id)->where('fase_autorizada', $fase_autorizada)->get();

                        if(count($conteoAutorizadores) >= $convocatoria->numero_autorizadores_minimo){
                            if($convocatoria->convocatoria_estatus_id == 2){
                                $convocatoria->convocatoria_estatus_id = 3;
                                $convocatoria->save();

                                $bitacora = new Bitacora();
                                $bitacora->accion_realizada = "Cambio de estatus de convocatoria a configuración de boleta(s)";
                                $bitacora->fecha_accion = Carbon::now();
                                $bitacora->accion_realizada_id = $convocatoria->id;
                                $bitacora->modulo_id = 2;
                                $bitacora->usuario_id = Auth::user()->id;
                                $bitacora->save();
                            }
                        }

                        $usuario->vigencia_token_autorizacion = null;
                        $usuario->save();

                        $mensajeExito = "Se ha realizado exitosamente la autorización.";
                        if($detector==1){
                            $mensajeExito = "Se ha realizado exitosamente la autorización.";
                            return View::make('convocatoria.autorizacionFaseDosMovil', compact("convocatoria"), compact("mensajeExito"))->with("mensajeDeValidacion", $mensajeDeValidacion);
                        }else{
                            $mensajeExito = "Se ha realizado exitosamente la autorización.";
                            return View::make('convocatoria.autorizacionFaseDos', compact("convocatoria"), compact("mensajeExito"))->with("mensajeDeValidacion", $mensajeDeValidacion);
                        }
                    }else{
                        $mensajeDeValidacion = "La vigencia del token ha terminado.";

                        if($detector == 1){
                            return View::make('errors.autorizacionDenegadaMovil', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion);
                        }else{
                            return View::make('errors.autorizacionDenegada', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion);
                        }
                    }
                }else{
                    $mensajeDeValidacion = "El token ingresado no es correcto, intente nuevamente.";
                    if($detector==1){
                        return View::make('convocatoria.autorizacionFaseDosMovil', compact("convocatoria"), compact("mensajeDeValidacion"))->with("mensajeExito", $mensajeExito);
                    }else{
                        return View::make('convocatoria.autorizacionFaseDos', compact("convocatoria"), compact("mensajeDeValidacion"))->with("mensajeExito", $mensajeExito);
                    }
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

    public function autorizarFaseTres($cad){
        $detector = $this->detectarMovil();

        if(Auth::user()->rol_id == 2 || Auth::user()->rol_id == 3){
            $convocatoria = Convocatoria::find(Auth::user()->convocatoria_id);
            $usuario = Usuario::find(Auth::user()->id);
            $mensajeExito = null;
            $mensajeDeValidacion = null;
            $fase_autorizada = 3;
            $validaAutorizacion = ConvocatoriaAutorizacion::where('convocatoria_id', $convocatoria->id)->where('usuario_id', $usuario->id)->where('fase_autorizada', $fase_autorizada)->first();

            if($validaAutorizacion){
                $mensajeDeValidacion = "Usted ya autorizó esta fase en la siguiente fecha: ".$validaAutorizacion->fecha_autorizacion;

                if($detector == 1){
                    return View::make('errors.autorizacionDenegadaMovil', compact("convocatoria"), compact("mensajeDeValidacion"));
                }else{
                    return View::make('errors.autorizacionDenegada', compact("convocatoria"), compact("mensajeDeValidacion"));
                }
            }
            if($cad != null){
                if($usuario->token_autorizacion == $cad){
                    if($usuario->vigencia_token_autorizacion > Carbon::now()){
                        $convocatoriaAutorizacion = new ConvocatoriaAutorizacion();
                        $convocatoriaAutorizacion->usuario_id = $usuario->id;
                        $convocatoriaAutorizacion->convocatoria_id = $convocatoria->id;
                        $convocatoriaAutorizacion->fecha_autorizacion = Carbon::now();
                        $convocatoriaAutorizacion->fase_autorizada = 3;
                        $convocatoriaAutorizacion->save();

                        $bitacora = new Bitacora();
                        $bitacora->accion_realizada = "Autorización de inicio de la consulta: ".$usuario->curp;
                        $bitacora->fecha_accion = Carbon::now();
                        $bitacora->accion_realizada_id = $convocatoria->id;
                        $bitacora->modulo_id = 2;
                        $bitacora->usuario_id = Auth::user()->id;
                        $bitacora->save();

                        $conteoAutorizadores = ConvocatoriaAutorizacion::where('convocatoria_id', $convocatoria->id)->where('fase_autorizada', $fase_autorizada)->get();

                        if(count($conteoAutorizadores) >= $convocatoria->numero_autorizadores_minimo){
                            if($convocatoria->convocatoria_estatus_id == 3){
                                $convocatoria->convocatoria_estatus_id = 4;
                                $convocatoria->save();

                                $bitacora = new Bitacora();
                                $bitacora->accion_realizada = "Cambio de estatus de convocatoria a realizar consulta";
                                $bitacora->fecha_accion = Carbon::now();
                                $bitacora->accion_realizada_id = $convocatoria->id;
                                $bitacora->modulo_id = 2;
                                $bitacora->usuario_id = Auth::user()->id;
                                $bitacora->save();
                            }
                        }

                        $usuario->vigencia_token_autorizacion = null;
                        $usuario->save();

                        $mensajeExito = "Se ha realizado exitosamente la autorización.";

                        if($detector==1){
                            return View::make('convocatoria.autorizacionFaseTresMovil', compact("convocatoria"), compact("mensajeExito"))->with("mensajeDeValidacion", $mensajeDeValidacion);
                        }else{
                            return View::make('convocatoria.autorizacionFaseTres', compact("convocatoria"), compact("mensajeExito"))->with("mensajeDeValidacion", $mensajeDeValidacion);
                        }
                    }else{
                        $mensajeDeValidacion = "La vigencia del token ha terminado.";

                        if($detector == 1){
                            return View::make('errors.autorizacionDenegadaMovil', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion);
                        }else{
                            return View::make('errors.autorizacionDenegada', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion);
                        }
                    }
                }else{
                    $mensajeDeValidacion = "El token ingresado no es correcto, intente nuevamente.";
                    if($detector==1){
                        return View::make('convocatoria.autorizacionFaseTresMovil', compact("convocatoria"), compact("mensajeDeValidacion"))->with("mensajeExito", $mensajeExito);
                    }else{
                        return View::make('convocatoria.autorizacionFaseTres', compact("convocatoria"), compact("mensajeDeValidacion"))->with("mensajeExito", $mensajeExito);
                    }
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

    public function autorizarFaseCuatro($cad){
        $detector = $this->detectarMovil();

        if(Auth::user()->rol_id == 2 || Auth::user()->rol_id == 3){
            $convocatoria = Convocatoria::find(Auth::user()->convocatoria_id);
            $usuario = Usuario::find(Auth::user()->id);
            $mensajeExito = null;
            $mensajeDeValidacion = null;
            $fase_autorizada = 4;
            $validaAutorizacion = ConvocatoriaAutorizacion::where('convocatoria_id', $convocatoria->id)->where('usuario_id', $usuario->id)->where('fase_autorizada', $fase_autorizada)->first();

            if($validaAutorizacion){
                $mensajeDeValidacion = "Usted ya autorizó esta fase en la siguiente fecha: ".$validaAutorizacion->fecha_autorizacion;

                if($detector == 1){
                    return View::make('errors.autorizacionDenegadaMovil', compact("convocatoria"), compact("mensajeDeValidacion"));
                }else{
                    return View::make('errors.autorizacionDenegada', compact("convocatoria"), compact("mensajeDeValidacion"));
                }
            }
            if($cad != null){
                if($usuario->token_autorizacion == $cad){
                    if($usuario->vigencia_token_autorizacion > Carbon::now()){
                        $convocatoriaAutorizacion = new ConvocatoriaAutorizacion();
                        $convocatoriaAutorizacion->usuario_id = $usuario->id;
                        $convocatoriaAutorizacion->convocatoria_id = $convocatoria->id;
                        $convocatoriaAutorizacion->fecha_autorizacion = Carbon::now();
                        $convocatoriaAutorizacion->fase_autorizada = 4;
                        $convocatoriaAutorizacion->save();

                        $bitacora = new Bitacora();
                        $bitacora->accion_realizada = "Autorización de fin de la consulta: ".$usuario->curp;
                        $bitacora->fecha_accion = Carbon::now();
                        $bitacora->accion_realizada_id = $convocatoria->id;
                        $bitacora->modulo_id = 2;
                        $bitacora->usuario_id = Auth::user()->id;
                        $bitacora->save();

                        $conteoAutorizadores = ConvocatoriaAutorizacion::where('convocatoria_id', $convocatoria->id)->where('fase_autorizada', $fase_autorizada)->get();

                        if(count($conteoAutorizadores) >= $convocatoria->numero_autorizadores_minimo){
                            if($convocatoria->convocatoria_estatus_id == 4){
                                $convocatoria->convocatoria_estatus_id = 5;
                                $convocatoria->save();

                                $bitacora = new Bitacora();
                                $bitacora->accion_realizada = "Cambio de estatus de convocatoria a cerrar consulta";
                                $bitacora->fecha_accion = Carbon::now();
                                $bitacora->accion_realizada_id = $convocatoria->id;
                                $bitacora->modulo_id = 2;
                                $bitacora->usuario_id = Auth::user()->id;
                                $bitacora->save();
                            }
                        }

                        $usuario->vigencia_token_autorizacion = null;
                        $usuario->save();

                        $mensajeExito = "Se ha realizado exitosamente la autorización.";

                        if($detector == 1){
                            return View::make('convocatoria.autorizacionFaseCuatroMovil', compact("convocatoria"), compact("mensajeExito"))->with("mensajeDeValidacion", $mensajeDeValidacion);
                        }else{
                            return View::make('convocatoria.autorizacionFaseCuatro', compact("convocatoria"), compact("mensajeExito"))->with("mensajeDeValidacion", $mensajeDeValidacion);
                        }
                    }else{
                        $mensajeDeValidacion = "La vigencia del token ha terminado.";

                        if($detector == 1){
                            return View::make('errors.autorizacionDenegadaMovil', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion);
                        }else{
                            return View::make('errors.autorizacionDenegada', compact("convocatoria"))->with("mensajeDeValidacion", $mensajeDeValidacion);
                        }
                    }
                }else{
                    $mensajeDeValidacion = "El token ingresado no es correcto, intente nuevamente.";

                    if($detector == 1){
                        return View::make('convocatoria.autorizacionFaseCuatroMovil', compact("convocatoria"), compact("mensajeDeValidacion"))->with("mensajeExito", $mensajeExito);
                    }else{
                        return View::make('convocatoria.autorizacionFaseCuatro', compact("convocatoria"), compact("mensajeDeValidacion"))->with("mensajeExito", $mensajeExito);
                    }
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

    public function bitacora($id){
        $detector = $this->detectarMovil();

        if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 2){
            if($detector == 1){
                $register = Bitacora::where("accion_realizada_id", $id)->where("modulo_id", 2)->get();
                return View::make('convocatoria.bitacoraMovil', compact("register"));
            }else{
                $register = Bitacora::where("accion_realizada_id", $id)->where("modulo_id", 2)->get();
                return View::make('convocatoria.bitacora', compact("register"));
            }
        }else{
            if($detector == 1){
                return View::make("errors.permisoDenegadoMovil");
            }else{
                return View::make("errors.permisoDenegado");
            }
        }
    }

    public function busquedaPorCURP(){
        $detector = $this->detectarMovil();

        if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 2 || Auth::user()->rol_id == 3){

            $curp = null;
            $mensaje = null;

            if($detector==1){
                return View::make("convocatoria.busquedaPorCURP")->with('curp', $curp)->with('mensaje', $mensaje);
            }else{
                return View::make("convocatoria.busquedaPorCURP")->with('curp', $curp)->with('mensaje', $mensaje);
            }
        }else
            return View::make("errors.permisoDenegado");
    }

    public function busquedaPorCURPConsulta(){
        $detector = $this->detectarMovil();

        if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 2 || Auth::user()->rol_id == 3){
            $data = Request::all();
            $curp = trim(strtoupper($data["curp"]));
            $convocatoriaPadron = ConvocatoriaPadron::where('curp', $curp)->whereNotNull('fecha_validacion')->first();
            $mensaje = null;

            if(!$convocatoriaPadron){
                $mensaje = 'La CURP no se encontró';

                if($detector == 1){
                    return View::make("convocatoria.busquedaPorCURP")->with('mensaje', $mensaje)->with('curp', $curp);
                }else{
                    return View::make("convocatoria.busquedaPorCURP")->with('mensaje', $mensaje)->with('curp', $curp);
                }
            }else{
                if($detector == 1){
                    return View::make("convocatoria.busquedaPorCURPConsulta")->with('convocatoriaPadron', $convocatoriaPadron);
                }else{
                    return View::make("convocatoria.busquedaPorCURPConsulta")->with('convocatoriaPadron', $convocatoriaPadron);
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

    public function bitacoraShow($id){
        $detector = $this->detectarMovil();

        if(Auth::user()->rol_id == 1){
            if($detector == 1){
                $register = Bitacora::where("accion_realizada_id", $id)->where("modulo_id", 2)->get();
                return View::make('convocatoria.bitacoraShowMovil', compact("register"));
            }else{
                $register = Bitacora::where("accion_realizada_id", $id)->where("modulo_id", 2)->get();
                return View::make('convocatoria.bitacoraShow', compact("register"));
            }
        }else{
            if($detector == 1){
                return View::make("errors.permisoDenegadoMovil");
            }else{
                return View::make("errors.permisoDenegado");
            }
        }
    }

    public function descargarTodo($id){
        $detector = $this->detectarMovil();

        $convocatoriaPadron = null;

        if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 2 || Auth::user()->rol_id == 3){
            try{
                $id = Crypt::decrypt($id);
                $convocatoria = Convocatoria::find($id);
                $convocatoriaPadron = ConvocatoriaPadron::where('convocatoria_id', $id)->get();
            }catch (Exception $e){
                $mensaje = "No hay ninguna convocatoria asociada";

                if($detector == 1){
                    return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
                }else{
                    return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                }
            }

            try{
                $objPHPExcel = static::getPhpExcelObj();

                $objPHPExcel->getProperties()->setCreator("STPS")
                    ->setLastModifiedBy("STPS")
                    ->setTitle("Descarga Padron")
                    ->setSubject("Descarga Padron")
                    ->setDescription("Descarga Padron")
                    ->setKeywords("Descarga Padron")
                    ->setCategory("Descarga Padron");

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CURP')
                    ->setCellValue('B1', 'Nombre completo')
                    ->setCellValue('C1', 'Centro de trabajo')
                    ->setCellValue('D1', 'Entidad federativa')
                    ->setCellValue('E1', 'Convocatoria')
                    ->setCellValue('F1', 'Fecha de carga')
                    ->setCellValue('G1', 'Fecha de registro')
                    ->setCellValue('H1', 'Fecha de validacion')
                    ->setCellValue('I1', 'Fecha de rechazo')
                    ->setCellValue('J1', 'Motivo de rechazo')
                    ->setCellValue('K1', 'Fecha de voto');

                $numeroCelda = 2;

                foreach($convocatoriaPadron as $padron){
                    $centroTrabajo = null;
                    $entidadFederativa = null;
                    $convocatoriaTexto = null;
                    $motivo = null;
                    $fechaVoto = null;

                    if($padron->centro_trabajo_id != null && $padron->centro_trabajo_id != ''){
                        $centroTrabajo = ConvocatoriaCentroTrabajo::find($padron->centro_trabajo_id)->nombre;
                    }
                    if($padron->entidad_federativa_id != null && $padron->entidad_federativa_id != ''){
                        $entidadFederativa = EntidadFederativa::find($padron->entidad_federativa_id)->nombre;
                    }
                    if($padron->convocatoria_id != null && $padron->convocatoria_id != ''){
                        $convocatoriaTexto = Convocatoria::find($padron->convocatoria_id)->nombre;
                    }
                    if($padron->motivo_rechazo_id != null && $padron->motivo_rechazo_id != ''){
                        $motivo = MotivoRechazo::find($padron->motivo_rechazo_id)->nombre;
                    }

                    $realizoVoto = Voto::where('convocatoria_padron_id', $padron->id)->where('convocatoria_id', $convocatoria->id)->first();

                    if($realizoVoto){
                       $fechaVoto = (string)$realizoVoto->created_at;
                    }else{
                        $fechaVoto = '';
                    }

                    $objPHPExcel->setActiveSheetIndex(0)
                        ->setCellValue('A'.$numeroCelda, $padron->curp)
                        ->setCellValue('B'.$numeroCelda, $padron->nombre_completo)
                        ->setCellValue('C'.$numeroCelda, $centroTrabajo)
                        ->setCellValue('D'.$numeroCelda, $entidadFederativa)
                        ->setCellValue('E'.$numeroCelda, $convocatoriaTexto)
                        ->setCellValue('F'.$numeroCelda, $padron->fecha_carga)
                        ->setCellValue('G'.$numeroCelda, $padron->fecha_registro)
                        ->setCellValue('H'.$numeroCelda, $padron->fecha_validacion)
                        ->setCellValue('I'.$numeroCelda, $padron->fecha_rechazo)
                        ->setCellValue('J'.$numeroCelda, $motivo)
                        ->setCellValue('K'.$numeroCelda, $fechaVoto);

                        $numeroCelda = $numeroCelda + 1;
                }

                $objPHPExcel->getActiveSheet()->setTitle('Padrón');
                return static::descargarExcel($objPHPExcel, 'Descarga Padron');
            }catch (Exception $e){
                $mensaje = "Hubo un error con la descarga del archivo";

                if($detector == 1){
                    return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje.': '.$e);
                }else{
                    return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje.': '.$e);
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

    public function descargarConsulta($id, $tipo){
        $detector = $this->detectarMovil();

        $convocatoriaPadron = null;

        if(Auth::user()->rol_id == 1 || Auth::user()->rol_id == 2 || Auth::user()->rol_id == 3){
            try{
                $id = Crypt::decrypt($id);
                $convocatoria = Convocatoria::find($id);

                if($tipo == 1){
                    $convocatoriaPadron = ConvocatoriaPadron::where('convocatoria_id', $id)->whereNotNull('fecha_registro')->get();
                }
                if($tipo == 2){
                    $convocatoriaPadron = ConvocatoriaPadron::where('convocatoria_id', $id)->where('fecha_registro', null)->get();
                }
                if($tipo == 3){
                    $convocatoriaPadron = ConvocatoriaPadron::where('convocatoria_id', $id)->whereNotNull('fecha_rechazo')->get();
                }
                if($tipo == 4){
                    $convocatoriaPadron = ConvocatoriaPadron::where('convocatoria_id', $id)->whereNotNull('fecha_validacion')->get();
                }
                if($tipo == 5){
                     $convocatoriaPadron = Voto::where('convocatoria_id', $id)->get();
                }
            }catch (Exception $e){
                $mensaje = "No hay ninguna convocatoria asociada";

                if($detector == 1){
                    return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje);
                }else{
                    return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje);
                }
            }

            $cabecera = null;

            if($tipo == 5)
                $cabecera = 'Boleta';
            else
                $cabecera = '';

            try{
                $objPHPExcel = static::getPhpExcelObj();

                $objPHPExcel->getProperties()->setCreator("STPS")
                    ->setLastModifiedBy("STPS")
                    ->setTitle("Descarga Padron")
                    ->setSubject("Descarga Padron")
                    ->setDescription("Descarga Padron")
                    ->setKeywords("Descarga Padron")
                    ->setCategory("Descarga Padron");

                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'CURP')
                    ->setCellValue('B1', 'Nombre completo')
                    ->setCellValue('C1', 'Centro de trabajo')
                    ->setCellValue('D1', 'Entidad federativa')
                    ->setCellValue('E1', 'Convocatoria')
                    ->setCellValue('F1', 'Fecha de carga')
                    ->setCellValue('G1', 'Fecha de registro')
                    ->setCellValue('H1', 'Fecha de validacion')
                    ->setCellValue('I1', 'Fecha de rechazo')
                    ->setCellValue('J1', 'Motivo de rechazo')
                    ->setCellValue('K1', $cabecera);

                $numeroCelda = 2;

                if($tipo == 5){
                    foreach($convocatoriaPadron as $padron){
                        $centroTrabajo = null;
                        $entidadFederativa = null;
                        $convocatoriaTexto = null;
                        $motivo = null;

                        if(ConvocatoriaPadron::find($padron->convocatoria_padron_id)->centro_trabajo_id != null && ConvocatoriaPadron::find($padron->convocatoria_padron_id)->centro_trabajo_id != ''){
                            $centroTrabajo = ConvocatoriaCentroTrabajo::find(ConvocatoriaPadron::find($padron->convocatoria_padron_id)->centro_trabajo_id)->nombre;
                        }
                        if(ConvocatoriaPadron::find($padron->convocatoria_padron_id)->entidad_federativa_id != null && ConvocatoriaPadron::find($padron->convocatoria_padron_id)->entidad_federativa_id != ''){
                            $entidadFederativa = EntidadFederativa::find(ConvocatoriaPadron::find($padron->convocatoria_padron_id)->entidad_federativa_id)->nombre;
                        }
                        if(ConvocatoriaPadron::find($padron->convocatoria_padron_id)->convocatoria_id != null && ConvocatoriaPadron::find($padron->convocatoria_padron_id)->convocatoria_id != ''){
                            $convocatoriaTexto = Convocatoria::find(ConvocatoriaPadron::find($padron->convocatoria_padron_id)->convocatoria_id)->nombre;
                        }
                        if(ConvocatoriaPadron::find($padron->convocatoria_padron_id)->motivo_rechazo_id != null && ConvocatoriaPadron::find($padron->convocatoria_padron_id)->motivo_rechazo_id != ''){
                            $motivo = MotivoRechazo::find(ConvocatoriaPadron::find($padron->convocatoria_padron_id)->motivo_rechazo_id)->nombre;
                        }

                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$numeroCelda, (ConvocatoriaPadron::find($padron->convocatoria_padron_id)->curp))
                            ->setCellValue('B'.$numeroCelda, (ConvocatoriaPadron::find($padron->convocatoria_padron_id)->nombre_completo))
                            ->setCellValue('C'.$numeroCelda, $centroTrabajo)
                            ->setCellValue('D'.$numeroCelda, $entidadFederativa)
                            ->setCellValue('E'.$numeroCelda, $convocatoriaTexto)
                            ->setCellValue('F'.$numeroCelda, (ConvocatoriaPadron::find($padron->convocatoria_padron_id)->fecha_carga))
                            ->setCellValue('G'.$numeroCelda, (ConvocatoriaPadron::find($padron->convocatoria_padron_id)->fecha_registro))
                            ->setCellValue('H'.$numeroCelda, (ConvocatoriaPadron::find($padron->convocatoria_padron_id)->fecha_validacion))
                            ->setCellValue('I'.$numeroCelda, (ConvocatoriaPadron::find($padron->convocatoria_padron_id)->fecha_rechazo))
                            ->setCellValue('J'.$numeroCelda, $motivo)
                            ->setCellValue('K'.$numeroCelda, (Boleta::find($padron->boleta_id)->cargo_postulacion));

                            $numeroCelda = $numeroCelda + 1;
                    }
                }else{
                    foreach($convocatoriaPadron as $padron){
                        $centroTrabajo = null;
                        $entidadFederativa = null;
                        $convocatoriaTexto = null;
                        $motivo = null;

                        if($padron->centro_trabajo_id != null && $padron->centro_trabajo_id != ''){
                            $centroTrabajo = ConvocatoriaCentroTrabajo::find($padron->centro_trabajo_id)->nombre;
                        }
                        if($padron->entidad_federativa_id != null && $padron->entidad_federativa_id != ''){
                            $entidadFederativa = EntidadFederativa::find($padron->entidad_federativa_id)->nombre;
                        }
                        if($padron->convocatoria_id != null && $padron->convocatoria_id != ''){
                            $convocatoriaTexto = Convocatoria::find($padron->convocatoria_id)->nombre;
                        }
                        if($padron->motivo_rechazo_id != null && $padron->motivo_rechazo_id != ''){
                            $motivo = MotivoRechazo::find($padron->motivo_rechazo_id)->nombre;
                        }

                        $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A'.$numeroCelda, $padron->curp)
                            ->setCellValue('B'.$numeroCelda, $padron->nombre_completo)
                            ->setCellValue('C'.$numeroCelda, $centroTrabajo)
                            ->setCellValue('D'.$numeroCelda, $entidadFederativa)
                            ->setCellValue('E'.$numeroCelda, $convocatoriaTexto)
                            ->setCellValue('F'.$numeroCelda, $padron->fecha_carga)
                            ->setCellValue('G'.$numeroCelda, $padron->fecha_registro)
                            ->setCellValue('H'.$numeroCelda, $padron->fecha_validacion)
                            ->setCellValue('I'.$numeroCelda, $padron->fecha_rechazo)
                            ->setCellValue('J'.$numeroCelda, $motivo);

                            $numeroCelda = $numeroCelda + 1;
                    }
                }

                $objPHPExcel->getActiveSheet()->setTitle('Padrón');
                return static::descargarExcel($objPHPExcel, 'Descarga Padron');
            }catch (Exception $e){
                $mensaje = "Hubo un error con la descarga del archivo";

                if($detector == 1){
                    return View::make("errors.sinPeriodoRegistroVigenteMovil")->with('mensaje', $mensaje.': '.$e);
                }else{
                    return View::make("errors.sinPeriodoRegistroVigente")->with('mensaje', $mensaje.': '.$e);
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

    public function descargarActa(){
        $detector = $this->detectarMovil();

        if(Auth::user()->rol_id == 2 || Auth::user()->rol_id == 3){


        }else{
            if($detector == 1){
                return View::make("errors.permisoDenegadoMovil");
            }else{
                return View::make("errors.permisoDenegado");
            }
        }
    }

    /**
     * Consulta la CURP en RENAPO. La lógica se movió a App\Services\Renapo
     * (antes estaba duplicada aquí y en BoletaController).
     */
    public static function validaCURP($curp){
        return Renapo::validaCURP($curp);
    }

    public static function getPhpExcelObj(){
        return new Spreadsheet();
    }

    /**
     * Antes enviaba encabezados con header() y terminaba con exit; ahora
     * regresa una respuesta de descarga (mismo archivo .xls / Excel5).
     */
    public static function descargarExcel($objPHPExcel,$fileName){
        $objWriter = IOFactory::createWriter($objPHPExcel, 'Xls');

        return response()->streamDownload(function () use ($objWriter) {
            $objWriter->save('php://output');
        }, $fileName.'.xls', [
            'Content-Type' => 'application/vnd.ms-excel',
            'Cache-Control' => 'cache, must-revalidate',
            'Pragma' => 'public',
            'Expires' => 'Mon, 26 Jul 1997 05:00:00 GMT',
        ]);
    }
}
