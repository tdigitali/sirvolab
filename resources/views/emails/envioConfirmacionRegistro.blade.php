<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<title>Confirmación de registro a SIRVOLAB</title>
	<style type="text/css">
            @media screen and (max-width: 600px) {
                    table[class="container"] {
                            width: 95% !important;
                    }
            }
            #outlook a {padding:0;}
            body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0;}
            .ExternalClass {width:100%;}
            .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;}
            #backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}
            img {outline:none; text-decoration:none; -ms-interpolation-mode: bicubic;}
            a img {border:none;}
            .image_fix {display:block;}
            p {margin: 1em 0;}
            
            h2{
                margin: 0.67em 0;
            }

            h1,
            h2,
            h3,
            h4,
            h5 {
              font-family: 'Lato', 'Helvetica Neue', Arial, Helvetica, sans-serif;
              line-height: 1.2857em;
              margin: calc(2rem -  0.14285em ) 0em 1rem;
              font-weight: bold;
              padding: 0em;
            }

            a {color: #000;}

            @media only screen and (max-device-width: 480px) {

                    a[href^="tel"], a[href^="sms"] {
                            text-decoration: none;
                            color: black; /* or whatever your want */
                            pointer-events: none;
                            cursor: default;
                    }

                    .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
                            text-decoration: default;
                            color: orange !important; /* or whatever your want */
                            pointer-events: auto;
                            cursor: default;
                    }
            }


            @media only screen and (min-device-width: 768px) and (max-device-width: 1024px) {
                    a[href^="tel"], a[href^="sms"] {
                            text-decoration: none;
                            color: blue; /* or whatever your want */
                            pointer-events: none;
                            cursor: default;
                    }

                    .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
                            text-decoration: default;
                            color: orange !important;
                            pointer-events: auto;
                            cursor: default;
                    }
            }

            @media only screen and (-webkit-min-device-pixel-ratio: 2) {
                    /* Put your iPhone 4g styles in here */
            }

            @media only screen and (-webkit-device-pixel-ratio:.75){
                    /* Put CSS for low density (ldpi) Android layouts in here */
            }
            @media only screen and (-webkit-device-pixel-ratio:1){
                    /* Put CSS for medium density (mdpi) Android layouts in here */
            }
            @media only screen and (-webkit-device-pixel-ratio:1.5){
                    /* Put CSS for high density (hdpi) Android layouts in here */
            }
            /* end Android targeting */
            a.link1{

            }
            a.link2{
                    color:#fff;
                    text-decoration:none;
                    font-family:Helvetica, Arial, sans-serif;
                    font-size:16px;
                    color:#fff;border-radius:4px;
            }
            p{
                    color:#555;
                    font-family:Helvetica, Arial, sans-serif;
                    font-size:16px;
                    line-height:160%;
            }
            
            table{  
                color: grey; 
                font-family: 'Roboto Condensed', sans-serif; 
                border-collapse: collapse;
            }
            td, th{ 
                border: 1px solid transparent; 
                height: 30px; 
            }
            th{
                background: #2185D0; 
                font-weight: bold;
                color: white; 
            }
            td{
                background: #FAFAFA;
                text-align: center; 
            }
            tr:nth-child(even) td{ 
                background: #F1F1F1; 
            }  
            tr:nth-child(odd) td{ 
                background: #FEFEFE; 
            }
            tr:hover{
                color: #37C158; 
            }
	</style>

        <script type="colorScheme" class="swatch active">
            {
              "name":"Default",
              "bgBody":"ffffff",
              "link":"fff",
              "color":"555555",
              "bgItem":"ffffff",
              "title":"181818"
            }
        </script>

    </head>
    <body>
        <div style="text-align: left">
            <h3 style="color: #10312B;">Estimado {{$convocatoriaPadron->nombre_completo}},</h3>
        </div>
        <div style="text-align: left">
            <h3 style="color: #235B4E;">El SIRVOLAB te informa que se ha realizado la validación de tu registro, por lo que el día de la consulta debes ingresar al siguiente enlace:
                <br/>
                {{\App\Models\Ambiente::find(1)->nombre}}voto/{{Crypt::encrypt($convocatoriaPadron->id)}}/cad/{{Crypt::encrypt($convocatoriaPadron->cadena_validacion)}}
            </h3>
        </div>
        <div style="text-align: left">
            <h4 style="color: #10312B;">Si considera que es un error por favor contacte al Administrador del Sistema.</h4>
        </div>
        <div style="text-align: left">
            <h3 style="color: #10312B;">Saludos Cordiales.</h3>
        </div>
    </body>
</html>
