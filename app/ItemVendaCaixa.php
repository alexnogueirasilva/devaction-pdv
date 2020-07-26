<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemVendaCaixa extends Model
{
    protected $fillable = [
		'produto_id', 'venda_caixa_id', 'quantidade', 'valor', 'item_pedido_id', 'observacao'
	];

	public function produto(){
        return $this->belongsTo(Produto::class, 'produto_id');
    }

    public function itemPedido(){
        return $this->belongsTo(ItemPedido::class, 'item_pedido_id');
    }

    public function nomeDoProduto(){
    	$nome = $this->produto->nome;
    	if($this->observacao != '')
    		$nome .= ' obs: ' . $this->observacao;
    	return $nome;
    }
}