<?php

namespace App\Http\Controllers\AppFiscal;

use Illuminate\Http\Request;
use App\Transportadora;
use App\Cidade;

class TransportadoraController extends Controller
{
	public function index(){
		$transportadoras = Transportadora::all();
		foreach($transportadoras as $c){
			$c->cidade;
		}
		return response()->json($transportadoras, 200);
	}
}