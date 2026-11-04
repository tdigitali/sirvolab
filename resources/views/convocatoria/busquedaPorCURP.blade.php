@extends('layouts.ly_admin')

@section('content')
    {{Form::open(array('action' => 'ConvocatoriaController@busquedaPorCURPConsulta' ,'class' => 'ui form', 'id' => "register_form"))}}
        <div class="ui segment" id="content-page" style="background-color: transparent; text-align: center;">
            <div class="ui grid">
                <div class="one wide column">
                    <div style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #002F2A; cursor: pointer;" onmouseover="document.getElementById('regresar').src = '/assets/img/regresar_blanco.png'; document.getElementById('regresarHR').style.border = '1px solid white'; document.getElementById('regresarL').style.color = 'white'; this.style.backgroundColor = '#002F2A';" onmouseout="document.getElementById('regresar').src = '/assets/img/regresar_verde.png'; document.getElementById('regresarHR').style.border = '1px solid #002F2A'; document.getElementById('regresarL').style.color = '#002F2A'; this.style.backgroundColor = 'transparent';">
                        <br/>
                        <a href="/menu/principal/">
                            <img id="regresar" style="margin: auto; width: 45%; height: 45%;" src="/assets/img/regresar_verde.png" class="ui image">
                            <div style="background-color: transparent; text-align: center;">
                                <hr id="regresarHR" style="border:1px solid #002F2A; width: 50%;"/>
                                <label id="regresarL" class="ui header" style="color: #002F2A; font-size: 100%; font-weight: bolder; cursor: pointer;">
                                    Regresar
                                </label>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="fourteen wide column">
                    <br/>
                    <div style="text-align: center; width: 100%; margin: 0 auto;">
                        <h1 class="ui header" style="color: #002F2A; font-size: 500%;">
                            Búsqueda de persona
                        </h1>
                    </div>
                </div>
                <div class="one wide column">
                    &nbsp;
                </div>
                <div class="three wide column">
                    &nbsp;
                </div>
                <div class="ten wide column" style="border: 5px solid #002F2A;">
                    <div class="ui styled">
                        <div class="content active">
                            <div class="ui styled accordion fluid" style="background-color: transparent;">
                                @if($mensaje)
                                    <h3 class="ui header" style="color: white; background-color: #3DAD93; text-align: center;">
                                        <i style="margin-top: 3px;" class="remove circle layout icon"></i>{{$mensaje}}
                                    </h3>
                                @endif
                                <div class="content active">
                                    <div style="text-align: center;" class="field">
                                        <label style="color: #002F2A; font-size: 200%; font-weight: bold;">
                                            Por favor, ingresa la CURP de la persona.
                                        </label>
                                    </div>
                                    <center>
                                        <table align="center" style="width: 50%;">
                                            <tr>
                                                <td style="background-color: transparent;">
                                                    <div class="field">
                                                        <label style="color: #1E5B4F; font-size: large">CURP<span style="color: #691C32;">*</span></label>
                                                        <label style="color: #1E5B4F; font-size: small">Máximo 18 caracteres</label>
                                                        {{Form::text('curp', $curp, ['style' => 'color: grey', 'maxlength' => '18'])}}<br/>
                                                    </div>
                                                </td>
                                                <td style="background-color: transparent;">
                                                    <div class="saveForm" style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; cursor: pointer;">
                                                        <br/>
                                                        <img id="guardar" style="margin: auto; width: 45%; height: 45%;" src="/assets/img/notas.png" class="ui image">
                                                        <div style="background-color: transparent; text-align: center;">
                                                            <hr id="guardarHR" style="border:1px solid #002F2A; width: 50%;"/>
                                                            <label id="guardarL" class="ui header" style="color: #002F2A; font-size: 100%; font-weight: bolder; cursor: pointer;">
                                                                Buscar
                                                            </label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="three wide column">
                    &nbsp;
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
    </script>
@endsection