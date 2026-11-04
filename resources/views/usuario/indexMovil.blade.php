@extends('layouts.ly_adminMovil')

@section('title')
    <title>Usuarios</title>
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
                    <h1 class="ui header" style="color: #10312B; font-size: 250%;">
                        Listado de Usuarios
                    </h1>
                </div>
            </div>
        </div>
        <br/>
        <center>
            <div style="background-color: #235B4E; width: 70%; border-radius: 15px 15px 15px 15px;" onmouseover="this.style.backgroundColor = '#10312B';" onmouseout="this.style.backgroundColor = '#235B4E';">
                <p style="color: red; font-size: 130%">
                    <a id="creaUsr" href="/usuario/create/" style="color: white;">
                        <i id="icono" class="add icon"></i>Agregar usuario
                    </a>
                </p>
            </div>
        </center>
        @if(Session::get("message") != null)
            <h3 class="ui header" style="color: white; background-color: #3DAD93;">
                <i style="margin-top: 3px;" class="save layout icon"></i>{{Session::get("message")}}
            </h3>
        @endif
        <p class="ui header" style="color: #10312B">
            <i class="find layout icon white"></i>@if(count($usuarios) == 0) No se encontraron registros @endif @if(count($usuarios) == 1) Se encontró un registro @endif @if(count($usuarios) > 1) Se encontraron {{count($usuarios)}} registros @endif
        </p>
        <div class="menu">
            <table align="center" style="width: 100%;">
                <thead>
                    <th>Nombre Completo&nbsp;&nbsp;</th>
                    <th colspan="2">Acciones</th>
                </thead>
                <tbody>
                    @foreach($usuarios as $usuario)
                        <tr style="font-size: 95%">
                            <td>&nbsp;{{$usuario->nombre}}<br/>{{$usuario->apellido_paterno}}&nbsp;{{$usuario->apellido_materno}}&nbsp;&nbsp;</td>
                            <td>
                                <a href="/usuario/edit/{{$usuario->id}}" title="Editar">
                                    <p style="font-size: 200%">
                                        <i class="edit icon" style="color: #235B4E" onmouseover="this.style.color = '#10312B'" onmouseout="this.style.color = '#235B4E'"></i>&nbsp;
                                    </p>
                                </a>
                            </td>
                            <td>
                                <a href="/usuario/bitacora/{{$usuario->id}}" title="Bitácora">
                                    <p style="font-size: 200%">
                                        <i class="help circle icon" style="color: #235B4E" onmouseover="this.style.color = '#10312B'" onmouseout="this.style.color = '#235B4E'"></i>&nbsp;
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