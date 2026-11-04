@extends('layouts.ly_admin')

@section('content')
    {{Form::open(array('action' => array('BoletaController@update',Crypt::encrypt($register->id)) ,'class' => 'ui form', 'id' => "register_form"))}}
        <div class="ui segment" id="content-page" style="background-color: transparent;">
            <div class="sixteen wide column">
                <div style="text-align: center; width: 51%; margin: 0 auto;">
                    <h1 class="ui header" style="color: #10312B; font-size: 500%;">
                        Editar Boleta
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
                <div class="three wide column">                
                    <div class="ui grid">
                      <div class="sixteen wide column">
                        @if (count($errors) > 0)                        
                            @foreach ($errors->all() as $error)
                                <div style="margin-left:auto;margin-right:auto;margin-top:5px;color: #10312B;">
                                    <i class="remove circle icon"></i>{{$error}}
                                </div>
                            @endforeach
                        @endif
                      </div>
                    </div>
                    <br/>
                    <div style="text-align:center;">
                        <div class="saveForm" style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;" onmouseover="document.getElementById('guardar').src = '/assets/img/guardar_blanco.png'; document.getElementById('guardarHR').style.border = '1px solid white'; document.getElementById('guardarL').style.color = 'white'; this.style.backgroundColor = '#10312B';" onmouseout="document.getElementById('guardar').src = '/assets/img/guardar.png'; document.getElementById('guardarHR').style.border = '1px solid #10312B'; document.getElementById('guardarL').style.color = '#10312B'; this.style.backgroundColor = 'transparent';">
                            <br/>
                            <img id="guardar" style="margin: auto; width: 45%; height: 45%;" src="/assets/img/guardar.png" class="ui image">
                            <div style="background-color: transparent; text-align: center;">
                                <hr id="guardarHR" style="border:1px solid #10312B; width: 50%;"/>
                                <label id="guardarL" class="ui header" style="color: #10312B; font-size: 100%; font-weight: bolder; cursor: pointer;">
                                    Actualizar
                                </label>
                            </div>
                        </div>
                        <br/>
                        <div style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;" onmouseover="document.getElementById('regresar').src = '/assets/img/regresar_blanco.png'; document.getElementById('regresarHR').style.border = '1px solid white'; document.getElementById('regresarL').style.color = 'white'; this.style.backgroundColor = '#10312B';" onmouseout="document.getElementById('regresar').src = '/assets/img/regresar_verde.png'; document.getElementById('regresarHR').style.border = '1px solid #10312B'; document.getElementById('regresarL').style.color = '#10312B'; this.style.backgroundColor = 'transparent';">
                            <br/>
                            <a href="/boleta/index/">
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
                            <div class="content active">
                                <div class="two fields">
                                    <div class="field">
                                        <label style="color: #235B4E; font-size: large">Cargo a Postular<span style="color: #691C32;">*</span></label>
                                        <label style="color: #235B4E; font-size: small">Máximo 200 caracteres</label>
                                        {{ Form::text('cargo_postulacion', $register->cargo_postulacion, ['style' => 'color: grey', 'maxlength' => '200']) }}<br/>
                                    </div>
                                    <div class="field">
                                        <label style="color: #235B4E; font-size: large">Número de Boleta<span style="color: #691C32;">*</span></label>
                                        <label style="color: #235B4E; font-size: small">Número Máximo de Asignación: 10</label>
                                        {{ Form::number('numero_boleta', $register->numero_boleta, ['style' => 'color: grey', 'maxlength' => '10']) }}<br/>
                                    </div>
                                </div>
                                <br/>
                                <hr style="border:1px solid #10312B; width: 80%;"/>
                                <br/>
                            </div>
                        </div>
                    </div>
                    <center>
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
        
        function copiar(){
            var aux = document.createElement("input");
            aux.setAttribute("value", document.getElementById("contrasena_txt").innerHTML);
            document.body.appendChild(aux);
            aux.select();
            document.execCommand("copy");
            document.body.removeChild(aux);
            alert("Vínculo copiado");
        }
    </script>
@endsection
