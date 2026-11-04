@extends('layouts.ly_adminMovil')

@section('title')
    <title>Consulta</title>
@endsection

@section('scripts')
    <style>
        .inputfile{
            width: 0.01px;
            height: 0.01px;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            z-index: -1;
        }

        .tooltip{
            position: relative;
            display: inline-block;
            border-bottom: 1px solid #6F7271; /* If you want dots under the hoverable text */
        }

        .tooltip .tooltiptext{
            visibility: hidden;
            width: 300px;
            background-color: #235B4E;
            color: #fff;
            text-align: center;
            padding: 5px 0;
            border-radius: 6px;

            position: absolute;
            z-index: 1;
        }

        .tooltip:hover .tooltiptext{
            visibility: visible;
        }
    </style>
@endsection

@section('content')
    <div class="ui segment" id="content-page" style="background-color: transparent; text-align: center;">
        <div class="ui grid">
            <div class="sixteen wide column">
                <div style="text-align: center; width: 100%; margin: 0 auto;">
                    <h1 class="ui header" style="color: #10312B; font-size: 230%;">
                        Apertura de Urnas
                    </h1>
                </div>
            </div>
        </div>
        <div id="apertura" style="text-align: center; width: 100%; margin: 0 auto; display: block;">
            <br/>
            <br/>
            <img id="abrirResultados" style="margin: auto; width: 80%; height: 80%;" src="/assets/img/contar.png" class="ui image">
            <center>
                <button style=" font-weight: bold; background-color: #3DAD93; width: 50%; height: 100%; font-size: 200%; color: white; border: none; border-radius: 20px">Abrir</button><br><br>
            </center>
        </div>
        <div id="divMenu" style="display: none;" class="menu">
            <div style="display: none;">
                {{$contador = 1;}}
                {{$contadorVotos = 0;}}
            </div>
            @foreach($boletas as $boleta)
                <hr style="border:1px solid #10312B; width: 90%;"/>
                <br/>
                <div style="text-align: center; width: 100%; margin: 0 auto;">
                    <h1 class="ui header" style="color: #235B4E; font-size: 200%;">
                        {{$boleta->cargo_postulacion}}
                    </h1>
                </div>
                <br/>
                <hr style="border:1px solid #10312B; width: 90%;"/>
                <br/>
                <center>
                    <div id="div{{$contador}}" style="display: none;">
                        <table width="100%">
                            @foreach(BoletaCandidato::where('boleta_id', $boleta->id)->get() as $boletaCandidato)
                                @if(count(Conteo::where('boleta_candidato_id', $boletaCandidato->id)->get()) < $resultadoFin[$contadorVotos])
                                    <tr>
                                        <td width="5%" style="background-color: transparent;">
                                            &nbsp;
                                        </td>
                                        <td style="background-color: transparent;">
                                            <br/>
                                            <span style="font-size: 250%; border-radius: 25%; width: 50px; height: 50px; background-color: {{$boletaCandidato->planilla_color}};">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                            <br/>
                                        </td>
                                        <td style="background-color: transparent; font-size: 100%; color: #10312B">
                                            &nbsp;&nbsp;{{$boletaCandidato->nombre_completo}}&nbsp;&nbsp;
                                        </td>
                                        <td style="background-color: transparent; font-size: 100%; color: #10312B">
                                            &nbsp;&nbsp;{{count(Conteo::where('boleta_candidato_id', $boletaCandidato->id)->get())}}
                                        </td>
                                        <td width="5%" style="background-color: transparent;">
                                            &nbsp;
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: transparent;">
                                            <div style="display: none;">
                                                {{$contador = $contador + 1;}}
                                            </div>
                                        </td>
                                    </tr>
                                @else
                                    <tr style="background-color: #10312B;">
                                        <td width="5%" style="background-color: transparent;">
                                            &nbsp;
                                        </td>
                                        <td style="background-color: #10312B;">
                                            <br/>
                                            <span style="font-size: 250%; border-radius: 25%; width: 50px; height: 50px; background-color: {{$boletaCandidato->planilla_color}};">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                            <br/>
                                        </td>
                                        <td style="background-color: #10312B; font-size: 120%; color: white">
                                            &nbsp;&nbsp;{{$boletaCandidato->nombre_completo}}&nbsp;&nbsp;
                                        </td>
                                        <td style="background-color: #10312B; font-size: 100%; color: white">
                                            &nbsp;&nbsp;{{count(Conteo::where('boleta_candidato_id', $boletaCandidato->id)->get())}}
                                        </td>
                                        <td width="5%" style="background-color: transparent;">
                                            &nbsp;
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="background-color: transparent;">
                                            <div style="display: none;">
                                                {{$contador = $contador + 1;}}
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </table>
                    </div>
                    <div style="background-color: transparent; font-size: 200%; color: #10312B">
                        Votos Nulos: {{count(Conteo::where('boleta_candidato_id', null)->where('boleta_id', $boleta->id)->get())}}  
                    </div>
                </center>
                <br/>
                <center>
                    <hr style="border:1px solid #12322B; width: 60%; margin: 0;"/>
                    <br/>
                    <br/>
                    <div>
                        <h1 class="ui header" style="color: #10312B; font-size: 120%;">
                            - Descargar acta de la boleta -
                        </h1>
                        <br/>
                        <br/>
                        <a href="/consulta/descargaActa/{{$boleta->id}}">
                            <h1 class="ui header" >
                                <i id="descargaDoc" style="color: #3DAD93; font-size: 300%" class="download icon"></i>
                            </h1>
                        </a>
                    </div>
                    <hr style="border:1px solid #12322B; width: 60%; margin: 0;"/>
                    <br/>
                </center>
                <br/>
                <div style="background-color: #235B4E; width: 100%; font-size: 200%; color: white;">
                    <center>
                        <br/>
                        Fin de boleta: {{$boleta->numero_boleta}}
                        <br/>
                        <br/>
                    </center>
                </div>
                <div style="display: none;">
                    {{$contadorVotos = $contadorVotos + 1;}}
                </div>
            @endforeach
            <br/>
            {{Form::open(array('action' => 'VotacionController@cargaActa' ,'class' => 'ui form', 'id' => "register_form", 'enctype' => "multipart/form-data"))}}
                <div>
                    <h1 class="ui header" style="color: #10312B; font-size: 120%;">
                        - Cargar acta firmada de la consulta -
                    </h1>
                    <br/>
                    <center>
                        <table>
                            <tr>
                                <td style="background-color: transparent;">
                                    <div style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;" onmouseover="document.getElementById('pdfSubir').src = '/assets/img/pdfBlanco.png'; document.getElementById('pdfSubirHR').style.border = '1px solid white'; document.getElementById('pdfSubirL').style.color = 'white'; this.style.backgroundColor = '#10312B';" onmouseout="document.getElementById('pdfSubir').src = '/assets/img/pdfVerde.png'; document.getElementById('pdfSubirHR').style.border = '1px solid #10312B'; document.getElementById('pdfSubirL').style.color = '#10312B'; this.style.backgroundColor = 'transparent';">
                                        <label for="fileArchivo1">
                                            <br/>
                                            <img id="pdfSubir" style="margin: auto; width: 45%; height: 45%;" src="/assets/img/pdfVerde.png" class="ui image">
                                            <div style="background-color: transparent; text-align: center;">
                                                <hr id="pdfSubirHR" style="border:1px solid #10312B; width: 50%;"/>
                                                <b id="pdfSubirL" class="ui header" style="color: #10312B; font-size: 100%; font-weight: bolder; cursor: pointer;">
                                                    Subir
                                                </b>
                                            </div>
                                            <div style="text-align: center">
                                                <input type="file" name="ruta_acta" id="fileArchivo1" class="inputfile" onchange="validaDocumento();"/>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            </div>
                                        </label>
                                    </div>
                                </td>
                                <td style="background-color: transparent;">
                                    &nbsp;
                                </td>
                                <td style="background-color: transparent;">
                                    <div class="saveForm" style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;" onmouseover="document.getElementById('guardar').src = '/assets/img/guardar_blanco.png'; document.getElementById('guardarHR').style.border = '1px solid white'; document.getElementById('guardarL').style.color = 'white'; this.style.backgroundColor = '#10312B';" onmouseout="document.getElementById('guardar').src = '/assets/img/guardar.png'; document.getElementById('guardarHR').style.border = '1px solid #10312B'; document.getElementById('guardarL').style.color = '#10312B'; this.style.backgroundColor = 'transparent';">
                                        <br/>
                                        <img id="guardar" style="margin: auto; width: 45%; height: 45%;" src="/assets/img/guardar.png" class="ui image">
                                        <div style="background-color: transparent; text-align: center;">
                                            <hr id="guardarHR" style="border:1px solid #10312B; width: 50%;"/>
                                            <label id="guardarL" class="ui header" style="color: #10312B; font-size: 100%; font-weight: bolder; cursor: pointer;">
                                                Guardar
                                            </label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <div id="verificadorImagen1" style="display: none" style="text-align: center">
                            <br/>
                            <h3 class="ui header" style="color: #235B4E;">
                                <i class="check circle icon"></i>Archivo Cargado
                            </h3>
                        </div>
                    </center>
                </div>
                <br/>
                <div style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;">
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
            {{Form::close()}}
        </div>
    </div>
@endsection

@section('scripts-page')
    <script type="text/javascript">
        $(document).ready(function(){
            var formFunctions = {
                saveForm: function(){
                    $("#register_form").submit();
                },
            };
            $(".saveForm").on("click",formFunctions.saveForm);
            $("button").click(function(){
                $("#apertura").fadeOut(500);
                document.getElementById('divMenu').style.display = 'block';

                for(var x = 1; x < 50; x++){
                    $("#div" + x).fadeIn(3000);
                }
            });
        });

        function validaDocumento(){
            var inputArchivo = document.getElementById("fileArchivo1")
            var formato = validaFormatoArchivo(inputArchivo);
            var tamano = validaTamanoArchivo(inputArchivo);

            if(!(formato && tamano)){
                alert("El archivo ha sido removido por no cumplir con las características necesarías, deber ser en formato: pdf y pesar menos de 4.5 MB")
                inputArchivo.value = "";
            }else{
                document.getElementById('verificadorImagen1').style.display = 'block';
            }
        }
        
        function validaFormatoArchivo(obj){
            var archivo = obj.files[0];
            
            if(archivo != null){
                var extensionesPermitidas = new Array(".pdf");
                var extension = (obj.value.substring(obj.value.lastIndexOf("."))).toLowerCase(); 
                var permitida = false; 
                for (var i = 0; i < extensionesPermitidas.length; i++){
                    if(extensionesPermitidas[i] == extension){
                        permitida = true; 
                        break; 
                    } 
                } 
                if(!permitida) { 
                    return false;
                }else{ 
                    return true;
                } 
            }else{
                alert("No has seleccionado ningún archivo para adjuntar.")
                return false;
            }
        }
        
        function validaTamanoArchivo(obj){
            var archivo = obj.files[0];
            if(archivo != null){
                if(archivo.size > 4500000){
                    return false;
                }
                return true;
            }else{
                return false;
            }
        }
    </script>
@endsection
