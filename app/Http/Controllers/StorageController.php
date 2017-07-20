<?php

namespace App\Http\Controllers;

use Storage;
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
       //obtenemos el nombre del archivo
       $nombre = time().'_'.$archivo->getClientOriginalName();
 
       //indicamos que queremos guardar un nuevo archivo en el disco local
       Storage::disk('local')->put($nombre,  \File::get($archivo));

       return redirect()->action('StorageController@index');
    }
}
