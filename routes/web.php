<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BoletaController;
use App\Http\Controllers\ConvocatoriaController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\RegistroValidacionController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VotacionController;
use Illuminate\Support\Facades\Route;

// Inicio de sesion
Route::get('/', [AuthController::class, 'showLogin']);
Route::get('/login/', [AuthController::class, 'showLogin']);
Route::post('/login/', [AuthController::class, 'signIn']);
Route::get('/logout', [AuthController::class, 'logOut']);
Route::get('/cambiarContrasena/u/{idU}/d/{idD}', [AuthController::class, 'cambiarContrasena']);
Route::get('/registrarse/', [AuthController::class, 'registrarse']);
Route::get('/aviso/{cad}', [AuthController::class, 'aviso']);
Route::post("/cambiarContrasenaSave/{id}",[AuthController::class, 'cambiarContrasenaSave']);
Route::get("/preguntas/",[AuthController::class, 'preguntas']);

//Rutas para registarse
Route::get("/registro/{cad}/",[RegistroValidacionController::class, 'registro']);
Route::post("/validaCURP/{id}",[RegistroValidacionController::class, 'validaCURP']);
Route::get("/registro/formulario/{id}/",[RegistroValidacionController::class, 'registro']);
Route::post("/registrarse/{id}/",[RegistroValidacionController::class, 'registrarse']);
Route::get("/formularioEdit/{id}/cad/{cad}",[RegistroValidacionController::class, 'formularioEdit']);
Route::post("/registrarseUpdate/{id}/",[RegistroValidacionController::class, 'registrarseUpdate']);

//Rutas para votación
Route::get("/voto/{id}/cad/{cad}",[VotacionController::class, 'voto']);
Route::get("/realizarVoto/{id}/{can}",[VotacionController::class, 'realizarVoto']);
Route::post("/realizarVotoPorCargo/{id}/",[VotacionController::class, 'realizarVotoPorCargo']);
Route::get("/anularVoto/{id}/{bol}",[VotacionController::class, 'anularVoto']);

// Solo permite el acceso a la ruta si encuentra una sesión válida
// (antes: filtro 'before' => 'auth' de Laravel 4).
Route::middleware('auth')->group(function () {
    //Menú Principal
    Route::get("/menu/principal",[MenuController::class, 'principal']);////Sirvolab
    Route::get("/menu/comite",[MenuController::class, 'comite']);
    Route::get("/menu/validador",[MenuController::class, 'validador']);
    ////////////////////////////////////////////////////////////////////////////////////////////////////

    //Rutas para los Usuarios
    Route::get("/usuario/index",[UsuarioController::class, 'index']);
    Route::get("/usuario/create",[UsuarioController::class, 'create']);
    Route::post("/usuario/save",[UsuarioController::class, 'store']);
    Route::get("/usuario/edit/{id}",[UsuarioController::class, 'edit']);
    Route::post("/usuario/update/{id}",[UsuarioController::class, 'update']);
    Route::get("/usuario/generarEnviarToken/{id}",[UsuarioController::class, 'generarEnviarToken']);
    Route::get("/usuario/enviarEnlaceAcceso/{id}",[UsuarioController::class, 'enviarEnlaceAcceso']);
    Route::get("/usuario/enviarTokenVoto/{id}/t/{tipo}",[UsuarioController::class, 'enviarTokenVoto']);
    Route::get("/usuario/enviarFormularioEdit/{id}/t/{tipo}",[UsuarioController::class, 'enviarFormularioEdit']);
    Route::get("/usuario/enviarTokenVotoComite/{id}/t/{tipo}",[UsuarioController::class, 'enviarTokenVotoComite']);
    Route::get("/usuario/enviarFormularioEditComite/{id}/t/{tipo}",[UsuarioController::class, 'enviarFormularioEditComite']);
    Route::get("/usuario/enviarTokenVotoComitePorCURP/{id}",[UsuarioController::class, 'enviarTokenVotoComitePorCURP']);
    Route::get("/usuario/bitacora/{id}",[UsuarioController::class, 'bitacora']);
    ////////////////////////////////////////////////////////////////////////////////////////////////////

    //Ruta para las convocatorias
    Route::get("/convocatoria/index",[ConvocatoriaController::class, 'index']);
    Route::get("/convocatoria/indexCerradas",[ConvocatoriaController::class, 'indexCerradas']);
    Route::get("/convocatoria/indexPadron/{id}",[ConvocatoriaController::class, 'indexPadron']);
    Route::get("/convocatoria/indexPadronConsulta/{id}/t/{tipo}",[ConvocatoriaController::class, 'indexPadronConsulta']);
    Route::get("/convocatoria/indexPadronComite/",[ConvocatoriaController::class, 'indexPadronComite']);
    Route::get("/convocatoria/indexPadronComiteConsulta/{tipo}",[ConvocatoriaController::class, 'indexPadronComiteConsulta']);
    Route::get("/convocatoria/cargaPadron/{id}",[ConvocatoriaController::class, 'cargaPadron']);
    Route::post("/convocatoria/cargaPadronUpdate/{id}",[ConvocatoriaController::class, 'cargaPadronUpdate']);
    Route::get("/convocatoria/descargaPadron/{id}",[ConvocatoriaController::class, 'descargaPadron']);
    Route::get("/convocatoria/create",[ConvocatoriaController::class, 'create']);
    Route::post("/convocatoria/save",[ConvocatoriaController::class, 'store']);
    Route::get("/convocatoria/edit/{id}",[ConvocatoriaController::class, 'edit']);
    Route::post("/convocatoria/update/{id}",[ConvocatoriaController::class, 'update']);
    Route::get("/convocatoria/show/{id}",[ConvocatoriaController::class, 'show']);
    Route::get("/convocatoria/asignarComite/{id}",[ConvocatoriaController::class, 'asignarComite']);
    Route::post("/convocatoria/asignarComiteUpdate/{id}",[ConvocatoriaController::class, 'asignarComiteUpdate']);
    Route::get("/convocatoria/asignarValidador",[ConvocatoriaController::class, 'asignarValidador']);
    Route::post("/convocatoria/asignarValidadorUpdate/{id}",[ConvocatoriaController::class, 'asignarValidadorUpdate']);
    Route::get("/convocatoria/cambiaPermiso/{id}",[ConvocatoriaController::class, 'cambiaPermiso']);
    Route::get("/convocatoria/eliminaAsignacion/{id}",[ConvocatoriaController::class, 'eliminaAsignacion']);
    Route::get("/convocatoria/eliminaValidador/{id}",[ConvocatoriaController::class, 'eliminaValidador']);
    Route::get("/convocatoria/autorizacion/",[ConvocatoriaController::class, 'autorizacion']);
    Route::get("/convocatoria/autorizacion/autorizarFaseUno/{cad}",[ConvocatoriaController::class, 'autorizarFaseUno']);
    Route::get("/convocatoria/autorizacion/autorizarFaseDos/{cad}",[ConvocatoriaController::class, 'autorizarFaseDos']);
    Route::get("/convocatoria/autorizacion/autorizarFaseTres/{cad}",[ConvocatoriaController::class, 'autorizarFaseTres']);
    Route::get("/convocatoria/autorizacion/autorizarFaseCuatro/{cad}",[ConvocatoriaController::class, 'autorizarFaseCuatro']);
    Route::get("/convocatoria/eliminaVotante/{id}/t/{tipo}",[ConvocatoriaController::class, 'eliminaVotante']);
    Route::get("/convocatoria/bitacora/{id}",[ConvocatoriaController::class, 'bitacora']);
    Route::get("/convocatoria/bitacoraShow/{id}",[ConvocatoriaController::class, 'bitacoraShow']);
    Route::get("/convocatoria/pruebaRENAPO/",[ConvocatoriaController::class, 'pruebaRENAPO']);
    Route::get("/convocatoria/descargarTodo/{id}",[ConvocatoriaController::class, 'descargarTodo']);
    Route::get("/convocatoria/descargarConsulta/{id}/t/{tipo}",[ConvocatoriaController::class, 'descargarConsulta']);
    Route::get("/convocatoria/descargarActa/",[ConvocatoriaController::class, 'descargarActa']);
    Route::post("/convocatoria/subirActa/",[ConvocatoriaController::class, 'subirActa']);
    Route::get("/convocatoria/busquedaPorCURP/",[ConvocatoriaController::class, 'busquedaPorCURP']);
    Route::post("/convocatoria/busquedaPorCURPConsulta/",[ConvocatoriaController::class, 'busquedaPorCURPConsulta']);
    Route::get("/convocatoria/terminarConvocatoria/{id}",[ConvocatoriaController::class, 'terminarConvocatoria']);
    Route::post("/convocatoria/terminarConvocatoriaUpdate/{id}",[ConvocatoriaController::class, 'terminarConvocatoriaUpdate']);
    ////////////////////////////////////////////////////////////////////////////////////////////////////

    //Ruta para validadores
    Route::get("/validacion/",[RegistroValidacionController::class, 'validacion']);
    Route::post("/validacion/aprobacion/{id}",[RegistroValidacionController::class, 'aprobacion']);
    Route::get("/validacion/rechazo/{id}/mot/{motivoId}",[RegistroValidacionController::class, 'rechazo']);
    ////////////////////////////////////////////////////////////////////////////////////////////////////

    //Ruta para boletas
    Route::get("/boleta/index",[BoletaController::class, 'index']);
    Route::get("/boleta/create",[BoletaController::class, 'create']);
    Route::post("/boleta/save",[BoletaController::class, 'store']);
    Route::get("/boleta/edit/{id}",[BoletaController::class, 'edit']);
    Route::post("/boleta/update/{id}",[BoletaController::class, 'update']);
    Route::get("/boleta/eliminaBoleta/{id}",[BoletaController::class, 'eliminaBoleta']);
    Route::get("/boleta/asignarCandidato/{id}",[BoletaController::class, 'asignarCandidato']);
    Route::post("/boleta/asignarCandidatoUpdate/{id}",[BoletaController::class, 'asignarCandidatoUpdate']);
    Route::get("/boleta/eliminarCandidato/{id}",[BoletaController::class, 'eliminarCandidato']);
    ////////////////////////////////////////////////////////////////////////////////////////////////////

    //Ruta para voto
    Route::get("/consulta/index",[VotacionController::class, 'index']);
    Route::get("/consulta/descargaActa/{id}",[VotacionController::class, 'descargaActa']);
    Route::post("/consulta/cargaActa",[VotacionController::class, 'cargaActa']);
    Route::get("/consulta/descargaActaFirmada/{id}/cad/{cad}",[VotacionController::class, 'descargaActaFirmada']);
    Route::get("/consulta/cerrarConvocatoria/{id}",[VotacionController::class, 'cerrarConvocatoria']);
    ////////////////////////////////////////////////////////////////////////////////////////////////////
});
