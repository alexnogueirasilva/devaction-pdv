<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ConfigNota;

class VendaCaixa extends Model
{
	protected $fillable = [
		'cliente_id', 'usuario_id', 'valor_total', 'NFcNumero',
		'natureza_id', 'chave', 'path_xml', 'estado', 'tipo_pagamento', 'forma_pagamento', 'dinheiro_recebido','troco', 'nome', 'cpf', 'observacao', 'desconto', 'acrescimo'
	];

	public function itens(){
		return $this->hasMany('App\ItemVendaCaixa', 'venda_caixa_id', 'id');
	}

	public function cliente(){
		return $this->belongsTo(Cliente::class, 'cliente_id');
	}

	public function natureza(){
        return $this->belongsTo(NaturezaOperacao::class, 'natureza_id');
    }

	public function usuario(){
		return $this->belongsTo(Usuario::class, 'usuario_id');
	}

	public static function tiposPagamento(){
		return [
			'01' => 'Dinheiro',
			'02' => 'Cheque',
			'03' => 'Cartão de Crédito',
			'04' => 'Cartão de Débito',
			'05' => 'Crédito Loja',
			'10' => 'Vale Alimentação',
			'11' => 'Vale Refeição',
			'12' => 'Vale Presente',
			'13' => 'Vale Combustível',
			'14' => 'Duplicata Mercantil',
			'15' => 'Boleto Bancário',
			'90' => 'Sem pagamento',
			'99' => 'Outros',
		];
	}

	public static function lastNFCe(){
		$venda = VendaCaixa::
		where('NFcNumero', '!=', 0)
		->orderBy('NFcNumero', 'desc')
		->first();

		if($venda == null) {
			return ConfigNota::first()->ultimo_numero_nfce;
		}
		else{ 
			$configNum = ConfigNota::first()->ultimo_numero_nfce;
			return $configNum > $venda->NFcNumero ? $configNum : $venda->NFcNumero;
		}
	}

	public static function filtroData($dataInicial, $dataFinal){
		return VendaCaixa::
		orderBy('id', 'desc')
		->whereBetween('data_registro', [$dataInicial, 
			$dataFinal])
		->get();
	}

	public static function filtroCliente($cliente){
		return VendaCaixa::
		join('clientes', 'clientes.id' , '=', 'venda_caixas.cliente_id')
		->where('clientes.razao_social', 'LIKE', "%$cliente%")
		->get();
	}

	public static function filtroNFCe($nfce){
		return VendaCaixa::
		where('NFcNumero', $nfce)
		->get();
	}

	public static function filtroValor($valor){
		return VendaCaixa::
		where('valor_total', 'LIKE', "%$valor%")
		->get();
	}

}
