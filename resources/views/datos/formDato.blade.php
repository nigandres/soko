<script type="text/javascript">
    function agregar()
    {
        valor = document.getElementById("hidden").value;
        valor++;
        spanNum = "spanNum";
        spanLet = "spanLet";
        nume = "num";
        letr = "let";
        br = "br";

        //crear elemento
        var elemento = document.createElement("span");
        //crear nodo de texto
        var contenido = document.createTextNode("numeros");
        //añadir nodo de texto al elemento
        elemento.appendChild(contenido);
        //agregar atributos al elemento
        elemento.setAttribute('id', spanNum.concat(valor));
        //agregar elemneto al documento
        document.getElementById("padre").appendChild(elemento);

        //crear elemento
        var elemento = document.createElement("input");
        //agregar atributos al elemento
        elemento.setAttribute('type', "text");
        elemento.setAttribute('name', nume.concat(valor));
        elemento.setAttribute('id', nume.concat(valor));
        //agregar elemneto al documento
        document.getElementById("padre").appendChild(elemento);

        //crear elemento
        var elemento = document.createElement("span");
        //crear nodo de texto
        var contenido = document.createTextNode("letras");
        //añadir nodo de texto al elemento
        elemento.appendChild(contenido);
        //agregar atributos al elemento
        elemento.setAttribute('id', spanLet.concat(valor));
        //agregar elemneto al documento
        document.getElementById("padre").appendChild(elemento);

        //crear elemento
        var elemento = document.createElement("input");
        //agregar atributos al elemento
        elemento.setAttribute('type', "text");
        elemento.setAttribute('name', letr.concat(valor));
        elemento.setAttribute('id', letr.concat(valor));
        //agregar elemneto al documento
        document.getElementById("padre").appendChild(elemento);

        //crear elemento
        var elemento = document.createElement("br");
        //agregar atributos al elemento
        elemento.setAttribute('id', br.concat(valor));
        //agregar elemneto al documento
        document.getElementById("padre").appendChild(elemento);

        hidden = document.getElementById("hidden");
        hidden.setAttribute("value",valor);
        document.getElementById("delete").removeAttribute("disabled");
    }

    function eliminar()
    {
        valor = document.getElementById("hidden").value;
        spanNum = "spanNum";
        spanLet = "spanLet";
        nume = "num";
        letr = "let";
        br = "br";
        if(valor == 0)
        {
            alert("Nel Perro");
            document.getElementById("delete").setAttribute("disabled","true");
        }
        else
        {
            var padre = document.getElementById("padre");
            var hijo = document.getElementById(spanNum.concat(valor)); 
            padre.removeChild(hijo);

            var hijo = document.getElementById(spanLet.concat(valor)); 
            padre.removeChild(hijo);

            var hijo = document.getElementById(nume.concat(valor)); 
            padre.removeChild(hijo);

            var hijo = document.getElementById(letr.concat(valor)); 
            padre.removeChild(hijo);

            var hijo = document.getElementById(br.concat(valor)); 
            padre.removeChild(hijo);

            valor--;
            hidden = document.getElementById("hidden");
            hidden.setAttribute("value",valor);
        }
    }
</script>
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                <body onload="agregar()"></body>
                <center>
                    <a href="{{ action('DatoController@index') }}" class="btn btn-default">Regresar</a>
                    <br>
                    <input type="button" value="agregar articulo" onclick="agregar()">
                    <input type="button" value="eliminar articulo" id="delete" onclick="eliminar()">
                    {!! Form::open(['route' => 'dato.store']) !!}
                    <div id="padre"></div><!-- con esto se anclan los DOM -->
                    <input type="hidden" value="-1" id="hidden" name="hidden">
                    <input type="submit" value="dale">
                    <!-- {!! Form::label('num', 'NUMEROS') !!}
                    <br>
                    {!! Form::text('num', null) !!}
                    <br>
                    {!! Form::label('let', 'LETRAS') !!}
                    <br>
                    {!! Form::text('let', null) !!}
                    <br>
                    {!! Form::submit('aceptar') !!} -->
                    {!! Form::close() !!}
                </center>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
