@extends('layouts.ly_admin')

@section('title')
    <title>Convocatorias Cerradas</title>
@endsection

@section('scripts')
    <style>
        tr:hover{
            color: #691C32;
        }
    </style>
    <style>
        .valid {color: #0ea432}
        .invalid {color: #f72d30}
        .error_m{
            font-size: 10px;
            color: red;
        }
    </style>
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
                    <h1 class="ui header" style="color: #10312B; font-size: 400%;">
                        Listado de Convocatorias Cerradas
                    </h1>
                </div>
            </div>
        </div>
        <br/>
        <p class="ui header" style="color: #10312B">
            <i class="find layout icon white"></i>@if(count($convocatorias) == 0) No se encontraron registros @endif @if(count($convocatorias) == 1) Se encontró un registro @endif @if(count($convocatorias) > 1) Se encontraron {{count($convocatorias)}} registros @endif
        </p>
        <div class="menu">
            <table align="center" style="width: 100%;">
                <thead>
                    <th>Nombre de Convocatoria&nbsp;&nbsp;</th>
                    <th>Sindicato&nbsp;&nbsp;</th>
                    <th colspan="4">Acciones</th>
                </thead>
                <tbody>
                    @foreach($convocatorias as $convocatoria)
                        @if($convocatoria->razon_termino == null)
                            <tr>
                                <td>&nbsp;{{$convocatoria->nombre}}&nbsp;&nbsp;</td>
                                <td>&nbsp;{{$convocatoria->nombre_sindicato}}&nbsp;&nbsp;</td>
                                <td>
                                    <a href="/convocatoria/show/{{$convocatoria->id}}" title="Editar">
                                        <p style="font-size: 200%">
                                            <i class="search icon" style="color: #235B4E" onmouseover="this.style.color = '#10312B'" onmouseout="this.style.color = '#235B4E'"></i>&nbsp;
                                        </p>
                                    </a>
                                </td>
                                <td>
                                    <a href="/convocatoria/bitacoraShow/{{$convocatoria->id}}" title="Bitácora">
                                        <p style="font-size: 200%">
                                            <i class="help circle icon" style="color: #235B4E" onmouseover="this.style.color = '#10312B'" onmouseout="this.style.color = '#235B4E'"></i>&nbsp;
                                        </p>
                                    </a>
                                </td>
                                <td>
                                    <a href="/consulta/descargaActaFirmada/{{$convocatoria->id}}/cad/{{\Illuminate\Support\Str::random(10)}}">
                                        <p style="font-size: 200%">
                                            <i class="download icon" style="color: #235B4E" onmouseover="this.style.color = '#10312B'" onmouseout="this.style.color = '#235B4E'" title="Descargar Acta"></i>&nbsp;
                                        </p>
                                    </a>
                                </td>
                                <td>
                                    <a href="/convocatoria/descargarTodo/{{Crypt::encrypt($convocatoria->id)}}">
                                        <p style="font-size: 200%">
                                            <i class="file text icon" style="color: #235B4E" onmouseover="this.style.color = '#10312B'" onmouseout="this.style.color = '#235B4E'" title="Descargar Acta"></i>&nbsp;
                                        </p>
                                    </a>
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td style="background-color: #691C32; color: white; font-weight: bolder;">&nbsp;{{$convocatoria->nombre}}&nbsp;&nbsp;</td>
                                <td style="background-color: #691C32; color: white; font-weight: bolder;">&nbsp;{{$convocatoria->razon_termino}}&nbsp;&nbsp;</td>
                                <td style="background-color: #691C32; color: white; font-weight: bolder;">
                                    <a href="/convocatoria/show/{{$convocatoria->id}}" title="Editar">
                                        <p style="font-size: 200%">
                                            <i class="search icon" style="color: white"></i>&nbsp;
                                        </p>
                                    </a>
                                </td>
                                <td style="background-color: #691C32; color: white; font-weight: bolder;">
                                    <a href="/convocatoria/bitacoraShow/{{$convocatoria->id}}" title="Bitácora">
                                        <p style="font-size: 200%">
                                            <i class="help circle icon" style="color: white"></i>&nbsp;
                                        </p>
                                    </a>
                                </td>
                                <td style="background-color: #691C32; color: white; font-weight: bolder;">
                                    &nbsp;
                                </td>
                                <td style="background-color: #691C32; color: white; font-weight: bolder;">
                                    &nbsp;
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
            <br/>
        </div>
    </div>
@endsection