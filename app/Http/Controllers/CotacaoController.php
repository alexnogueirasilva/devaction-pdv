<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Fornecedor;
use App\Cotacao;
use App\ItemCotacao;
use Mail;
use Dompdf\Dompdf;


class CotacaoController extends Controller
{

	public function __construct(){
        $this->middleware(function ($request, $next) {
            $value = session('user_logged');
            if(!$value){
                return redirect("/login");
            }else{
                if($value['acesso_cliente'] == 0){
                    return redirect("/sempermissao");
                }
            }
            return $next($request);
        });
    }
    
	public function index(){

		date_default_timezone_set('America/Sao_Paulo');
		$dataCurrent = date("Y-m-d");
		$dataBefore = date('Y-m-d', strtotime($dataCurrent. ' - 15 days'));

		$cotacoes = Cotacao::
		whereRaw("DATE_FORMAT(data_registro, '%Y-%m-%d') BETWEEN '$dataBefore' AND '$dataCurrent'")
		->orderBy('id', 'desc')
		->get();


		return view('cotacao/list')
		->with('cotacoes', $cotacoes)
		->with('title', 'Cotações');

	}

	public function filtro(Request $request){
		$data_final = $request->data_final;
		$data_inicial = $request->data_inicial;
		$fornecedor = $request->fornecedor;
		if($data_final && $data_final){
			$data_inicial = $this->parseDate($data_inicial);
			$data_final = $this->parseDate($data_final, true);
		}

		$cotacoes = Cotacao::
		select('cotacaos.*')
		->join('fornecedors', 'fornecedors.id', '=', 'cotacaos.fornecedor_id')
		->orWhere(function($q) use ($fornecedor){
			if($fornecedor){
				return $q->where('fornecedors.razao_social', 'LIKE', "%$fornecedor%");
			}
		})

		->Where(function($q) use ($data_inicial, $data_final){
			if($data_final && $data_final){
				return $q->whereBetween('cotacaos.created_at', [$data_inicial, 
					$data_final]);
			}
		})

		
		->orderBy('cotacaos.id', 'desc')
		->get();

		return view('cotacao/list')
		->with('cotacoes', $cotacoes)
		->with('data_final', $request->data_final)
		->with('data_inicial', $request->data_inicial)
		->with('fornecedor', $request->fornecedor)
		->with('title', 'Cotações');
	}

	private static function parseDate($date, $plusDay = false){
		if($plusDay == false)
			return date('Y-m-d', strtotime(str_replace("/", "-", $date)));
		else
			return date('Y-m-d', strtotime("+1 day",strtotime(str_replace("/", "-", $date))));
	}

	public function clonar($id){

		$cotacao = Cotacao::
		where('id', $id)
		->first();

		return view('cotacao/clone')
		->with('cotacaoJs', true)
		->with('cloneJs', true)
		->with('cotacao', $cotacao)
		->with('title', 'Clonar Cotação');


	}

	public function clonarSave(Request $request){
		$data = $request->data;
		$cotacao = Cotacao
		::where('id', $data['cotacao'])
		->first();
		$tst = 0;
		foreach($data['fornecedores'] as $f){
			$forn = Fornecedor::
			where('id', $f)
			->first();

			$result = Cotacao::create([ 
				'forma_pagamento' => '*',
				'responsavel' => '',
				'valor' => 0,
				'desconto' => 0,
				'fornecedor_id' => $forn->id,
				'link' => $this->generateRandomString(20),
				'referencia' => $cotacao->referencia,
				'observacao' => $cotacao->observacao,
				'resposta' => false,
				'ativa' => true,
				'escolhida' => false

			]);


			foreach($cotacao->itens as $i){
				$itemResult = ItemCotacao::create([ 
					'cotacao_id' => $result->id,
					'produto_id' => $i->produto->id,
					'valor' => 0,
					'quantidade' => $i->quantidade
				]);
			}


		}
		echo json_encode($cotacao);
	}

	public function searchProvider(Request $request){
		if($this->logged){
			$providers = Provider::
			all();

			$vehicles = Vehicle::
			all();

			$quotes = Price::
			where('provider_id', $request->input('provider_id'))
			->orderBy('id', 'desc')
			->get();

			return view('quotes/list')
			->with('providers', $providers)
			->with('quotes', $quotes)
			->with('vehicles', $vehicles)
			->with('title', 'Cotações');
		}else{
			return redirect("/login");
		}
	}

	public function searchPiece(Request $request){
		if($this->logged){
			$providers = Provider::
			all();

			$vehicles = Vehicle::
			all();
			$search = $request->input('search');

			date_default_timezone_set('America/Sao_Paulo');
			$quotes = Price::
            //whereRaw("DATE_FORMAT(date_register, '%Y-%m-%d') BETWEEN '$dataBefore' AND '$dataCurrent'")
			selectRaw('prices.*')
			->join('piece_prices', 'piece_prices.price_id', '=', 'prices.id')
			->join('pieces', 'pieces.id', '=', 'piece_prices.piece_id')
			->where('pieces.description', 'like', "%$search%")
			->get();

			return view('quotes/list')
			->with('providers', $providers)
			->with('quotes', $quotes)
			->with('vehicles', $vehicles)
			->with('title', 'Cotações');
		}else{
			return redirect("/login");
		}
	}

	public function new(){

		return view('cotacao/register')
		->with('cotacaoJs', true)
		->with('title', 'Nova Cotação');
	}

	public function salvar(Request $request){
		$cotacao = $request->cotacao;
		$result = Cotacao::create([ 
			'forma_pagamento' => '*',
			'responsavel' => '',
			'valor' => 0,
			'desconto' => 0,
			'fornecedor_id' => $cotacao['fornecedor'],
			'link' => $this->generateRandomString(20),
			'referencia' => $cotacao['referencia'] ?? '',
			'observacao' => $cotacao['observacao'] ?? '',
			'resposta' => false,
			'ativa' => true,
			'escolhida' => false

		]);

		$itens = $cotacao['itens'];

		foreach($itens as $i){
			$itemResult = ItemCotacao::create([ 
				'cotacao_id' => $result->id,
				'produto_id' => (int)$i['codigo'],
				'valor' => 0,
				'quantidade' => str_replace(",", ".", $i['quantidade'])
			]);
		}

		echo json_encode($result);

	}

	private function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

    // PEÇAS DA COTAÇÂO
	public function edit($id){
		$cotacao = Cotacao::
		where('id', $id)
		->first();

		return view('cotacao/edit')
		->with('cotacao', $cotacao)
		->with('cotacaoJs', true)
		->with('title', 'Editar Cotação');

		echo json_encode($cotacao);
	}



	public function deleteItem($id){
		$item = ItemCotacao::
		where('id', $id)
		->first();

		$cotacaoId = $item->cotacao->id;

		if($item->delete()){
			session()->flash('color', 'green');
			session()->flash('message', 'Cotação removida!');
		}else{
			session()->flash('color', 'red');
			session()->flash('message', 'Erro');
		}


		return redirect("/cotacao/edit/$cotacaoId");

	}

	public function saveItem(Request $request){

		$cotacao = Cotacao::
		where('id', $request->id)
		->first();

		$produto = $request->input('produto');
		$produto = explode("-", $produto);
		$produto = $produto[0];

		$result = ItemCotacao::create([ 
			'cotacao_id' => $cotacao->id,
			'produto_id' => (int)$produto,
			'valor' => 0,
			'quantidade' => str_replace(",", ".", $request->quantidade)
		]);

		if($result){
			session()->flash('color', 'green');
			session()->flash('message', 'Produto adicionado!');
		}else{
			session()->flash('color', 'red');
			session()->flash('message', 'Erro');
		}


		return redirect("/cotacao/edit/$cotacao->id");

	}

	public function delete($id){

		if(Cotacao::where('id', $id)->delete()){
			session()->flash('color', 'green');
			session()->flash('message', 'Cotação removida!');
		}else{
			session()->flash('color', 'red');
			session()->flash('message', 'Erro');
		}
		return redirect("/cotacao");

	}

	public function alterarStatus($id, $status){

		$cotacao = Cotacao::
		where('id', $id)
		->first();

		$cotacao->ativa = $status;
		$result = $cotacao->save();

		if($result){
			session()->flash('color', 'green');
			session()->flash('message', 'Cotação ' . 
				($status == 1 ? 'Ativada!' : 'Desativada!'));
		}else{
			session()->flash('color', 'red');
			session()->flash('message', 'Erro');
		}
		return redirect("/cotacao");

	}

	public function view($id){

		$cotacao = Cotacao::
		where('id', $id)
		->first();

		return view('cotacao/view')
		->with('cotacao', $cotacao)
		->with('title', 'Cotação');

	}


	public function responseSave(Request $request){
		$js = $request->js;
		$return = true;
		foreach($js as $key => $j){
			$temp = json_decode(json_encode($j));
            if(isset($temp->value)){ // Editar Peças da Cotacao
            	$pieceQuote = PiecePrice::
            	where('id', $temp->id)
            	->first();

            	$pieceQuote->cost = str_replace(",", ".", $temp->value);

            	$pieceQuote->note = $temp->note ?? "";

            	$pieceQuote->save();

            	$piece = Piece::
            	where('id', $pieceQuote->piece_id)
            	->first();

            	$piece->code = $temp->code;

            	if($return)
            		$return = $piece->save();


            }else{ // Editar Cotação
            	$price = Price::
            	where('id', $temp->price_id)
            	->first();

            	$price->cost = $temp->total;
            	$price->responsible = $temp->responsible;
            	$price->payment_form = $temp->payment_form ?? "";
            	$price->response = true;
            	if($return)
            		$return = $price->save();

            }
        }
        echo json_encode($return);
    }

    public function sendMail($id){
    	$cotacao = Cotacao::
    	where('id', $id)
    	->first();
    	$pathUrl = getenv('PATH_URL');

    	Mail::send('mail.cotacao', ['link' => 
    		"$pathUrl/response/$cotacao->link"], 

    		function($message) use ($cotacao){
    			$nomeEmpresa = getenv('SMS_NOME_EMPRESA');
    			$nomeEmpresa = str_replace("_", " ",  $nomeEmpresa);
    			$nomeEmpresa = str_replace("_", " ",  $nomeEmpresa);

    			$emailEnvio = getenv('MAIL_USERNAME');

    			$message->to($cotacao->fornecedor->email)->subject('Cotação de Serviço/Compra');
    			$message->from($emailEnvio, $nomeEmpresa);
    			$message->subject('Envio de Cotação ' . $cotacao->id);

    		});

    	session()->flash('color', 'green');
    	session()->flash('message', 'EMAIL ENVIADO');

    	return redirect("/cotacao");

    }

    public function listaPorReferencia(){
    	$cotacoes = Cotacao::
    	select(\DB::raw('referencia'))
    	->where('referencia', '!=', '*')
    	->groupBy('referencia')
    	->get();

    	return view('cotacao/lista_referencia')
    	->with('title', 'Cotações Por Referência')
    	->with('cotacoes', $cotacoes);
    }

    public function listaPorReferenciaFiltro(Request $request){
    	$data_final = $request->data_final;
    	$data_inicial = $request->data_inicial;
    	$fornecedor = $request->fornecedor;


    	if($data_final && $data_final){
    		$data_inicial = $this->parseDate($data_inicial);
    		$data_final = $this->parseDate($data_final, true);
    	}

    	$cotacoes = Cotacao::
    	select(\DB::raw('referencia'))
    	->join('fornecedors', 'fornecedors.id', '=', 'cotacaos.fornecedor_id')
    	

    	->Where(function($q) use ($fornecedor){
    		if($fornecedor){
    			return $q->where('fornecedors.razao_social', 'LIKE', "%$fornecedor%");
    		}
    	})
    	->Where(function($q) use ($data_inicial, $data_final){
    		if($data_final && $data_final){
    			return $q->whereBetween('cotacaos.created_at', [$data_inicial, 
    				$data_final]);
    		}
    	})
    	->where('referencia', '!=', '*')

    	->groupBy('referencia')
    	->get();


    	return view('cotacao/lista_referencia')
    	->with('title', 'Cotações Por Referência')
    	->with('data_final', $request->data_final)
    	->with('data_inicial', $request->data_inicial)
    	->with('fornecedor', $request->fornecedor)
    	->with('cotacoes', $cotacoes);
    }

    public function referenciaView($referencia){
    	$cotacoes = Cotacao::
    	where('referencia', $referencia)
    	->where('valor', '>', 0)
    	->get();

    	if(count($cotacoes) > 0){
    		$itens = $this->preparaItens($cotacoes);

    		$fornecedores = [];
    		foreach($itens as $i){
    			if(!$this->estaNoArray($fornecedores, $i)){
    				array_push($fornecedores, 
    					[
    						'fornecedor' => $i['fornecedor'],
    						'qtd' => 1
    					]
    				);
    			}else{
    				for($aux = 0; $aux < count($fornecedores); $aux++){
    					if($fornecedores[$aux]['fornecedor'] == $i['fornecedor']) $fornecedores[$aux]['qtd'] += 1;
    				}

    			}
    		}

    		$melhorResultado = $cotacoes[0];

    		foreach($cotacoes as $c){
    			if($c->valor < $melhorResultado->valor) $melhorResultado = $c;
    		}

    		return view('cotacao/ver_resultados')
    		->with('title', 'Cotações Por Referência')
    		->with('itens', $itens)
    		->with('melhorResultado', $melhorResultado)
    		->with('fornecedores', $fornecedores)
    		->with('cotacoes', $cotacoes);
    	}else{
    		session()->flash('color', 'red');
    		session()->flash('message', 'Referência sem nehuma resposta!');
    		return redirect('/cotacao/listaPorReferencia');
    	}
    }

    private function estaNoArray($arr, $elem){
    	foreach($arr as $a){
    		if($a['fornecedor'] == $elem['fornecedor']) return true;
    	}
    	return false;
    }

    private function preparaItens($cotacoes){

    	if(count($cotacoes) > 0){
    	// echo $cotacoes;
    		$melhoresItens = $this->itemInicial($cotacoes[0]);
    		// print_r($itemInicial);

    		foreach($cotacoes as $c){
    			foreach($c->itens as $i){
    				for($aux = 0; $aux < count($melhoresItens); $aux++){
    					if($melhoresItens[$aux]['item'] == $i->produto->nome){
    						$valorTemp = $i->valor * $i->quantidade;
    						if($valorTemp < $melhoresItens[$aux]['valor_total']){
    							$melhoresItens[$aux]['valor_total'] = $valorTemp;
    							$melhoresItens[$aux]['valor_unitario'] = $i->valor;
    							$melhoresItens[$aux]['fornecedor'] = $c->fornecedor->razao_social;
    						}
    					}
    				}
    			}

    		}

    		// print_r($melhoresItens);
    		return $melhoresItens;
    	}
    }

    private function itemInicial($cotacao){
    	$itens = [];
    	foreach($cotacao->itens as $i){
    		$temp = [
    			'item' => $i->produto->nome,
    			'valor_unitario' => $i->valor,
    			'quantidade' => $i->quantidade,
    			'valor_total' => $i->valor * $i->quantidade,
    			'fornecedor' => $cotacao->fornecedor->razao_social
    		];
    		array_push($itens, $temp);
    	}
    	return $itens;
    }

    public function escolher($id){
    	$cotacao = Cotacao::find($id);
    	$cotacao->escolhida = true;
    	$cotacao->save();
    	session()->flash('color', 'green');
    	session()->flash('message', 'Cotação escolhida para referencia ' . $cotacao->referencia . '!');
    	return redirect('/cotacao/listaPorReferencia');
    }

    public function imprimirMelhorResultado(Request $request){
    	$fornecedor = $request->fornecedor;
    	$referencia = $request->referencia;

    	$cotacoes = Cotacao::
    	where('referencia', $referencia)
    	->where('valor', '>', 0)
    	->get();

    	$temp = [];
    	if(count($cotacoes) > 0){
    		$itens = $this->preparaItens($cotacoes);

    		foreach($itens as $i){
    			if($i['fornecedor'] == $fornecedor){
    				array_push($temp, $i);
    			}
    		}

    	}

    	$fornecedorByNome = Fornecedor::where('razao_social', $fornecedor)->first();
    	$p = view('cotacao/relatorio')
		->with('cotacao', $cotacoes[0])
		->with('fornecedor', $fornecedorByNome)
		->with('itens', $temp);

		// return $p;



		$domPdf = new Dompdf(["enable_remote" => true]);
		$domPdf->loadHtml($p);

		$pdf = ob_get_clean();

		$domPdf->setPaper("A4");
		$domPdf->render();
		$domPdf->stream("Refencia_{$cotacoes[0]->referencia}_Fornecedor_{$fornecedorByNome->razao_social}.pdf");
    }

}
