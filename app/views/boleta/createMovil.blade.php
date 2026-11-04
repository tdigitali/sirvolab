@extends('layouts.ly_adminMovil')

@section('content')
    {{Form::open(array('action' => 'BoletaController@store' ,'class' => 'ui form', 'id' => "register_form"))}}
        <div class="ui segment" id="content-page" style="background-color: transparent;">
            <div class="sixteen wide column">
                <div style="text-align: center; width: 100%; margin: 0 auto;">
                    <h1 class="ui header" style="color: #10312B; font-size: 220%;">
                        Crear Boleta
                    </h1>
                </div>
            </div>
            <br/>
            @if(Session::get("message") != null)
                <h3 class="ui header" style="color: white; background-color: #3DAD93; text-align: center;">
                    <i style="margin-top: 3px;" class="remove circle layout icon"></i>{{Session::get("message")}}
                </h3>
            @endif
            <div class="ui grid">
                <div class="sixteen wide column">
                    <div class="ui styled">
                        <div class="content active">
                            <br/>
                            <div class="content active">
                                <div class="two fields">
                                    <div class="field">
                                        @if(Convocatoria::find(Auth::user()->convocatoria_id)->aprobacion_confirmacion_contrato)
                                            <label style="color: #235B4E; font-size: large">Pregunta en la boleta<span style="color: #691C32;">*</span></label>
                                        @else
                                            <label style="color: #235B4E; font-size: large">Cargo a Postular<span style="color: #691C32;">*</span></label>
                                        @endif
                                        <label style="color: #235B4E; font-size: small">Máximo 200 caracteres</label>
                                        {{ Form::text('cargo_postulacion', null, ['style' => 'color: grey', 'maxlength' => '200']) }}<br/>
                                    </div>
                                    <div class="field">
                                        <label style="color: #235B4E; font-size: large">Número de Boleta<span style="color: #691C32;">*</span></label>
                                        <label style="color: #235B4E; font-size: small">Número Máximo de Asignación: 10</label>
                                        {{ Form::number('numero_boleta', null, ['style' => 'color: grey', 'maxlength' => '10']) }}<br/>
                                    </div>
                                </div>
                                <br/>
                                <hr style="border:1px solid #10312B; width: 80%;"/>
                                <br/>
                            </div>
                        </div>
                    </div>
                    <center>
                        <div style="text-align:center;">
                            <div class="saveForm" style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;">
                                <br/>
                                <img id="guardar" style="margin: auto; width: 45%; height: 45%;" src="/assets/img/guardar.png" class="ui image">
                                <div style="background-color: transparent; text-align: center;">
                                    <hr id="guardarHR" style="border:1px solid #10312B; width: 50%;"/>
                                    <label id="guardarL" class="ui header" style="color: #10312B; font-size: 100%; font-weight: bolder; cursor: pointer;">
                                        Crear
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
