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
            body > .grid { height: 100%; }
        </style>
    </head>
    <header class="ui menu" style="width: 100%; background-color: #10312B;">
        <div>
            <table style="width: 100%">
                <tr>
                    <td>&nbsp;&nbsp;</td>
                    <td style="background-color: transparent; vertical-align: middle">
                        <img id="convocatorias" style="width: 100%; height: 70%;" src="/assets/img/logo_sirvo_color_verde.png" class="ui image">
                    </td>
                </tr>
            </table>
        </div>
    </header>
    <body style="background-size: 100%; background-size: cover;">
        <div class="ui form">
            <div class="ui segment" id="content-page" style="background-color: transparent;">
                <br/><br/>
                <div style="text-align: center;">
                    <img id="convocatorias" style="margin: auto; width: 80%; height: 80%;" src="/assets/img/logo_sirvo_color.png" class="ui image">
                </div>
                <div class="ui grid">
                    <div class="one wide column">
                        &nbsp;
                    </div>
                    <div class="fourteen wide column">
                        {!!Form::open(['url' => '/login/', 'class' => 'ui large form', 'id' => "auth-form"])!!}
                            <br/><br/>
                            <center>
                                @if(Session::get("message") != null)
                                    <h3 class="ui header" style="color: #691C32">
                                        <i class="remove circle layout icon"></i>{{Session::get("message")}}
                                    </h3>
                                    <br/>
                                @endif
                            </center>
                            <div class="ui stacked segment" style="background-color: transparent;">
                                <div class="field">
                                    <div class="ui left icon input">
                                        <i class="user icon"></i>
                                        <input type="text" name="user" style="color: grey" placeholder="Usuario">
                                    </div>
                                </div>
                                <div class="field">
                                    <div class="ui left icon input">
                                        <i class="lock icon"></i>
                                        <input type="password" name="passwords" style="color: grey" placeholder="Contraseña">
                                    </div>
                                </div>
                                <center>
                                    <div style="background-color: #549C9C; width: 30%" class="ui fluid medium youtube submit button">Iniciar</div>
                                </center>
                            </div>
                            <div class="ui stacked segment" style="background-color: transparent;">
                                <a href="/registrarse/">
                                    <div style="background-color: #10312B" class="ui fluid large youtube submit button">¿No tiene acceso a SIRVOLAB?</div>
                                </a>
                            </div>
							<div class="ui stacked segment" style="background-color: transparent;">
                                <a href="/aviso/{{\Illuminate\Support\Str::random(10)}}">
                                    <div style="background-color: #1E5B4F" class="ui fluid large youtube submit button">Descargar Aviso de Privacidad</div>
                                </a>
                            </div>
                        {!!Form::close()!!}
                    </div>
                    <div class="one wide column" style="margin: auto;">
                        <div style="text-align: center; width: 100%;">
                            &nbsp;
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                
                $('#auth-form').form({
                    fields: {
                        user: {
                            identifier  : 'user',
                            rules: [{
                                type   : 'empty',
                                prompt : 'Por favor ingrese su correo electrónico'
                            }]
                        },
                        password: {
                            identifier  : 'password',
                            rules: [{
                                type   : 'empty',
                                prompt : 'Por favor ingrese su contraseña'
                            }]
                        }
                    }
                });
            });
        </script>
</html>