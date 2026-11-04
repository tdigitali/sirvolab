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
        <style type="text/css">
            body {
                background-image: url('/assets/img/fondo_72.jpg');
            }
            body > .grid{height: 100%;}
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
    <body style="background-size: 100%; background-size: cover;">
        <div class="ui form" style="background-color: transparent;">
            <div class="ui segment" id="content-page" style="background-color: transparent;">
                <div class="ui grid" style="background-color: transparent;">
                    <div class="one wide column">
                        &nbsp;
                    </div>
                    <div class="fourteen wide column">
                        <div style="text-align: center;">
                            <br/>
                            <div style="text-align: center; width: 100%; margin: 0 auto;">
                                <h1 class="ui header" style="color: #10312B; font-size: 130%;">
                                    ¿No tiene acceso a SIRVOLAB?
                                </h1>
                            </div>
                            <br/>
                            <p style="text-align: justify; width: 100%;">
                                <b style="color: #549C9C; font-size: 100%">1.- No sé como ingresar al sistema.</b>
                                <br/>
                                <b style="color: #6F7271; font-size: 100%;">
                                    &nbsp;&nbsp;- En el apartado de usuario ( <i class="user icon"></i>) debe escribir su correo completo, ejemplo:&nbsp;</b>
                                <br/>
                                <b style="color: #549C9C; font-size: 100%">edward.raul@stps.gob.mx</b>
                                <br/>
                                <b style="color: #6F7271; font-size: 100%">
                                    &nbsp;&nbsp;- En el apartado de contraseña ( <i class="lock icon"></i>) debe escribir la que previamente tecleó en la pantalla de cambio de contraseña.
                                </b>
                            </p>
                            <p style="text-align: justify; width: 100%;">
                                <b style="color: #549C9C; font-size: 100%;">2.- ¿Olvidé mi contraseña?</b>
                                <br/>
                                <b style="color: #6F7271; font-size: 100%">En el SIRVOLAB no puede reestablecer su contraseña de manera manual por cuestiones de seguridad, por lo que deberá contactar al administrador, para active su cambio de contraseña.</b>
                                <br/><br/>
                                <b style="color: #549C9C; font-size: 100%">Contacto</b>
                                <br/>
                                <b style="color: #6F7271; font-size: 100%">Edward Raúl García Mejía</b>
                                <br/>
                                <b style="color: #6F7271; font-size: 100%">edward.raul@stps.gob.mx</b>
                                <br/>
                                <b style="color: #6F7271; font-size: 100%">ext. 62599</b>
                                <br/>
                                <b style="color: #6F7271; font-size: 100%">ADMINISTRADOR DEL SISTEMA</b>
                            </p>
                        </div>
                        <br/>
                        <center>
                            <div style="text-align: center; width: 100%; margin: 0 auto;">
                                <img id="convocatorias" style="margin: auto; width: 100%; height: 100%;" src="/assets/img/img_principal.png" class="ui image">
                            </div>
                            <br/><br/>
                            <div style="background-color: transparent; width:100px; height:100px; border-radius:100px; border:5px solid #10312B;">
                                <br/>
                                <a href="/login">
                                    <img id="regresar" style="margin: auto; width: 35%; height: 35%;" src="/assets/img/regresar_verde.png" class="ui image">
                                    <div style="background-color: transparent; text-align: center;">
                                        <hr id="regresarHR" style="border:1px solid #10312B; width: 50%;"/>
                                        <label id="regresarL" class="ui header" style="color: #10312B; font-size: 75%; font-weight: bolder;">
                                            Regresar
                                        </label>
                                    </div>
                                </a>
                            </div>
                        </center>
                    </div>
                </div>
            </div>
        </div>
        <br/>
    </body>
    <footer style="background-color: #10312B; color: white; width: 100%" id="footer">
        <center>
            <br/>
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