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
                    <a href="/convocatoria/indexPadron/{{Crypt::encrypt($convocatoria->id)}}">
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
                <br/>
                <hr style="border:1px solid #10312B; width: 100%;"/>
                <br/>
                <div style="text-align: center; width: 100%; margin: 0 auto;">
                    <h1 class="ui header" style="color: #235B4E; font-size: 200%;">
                        {{$convocatoria->nombre}}
                    </h1>
                </div>
                <br/>
                <hr style="border:1px solid #10312B; width: 100%;"/>
                <br/>
            </div>
        </div>
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
                    <th>&nbsp;&nbsp;CURP&nbsp;&nbsp;</th>
                    <th>Nombre Completo&nbsp;&nbsp;</th>
                    <th>Centro de Trabajo&nbsp;&nbsp;</th>
                    <th>Entidad Federativa&nbsp;&nbsp;</th>
                    <th colspan="3">{{$tipo == 5 ? 'Boleta' : 'Acciones'}}</th>
                </thead>
                <tbody>
                    @if($tipo == 5)
                        @foreach($convocatoriaPadron as $padron)
                            <tr>
                                <td>&nbsp;{{ConvocatoriaPadron::find($padron->convocatoria_padron_id)->curp}}&nbsp;&nbsp;</td>
                                <td>&nbsp;{{ConvocatoriaPadron::find($padron->convocatoria_padron_id)->nombre_completo}}&nbsp;&nbsp;</td>
                                <td>&nbsp;{{ConvocatoriaCentroTrabajo::find(ConvocatoriaPadron::find($padron->convocatoria_padron_id)->centro_trabajo_id)->nombre}}&nbsp;&nbsp;</td>
                                <td>&nbsp;{{EntidadFederativa::find(ConvocatoriaPadron::find($padron->convocatoria_padron_id)->entidad_federativa_id)->nombre}}&nbsp;&nbsp;</td>
                                <td colspan="3">{{Boleta::find($padron->boleta_id)->cargo_postulacion}}</td>
                            </tr>
                        @endforeach
                    @else
                        @foreach($convocatoriaPadron as $padron)
                            <tr>
                                <td>&nbsp;{{$padron->curp}}&nbsp;&nbsp;</td>
                                <td>&nbsp;{{$padron->nombre_completo}}&nbsp;&nbsp;</td>
                                <td>&nbsp;{{ConvocatoriaCentroTrabajo::find($padron->centro_trabajo_id)->nombre}}&nbsp;&nbsp;</td>
                                <td>&nbsp;{{EntidadFederativa::find($padron->entidad_federativa_id)->nombre}}&nbsp;&nbsp;</td>
                                @if($padron->fecha_validacion)
                                    <td onclick="copiar('contrasena' + '{{$padron->id}}');">
                                        {{Form::hidden('enlace', Ambiente::find(1)->nombre.'voto/'.Crypt::encrypt($padron->id).'/cad/'.Crypt::encrypt($padron->cadena_validacion), ['id' => 'contrasena'.$padron->id, 'style' => 'color: grey', 'maxlength' => '300'])}}
                                        <a href="/usuario/enviarTokenVoto/{{Crypt::encrypt($padron->id)}}/t/{{$tipo}}" title="Copiar y enviar enlace de voto">
                                            <p style="font-size: 200%">
                                                <i class="linkify icon" style="color: #235B4E" onmouseover="this.style.color = '#691C32'" onmouseout="this.style.color = '#235B4E'"></i>&nbsp;
                                            </p>
                                        </a>
                                    </td>
                                @else
                                    <td>
                                        &nbsp;
                                    </td>
                                @endif
                                <td onclick="copiarToken('actualizacion' + '{{$padron->id}}');">
                                    {{Form::hidden('token', Ambiente::find(1)->nombre.'formularioEdit/'.Crypt::encrypt($padron->id).'/cad/'.Crypt::encrypt($padron->cadena_validacion), ['id' => 'actualizacion'.$padron->id, 'style' => 'color: grey', 'maxlength' => '300'])}}
                                    <a href="/usuario/enviarFormularioEdit/{{Crypt::encrypt($padron->id)}}/t/{{$tipo}}" title="Copiar y enviar edición de formulario">
                                        <p style="font-size: 200%">
                                            <i class="edit icon" style="color: #235B4E" onmouseover="this.style.color = '#691C32'" onmouseout="this.style.color = '#235B4E'"></i>&nbsp;
                                        </p>
                                    </a>
                                </td>
                                <td>
                                    <a href="/convocatoria/eliminaVotante/{{Crypt::encrypt($padron->id)}}/t/{{$tipo}}" onclick="return confirm('¿Desea eliminar al votante?')" title="Eliminar usuario">
                                        <p style="font-size: 200%">
                                            <i class="remove circle icon" style="color: #235B4E" onmouseover="this.style.color = '#691C32'" onmouseout="this.style.color = '#235B4E'"></i>&nbsp;
                                        </p>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        {{$convocatoriaPadron->links()}}
    </div>
@endsection

@section('scripts-page')
    <script type="text/javascript">
        function copiar(cad){
            var aux = document.createElement("input");
            aux.setAttribute("value", document.getElementById(cad).value);
            document.body.appendChild(aux);
            aux.select();
            document.execCommand("copy");
            document.body.removeChild(aux);
            alert("Vínculo para voto copiado");
        }
        
        function copiarToken(cad){
            var aux = document.createElement("input");
            aux.setAttribute("value", document.getElementById(cad).value);
            document.body.appendChild(aux);
            aux.select();
            document.execCommand("copy");
            document.body.removeChild(aux);
            alert("Vínculo para edición de formulario copiado");
        }
    </script>
@endsection