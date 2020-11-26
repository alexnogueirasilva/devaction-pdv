<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AberturaCaixa;

class AberturaCaixaController extends Controller
{
	public function __construct(){
		$this->middleware(function ($request, $next) {
			$value = session('user_logged');
			if(!$value){
				return redirect("/login");
			}
			return $next($request);
		});
	}

	public function abrir(Request $request){
		$verify = $this->verificaAberturaCaixa();
		if($verify == -1){
			$result = AberturaCaixa::create([
				'usuario_id' => get_id_user(),
				'valor' => str_replace(",", ".", $request->valor) 
			]);
			echo json_encode($result);
		}else{
			echo json_encode(true);
		}
	}

	public function verificaHoje(){
		echo json_encode($this->verificaAberturaCaixa());
	}

	public function diaria(){
		date_default_timezone_set('America/Sao_Paulo');
		$hoje = date("Y-m-d") . " 00:00:00";
		$amanha = date('Y-m-d', strtotime('+1 days')). " 00:00:00";
		$abertura = AberturaCaixa::
		whereBetween('data_registro', [$hoje, 
			$amanha])
		->first();
		 echo json_encode($abertura);
	}

	private function setUsuario($sangrias){
		for($aux = 0; $aux < count($sangrias); $aux++){
			$sangrias[$aux]['nome_usuario'] = $sangrias[$aux]->usuario->nome;
		}
		return $sangrias;
	}

	private function verificaAberturaCaixa(){
		date_default_timezone_set('America/Sao_Paulo');
		$dataHoje = date("Y-m-d");
		$ab = AberturaCaixa::
		orderBy('id', 'desc')
		->first();
		if($ab){
			$ultimaDataAbertura = substr($ab->data_registro, 0, 10);
			if($ultimaDataAbertura == $dataHoje) return $ab->valor;
			else return -1;
		}else{
			return -1;
		}
	}

}
