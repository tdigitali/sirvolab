@extends('layouts.ly_admin')

@section('title')
    <title>Convocatorias</title>
@endsection

@section('content')
    <div class="ui segment" id="content-page" style="background-color: transparent; text-align: center;">
        <div class="ui grid">
            <div class="one wide column">
                <div style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;" onmouseover="document.getElementById('regresar').src = '/assets/img/regresar_blanco.png'; document.getElementById('regresarHR').style.border = '1px solid white'; document.getElementById('regresarL').style.color = 'white'; this.style.backgroundColor = '#10312B';" onmouseout="document.getElementById('regresar').src = '/assets/img/regresar_verde.png'; document.getElementById('regresarHR').style.border = '1px solid #10312B'; document.getElementById('regresarL').style.color = '#10312B'; this.style.backgroundColor = 'transparent';">
                    <br/>
                    <a href="/menu/principal/">
                        <img id="regresar" style="margin: auto; width: 45%; height: 45%;" src="/assets/img/regresar_verde.png" class="ui image">
                        <div style="background-color: transparent; text-align: center;">
                            <hr id="regresarHR" style="border:1px solid #10312B; width: 50%;"/>
                            <label id="regresarL" class="ui header" style="color: #10312B; font-size: 100%; font-weight: bolder; cursor: pointer;">
                                Regresar
                            </label>
                        </div>
                    </a>
                </div>
            </div>
            <div class="fourteen wide column">
                <br/>
                <div style="text-align: center; width: 100%; margin: 0 auto;">
                    <h1 class="ui header" style="color: #10312B; font-size: 450%;">
                        Pruebas de RENAPO
                    </h1>
                </div>
            </div>
        </div>
        <br/>
        <center>
            <h3 class="ui header" style="color: white; background-color: #3DAD93; text-align: center;">
                <i style="margin-top: 3px;" class="save layout icon"></i>{{$curp}}
            </h3>
            <br/>
            <br/>
            <h3 class="ui header" style="color: white; background-color: #3DAD93; text-align: center;">
                <i style="margin-top: 3px;" class="save layout icon"></i>{{$response}}
            </h3>
            <br/>
            <br/>
            <h3 class="ui header" style="color: white; background-color: #3DAD93; text-align: center;">
                <i style="margin-top: 3px;" class="save layout icon"></i>{{$error}}
            </h3>
        </center>
    </div>
@endsection