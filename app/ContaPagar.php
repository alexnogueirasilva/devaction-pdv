<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContaPagar extends Model
{
	protected $fillable = [
		'compra_id', 'data_vencimento', 'data_pagamento', 'valor_integral', 'valor_pago', 
		'referencia', 'categoria_id', 'status'
	];

	public function compra(){
		return $this->belongsTo(Compra::class, 'compra_id');
	}

	public function categoria(){
		return $this->belongsTo(CategoriaConta::class, 'categoria_id');
	}

	public static function filtroData($dataInicial, $dataFinal, $status){
		$c = ContaPagar::
		orderBy('data_vencimento', 'asc')
		->whereBetween('data_vencimento', [$dataInicial, 
			$dataFinal]);

		if($status == 'pago'){
			$c->where('status', true);
		} else if($status == 'pendente'){
			$c->where('status', false);
		}
		return $c->get();
	}
	public static function filtroDataFornecedor($fornecedor, $dataInicial, $dataFinal, $status){
		$c = ContaPagar::
		orderBy('conta_pagars.data_vencimento', 'asc')
		->join('compras', 'compras.id' , '=', 'conta_pagars.compra_id')
		->join('fornecedors', 'fornecedors.id' , '=', 'compras.fornecedor_id')
		->where('fornecedors.razao_social', 'LIKE', "%$fornecedor%")
		->whereBetween('data_vencimento', [$dataInicial, 
			$dataFinal]);

		if($status == 'pago'){
			$c->where('status', true);
		} else if($status == 'pendente'){
			$c->where('status', false);
		}
		return $c->get();
	}

	public static function filtroFornecedor($fornecedor, $status){
		$c = ContaPagar::
		orderBy('conta_pagars.data_vencimento', 'asc')
		->join('compras', 'compras.id' , '=', 'conta_pagars.compra_id')
		->join('fornecedors', 'fornecedors.id' , '=', 'compras.fornecedor_id')
		->where('razao_social', 'LIKE', "%$fornecedor%");

		if($status == 'pago'){
			$c->where('status', true);
		} else if($status == 'pendente'){
			$c->where('status', false);
		}
		
		return $c->get();
	}

	public static function filtroStatus($status){
		$c = ContaPagar::
		orderBy('conta_pagars.data_vencimento', 'asc');

		if($status == 'pago'){
			$c->where('status', true);
		} else if($status == 'pendente'){
			$c->where('status', false);
		}
		
		return $c->get();
	}

}
