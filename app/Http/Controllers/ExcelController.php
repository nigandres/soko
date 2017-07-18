<?php

namespace App\Http\Controllers;

use App\Dato;
use App\User;
use App\Persona;
use Maatwebsite\Excel\Facades\Excel;
// use Maatwebsite\Excel\Files\ExcelFile;
use Illuminate\Http\Request;

class ExcelController extends Controller
{
    //
    private $colaImport;
    public function importar()
    {
    	set_time_limit(120);//esto incrementa el tiempo de respuesta
        $personas = Persona::all();
        if(count($personas) == 0)
        {
            Excel::load('PARACEIS.xls', function($reader) {
                $tabla_importada = $reader->get(array('codigo','nombre','desctab','dd2','dd4'));
                $this->excelNuevo($tabla_importada);
            })->get()/*EL GET PARECE QUE SE PUEDE OMITIR*/;
        }
        else
        {
            Excel::load('PARACEIS.xls', function($reader) use ($personas) {
                $tabla_importada = $reader->get(array('codigo','nombre','desctab','dd2','dd4'));
                $this->excelActualizar($tabla_importada,$personas);
            })->get()/*EL GET PARECE QUE SE PUEDE OMITIR*/;

            for($i=0;$i < count($this->colaImport);$i++)
            {
                $persona = new Persona();
                $this->personaSave($persona,$this->colaImport[$i]);
            }

            $personasActualizadas = Persona::all();
            $this->ordenamientoBaseDeDatos($personasActualizadas,count($personasActualizadas));
        }
        return redirect()->action('PersonaController@index');
    }

    public function excelNuevo($importacion)
    {
    	$desactiva = true;
        for($i=0; $i < count($importacion) ;$i++)
        {
            $registro_incremental = $importacion[$i];
        	if($i == 0)
        	{
        		$registro_comparador = $importacion[$i];
        	}
        	else
        	{
	    		$registro_comparador = $importacion[$i-1];
        	}
	        if($registro_incremental->dd2 == 'C. U. DE CS. EXACTAS E INGENIERIAS')
            {
                if($registro_comparador->codigo != $registro_incremental->codigo)
                {
                	if($desactiva == true)
                	{			            
			            $persona = new Persona();
			            $this->personaSave($persona,$registro_comparador);
			            $desactiva = false;
                	}
	                if($registro_incremental->dd4 != null && $registro_incremental->desctab != 'PROFESOR DE ASIGNATURA CONTRATO')
	            	{
	                    $persona = new Persona();
	                    $this->personaSave($persona,$registro_incremental);
	                }
            	}
            	else
            	{//esto es para cuando los id son iguales pero no los planteles
	                if($registro_comparador->dd2 != $registro_incremental->dd2 && $registro_incremental->dd2 == 'C. U. DE CS. EXACTAS E INGENIERIAS')
	                {
		                if($registro_incremental->dd4 != null && $registro_incremental->desctab != 'PROFESOR DE ASIGNATURA CONTRATO')
		            	{
		                    $persona = new Persona();
		                    $this->personaSave($persona,$registro_incremental);
		                }
		            }
            	}
            }
        }
    }

    public function excelActualizar($importacion,$personas)
    {
    	$desactiva = true;
        for($i=0; $i < count($importacion) ;$i++)
        {
            $registro_incremental = $importacion[$i];
        	if($i == 0)
        	{
        		$registro_comparador = $importacion[$i];
        	}
        	else
        	{
	    		$registro_comparador = $importacion[$i-1];
        	}
	        if($registro_incremental->dd2 == 'C. U. DE CS. EXACTAS E INGENIERIAS')
            {
                if($registro_comparador->codigo != $registro_incremental->codigo)
                {
                	if($desactiva == true)
                	{
                        $this->busquedaBinaria(0,count($personas)-1,$registro_comparador,$personas);
			            $desactiva = false;
                	}
	                if($registro_incremental->dd4 != null && $registro_incremental->desctab != 'PROFESOR DE ASIGNATURA CONTRATO')
	            	{
                        $this->busquedaBinaria(0,count($personas)-1,$registro_incremental,$personas);
	                }
            	}
            	else
            	{//esto es para cuando los id son iguales pero no los planteles
	                if($registro_comparador->dd2 != $registro_incremental->dd2 && $registro_incremental->dd2 == 'C. U. DE CS. EXACTAS E INGENIERIAS')
	                {
		                if($registro_incremental->dd4 != null && $registro_incremental->desctab != 'PROFESOR DE ASIGNATURA CONTRATO')
		            	{
	                        $this->busquedaBinaria(0,count($personas)-1,$registro_incremental,$personas);
		                }
		            }
            	}
            }
        }
    }

    public function busquedaBinaria($min,$max,$registro_comparador,$personas)
    {
        $resultado = round(($min + $max)/2, 0, PHP_ROUND_HALF_DOWN);
        if($min == $max && $personas[$resultado]->codigo != $registro_comparador->codigo)
        {
            $this->colaImportar($registro_comparador);
        }
        elseif($personas[$resultado]->codigo == $registro_comparador->codigo)
        {
            $this->personaSave($personas[$resultado],$registro_comparador);
        }
        else
        {
            if($personas[$resultado]->codigo < $registro_comparador->codigo)
            {
                $this->busquedaBinaria($resultado+1,$max,$registro_comparador,$personas);
            }
            else
            {
                $this->busquedaBinaria($min,$resultado-1,$registro_comparador,$personas);
            }
        }
    }

    public function personaSave($persona,$registro)
    {
        $persona->nombre = $registro->nombre;
        $persona->codigo = $registro->codigo;
        $persona->adscripcion_nominal = $registro->dd4;
        // $persona->adscripcion_fisica = $registro->blablabla;
        $persona->nombramiento = $registro->desctab;
        $persona->plantel = $registro->dd2;
        $persona->save();
    }

    public function colaImportar($registro)
    {
        $this->colaImport[] = $registro;
    }

    public function ordenamientoBaseDeDatos($baseDeDatos,$tamanio)
    {
    	$i;
    	$j;
    	$aux;
    	for($i=1;$i < $tamanio;$i++)
    	{
    		$j = $i;
    		while($j > 0 && $baseDeDatos[$j-1]->codigo > $baseDeDatos[$j]->codigo)
    		{
    			$aux = $baseDeDatos[$j];
    			$baseDeDatos[$j] = $baseDeDatos[$j-1];
    			$baseDeDatos[$j-1] = $aux;
    			$j--;
    		}
    	}
    	$personas = Persona::all();
    	for($i=0;$i < count($baseDeDatos);$i++)
        {
        	$personaAntes = $personas[$i];
        	$personaDespues = $baseDeDatos[$i];
    	// dd($personaDespues);
        	$personaAntes->nombre = $personaDespues->nombre;
	        $personaAntes->codigo = $personaDespues->codigo;
	        $personaAntes->adscripcion_nominal = $personaDespues->adscripcion_nominal;
	        // $personaAntes->adscripcion_fisica = $personaDespues->blablabla;
	        $personaAntes->nombramiento = $personaDespues->nombramiento;
	        $personaAntes->plantel = $personaDespues->plantel;
	        $personaAntes->save();
            // $this->personaSave($personaAntes,$personaDespues);
        }
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
