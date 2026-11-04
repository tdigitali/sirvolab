@extends('layouts.ly_registro')

@section('scripts')
    <script type="text/javascript">
        $('.menu .item').tab();
        function confirmarVoto(id){
            if(id == 0){
                $('#candidato' + id).modal('show');
            }else{
                var sel1 = document.getElementById('seleccion1');
                var sel2 = document.getElementById('seleccion2');

                if((sel1.value != null && sel1.value != "") && (sel2.value != null && sel2.value != "")){
                    document.getElementById('boletaCandidato'+sel1.value).style.backgroundColor = 'transparent';
                    document.getElementById('boletaCandidato'+sel2.value).style.backgroundColor = 'transparent';
                    sel1.value = null;
                    sel2.value = null;
                }

                if(sel1.value != id){
                    if(sel1.value == null || sel1.value == ""){
                        sel1.value = id;
                        document.getElementById('boletaCandidato'+id).style.backgroundColor = '#01E9C8';
                    }else{
                        sel2.value = id;
                        document.getElementById('boletaCandidato'+id).style.backgroundColor = '#01E9C8';
                    }

                    if(sel2.value != null && sel2.value != "")
                        $('#candidato' + id).modal('show');
                }else{
                    alert("No puedes seleccionar al mismo candidato 2 veces.");
                }
            }
        }
    </script>
@endsection

@section('content')
    {{Form::open(array('action' => array('VotacionController@realizarVotoPorCargo',Crypt::encrypt($convocatoriaPadron->id)) ,'class' => 'ui form', 'id' => "register_form"))}}
        {{Form::hidden('seleccion1', null, ['id' => 'seleccion1'])}}
        {{Form::hidden('seleccion2', null, ['id' => 'seleccion2'])}}
        <div class="ui segment" id="content-page" style="background-color: transparent;">
            <div class="ui grid">
                <div class="three wide column">                
                    &nbsp;
                </div>
                <div class="ten wide column">
                    <div class="ui styled accordion fluid" style="background-color: transparent; border: 5px solid #10312B;">
                        <br/>
                        <div style="text-align: center; width: 90%; margin: 0 auto;">
                            <h1 class="ui header" style="color: #10312B; font-size: 180%;">
                                {{Convocatoria::find($convocatoriaPadron->convocatoria_id)->nombre}}
                            </h1>
                        </div>
                        <br/>
                        <br/>
                        <center>
                            <hr style="border:1px solid #235B4E; width: 70%; margin: 0;"/>
                            <br/>
                        </center>
                        @if(Session::get("message") != null)
                            <h3 class="ui header" style="color: white; background-color: #3DAD93; text-align: center; font-size: 150%">
                                <i style="margin-top: 3px;" class="remove circle layout icon"></i>{{Session::get("message")}}
                            </h3>
                        @endif
                        <div class="content active">
                            <div style="text-align: center;">
                                <label class="ui header" style="color: #235B4E; font-size: 220%; font-weight: bolder;">
                                    {{$boleta->cargo_postulacion}}
                                </label>
                            </div>
                            <center>
                                <br/>
                                <hr style="border:1px solid #235B4E; width: 60%; margin: 0;"/>
                                <br/>
                                <br/>
                            </center>
                            <div style="text-align: center;">
                                <label class="ui header" style="color: #3DAD93; font-size: 150%; font-weight: bolder;">
                                    **Debe seleccionar dos candidatos para emitir su voto**
                                </label>
                            </div>
                            <br/>
                            <br/>
                            @foreach(BoletaCandidato::where('boleta_id', $boleta->id)->get() as $boletaCandidato)
                                <div id="boletaCandidato{{$boletaCandidato->id}}" class="two fields" style="background-color: transparent;">
                                    <div class="field" style="border-radius:10px; text-align: center; width: 100%; margin: 0 auto;">
                                        <a href="#" onclick="confirmarVoto({{$boletaCandidato->id}});" style="cursor: pointer; color: #235B4E; font-size: large">
                                            <br/>
                                            {{$boletaCandidato->nombre_completo}}
                                            <br/>
                                            <br/>
                                        </a>
                                    </div>
                                    <div class="field" style="text-align: center; width: 51%; margin: 0 auto;">
                                        <a href="#" onclick="confirmarVoto({{$boletaCandidato->id}});"  style="cursor: pointer; color: #10312B; font-size: large">
                                            <div style="background-color: {{$boletaCandidato->planilla_color}}; margin: auto; display: block; width:100px; height:100px; border-radius:100px;">
                                                <br/><br/><br/>
                                                <br/><br/><br/>
                                            </div>
                                        </a>
                                    </div>
                                    <div id="candidato{{$boletaCandidato->id}}" class="ui modal">
                                        <i class="close icon"></i>
                                        <div class="content">
                                            <center>
                                                <label style="color: #10312B; font-size: x-large; font-weight: bold;">Confirmación de Voto</label>
                                                <br/>
                                                <label style="color: #235B4E; font-size: medium; font-weight: bold;">¿Desea votar por {{$boletaCandidato->nombre_completo}}?</label>
                                                <br/>
                                                <hr style="border:1px solid #10312B; width: 80%;"/>
                                                <p style="color: #3DAD93"><b>**Una vez realizado el voto ya no se podrá editar**</b></p>
                                            </center>
                                            <br/>
                                            <div class="saveForm" style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;" onmouseover="document.getElementById('regresar' + {{$boletaCandidato->id}}).src = '/assets/img/enviar_blanco.png'; document.getElementById('regresarHR' + {{$boletaCandidato->id}}).style.border = '1px solid white'; document.getElementById('regresarL' + {{$boletaCandidato->id}}).style.color = 'white'; this.style.backgroundColor = '#10312B';" onmouseout="document.getElementById('regresar' + {{$boletaCandidato->id}}).src = '/assets/img/enviar.png'; document.getElementById('regresarHR' + {{$boletaCandidato->id}}).style.border = '1px solid #10312B'; document.getElementById('regresarL' + {{$boletaCandidato->id}}).style.color = '#10312B'; this.style.backgroundColor = 'transparent';">
                                                <br/>
                                                <img id="regresar{{$boletaCandidato->id}}" style="margin: auto; width: 45%; height: 45%;" src="/assets/img/enviar.png" class="ui image">
                                                <div style="background-color: transparent; text-align: center;">
                                                    <hr id="regresarHR{{$boletaCandidato->id}}" style="border:1px solid #10312B; width: 50%;"/>
                                                    <label id="regresarL{{$boletaCandidato->id}}" class="ui header" style="color: #10312B; font-size: 100%; font-weight: bolder; cursor: pointer;">
                                                        Enviar
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br/>
                                <hr style="border:1px dashed #235B4E; width: 80%;"/>
                                <br/>
                            @endforeach
                            <div class="two fields" style="background-color: transparent;">
                                <div class="field" style="border-radius:10px; text-align: center; width: 100%; margin: 0 auto;">
                                    <a href="#" onclick="confirmarVoto(0);"  style="cursor: pointer; color: #235B4E; font-size: large">
                                        <br/>
                                        Voto Nulo
                                        <br/>
                                        <br/>
                                    </a>
                                </div>
                                <div class="field" style="text-align: center; width: 51%; margin: 0 auto;">
                                    <a href="#" onclick="confirmarVoto(0);"  style="cursor: pointer; color: #235B4E; font-size: large">
                                        <br/>
                                        Voto Nulo
                                        <br/>
                                        <br/>
                                    </a>
                                </div>
                                <div id="candidato0" class="ui modal">
                                    <i class="close icon"></i>
                                    <div class="content">
                                        <center>
                                            <label style="color: #10312B; font-size: x-large; font-weight: bold;">Confirmación de Voto</label>
                                            <br/>
                                            <label style="color: #235B4E; font-size: medium; font-weight: bold;">¿Desea anular su voto?</label>
                                            <br/>
                                            <hr style="border:1px solid #10312B; width: 80%;"/>
                                            <p style="color: #3DAD93"><b>**Una vez realizado el voto ya no se podrá editar**</b></p>
                                        </center>
                                        <br/>
                                        <div style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;" onmouseover="document.getElementById('regresar0').src = '/assets/img/enviar_blanco.png'; document.getElementById('regresarHR0').style.border = '1px solid white'; document.getElementById('regresarL0').style.color = 'white'; this.style.backgroundColor = '#10312B';" onmouseout="document.getElementById('regresar0').src = '/assets/img/enviar.png'; document.getElementById('regresarHR0').style.border = '1px solid #10312B'; document.getElementById('regresarL0').style.color = '#10312B'; this.style.backgroundColor = 'transparent';">
                                            <br/>
                                            <a href="{{Ambiente::find(1)->nombre}}anularVoto/{{Crypt::encrypt($convocatoriaPadron->id)}}/{{Crypt::encrypt($boleta->id)}}" title="Anular Voto">
                                                <img id="regresar0" style="margin: auto; width: 45%; height: 45%;" src="/assets/img/enviar.png" class="ui image">
                                                <div style="background-color: transparent; text-align: center;">
                                                    <hr id="regresarHR0" style="border:1px solid #10312B; width: 50%;"/>
                                                    <label id="regresarL0" class="ui header" style="color: #10312B; font-size: 100%; font-weight: bolder; cursor: pointer;">
                                                        Enviar
                                                    </label>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br/>
                            <hr style="border:1px dashed #10312B; width: 80%;"/>
                            <br/>
                        </div>
                    </div>
                </div>
            </div>
            <br/>
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