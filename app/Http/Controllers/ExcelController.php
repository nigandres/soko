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


            // dd($this->colaImport);
            // for($i=0;$i < count($this->cola);$i++)
            // {
            //     $dato = new Dato();
            //     $this->datoSave($dato,$this->cola[$i]);
            // }
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
    	$indice = 0;
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
                        $this->busquedaBinaria($indice,count($personas)-1,$registro_comparador,$personas);
			            $desactiva = false;
						// $resultado = ($indice < count($personas)-1) ? $indice++ : ''/*dd($indice)*/ ;
	                    $indice++;
                	}
	                if($registro_incremental->dd4 != null && $registro_incremental->desctab != 'PROFESOR DE ASIGNATURA CONTRATO')
	            	{
                        $this->busquedaBinaria($indice,count($personas)-1,$registro_incremental,$personas);
						// $resultado = ($indice < count($personas)-1) ? $indice++ : ''/*dd($indice)*/ ;
	                    $indice++;
	                }
            	}
            	else
            	{//esto es para cuando los id son iguales pero no los planteles
	                if($registro_comparador->dd2 != $registro_incremental->dd2 && $registro_incremental->dd2 == 'C. U. DE CS. EXACTAS E INGENIERIAS')
	                {
		                if($registro_incremental->dd4 != null && $registro_incremental->desctab != 'PROFESOR DE ASIGNATURA CONTRATO')
		            	{
	                        $this->busquedaBinaria($indice,count($personas)-1,$registro_incremental,$personas);
							// $resultado = ($indice < count($personas)-1) ? $indice++ : ''/*dd($indice)*/ ;
		                    $indice++;
		                }
		            }
            	}
            }
        }
    }

    public function busquedaBinaria($min,$max,$registro_comparador,$personas)
    {
    	// dd($min.'__'.$max.'__'.$registro_comparador->nombre);
        $resultado = round(($min + $max)/2);
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
