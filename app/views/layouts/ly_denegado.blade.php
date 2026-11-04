<!DOCTYPE html>
<html>
    <head>
        <!-- Standard Meta -->
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">

        <!-- Site Properities -->
        <title>SIHGECID</title>
        <link rel="stylesheet" type="text/css" href="/assets/css/semantic.css">
        <link rel="stylesheet" type="text/css" href="/assets/css/app.css">
        <link rel="stylesheet" type="text/css" href="/assets/css/fonts.css"/>
        <style type="text/css">
            .redMenu{
              padding: 1em;
              color: #FFF;
              background: #A22C2C !important;
              text-align: left !important;
              cursor: pointer;
              text-align: center !important;
              border-bottom: 1px solid #fff;
            }
            .redMenu:hover{
              opacity: 0.8;
              color: #FFF !important;
            }
            .redMenu a{
              color: #FFF !important;
            }
        </style>
        @yield("css")
        <script src="/assets/js/jquery.min.js"></script>
        <script src="/assets/js/semantic.js"></script>
        @yield("scripts")
    </head>
    <body style="background-color: #E9E9E9;">
        <header class="ui menu" style="background-color: #235B4E; border-color: #E9E9E9">
            <div style="background-color: #E9E9E9; text-align: center;">
                <img src="/assets/img/amexcid.png" style="width: 50%;"/>
            </div>
            <div style="text-align:center; position: absolute; top: .5%; left: 20%;">
                <b style="color: white; font-size: 150%;">&nbsp;&nbsp;SIHGECID</b>
                <br/>
                <b style="color: white; font-size: 75%;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>Sistema de Herramientas de Gestión de CID</i></b>
            </div>
        </header>
        <div id="main-section" class="ui padded grid" style="border: none;">
            <div class="column">
                @yield('content')
            </div>
        </div>
        <footer style="background-color: #10312B" id="footer">
            <p>Plaza Juárez #20, Col. Centro, Del. Cuauhtémoc. Distrito Federal C.P. 06010, Tel: (55) 3686 - 5100</p>
            <p>Algunos Derechos Reservados © 2015 - Políticas de Privacidad</p>
        </footer>
        
        @yield('scripts-page')
        <script type="text/javascript">
            $(document).ready(function() {
                var docHeight = $(window).height();
                var footerHeight = $('#footer').height();
                var footerTop = $('#footer').position().top + footerHeight;

                if (footerTop < docHeight) {
                    $('#footer').css('margin-top', (docHeight - footerTop) - 28 + 'px');
                }
                $('.ui.dropdown').dropdown();
            });
        </script>
    </body>
</html>
