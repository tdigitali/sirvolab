@extends('layouts.ly_adminMovil')

@section('content')
    {{Form::open(array('action' => array('ConvocatoriaController@asignarComiteUpdate',Crypt::encrypt($register->id)) ,'class' => 'ui form', 'id' => "register_form"))}}
        <div class="ui segment" id="content-page" style="background-color: transparent;">
            <div class="sixteen wide column">
                <div style="text-align: center; width: 100%; margin: 0 auto;">
                    <h1 class="ui header" style="color: #10312B; font-size: 220%;">
                        Gestión de Usuarios de Convocatoria
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
                        <div class="content active" >
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
                                            <th colspan="5">Datos&nbsp;&nbsp;</th>
                                        </thead>
                                        <tbody>
                                            @if(count($usuarios) > 0)
                                                @foreach($usuarios as $usuario)
                                                    <tr>
                                                        <td colspan="5">
                                                            {{$usuario->curp}}
                                                            <br/>
                                                            {{$usuario->nombre}}&nbsp;{{$usuario->apellido_paterno}}&nbsp;{{$usuario->apellido_materno}}&nbsp;&nbsp;
                                                            <br/>
                                                            {{$usuario->email}}&nbsp;&nbsp;
                                                            <br/>
                                                            {{Rol::find($usuario->rol_id)->nombre}}&nbsp;&nbsp;
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        @if($usuario->rol_id == 4)
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                            <td>&nbsp;</td>
                                                        @else
                                                            <td>
                                                                <a href="/convocatoria/cambiaPermiso/{{Crypt::encrypt($usuario->id)}}" title="Cambiar permiso">
                                                                    <p style="font-size: 150%">
                                                                        <i class="exchange icon" style="color: #235B4E" onmouseover="this.style.color = '#10312B'" onmouseout="this.style.color = '#235B4E'"></i>&nbsp;
                                                                    </p>
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <a href="/usuario/generarEnviarToken/{{Crypt::encrypt($usuario->id)}}" title="Generar y enviar token">
                                                                    <p style="font-size: 150%">
                                                                        <i class="key icon" style="color: #235B4E" onmouseover="this.style.color = '#10312B'" onmouseout="this.style.color = '#235B4E'"></i>&nbsp;
                                                                    </p>
                                                                </a>
                                                            </td>
                                                            <td onclick="copiarToken('token' + '{{$usuario->verify_password}}');">
                                                                {{Form::hidden('token', $usuario->token_autorizacion, ['id' => 'token'.$usuario->verify_password, 'style' => 'color: grey', 'maxlength' => '60'])}}
                                                                <a href="#" title="Copiar token">
                                                                    <p style="font-size: 150%">
                                                                        <i class="unlock icon" style="color: #235B4E" onmouseover="this.style.color = '#10312B'" onmouseout="this.style.color = '#235B4E'"></i>&nbsp;
                                                                    </p>
                                                                </a>
                                                            </td>
                                                        @endif
                                                        <td onclick="copiar('contrasena' + '{{$usuario->verify_password}}');">
                                                            {{Form::hidden('enlace', Ambiente::find(1)->nombre.'cambiarContrasena/u/'.$usuario->email.'/d/'.$usuario->verify_password, ['id' => 'contrasena'.$usuario->verify_password, 'style' => 'color: grey', 'maxlength' => '60'])}}
                                                            <a href="/usuario/enviarEnlaceAcceso/{{$usuario->id}}" title="Copiar enlace de acceso">
                                                                <p style="font-size: 150%">
                                                                    <i class="linkify icon" style="color: #235B4E" onmouseover="this.style.color = '#10312B'" onmouseout="this.style.color = '#235B4E'"></i>&nbsp;
                                                                </p>
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="/convocatoria/eliminaAsignacion/{{Crypt::encrypt($usuario->id)}}" onclick="return confirm('¿Desea eliminar al usuario de convocatoria?')" title="Eliminar usuario">
                                                                <p style="font-size: 150%">
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
                                <div class="four fields">
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
                                    <div class="field">
                                        <label style="color: #10312B; font-size: large">Rol<span style="color: #691C32;">*</span></label>
                                        <label style="color: #10312B; font-size: small">Seleccione el rol que desea asignar</label>
                                        <div class="ui fluid selection dropdown search">
                                            {{ Form::hidden('rol_id', null) }}
                                            <i style="color: #691C32;" class="list layout icon"></i>
                                            <div class="default text" style="color: #10312B;">Selecciona una opción</div>
                                            <div class="menu">
                                                @foreach(Rol::where('id', 2)->orWhere('id', 3)->orWhere('id', 4)->get() as $rol)
                                                    <div class="item" style="color: #10312B" data-value="{{$rol->id}}">{{$rol->nombre}}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br/>
                                <hr style="border:1px solid #10312B; width: 100%;"/>
                                <br/>
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
        
        function copiar(cad){
            var aux = document.createElement("input");
            aux.setAttribute("value", document.getElementById(cad).value);
            document.body.appendChild(aux);
            aux.select();
            document.execCommand("copy");
            document.body.removeChild(aux);
            alert("Vínculo copiado");
        }
        
        function copiarToken(cad){
            var aux = document.createElement("input");
            aux.setAttribute("value", document.getElementById(cad).value);
            document.body.appendChild(aux);
            aux.select();
            document.execCommand("copy");
            document.body.removeChild(aux);
            alert("Token copiado");
        }
    </script>
@endsection
