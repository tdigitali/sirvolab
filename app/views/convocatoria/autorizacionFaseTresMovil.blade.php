@extends('layouts.ly_adminMovil')

@section('title') Autorizacion @endsection

@section('scripts')
    <script type="text/javascript">
        $('.menu .item').tab();
        function aprobaciones(){
            $('#aprobacion1').modal('show');
        }
    </script>
@endsection

@section('content')
    <div class="ui segment" id="content-page" style="background-color: transparent;">
        <div class="ui grid">
            <div class="sixteen wide column">
                <div style="text-align: center; width: 100%; margin: 0 auto;">
                    <h1 class="ui header" style="color: #10312B; font-size: 220%;">
                        Proceso de Autorizaciones de Convocatoria
                    </h1>
                </div>
            </div>
            <br/>
            <div style="text-align: center; width: 100%; margin: 0 auto;">
                <h1 class="ui header" style="color: #235B4E; font-size: 120%;">
                    {{$convocatoria->nombre}}
                </h1>
                <br/>
                @if($mensajeDeValidacion != null)
                    <h3 class="ui header" style="color: white; background-color: #3DAD93;">
                        <i style="margin-top: 3px;" class="remove circle layout icon"></i>{{$mensajeDeValidacion}}
                    </h3>
                    <br/>
                @endif
                @if($mensajeExito != null)
                    <h3 class="ui header" style="color: white; background-color: #3DAD93;">
                        <i style="margin-top: 3px;" class="save layout icon"></i>{{$mensajeExito}}
                    </h3>
                    <br/>
                @endif
            </div>
            <br/>
            <div style="text-align: center; background-color: transparent;" class="eight wide column">
                <img style="margin: auto; filter: brightness(20%);" src="/assets/img/validacion_nvf.png" class="ui image">
            </div>
            <div style="text-align: center; background-color: transparent;" class="eight wide column">
                <div style="background-color: transparent; text-align: center;">
                    <label class="ui header" style="color: #10312B; font-size: 150%; font-weight: bolder; cursor: pointer;">
                        <br/>
                        Etapa de registro
                        <br/>
                        y validación
                    </label>
                </div>
            </div>
            <div style="text-align: center; background-color: transparent;" class="eight wide column">
                <br/>
                <i style="color: #3DAD93; font-size: 300%" class="arrow down layout icon"></i>
            </div>
            <div style="text-align: center; background-color: transparent;" class="eight wide column">
                &nbsp;
            </div>
            <div style="text-align: center; background-color: transparent;" class="eight wide column">
                <img style="margin: auto; filter: brightness(20%);" src="/assets/img/aprobado.png" class="ui image">
            </div>
            <div style="text-align: center; background-color: transparent;" class="eight wide column">
                <div style="background-color: transparent; text-align: center;">
                    <label class="ui header" style="color: #10312B; font-size: 150%; cursor: pointer;">
                        <br/>
                        Finalizar etapa de
                        <br/>
                        registro y validación
                    </label>
                </div>
            </div>
            <div style="text-align: center; background-color: transparent;" class="eight wide column">
                <br/>
                <i style="color: #3DAD93; font-size: 300%" class="arrow down layout icon"></i>
            </div>
            <div style="text-align: center; background-color: transparent;" class="eight wide column">
                &nbsp;
            </div>
            <div onclick="aprobaciones();" style="text-align: center; background-color: transparent;" class="eight wide column">
                <img style="margin: auto;" src="/assets/img/voto_linea.png" class="ui image">
            </div>
            <div style="text-align: center; background-color: transparent;" class="eight wide column">
                <div style="background-color: transparent; text-align: center;">
                    <label class="ui header" style="color: #10312B; font-size: 150%; cursor: pointer;">
                        <br/>
                        Iniciar etapa 
                        <br/>
                        de Consulta
                    </label>
                </div>
            </div>
            <div id="aprobacion1" class="ui modal">
                <i class="close icon"></i>
                <div class="content">
                    <center>
                        <label style="color: #235B4E; font-size: large; font-weight: bold;">Token de seguridad<span style="color: #691C32;">*</span></label>
                        <br/>
                        <label style="color: #235B4E; font-size: small">Ingrese el token que le llegó por correo</label>
                        <br/>
                        <br/>
                        {{Form::text('token1', null, ['id' => 'token1', 'style' => 'color: grey', 'maxlength' => '10'])}}<br/>
                        <br/>
                        <hr style="border:1px solid #10312B; width: 80%;"/>
                        <p style="color: #691C32"><b>* El token tiene vigencia de una hora</b></p>
                    </center>
                    <br/>
                    <div style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;">
                        <br/>
                        <a href="" title="Autorizar Fase" onclick="enviaToken(this, 1);return confirm('¿Desea autorizar el cierre de registro y validación?')">
                            <img id="regresar" style="margin: auto; width: 45%; height: 45%;" src="/assets/img/enviar.png" class="ui image">
                            <div style="background-color: transparent; text-align: center;">
                                <hr id="regresarHR" style="border:1px solid #10312B; width: 50%;"/>
                                <label id="regresarL" class="ui header" style="color: #10312B; font-size: 100%; font-weight: bolder; cursor: pointer;">
                                    Enviar
                                </label>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div style="text-align: center; background-color: transparent;" class="eight wide column">
                <br/>
                <i style="color: #3DAD93; font-size: 300%" class="arrow down layout icon"></i>
            </div>
            <div style="text-align: center; background-color: transparent;" class="eight wide column">
                &nbsp;
            </div>
            <div style="text-align: center; background-color: transparent;" class="eight wide column">
                <img style="margin: auto; filter: brightness(20%);" src="/assets/img/resultados_solo_verde.png" class="ui image">
            </div>
            <div style="text-align: center; background-color: transparent;" class="eight wide column">
                <div style="background-color: transparent; text-align: center;">
                    <label class="ui header" style="color: #10312B; font-size: 150%; cursor: pointer;">
                        <br/>
                        <br/>
                        Resultados
                    </label>
                </div>
            </div>
        </div>
    </div>
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
        
        function enviaToken(obj, id){
            var texto = document.getElementById('token' + id).value;
            if(texto == null || texto == '')
                texto  = '-';

            obj.href = '/convocatoria/autorizacion/autorizarFaseTres/' + texto;
        }
    </script>
@endsection