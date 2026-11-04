@extends('layouts.ly_registro')

@section('content')
    {{Form::open(array('action' => array('RegistroValidacionController@validaCURP', Crypt::encrypt($convocatoria->id)) ,'class' => 'ui form', 'id' => "register_form"))}}
        <div class="ui segment" id="content-page" style="background-color: transparent; border: none;">
            <div class="sixteen wide column">
                <div style="text-align: center; width: 100%; margin: 0 auto;">
                    <h1 class="ui header" style="color: #10312B; font-size: 250%;">
                        Validación de Acceso a Registro
                    </h1>
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
            @if(Session::get("message") != null)
                <div style="text-align: center;">
                    <h3 class="ui header" style="color: white; background-color: #3DAD93;">
                        <i style="margin-top: 3px;" class="remove circle layout icon"></i>{{Session::get("message")}}
                    </h3>
                </div>
            @endif
            <div class="ui grid">
                <div class="sixteen wide column">
                    <div class="ui styled">
                        <div class="content active">
                            <br/>
                            <div class="content active">
                                <div class="two fields">
                                    <div class="field">
                                        <label style="color: #235B4E; font-size: large">CURP<span style="color: #691C32;">*</span></label>
                                        <label style="color: #235B4E; font-size: small">Ingrese su CURP para el registro a la convocatoria</label>
                                        {{ Form::text('curp', null, ['style' => 'color: grey', 'maxlength' => '100']) }}<br/>
                                    </div>
                                </div>
                                <hr style="border:1px solid #10312B; width: 80%;"/>
                                <br/>
                            </div>
                        </div>
                    </div>
                    <center>
                        <div style="text-align:center;">
                            <div class="saveForm" style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;">
                                <br/>
                                <img id="guardar" style="margin: auto; width: 45%; height: 45%;" src="/assets/img/enviar.png" class="ui image">
                                <div style="background-color: transparent; text-align: center;">
                                    <hr id="guardarHR" style="border:1px solid #10312B; width: 50%;"/>
                                    <label id="guardarL" class="ui header" style="color: #10312B; font-size: 100%; font-weight: bolder; cursor: pointer;">
                                        Validar
                                    </label>
                                </div>
                            </div>
                        </div>
                        <br/>
                        <p style="color: #691C32"><b>* Campos Obligatorios</b></p>
                    </center>
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
    </script>
@endsection
