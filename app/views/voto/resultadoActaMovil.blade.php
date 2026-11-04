@extends('layouts.ly_registro')

@section('title') Confirmacion @endsection

@section('content')
    <div class="ui segment" id="content-page" style="background-color: transparent; text-align: center;">
        <div class="ui grid">
            <div class="sixteen wide column">
                <br/>
                <div style="text-align: center; width: 100%; margin: 0 auto;">
                    <h1 class="ui header" style="color: #10312B; font-size: 230%;">
                        {{$mensaje}}
                    </h1>
                </div>
                <br/>
                <br/>
                @if(!$banderaError)
                    <div style="text-align: center;">
                        <img id="convocatorias" style="width: 60%; height: 60%;" src="/assets/img/aprobado.png" >
                    </div>
                @endif
            </div>
            <div style="background-color: transparent; margin: auto;">
                <br/>
                <a href="/menu/principal/">
                    <div style="background-color: transparent; text-align: center;">
                        <label id="regresarL" onmouseover="this.style.color = '#3DAD93';" onmouseout="this.style.color = '#10312B'" class="ui header" style="color: #10312B; font-size: 150%; font-weight: bolder; cursor: pointer;">
                            Regresar
                        </label>
                        <hr id="regresarHR" style="border:1px solid #10312B; width: 100%;"/>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection