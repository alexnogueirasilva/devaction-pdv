<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImagensProdutoDelivery extends Model
{	
	protected $fillable = [
		'produto_id', 'path'
	];

	
}
