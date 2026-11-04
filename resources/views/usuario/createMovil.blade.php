@extends('layouts.ly_adminMovil')

@section('content')
    {!!Form::open(array('action' => 'UsuarioController@store' ,'class' => 'ui form', 'id' => "register_form"))!!}
        <div class="ui segment" id="content-page" style="background-color: transparent;">
            <div class="sixteen wide column">
                <div style="text-align: center; width: 100%;">
                    <h1 class="ui header" style="color: #10312B; font-size: 250%;">
                        Crear Usuario
                    </h1>
                </div>
                @if(Session::get("message") != null)
                    <h3 class="ui header" style="color: white; background-color: #3DAD93; text-align: center;">
                        <i style="margin-top: 3px;" class="remove circle layout icon"></i>{{Session::get("message")}}
                    </h3>
                @endif
            </div>
            <br/>
            <div class="ui grid">
                <div class="sixteen wide column">
                    <div class="ui styled">
                        <div class="content active">
                            <br/>
                            <div class="content active">
                                <div class="two fields">
                                    <div class="field">
                                        <label style="color: #235B4E; font-size: large">Enlace {!!Form::checkbox('default_password', null, true,['class' => 'ui checkbox'])!!}</label>
                                        <label style="color: #235B4E; font-size: small">Marque la casilla para activar el enlace para cambio de contraseña</label>
                                    </div>
                                    <div class="field">
                                        <label style="color: #235B4E; font-size: large">Habilitar Usuario {!!Form::checkbox('enabled', null, true,['class' => 'ui checkbox'])!!}</label>
                                        <label style="color: #235B4E; font-size: small">Marque la casilla si desea habilitar al usuario</label>
                                    </div>
                                </div>
                                <hr style="border:1px solid #10312B; width: 80%;"/>
                                <br/>
                                <div class="three fields">
                                    <div class="field">
                                        <label style="color: #235B4E; font-size: large">Nombre<span style="color: #691C32;">*</span></label>
                                        <label style="color: #235B4E; font-size: small">Máximo 100 caracteres</label>
                                        {!! Form::text('nombre', null, ['style' => 'color: grey', 'maxlength' => '100']) !!}<br/>
                                    </div>
                                    <div class="field">
                                        <label style="color: #235B4E; font-size: large">Apellido Paterno<span style="color: #691C32;">*</span></label>
                                        <label style="color: #235B4E; font-size: small">Máximo 100 caracteres</label>
                                        {!! Form::text('apellido_paterno', null, ['style' => 'color: grey', 'maxlength' => '100']) !!}<br/>
                                    </div>
                                    <div class="field">
                                        <label style="color: #235B4E; font-size: large">Apellido Materno</label>
                                        <label style="color: #235B4E; font-size: small">Máximo 100 caracteres</label>
                                        {!! Form::text('apellido_materno', null, ['style' => 'color: grey', 'maxlength' => '100']) !!}<br/>
                                    </div>
                                </div>
                                <div class="three fields">
                                    <div class="field">
                                        <label style="color: #235B4E; font-size: large">Correo Electrónico (Usuario)<span style="color: #691C32;">*</span></label>
                                        <label style="color: #235B4E; font-size: small">Escriba su correo institucional</label>
                                        {!!Form::text('email', null, ['style' => 'color: grey', 'maxlength' => '60'])!!}<br/>
                                    </div>
                                    <div class="field">
                                        <label style="color: #235B4E; font-size: large">Rol del Usuario<span style="color: #691C32;">*</span></label>
                                        <label style="color: #235B4E; font-size: small">Seleccione el rol del usuario</label>
                                        <div class="ui fluid selection dropdown">
                                            {!!Form::hidden('rol_id', null)!!}
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
    </script>
@endsection
