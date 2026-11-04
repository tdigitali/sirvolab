@extends('layouts.ly_admin')

@section('title')
    <title>Validación</title>
@endsection

@section('scripts')
    <script type="text/javascript">
        $('.menu .item').tab();
        function visualizarImagen(id){
            $('#imagen' + id).modal('show');
        }
    </script>
@endsection

@section('content')
    {{Form::open(array('action' => array('RegistroValidacionController@aprobacion', Crypt::encrypt($convocatoriaPadron->id)) ,'class' => 'ui form', 'id' => "register_form"))}}
        <div class="ui segment" id="content-page" style="background-color: transparent;">
            <div class="sixteen wide column">
                <div style="text-align: center; width: 100%; margin: 0 auto;">
                    <h1 class="ui header" style="color: #10312B; font-size: 500%;">
                        Validación de Votante
                    </h1>
                    <h1 class="ui header" style="color: #235B4E; font-size: 130%;">
                        {{Convocatoria::find($convocatoriaPadron->convocatoria_id)->nombre}}
                    </h1>
                </div>
            </div>
            <br/>
            <div class="sixteen wide column">
                @if(Session::get("message") != null)
                    <div style="text-align: center;">
                        <h3 class="ui header" style="color: white; background-color: #3DAD93;">
                            <i style="margin-top: 3px;" class="remove circle layout icon"></i>{{Session::get("message")}}
                        </h3>
                    </div>
                    <br/>
                @endif
            </div>
            <div class="ui grid">
                <div class="three wide column">                
                    <div style="text-align:center;">
                        <div class="saveForm" style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;" onmouseover="document.getElementById('guardar').src = '/assets/img/check_blanco.png'; document.getElementById('guardarHR').style.border = '1px solid white'; document.getElementById('guardarL').style.color = 'white'; this.style.backgroundColor = '#10312B';" onmouseout="document.getElementById('guardar').src = '/assets/img/check_verde.png'; document.getElementById('guardarHR').style.border = '1px solid #10312B'; document.getElementById('guardarL').style.color = '#10312B'; this.style.backgroundColor = 'transparent';">
                            <br/>
                            <img id="guardar" style="margin: auto; width: 45%; height: 45%;" src="/assets/img/check_verde.png" class="ui image">
                            <div style="background-color: transparent; text-align: center;">
                                <hr id="guardarHR" style="border:1px solid #10312B; width: 50%;"/>
                                <label id="guardarL" class="ui header" style="color: #10312B; font-size: 100%; font-weight: bolder; cursor: pointer;">
                                    Aprobar
                                </label>
                            </div>
                        </div>
                        <br/>
                        <div style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;" onmouseover="document.getElementById('rechazar').src = '/assets/img/cross_blanco.png'; document.getElementById('rechazarHR').style.border = '1px solid white'; document.getElementById('rechazarL').style.color = 'white'; this.style.backgroundColor = '#10312B';" onmouseout="document.getElementById('rechazar').src = '/assets/img/cross_verde.png'; document.getElementById('rechazarHR').style.border = '1px solid #10312B'; document.getElementById('rechazarL').style.color = '#10312B'; this.style.backgroundColor = 'transparent';">
                            <br/>
                            <a href="" id="{{$convocatoriaPadron->id}}" onclick="enviaRechazo(this);return confirm('¿Desea rechazar el registro del votante?')" title="Rechazar registro">
                                <img id="rechazar" style="margin: auto; width: 45%; height: 45%;" src="/assets/img/cross_verde.png" class="ui image">
                                <div style="background-color: transparent; text-align: center;">
                                    <hr id="rechazarHR" style="border:1px solid #10312B; width: 50%;"/>
                                    <label id="rechazarL" class="ui header" style="color: #10312B; font-size: 100%; font-weight: bolder; cursor: pointer;">
                                        Rechazar
                                    </label>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="eleven wide column">
                    <div class="ui styled">
                        <div class="content active">
                            <br/>
                            <div class="content active">
                                <div class="three fields">
                                    <div class="field">
                                        <label style="color: #10312B; font-size: large">CURP</label>
                                        <label style="color: #10312B; font-size: small">{{$convocatoriaPadron->curp}}</label>
                                    </div>
                                    <div class="field">
                                        <label style="color: #10312B; font-size: large">Nombre completo</label>
                                        <label style="color: #10312B; font-size: small">{{$convocatoriaPadron->nombre_completo}}</label>
                                    </div>
                                    <div class="field">
                                        <label style="color: #10312B; font-size: large">Correo electrónico</label>
                                        <label style="color: #10312B; font-size: small">{{$convocatoriaPadron->correo_electronico}}</label>
                                    </div>
                                </div>
                                <br/>
                                <hr style="border:1px solid #10312B; width: 100%;"/>
                                <br/>
                                <div class="three fields">
                                    <div class="field">
                                        <label style="color: #10312B; font-size: large">Centro de trabajo</label>
                                        <label style="color: #10312B; font-size: small">{{ConvocatoriaCentroTrabajo::find($convocatoriaPadron->centro_trabajo_id)->nombre}}</label>
                                    </div>
                                    <div class="field">
                                        <label style="color: #10312B; font-size: large">Entidad Federativa</label>
                                        <label style="color: #10312B; font-size: small">{{EntidadFederativa::find($convocatoriaPadron->entidad_federativa_id)->nombre}}</label>
                                    </div>
                                    <div class="field">
                                        <label style="color: #10312B; font-size: large">Fecha de registro</label>
                                        <label style="color: #10312B; font-size: small">{{$convocatoriaPadron->fecha_registro}}</label>
                                    </div>
                                </div>
                                <br/>
                                <div class="three fields">
                                    <div onclick="visualizarImagen(1);" class="field" style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;" onmouseover="document.getElementById('imgSubir1').src = '/assets/img/jpg.png'; document.getElementById('imgSubir1HR').style.border = '1px solid white'; document.getElementById('imgSubir1L').style.color = 'white'; this.style.backgroundColor = '#10312B';" onmouseout="document.getElementById('imgSubir1').src = '/assets/img/imagen_verdeClaro.png'; document.getElementById('imgSubir1HR').style.border = '1px solid #10312B'; document.getElementById('imgSubir1L').style.color = '#10312B'; this.style.backgroundColor = 'transparent';">
                                        <label for="fileImagen1">
                                            <br/>
                                            <img id="imgSubir1" style="margin: auto; width: 45%; height: 45%;" src="/assets/img/imagen_verdeClaro.png" class="ui image">
                                            <div style="background-color: transparent; text-align: center;">
                                                <hr id="imgSubir1HR" style="border:1px solid #10312B; width: 50%;"/>
                                                <b id="imgSubir1L" class="ui header" style="color: #10312B; font-size: 100%; font-weight: bolder; cursor: pointer;">
                                                    Frente
                                                </b>
                                            </div>
                                        </label>
                                    </div>
                                    <div id="imagen1" class="ui modal">
                                        <i class="close icon"></i>
                                        <div class="content">
                                            <img id="ine_frente" style="margin: auto; width: 100%; height: 100%;" src="{{$convocatoriaPadron->ruta_ine_frente}}" class="ui image">
                                        </div>
                                    </div>
                                    <div onclick="visualizarImagen(2);" class="field" style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;" onmouseover="document.getElementById('imgSubir2').src = '/assets/img/jpg.png'; document.getElementById('imgSubir2HR').style.border = '1px solid white'; document.getElementById('imgSubir2L').style.color = 'white'; this.style.backgroundColor = '#10312B';" onmouseout="document.getElementById('imgSubir2').src = '/assets/img/imagen_verdeClaro.png'; document.getElementById('imgSubir2HR').style.border = '1px solid #10312B'; document.getElementById('imgSubir2L').style.color = '#10312B'; this.style.backgroundColor = 'transparent';">
                                        <label for="fileImagen2">
                                            <br/>
                                            <img id="imgSubir2" style="margin: auto; width: 45%; height: 45%;" src="/assets/img/imagen_verdeClaro.png" class="ui image">
                                            <div style="background-color: transparent; text-align: center;">
                                                <hr id="imgSubir2HR" style="border:1px solid #10312B; width: 50%;"/>
                                                <b id="imgSubir2L" class="ui header" style="color: #10312B; font-size: 100%; font-weight: bolder; cursor: pointer;">
                                                    Revés
                                                </b>
                                            </div>
                                        </label>
                                    </div>
                                    <div id="imagen2" class="ui modal">
                                        <i class="close icon"></i>
                                        <div class="content">
                                            <img id="ine_frente" style="margin: auto; width: 100%; height: 100%;" src="{{$convocatoriaPadron->ruta_ine_reverso}}" class="ui image">
                                        </div>
                                    </div>
                                    <div onclick="visualizarImagen(3);" class="field" style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;" onmouseover="document.getElementById('imgSubir3').src = '/assets/img/jpg.png'; document.getElementById('imgSubir3HR').style.border = '1px solid white'; document.getElementById('imgSubir3L').style.color = 'white'; this.style.backgroundColor = '#10312B';" onmouseout="document.getElementById('imgSubir3').src = '/assets/img/imagen_verdeClaro.png'; document.getElementById('imgSubir3HR').style.border = '1px solid #10312B'; document.getElementById('imgSubir3L').style.color = '#10312B'; this.style.backgroundColor = 'transparent';">
                                        <label for="fileImagen3">
                                            <br/>
                                            <img id="imgSubir3" style="margin: auto; width: 45%; height: 45%;" src="/assets/img/imagen_verdeClaro.png" class="ui image">
                                            <div style="background-color: transparent; text-align: center;">
                                                <hr id="imgSubir3HR" style="border:1px solid #10312B; width: 50%;"/>
                                                <b id="imgSubir3L" class="ui header" style="color: #10312B; font-size: 100%; font-weight: bolder; cursor: pointer;">
                                                    Selfie
                                                </b>
                                            </div>
                                        </label>
                                    </div>
                                    <div id="imagen3" class="ui modal">
                                        <i class="close icon"></i>
                                        <div class="content">
                                            <img id="ine_frente" style="margin: auto; width: 100%; height: 100%;" src="{{$convocatoriaPadron->ruta_selfie_con_ine}}" class="ui image">
                                        </div>
                                    </div>
                                </div>
                                <br/>
                                <hr style="border:1px solid #10312B; width: 100%;"/>
                                <br/>
                                <div class="two fields">
                                    <div class="field">
                                        <label style="color: #235B4E; font-size: large">Información correcta&nbsp;{{Form::checkbox('informacion_valida', null, false,['class' => 'ui checkbox'])}}</label>
                                        <label style="color: #235B4E; font-size: small">Marque la casilla para indicar que aprueba el registro</label>
                                    </div>
                                </div>
                                <div class="field">
                                    <label style="color: #235B4E; font-size: large">Motivo del rechazo</label>
                                    <label style="color: #235B4E; font-size: small">Seleccione el rol del usuario</label>
                                    <div class="ui fluid selection dropdown">
                                        {{Form::hidden('motivo_rechazo_id', 8, ['id' => 'motivo_rechazo_id'])}}
                                        <i style="color: #235B4E;" class="list layout icon"></i>
                                        <div class="default text" style="color: #235B4E;">Por favor, selecciona una opción</div>
                                        <div class="menu">
                                            @foreach(MotivoRechazo::all() as $motivoRechazo)
                                                <div class="item" style="color: #235B4E" data-value="{{ $motivoRechazo->id }}">{{ $motivoRechazo->nombre }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <hr style="border:1px solid #10312B; width: 100%;"/>
                                <br/>
                            </div>
                        </div>
                    </div>
                    <center>
                        <p style="color: #691C32"><b>* Campos Obligatorios</b></p>
                    </center>
                </div>
                <div class="two wide column">
                    &nbsp;
                </div>
            </div>
        </div>
    {{ Form::close() }}   
@endsection

@section('scripts-page')
    <script type="text/javascript">
        $(document).ready(function() {
            var formFunctions = {
                saveForm: function(){
                    $("#register_form").submit();
                },
            };
            $(".saveForm").on("click",formFunctions.saveForm);
        });
        
        function enviaRechazo(obj){
            var motivo = document.getElementById('motivo_rechazo_id').value;
            var id = obj.id;
            obj.href = '/validacion/rechazo/' + id + '/mot/' + motivo;
        }
    </script>
@endsection
