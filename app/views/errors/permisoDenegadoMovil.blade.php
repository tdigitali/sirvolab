@extends('layouts.ly_registro')

@section('title') Acceso Denegado @endsection

@section('content')
    <div class="ui segment" id="content-page" style="background-color: transparent; text-align: center;">
        <div class="ui grid">
            <div class="sixteen wide column">
                <br/>
                <div style="text-align: center; width: 100%; margin: 0 auto;">
                    <h1 class="ui header" style="color: #10312B; font-size: 220%;">
                        Acceso denegado
                    </h1>
                </div>
            </div>
        </div>
        <br/>
        <br/>
        <br/>
        <div>
            <h3 class="ui header" style="color: white; background-color: #3DAD93;">
                <i style="margin-top: 3px;" class="remove circle layout icon"></i>Lo sentimos, pero no cuenta con los permisos necesarios para visualizar esta página si usted cree que es un error por favor contacte al administrador.
            </h3>
            <br/>
            <h4 class="ui header" style="color: #235B4E">
                <i class="mail layout icon"></i>En caso de considerar que esto es un error, por favor contacte al administrador.
            </h4>
            <p>
                <b style="color: #691C32; font-size: x-large">Contacto</b>
                <br/>
                <b style="color: #235B4E; font-size: medium">edward.raul@stps.gob.mx</b>
                <br/>
                <b style="color: #235B4E; font-size: medium">Edward Raúl García Mejía</b>
                <br/>
                <b style="color: #235B4E; font-size: medium">ext. 62599</b>
                <br/>
                <b style="color: #235B4E; font-size: medium">ADMINISTRADOR DEL SISTEMA</b>
            </p> 
            <br/>
            <center>
                <a href="/menu/principal/" title="Regresar">
                    <div style="cursor: pointer; background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;">
                        <br/>
                        <img id="regresar" style="margin: auto; width: 45%; height: 45%;" src="/assets/img/regresar_verde.png" class="ui image">
                        <div style="background-color: transparent; text-align: center;">
                            <hr id="regresarHR" style="border:1px solid #10312B; width: 50%;"/>
                            <label id="regresarL" class="ui header" style="color: #10312B; font-size: 100%; font-weight: bolder; cursor: pointer;">
                                Regresar
                            </label>
                        </div>
                    </div>
                </a>
            </center>
        </div>
    </div>
@endsection