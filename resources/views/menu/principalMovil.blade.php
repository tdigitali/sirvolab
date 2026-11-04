@extends('layouts.ly_adminMovil')

@section('content')
    <div class="ui form">
        <div class="ui segment" id="content-page" style="background-color: transparent;">
            <div style="text-align: center; width: 100%; margin: 0 auto;">
                <label class="ui header" style="color: #10312B; font-size: 300%;">
                    SIRVOLAB
                </label>
                <br/>
                <label class="ui header" style="color: #10312B; font-size: 150%;">
                    Sistema Remoto de Votación Laboral
                </label>
            </div>
            <br/>
            <div class="ui grid">
                <div class="sixteen wide column">
                    <div class="ui styled">
                        <div class="content active">
                            <div class="ui styled accordion fluid" style="background-color: transparent;">
                                <br/>
                                <center>
                                    <div class="title active" style="font-size: medium">
                                        <label class="ui header" style="color: #12322B; font-size: 100%; font-weight: bolder; cursor: pointer;">
                                            &nbsp;Resumen de convocatorias activas
                                        </label>
                                        <hr style="border:1px solid #12322B; width: 100%; margin: 0;"/>
                                        <br/>
                                    </div>
                                    <div class="content active">
                                        <table align="center" style="width: 100%; font-size: 80%;">
                                            <thead>
                                                <th>&nbsp;&nbsp;Convocatoria&nbsp;&nbsp;</th>
                                                <th colspan="2">&nbsp;&nbsp;Acciones&nbsp;&nbsp;</th>
                                            </thead>
                                            <tbody>
                                                @foreach(Convocatoria::where('convocatoria_estatus_id', '!=', 6)->get() as $convocatoria)
                                                    <tr>
                                                        <td>&nbsp;{{$convocatoria->nombre}}&nbsp;&nbsp;</td>
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
                                        <label class="ui header" style="color: #12322B; font-size: 100%; font-weight: bolder; cursor: pointer;">
                                            &nbsp;Acciones
                                        </label>
                                        <hr style="border:1px solid #12322B; width: 80%; margin: 0;"/>
                                        <br/>
                                    </div>
                                    <div class="content">
                                        
                                        
                                        
                                        <table>
                                            <tr>
                                                <td style="background-color: transparent;">
                                                    <div style="text-align: center;">
                                                        <a href="/convocatoria/index/" style="color: #10312B; font-weight: bolder;">
                                                            <img style="margin: auto; width: 50%; height: 50%;" src="/assets/img/actualizacion.png">
                                                            <br>
                                                            <hr style="border:1px solid #12322B; width: 92%;"/>
                                                            Convocatorias Activas
                                                        </a>
                                                        <br>
                                                        <br>
                                                    </div>
                                                </td>
                                                <td style="background-color: transparent;">
                                                    <div style="text-align: center;">
                                                        <a href="/convocatoria/indexCerradas/" style="color: #10312B; font-weight: bolder;">
                                                            <img style="margin: auto; width: 52%; height: 52%" src="/assets/img/historia.png">
                                                            <br>
                                                            <hr style="border:1px solid #12322B; width: 80%;"/>
                                                            Historial
                                                        </a>
                                                        <br>
                                                        <br>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="background-color: transparent;">
                                                    <div style="text-align: center;">
                                                        <a href="/usuario/index/" style="color: #10312B; font-weight: bolder;">
                                                            <img style="margin: auto; width: 50%; height: 50%;" src="/assets/img/perfiles_usuario.png">
                                                            <br/>
                                                            <hr style="border:1px solid #12322B; width: 92%;"/>
                                                            Usuarios
                                                        </a>
                                                        <br/>
                                                        <br/>
                                                    </div>
                                                </td>
                                                <td style="background-color: transparent;">
                                                    <div style="text-align: center;">
                                                        <a href="/convocatoria/create/" style="color: #10312B; font-weight: bolder;">
                                                            <img style="margin: auto; width: 52%; height: 52%;" src="/assets/img/calendario.png">
                                                            <br/>
                                                            <hr style="border:1px solid #12322B; width: 80%;"/>
                                                            Nueva convocatoria
                                                        </a>
                                                        <br/>
                                                        <br/>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </center>
                            </div>
                        </div>
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
