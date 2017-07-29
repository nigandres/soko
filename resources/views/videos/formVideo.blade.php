<!-- se crea una vista para los datos -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>
                <div class="panel-body">
                    <a href="{{ action('VideoController@index') }}" class="btn btn-success">Videos</a>
                    <br><br>
                    <form action="{{ action('VideoController@store') }}" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <label>Titulo</label>
                        <br>
                        <input type="text" name="titulo">
                        <br>
                        <label>Url</label>
                        <br>
                        <input type="text" size="70" name="url">
                        <br><br>
                        <center>
                            <input type="submit" class="btn btn-danger" name="enviar" value="Subir">
                        </center>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
