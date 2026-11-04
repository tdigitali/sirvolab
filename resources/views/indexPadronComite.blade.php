@extends('layouts.ly_admin')

@section('title')
    <title>Padrón</title>
@endsection

@section('content')
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
                <div style="text-align: center; width: 100%; margin: 0 auto;">
                    <h1 class="ui header" style="color: #235B4E; font-size: 200%;">
                        {{$convocatoria->nombre}}
                    </h1>
                </div>
                <br/>
                <center>
                    <div style="background-color: #235B4E; width: 20%; border-radius: 15px 15px 15px 15px;" onmouseover="this.style.backgroundColor = '#10312B';" onmouseout="this.style.backgroundColor = '#235B4E';">
                        <p style="color: red; font-size: 130%">
                            <a id="creaUsr" href="/convocatoria/cargaPadron/{{Crypt::encrypt($convocatoria->id)}}" style="color: white;">
                                <i id="icono" class="add icon"></i>Cargar padrón
                            </a>
                        </p>
                    </div>
                </center>
                <br/>
                @if(Session::get("message") != null)
                    <h3 class="ui header" style="color: white; background-color: #3DAD93;">
                        <i style="margin-top: 3px;" class="save layout icon"></i>{{Session::get("message")}}
                    </h3>
                @endif
                @if(Session::get("messageError") != null)
                    <h3 class="ui header" style="color: white; background-color: #3DAD93;">
                        <i style="margin-top: 3px;" class="remove circle icon"></i>{{Session::get("messageError")}}
                    </h3>
                @endif
            </div>
            <div class="one wide column">
                &nbsp;
            </div>
            <div class="three wide column">
                &nbsp;
            </div>
            <div class="ten wide column" style="border: 5px solid #10312B;">
                <div class="ui styled">
                    <div class="content active">
                        <div class="ui styled accordion fluid" style="background-color: transparent;">
                            <br/>
                            <div class="content active">
                                <div class="two fields">
                                    <div style="text-align: center;" class="field">
                                        <br/>
                                        <label style="color: #10312B; font-size: 200%; font-weight: bold;">
                                            Todo el padrón
                                        </label>
                                        <br/>
                                        <br/>
                                        <label style="color: #10312B; font-size: 120%">
                                            En este tipo de de búsqueda sólo se permite la descarga, no hay vista previa.
                                        </label>
                                    </div>
                                    <br/>
                                    <div style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;" onmouseover="document.getElementById('pdfSubir').src = '/assets/img/xls_blanco.png'; document.getElementById('pdfSubirHR').style.border = '1px solid white'; document.getElementById('pdfSubirL').style.color = 'white'; this.style.backgroundColor = '#10312B';" onmouseout="document.getElementById('pdfSubir').src = '/assets/img/excel_verde.png'; document.getElementById('pdfSubirHR').style.border = '1px solid #10312B'; document.getElementById('pdfSubirL').style.color = '#10312B'; this.style.backgroundColor = 'transparent';">
                                        <a href='/convocatoria/descargarTodo/{{Crypt::encrypt($convocatoria->id)}}'>
                                            <br/>
                                            <img id="pdfSubir" style="margin: auto; width: 45%; height: 45%;" src="/assets/img/excel_verde.png" class="ui image">
                                            <div style="background-color: transparent; text-align: center;">
                                                <hr id="pdfSubirHR" style="border:1px solid #10312B; width: 50%;"/>
                                                <b id="pdfSubirL" class="ui header" style="color: #10312B; font-size: 90%; font-weight: bolder; cursor: pointer;">
                                                    Descargar
                                                </b>
                                            </div>    
                                        </a>
                                    </div>
                                </div>
                                <br/>
                                <hr style="border:1px dashed #235B4E; width: 60%;"/>
                                <br/>
                                <div style="text-align: center;" class="field">
                                    <br/>
                                    <label style="color: #10312B; font-size: 200%; font-weight: bold;">
                                        Votantes registrados
                                    </label>
                                    <br/>
                                </div>
                                <br/>
                                <center>
                                    <table align="center" style="width: 25%;">
                                        <tr>
                                            <td style="background-color: transparent;">
                                                <div style="background-color: transparent;">
                                                    <a href='/convocatoria/descargarConsulta/{{Crypt::encrypt($convocatoria->id)}}/t/1'>
                                                        <img id="pdfSubir" style="margin: auto; width: 60%; height: 60%;" src="/assets/img/excel_verde.png" class="ui image">
                                                        <div style="background-color: transparent; text-align: center;">
                                                            <hr id="pdfSubirHR" style="border:1px solid #10312B; width: 60%;"/>
                                                            <b id="pdfSubirL" class="ui header" style="color: #10312B; font-size: 80%; font-weight: bolder;">
                                                                Descargar
                                                            </b>
                                                        </div>
                                                    </a>
                                                </div>
                                            </td>
                                            <td style="background-color: transparent;">
                                                <div style="background-color: transparent;">
                                                    <a href='/convocatoria/indexPadronComiteConsulta/1'>
                                                        <img id="pdfSubir" style="margin: auto; width: 60%; height: 60%;" src="/assets/img/busquedaPadron.png" class="ui image">
                                                        <div style="background-color: transparent; text-align: center;">
                                                            <hr id="pdfSubirHR" style="border:1px solid #10312B; width: 60%;"/>
                                                            <b id="pdfSubirL" class="ui header" style="color: #10312B; font-size: 80%; font-weight: bolder;">
                                                                Búsqueda
                                                            </b>
                                                        </div>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </center>
                                <hr style="border:1px dashed #235B4E; width: 60%;"/>
                                <br/>
                                <div style="text-align: center;" class="field">
                                    <br/>
                                    <label style="color: #10312B; font-size: 200%; font-weight: bold;">
                                        Votantes no registrados
                                    </label>
                                    <br/>
                                </div>
                                <br/>
                                <center>
                                    <table align="center" style="width: 25%;">
                                        <tr>
                                            <td style="background-color: transparent;">
                                                <div style="background-color: transparent;">
                                                    <a href='/convocatoria/descargarConsulta/{{Crypt::encrypt($convocatoria->id)}}/t/2'>
                                                        <img id="pdfSubir" style="margin: auto; width: 60%; height: 60%;" src="/assets/img/excel_verde.png" class="ui image">
                                                        <div style="background-color: transparent; text-align: center;">
                                                            <hr id="pdfSubirHR" style="border:1px solid #10312B; width: 60%;"/>
                                                            <b id="pdfSubirL" class="ui header" style="color: #10312B; font-size: 80%; font-weight: bolder;">
                                                                Descargar
                                                            </b>
                                                        </div>
                                                    </a>
                                                </div>
                                            </td>
                                            <td style="background-color: transparent;">
                                                <div style="background-color: transparent;">
                                                    <a href='/convocatoria/indexPadronComiteConsulta/2'>
                                                        <img id="pdfSubir" style="margin: auto; width: 60%; height: 60%;" src="/assets/img/busquedaPadron.png" class="ui image">
                                                        <div style="background-color: transparent; text-align: center;">
                                                            <hr id="pdfSubirHR" style="border:1px solid #10312B; width: 60%;"/>
                                                            <b id="pdfSubirL" class="ui header" style="color: #10312B; font-size: 80%; font-weight: bolder;">
                                                                Búsqueda
                                                            </b>
                                                        </div>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </center>
                                <hr style="border:1px dashed #235B4E; width: 60%;"/>
                                <br/>
                                <div style="text-align: center;" class="field">
                                    <br/>
                                    <label style="color: #10312B; font-size: 200%; font-weight: bold;">
                                        Votantes rechazados
                                    </label>
                                    <br/>
                                </div>
                                <br/>
                                <center>
                                    <table align="center" style="width: 25%;">
                                        <tr>
                                            <td style="background-color: transparent;">
                                                <div style="background-color: transparent;">
                                                    <a href='/convocatoria/descargarConsulta/{{Crypt::encrypt($convocatoria->id)}}/t/3'>
                                                        <img id="pdfSubir" style="margin: auto; width: 60%; height: 60%;" src="/assets/img/excel_verde.png" class="ui image">
                                                        <div style="background-color: transparent; text-align: center;">
                                                            <hr id="pdfSubirHR" style="border:1px solid #10312B; width: 60%;"/>
                                                            <b id="pdfSubirL" class="ui header" style="color: #10312B; font-size: 80%; font-weight: bolder;">
                                                                Descargar
                                                            </b>
                                                        </div>
                                                    </a>
                                                </div>
                                            </td>
                                            <td style="background-color: transparent;">
                                                <div style="background-color: transparent;">
                                                    <a href='/convocatoria/indexPadronComiteConsulta/3'>
                                                        <img id="pdfSubir" style="margin: auto; width: 60%; height: 60%;" src="/assets/img/busquedaPadron.png" class="ui image">
                                                        <div style="background-color: transparent; text-align: center;">
                                                            <hr id="pdfSubirHR" style="border:1px solid #10312B; width: 60%;"/>
                                                            <b id="pdfSubirL" class="ui header" style="color: #10312B; font-size: 80%; font-weight: bolder;">
                                                                Búsqueda
                                                            </b>
                                                        </div>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </center>
                                <hr style="border:1px dashed #235B4E; width: 60%;"/>
                                <br/>
                                <div style="text-align: center;" class="field">
                                    <br/>
                                    <label style="color: #10312B; font-size: 200%; font-weight: bold;">
                                        Votantes validados
                                    </label>
                                    <br/>
                                </div>
                                <br/>
                                <center>
                                    <table align="center" style="width: 25%;">
                                        <tr>
                                            <td style="background-color: transparent;">
                                                <div style="background-color: transparent;">
                                                    <a href='/convocatoria/descargarConsulta/{{Crypt::encrypt($convocatoria->id)}}/t/4'>
                                                        <img id="pdfSubir" style="margin: auto; width: 60%; height: 60%;" src="/assets/img/excel_verde.png" class="ui image">
                                                        <div style="background-color: transparent; text-align: center;">
                                                            <hr id="pdfSubirHR" style="border:1px solid #10312B; width: 60%;"/>
                                                            <b id="pdfSubirL" class="ui header" style="color: #10312B; font-size: 80%; font-weight: bolder;">
                                                                Descargar
                                                            </b>
                                                        </div>
                                                    </a>
                                                </div>
                                            </td>
                                            <td style="background-color: transparent;">
                                                <div style="background-color: transparent;">
                                                    <a href='/convocatoria/indexPadronComiteConsulta/4'>
                                                        <img id="pdfSubir" style="margin: auto; width: 60%; height: 60%;" src="/assets/img/busquedaPadron.png" class="ui image">
                                                        <div style="background-color: transparent; text-align: center;">
                                                            <hr id="pdfSubirHR" style="border:1px solid #10312B; width: 60%;"/>
                                                            <b id="pdfSubirL" class="ui header" style="color: #10312B; font-size: 80%; font-weight: bolder;">
                                                                Búsqueda
                                                            </b>
                                                        </div>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </center>
                                <hr style="border:1px dashed #235B4E; width: 60%;"/>
                                <br/>
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
@endsection