<!-- se crea una vista para los datos -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>
                <div class="panel-body">
                    <a href="{{ action('ComentarioController@index') }}" class="btn btn-success">Comentarios</a>
                    <br><br>
                    <form action="{{ action('ComentarioController@store') }}" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <label>Cuerpo</label>
                        <br>
                        <textarea name="cuerpo" cols="100" rows="8"></textarea>
                        <br><br>
                        <input type="hidden" name="comentariable_id" value="{{ $videoId }}">
                        <input type="hidden" name="comentariable_type" value="{{ $videoTipo }}">
                        <center>
                            <input type="submit" class="btn btn-danger" name="enviar" value="Publicar">
                        </center>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
