@extends('layouts.ly_registro')

@section('scripts')
    <style>
        .inputfile {
            width: 0.01px;
            height: 0.01px;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            z-index: -1;
        }
        /* Tooltip container */
        .tooltip {
          position: relative;
          display: inline-block;
          border-bottom: 1px solid #6F7271; /* If you want dots under the hoverable text */
        }

        /* Tooltip text */
        .tooltip .tooltiptext {
          visibility: hidden;
          width: 300px;
          background-color: #235B4E;
          color: #fff;
          text-align: center;
          padding: 5px 0;
          border-radius: 6px;

          /* Position the tooltip text - see examples below! */
          position: absolute;
          z-index: 1;
        }

        /* Show the tooltip text when you mouse over the tooltip container */
        .tooltip:hover .tooltiptext {
          visibility: visible;
        }
    </style>
    <script type="text/javascript">
        window.onload = function(){
            var inputCE = document.getElementById('correo_electronico');
            var inputCE2 = document.getElementById('correo_electronico_2');
            
            inputCE.onpaste = function(e){
              e.preventDefault();
              alert("No puedes pegar dentro del campo");
            }

            inputCE2.onpaste = function(e){
              e.preventDefault();
              alert("No puedes pegar dentro del campo");
            }
        }    
    </script>
@endsection

@section('content')
    {{Form::open(array('action' => array('RegistroValidacionController@registrarseUpdate',Crypt::encrypt($convocatoriaPadron->id)) ,'class' => 'ui form', 'id' => "register_form", 'enctype' => "multipart/form-data"))}}
        <div class="ui segment" id="content-page" style="background-color: transparent;">
            <div class="sixteen wide column">
                <div style="text-align: center; width: 51%; margin: 0 auto;">
                    <h1 class="ui header" style="color: #10312B; font-size: 500%;">
                        Registrarse
                    </h1>
                     <h1 class="ui header" style="color: #235B4E; font-size: 120%;">
                        {{Convocatoria::find($convocatoriaPadron->convocatoria_id)->nombre}}
                    </h1>
                </div>
            </div>
            <br/>
            <div class="ui grid">
                <div class="three wide column">
                    <div style="text-align:center;">
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
                    </div>
                </div>
                <div class="thirteen wide column">
                    <div class="ui styled">
                        <div class="content active">
                            <br/>
                            @if($mensaje != null)
                                <center>
                                    <h3 class="ui header" style="color: white; background-color: #3DAD93;">
                                        <i style="margin-top: 3px;" class="remove circle layout icon"></i>{{$mensaje}}
                                    </h3>
                                </center>
                                <br/>
                            @endif
                            <div class="content active">
                                <div class="two fields">
                                    <div class="field">
                                        <label style="color: #10312B; font-size: large">CURP</label>
                                        <label style="color: #10312B; font-size: small">{{$convocatoriaPadron->curp}}</label>
                                    </div>
                                    <div class="field">
                                        <label style="color: #10312B; font-size: large">Nombre completo</label>
                                        <label style="color: #10312B; font-size: small">{{$convocatoriaPadron->nombre_completo}}</label>
                                    </div>
                                </div>
                                <div class="two fields">
                                    <div class="field">
                                        <label style="color: #235B4E; font-size: large">Correo electrónico<span style="color: #691C32;">*</span></label>
                                        <label style="color: #235B4E; font-size: small">Escriba su correo de contacto</label>
                                        {{Form::text('correo_electronico', $correoElectronico, ['id' => 'correo_electronico', 'style' => 'color: grey', 'maxlength' => '60'])}}<br/>
                                    </div>
                                    <div class="field">
                                        <label style="color: #235B4E; font-size: large">Compruebe su correo electrónico<span style="color: #691C32;">*</span></label>
                                        <label style="color: #235B4E; font-size: small">Escriba nuevamente su correo de contacto</label>
                                        {{Form::text('correo_electronico_2', $correoElectronico2, ['id' => 'correo_electronico_2', 'style' => 'color: grey', 'maxlength' => '60'])}}<br/>
                                    </div>
                                </div>
                                <hr style="border:1px solid #10312B; width: 80%;"/>
                                <br/>
                                <div class="field">
                                    <div id="imagen1">
                                        <label style="color: #10312B; font-size: large; font-weight: bold;">Adjuntar imágenes</label>
                                        <br/>
                                        <label style="color: #10312B; font-size: small; font-weight: bold;">Los archivos no deben pesar más de 4.5MB y sólo pueden ser imágenes con formato: jpg, gif, png, bmp, tiff.</label>
                                        <br/>
                                        <br/>
                                        <table style="width: 100%">
                                             <thead>
                                                <th>&nbsp;&nbsp;INE Frente</th>
                                                <th>&nbsp;&nbsp;INE Reverso</th>
                                                <th>&nbsp;&nbsp;Selfie con INE y CURP visible</th>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style="background-color: transparent;">
                                                        <br/>
                                                        <div style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;" onmouseover="document.getElementById('imgSubir').src = '/assets/img/jpg.png'; document.getElementById('imgSubirHR').style.border = '1px solid white'; document.getElementById('imgSubirL').style.color = 'white'; this.style.backgroundColor = '#10312B';" onmouseout="document.getElementById('imgSubir').src = '/assets/img/imagen_verdeClaro.png'; document.getElementById('imgSubirHR').style.border = '1px solid #10312B'; document.getElementById('imgSubirL').style.color = '#10312B'; this.style.backgroundColor = 'transparent';">
                                                            <label for="fileImagen1">
                                                                <br/>
                                                                <img id="imgSubir" style="margin: auto; width: 45%; height: 45%;" src="/assets/img/imagen_verdeClaro.png" class="ui image">
                                                                <div style="background-color: transparent; text-align: center;">
                                                                    <hr id="imgSubirHR" style="border:1px solid #10312B; width: 50%;"/>
                                                                    <b id="imgSubirL" class="ui header" style="color: #10312B; font-size: 100%; font-weight: bolder; cursor: pointer;">
                                                                        Subir
                                                                    </b>
                                                                </div>
                                                                <div style="text-align: center">
                                                                    <input type="file" name="memoria_documental_ruta_1" id="fileImagen1" class="inputfile" onchange="validaImagen(1);"/>
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td style="background-color: transparent;">
                                                        <br/>
                                                        <div style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;" onmouseover="document.getElementById('imgSubir2').src = '/assets/img/jpg.png'; document.getElementById('imgSubirHR2').style.border = '1px solid white'; document.getElementById('imgSubirL2').style.color = 'white'; this.style.backgroundColor = '#10312B';" onmouseout="document.getElementById('imgSubir2').src = '/assets/img/imagen_verdeClaro.png'; document.getElementById('imgSubirHR2').style.border = '1px solid #10312B'; document.getElementById('imgSubirL2').style.color = '#10312B'; this.style.backgroundColor = 'transparent';">
                                                            <label for="fileImagen2">
                                                                <br/>
                                                                <img id="imgSubir2" style="margin: auto; width: 45%; height: 45%;" src="/assets/img/imagen_verdeClaro.png" class="ui image">
                                                                <div style="background-color: transparent; text-align: center;">
                                                                    <hr id="imgSubirHR2" style="border:1px solid #10312B; width: 50%;"/>
                                                                    <b id="imgSubirL2" class="ui header" style="color: #10312B; font-size: 100%; font-weight: bolder; cursor: pointer;">
                                                                        Subir
                                                                    </b>
                                                                </div>
                                                                <div style="text-align: center">
                                                                    <input type="file" name="memoria_documental_ruta_2" id="fileImagen2" class="inputfile" onchange="validaImagen(2);"/>
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </td>
                                                    <td style="background-color: transparent;">
                                                        <br/>
                                                        <div style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;" onmouseover="document.getElementById('imgSubir3').src = '/assets/img/jpg.png'; document.getElementById('imgSubirHR3').style.border = '1px solid white'; document.getElementById('imgSubirL3').style.color = 'white'; this.style.backgroundColor = '#10312B';" onmouseout="document.getElementById('imgSubir3').src = '/assets/img/imagen_verdeClaro.png'; document.getElementById('imgSubirHR3').style.border = '1px solid #10312B'; document.getElementById('imgSubirL3').style.color = '#10312B'; this.style.backgroundColor = 'transparent';">
                                                            <label for="fileImagen3">
                                                                <br/>
                                                                <img id="imgSubir3" style="margin: auto; width: 45%; height: 45%;" src="/assets/img/imagen_verdeClaro.png" class="ui image">
                                                                <div style="background-color: transparent; text-align: center;">
                                                                    <hr id="imgSubirHR3" style="border:1px solid #10312B; width: 50%;"/>
                                                                    <b id="imgSubirL3" class="ui header" style="color: #10312B; font-size: 100%; font-weight: bolder; cursor: pointer;">
                                                                        Subir
                                                                    </b>
                                                                </div>
                                                                <div style="text-align: center">
                                                                    <input type="file" name="memoria_documental_ruta_3" id="fileImagen3" class="inputfile" onchange="validaImagen(3);"/>
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="background-color: transparent;">
                                                        <div style="text-align: center">
                                                            <h3 id="verificadorImagen1" class="ui header" style="color: #235B4E; display: none;">
                                                                <i class="check circle icon"></i>Imagen Cargada
                                                            </h3>
                                                        </div>
                                                    </td>
                                                    <td style="background-color: transparent;">
                                                        <div style="text-align: center">
                                                            <h3 id="verificadorImagen2" class="ui header" style="color: #235B4E; display: none;">
                                                                <i class="check circle icon"></i>Imagen Cargada
                                                            </h3>
                                                        </div>
                                                    </td>
                                                    <td style="background-color: transparent;">
                                                        <div style="text-align: center">
                                                            <h3 id="verificadorImagen3" class="ui header" style="color: #235B4E; display: none;">
                                                                <i class="check circle icon"></i>Imagen Cargada
                                                            </h3>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="background-color: transparent;">
                                                        <div style="text-align: center">
                                                            <h5 id="eliminaImagen1" class="ui header" style="text-decoration: underline; color: #691C32; display: none; cursor: pointer;" onclick="remueveImagen(1);">
                                                                Eliminar
                                                            </h5>
                                                        </div>
                                                    </td>
                                                    <td style="background-color: transparent;">
                                                        <div style="text-align: center">
                                                            <h5 id="eliminaImagen2" class="ui header" style="text-decoration: underline; color: #691C32; display: none; cursor: pointer;" onclick="remueveImagen(2);">
                                                                Eliminar
                                                            </h5>
                                                        </div>
                                                    </td>
                                                    <td style="background-color: transparent;">
                                                        <div style="text-align: center">
                                                            <h5 id="eliminaImagen3" class="ui header" style="text-decoration: underline; color: #691C32; display: none; cursor: pointer;" onclick="remueveImagen(3);">
                                                                Eliminar
                                                            </h5>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
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
        
        function validaImagen(id){
            var inputImagen = document.getElementById("fileImagen" + id)
            var formato = validaFormatoImagen(inputImagen);
            var tamano = validaTamanoImagen(inputImagen);

            if(!(formato && tamano)){
                alert("El archivo ha sido removido por no cumplir con las características necesarías, deber ser en formato: jpeg, jpg, gif, png, tiff, bmp  y pesar menos de 4.5MB")
                inputImagen.value = "";
                document.getElementById('verificadorImagen' + id).style.display = 'none';
                document.getElementById('eliminaImagen' + id).style.display = 'none';
            }else{
                document.getElementById('verificadorImagen' + id).style.display = 'block';
                document.getElementById('eliminaImagen' + id).style.display = 'block';
            }
        }

        function validaFormatoImagen(obj){
            var archivo = obj.files[0];
            
            if(archivo != null){
                var extensionesPermitidas = new Array(".jpeg",".jpg",".gif",".png",".tiff",".bmp");
                var extension = (obj.value.substring(obj.value.lastIndexOf("."))).toLowerCase(); 
                var permitida = false; 
                for (var i = 0; i < extensionesPermitidas.length; i++){
                    if(extensionesPermitidas[i] == extension){
                        permitida = true; 
                        break; 
                    } 
                } 
                if(!permitida) { 
                    //alert("Comprueba la extensión del archivo. \nSólo se pueden subir archivos con extensiones: jpg, jpeg, png, bmp, gif");
                    return false;
                }else{ 
                    return true;
                } 
            }else{
                alert("No has seleccionado ningún archivo para adjuntar.")
                return false;
            }
        }
        
        function validaTamanoImagen(obj){
            var archivo = obj.files[0];
            if(archivo != null){
                if(archivo.size > 4500000){
                    //alert("No puedes subir el archivo debido a que el tamaño excede el permitido: 1.8MB");
                    return false;
                }
                return true;
            }else{
                return false;
            }
        }

        function remueveImagen(id){
            document.getElementById("fileImagen" + id).value = "";
            document.getElementById('verificadorImagen' + id).style.display = 'none';
            document.getElementById('eliminaImagen' + id).style.display = 'none';
        }
    </script>
@endsection
