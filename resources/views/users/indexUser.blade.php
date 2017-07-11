<!-- se hizo un vista para que se puedan visualizar los usuarios -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <table class="table">
                    <tr class="warning">
                        <td>ID</td>
                        <td>NOMBRE</td>
                        <td>EDAD</td>
                        <td>CARGO</td>
                        <td>CORREO</td>
                        <td>CONTRASEÑA</td>
                    </tr>
                    @foreach($users as $user)
                        <tr class="info">
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->nombre }}</td>
                            <td>{{ $user->edad }}</td>
                            <td>{{ $user->cargo }}</td>
                            <td>{{ $user->correo }}</td>
                            <td>{{ $user->password }}</td>
                        </tr>
                    @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
