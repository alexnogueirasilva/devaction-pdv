<?php

namespace App\Http\Controllers\AppFiscal;

use Illuminate\Http\Request;
use App\Cliente;
use App\Cidade;

class ClienteController extends Controller
{
	public function clientes(){
		$clientes = Cliente::all();
		foreach($clientes as $c){
			$c->cidade;
		}
		return response()->json($clientes, 200);
	}

	public function salvar(Request $request){
		

		$data = [
			'razao_social' => $request->razao_social,
			'nome_fantasia' => $request->nome_fantasia,
			'bairro' => $request->bairro,
			'numero' => $request->numero,
			'rua' => $request->logradouro,
			'cpf_cnpj' => $request->cpf_cnpj,
			'telefone' => $request->telefone,
			'celular' => $request->celular,
			'email' => $request->email,
			'cep' => $request->cep,
			'ie_rg' => $request->ie_rg,
			'consumidor_final' => $request->consumidor_final,
			'limite_venda' => $request->limite_venda,
			'cidade_id' => $request->cidade,
			'contribuinte' => $request->contribuinte
		];

		$res = Cliente::create($data);
		return response()->json($res, 200);
	}

	public function cidades(){
		$cidades = Cidade::all();
		return response()->json($cidades, 200);
	}
}