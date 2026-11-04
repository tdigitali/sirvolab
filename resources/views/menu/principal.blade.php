@extends('layouts.ly_admin')

@section('content')
    <div class="ui form">
        <div class="ui segment" id="content-page" style="background-color: transparent;">
            <div style="text-align: center; width: 61%; margin: 0 auto;">
                <label class="ui header" style="color: #10312B; font-size: 700%;">
                    SIRVOLAB
                </label>
                <br/>
                <label class="ui header" style="color: #10312B; font-size: 250%;">
                    Sistema Remoto de Votación Laboral
                </label>
            </div>
            <br/>
            <div class="ui grid">
                <div class="three wide column">
                    <div style="background-color: transparent; margin: auto; display: block; width:170px; height:170px; border-radius:170px; border:5px solid #10312B; cursor: pointer;" onmouseover="document.getElementById('nuevaC').src = '/assets/img/calendario_blanco.png'; document.getElementById('nuevaCHR').style.border = '1px solid white'; document.getElementById('nuevaCL').style.color = 'white'; this.style.backgroundColor = '#10312B';" onmouseout="document.getElementById('nuevaC').src = '/assets/img/calendario.png'; document.getElementById('nuevaCHR').style.border = '1px solid #10312B'; document.getElementById('nuevaCL').style.color = '#10312B'; this.style.backgroundColor = 'transparent';">
                        <br/>
                        <a href="/convocatoria/create/">
                            <img id="nuevaC" style="margin: auto; width: 45%; height: 45%;" src="/assets/img/calendario.png" class="ui image">
                            <div style="background-color: transparent; text-align: center;">
                                <hr id="nuevaCHR" style="border:1px solid #10312B; width: 50%;"/>
                                <label id="nuevaCL" class="ui header" style="color: #10312B; font-size: 100%; font-weight: bolder; cursor: pointer;">
                                    Nueva<br/>convocatoria
                                </label>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="ten wide column">
                    <div class="ui styled">
                        <div class="content active">
                            <div class="ui styled accordion fluid" style="background-color: transparent;">
                                <br/>
                                <center>
                                    <div class="title active" style="font-size: medium">
                                        <label class="ui header" style="color: #12322B; font-size: 120%; font-weight: bolder; cursor: pointer;">
                                            &nbsp;Resumen de convocatorias activas
                                        </label>
                                        <hr style="border:1px solid #12322B; width: 80%; margin: 0;"/>
                                        <br/>
                                    </div>
                                    <div class="content active">
                                        <table align="center" style="width: 90%;">
                                            <thead>
                                                <th>&nbsp;&nbsp;Convocatoria&nbsp;&nbsp;</th>
                                                <th>&nbsp;&nbsp;Estatus</th>
                                                <th colspan="2">&nbsp;&nbsp;Acciones</th>
                                            </thead>
                                            <tbody>
                                                @foreach(\App\Models\Convocatoria::where('convocatoria_estatus_id', '!=', 6)->get() as $convocatoria)
                                                    <tr>
                                                        <td>&nbsp;{{$convocatoria->nombre}}&nbsp;&nbsp;</td>
                                                        <td>&nbsp;{{\App\Models\ConvocatoriaEstatus::find($convocatoria->convocatoria_estatus_id)->nombre}}&nbsp;&nbsp;</td>
                                                        <td>
                                                            <a href="/convocatoria/asignarComite/{{$convocatoria->id}}" title="Gestión de Usuarios">
                                                                <p style="font-size: 200%">
                                                                    <i class="user icon" style="color: #235B4E" onmouseover="this.style.color = '#10312B'" onmouseout="this.style.color = '#235B4E'"></i>&nbsp;
                                                                </p>
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="/convocatoria/indexPadron/{{Crypt::encrypt($convocatoria->id)}}" title="Padrón de Convocatoria">
                                                                <p style="font-size: 200%">
                                                                    <i class="file text icon" style="color: #235B4E" onmouseover="this.style.color = '#10312B'" onmouseout="this.style.color = '#235B4E'"></i>&nbsp;
                                                                </p>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="title" style="font-size: medium">
                                        <label class="ui header" style="color: #12322B; font-size: 120%; font-weight: bolder; cursor: pointer;">
                                            &nbsp;Acciones
                                        </label>
                                        <hr style="border:1px solid #12322B; width: 80%; margin: 0;"/>
                                        <br/>
                                    </div>
                                    <div class="content">
                                        <div class="three fields">
                                            <div class="field">
                                                <a href="/convocatoria/index/">
                                                    <img style="margin: auto; width: 65%; height: 65%;" src="/assets/img/actualizacion.png" class="ui image">
                                                    <div style="background-color: transparent; text-align: center;">
                                                        <hr style="border:1px solid #10312B; width: 50%;"/>
                                                        <label class="ui header" style="color: #10312B; font-size: 150%; font-weight: bolder; cursor: pointer;">
                                                            Convocatorias
                                                            <br/>
                                                            Activas
                                                        </label>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="field">
                                                <a href="/convocatoria/indexCerradas/">
                                                    <img style="margin: auto; width: 65%; height: 65%;" src="/assets/img/historia.png" class="ui image">
                                                    <div style="background-color: transparent; text-align: center;">
                                                        <hr id="proyectoHR" style="border:1px solid #10312B; width: 50%;"/>
                                                        <label class="ui header" style="color: #10312B; font-size: 150%; font-weight: bolder; cursor: pointer;">
                                                            Historial
                                                        </label>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="field">
                                                <div style="text-align: center; background-color: transparent;" class="four wide column">
                                                    <a href="/usuario/index/">
                                                        <img id="proyecto" style="margin: auto; width: 65%; height: 65%;" src="/assets/img/perfiles_usuario.png" class="ui image">
                                                        <div style="background-color: transparent; text-align: center;">
                                                            <hr id="proyectoHR" style="border:1px solid #10312B; width: 50%;"/>
                                                            <label class="ui header" style="color: #10312B; font-size: 150%; font-weight: bolder; cursor: pointer;">
                                                                Usuarios
                                                            </label>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div style="text-align: center; background-color: transparent;" class="four wide column">                                            
                                                    <a href="/convocatoria/create/">
                                                        <img id="nuevaC" style="margin: auto; width: 65%; height: 65%;" src="/assets/img/calendario.png" class="ui image">
                                                        <div style="background-color: transparent; text-align: center;">
                                                            <hr id="nuevaCHR" style="border:1px solid #10312B; width: 50%;"/>
                                                            <label class="ui header" style="color: #10312B; font-size: 150%; font-weight: bolder; cursor: pointer;">
                                                                Nueva<br/>convocatoria
                                                            </label>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="three wide column">
                    <div style="background-color: transparent; margin: auto; display: block; width:170px; height:170px; border-radius:170px; border:5px solid #10312B; cursor: pointer;" onmouseover="document.getElementById('convocatorias').src = '/assets/img/actualizacion_blanco.png'; document.getElementById('convocatoriasHR').style.border = '1px solid white'; document.getElementById('convocatoriasL').style.color = 'white'; this.style.backgroundColor = '#10312B';" onmouseout="document.getElementById('convocatorias').src = '/assets/img/actualizacion.png'; document.getElementById('convocatoriasHR').style.border = '1px solid #10312B'; document.getElementById('convocatoriasL').style.color = '#10312B'; this.style.backgroundColor = 'transparent';">
                        <br/>
                        <a href="/convocatoria/index/">
                            <img id="convocatorias" style="margin: auto; width: 45%; height: 45%;" src="/assets/img/actualizacion.png" class="ui image">
                            <div style="background-color: transparent; text-align: center;">
                                <hr id="convocatoriasHR" style="border:1px solid #10312B; width: 50%;"/>
                                <label id="convocatoriasL" class="ui header" style="color: #10312B; font-size: 100%; font-weight: bolder; cursor: pointer;">
                                    Convocatorias<br/>activas
                                </label>
                            </div>
                        </a>
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
    </script>
@endsection
