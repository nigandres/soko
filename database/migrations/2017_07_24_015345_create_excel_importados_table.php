<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExcelImportadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('excel_importados', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre')->unique();
            $table->string('mime');
            $table->bigInteger('tamanio');
            // $table->datetime('importado');
            $table->string('importado');
            $table->string('extencion');
            $table->string('ruta')->unique();
            $table->string('tipo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('excel_importados');
    }
}
