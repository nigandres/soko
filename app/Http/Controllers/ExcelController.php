<?php

namespace App\Http\Controllers;

use App\Dato;
use App\User;
use Maatwebsite\Excel\Facades\Excel;
// use Maatwebsite\Excel\Files\ExcelFile;
use Illuminate\Http\Request;

class ExcelController extends Controller
{
    //
    public function impotar()
    {
    	set_time_limit(120);//esto incrementa el tiempo de respuesta
    	Excel::load('Datos.xls', function($reader) {
	    	// $resultado = $reader->all();
	    	// $resultado = $reader->get();
	    	// $resultado = $reader->get(array('numeros'));
	    	// $resultado = $reader->get(array('letras'));
	    	
	    	$resultado = $reader->get(array('id','numeros','letras'));

			// dd($resultado);

	    	$comparador = 0;
	    	for($i=0; $i < count($resultado)-1 ;$i++)
	    	{
		    	$registro_comparador = $resultado[$comparador];
		    	$registro_incremental = $resultado[$i];
	    		if($registro_incremental->id != 'hola')
	    		{
	    			// dd($registro_incremental->letras);
	    		}
	    		elseif($registro_comparador->numeros != $registro_incremental->numeros)
	    		{
	    			$dato = new Dato();
		            $dato->numeros = $registro_comparador->numeros;
		            $dato->letras = $registro_comparador->letras;
		            $dato->save();
	    			$comparador = $i;
	    		}
	    	}
	    	$registro_comparador = $resultado[$comparador];
	    	$registro_incremental = $resultado[count($resultado)-1];
	    	if($registro_incremental->id != 'hola')
    		{
    			if($registro_comparador->numeros != $registro_incremental->numeros)
	    		{
	    			$dato = new Dato();
		            $dato->numeros = $registro_comparador->numeros;
		            $dato->letras = $registro_comparador->letras;
		            $dato->save();
	    		}
    			// dd($registro_comparador->letras);
    			// dd($registro_incremental->letras);
    		}
	    	elseif($registro_comparador->numeros == $registro_incremental->numeros)
    		{
    			$dato = new Dato();
	            $dato->numeros = $registro_comparador->numeros;
	            $dato->letras = $registro_comparador->letras;
	            $dato->save();
    		}
    		elseif($registro_comparador->numeros != $registro_incremental->numeros)
    		{
    			$dato = new Dato();
	            $dato->numeros = $registro_comparador->numeros;
	            $dato->letras = $registro_comparador->letras;
	            $dato->save();
    			$dato = new Dato();
	            $dato->numeros = $registro_incremental->numeros;
	            $dato->letras = $registro_incremental->letras;
	            $dato->save();
    		}

	    	// dd($resultado);
			// se insertan el numero de datos en la tabla datos
	      //   $lim = count($resultado);
	      //   for($i = 0; $i <= $lim-1 ; $i++)
	      //   {
		    	// $algo = $resultado[$i];
		    	// dd($algo->letras);

	      //       $dato = new Dato();
	      //       $dato->numeros = $algo->numeros;
	      //       $dato->letras = $algo->letras;
	      //       $dato->save();
	      //   }
	        
		    	// dd(count($resultado));
		    	// dd($algo->numeros);
		    	// dd($resultado);
    	})->get()/*EL GET PARECE QUE SE PUEDE OMITIR*/;

        // redirecciona a la vista para ver los resultados
        return redirect()->action('DatoController@index');
    }

    public function exportar($tabla)
    {
    	// dd($tabla);
    	$nombres = array("Datos","Users");
    	if($tabla == 'dato')
    	{
    		$nombre = $nombres[0];
	    	$datos = Dato::all();
	    	foreach ($datos as $dato)
	    	{
		        $arreglo[] = array($dato->id,$dato->numeros,$dato->letras);
	    	}
    	}
    	elseif($tabla == 'user')
    	{
    		$nombre = $nombres[1];
    		$users = User::all();
	    	foreach ($users as $user)
	    	{
		        $arreglo[] = array($user->id,$user->nombre,$user->edad,$user->cargo,$user->correo,$user->password);
	    	}
    	}
        Excel::create($nombre, function($excel) use ($arreglo) {

        $excel->sheet('uno', function($sheet) use ($arreglo) {

                // Sheet manipulation
		        // dd($arreglo);
                $sheet->fromArray($arreglo);

            });

        })->export('xls');
    }
}
