@extends('layouts.ly_admin')

@section('content')
    {!!Form::open(array('action' => array('UsuarioController@update',Crypt::encrypt($register->id)) ,'class' => 'ui form', 'id' => "register_form"))!!}
        <div class="ui segment" id="content-page" style="background-color: transparent;">
            <div class="sixteen wide column">
                <div style="text-align: center; width: 51%; margin: 0 auto;">
                    <h1 class="ui header" style="color: #10312B; font-size: 500%;">
                        Editar Usuario
                    </h1>
                </div>
            </div>
            <br/>
            <div class="ui grid">
                <div class="three wide column">                
                    <div class="ui grid">
                      <div class="sixteen wide column">
                        <div style="margin-left:auto;margin-right:auto;margin-top:5px;text-align:center;">
                            <span><marquee><label style="color: #691C32"><b>{{Session::get("message")}}</b></label></marquee></span>
                        </div>
                        @if (count($errors) > 0)                        
                            @foreach ($errors->all() as $error)
                                <div style="margin-left:auto;margin-right:auto;margin-top:5px;color: #691C32;">
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
                            <a href="/usuario/index/">
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
                                        <label style="color: #235B4E; font-size: large">Enlace {!!Form::checkbox('default_password', null, $register->default_password,['class' => 'ui checkbox'])!!}</label>
                                        <label style="color: #235B4E; font-size: small">Marque la casilla para activar el enlace para cambio de contraseña</label>
                                    </div>
                                    <div class="field">
                                        <label style="color: #235B4E; font-size: large">Habilitar Usuario {!!Form::checkbox('enabled', null, $register->enabled,['class' => 'ui checkbox'])!!}</label>
                                        <label style="color: #235B4E; font-size: small">Marque la casilla si desea habilitar al usuario</label>
                                    </div>
                                </div>
                                <div class="field">
                                    <label style="color: #235B4E; font-size: large">Cambio de Contraseña</label>
                                    <label style="color: #235B4E; font-size: small"><i id="contrasena_txt">{{\App\Models\Ambiente::find(1)->nombre}}cambiarContrasena/u/{{$register->email}}/d/{{$register->verify_password}}</i>&nbsp;&nbsp;<span onclick="copiar();"><i class="copy circle icon" style="font-size: large; cursor: pointer; color: #235B4E" onmouseover="this.style.color = '#691C32'" onmouseout="this.style.color = '#235B4E'"></i></span></label>
                                </div>
                                <br/>
                                <hr style="border:1px solid #10312B; width: 80%;"/>
                                <br/>
                                <div class="three fields">
                                    <div class="field">
                                        <label style="color: #235B4E; font-size: large">Nombre<span style="color: #691C32;">*</span></label>
                                        <label style="color: #235B4E; font-size: small">Máximo 100 caracteres</label>
                                        {!! Form::text('nombre', $register->nombre, ['style' => 'color: grey', 'maxlength' => '100']) !!}<br/>
                                    </div>
                                    <div class="field">
                                        <label style="color: #235B4E; font-size: large">Apellido Paterno<span style="color: #691C32;">*</span></label>
                                        <label style="color: #235B4E; font-size: small">Máximo 100 caracteres</label>
                                        {!! Form::text('apellido_paterno', $register->apellido_paterno, ['style' => 'color: grey', 'maxlength' => '100']) !!}<br/>
                                    </div>
                                    <div class="field">
                                        <label style="color: #235B4E; font-size: large">Apellido Materno</label>
                                        <label style="color: #235B4E; font-size: small">Máximo 100 caracteres</label>
                                        {!! Form::text('apellido_materno', $register->apellido_materno, ['style' => 'color: grey', 'maxlength' => '100']) !!}<br/>
                                    </div>
                                </div>
                                <br/>
                                <div class="three fields">
                                    <div class="field">
                                        <label style="color: #235B4E; font-size: large">Correo Electrónico (Usuario)<span style="color: #691C32;">*</span></label>
                                        <label style="color: #235B4E; font-size: small">Escriba su correo institucional</label>
                                        <label style="color: #235B4E; font-size: small">{{$register->email}}</label>
                                    </div>
                                    <div class="field">
                                        <label style="color: #235B4E; font-size: large">Rol del Usuario<span style="color: #691C32;">*</span></label>
                                        <label style="color: #235B4E; font-size: small">Seleccione el rol del usuario</label>
                                        <div class="ui fluid selection dropdown">
                                            {!! Form::hidden('rol_id', $register->rol_id) !!}
                                            <i style="color: #691C32;" class="list layout icon"></i>
                                            <div class="default text" style="color: #235B4E;">Por favor, selecciona una opción</div>
                                            <div class="menu">
                                                @foreach(\App\Models\Rol::where('id', 1)->get() as $rol)
                                                    <div class="item" style="color: #235B4E" data-value="{{ $rol->id }}">{{ $rol->nombre }}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
    {!! Form::close() !!}   
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
