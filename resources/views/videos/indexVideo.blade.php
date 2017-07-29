<!-- se crea una vista para los datos -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>
                <a href="{{ action('PostController@index') }}" class="btn btn-default">Posts</a>
                <a href="{{ action('ComentarioController@index') }}" class="btn btn-default">Comentarios</a>
                <br>
                <a href="{{ action('VideoController@create') }}" class="btn btn-danger">Crear un nuevo video</a>
                <div class="panel-body">
                    <table class="table">
                        <tr>
                            <td width="150">ID</td>
                            <td width="150">TITULO</td>
                            <td width="150">URL</td>
                            <td width="150">RELACION</td>
                        </tr>
                        @foreach($videos as $video)
                        <tr>
                            <td width="150">{{ $video->id }}</td>
                            <td width="150">{{ $video->titulo }}</td>
                            <td width="150">
                                <a href="{{ $video->url }}">{{ $video->url }}</a>
                                <a href="{{ action('ComentarioController@create', [$video->id,'video']) }}" class="btn btn-info">Comentar video</a>
                            </td>
                            <td width="150">
                                <table class="table">
                                    <tr>
                                        <td>id</td>
                                        <td>tipo</td>
                                        <td>comentario id</td>
                                        <td>cuerpo</td>
                                    </tr>
                                    @foreach($video->comentarios as $comentario)
                                        <tr>
                                            <td>{{ $comentario->comentariable_id }}</td>
                                            <td>{{ $comentario->comentariable_type }}</td>
                                            <td>{{ $comentario->id }}</td>
                                            <td>{{ $comentario->cuerpo }}</td>
                                        </tr>
                                    @endforeach
                                </table>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
