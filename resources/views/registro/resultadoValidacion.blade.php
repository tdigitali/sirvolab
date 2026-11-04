@extends('layouts.ly_admin')

@section('title') Confirmación @endsection

@section('content')
    <div class="ui segment" id="content-page" style="background-color: transparent; text-align: center;">
        <div class="ui grid">
            <div class="sixteen wide column">
                <br/>
                <div style="text-align: center; width: 100%; margin: 0 auto;">
                    <h1 class="ui header" style="color: #10312B; font-size: 500%;">
                        Resultado de la validación
                    </h1>
                </div>
            </div>
        </div>
        <br/>
        <br/>
        <br/>
        <div>
            <div style="text-align: center;">
                <h3 class="ui header" style="color: white; background-color: #3DAD93;">
                    <i style="margin-top: 3px;" class="save layout icon"></i>{{$resultado}}
                </h3>
            </div>
            <br/>
            <div style="text-align:center;">
                <div style="background-color: transparent; margin: auto; display: block; width:120px; height:120px; border-radius:120px; border:5px solid #10312B; cursor: pointer;" onmouseover="document.getElementById('regresar').src = '/assets/img/next_blanco.png'; document.getElementById('regresarHR').style.border = '1px solid white'; document.getElementById('regresarL').style.color = 'white'; this.style.backgroundColor = '#10312B';" onmouseout="document.getElementById('regresar').src = '/assets/img/next_verde.png'; document.getElementById('regresarHR').style.border = '1px solid #10312B'; document.getElementById('regresarL').style.color = '#10312B'; this.style.backgroundColor = 'transparent';">
                    <br/>
                    <a href="/validacion/">
                        <img id="regresar" style="margin: auto; width: 45%; height: 45%;" src="/assets/img/next_verde.png" class="ui image">
                        <div style="background-color: transparent; text-align: center;">
                            <hr id="regresarHR" style="border:1px solid #10312B; width: 50%;"/>
                            <label id="regresarL" class="ui header" style="color: #10312B; font-size: 100%; font-weight: bolder; cursor: pointer;">
                                Siguiente
                            </label>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection