@extends('layouts.ly_adminMovil')

@section('title')
    <title>Padrón</title>
@endsection

@section('scripts')
    <style>
        tr:hover{
            color: #10312B;
        }
    </style>
@endsection

@section('content')
    <div class="ui segment" id="content-page" style="background-color: transparent; text-align: center;">
        <div class="ui grid">
            <div class="sixteen wide column">
                <br/>
                <div style="text-align: center; width: 100%; margin: 0 auto;">
                    <h1 class="ui header" style="color: #10312B; font-size: 220%;">
                        Padrón de la convocatoria
                    </h1>
                </div>
                <br/>
                <hr style="border:1px solid #10312B; width: 100%;"/>
                <br/>
                <div style="text-align: center; width: 100%; margin: 0 auto;">
                    <h1 class="ui header" style="color: #235B4E; font-size: 120%;">
                        {{$convocatoria->nombre}}
                    </h1>
                </div>
                <br/>
                <hr style="border:1px solid #10312B; width: 100%;"/>
                <br/>
            </div>
        </div>
        <br/>
        <center>
            <div style="background-color: #235B4E; width: 70%; border-radius: 15px 15px 15px 15px;" onmouseover="this.style.backgroundColor = '#10312B';" onmouseout="this.style.backgroundColor = '#235B4E';">
                <p style="color: red; font-size: 130%">
                    <a id="creaUsr" href="/convocatoria/cargaPadron/{{Crypt::encrypt($convocatoria->id)}}" style="color: white;">
                        <i id="icono" class="add icon"></i>Cargar padrón
                    </a>
                </p>
            </div>
        </center>
        @if(Session::get("message") != null)
            <h3 class="ui header" style="color: white; background-color: #3DAD93;">
                <i style="margin-top: 3px;" class="save layout icon"></i>{{Session::get("message")}}
            </h3>
        @endif
        @if(Session::get("messageError") != null)
            <h3 class="ui header" style="color: white; background-color: #3DAD93;">
                <i style="margin-top: 3px;" class="remove circle icon"></i>{{Session::get("messageError")}}
            </h3>
        @endif
        <p class="ui header" style="color: #10312B">
            <i class="find layout icon white"></i>@if(count($convocatoriaPadron) == 0) No se encontraron registros @endif @if(count($convocatoriaPadron) == 1) Se encontró un registro @endif @if(count($convocatoriaPadron) > 1) Se encontraron {{count($convocatoriaPadron)}} registros @endif
        </p>
        <div class="menu">
            <table align="center" style="width: 100%;">
                <thead>
                    @if($tipo == 5)
                        <th>&nbsp;&nbsp;Datos&nbsp;&nbsp;</th>
                        <th>Boleta</th>
                    @else
                        <th>&nbsp;&nbsp;CURP&nbsp;&nbsp;</th>
                        <th colspan="2">Acciones</th>
                    @endif
                </thead>
                <tbody>
                    @if($tipo == 5)
                        @foreach($convocatoriaPadron as $padron)
                            <tr>
                                <td>
                                    &nbsp;{{\App\Models\ConvocatoriaPadron::find($padron->convocatoria_padron_id)->curp}}&nbsp;&nbsp;
                                    &nbsp;{{\App\Models\EntidadFederativa::find(\App\Models\ConvocatoriaPadron::find($padron->convocatoria_padron_id)->entidad_federativa_id)->nombre}}&nbsp;&nbsp;
                                </td>
                                <td>{{\App\Models\Boleta::find($padron->boleta_id)->cargo_postulacion}}</td>
                            </tr>
                        @endforeach
                    @else
                        @foreach($convocatoriaPadron as $padron)
                            <tr>
                                <td>&nbsp;{{$padron->curp}}&nbsp;&nbsp;</td>
                                <td>
                                    @if($padron->fecha_validacion)
                                        <a href="/usuario/enviarTokenVotoComite/{{Crypt::encrypt($padron->id)}}/t/{{$tipo}}" title="Copiar y enviar enlace de voto">
                                            <p style="font-size: 200%">
                                                <i class="linkify icon" style="color: #235B4E" onmouseover="this.style.color = '#691C32'" onmouseout="this.style.color = '#235B4E'"></i>&nbsp;
                                            </p>
                                        </a>
                                    @else
                                        &nbsp;&nbsp;
                                    @endif
                                </td>
                                <td>
                                    <a href="/usuario/enviarFormularioEditComite/{{Crypt::encrypt($padron->id)}}/t/{{$tipo}}" title="Copiar y enviar edición de formulario">
                                        <p style="font-size: 200%">
                                            <i class="edit icon" style="color: #235B4E" onmouseover="this.style.color = '#691C32'" onmouseout="this.style.color = '#235B4E'"></i>&nbsp;
                                        </p>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        {!!$convocatoriaPadron->links()!!}
        <br/>
        <div style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;">
            <br/>
            <a href="/convocatoria/indexPadronComite/">
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
@endsection