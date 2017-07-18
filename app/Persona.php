<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $fillable = ['nombre','codigo','adscripcion_nominal','adscripcion_fisica','nombramiento'];
}
