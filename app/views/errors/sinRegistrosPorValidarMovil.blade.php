@extends('layouts.ly_adminMovil')

@section('title') Usuarios @endsection

@section('content')
    <div class="ui segment" id="content-page" style="background-color: transparent; text-align: center;">
        <div class="ui grid">
            <div class="sixteen wide column">
                <br/>
                <div style="text-align: center; width: 100%; margin: 0 auto;">
                    <h1 class="ui header" style="color: #10312B; font-size: 220%;">
                        Validaciones
                    </h1>
                </div>
            </div>
        </div>
        <br/>
        @if($mensaje != null)
            <h3 class="ui header" style="color: white; background-color: #3DAD93;">
                <i style="margin-top: 3px;" class="remove circle layout icon"></i>{{$mensaje}}
            </h3>
        @endif
        <br/>
        <br/>
        <div style="text-align:center;">
            <div style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;">
                <br/>
                <a href="/menu/principal">
                    <img id="regresar" style="margin: auto; width: 45%; height: 45%;" src="/assets/img/regresar_verde.png" class="ui image">
                    <div style="background-color: transparent; text-align: center;">
                        <hr id="regresarHR" style="border:1px solid #10312B; width: 50%;"/>
                        <label id="regresarL" class="ui header" style="color: #10312B; font-size: 100%; font-weight: bolder; cursor: pointer;">
                            regresar
                        </label>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection