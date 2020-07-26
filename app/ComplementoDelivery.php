<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComplementoDelivery extends Model
{
    protected $fillable = [
		'nome', 'valor', 'categoria_id'
	];
}
