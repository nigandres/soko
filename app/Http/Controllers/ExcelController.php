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
    private $cola;
    public function impotar()
    {
    	set_time_limit(120);//esto incrementa el tiempo de respuesta
    	$datos = Dato::all();
    	if(count($datos) == 0)
    	{
	    	Excel::load('Datos.xls', function($reader) {
		    	// $importacion = $reader->all();
		    	// $importacion = $reader->get();
		    	// $importacion = $reader->get(array('numeros'));
		    	$importacion = $reader->get(array('id','numeros','letras'));
		    	$this->excelNuevo($importacion);
	    	})->get()/*EL GET PARECE QUE SE PUEDE OMITIR*/;
    	}
    	else
    	{
    		Excel::load('Datos.xls', function($reader) use ($datos) {
		    	$importacion = $reader->get(array('id','numeros','letras'));
		    	// dd(count($importacion));
		    	$this->excelActualizar($importacion,$datos);
	    	})->get()/*EL GET PARECE QUE SE PUEDE OMITIR*/;

			for($i=0;$i < count($this->cola);$i++)
			{
				$dato = new Dato();
				$this->datoSave($dato,$this->cola[$i]);
			}
    	}
        return redirect()->action('DatoController@index');
    }

	public function excelNuevo($importacion)
	{
		$comparador = 0;
    	for($i=0; $i < count($importacion)-1 ;$i++)
    	{
	    	$registro_comparador = $importacion[$comparador];
	    	$registro_incremental = $importacion[$i];
    		if($registro_incremental->id == 'hola')
    		{
				if($registro_comparador->numeros != $registro_incremental->numeros)
	    		{
	    			$dato = new Dato();
	    			$this->datoSave($dato,$registro_comparador);
	    			$comparador = $i;
	    		}
    		}
    	}
    	$registro_comparador = $importacion[$comparador];
    	$registro_incremental = $importacion[count($importacion)-1];
    	if($registro_incremental->id != 'hola')
		{
			if($registro_comparador->numeros != $registro_incremental->numeros)
    		{
    			$dato = new Dato();
    			$this->datoSave($dato,$registro_comparador);
    		}
		}
    	elseif($registro_comparador->numeros == $registro_incremental->numeros)
		{
			$dato = new Dato();
			$this->datoSave($dato,$registro_comparador);
		}
		elseif($registro_comparador->numeros != $registro_incremental->numeros)
		{
			$dato = new Dato();
			$this->datoSave($dato,$registro_comparador);
			$dato = new Dato();
			$this->datoSave($dato,$registro_incremental);
		}
	}

	public function excelActualizar($importacion,$datos)
	{
    	$comparador = 0;
    	for($i=0; $i < count($importacion)-1 ;$i++)
    	{
	    	$registro_comparador = $importacion[$comparador];
	    	$registro_incremental = $importacion[$i];
    		if($registro_incremental->id == 'hola')
    		{
				if($registro_comparador->numeros != $registro_incremental->numeros)
	    		{
			    	$this->busquedaBinaria($i-1,count($importacion)-1,$registro_comparador,$datos);
	    			$comparador = $i;
	    		}
    		}
    	}
    	$registro_comparador = $importacion[$comparador];
    	$registro_incremental = $importacion[count($importacion)-1];
    	if($registro_incremental->id != 'hola')
		{
			if($registro_comparador->numeros != $registro_incremental->numeros)
    		{
		    	$this->busquedaBinaria($comparador,count($importacion)-1,$registro_comparador,$datos);
    		}
		}
    	elseif($registro_comparador->numeros == $registro_incremental->numeros)
		{
	    	$this->busquedaBinaria($comparador,count($importacion)-1,$registro_comparador,$datos);
		}
		elseif($registro_comparador->numeros != $registro_incremental->numeros)
		{
	    	$this->busquedaBinaria($comparador,count($importacion)-1,$registro_comparador,$datos);
	    	$this->busquedaBinaria($comparador+1,count($importacion)-1,$registro_incremental,$datos);
		}
		/*
		$cont = 0;
    	$comparador = 0;
    	for($i=0; $i < count($importacion)-1 ;$i++)
    	{
	    	$registro_comparador = $importacion[$comparador];
	    	$registro_incremental = $importacion[$i];
    		if($registro_incremental->id == 'hola')
    		{
				if($registro_comparador->numeros != $registro_incremental->numeros)
	    		{
		    		$dato = $datos[$cont];
	    			$this->datoSave($dato,$registro_comparador);
	    			$cont++;
	    			$comparador = $i;
	    		}
    		}
    	}
    	$registro_comparador = $importacion[$comparador];
    	$registro_incremental = $importacion[count($importacion)-1];
    	if($registro_incremental->id != 'hola')
		{
			if($registro_comparador->numeros != $registro_incremental->numeros)
    		{
	    		$dato = $datos[$cont];
    			$this->datoSave($dato,$registro_comparador);
    		}
		}
    	elseif($registro_comparador->numeros == $registro_incremental->numeros)
		{
    		$dato = $datos[$cont];
			$this->datoSave($dato,$registro_comparador);
		}
		elseif($registro_comparador->numeros != $registro_incremental->numeros)
		{
    		$dato = $datos[$cont];
			$this->datoSave($dato,$registro_comparador);
    		$dato = $datos[$cont+1];
			$this->datoSave($dato,$registro_incremental);
		}
		*/
	}

	public function busquedaBinaria($min,$max,$registro_comparador,$datos)
	{
		$resultado = round(($min + $max)/2);
		if($min == $max && $datos[$resultado]->numeros != $registro_comparador->numeros)
		{
			// dd($datos[$resultado]->numeros.'___'.$registro_comparador->numeros);
			// $datos[$resultado]->delete();
			$this->colaImportar($registro_comparador);
		}
		elseif($datos[$resultado]->numeros == $registro_comparador->numeros)
		{
			$this->datoSave($datos[$resultado],$registro_comparador);
		}
		else
		{
			if($datos[$resultado]->numeros < $registro_comparador->numeros)
			{
				$this->busquedaBinaria($resultado+1,$max,$registro_comparador,$datos);
			}
			else
			{
				$this->busquedaBinaria($min,$resultado-1,$registro_comparador,$datos);
			}
		}
	}

	public function datoSave($dato,$registro)
	{
		// if($registro->numeros == 4)
		// dd($dato.'______'.$registro);
	    $dato->numeros = $registro->numeros;
	    $dato->letras = $registro->letras;
	    $dato->save();
	}

	public function colaImportar($registro)
	{
		$this->cola[] = $registro;
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
		        $arreglo[] = array('ID' => 'hola'/*$dato->id*/, 'NUMEROS' => $dato->numeros, 'LETRAS' => $dato->letras);
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
