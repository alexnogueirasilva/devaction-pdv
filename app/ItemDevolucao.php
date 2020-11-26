<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemDevolucao extends Model
{
    protected $fillable = [
		'cod', 'nome', 'ncm', 'cfop', 'valor_unit', 'quantidade', 'item_parcial', 'devolucao_id', 
		'codBarras', 'unidade_medida'
	];
}
