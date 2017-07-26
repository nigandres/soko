<!-- se crea una vista para los datos -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <table>
                        <tr>
                            <td width="150">ID</td>
                            <td width="150">TITULO</td>
                            <td width="150">CUERPO</td>
                        </tr>
                        @foreach($posts as $post)
                        <tr>
                            <td width="150">{{ $post->id }}</td>
                            <td width="150">{{ $post->titulo }}</td>
                            <td width="150">{{ $post->cuerpo }}</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
