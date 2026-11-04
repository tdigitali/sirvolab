@extends('layouts.ly_admin')

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
                    <h1 class="ui header" style="color: #10312B; font-size: 450%;">
                        Gestión de Validadores de Convocatoria
                    </h1>
                </div>
                <br/>
                <center>
                    <h1 class="ui header" style="color: #235B4E; font-size: 200%;">
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
                        <div style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;" onmouseover="document.getElementById('regresar').src = '/assets/img/regresar_blanco.png'; document.getElementById('regresarHR').style.border = '1px solid white'; document.getElementById('regresarL').style.color = 'white'; this.style.backgroundColor = '#10312B';" onmouseout="document.getElementById('regresar').src = '/assets/img/regresar_verde.png'; document.getElementById('regresarHR').style.border = '1px solid #10312B'; document.getElementById('regresarL').style.color = '#10312B'; this.style.backgroundColor = 'transparent';">
                            <br/>
                            <a href="/menu/principal/">
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
                <div class="twelve wide column">
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
                                    <table align="center" style="width: 100%;">
                                        <thead>
                                            <th>CURP - Nombre Completo&nbsp;&nbsp;</th>
                                            <th>Usuario&nbsp;&nbsp;</th>
                                            <th>Correo electrónico&nbsp;&nbsp;</th>
                                            <th>Rol&nbsp;&nbsp;</th>
                                            <th colspan="1">Acciones</th>
                                        </thead>
                                        <tbody>
                                            @if(count($usuarios) > 0)
                                                @foreach($usuarios as $usuario)
                                                    <tr>
                                                        <td>{{$usuario->curp}}<br/>{{$usuario->nombre}}&nbsp;{{$usuario->apellido_paterno}}&nbsp;{{$usuario->apellido_materno}}&nbsp;&nbsp;</td>
                                                        <td>{{$usuario->email}}&nbsp;&nbsp;</td>
                                                        <td>{{$usuario->correo_electronico_notificaciones}}&nbsp;&nbsp;</td>
                                                        <td>{{Rol::find($usuario->rol_id)->nombre}}&nbsp;&nbsp;</td>
                                                        <td>
                                                            <a href="/convocatoria/eliminaValidador/{{$usuario->id}}" title="Eliminar Usuario">
                                                                <p style="font-size: 200%">
                                                                    <i class="remove circle icon" style="color: #235B4E" onmouseover="this.style.color = '#691C32'" onmouseout="this.style.color = '#235B4E'"></i>&nbsp;
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
                                <br/>
                                <div style="text-align:center;">
                                    <div class="saveForm" style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;" onmouseover="document.getElementById('guardar').src = '/assets/img/guardar_blanco.png'; document.getElementById('guardarHR').style.border = '1px solid white'; document.getElementById('guardarL').style.color = 'white'; this.style.backgroundColor = '#10312B';" onmouseout="document.getElementById('guardar').src = '/assets/img/guardar.png'; document.getElementById('guardarHR').style.border = '1px solid #10312B'; document.getElementById('guardarL').style.color = '#10312B'; this.style.backgroundColor = 'transparent';">
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
