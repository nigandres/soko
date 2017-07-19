<!-- se crea una vista para los datos importados -->
@extends('layouts.app')
<!-- esto va la carpeta layout y selecciona el tema para vista -->

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Personas</div>

                <div class="panel-body">
                <a href="" class="btn btn-info">Crear una nueva persona</a>
                    <br>
                    <a href="{{ action('ExcelController@exportar') }}" class="btn btn-danger">exportar todos</a>
                    <a href="{{ action('ExcelController@importar') }}" class="btn btn-warning">importar</a>
                    <br>
                    <table>
                        <tr>
                            <td width="170">ID</td>
                            <td width="170">NOMBRE</td>
                            <td width="170">CODIGO</td>
                            <td width="170">ADSCRIPCION NOMINAL</td>
                            <!-- <td width="170">ADSCRIPCION FISICA</td> -->
                            <td width="170">NOMBRAMIENTO</td>
                            <td width="170">PLANTEL</td>
                        </tr>
                        @foreach($personas as $persona)
                        <tr>
                            <td width="170">{{ $persona->id }}</td>
                            <td width="170">{{ $persona->nombre }}</td>
                            <td width="170">{{ $persona->codigo }}</td>
                            <td width="170">{{ $persona->adscripcion_nominal }}</td>
                            <!-- <td width="170">{{ $persona->adscripcion_fisica }}</td> -->
                            <td width="170">{{ $persona->nombramiento }}</td>
                            <td width="170">{{ $persona->plantel }}</td>
                            <td><a href="{{ action('PersonaController@show', [$persona->id]) }}" class="btn btn-default">exportar</a></td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
