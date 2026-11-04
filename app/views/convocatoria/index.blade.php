@extends('layouts.ly_admin')

@section('title')
    <title>Convocatorias</title>
@endsection

@section('scripts')
    <style>
        tr:hover{
            color: #10312B;
        }
    </style>
    <script type="text/javascript">
        $('.menu .item').tab();
        function estatusConvocatoria(id){
            $('#convocatoria' + id).modal('show');
        }
    </script>
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
                        Listado de Convocatorias Activas
                    </h1>
                </div>
            </div>
        </div>
        <br/>
        <center>
            <div style="background-color: #235B4E; width: 20%; border-radius: 15px 15px 15px 15px;" onmouseover="this.style.backgroundColor = '#10312B';" onmouseout="this.style.backgroundColor = '#235B4E';">
                <p style="color: red; font-size: 130%">
                    <a id="creaUsr" href="/convocatoria/create/" style="color: white;">
                        <i id="icono" class="add icon"></i>Nueva convocatoria
                    </a>
                </p>
            </div>
        </center>
        @if(Session::get("message") != null)
            <center>
                <h3 class="ui header" style="color: white; background-color: #3DAD93; text-align: center;">
                    <i style="margin-top: 3px;" class="save layout icon"></i>{{Session::get("message")}}
                </h3>
            </center>
        @endif
        <p class="ui header" style="color: #10312B">
            <i class="find layout icon white"></i>@if(count($convocatorias) == 0) No se encontraron registros @endif @if(count($convocatorias) == 1) Se encontró un registro @endif @if(count($convocatorias) > 1) Se encontraron {{count($convocatorias)}} registros @endif
        </p>
        <div class="menu">
            <table align="center" style="width: 100%;">
                <thead>
                    <th>Nombre de Convocatoria&nbsp;&nbsp;</th>
                    <th>Sindicato&nbsp;&nbsp;</th>
                    <th>Estatus&nbsp;&nbsp;</th>
                    <th colspan="7">Acciones</th>
                </thead>
                <tbody>
                    @foreach($convocatorias as $convocatoria)
                        <tr>
                            <td>&nbsp;{{$convocatoria->nombre}}&nbsp;&nbsp;</td>
                            <td>&nbsp;{{$convocatoria->nombre_sindicato}}&nbsp;&nbsp;</td>
                            <td>&nbsp;{{ConvocatoriaEstatus::find($convocatoria->convocatoria_estatus_id)->nombre}}&nbsp;&nbsp;</td>
                            <td>
                                <a href="/convocatoria/edit/{{$convocatoria->id}}" title="Editar">
                                    <p style="font-size: 200%">
                                        <i class="edit icon" style="color: #235B4E" onmouseover="this.style.color = '#10312B'" onmouseout="this.style.color = '#235B4E'"></i>&nbsp;
                                    </p>
                                </a>
                            </td>
                            <td>
                                <a href="/convocatoria/asignarComite/{{$convocatoria->id}}" title="Gestión de Usuarios">
                                    <p style="font-size: 200%">
                                        <i class="user icon" style="color: #235B4E" onmouseover="this.style.color = '#10312B'" onmouseout="this.style.color = '#235B4E'"></i>&nbsp;
                                    </p>
                                </a>
                            </td>
                            <td onclick="estatusConvocatoria({{$convocatoria->id}});">
                                <a href="#" id="estatusConvocatoria" title="Estatus de Convocatoria">
                                    <p style="font-size: 200%">
                                        <i class="list ul icon" style="color: #235B4E" onmouseover="this.style.color = '#10312B'" onmouseout="this.style.color = '#235B4E'"></i>&nbsp;
                                    </p>
                                </a>
                                <div id="convocatoria{{$convocatoria->id}}" class="ui modal">
                                    <i class="close icon"></i>
                                    <div class="content">
                                        <table>
                                            <tr>
                                                <td style="background-color: white; width: 15%">
                                                    <img style="margin: auto; filter: {{$convocatoria->convocatoria_estatus_id == 1 ? 'brightness(100%)' : 'brightness(20%)'}};" src="/assets/img/proceso.png" class="ui image">
                                                </td>
                                                <td style="background-color: white; width: 5%">&nbsp;<font style="color: #10312B; font-weight: bold;">_____</font></td>
                                                <td style="background-color: white; width: 15%">
                                                    <img style="margin: auto; filter: {{$convocatoria->convocatoria_estatus_id == 2 ? 'brightness(100%)' : 'brightness(20%)'}};" src="/assets/img/validacion.png" class="ui image">
                                                </td>
                                                <td style="background-color: white;  width: 5%">&nbsp;<font style="color: #10312B; font-weight: bold;">_____</font></td>
                                                <td style="background-color: white; width: 15%">
                                                    <img style="margin: auto; filter: {{$convocatoria->convocatoria_estatus_id == 3 ? 'brightness(100%)' : 'brightness(20%)'}};" src="/assets/img/votacion.png" class="ui image">
                                                </td>
                                                <td style="background-color: white; width: 5%"><font style="color: #10312B; font-weight: bold;">_____</font></td>
                                                <td style="background-color: white; width: 15%">
                                                    <img style="margin: auto; filter: {{$convocatoria->convocatoria_estatus_id == 4 ? 'brightness(100%)' : 'brightness(20%)'}};" src="/assets/img/archivo.png" class="ui image">
                                                </td>
                                                <td style="background-color: white; width: 5%">&nbsp;<font style="color: #10312B; font-weight: bold;">_____&nbsp;</font></td>
                                                <td style="background-color: white; width: 15%">
                                                    <img style="margin: auto; filter: {{$convocatoria->convocatoria_estatus_id == 5 ? 'brightness(100%)' : 'brightness(20%)'}};" src="/assets/img/resultados_solo_verde.png" class="ui image">
                                                </td>
                                                <td style="background-color: white; width: 5%">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td style="background-color: white; width: 15%">
                                                    <br/>{{$convocatoria->convocatoria_estatus_id == 1 ? '<font style="color: #10312B; font-weight: bold;">Configuración de convocatoria</font>' : 'Configuración de convocatoria'}}
                                                </td>
                                                <td style="background-color: white; width: 5%">&nbsp;</td>
                                                <td style="background-color: white; width: 15%">
                                                    <br/>{{$convocatoria->convocatoria_estatus_id == 2 ? '<font style="color: #10312B; font-weight: bold;">Registro y Validación</font>' : 'Registro y Validación'}}
                                                </td>
                                                <td style="background-color: white;  width: 5%">&nbsp;</td>
                                                <td style="background-color: white; width: 15%">
                                                    <br/>{{$convocatoria->convocatoria_estatus_id == 3 ? '<font style="color: #10312B; font-weight: bold;">Configuración de boleta(s)</font>' : 'Configuración de boleta(s)'}}
                                                </td>
                                                <td style="background-color: white; width: 5%">&nbsp;</td>
                                                <td style="background-color: white; width: 15%">
                                                    <br/>{{$convocatoria->convocatoria_estatus_id == 4 ? '<font style="color: #10312B; font-weight: bold;">Consulta</font>' : 'Consulta'}}
                                                </td>
                                                <td style="background-color: white; width: 5%">&nbsp;</td>
                                                <td style="background-color: white; width: 15%">
                                                    <br/>{{$convocatoria->convocatoria_estatus_id == 5 ? '<font style="color: #10312B; font-weight: bold;">Resultados y Cierre</font>' : 'Resultados y Cierre'}}
                                                </td>
                                                <td style="background-color: white; width: 5%">&nbsp;</td>
                                            </tr>
                                        </table>
                                        <br/>
                                        <div style="width: 100%; overflow: auto;">
                                            <center>
                                                <label style="color: #10312B; font-size: large; font-weight: bold;">Enlace de registro a convocatoria</label>
                                                <br/>
                                                <hr style="border:1px solid #10312B; width: 80%;"/>
                                                <span onclick="copiar({{$convocatoria->id}});"><i class="copy circle icon" style="font-size: large; cursor: pointer; color: #235B4E" onmouseover="this.style.color = '#691C32'" onmouseout="this.style.color = '#235B4E'"></i></span>
                                                <span>Copiar enlace</span>
                                            </center>
                                            <span id="enlace{{$convocatoria->id}}" style="color: #10312B; font-size: 100%;">{{Ambiente::find(1)->nombre.'registro/'.Crypt::encrypt($convocatoria->id)}}</span>
                                            <br/>
                                            <br/>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <a href="/convocatoria/indexPadron/{{Crypt::encrypt($convocatoria->id)}}" title="Padrón de Convocatoria">
                                    <p style="font-size: 200%">
                                        <i class="file text icon" style="color: #235B4E" onmouseover="this.style.color = '#10312B'" onmouseout="this.style.color = '#235B4E'"></i>&nbsp;
                                    </p>
                                </a>
                            </td>
                            <td>
                                <a href="/convocatoria/terminarConvocatoria/{{Crypt::encrypt($convocatoria->id)}}" title="Terminar Convocatoria">
                                    <p style="font-size: 200%">
                                        <i class="remove circle icon" style="color: #235B4E" onmouseover="this.style.color = '#10312B'" onmouseout="this.style.color = '#235B4E'"></i>&nbsp;
                                    </p>
                                </a>
                            </td>
                            <td>
                                <a href="/convocatoria/bitacora/{{$convocatoria->id}}" title="Bitácora">
                                    <p style="font-size: 200%">
                                        <i class="help circle icon" style="color: #235B4E" onmouseover="this.style.color = '#10312B'" onmouseout="this.style.color = '#235B4E'"></i>&nbsp;
                                    </p>
                                </a>
                            </td>
                            <td>
                                <a href="/consulta/descargaActaFirmada/{{$convocatoria->id}}/cad/{{str_random(10)}}">
                                    <p style="font-size: 200%">
                                        <i class="download icon" style="color: #235B4E" onmouseover="this.style.color = '#10312B'" onmouseout="this.style.color = '#235B4E'" title="Descargar Acta"></i>&nbsp;
                                    </p>
                                </a>
                            </td>
                            @if($convocatoria->convocatoria_estatus_id == 5 && ($convocatoria->ruta_acta != null || $convocatoria->ruta_acta != ''))
                                <td>
                                    <a href="/consulta/cerrarConvocatoria/{{$convocatoria->id}}" onclick="return confirm('¿Desea cerrar la convocatoria?')">
                                        <p style="font-size: 200%">
                                            <i class="circle check icon" style="color: #235B4E" onmouseover="this.style.color = '#10312B'" onmouseout="this.style.color = '#235B4E'" title="Cerrar Convocatoria"></i>&nbsp;
                                        </p>
                                    </a>
                                </td>
                            @else
                                <td>
                                    &nbsp;
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts-page')
    <script type="text/javascript">
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