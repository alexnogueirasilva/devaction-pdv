<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tributacao extends Model
{
	protected $fillable = [
		'icms', 'pis', 'cofins', 'regime', 'ipi'
	];

	public static function regimes(){
		return [ 
			0 => 'Simples',
			1 => 'Normal'
		];
	}
}
