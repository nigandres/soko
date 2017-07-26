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
                            <td width="250">ID</td>
                            <td width="250">CUERPO</td>
                            <td width="250">COMENTARIO ID</td>
                            <td width="250">COMENTARIO TIPO</td>
                        </tr>
                        @foreach($comentarios as $comentario)
                        <tr>
                            <td width="250">{{ $comentario->id }}</td>
                            <td width="250">{{ $comentario->cuerpo }}</td>
                            <td width="250">{{ $comentario->comentariable_id }}</td>
                            <td width="250">{{ $comentario->comentariable_type }}</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
