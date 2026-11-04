<!DOCTYPE html>
<html>
    <head>
        <!-- Standard Meta -->
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

        <!-- Site Properities -->
        <title>SIRVOLAB</title>
        <link rel="stylesheet" type="text/css" href="/assets/css/semantic.css">
        <link rel="stylesheet" href="/assets/js/pickadate/lib/themes/classic.css"/>
        <link rel="stylesheet" href="/assets/js/pickadate/lib/themes/classic.date.css"/>

        <script src="/assets/js/jquery.min.js"></script>
        <script src="/assets/js/semantic.js"></script>
        <script src="/assets/js/pickadate/lib/picker.js"></script>
        <script src="/assets/js/pickadate/lib/picker.date.js"></script>
        <style>
            .valid {color: #0ea432}
            .invalid {color: #f72d30}
        </style>

        <style type="text/css">
            body {
                background-image: url('/assets/img/fondo_72.jpg');
            }
            body > .grid { height: 100%; }
            .column { max-width: 450px; }
        </style>
    </head>
    <header class="ui menu" style="width: 100%; background-color: #10312B;">
        <div>
            <table style="width: 100%">
                <tr>
                    <td>&nbsp;&nbsp;</td>
                    <td style="background-color: transparent; vertical-align: middle">
                        <img id="convocatorias" style="width: 100%; height: 100%;" src="/assets/img/logo_sirvo_color_verde.png" >
                    </td>
                </tr>
            </table>
        </div>
    </header>
    <body style="background-image: url('/assets/img/fondo_72.jpg'); background-size: 100% 100%; background-size: cover;">
        <div class="ui form">
            <div class="ui segment" id="content-page" style="background-color: transparent;">
                <div class="ui grid">
                    <div class="seven wide column" style="margin: auto;">
                        <br/><br/><br/>
                        <div style="text-align: center; width: 100%; margin: 0 auto;">
                            <img id="convocatorias" style="margin: auto; width: 100%; height: 100%;" src="/assets/img/contrasena.png" class="ui image">
                        </div>
                        <br/>
                    </div>
                    <div class="nine wide column">
                        @if($register)
                            {{Form::open(array('action' => array('AuthController@cambiarContrasenaSave',Crypt::encrypt($register->id)) ,'class' => 'ui form', 'id' => "register_form"))}}
                                <div style="background-color: transparent;">
                                    <br/>
                                    <div style="text-align: center; width: 150%; margin: 0 auto;">
                                        <h1 class="ui header" style="color: #10312B; font-size: 200%;">
                                            Acceso a SIRVOLAB
                                        </h1>
                                    </div>
                                    <br/>
                                    <p style="text-align: center; width: 150%; margin: 0 auto;">
                                        <b style="color: #549C9C; font-size: large">***Verifique sus datos e ingrese su contraseña de acceso.***</b>
                                    </p>
                                    <br/>
                                    <br/>
                                    <br/>
                                    <p style="text-align: justify; width: 150%; margin: 0 auto;">
                                        <b style="color: #10312B; font-size: x-large">Datos de Usuario</b>
                                        <br/>
                                        <hr style="border:1px solid #10312B; width: 100%;"/>
                                        <br/>
                                    </p>
                                    <p style="text-align: justify; width: 150%; margin: 0 auto;">
                                        <b style="color: #549C9C; font-size: medium;">{{$register->nombre}}<br/>{{$register->apellido_paterno}}&nbsp;{{$register->apellido_materno}}</b>
                                        <br/>
                                        <b style="color: #549C9C; font-size: medium;">{{$register->email}}</b>
                                    </p>
                                    <div class="field">
                                        @if(Session::get("message") != null)
                                            <h3 class="ui header" style="color: white; background-color: #3DAD93;">
                                                <i style="margin-top: 3px;" class="remove circle layout icon"></i>{{Session::get("message")}}
                                            </h3>
                                        @endif
                                    </div>
                                    <div class="field">
                                        <label style="color: #235B4E; font-size: large">Contraseña<span style="color: #10312B;">*</span></label>
                                        <label style="color: #235B4E; font-size: small; text-align: justify; width: 150%; margin: 0 auto;">Escriba su contraseña de acceso, debe contener mínimo 8 y máximo 16 caracteres.</label>
                                        {{Form::password('password', null)}}
                                    </div>
                                    <div class="field">
                                        <label style="color: #235B4E; font-size: large">Confirme su Contraseña<span style="color: #10312B;">*</span></label>
                                        <label style="color: #235B4E; font-size: small; text-align: justify; width: 150%; margin: 0 auto;">Eescriba nuevamente su contraseña de acceso, debe contener mínimo 8 y máximo 16 caracteres.</label>
                                        {{Form::password('password_confirmation', null)}}
                                    </div>
                                    <div style="text-align:center;">
                                        <div class="saveForm" style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;" onmouseover="document.getElementById('guardar').src = '/assets/img/guardar_blanco.png'; document.getElementById('guardarHR').style.border = '1px solid white'; document.getElementById('guardarL').style.color = 'white'; this.style.backgroundColor = '#10312B';" onmouseout="document.getElementById('guardar').src = '/assets/img/guardar.png'; document.getElementById('guardarHR').style.border = '1px solid #10312B'; document.getElementById('guardarL').style.color = '#10312B'; this.style.backgroundColor = 'transparent';">
                                            <br/>
                                            <img id="guardar" style="margin: auto; width: 45%; height: 45%;" src="/assets/img/guardar.png" class="ui image">
                                            <div style="background-color: transparent; text-align: center;">
                                                <hr id="guardarHR" style="border:1px solid #10312B; width: 50%;"/>
                                                <label id="guardarL" class="ui header" style="color: #10312B; font-size: 100%; font-weight: bolder; cursor: pointer;">
                                                    Enviar
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br/>
                            {{Form::close()}}
                        @else
                            <div style="background-color: transparent;">
                                <br/>
                                <div style="text-align: center; width: 150%; margin: 0 auto;">
                                    <h1 class="ui header" style="color: #10312B; font-size: 200%;">
                                        Acceso a SIRVOLAB
                                    </h1>
                                </div>
                                <br/>
                                <p style="text-align: justify; width: 150%; margin: 0 auto;">
                                    <b style="color: #549C9C; font-size: large">La contraseña ya ha sido cambiada, verifique con el administrador.</b>
                                </p>
                                <br/>
                                <br/>
                                <div style="text-align: center; width: 150%; margin: 0 auto;">
                                    <h1 class="ui header" style="color: #10312B; font-size: 150%;">
                                       <b>Contacto</b>
                                    </h1>
                                    <hr style="border:1px solid #10312B; width: 70%;"/>
                                </div>
                                <div style="text-align: center; width: 150%; margin: 0 auto;">
                                    <p style="text-align: center;">
                                        <b style="color: #10312B; font-size: medium">Edward Raúl García Mejía</b>
                                        <br/>
                                        <b style="color: #10312B; font-size: medium">edward.raul@stps.gob.mx</b>
                                        <br/>
                                        <b style="color: #10312B; font-size: medium">ext. 62599</b>
                                        <br/>
                                        <b style="color: #10312B; font-size: medium">ADMINISTRADOR DEL SISTEMA</b>
                                    </p>
                                </div>
                                <br/>
                                <div style="text-align: center; width: 150%; margin: 0 auto;">
                                    <div style="text-align: center; background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;" onmouseover="document.getElementById('regresar').src = '/assets/img/regresar_blanco.png'; document.getElementById('regresarHR').style.border = '1px solid white'; document.getElementById('regresarL').style.color = 'white'; this.style.backgroundColor = '#10312B';" onmouseout="document.getElementById('regresar').src = '/assets/img/regresar_verde.png'; document.getElementById('regresarHR').style.border = '1px solid #10312B'; document.getElementById('regresarL').style.color = '#10312B'; this.style.backgroundColor = 'transparent';">
                                        <br/>
                                        <a href="/login/">
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
                                <br/>
                            </div>
                        @endif
                    </div>
                    <div class="one wide column" style="margin: auto;">
                        <div style="text-align: center; width: 100%; margin: 0 auto;">
                            &nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <footer style="background-color: #10312B; color: white;" id="footer">
        <center>
            <br/>
            <p>Blvd. Adolfo López Mateos 1968, Los Alpes, Álvaro Obregón, 01010 Ciudad de México, CDMX, Tel: (55) 3000 2200</p>
            <p>Algunos Derechos Reservados © 2023 - Políticas de Privacidad</p>
            <div style="background-image: url('/assets/img/pleca_verde.png'); background-size: cover;">
                &nbsp;
                <br/>
                &nbsp;
            </div>
        </center>
    </footer>
    <script type="text/javascript">
        $(document).ready(function() {
            var docHeight = $(window).height();
            var footerHeight = $('#footer').height();
            var footerTop = $('#footer').position().top + footerHeight;

            if (footerTop < docHeight) {
                $('#footer').css('margin-top', (docHeight - footerTop) - 28 + 'px');
            }
            var formFunctions = {
                saveForm: function(){
                    $("#register_form").submit();
                },
            };
            $(".saveForm").on("click",formFunctions.saveForm);
        });
    </script>
</html>