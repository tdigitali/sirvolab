<!DOCTYPE html>
<html>
    <head>
        <!-- Standard Meta -->
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0">

        <!-- Site Properities -->
        <title>SIRVOLAB</title>
        <link rel="stylesheet" type="text/css" href="/assets/css/semantic.css">
        <link rel="stylesheet" type="text/css" href="/assets/css/app.css">
        <link rel="stylesheet" type="text/css" href="/assets/css/fonts.css"/>
        @yield("css")
            <script src="/assets/js/jquery.min.js"></script>
            <script src="/assets/js/semantic.js"></script>
        @yield("scripts")
    </head>
    <body style="background-image: url('/assets/img/fondo_72.jpg'); background-size: 100% 100%; background-size: cover;">
        <header class="ui menu" style="background-color: #10312B; border-color: #E9E9E9">
            <div style="text-align:center; position: absolute; top: 2%; left: 3%;">
                <a href="/menu/principal">
                    <font style="color: white; font-size: 150%; font-weight: bold;">&nbsp;&nbsp;SIRVOLAB&nbsp;</font>
                </a>
            </div>
            <div class="right menu">
                <a href="/menu/principal">
                    <div class="header item" onmouseover="this.style.backgroundColor = '#6F7271'" onmouseout="this.style.backgroundColor = 'transparent'">
                        <i style="color: white;" class="home icon"></i>
                    </div>
                </a>
                <a href="/logout/">
                    <div class="header item" onmouseover="this.style.backgroundColor = '#6F7271';" onmouseout="this.style.backgroundColor = 'transparent';">
                        <i id="cerrar" style="color: white;" class="user close icon"></i>
                    </div>
                </a>
            </div>
        </header>
        <div id="main-section" class="ui padded grid" style="border: none;">
            <div class="column">
                @yield('content')
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
    @yield('scripts-page')
    <script type="text/javascript">
        $(document).ready(function() {
            var docHeight = $(window).height();
            var footerHeight = $('#footer').height();
            var footerTop = $('#footer').position().top + footerHeight;

            if (footerTop < docHeight) {
                $('#footer').css('margin-top', (docHeight - footerTop) - 0 + 'px');
            }
            $('.ui.accordion').accordion();
            $('.ui.dropdown').dropdown();
        });
    </script>
</html>
