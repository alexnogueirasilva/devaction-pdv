<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ManifestaDfe extends Model
{
    protected $fillable = [
		'chave', 'nome', 'documento', 'valor', 'num_prot', 'data_emissao', 
		'sequencia_evento', 'fornecedor_salvo', 'fatura_salva'
	];
}
