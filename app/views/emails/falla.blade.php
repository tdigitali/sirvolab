@extends('layouts.ly_admin')

@section('title') Convocatoria @endsection

@section('scripts')
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
    <div class="ui segment" id="content-page" style="background-image: url('/assets/img/back.png'); text-align: center">
        <h1 class="ui header" style="color: grey">
            <i class="archive icon green"></i>Falla
        </h1>
        <h1 class="ui header" style="color: grey">
            {{$e}}<br/>
        </h1>
    </div>
@endsection