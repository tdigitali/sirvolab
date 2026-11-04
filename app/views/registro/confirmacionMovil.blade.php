@extends('layouts.ly_registro')

@section('title') Confirmacion @endsection

@section('content')
    <div class="ui segment" id="content-page" style="background-color: transparent; text-align: center;">
        <div class="ui grid">
            <div class="sixteen wide column">
                <br/>
                <div style="text-align: center; width: 100%; margin: 0 auto;">
                    <h1 class="ui header" style="color: #10312B; font-size: 220%;">
                        ¡Gracias por registrate a la convocatoria a través de SIRVOLAB!
                    </h1>
                </div>
                <br/>
                <div style="text-align: center;">
                    <img id="convocatorias" style="width: 50%; height: 50%;" src="/assets/img/validacion.png">
                </div>
            </div>
        </div>
    </div>
@endsection