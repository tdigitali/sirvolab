@extends('layouts.ly_admin')

@section('title')Administración @endsection

@section('css')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.11/css/jquery.dataTables.min.css"/>
    <style type="text/css">
    	.button.margin{
    		margin: 1em;
    		float: right;
    	}
    </style>
@endsection


@section('content')
	<div class="ui segment" id="content-page">
        <h3 class="ui header">
            <i class="configure icon"></i>
            <div class="content">
             	Administración 
            </div>
        </h3>
        {!! Form::open(array('url' => '/admin/executeRowQuery/', 'class' => 'ui form', 'id' => "register_form")) !!}
        <div class="field">
            <label>Password</label>
            <input type="text" name="password">
        </div>  
        <div class="field">
            <label>Query</label>
            <textarea name="query"></textarea>
        </div>
        <input type="submit" value="Guardar" class="ui button green">
        {!!Form::close()!!}
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="//cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript">
    $(document).on("ready",function(){
		$('#indexTable').DataTable({"language": {
            "url": "/assets/js/Spanish.json",
            "deferRender": true
        }});
	});
    </script>
@endsection