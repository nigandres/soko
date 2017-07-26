<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
	public $timestamps = false;
	protected $fillable = ['titulo','cuerpo'];

	public function comentarios()
	{
		return $this->morphMany('App\Comentario','comentariable');
	}
}
