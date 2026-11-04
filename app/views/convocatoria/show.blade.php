@extends('layouts.ly_admin')

@section('content')
    {{Form::open(array('action' => array('ConvocatoriaController@update',Crypt::encrypt($register->id)) ,'class' => 'ui form', 'id' => "register_form"))}}
        <div class="ui segment" id="content-page" style="background-color: transparent;">
            <div class="sixteen wide column">
                <div style="text-align: center; width: 100%; margin: 0 auto;">
                    <h1 class="ui header" style="color: #10312B; font-size: 500%;">
                        Mostrar Convocatoria
                    </h1>
                </div>
            </div>
            <br/>
            <div class="ui grid">
                <div class="three wide column">                
                    <div style="text-align:center;">
                        <div style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;" onmouseover="document.getElementById('regresar').src = '/assets/img/regresar_blanco.png'; document.getElementById('regresarHR').style.border = '1px solid white'; document.getElementById('regresarL').style.color = 'white'; this.style.backgroundColor = '#10312B';" onmouseout="document.getElementById('regresar').src = '/assets/img/regresar_verde.png'; document.getElementById('regresarHR').style.border = '1px solid #10312B'; document.getElementById('regresarL').style.color = '#10312B'; this.style.backgroundColor = 'transparent';">
                            <br/>
                            <a href="/convocatoria/indexCerradas/">
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
                </div>
                <div class="thirteen wide column">
                    <div class="ui styled">
                        <div class="content active">
                            <br/>
                            <div class="content active">
                                <div class="two fields">
                                    <div class="field">
                                        <label style="color: #235B4E; font-size: large">Convocatoria Activa {{Form::checkbox('convocatoria_activa', null, $register->convocatoria_activa,['class' => 'ui checkbox'])}}</label>
                                        <label style="color: #235B4E; font-size: small">Marque la casilla para activar la convocatoria</label>
                                    </div>
                                </div>
                                <hr style="border:1px solid #10312B; width: 80%;"/>
                                <br/>
                                <div class="two fields">
                                    <div class="field">
                                        <label style="color: #235B4E; font-size: large">Nombre del sindicato<span style="color: #691C32;">*</span></label>
                                        <label style="color: #235B4E; font-size: small">Máximo 200 caracteres</label>
                                        {{ Form::text('nombre_sindicato', $register->nombre_sindicato, ['style' => 'color: grey', 'maxlength' => '200']) }}<br/>
                                    </div>
                                    <div class="field">
                                        <label style="color: #235B4E; font-size: large">Nombre de la convocatoria<span style="color: #691C32;">*</span></label>
                                        <label style="color: #235B4E; font-size: small">Máximo 300 caracteres</label>
                                        {{ Form::text('nombre', $register->nombre, ['style' => 'color: grey', 'maxlength' => '300']) }}<br/>
                                    </div>
                                </div>
                                <br/>
                                <div class="two fields">
                                    <div class="field">
                                        <label style="color: #235B4E; font-size: medium">Fecha de inicio de registro y validación<span style="color: #691C32;">*</span></label>
                                        <label style="color: #235B4E; font-size: small">Seleccione la fecha</label>
                                        {{Form::text('fecha_registro_validacion_inicio', substr($register->fecha_registro_validacion_inicio, 0,10), ['id' => 'fecha_registro_validacion_inicio', 'class' => 'datepicker-post', 'style' => 'color: grey'])}}<br/>
                                    </div>
                                    <div class="field">
                                        <label style="color: #235B4E; font-size: medium">Determina la hora en que iniciará el registro y validación<span style="color: #691C32;">*</span></label>
                                        <label style="color: #235B4E; font-size: small">Seleccione la hora</label>
                                        <table>
                                            <tr>
                                                <td style="background-color: transparent;">
                                                    {{Form::select('fecha_registro_validacion_inicio_hora', array('00' => '00', '01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05'
                                                    , '06' => '06', '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11'
                                                            , '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17'
                                                                    , '18' => '18', '19' => '19', '20' => '20', '21' => '21', '22' => '22', '23' => '23'), substr($register->fecha_registro_validacion_inicio, 11, 2));}}
                                                </td>
                                                <td style="background-color: transparent;">
                                                    {{Form::select('fecha_registro_validacion_inicio_minu', array('00' => '00', '01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05'
                                                    , '06' => '06', '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11'
                                                            , '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17'
                                                                    , '18' => '18', '19' => '19', '20' => '20', '21' => '21', '22' => '22', '23' => '23'
                                                                            , '24' => '24', '25' => '25', '26' => '26', '27' => '27', '28' => '28', '29' => '29'
                                                                                    , '30' => '30', '31' => '31', '32' => '32', '33' => '33', '34' => '34', '35' => '35'
                                                                                            , '36' => '36', '37' => '37', '38' => '38', '39' => '39', '40' => '40', '41' => '41'
                                                                                                    , '42' => '42', '43' => '43', '44' => '44', '45' => '45', '46' => '46', '47' => '47'
                                                                                                            , '48' => '48', '49' => '49', '50' => '50', '51' => '51', '52' => '52', '53' => '53'
                                                                                                                    , '54' => '54', '55' => '55', '56' => '56', '57' => '57', '58' => '58', '59' => '59'), substr($register->fecha_registro_validacion_inicio, 14, 2));}}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <br/>
                                <div class="two fields">
                                    <div class="field">
                                        <label style="color: #235B4E; font-size: medium">Fecha de fin de registro y validación<span style="color: #691C32;">*</span></label>
                                        <label style="color: #235B4E; font-size: small">Seleccione la fecha</label>
                                        {{Form::text('fecha_registro_validacion_fin', substr($register->fecha_registro_validacion_fin, 0,10), ['id' => 'fecha_registro_validacion_fin', 'class' => 'datepicker-post', 'style' => 'color: grey'])}}<br/>
                                    </div>
                                    <div class="field">
                                        <label style="color: #235B4E; font-size: medium">Determina la hora en que terminará el registro y validación<span style="color: #691C32;">*</span></label>
                                        <label style="color: #235B4E; font-size: small">Seleccione la hora</label>
                                        <table>
                                            <tr>
                                                <td style="background-color: transparent;">
                                                    {{Form::select('fecha_registro_validacion_fin_hora', array('00' => '00', '01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05'
                                                    , '06' => '06', '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11'
                                                            , '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17'
                                                                    , '18' => '18', '19' => '19', '20' => '20', '21' => '21', '22' => '22', '23' => '23'), substr($register->fecha_registro_validacion_fin, 11, 2));}}
                                                </td>
                                                <td style="background-color: transparent;">
                                                    {{Form::select('fecha_registro_validacion_fin_minu', array('00' => '00', '01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05'
                                                    , '06' => '06', '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11'
                                                            , '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17'
                                                                    , '18' => '18', '19' => '19', '20' => '20', '21' => '21', '22' => '22', '23' => '23'
                                                                            , '24' => '24', '25' => '25', '26' => '26', '27' => '27', '28' => '28', '29' => '29'
                                                                                    , '30' => '30', '31' => '31', '32' => '32', '33' => '33', '34' => '34', '35' => '35'
                                                                                            , '36' => '36', '37' => '37', '38' => '38', '39' => '39', '40' => '40', '41' => '41'
                                                                                                    , '42' => '42', '43' => '43', '44' => '44', '45' => '45', '46' => '46', '47' => '47'
                                                                                                            , '48' => '48', '49' => '49', '50' => '50', '51' => '51', '52' => '52', '53' => '53'
                                                                                                                    , '54' => '54', '55' => '55', '56' => '56', '57' => '57', '58' => '58', '59' => '59'), substr($register->fecha_registro_validacion_fin, 14, 2));}}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="two fields">
                                    <div class="field">
                                        <label style="color: #235B4E; font-size: medium">Fecha de inicio de la consulta<span style="color: #691C32;">*</span></label>
                                        <label style="color: #235B4E; font-size: small">Seleccione la fecha</label>
                                        {{Form::text('fecha_consulta_inicio', substr($register->fecha_consulta_inicio, 0,10), ['id' => 'fecha_consulta_inicio', 'class' => 'datepicker-post', 'style' => 'color: grey'])}}<br/>
                                    </div>
                                    <div class="field">
                                        <label style="color: #235B4E; font-size: medium">Determina la hora en que iniciará la consulta<span style="color: #691C32;">*</span></label>
                                        <label style="color: #235B4E; font-size: small">Seleccione la hora</label>
                                        <table>
                                            <tr>
                                                <td style="background-color: transparent;">
                                                    {{Form::select('fecha_consulta_inicio_hora', array('00' => '00', '01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05'
                                                    , '06' => '06', '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11'
                                                            , '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17'
                                                                    , '18' => '18', '19' => '19', '20' => '20', '21' => '21', '22' => '22', '23' => '23'), substr($register->fecha_consulta_inicio, 11, 2));}}
                                                </td>
                                                <td style="background-color: transparent;">
                                                    {{Form::select('fecha_consulta_inicio_minu', array('00' => '00', '01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05'
                                                    , '06' => '06', '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11'
                                                            , '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17'
                                                                    , '18' => '18', '19' => '19', '20' => '20', '21' => '21', '22' => '22', '23' => '23'
                                                                            , '24' => '24', '25' => '25', '26' => '26', '27' => '27', '28' => '28', '29' => '29'
                                                                                    , '30' => '30', '31' => '31', '32' => '32', '33' => '33', '34' => '34', '35' => '35'
                                                                                            , '36' => '36', '37' => '37', '38' => '38', '39' => '39', '40' => '40', '41' => '41'
                                                                                                    , '42' => '42', '43' => '43', '44' => '44', '45' => '45', '46' => '46', '47' => '47'
                                                                                                            , '48' => '48', '49' => '49', '50' => '50', '51' => '51', '52' => '52', '53' => '53'
                                                                                                                    , '54' => '54', '55' => '55', '56' => '56', '57' => '57', '58' => '58', '59' => '59'), substr($register->fecha_consulta_inicio, 14, 2));}}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <br/>
                                <div class="two fields">
                                    <div class="field">
                                        <label style="color: #235B4E; font-size: medium">Fecha de fin de la consulta<span style="color: #691C32;">*</span></label>
                                        <label style="color: #235B4E; font-size: small">Seleccione la fecha</label>
                                        {{Form::text('fecha_consulta_fin', substr($register->fecha_consulta_fin, 0,10), ['id' => 'fecha_consulta_fin', 'class' => 'datepicker-post', 'style' => 'color: grey'])}}<br/>
                                    </div>
                                    <div class="field">
                                        <label style="color: #235B4E; font-size: medium">Determina la hora en que terminará la consulta<span style="color: #691C32;">*</span></label>
                                        <label style="color: #235B4E; font-size: small">Seleccione la hora</label>
                                        <table>
                                            <tr>
                                                <td style="background-color: transparent;">
                                                    {{Form::select('fecha_consulta_fin_hora', array('00' => '00', '01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05'
                                                    , '06' => '06', '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11'
                                                            , '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17'
                                                                    , '18' => '18', '19' => '19', '20' => '20', '21' => '21', '22' => '22', '23' => '23'), substr($register->fecha_consulta_fin, 11, 2));}}
                                                </td>
                                                <td style="background-color: transparent;">
                                                    {{Form::select('fecha_consulta_fin_minu', array('00' => '00', '01' => '01', '02' => '02', '03' => '03', '04' => '04', '05' => '05'
                                                    , '06' => '06', '07' => '07', '08' => '08', '09' => '09', '10' => '10', '11' => '11'
                                                            , '12' => '12', '13' => '13', '14' => '14', '15' => '15', '16' => '16', '17' => '17'
                                                                    , '18' => '18', '19' => '19', '20' => '20', '21' => '21', '22' => '22', '23' => '23'
                                                                            , '24' => '24', '25' => '25', '26' => '26', '27' => '27', '28' => '28', '29' => '29'
                                                                                    , '30' => '30', '31' => '31', '32' => '32', '33' => '33', '34' => '34', '35' => '35'
                                                                                            , '36' => '36', '37' => '37', '38' => '38', '39' => '39', '40' => '40', '41' => '41'
                                                                                                    , '42' => '42', '43' => '43', '44' => '44', '45' => '45', '46' => '46', '47' => '47'
                                                                                                            , '48' => '48', '49' => '49', '50' => '50', '51' => '51', '52' => '52', '53' => '53'
                                                                                                                    , '54' => '54', '55' => '55', '56' => '56', '57' => '57', '58' => '58', '59' => '59'), substr($register->fecha_consulta_fin, 14, 2));}}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <br/>
                                <div class="two fields">
                                    <div class="field">
                                        <label style="color: #235B4E; font-size: medium">Fecha de publicación de resultados<span style="color: #691C32;">*</span></label>
                                        <label style="color: #235B4E; font-size: small">Seleccione la fecha</label>
                                        {{Form::text('fecha_publicacion_resultados', substr($register->fecha_publicacion_resultados, 0,10), ['id' => 'fecha_publicacion_resultados', 'class' => 'datepicker-post', 'style' => 'color: grey'])}}<br/>
                                    </div>
                                    <div class="field">
                                        <label style="color: #235B4E; font-size: medium">Número mínimo de usuarios que realizarán autorizaciones<span style="color: #691C32;">*</span></label>
                                        <label style="color: #235B4E; font-size: small">Ingrese sólo números</label>
                                        {{Form::number('numero_autorizadores_minimo', $register->numero_autorizadores_minimo, ['style' => 'color: grey', 'max' => '10', 'min' => '1'])}}<br/>
                                    </div>
                                </div>
                                <br/>
                                <hr style="border:1px solid #10312B; width: 80%;"/>
                                <br/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{ Form::close() }}   
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
            $('.datepicker').pickadate({
                monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
                today: 'Hoy',
                clear: 'Limpiar',
                close: 'Cerrar',
                selectMonths: true,
                selectYears: 100,
                format: 'yyyy-mm-dd',
                max: new Date()
            });
            $('.datepicker-post').pickadate({
                monthsFull: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                weekdaysShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
                today: 'Hoy',
                clear: 'Limpiar',
                close: 'Cerrar',
                selectMonths: true,
                selectYears: 100,
                format: 'yyyy-mm-dd'
            });
        });
    </script>
@endsection
