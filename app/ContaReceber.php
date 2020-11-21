<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContaReceber extends Model
{
	protected $fillable = [
		'venda_id', 'data_vencimento', 'data_recebimento', 'valor_integral', 'valor_recebido', 
		'referencia', 'categoria_id', 'status'
	];

	public function venda(){
		return $this->belongsTo(Venda::class, 'venda_id');
	}

	public function categoria(){
		return $this->belongsTo(CategoriaConta::class, 'categoria_id');
	}

	public static function filtroData($dataInicial, $dataFinal, $status){
		$c = ContaReceber::
		orderBy('conta_recebers.data_vencimento', 'asc')
		->whereBetween('conta_recebers.data_vencimento', [$dataInicial, 
			$dataFinal]);

		if($status == 'pago'){
			$c->where('status', true);
		} else if($status == 'pendente'){
			$c->where('status', false);
		}
		return $c->get();
	}
	public static function filtroDataFornecedor($cliente, $dataInicial, $dataFinal, $status){
		$c = ContaReceber::
		orderBy('conta_recebers.data_vencimento', 'asc')
		->join('vendas', 'vendas.id' , '=', 'conta_recebers.venda_id')
		->join('clientes', 'clientes.id' , '=', 'vendas.cliente_id')
		->where('clientes.razao_social', 'LIKE', "%$cliente%")
		->whereBetween('conta_recebers.data_vencimento', [$dataInicial, 
			$dataFinal]);

		if($status == 'pago'){
			$c->where('status', true);
		} else if($status == 'pendente'){
			$c->where('status', false);
		}
		return $c->get();
	}

	public static function filtroFornecedor($cliente, $status){
		$c = ContaReceber::
		orderBy('conta_recebers.data_vencimento', 'asc')
		->join('vendas', 'vendas.id' , '=', 'conta_recebers.venda_id')
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
		$c = ContaReceber::
		orderBy('conta_recebers.data_vencimento', 'asc');
		if($status == 'pago'){
			$c->where('status', true);
		} else if($status == 'pendente'){
			$c->where('status', false);
		}
		
		return $c->get();
	}
}
