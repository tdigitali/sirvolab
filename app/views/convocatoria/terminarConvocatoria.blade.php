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
    {{Form::open(array('action' => array('ConvocatoriaController@terminarConvocatoriaUpdate',Crypt::encrypt($register->id)) ,'class' => 'ui form', 'id' => "register_form"))}}
        <div class="ui segment" id="content-page" style="background-color: transparent;">
            <br/>
            <br/>
            <div class="sixteen wide column">
                <div style="text-align: center; width: 100%; margin: 0 auto;">
                    <h1 class="ui header" style="color: #10312B; font-size: 500%;">
                        <i class="warning circle icon" style="color: #235B4E"></i>Lee con cuidado lo siguiente:
                    </h1>
                </div>
                @if(Session::get("message") != null)
                    <h3 class="ui header" style="color: white; background-color: #3DAD93; text-align: center; font-size: 150%">
                        <i style="margin-top: 3px;" class="remove circle layout icon"></i>{{Session::get("message")}}
                    </h3>
                @endif
            </div>
            <div class="ui grid">
                <div class="two wide column">
                    <br/>
                    <div style="text-align:center;">
                        <div style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;" onmouseover="document.getElementById('regresar').src = '/assets/img/regresar_blanco.png'; document.getElementById('regresarHR').style.border = '1px solid white'; document.getElementById('regresarL').style.color = 'white'; this.style.backgroundColor = '#10312B';" onmouseout="document.getElementById('regresar').src = '/assets/img/regresar_verde.png'; document.getElementById('regresarHR').style.border = '1px solid #10312B'; document.getElementById('regresarL').style.color = '#10312B'; this.style.backgroundColor = 'transparent';">
                            <br/>
                            <a href="/convocatoria/index/">
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
                </div>
                <div class="thirteen wide column">
                    <div class="ui styled">
                        <div class="content active">
                            <br/>
                            <br/>
                            <div class="column">
                                <p class="ui header" style="color: #235B4E; text-align: justify; font-size: 200%">
                                    Si se necesita cerrar la convocatoria de manera anticipada, debe de tomar en cuenta que esta cerrará por completo y deshabilitará todos los usuarios que se hayan creado y que NO se podrá continuar el proceso de la misma.
                                </p>
                                <hr style="border:1px solid #235B4E; width: 100%; margin: 0;"/>
                                <p class="ui header" style="color: #235B4E; text-align: justify; font-size: 200%">
                                    La convocatoria que deseas terminar es: 
                                </p>
                            </div>
                            <br/>
                            <div class="content active">
                                <div class="one fields">
                                    <div class="field">
                                        <label style="color: #10312B; font-size: x-large">{{$register->nombre_sindicato}}</label>
                                    </div>
                                </div>
                                <div class="one fields">
                                    <div class="field">
                                        <label style="color: #10312B; font-size: x-large">{{$register->nombre}}</label>
                                    </div>
                                </div>
                                <br/>
                                <hr style="border:1px solid #10312B; width: 100%;"/>
                                <br/>
                            </div>
                            <div class="field">
                                <label style="color: #10312B; font-size: large">Describa la razón de terminar anticipadamente<span style="color: #611232;">*</span></label>
                                <label style="color: #10312B; font-size: small">Máximo 200 caracteres</label>
                                {{ Form::textArea('razon_termino', null, ['style' => 'color: grey', 'maxlength' => '200', 'rows' => '4']) }}<br/>
                            </div>
                            <br/>
                            <div class="saveForm" style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;" onmouseover="document.getElementById('guardar').src = '/assets/img/guardar_blanco.png'; document.getElementById('guardarHR').style.border = '1px solid white'; document.getElementById('guardarL').style.color = 'white'; this.style.backgroundColor = '#10312B';" onmouseout="document.getElementById('guardar').src = '/assets/img/guardar.png'; document.getElementById('guardarHR').style.border = '1px solid #10312B'; document.getElementById('guardarL').style.color = '#10312B'; this.style.backgroundColor = 'transparent';">
                                <br/>
                                <img id="guardar" style="margin: auto; width: 45%; height: 45%;" src="/assets/img/guardar.png" class="ui image">
                                <div style="background-color: transparent; text-align: center;">
                                    <hr id="guardarHR" style="border:1px solid #10312B; width: 50%;"/>
                                    <label id="guardarL" class="ui header" style="color: #10312B; font-size: 100%; font-weight: bolder; cursor: pointer;">
                                        Terminar
                                    </label>
                                </div>
                            </div>
                            <br/>
                        </div>
                    </div>
                    <center>
                        <p style="color: #691C32"><b>* Campos Obligatorios</b></p>
                    </center>
                </div>
                <div class="one wide column">
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
            $('.datepicker').pickadate({
                monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
                today: 'Hoy',
                clear: 'Limpiar',
                close: 'Cerrar',
                selectMonths: true,
                selectYears: 100,
                format: 'yyyy-mm-dd',
                max: new Date()
            });
            $('.datepicker-post').pickadate({
                monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
                today: 'Hoy',
                clear: 'Limpiar',
                close: 'Cerrar',
                selectMonths: true,
                selectYears: 100,
                format: 'yyyy-mm-dd'
            });
        });
    </script>
@endsection
