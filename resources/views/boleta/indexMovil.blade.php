@extends('layouts.ly_adminMovil')

@section('title')
    <title>Boletas</title>
@endsection

@section('scripts')
    <style>
        tr:hover{
            color: #10312B;
        }
    </style>
@endsection

@section('content')
    <div class="ui segment" id="content-page" style="background-color: transparent; text-align: center;">
        <div class="ui grid">
            <div class="sixteen wide column">
                <br/>
                <div style="text-align: center; width: 100%; margin: 0 auto;">
                    <h1 class="ui header" style="color: #10312B; font-size: 220%;">
                        Listado de Boletas
                    </h1>
                </div>
            </div>
        </div>
        <br/>
        <center>
            <div style="background-color: #235B4E; width: 70%; border-radius: 15px 15px 15px 15px;" onmouseover="this.style.backgroundColor = '#10312B';" onmouseout="this.style.backgroundColor = '#235B4E';">
                <p style="color: red; font-size: 130%">
                    <a id="creaUsr" href="/boleta/create/" style="color: white;">
                        <i id="icono" class="add icon"></i>Agregar boleta
                    </a>
                </p>
            </div>
        </center>
        @if(Session::get("message") != null)
            <h3 class="ui header" style="color: white; background-color: #3DAD93;">
                <i style="margin-top: 3px;" class="save layout icon"></i>{{Session::get("message")}}
            </h3>
        @endif
        @if(Session::get("mensaje") != null)
            <h3 class="ui header" style="color: white; background-color: #3DAD93;">
                <i style="margin-top: 3px;" class="remove circle layout icon"></i>{{Session::get("mensaje")}}
            </h3>
        @endif
        <p class="ui header" style="color: #10312B">
            <i class="find layout icon white"></i>@if(count($boletas) == 0) No se encontraron registros @endif @if(count($boletas) == 1) Se encontró un registro @endif @if(count($boletas) > 1) Se encontraron {{count($boletas)}} registros @endif
        </p>
        <div class="menu">
            <table align="center" style="width: 100%;">
                <thead>
                    <th>Cargo a Postular&nbsp;&nbsp;</th>
                    <th colspan="2">Participantes asignados&nbsp;&nbsp;</th>
                </thead>
                <tbody>
                    @foreach($boletas as $boleta)
                        <tr>
                            <td>&nbsp;{{$boleta->cargo_postulacion}}&nbsp;&nbsp;</td>
                            <td colspan="2" style="text-align: left;">
                                @foreach(BoletaCandidato::where('boleta_id', $boleta->id)->get() as $candidato)
                                    <span style="border-radius: 20px; background-color: {{$candidato->planilla_color}};">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>&nbsp;<span>{{$candidato->nombre_completo}}</span><br/>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td>
                                @if(Convocatoria::find($boleta->convocatoria_id)->aprobacion_confirmacion_contrato)
                                    &nbsp;
                                @else
                                    <a href="/boleta/edit/{{$boleta->id}}" title="Editar">
                                        <p style="font-size: 200%">
                                            <i class="edit icon" style="color: #235B4E" onmouseover="this.style.color = '#10312B'" onmouseout="this.style.color = '#235B4E'"></i>&nbsp;
                                        </p>
                                    </a>
                                @endif
                            </td>
                            <td>
                                @if(Convocatoria::find($boleta->convocatoria_id)->aprobacion_confirmacion_contrato)
                                    &nbsp;
                                @else
                                    <a href="/boleta/asignarCandidato/{{$boleta->id}}" title="Bitácora">
                                        <p style="font-size: 200%">
                                            <i class="user icon" style="color: #235B4E" onmouseover="this.style.color = '#10312B'" onmouseout="this.style.color = '#235B4E'"></i>&nbsp;
                                        </p>
                                    </a>
                                @endif
                            </td>
                            <td>
                                <a href="/boleta/eliminaBoleta/{{$boleta->id}}" onclick="return confirm('¿Desea eliminar la boleta de convocatoria?')" title="Eliminar boleta">
                                    <p style="font-size: 200%">
                                        <i class="remove circle icon" style="color: #235B4E" onmouseover="this.style.color = '#10312B'" onmouseout="this.style.color = '#235B4E'"></i>&nbsp;
                                    </p>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection