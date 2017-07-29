<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

// Relation::morphMap(['post' => 'App\Post', 'video' => 'App\Video',]);
Relation::morphMap([
    'post' => \App\Post::class,
    'video' => \App\Video::class,
]);

class Comentario extends Model
{
	public $timestamps = false;
	protected $fillable = ['cuerpo','comentariable_id','comentariable_type'];

	public function comentariable()
	{
		return $this->morphTo();
	}
}
