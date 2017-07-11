<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // se modifican los campos de la tabla users
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre')->unque();
            $table->integer('edad');
            $table->enum('cargo',['Jefe','Empleado']);
            $table->string('correo')->unique();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
