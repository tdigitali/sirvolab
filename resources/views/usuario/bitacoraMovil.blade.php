@extends('layouts.ly_adminMovil')

@section('title') Bitácora @endsection

@section('scripts')
    <style>
        tr:hover{
            color: #2B002B; 
        }
    </style>
@endsection

@section('content')
    <div class="ui segment" id="content-page" style="background-color: transparent; text-align: center;">
        <div class="ui grid">
            <div class="sixteen wide column">
                <br/>
                <div style="text-align: center; width: 100%; margin: 0 auto;">
                    <h1 class="ui header" style="color: #10312B; font-size: 250%;">
                        Bitácora de Convocatoria
                    </h1>
                </div>
            </div>
        </div>
        <br/>
        <div class="menu">
            <table style="width: 95%" align="center">
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
                <tr style="font-size: 95%" onmouseover="this.style.background = '#FFF'; this.style.color= '#10312B';" onmouseout="this.style.background = 'gray'; this.style.color= 'grey';">
                    <td>
                        {{$registroBitacora->accion_realizada}}
                    </td>
                    <td>
                        {{$registroBitacora->fecha_accion}}
                    </td>
                    <td>
                        {{Usuario::find($registroBitacora->usuario_id)->nombre}}&nbsp;{{Usuario::find($registroBitacora->usuario_id)->apellido_paterno}}
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection