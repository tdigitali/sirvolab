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
        <script src="/assets/js/jquery.min.js"></script>
        <script src="/assets/js/semantic.js"></script>
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
                            <img id="convocatorias" style="margin: auto; width: 100%; height: 100%;" src="/assets/img/img_principal.png" class="ui image">
                        </div>
                    </div>
                    <div class="nine wide column">
                        <div style="background-color: transparent; text-align: center;">
                            <br/>
                            <div style="text-align: center; width: 150%; margin: 0 auto;">
                                <h1 class="ui header" style="color: #10312B; font-size: 200%;">
                                    ¿No tiene acceso a SIRVOLAB?
                                </h1>
                            </div>
                            <br/>
                            <p style="text-align: justify; width: 150%; margin: 0 auto;">
                                <b style="color: #549C9C; font-size: x-large">1.- No sé como ingresar al sistema.</b>
                                <br/>
                                <b style="color: #6F7271; font-size: medium;">
                                    - En el apartado de usuario ( <i class="user icon"></i>) debe escribir su correo completo, ejemplo:&nbsp;</b><b style="color: #549C9C; font-size: medium">edward.raul@stps.gob.mx</b>
                                <br/>
                                <b style="color: #6F7271; font-size: medium">
                                    - En el apartado de contraseña ( <i class="lock icon"></i>) debe escribir la que previamente tecleó en la pantalla de cambio de contraseña.
                                </b>
                            </p>
                            <br/>
                            <p style="text-align: justify; width: 150%; margin: 0 auto;">
                                <b style="color: #549C9C; font-size: x-large">2.- ¿Olvidé mi contraseña?</b>
                                <br/>
                                <br/>
                                <b style="color: #6F7271; font-size: medium">En el SIRVOLAB no puede reestablecer su contraseña de manera manual por cuestiones de seguridad, por lo que deberá contactar al administrador, para active su cambio de contraseña.</b>
                            </p>
                            <br/>
                            <p style="text-align: justify; width: 150%; margin: 0 auto;">
                                <b style="color: #549C9C; font-size: x-large">Contacto</b>
                                <br/>
                                <b style="color: #6F7271; font-size: medium">Edward Raúl García Mejía</b>
                                <br/>
                                <b style="color: #6F7271; font-size: medium">edward.raul@stps.gob.mx</b>
                                <br/>
                                <b style="color: #6F7271; font-size: medium">ext. 62599</b>
                                <br/>
                                <b style="color: #6F7271; font-size: medium">ADMINISTRADOR DEL SISTEMA</b>
                            </p>
                            <br/>
                            <center>
                                <div style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;" onmouseover="document.getElementById('regresar').src = '/assets/img/regresar_blanco.png'; document.getElementById('regresarHR').style.border = '1px solid white'; document.getElementById('regresarL').style.color = 'white'; this.style.backgroundColor = '#10312B';" onmouseout="document.getElementById('regresar').src = '/assets/img/regresar_verde.png'; document.getElementById('regresarHR').style.border = '1px solid #10312B'; document.getElementById('regresarL').style.color = '#10312B'; this.style.backgroundColor = 'transparent';">
                                    <br/>
                                    <a href="/login">
                                        <img id="regresar" style="margin: auto; width: 45%; height: 45%;" src="/assets/img/regresar_verde.png" class="ui image">
                                        <div style="background-color: transparent; text-align: center;">
                                            <hr id="regresarHR" style="border:1px solid #10312B; width: 50%;"/>
                                            <label id="regresarL" class="ui header" style="color: #10312B; font-size: 100%; font-weight: bolder; cursor: pointer;">
                                                Regresar
                                            </label>
                                        </div>
                                    </a>
                                </div>
                            </center>
                            <br/>
                        </div>
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
    <script>
            $(document).ready(function() {
                var docHeight = $(window).height();
                var footerHeight = $('#footer').height();
                var footerTop = $('#footer').position().top + footerHeight;
                
                if (footerTop < docHeight) {
                    $('#footer').css('margin-top', (docHeight - footerTop) - 0 + 'px');
                }
            });
        </script>
</html>