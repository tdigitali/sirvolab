@extends('layouts.ly_admin')

@section('title')
    <title>Padrón</title>
@endsection

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
@endsection

@section('content')
    {!!Form::open(array('action' => array('ConvocatoriaController@cargaPadronUpdate',Crypt::encrypt($convocatoria->id)) ,'class' => 'ui form', 'id' => "register_form", 'enctype' => "multipart/form-data"))!!}
        <div class="ui segment" id="content-page" style="background-color: transparent; text-align: center;">
            <div class="ui grid">
                <div class="one wide column">
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
                <div class="fourteen wide column">
                    <br/>
                    <div style="text-align: center; width: 100%; margin: 0 auto;">
                        <h1 class="ui header" style="color: #10312B; font-size: 500%;">
                            Padrón de la convocatoria
                        </h1>
                    </div>
                    <br/>
                    <br/>
                    <hr style="border:1px solid #10312B; width: 100%;"/>
                    <br/>
                    <div style="text-align: center; width: 100%; margin: 0 auto;">
                        <h1 class="ui header" style="color: #235B4E; font-size: 200%;">
                            {{$convocatoria->nombre}}
                        </h1>
                    </div>
                    <br/>
                    <hr style="border:1px solid #10312B; width: 100%;"/>
                </div>
            </div>
            <br/>
            @if(Session::get("message") != null)
                <h3 class="ui header" style="color: white; background-color: #3DAD93;">
                    <i style="margin-top: 3px;" class="save layout icon"></i>{{Session::get("message")}}
                </h3>
            @endif
            <center>
                <table>
                    <tr>
                        <td style="background-color: transparent;">
                            <div style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;" onmouseover="document.getElementById('pdfSubir').src = '/assets/img/xls_blanco.png'; document.getElementById('pdfSubirHR').style.border = '1px solid white'; document.getElementById('pdfSubirL').style.color = 'white'; this.style.backgroundColor = '#10312B';" onmouseout="document.getElementById('pdfSubir').src = '/assets/img/excel_verde.png'; document.getElementById('pdfSubirHR').style.border = '1px solid #10312B'; document.getElementById('pdfSubirL').style.color = '#10312B'; this.style.backgroundColor = 'transparent';">
                                <label for="fileArchivo1">
                                    <br/>
                                    <img id="pdfSubir" style="margin: auto; width: 45%; height: 45%;" src="/assets/img/excel_verde.png" class="ui image">
                                    <div style="background-color: transparent; text-align: center;">
                                        <hr id="pdfSubirHR" style="border:1px solid #10312B; width: 50%;"/>
                                        <b id="pdfSubirL" class="ui header" style="color: #10312B; font-size: 100%; font-weight: bolder; cursor: pointer;">
                                            Subir
                                        </b>
                                    </div>
                                    <div style="text-align: center">
                                        <input type="file" name="ruta_padron" id="fileArchivo1" class="inputfile" onchange="validaDocumento();"/>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </div>
                                </label>
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
            <p class="ui header" style="color: #10312B">
                <i class="find layout icon white"></i>@if(count($convocatoriaPadronesCargado) == 0) No se encontraron registros @endif @if(count($convocatoriaPadronesCargado) == 1) Se encontró un registro @endif @if(count($convocatoriaPadronesCargado) > 1) Se encontraron {{count($convocatoriaPadronesCargado)}} registros @endif
            </p>
            <div class="menu">
                <table align="center" style="width: 50%;">
                    <thead>
                        <th>Fecha de carga del padrón&nbsp;&nbsp;</th>
                        <th>Número de registros cargados&nbsp;&nbsp;</th>
                        <th colspan="2">Acciones</th>
                    </thead>
                    <tbody>
                        @foreach($convocatoriaPadronesCargado as $padron)
                            <tr>
                                <td>&nbsp;{{$padron->fecha_carga}}&nbsp;&nbsp;</td>
                                <td>&nbsp;{{$padron->registros_cargados}}&nbsp;&nbsp;</td>
                                <td>
                                    <a href="/convocatoria/descargaPadron/{{$padron->id}}" title="Editar">
                                        <p style="font-size: 200%">
                                            <i class="download icon" style="color: #235B4E" onmouseover="this.style.color = '#10312B'" onmouseout="this.style.color = '#235B4E'"></i>&nbsp;
                                        </p>
                                    </a>
                                </td>
                                <td>
                                    <a href="/convocatoria/bitacora/{{$convocatoria->id}}" title="Bitácora">
                                        <p style="font-size: 200%">
                                            <i class="help circle icon" style="color: #235B4E" onmouseover="this.style.color = '#10312B'" onmouseout="this.style.color = '#235B4E'"></i>&nbsp;
                                        </p>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <br/>
            </div>
        </div>
    {!!Form::close()!!}
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
        
        function validaDocumento(){
            var inputArchivo = document.getElementById("fileArchivo1")
            var formato = validaFormatoArchivo(inputArchivo);
            var tamano = validaTamanoArchivo(inputArchivo);

            if(!(formato && tamano)){
                alert("El archivo ha sido removido por no cumplir con las características necesarías, deber ser en formato: xls y pesar menos de 1.8 MB")
                inputArchivo.value = "";
            }else{
                document.getElementById('verificadorImagen1').style.display = 'block';
                $("#register_form").submit();
            }
        }
        
        function validaFormatoArchivo(obj){
            var archivo = obj.files[0];
            
            if(archivo != null){
                var extensionesPermitidas = new Array(".xls");
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
                if(archivo.size > 1848426){
                    return false;
                }
                return true;
            }else{
                return false;
            }
        }
    </script>
@endsection
