<?php

namespace App\Http\Controllers;

// use App\Dato;
// use App\User;
use App\Persona;
use App\ExcelImportado;
use Storage;
use File;
use Maatwebsite\Excel\Facades\Excel;
// use Maatwebsite\Excel\Files\ExcelFile;
use Illuminate\Http\Request;

class ExcelController extends Controller
{
    //
    private $colaImport;

    public function validar(Request $request)
    {
        $regresar = redirect()->action('PersonaController@index');
        $fichero = $request->file('archivo');
        // $algo = File::lastModified('/home/vagrant/Code/soko/public/archivos/rusa.png');
        // dd(date("F d Y H:i:s.",$algo));
        // $ruta_de_almacen = public_path('archivos').'/'.'rusa.png';
        // dd($fichero->getpathname());
        // $ruta = $_FILES["archivo"]["tmp_name"];
        // $algo = File::lastModified($ruta);
        // $time = Storage::lastModified('rusa.png');
        // dd(dirname($fichero->getrealpath()));
        // dd(date("F d Y H:i:s.",$algo));
        if($fichero == null)
        {
            return $regresar;
        }
        // $fecha = date("D_M_Y",time()).' ';
        // $fecha = date("l_F_Y_d_m_y_H_i_s_a",time()).' ';
        $nombre = date("d_m_y H-i-s",time()).' '.$fichero->getClientOriginalName();
        // $encontrar = '.';
        // $posicion = strpos($nombre, $encontrar);
        // $subcadena = strtolower(substr($nombre, $posicion+1));
        $extension = File::extension($nombre);
        // if($subcadena == 'xls' || $subcadena == 'xlsx')
        if($extension == 'xls' || $extension == 'xlsx')
        {
            Storage::disk('local')->put($nombre, File::get($fichero));
            $ruta_de_almacen = public_path('archivos').'/'.$nombre;
            // $ruta_de_almacen = public_path('archivos').'/'.'rusa.png';
            $file = File::lastModified($ruta_de_almacen);
            $fechaMod = date("d/m/y H:i:s",$file);
            $this->importarExcel($ruta_de_almacen,$fichero,$fechaMod,$extension,$nombre);
        }
        else
        {
            return $regresar;
        }
        return $regresar;
    }

    public function storeExcel($ruta,$archivo,$fecha,$exten,$nombre)
    {
        $fichero = new ExcelImportado();
        $fichero->nombre = $nombre;
        $fichero->mime = $archivo->getClientMimeType();
        $fichero->tamanio = $archivo->getClientSize();
        $fichero->importado = $fecha;
        $fichero->extencion = $exten;
        $fichero->ruta = $ruta;
        $fichero->tipo = $archivo->gettype();
        $fichero->save();
    }

    public function importarExcel($ruta,$fichero,$fechaMod,$extension,$nombre)
    {
        set_time_limit(120);//esto incrementa el tiempo de respuesta
        $personas = Persona::all();
        if(count($personas) == 0)
        {
            // Excel::load('PARACEIS.xls', function($reader) {
            Excel::load($ruta, function($reader) use ($ruta,$fichero,$fechaMod,$extension,$nombre) {
                $campos = $this->camposRequeridos($reader->get()->getHeading());
                if($campos != 0)
                {
                    Storage::delete($nombre);
                    return redirect()->action('PersonaController@index');
                }
                else
                {
                    $this->storeExcel($ruta,$fichero,$fechaMod,$extension,$nombre);
                }
                $tabla_importada = $reader->get(array('codigo','nombre','desctab','dd2','dd4'));
                $this->excelNuevo($tabla_importada);
            })->get()/*EL GET PARECE QUE SE PUEDE OMITIR*/;
        }
        else
        {
            // Excel::load('PARACEIS.xls', function($reader) use ($personas) {
            Excel::load($ruta, function($reader) use ($personas,$ruta,$fichero,$fechaMod,$extension,$nombre) {
                $campos = $this->camposRequeridos($reader->get()->getHeading());
                if($campos != 0)
                {
                    Storage::delete($nombre);
                    return redirect()->action('PersonaController@index');
                }
                else
                {
                    $this->storeExcel($ruta,$fichero,$fechaMod,$extension,$nombre);
                }
                $tabla_importada = $reader->get(array('codigo','nombre','desctab','dd2','dd4'));
                $this->excelActualizar($tabla_importada,$personas);
            })->get()/*EL GET PARECE QUE SE PUEDE OMITIR*/;

            for($i=0;$i < count($this->colaImport);$i++)
            {
                $persona = new Persona();
                $this->personaSave($persona,$this->colaImport[$i]);
            }

            $personasActualizadas = Persona::all();
            // $this->ordenamientoBaseDeDatos($personasActualizadas,count($personasActualizadas));
            $this->ordenamientoBaseDeDatos($personasActualizadas,0,count($personasActualizadas)-1);
        }
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

    public function ordenamientoBaseDeDatos($baseDeDatos,$min,$max)
    {
    	if($min >= $max)
    	{
    		return;
    	}
    	$mid = round(($min+$max)/2, 0, PHP_ROUND_HALF_DOWN);
    	$this->ordenamientoBaseDeDatos($baseDeDatos,$min,$mid);
    	$this->ordenamientoBaseDeDatos($baseDeDatos,$mid+1,$max);
    	$this->merge($baseDeDatos,$min,$max);
    }

    public function merge($baseDeDatos,$izqIni,$derFin)
    {
    	for($i=0;$i<$derFin;$i++)
    	{
    		$aux[$i] = $baseDeDatos[$i];
    	}
    	$izqFin = round(($izqIni+$derFin)/2, 0, PHP_ROUND_HALF_DOWN);
    	$derIni = $izqFin + 1;
    	$tamanio = $derFin - $izqIni + 1;
    	$left = $izqIni;
	    $right = $derIni;
	    $index = $izqIni;
	    while($left <= $izqFin && $right <= $derFin)
	    {
	    	if($baseDeDatos[$left] <= $baseDeDatos[$right])
	    	{
	    		$aux[$index] = $baseDeDatos[$left];
	    		$left++;
	    	}
	    	else
	    	{
	    		$aux[$index] = $baseDeDatos[$right];
	    		$right++;
	    	}
    		$index++;
	    }
	    while ($left <= $izqFin)
	    {
	    	$aux[$index] = $baseDeDatos[$left];
	    	$left++;
	    }
	    while ($right <= $derFin)
	    {
	    	$aux[$index] = $baseDeDatos[$right];
	    	$right++;
	    }
    	for($i=0;$i<$derFin;$i++)
    	{
    		$baseDeDatos[$i] = $aux[$i];
    		$baseDeDatos[$i]->save();
    	}
    }

    // public function ordenamientoBaseDeDatos($baseDeDatos,$tamanio)
    // {
    // 	$i;
    // 	$j;
    // 	$aux;
    // 	for($i=1;$i < $tamanio;$i++)
    // 	{
    // 		$j = $i;
    // 		while($j > 0 && $baseDeDatos[$j-1]->codigo > $baseDeDatos[$j]->codigo)
    // 		{
    // 			$aux = $baseDeDatos[$j];
    // 			$baseDeDatos[$j] = $baseDeDatos[$j-1];
    // 			$baseDeDatos[$j-1] = $aux;
    // 			$j--;
    // 		}
    // 	}
    // 	$personas = Persona::all();
    // 	for($i=0;$i < count($baseDeDatos);$i++)
    //     {
    //     	$personaAntes = $personas[$i];
    //     	$personaDespues = $baseDeDatos[$i];
    // 	// dd($personaDespues);
    //     	$personaAntes->nombre = $personaDespues->nombre;
	   //      $personaAntes->codigo = $personaDespues->codigo;
	   //      $personaAntes->adscripcion_nominal = $personaDespues->adscripcion_nominal;
	   //      // $personaAntes->adscripcion_fisica = $personaDespues->blablabla;
	   //      $personaAntes->nombramiento = $personaDespues->nombramiento;
	   //      $personaAntes->plantel = $personaDespues->plantel;
	   //      $personaAntes->save();
    //         // $this->personaSave($personaAntes,$personaDespues);
    //     }
    // }

    public function camposRequeridos($encabezados)
    {
        $campos = array('dd4','dd2','desctab','nombre','codigo');
        foreach ($encabezados as $encabezado)
        {
            for($i=count($campos)-1;$i>=0;$i--)
            {
                if($encabezado == $campos[$i])
                {
                    // array_pop($campos);
                    unset($campos[$i]);
                    // array_splice($campos,$i,1);
                    // array_diff($campos,$campos[$i]/*,$campos[$i+1]*/);
                }
            }
        }
        return count($campos);
    }

    public function exportar()
    {
        $personas = Persona::all();
        foreach ($personas as $persona)
        {
            $arreglo[] = array('CODIGO' => $persona->codigo, 'NOMBRE' => $persona->nombre, 'DESCTAB' => $persona->nombramiento, 'DD4' => $persona->adscripcion_nominal, 'DD2' => $persona->plantel,);
        }
        Excel::create('PARACEIS', function($excel) use ($arreglo) {
	        $excel->sheet('hoja1', function($sheet) use ($arreglo) {
                // Sheet manipulation
                $sheet->fromArray($arreglo);
            });
        })->export('xls');
    }
}
