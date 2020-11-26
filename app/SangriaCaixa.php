<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SangriaCaixa extends Model
{
    protected $fillable = [
        'usuario_id', 'valor'
    ];

    public function usuario(){
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
