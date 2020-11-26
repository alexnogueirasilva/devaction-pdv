<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreditoVenda extends Model
{
    protected $fillable = [
		'venda_id', 'cliente_id', 'status'
	];

	public function venda(){
		return $this->belongsTo(Venda::class, 'venda_id');
	}

	public function cliente(){
		return $this->belongsTo(Cliente::class, 'cliente_id');
	}

	public static function filtroData($dataInicial, $dataFinal, $status){
		$c = CreditoVenda::
		orderBy('credito_vendas.id', 'desc')
		->join('vendas', 'vendas.id' , '=', 'credito_vendas.venda_id')
		->whereBetween('vendas.data_registro', [$dataInicial, 
			$dataFinal]);

		if($status == 'pago'){
			$c->where('status', true);
		} else if($status == 'pendente'){
			$c->where('status', false);
		}
		return $c->get();
	}
	public static function filtroDataFornecedor($cliente, $dataInicial, $dataFinal, $status){
		$c = CreditoVenda::
		orderBy('credito_vendas.id', 'desc')
		->join('vendas', 'vendas.id' , '=', 'credito_vendas.venda_id')
		->join('clientes', 'clientes.id' , '=', 'vendas.cliente_id')
		->where('clientes.razao_social', 'LIKE', "%$cliente%")
		->whereBetween('vendas.data_registro', [$dataInicial, 
			$dataFinal]);

		if($status == 'pago'){
			$c->where('status', true);
		} else if($status == 'pendente'){
			$c->where('status', false);
		}
		return $c->get();
	}

	public static function filtroFornecedor($cliente, $status){
		$c = CreditoVenda::
		orderBy('credito_vendas.id', 'desc')
		->join('vendas', 'vendas.id' , '=', 'credito_vendas.venda_id')
		->join('clientes', 'clientes.id' , '=', 'vendas.cliente_id')
		->where('razao_social', 'LIKE', "%$cliente%");

		if($status == 'pago'){
			$c->where('status', true);
		} else if($status == 'pendente'){
			$c->where('status', false);
		}
		
		return $c->get();
	}

	public static function filtroStatus($status){
		$c = CreditoVenda::
		orderBy('id', 'desc');
		if($status == 'pago'){
			$c->where('status', true);
		} else if($status == 'pendente'){
			$c->where('status', false);
		}
		
		return $c->get();
	}

}
