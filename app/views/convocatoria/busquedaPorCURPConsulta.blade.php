@extends('layouts.ly_admin')

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
            <div class="one wide column">
                <div style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;" onmouseover="document.getElementById('regresar').src = '/assets/img/regresar_blanco.png'; document.getElementById('regresarHR').style.border = '1px solid white'; document.getElementById('regresarL').style.color = 'white'; this.style.backgroundColor = '#10312B';" onmouseout="document.getElementById('regresar').src = '/assets/img/regresar_verde.png'; document.getElementById('regresarHR').style.border = '1px solid #10312B'; document.getElementById('regresarL').style.color = '#10312B'; this.style.backgroundColor = 'transparent';">
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
            <div class="fourteen wide column">
                <br/>
                <div style="text-align: center; width: 100%; margin: 0 auto;">
                    <h1 class="ui header" style="color: #10312B; font-size: 500%;">
                        Padrón de la convocatoria
                    </h1>
                </div>
                <br/>
                <div style="text-align: center; width: 100%; margin: 0 auto;">
                    <h1 class="ui header" style="color: #235B4E; font-size: 200%;">
                        {{Convocatoria::find($convocatoriaPadron->convocatoria_id)->nombre}}
                    </h1>
                </div>
            </div>
        </div>
        <br/>
        <p class="ui header" style="color: #10312B">
            <i class="find layout icon white"></i>@if(count($convocatoriaPadron) == 0) No se encontraron registros @endif @if(count($convocatoriaPadron) == 1) Se encontró un registro @endif @if(count($convocatoriaPadron) > 1) Se encontraron {{count($convocatoriaPadron)}} registros @endif
        </p>
        <div class="menu">
            <table align="center" style="width: 100%;">
                <thead>
                    <th>&nbsp;&nbsp;CURP&nbsp;&nbsp;</th>
                    <th>Nombre Completo&nbsp;&nbsp;</th>
                    <th>Correo Electrónico&nbsp;&nbsp;</th>
                    <th>Acciones</th>
                </thead>
                <tbody>
                    <tr>
                        <td>&nbsp;{{$convocatoriaPadron->curp}}&nbsp;&nbsp;</td>
                        <td>&nbsp;{{$convocatoriaPadron->nombre_completo}}&nbsp;&nbsp;</td>
                        <td>&nbsp;{{$convocatoriaPadron->correo_electronico}}&nbsp;&nbsp;</td>
                        <td>
                            <a href="/usuario/enviarTokenVotoComitePorCURP/{{Crypt::encrypt($convocatoriaPadron->id)}}" title="Enviar enlace de voto">
                                <p style="font-size: 200%">
                                    <i class="linkify icon" style="color: #235B4E" onmouseover="this.style.color = '#691C32'" onmouseout="this.style.color = '#235B4E'"></i>&nbsp;
                                </p>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection