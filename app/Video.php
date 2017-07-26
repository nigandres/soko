<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
	public $timestamps = false;
	protected $fillable = ['titulo','url'];
	
	public function comentarios()
	{
		return $this->morphMany('App\Comentario','comentariable');
	}
}
