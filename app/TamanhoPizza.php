<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TamanhoPizza extends Model
{
    protected $fillable = [ 
		'nome', 'pedacos', 'maximo_sabores'
	];

	public function produtoPizza(){
		return $this->hasMany('App\ProdutoPizza', 'tamanho_id', 'id');
	}
}
