@extends('layouts.ly_adminMovil')

@section('title')
    <title>Padrón</title>
@endsection

@section('content')
    <div class="ui segment" id="content-page" style="background-color: transparent; text-align: center;">
        <div class="ui grid">
            <div class="sixteen wide column">
                <br/>
                <div style="text-align: center; width: 100%; margin: 0 auto;">
                    <h1 class="ui header" style="color: #10312B; font-size: 220%;">
                        Padrón de la convocatoria
                    </h1>
                </div>
                <br/>
                <hr style="border:1px solid #10312B; width: 100%;"/>
                <br/>
                <div style="text-align: center; width: 100%; margin: 0 auto;">
                    <h1 class="ui header" style="color: #235B4E; font-size: 120%;">
                        {{$convocatoria->nombre}}
                    </h1>
                </div>
                <br/>
                <hr style="border:1px solid #10312B; width: 100%;"/>
                <br/>
                <center>
                    <div style="background-color: #235B4E; width: 70%; border-radius: 15px 15px 15px 15px;" onmouseover="this.style.backgroundColor = '#10312B';" onmouseout="this.style.backgroundColor = '#235B4E';">
                        <p style="color: red; font-size: 130%">
                            <a id="creaUsr" href="/convocatoria/cargaPadron/{{Crypt::encrypt($convocatoria->id)}}" style="color: white;">
                                <i id="icono" class="add icon"></i>Cargar padrón
                            </a>
                        </p>
                    </div>
                </center>
            </div>
            <div class="sixteen wide column">
                <div class="ui styled">
                    <div class="content active">
                        <div class="ui styled accordion fluid" style="background-color: transparent;">
                            <div class="content active">
                                <div class="two fields">
                                    <div style="text-align: center;" class="field">
                                        <label style="color: #10312B; font-size: 150%; font-weight: bold;">
                                            Padrón completo de participantes
                                        </label>
                                        <br/>
                                        <br/>
                                        <label style="color: #10312B; font-size: 100%">
                                            En este tipo de de búsqueda sólo se permite la descarga, no hay vista previa.
                                        </label>
                                    </div>
                                    <br/>
                                    <div style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;">
                                        <a href='/convocatoria/descargarTodo/{{Crypt::encrypt($convocatoria->id)}}'>
                                            <br/>
                                            <img id="pdfSubir" style="margin: auto; width: 45%; height: 45%;" src="/assets/img/excel_verde.png" class="ui image">
                                            <div style="background-color: transparent; text-align: center;">
                                                <hr id="pdfSubirHR" style="border:1px solid #10312B; width: 50%;"/>
                                                <b id="pdfSubirL" class="ui header" style="color: #10312B; font-size: 80%; font-weight: bolder; cursor: pointer;">
                                                    Descargar
                                                </b>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <br/>
                                <hr style="border:1px dashed #235B4E; width: 80%;"/>
                                <div style="text-align: center;" class="field">
                                    <br/>
                                    <label style="color: #10312B; font-size: 150%; font-weight: bold;">
                                        Participantes que no se han registrado
                                    </label>
                                    <br/>
                                    <br/>
                                    <label style="color: #10312B; font-size: 100%">
                                        No han realizado ninguna acción de registro.
                                    </label>
                                </div>
                                <br/>
                                <center>
                                    <table align="center" style="width: 60%;">
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
                                            <td style='background-color: transparent;'>&nbsp;&nbsp;</td>
                                            <td style="background-color: transparent;">
                                                <div style="background-color: transparent;">
                                                    <a href='/convocatoria/indexPadronConsulta/{{Crypt::encrypt($convocatoria->id)}}/t/2'>
                                                        <img id="pdfSubir" style="margin: auto; width: 60%; height: 60%;" src="/assets/img/busquedaPadron.png" class="ui image">
                                                        <div style="background-color: transparent; text-align: center;">
                                                            <hr id="pdfSubirHR" style="border:1px solid #10312B; width: 60%;"/>
                                                            <b id="pdfSubirL" class="ui header" style="color: #10312B; font-size: 80%; font-weight: bolder;">
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ver&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                            </b>
                                                        </div>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </center>
                                <hr style="border:1px dashed #235B4E; width: 80%;"/>
                                <div style="text-align: center;" class="field">
                                    <br/>
                                    <label style="color: #10312B; font-size: 150%; font-weight: bold;">
                                        Participantes registrados
                                    </label>
                                    <br/>
                                    <br/>
                                    <label style="color: #10312B; font-size: 100%">
                                        Ya se registraron pero no han sido validados.
                                    </label>
                                </div>
                                <br/>
                                <center>
                                    <table align="center" style="width: 60%;">
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
                                            <td style='background-color: transparent;'>&nbsp;&nbsp;</td>
                                            <td style="background-color: transparent;">
                                                <div style="background-color: transparent;">
                                                    <a href='/convocatoria/indexPadronConsulta/{{Crypt::encrypt($convocatoria->id)}}/t/1'>
                                                        <img id="pdfSubir" style="margin: auto; width: 60%; height: 60%;" src="/assets/img/busquedaPadron.png" class="ui image">
                                                        <div style="background-color: transparent; text-align: center;">
                                                            <hr id="pdfSubirHR" style="border:1px solid #10312B; width: 60%;"/>
                                                            <b id="pdfSubirL" class="ui header" style="color: #10312B; font-size: 80%; font-weight: bolder;">
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ver&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
                                    <label style="color: #10312B; font-size: 150%; font-weight: bold;">
                                        Participantes que fueron rechazados
                                    </label>
                                    <br/>
                                    <br/>
                                    <label style="color: #10312B; font-size: 100%">
                                        Por inconsistencias en su registro los rechazaron y deben editarlo para poder votar.
                                    </label>
                                </div>
                                <br/>
                                <center>
                                    <table align="center" style="width: 60%;">
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
                                            <td style='background-color: transparent;'>&nbsp;&nbsp;</td>
                                            <td style="background-color: transparent;">
                                                <div style="background-color: transparent;">
                                                    <a href='/convocatoria/indexPadronConsulta/{{Crypt::encrypt($convocatoria->id)}}/t/3'>
                                                        <img id="pdfSubir" style="margin: auto; width: 60%; height: 60%;" src="/assets/img/busquedaPadron.png" class="ui image">
                                                        <div style="background-color: transparent; text-align: center;">
                                                            <hr id="pdfSubirHR" style="border:1px solid #10312B; width: 60%;"/>
                                                            <b id="pdfSubirL" class="ui header" style="color: #10312B; font-size: 80%; font-weight: bolder;">
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ver&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
                                    <label style="color: #10312B; font-size: 150%; font-weight: bold;">
                                        Participantes validados
                                    </label>
                                    <br/>
                                    <br/>
                                    <label style="color: #10312B; font-size: 100%">
                                        Ya pueden votar en la consulta.
                                    </label>
                                </div>
                                <br/>
                                <center>
                                    <table align="center" style="width: 60%;">
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
                                            <td style='background-color: transparent;'>&nbsp;&nbsp;</td>
                                            <td style="background-color: transparent;">
                                                <div style="background-color: transparent;">
                                                    <a href='/convocatoria/indexPadronConsulta/{{Crypt::encrypt($convocatoria->id)}}/t/4'>
                                                        <img id="pdfSubir" style="margin: auto; width: 60%; height: 60%;" src="/assets/img/busquedaPadron.png" class="ui image">
                                                        <div style="background-color: transparent; text-align: center;">
                                                            <hr id="pdfSubirHR" style="border:1px solid #10312B; width: 60%;"/>
                                                            <b id="pdfSubirL" class="ui header" style="color: #10312B; font-size: 80%; font-weight: bolder;">
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ver&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
                                    <label style="color: #10312B; font-size: 150%; font-weight: bold;">
                                        Votos realizados durante la consulta
                                    </label>
                                    <br/>
                                    <br/>
                                    <label style="color: #10312B; font-size: 100%">
                                        Listado de participantes que ya votaron.
                                    </label>
                                </div>
                                <br/>
                                <center>
                                    <table align="center" style="width: 60%;">
                                        <tr>
                                            <td style="background-color: transparent;">
                                                <div style="background-color: transparent;">
                                                    <a href='/convocatoria/descargarConsulta/{{Crypt::encrypt($convocatoria->id)}}/t/5'>
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
                                                    <a href='/convocatoria/indexPadronConsulta/{{Crypt::encrypt($convocatoria->id)}}/t/5'>
                                                        <img id="pdfSubir" style="margin: auto; width: 60%; height: 60%;" src="/assets/img/busquedaPadron.png" class="ui image">
                                                        <div style="background-color: transparent; text-align: center;">
                                                            <hr id="pdfSubirHR" style="border:1px solid #10312B; width: 60%;"/>
                                                            <b id="pdfSubirL" class="ui header" style="color: #10312B; font-size: 80%; font-weight: bolder;">
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ver&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
        </div>
    </div>
@endsection