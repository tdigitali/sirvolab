<?php

// Inicio de sesion
Route::get('/', 'AuthController@showLogin');
Route::get('/login/', 'AuthController@showLogin');
Route::post('/login/', 'AuthController@signIn');
Route::get('/logout', 'AuthController@logOut');
Route::get('/cambiarContrasena/u/{idU}/d/{idD}', 'AuthController@cambiarContrasena');
Route::get('/registrarse/', 'AuthController@registrarse');
Route::get('/aviso/{cad}', 'AuthController@aviso');
Route::post("/cambiarContrasenaSave/{id}",'AuthController@cambiarContrasenaSave');
Route::get("/preguntas/",'AuthController@preguntas');

//Rutas para registarse
Route::get("/registro/{cad}/",'RegistroValidacionController@registro');
Route::post("/validaCURP/{id}",'RegistroValidacionController@validaCURP');
Route::get("/registro/formulario/{id}/",'RegistroValidacionController@registro');
Route::post("/registrarse/{id}/",'RegistroValidacionController@registrarse');
Route::get("/formularioEdit/{id}/cad/{cad}",'RegistroValidacionController@formularioEdit');
Route::post("/registrarseUpdate/{id}/",'RegistroValidacionController@registrarseUpdate');

//Rutas para votación
Route::get("/voto/{id}/cad/{cad}",'VotacionController@voto');
Route::get("/realizarVoto/{id}/{can}",'VotacionController@realizarVoto');
Route::post("/realizarVotoPorCargo/{id}/",'VotacionController@realizarVotoPorCargo');
Route::get("/anularVoto/{id}/{bol}",'VotacionController@anularVoto');

//Solo permite el acceso a la ruta si encuentra una sesión válida.
Route::group(array('before' => 'auth'), function(){
    //Menú Principal
    Route::get("/menu/principal","MenuController@principal");////Sirvolab
    Route::get("/menu/comite","MenuController@comite");
    Route::get("/menu/validador","MenuController@validador");
    ////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //Rutas para los Usuarios
    Route::get("/usuario/index","UsuarioController@index");
    Route::get("/usuario/create","UsuarioController@create");
    Route::post("/usuario/save","UsuarioController@store");
    Route::get("/usuario/edit/{id}","UsuarioController@edit");
    Route::post("/usuario/update/{id}","UsuarioController@update");
    Route::get("/usuario/generarEnviarToken/{id}","UsuarioController@generarEnviarToken");
    Route::get("/usuario/enviarEnlaceAcceso/{id}","UsuarioController@enviarEnlaceAcceso");
    Route::get("/usuario/enviarTokenVoto/{id}/t/{tipo}",'UsuarioController@enviarTokenVoto');    
    Route::get("/usuario/enviarFormularioEdit/{id}/t/{tipo}",'UsuarioController@enviarFormularioEdit');
    Route::get("/usuario/enviarTokenVotoComite/{id}/t/{tipo}",'UsuarioController@enviarTokenVotoComite');    
    Route::get("/usuario/enviarFormularioEditComite/{id}/t/{tipo}",'UsuarioController@enviarFormularioEditComite');
    Route::get("/usuario/enviarTokenVotoComitePorCURP/{id}",'UsuarioController@enviarTokenVotoComitePorCURP');
    Route::get("/usuario/bitacora/{id}","UsuarioController@bitacora");
    ////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //Ruta para las convocatorias
    Route::get("/convocatoria/index","ConvocatoriaController@index");
    Route::get("/convocatoria/indexCerradas","ConvocatoriaController@indexCerradas");
    Route::get("/convocatoria/indexPadron/{id}","ConvocatoriaController@indexPadron");
    Route::get("/convocatoria/indexPadronConsulta/{id}/t/{tipo}","ConvocatoriaController@indexPadronConsulta");
    Route::get("/convocatoria/indexPadronComite/","ConvocatoriaController@indexPadronComite");
    Route::get("/convocatoria/indexPadronComiteConsulta/{tipo}","ConvocatoriaController@indexPadronComiteConsulta");
    Route::get("/convocatoria/cargaPadron/{id}","ConvocatoriaController@cargaPadron");
    Route::post("/convocatoria/cargaPadronUpdate/{id}","ConvocatoriaController@cargaPadronUpdate");
    Route::get("/convocatoria/descargaPadron/{id}","ConvocatoriaController@descargaPadron");
    Route::get("/convocatoria/create","ConvocatoriaController@create");
    Route::post("/convocatoria/save","ConvocatoriaController@store");
    Route::get("/convocatoria/edit/{id}","ConvocatoriaController@edit");
    Route::post("/convocatoria/update/{id}","ConvocatoriaController@update");
    Route::get("/convocatoria/show/{id}","ConvocatoriaController@show");
    Route::get("/convocatoria/asignarComite/{id}","ConvocatoriaController@asignarComite");
    Route::post("/convocatoria/asignarComiteUpdate/{id}","ConvocatoriaController@asignarComiteUpdate");
    Route::get("/convocatoria/asignarValidador","ConvocatoriaController@asignarValidador");
    Route::post("/convocatoria/asignarValidadorUpdate/{id}","ConvocatoriaController@asignarValidadorUpdate");
    Route::get("/convocatoria/cambiaPermiso/{id}","ConvocatoriaController@cambiaPermiso");
    Route::get("/convocatoria/eliminaAsignacion/{id}","ConvocatoriaController@eliminaAsignacion");
    Route::get("/convocatoria/eliminaValidador/{id}","ConvocatoriaController@eliminaValidador");
    Route::get("/convocatoria/autorizacion/","ConvocatoriaController@autorizacion");
    Route::get("/convocatoria/autorizacion/autorizarFaseUno/{cad}","ConvocatoriaController@autorizarFaseUno");
    Route::get("/convocatoria/autorizacion/autorizarFaseDos/{cad}","ConvocatoriaController@autorizarFaseDos");
    Route::get("/convocatoria/autorizacion/autorizarFaseTres/{cad}","ConvocatoriaController@autorizarFaseTres");
    Route::get("/convocatoria/autorizacion/autorizarFaseCuatro/{cad}","ConvocatoriaController@autorizarFaseCuatro");
    Route::get("/convocatoria/eliminaVotante/{id}/t/{tipo}","ConvocatoriaController@eliminaVotante");
    Route::get("/convocatoria/bitacora/{id}","ConvocatoriaController@bitacora");
    Route::get("/convocatoria/bitacoraShow/{id}","ConvocatoriaController@bitacoraShow");
    Route::get("/convocatoria/pruebaRENAPO/","ConvocatoriaController@pruebaRENAPO");
    Route::get("/convocatoria/descargarTodo/{id}","ConvocatoriaController@descargarTodo");
    Route::get("/convocatoria/descargarConsulta/{id}/t/{tipo}","ConvocatoriaController@descargarConsulta");
    Route::get("/convocatoria/descargarActa/","ConvocatoriaController@descargarActa");
    Route::post("/convocatoria/subirActa/","ConvocatoriaController@subirActa");
    Route::get("/convocatoria/busquedaPorCURP/","ConvocatoriaController@busquedaPorCURP");
    Route::post("/convocatoria/busquedaPorCURPConsulta/","ConvocatoriaController@busquedaPorCURPConsulta");
    Route::get("/convocatoria/terminarConvocatoria/{id}","ConvocatoriaController@terminarConvocatoria");
    Route::post("/convocatoria/terminarConvocatoriaUpdate/{id}","ConvocatoriaController@terminarConvocatoriaUpdate");
    ////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //Ruta para validadores
    Route::get("/validacion/","RegistroValidacionController@validacion");
    Route::post("/validacion/aprobacion/{id}","RegistroValidacionController@aprobacion");
    Route::get("/validacion/rechazo/{id}/mot/{motivoId}","RegistroValidacionController@rechazo");
    ////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //Ruta para boletas
    Route::get("/boleta/index","BoletaController@index");
    Route::get("/boleta/create","BoletaController@create");
    Route::post("/boleta/save","BoletaController@store");
    Route::get("/boleta/edit/{id}","BoletaController@edit");
    Route::post("/boleta/update/{id}","BoletaController@update");
    Route::get("/boleta/eliminaBoleta/{id}","BoletaController@eliminaBoleta");
    Route::get("/boleta/asignarCandidato/{id}","BoletaController@asignarCandidato");
    Route::post("/boleta/asignarCandidatoUpdate/{id}","BoletaController@asignarCandidatoUpdate");
    Route::get("/boleta/eliminarCandidato/{id}","BoletaController@eliminarCandidato");
    ////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //Ruta para voto
    Route::get("/consulta/index","VotacionController@index");
    Route::get("/consulta/descargaActa/{id}","VotacionController@descargaActa");
    Route::post("/consulta/cargaActa","VotacionController@cargaActa");
    Route::get("/consulta/descargaActaFirmada/{id}/cad/{cad}","VotacionController@descargaActaFirmada");
    Route::get("/consulta/cerrarConvocatoria/{id}","VotacionController@cerrarConvocatoria");
    ////////////////////////////////////////////////////////////////////////////////////////////////////
});