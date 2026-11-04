@extends('layouts.ly_admin')

@section('title')
    <title>Usuarios</title>
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
                    <h1 class="ui header" style="color: #10312B; font-size: 500%;">
                        Listado de Usuarios
                    </h1>
                </div>
            </div>
        </div>
        <br/>
        <center>
            <div style="background-color: #235B4E; width: 20%; border-radius: 15px 15px 15px 15px;" onmouseover="this.style.backgroundColor = '#10312B';" onmouseout="this.style.backgroundColor = '#235B4E';">
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
                    <th>Rol de Usuario&nbsp;&nbsp;</th>
                    <th>Usuario Habilitado?&nbsp;&nbsp;</th>
                    <th colspan="2">Acciones</th>
                </thead>
                <tbody>
                    @foreach($usuarios as $usuario)
                        <tr>
                            <td>&nbsp;{{$usuario->nombre}}&nbsp;{{$usuario->apellido_paterno}}&nbsp;{{$usuario->apellido_materno}}&nbsp;&nbsp;</td>
                            <td>&nbsp;{{\App\Models\Rol::find($usuario->rol_id)->nombre}}&nbsp;&nbsp;</td>
                            @if($usuario->enabled == true)
                                <td>Si&nbsp;&nbsp;</td>
                            @else($usuario->enabled == false)
                                <td>No&nbsp;&nbsp;</td>
                            @endif
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