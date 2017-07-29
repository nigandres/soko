<!-- se crea una vista para los datos -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>
                <a href="{{ action('VideoController@index') }}" class="btn btn-default">Videos</a>
                <a href="{{ action('ComentarioController@index') }}" class="btn btn-default">Comentarios</a>
                <br>
                <a href="{{ action('PostController@create') }}" class="btn btn-danger">Crear un nuevo post</a>
                <div class="panel-body">
                    <table class="table">
                        <tr>
                            <td width="150">ID</td>
                            <td width="150">TITULO</td>
                            <td width="150">CUERPO</td>
                            <td width="150">RELACION</td>
                        </tr>
                        @foreach($posts as $post)
                        <tr>
                            <td width="150">{{ $post->id }}</td>
                            <td width="150">{{ $post->titulo }}</td>
                            <td width="150">
                                {{ $post->cuerpo }}
                                <a href="{{ action('ComentarioController@create', [$post->id,'post']) }}" class="btn btn-info">Comentar post</a>
                            </td>
                            <td width="150">
                                <table class="table">
                                    <tr>
                                        <td>id</td>
                                        <td>tipo</td>
                                        <td>comentario id</td>
                                        <td>cuerpo</td>
                                    </tr>
                                    @foreach($post->comentarios as $comentario)
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
