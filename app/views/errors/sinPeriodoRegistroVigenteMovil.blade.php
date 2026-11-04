@extends('layouts.ly_registro')

@section('title') Validaciones @endsection

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