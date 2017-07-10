<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dato extends Model
{
    public $timestamps = false;
    protected $fillable = ['numeros','letras'];
}
