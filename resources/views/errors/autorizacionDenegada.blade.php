@extends('layouts.ly_admin')

@section('title') Autorización Denegada @endsection

@section('content')
    <div class="ui segment" id="content-page" style="background-color: transparent; text-align: center;">
        <div class="ui grid">
            <div class="one wide column">
                <a href="/menu/principal/" title="Regresar">
                    <div style="cursor: pointer; background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;" onmouseover="document.getElementById('regresar').src = '/assets/img/regresar_blanco.png'; document.getElementById('regresarHR').style.border = '1px solid white'; document.getElementById('regresarL').style.color = 'white'; this.style.backgroundColor = '#10312B';" onmouseout="document.getElementById('regresar').src = '/assets/img/regresar_verde.png'; document.getElementById('regresarHR').style.border = '1px solid #10312B'; document.getElementById('regresarL').style.color = '#10312B'; this.style.backgroundColor = 'transparent';">
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
            </div>
            <div class="fourteen wide column">
                <br/>
                <div style="text-align: center; width: 100%; margin: 0 auto;">
                    <h1 class="ui header" style="color: #10312B; font-size: 500%;">
                        Acceso denegado
                    </h1>
                </div>
            </div>
        </div>
        <br/>
        @if($mensajeDeValidacion != null)
            <h3 class="ui header" style="color: white; background-color: #3DAD93;">
                <i style="margin-top: 3px;" class="remove circle layout icon"></i>{{$mensajeDeValidacion}}
            </h3>
        @endif
        <br/>
        <br/>
        <div>
            <h4 class="ui header" style="color: #235B4E">
                <i class="lock layout icon"></i>En caso de considerar que esto es un error, por favor contacte al administrador.
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
        </div>
    </div>
@endsection