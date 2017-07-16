<!-- se crea una vista para los datos -->
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
                    <a href="{{ action('ExcelController@exportar', 'dato') }}" class="btn btn-danger">exportar todos</a>
                    <a href="{{ action('ExcelController@impotar') }}" class="btn btn-warning">importar</a>
                    <br>
                    <table>
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
                            <td><a href="{{ action('DatoController@exportExcel', [$dato->id]) }}" class="btn btn-default">exportar</a></td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
