<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	// llamo al seeder de la clase de datosSeeder
        $this->call(DatosTableSeeder::class);
    }
}
