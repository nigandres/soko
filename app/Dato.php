<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dato extends Model
{
	// se dan los accesos y estados de los campos
    public $timestamps = false;
    protected $fillable = ['numeros','letras'];
}
