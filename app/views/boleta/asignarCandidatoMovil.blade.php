@extends('layouts.ly_adminMovil')

@section('scripts')
    <style>
        tr:hover{
            color: #10312B;
        }
    </style>
@endsection

@section('content')
    {{Form::open(array('action' => array('BoletaController@asignarCandidatoUpdate',Crypt::encrypt($register->id)) ,'class' => 'ui form', 'id' => "register_form"))}}
        <div class="ui segment" id="content-page" style="background-color: transparent;">
            <div class="sixteen wide column">
                <div style="text-align: center; width: 100%; margin: 0 auto;">
                    <h1 class="ui header" style="color: #10312B; font-size: 220%;">
                        Configuración de Boleta
                    </h1>
                </div>
                <br/>
                <center>
                    <h1 class="ui header" style="color: #235B4E; font-size: 120%;">
                        {{$register->cargo_postulacion}}
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
                                Candidatos Asignados
                            </label>
                            <div class="content active">
                                <div class="menu">
                                    <br/>
                                    <table align="center" style="width: 100%;">
                                        <thead>
                                            <th>&nbsp;&nbsp;Datos&nbsp;&nbsp;</th>
                                            <th>Acciones&nbsp;&nbsp;</th>
                                        </thead>
                                        <tbody>
                                            @if(count($candidatos) > 0)
                                                @foreach($candidatos as $candidato)
                                                    <tr>
                                                        <td>
                                                            {{$candidato->curp}}&nbsp;
                                                            <br/>
                                                            {{$candidato->nombre_completo}}&nbsp;
                                                            <br/>
                                                            <span style="border-radius: 20px; background-color: {{$candidato->planilla_color}};">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                                        </td>
                                                        <td>
                                                            <a href="/boleta/eliminarCandidato/{{$candidato->id}}" onclick="return confirm('¿Desea eliminar al candidato de la boleta?')" title="Eliminar candidato">
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
                                                        Aún no hay candidatos asignados a la boleta
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
                                        {{Form::text('curp', null, ['style' => 'color: grey', 'maxlength' => '18'])}}<br/>
                                    </div>
                                     <div class="field">
                                        <label style="color: #10312B; font-size: large">Nombre Completo<span style="color: #691C32;">*</span></label>
                                        <label style="color: #10312B; font-size: small">Máximo 200 caracteres</label>
                                        {{Form::text('nombre_completo', null, ['style' => 'color: grey', 'maxlength' => '200'])}}<br/>
                                    </div>
                                    <div class="field">
                                        <label style="color: #10312B; font-size: large">Color de Planilla<span style="color: #691C32;">*</span></label>
                                        <label style="color: #10312B; font-size: small">Seleccione el color que desea asignar a la planilla</label>
                                        <input type="color" id="planilla_color" name="planilla_color" value="#ff0000" />
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
                    <br/>
                    <center>
                        <p style="color: #691C32"><b>* Campos Obligatorios</b></p>
                    </center>
                </div>
            </div>
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
