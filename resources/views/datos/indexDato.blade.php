@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                <a href="{{ route('dato.create') }}" class="btn btn-info">Crear un nuevo dato</a>
                    <br>
                    <table border="2">
                        <tr>
                            <td width="50">ID</td>
                            <td width="50">NUMEROS</td>
                            <td width="50">LETRAS</td>
                        </tr>
                        @foreach($datos as $dato)
                        <tr>
                            <td width="50">{{ $dato->id }}</td>
                            <td width="50">{{ $dato->numeros }}</td>
                            <td width="50">{{ $dato->letras }}</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
