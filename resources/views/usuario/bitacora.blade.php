@extends('layouts.ly_admin')

@section('title') Usuarios @endsection

@section('scripts')
    <style>
        .valid {color: #0ea432}
        .invalid {color: #f72d30}
        .error_m{
            font-size: 10px;
            color: red;
        }
        tr:hover{
            color: #2B002B; 
        }
    </style>
@endsection

@section('content')
    <div class="ui segment" id="content-page" style="background-color: transparent; text-align: center;">
        <div class="ui grid">
            <div class="one wide column">
                <div style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;" onmouseover="document.getElementById('regresar').src = '/assets/img/regresar_blanco.png'; document.getElementById('regresarHR').style.border = '1px solid white'; document.getElementById('regresarL').style.color = 'white'; this.style.backgroundColor = '#10312B';" onmouseout="document.getElementById('regresar').src = '/assets/img/regresar_verde.png'; document.getElementById('regresarHR').style.border = '1px solid #10312B'; document.getElementById('regresarL').style.color = '#10312B'; this.style.backgroundColor = 'transparent';">
                    <br/>
                    <a href="/usuario/index/">
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
                    <h1 class="ui header" style="color: #10312B; font-size: 500%;">
                        Bitácora de Usuario
                    </h1>
                </div>
            </div>
        </div>
        <br/>
        <table style="width: 70%" align="center">
            <thead>
                <th>
                    &nbsp;Acción realizada&nbsp;
                </th>
                <th>
                    &nbsp;Fecha de acción&nbsp;
                </th>
                <th>
                    &nbsp;Realizada por&nbsp;
                </th>
            </thead>
            @foreach($register as $registroBitacora)
            <tr onmouseover="this.style.background = '#FFF'; this.style.color= '#691C32';" onmouseout="this.style.background = 'gray'; this.style.color= 'grey';">
                <td>
                    {{$registroBitacora->accion_realizada}}
                </td>
                <td>
                    {{$registroBitacora->fecha_accion}}
                </td>
                <td>
                    {{\App\Models\Usuario::find($registroBitacora->usuario_id)->nombre}}&nbsp;{{\App\Models\Usuario::find($registroBitacora->usuario_id)->apellido_paterno}}
                </td>
            </tr>
            @endforeach
        </table>
    </div>
@endsection