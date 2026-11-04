@extends('layouts.ly_adminMovil')

@section('scripts')
    <style>
        tr:hover{
            color: #691C32;
        }
    </style>
@endsection

@section('content')
    {{Form::open(array('action' => array('ConvocatoriaController@asignarValidadorUpdate',Crypt::encrypt($register->id)) ,'class' => 'ui form', 'id' => "register_form"))}}
        <div class="ui segment" id="content-page" style="background-color: transparent;">
            <div class="sixteen wide column">
                <div style="text-align: center; width: 100%; margin: 0 auto;">
                    <h1 class="ui header" style="color: #10312B; font-size: 220%;">
                        Gestión de Validadores de Convocatoria
                    </h1>
                </div>
                <br/>
                <center>
                    <h1 class="ui header" style="color: #235B4E; font-size: 120%;">
                        {{$register->nombre}}
                    </h1>
                </center>
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
                            @if(Session::get("messageUpdate") != null)
                                <div style="text-align: center;">
                                    <h3 class="ui header" style="color: white; background-color: #3DAD93;">
                                        <i style="margin-top: 3px;" class="save layout icon"></i>{{Session::get("messageUpdate")}}
                                    </h3>
                                </div>
                                <br/>
                            @endif
                            <hr style="border:1px solid #10312B; width: 100%;"/>
                            <br/>
                            <label class="ui header" style="color: #10312B; font-size: 180%; font-weight: bolder;">
                                Usuarios Asignados
                            </label>
                            <div class="content active">
                                <div class="menu">
                                    <br/>
                                    <table align="center" style="width: 100%; font-size: 90%">
                                        <thead>
                                            <th>Datos&nbsp;&nbsp;</th>
                                            <th>Acciones</th>
                                        </thead>
                                        <tbody>
                                            @if(count($usuarios) > 0)
                                                @foreach($usuarios as $usuario)
                                                    <tr>
                                                        <td>
                                                            {{$usuario->curp}}
                                                            <br/>
                                                            {{$usuario->nombre}}&nbsp;{{$usuario->apellido_paterno}}&nbsp;{{$usuario->apellido_materno}}&nbsp;&nbsp;
                                                            <br/>
                                                            {{$usuario->email}}&nbsp;&nbsp;
                                                            <br/>
                                                            {{Rol::find($usuario->rol_id)->nombre}}&nbsp;&nbsp;
                                                        </td>
                                                        <td>
                                                            <a href="/convocatoria/eliminaValidador/{{$usuario->id}}" title="Eliminar Usuario">
                                                                <p style="font-size: 200%">
                                                                    <i class="remove circle icon" style="color: #235B4E" onmouseover="this.style.color = '#10312B'" onmouseout="this.style.color = '#235B4E'"></i>&nbsp;
                                                                </p>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="5" style="text-align: center;">
                                                        Aún no hay usuarios asignados al proyecto
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                    <br/>
                                </div>
                                <hr style="border:1px solid #10312B; width: 100%;"/>
                                <br/>
                                <label class="ui header" style="color: #10312B; font-size: 180%; font-weight: bolder;">
                                    Asignar Usuario
                                </label>
                                <br/>
                                <br/>
                                <div class="two fields">
                                    <div class="field">
                                        <label style="color: #10312B; font-size: large">CURP<span style="color: #691C32;">*</span></label>
                                        <label style="color: #10312B; font-size: small">Máximo 18 caracteres</label>
                                        {{ Form::text('curp', null, ['style' => 'color: grey', 'maxlength' => '200']) }}<br/>
                                    </div>
                                    <div class="field">
                                        <label style="color: #10312B; font-size: large">Usuario<span style="color: #691C32;">*</span></label>
                                        <label style="color: #10312B; font-size: small">Máximo 60 caracteres</label>
                                        {{ Form::text('email', null, ['style' => 'color: grey', 'maxlength' => '60'])}}<br/>
                                    </div>
                                    <div class="field">
                                        <label style="color: #10312B; font-size: large">Correo Electrónico<span style="color: #691C32;">*</span></label>
                                        <label style="color: #10312B; font-size: small">Dirección para notificaciones</label>
                                        {{ Form::text('correo_electronico_notificaciones', null, ['style' => 'color: grey', 'maxlength' => '60'])}}<br/>
                                    </div>
                                </div>
                                <div style="text-align:center;">
                                    <div class="saveForm" style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;">
                                        <br/>
                                        <img id="guardar" style="margin: auto; width: 45%; height: 45%;" src="/assets/img/guardar.png" class="ui image">
                                        <div style="background-color: transparent; text-align: center;">
                                            <hr id="guardarHR" style="border:1px solid #10312B; width: 50%;"/>
                                            <label id="guardarL" class="ui header" style="color: #10312B; font-size: 100%; font-weight: bolder; cursor: pointer;">
                                                Asignar
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br/>
            <center>
                <p style="color: #691C32"><b>* Campos Obligatorios</b></p>
            </center>
        </div>
    {{Form::close()}}
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
