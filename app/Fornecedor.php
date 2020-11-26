<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model
{
    protected $fillable = [
        'razao_social', 'nome_fantasia', 'bairro', 'numero', 'rua', 'cpf_cnpj', 'telefone', 'celular', 'email', 'cep', 'ie_rg', 'cidade_id'
    ];

    public function cidade(){
        return $this->belongsTo(Cidade::class, 'cidade_id');
    }

    public static function verificaCadastrado($cnpj){
    	
    	$forn = Fornecedor::where('cpf_cnpj', $cnpj)
    	->first();

    	return $forn;

    }
}
