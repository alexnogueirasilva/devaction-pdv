<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ContaPagar;
use App\CategoriaConta;

class ContasPagarController extends Controller
{

	public function __construct(){
		$this->middleware(function ($request, $next) {
			$value = session('user_logged');
			if(!$value){
				return redirect("/login");
			}else{
                if($value['acesso_financeiro'] == 0){
                    return redirect("/sempermissao");
                }
            }
			return $next($request);
		});
	}

	public function index(){
		$contas = ContaPagar::
		whereBetween('data_vencimento', [date("Y-m-d"), 
			date('Y-m-d', strtotime('+1 month'))])
		->orderBy('data_vencimento', 'asc')
		->get();
		$somaContas = $this->somaCategoriaDeContas($contas);
		return view('contaPagar/list')
		->with('contas', $contas)
		->with('graficoJs', true)
		->with('somaContas', $somaContas)
		->with('infoDados', "Dos próximos 30 dias")
		->with('title', 'Contas a Pagar');
	}

	private function somaCategoriaDeContas($contas){
		$arrayCategorias = $this->criaArrayDecategoriaDeContas();
		$temp = [];
		foreach($contas as $c){
			foreach($arrayCategorias as $a){
				if($c->categoria->nome == $a){
					if(isset($temp[$a])){
						$temp[$a] = $temp[$a] + $c->valor_integral;
					}else{
						$temp[$a] = $c->valor_integral;
					}
				}
			}
		}

		return $temp;
	}

	private function criaArrayDecategoriaDeContas(){
		$categorias = CategoriaConta::all();
		$temp = [];
		foreach($categorias as $c){
			array_push($temp, $c->nome);
		}

		return $temp;
	}

	public function filtro(Request $request){

		$dataInicial = $request->data_inicial;
		$dataFinal = $request->data_final;
		$fornecedor = $request->fornecedor;
		$status = $request->status;
		$contas = null;

		if(isset($fornecedor) && isset($dataInicial) && isset($dataFinal)){
			$contas = ContaPagar::filtroDataFornecedor(
				$fornecedor, 
				$this->parseDate($dataInicial),
				$this->parseDate($dataFinal),
				$status
			);
		}else if(isset($dataInicial) && isset($dataFinal)){
			$contas = ContaPagar::filtroData(
				$this->parseDate($dataInicial),
				$this->parseDate($dataFinal),
				$status
			);
		}else if(isset($fornecedor)){
			$contas = ContaPagar::filtroFornecedor(
				$fornecedor,
				$status
			);
		}else{
			$contas = ContaPagar::filtroStatus($status);
		}

		$somaContas = $this->somaCategoriaDeContas($contas);

		return view('contaPagar/list')
		->with('contas', $contas)
		->with('fornecedor', $fornecedor)
		->with('dataInicial', $dataInicial)
		->with('dataFinal', $dataFinal)
		->with('status', $status)
		->with('somaContas', $somaContas)
		->with('graficoJs', true)
		->with('infoDados', "Contas filtradas")
		->with('title', 'Filtro Contas a Pagar');
	}

	public function salvarParcela(Request $request){
		$parcela = $request->parcela;

		$valorParcela = str_replace(".", "", $parcela['valor_parcela']);
		$valorParcela = str_replace(",", ".", $valorParcela);
		$valorParcela = str_replace(" ", "", $valorParcela);

		$result = ContaPagar::create([
			'compra_id' => $parcela['compra_id'],
			'data_vencimento' => $this->parseDate($parcela['vencimento']),
			'data_pagamento' => $this->parseDate($parcela['vencimento']),
			'valor_integral' => $valorParcela,
			'valor_pago' => 0,
			'status' => false,
			'referencia' => $parcela['referencia'],
			'categoria_id' => 1,
		]);
		echo json_encode($parcela);
	}

	public function save(Request $request){
		
		if(strlen($request->recorrencia) == 5){
			$valid = $this->validaRecorrencia($request->recorrencia);
			echo $valid;
			if(!$valid){
				session()->flash('color', 'red');
				session()->flash('message', 'Valor recorrente inválido!');
				return redirect('/contasPagar/new');
			}
		}


		$this->_validate($request);
		$result = ContaPagar::create([
			'compra_id' => null,
			'data_vencimento' => $this->parseDate($request->vencimento),
			'data_pagamento' => $this->parseDate($request->vencimento),
			'valor_integral' => str_replace(",", ".", $request->valor),
			'valor_pago' => 0,
			'status' => $request->status ? true : false,
			'referencia' => $request->referencia,
			'categoria_id' => $request->categoria_id
		]);
		
		$loopRecorrencia = $this->calculaRecorrencia($request->recorrencia);
		if($loopRecorrencia > 0){
			$diaVencimento = substr($request->vencimento, 0, 2);
			$proximoMes = substr($request->vencimento, 3, 2);
			$ano = substr($request->vencimento, 6, 4);

			while($loopRecorrencia > 0){
				$proximoMes = $proximoMes == 12 ? 1 : $proximoMes+1;
				$proximoMes = $proximoMes < 10 ? "0".$proximoMes : $proximoMes;
				if($proximoMes == 1)  $ano++;
				$d = $diaVencimento . "/".$proximoMes . "/" . $ano;

				$result = ContaPagar::create([
					'compra_id' => null,
					'data_vencimento' => $this->parseDate($d),
					'data_pagamento' => $this->parseDate($d),
					'valor_integral' => str_replace(",", ".", $request->valor),
					'valor_pago' => 0,
					'status' => false,
					'referencia' => $request->referencia,
					'categoria_id' => $request->categoria_id
				]);
				$loopRecorrencia--;
			}
		}

		session()->flash('color', 'green');
		session()->flash('message', 'Registro inserido!');

		return redirect('/contasPagar');
	}

	public function update(Request $request){
		$this->_validate($request);
		$conta = ContaPagar::
		where('id', $request->id)
		->first();

		$conta->data_vencimento = $this->parseDate($request->vencimento);
		$conta->referencia = $request->referencia;
		$conta->valor_integral = str_replace(",", ".", $request->valor);
		$conta->categoria_id = $request->categoria_id;

		$result = $conta->save();

		if($result){
			session()->flash('color', 'green');
			session()->flash('message', 'Registro editado!');
		}else{
			session()->flash('color', 'red');
			session()->flash('message', 'Ocorreu um erro!');
		}

		return redirect('/contasPagar');

	}

	private function calculaRecorrencia($recorrencia){
		if(strlen($recorrencia) == 5){
			$dataAtual = date("Y-m");
			$dif = strtotime($this->parseRecorrencia($recorrencia)) - strtotime($dataAtual);

			$meses = floor($dif / (60 * 60 * 24 * 30));

			return $meses;
		}
		return 0;
	}

	public function validaRecorrencia($rec){
		$mesAutal = date('m');
		$anoAtual = date('y');
		$temp = explode("/", $rec);
		if($anoAtual > $temp[1]) return false;
		if($temp[0] <= $mesAutal) return false;

		return true;
	}

	private function _validate(Request $request){
		$rules = [
			'referencia' => 'required',
			'valor' => 'required',
			'vencimento' => 'required',
		];

		$messages = [
			'referencia.required' => 'O campo referencia é obrigatório.',
			'valor.required' => 'O campo valor é obrigatório.',
			'vencimento.required' => 'O campo vencimento é obrigatório.'
		];
		$this->validate($request, $rules, $messages);
	}

	public function new(){
		$categorias = CategoriaConta::all();
		return view('contaPagar/register')
		->with('categorias', $categorias)
		->with('title', 'Cadastrar Contas a Pagar');
	}

	public function edit($id){
		$categorias = CategoriaConta::all();
		$conta = ContaPagar::
		where('id', $id)
		->first();

		return view('contaPagar/register')
		->with('conta', $conta)
		->with('categorias', $categorias)
		->with('title', 'Editar Contas a Pagar');
	}

	public function pagar($id){
		$categorias = CategoriaConta::all();
		$conta = ContaPagar::
		where('id', $id)
		->first();

		return view('contaPagar/pagar')
		->with('conta', $conta)
		->with('categorias', $categorias)
		->with('title', 'Pagar Conta');
	}

	public function pagarConta(Request $request){
		$conta = ContaPagar::
		where('id', $request->id)
		->first();

		$valor = str_replace(".", "", $request->valor);
		$valor = str_replace(",", ".", $valor);

		$conta->status = true;
		$conta->valor_pago = $valor;
		$conta->data_pagamento = date("Y-m-d");

		$result = $conta->save();
		if($result){
			session()->flash('color', 'green');
			session()->flash('message', 'Conta paga!');
		}else{
			session()->flash('color', 'red');
			session()->flash('message', 'Erro!');
		}
		return redirect('/contasPagar');
	}

	public function delete($id){
		$delete = ContaPagar
		::where('id', $id)
		->delete();
		if($delete){
			session()->flash('color', 'blue');
			session()->flash('message', 'Registro removido!');
		}else{
			session()->flash('color', 'red');
			session()->flash('message', 'Erro!');
		}
		return redirect('/contasPagar');
	}

	private function parseDate($date, $plusDay = false){
        if($plusDay == false)
            return date('Y-m-d', strtotime(str_replace("/", "-", $date)));
        else
            return date('Y-m-d', strtotime("+1 day",strtotime(str_replace("/", "-", $date))));
    }

	private function parseRecorrencia($rec){
		$temp = explode("/", $rec);
		$rec = "01/".$temp[0]."/20".$temp[1];
		//echo $rec;
		return date('Y-m', strtotime(str_replace("/", "-", $rec)));
	}

}
