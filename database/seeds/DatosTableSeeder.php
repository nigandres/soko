<?php

use Illuminate\Database\Seeder;

class DatosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$letras = array('a','b','c','d','e','f','g');
        for($i=0;$i<count($letras);$i++)
        {
	        DB::table('datos')->insert(['letras' => $letras[$i],'numeros' => $i*$i]);
        }
    }
}
