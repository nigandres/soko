<?php

namespace App\Http\Controllers;

use App\Dato;
use Maatwebsite\Excel\Facades\Excel;//es para excel xd
use Illuminate\Http\Request;

class DatoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // regresa una vista de los datos recopilados en una pagina
        $datos = Dato::all();
        return view('datos.indexDato',compact('datos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('datos.formDato');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // se insertan el numero de datos en la tabla datos
        // dd($request->input('hidden'));
        $lim = $request->input('hidden');
        for($i = 0; $i <= $lim ; $i++)
        {
            $dato = new Dato();
            $dato->numeros = $request->input('num'.$i);
            $dato->letras = $request->input('let'.$i);
            $dato->save();
        }
        // redirecciona a la vista para ver los resultados
        return redirect()->action('DatoController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Dato  $dato
     * @return \Illuminate\Http\Response
     */
    public function show(Dato $dato)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Dato  $dato
     * @return \Illuminate\Http\Response
     */
    public function edit(Dato $dato)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Dato  $dato
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dato $dato)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Dato  $dato
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dato $dato)
    {
        //
    }

    public function exportExcel($id)
    {
        $dato = Dato::find($id);
        $arreglo = array($dato->id,$dato->numeros,$dato->letras);
        Excel::create('Dato', function($excel) use ($arreglo) {

            $excel->sheet('uno', function($sheet) use ($arreglo) {

                // Sheet manipulation
                // dd($arreglo);
                $sheet->fromArray($arreglo);

            });

        })->export('xls');
    }
}
