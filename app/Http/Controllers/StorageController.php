<?php

namespace App\Http\Controllers;

use Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class StorageController extends Controller
{
    //
    public function index()
    {
    	return view('personas.pedirPersona');
    }

    public function save(Request $request)
    {
      //obtenemos el campo file definido en el formulario
      $archivo = $request->file('archivo');
/*
dd($archivo);
dd($archivo->getClientOriginalName());
dd($archivo->getClientMimeType());
dd($archivo->getClientSize());//obtiene el mismo resultado
dd($archivo->getpath());
dd($archivo->getfilename());
dd($archivo->getbasename());
dd($archivo->getpathname());
dd($archivo->getextension());
dd($archivo->getrealPath());
dd($archivo->getaTime());
dd($archivo->getmTime());
dd($archivo->getctime());
dd($archivo->getinode());
dd($archivo->getsize());//obtiene el mismo resultado
dd($archivo->getperms());
dd($archivo->getowner());
dd($archivo->getgroup());
dd($archivo->gettype());
*/
// dd($archivo->getwritable());
// dd($archivo->getreadable());
// dd($archivo->getexecutable());
// dd($archivo->getfile());
// dd($archivo->getdir());
// dd($archivo->getlink());
      //obtenemos el nombre del archivo
      $nombre = $archivo->getClientOriginalName();
      //indicamos que queremos guardar un nuevo archivo en el disco local
      Storage::disk('local')->put($nombre,  \File::get($archivo));


// $info = Storage::get('PARACEIS.xls');//no se usa en la funcion de abajo
      Excel::load(public_path('archivos').'/'.$nombre, function($reader) {
// dd(public_path('archivos').'/'.$nombre.'____'.public_path() . '/archivos/' . $nombre);
// $reader->dump();
          $tabla_importada = $reader->get(array('codigo','nombre','desctab','dd2','dd4'));
          // $this->excelNuevo($tabla_importada);
      })->get()/*EL GET PARECE QUE SE PUEDE OMITIR*/;


      return redirect()->action('StorageController@index');
    }
}
