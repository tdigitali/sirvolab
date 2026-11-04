@extends('layouts.ly_admin')

@section('css')
    <link rel="stylesheet" href="/assets/js/pickadate/lib/themes/classic.css"/>
    <link rel="stylesheet" href="/assets/js/pickadate/lib/themes/classic.date.css"/>
@endsection

@section('scripts')
    <script src="/assets/js/pickadate/lib/picker.js"></script>
    <script src="/assets/js/pickadate/lib/picker.date.js"></script>
@endsection

@section('content')
    <div class="ui form">
        <div class="ui segment" id="content-page" style="background-color: transparent;">
            <div style="text-align: center; width: 61%; margin: 0 auto;">
                <label class="ui header" style="color: #10312B; font-size: 700%;">
                    SIRVOLAB
                </label>
                <br/>
                <label class="ui header" style="color: #10312B; font-size: 270%;">
                    Sistema Remoto de Votación Laboral
                </label>
            </div>
            <br/>
            <div class="ui grid">
                <div class="three wide column">
                    <div style="background-color: transparent;">
                        <br/>
                        &nbsp;
                    </div>
                </div>
                <div class="ten wide column">
                    <div class="ui styled">
                        <div class="content active">
                            <div class="ui styled accordion fluid" style="background-color: transparent;">
                                <br/>
                                <center>
                                    <div class="title active" style="font-size: medium">
                                        <label class="ui header" style="color: #12322B; font-size: 180%; font-weight: bolder; cursor: pointer;">
                                            &nbsp;Estatus de la convocatoria
                                        </label>
                                        <hr style="border:1px solid #12322B; width: 60%; margin: 0;"/>
                                        <br/>
                                    </div>
                                    <div class="content active">
                                        <table>
                                            <tr>
                                                <td style="background-color: transparent; width: 15%">
                                                    <img style="width: 70px; height: 70px; margin: auto; filter: {{$convocatoria->convocatoria_estatus_id == 1 ? 'brightness(100%)' : 'brightness(20%)'}};" src="/assets/img/proceso.png" class="ui image">
                                                </td>
                                                <td style="background-color: transparent; width: 5%">&nbsp;<font style="color: #10312B; font-weight: bold;">_____</font></td>
                                                <td style="background-color: transparent; width: 15%">
                                                    <img style="width: 70px; height: 70px; margin: auto; filter: {{$convocatoria->convocatoria_estatus_id == 2 ? 'brightness(100%)' : 'brightness(20%)'}};" src="/assets/img/validacion.png" class="ui image">
                                                </td>
                                                <td style="background-color: transparent;  width: 5%">&nbsp;<font style="color: #10312B; font-weight: bold;">_____</font></td>
                                                <td style="background-color: transparent; width: 15%">
                                                    <img style="width: 70px; height: 70px; margin: auto; filter: {{$convocatoria->convocatoria_estatus_id == 3 ? 'brightness(100%)' : 'brightness(20%)'}};" src="/assets/img/votacion.png" class="ui image">
                                                </td>
                                                <td style="background-color: transparent; width: 5%"><font style="color: #10312B; font-weight: bold;">_____</font></td>
                                                <td style="background-color: transparent; width: 15%">
                                                    <img style="width: 70px; height: 70px; margin: auto; filter: {{$convocatoria->convocatoria_estatus_id == 4 ? 'brightness(100%)' : 'brightness(20%)'}};" src="/assets/img/archivo.png" class="ui image">
                                                </td>
                                                <td style="background-color: transparent; width: 5%">&nbsp;<font style="color: #10312B; font-weight: bold;">_____&nbsp;</font></td>
                                                <td style="background-color: transparent; width: 15%">
                                                    <img style="width: 70px; height: 70px; margin: auto; filter: {{$convocatoria->convocatoria_estatus_id == 5 ? 'brightness(100%)' : 'brightness(20%)'}};" src="/assets/img/resultados_solo_verde.png" class="ui image">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="background-color: transparent; width: 15%">
                                                    <br/>{!!$convocatoria->convocatoria_estatus_id == 1 ? '<font style="color: #3DAD93; font-weight: bold;">Configuración de convocatoria</font>' : 'Configuración de convocatoria'!!}
                                                </td>
                                                <td style="background-color: transparent; width: 5%">&nbsp;</td>
                                                <td style="background-color: transparent; width: 15%">
                                                    <br/>{!!$convocatoria->convocatoria_estatus_id == 2 ? '<font style="color: #3DAD93; font-weight: bold;">Registro y validación</font>' : 'Registro y validación'!!}
                                                </td>
                                                <td style="background-color: transparent;  width: 5%">&nbsp;</td>
                                                <td style="background-color: transparent; width: 15%">
                                                    <br/>{!!$convocatoria->convocatoria_estatus_id == 3 ? '<font style="color: #3DAD93; font-weight: bold;">Configuración de boleta(s)</font>' : 'Configuración de boleta(s)'!!}
                                                </td>
                                                <td style="background-color: transparent; width: 5%">&nbsp;</td>
                                                <td style="background-color: transparent; width: 15%">
                                                    <br/>{!!$convocatoria->convocatoria_estatus_id == 4 ? '<font style="color: #3DAD93; font-weight: bold;">Consulta</font>' : 'Consulta'!!}
                                                </td>
                                                <td style="background-color: transparent; width: 5%">&nbsp;</td>
                                                <td style="background-color: transparent; width: 15%">
                                                    <br/>{!!$convocatoria->convocatoria_estatus_id == 5 ? '<font style="color: #3DAD93; font-weight: bold;">Resultados y cierre</font>' : 'Resultados y cierre'!!}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="title" style="font-size: medium">
                                        <label class="ui header" style="color: #12322B; font-size: 180%; font-weight: bolder; cursor: pointer;">
                                            &nbsp;Información de la convocatoria
                                        </label>
                                        <hr style="border:1px solid #12322B; width: 60%; margin: 0;"/>
                                        <br/>
                                    </div>
                                    <div class="content">
                                        <div class="column">
                                            <p class="ui header" style="color: #235B4E; text-align: center; font-size: 150%">
                                                <i style="color: #235B4E;" class="building icon"></i>Sindicato
                                            </p>
                                        </div>
                                        <br/>
                                        <center>
                                            <label class="ui header" style="color: #10312B; font-size: 130%;">
                                                {{$convocatoria->nombre_sindicato}}
                                            </label>
                                        </center>
                                        <br/>
                                        <br/>
                                        <div class="column">
                                            <p class="ui header" style="color: #235B4E; text-align: center; font-size: 150%">
                                                <i style="color: #235B4E;" class="bookmark icon"></i>Convocatoria
                                            </p>
                                        </div>
                                        <br/>
                                        <center>
                                            <label class="ui header" style="color: #10312B; font-size: 130%;">
                                                {{$convocatoria->nombre}}
                                            </label>
                                        </center>
                                        <br/>
                                        <br/>
                                        <div class="column">
                                            <p class="ui header" style="color: #235B4E; text-align: center; font-size: 150%">
                                                <i style="color: #235B4E;" class="calendar icon"></i>Fechas
                                            </p>
                                        </div>
                                        <br/>
                                        <table align="center" style="width: 90%;">
                                            <thead>
                                                <th>&nbsp;&nbsp;Parte del proceso&nbsp;&nbsp;</th>
                                                <th>&nbsp;&nbsp;Fecha</th>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>&nbsp;Inicio de registro y validación&nbsp;&nbsp;</td>
                                                    <td>&nbsp;{{$convocatoria->fecha_registro_validacion_inicio}}&nbsp;&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;Fin de registro y validación&nbsp;&nbsp;</td>
                                                    <td>&nbsp;{{$convocatoria->fecha_registro_validacion_fin}}&nbsp;&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;Inicio de consulta&nbsp;&nbsp;</td>
                                                    <td>&nbsp;{{$convocatoria->fecha_consulta_inicio}}&nbsp;&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;Fin de consulta&nbsp;&nbsp;</td>
                                                    <td>&nbsp;{{$convocatoria->fecha_consulta_fin}}&nbsp;&nbsp;</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <br/>
                                        <div class="column">
                                            <p class="ui header" style="color: #235B4E; text-align: center; font-size: 150%">
                                                <i style="color: #235B4E;" class="bar graph icon"></i>Estadísticas
                                            </p>
                                        </div>
                                        <br/>
                                        <table style="width: 60%; margin: 0;">
                                            <thead>
                                                <th>&nbsp;</th>
                                                <th>&nbsp;Dato&nbsp;</th>
                                                <th>&nbsp;Cantidad&nbsp;&nbsp;</th>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style="background-color: transparent; width:40%;" rowspan="6">
                                                        <div style="background-color: transparent; text-align: center;">
                                                            <img style="width: 65%; height: 65%;" src="/assets/img/analisis.png">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;Usuarios de comité</td>
                                                    <td>{{count(\App\Models\Usuario::where('convocatoria_id', Auth::user()->convocatoria_id)->get())}}</td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;Padrón registrado</td>
                                                    <td>{{count(\App\Models\ConvocatoriaPadron::where('convocatoria_id', Auth::user()->convocatoria_id)->get())}}</td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;Votantes registrados</td>
                                                    <td>{{count(\App\Models\ConvocatoriaPadron::where('convocatoria_id', Auth::user()->convocatoria_id)->whereNotNull('fecha_registro')->get())}}</td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;Votantes validados</td>
                                                    <td>{{count(\App\Models\ConvocatoriaPadron::where('convocatoria_id', Auth::user()->convocatoria_id)->whereNotNull('fecha_validacion')->get())}}</td>
                                                </tr>
                                                <tr>
                                                    <td>&nbsp;Votantes rechazados</td>
                                                    <td>{{count(\App\Models\ConvocatoriaPadron::where('convocatoria_id', Auth::user()->convocatoria_id)->whereNotNull('fecha_rechazo')->get())}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <br/>
                                        <center>
                                            <p class="ui header" style="color: #235B4E; text-align: center; font-size: 150%">
                                                <i style="color: #235B4E;" class="linkify icon"></i>Enlace de registro
                                            </p>
                                            <span onclick="copiar({{$convocatoria->id}});"><i class="copy circle icon" style="font-size: x-large; cursor: pointer; color: #235B4E" onmouseover="this.style.color = '#691C32'" onmouseout="this.style.color = '#235B4E'"></i></span>
                                            <span onclick="copiar({{$convocatoria->id}});" style="font-size: larger; cursor: pointer; color: #235B4E; font-weight: bolder;">Copiar enlace</span>
                                            <br/>
                                            <br/>
                                            <div style="width: 100%; overflow: auto;">
                                                <span id="enlace{{$convocatoria->id}}" style="color: #10312B; font-size: 100%;">{{\App\Models\Ambiente::find(1)->nombre.'registro/'.Crypt::encrypt($convocatoria->id)}}</span>
                                            </div>
                                            <br/>
                                            <br/>
                                        </center>
                                    </div>
                                    <div class="title" style="font-size: medium">
                                        <label class="ui header" style="color: #12322B; font-size: 180%; font-weight: bolder; cursor: pointer;">
                                            &nbsp;Validar registros
                                        </label>
                                        <hr style="border:1px solid #12322B; width: 60%; margin: 0;"/>
                                        <br/>
                                    </div>
                                    <div class="content">
                                        <div class="field">
                                            <div style="text-align: center; background-color: transparent;" class="four wide column">
                                                <a href="/validacion/">
                                                    <img style="margin: auto; width: 20%; height: 20%;" src="/assets/img/identity.png" class="ui image">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="three wide column">
                    <div style="background-color: transparent;">
                        &nbsp;
                    </div>
                </div>
            </div>
        </div>
   </div>
@endsection

@section('scripts-page')
    <script type="text/javascript">
        $(document).ready(function() {
            $('.ui.dropdown').dropdown();
            $('.ui.accordion').accordion();
        });
        
        function copiar(id){
            var aux = document.createElement("input");
            aux.setAttribute("value", document.getElementById("enlace" + id).innerHTML);
            document.body.appendChild(aux);
            aux.select();
            document.execCommand("copy");
            document.body.removeChild(aux);
            alert("Vínculo copiado");
        }
    </script>
@endsection
