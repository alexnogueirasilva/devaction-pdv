<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PedidoDelivery;
use App\ItemPedidoDelivery;
use App\DeliveryConfig;
use App\ClienteDelivery;
use NFePHP\DA\NFe\PedidoPrint;
use App\VendaCaixa;
use App\ConfigNota;
use Comtele\Services\CreditService;
use Comtele\Services\TextMessageService;
use App\EnderecoDelivery;
use App\ProdutoDelivery;
use App\Produto;
use App\ItemPizzaPedido;
use App\ComplementoDelivery;
use App\ItemPedidoComplementoDelivery;

class PedidoDeliveryController extends Controller
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

	public function today(){
		
		$pedidosNovo = $this->filtroPedidos(date("Y-m-d"),
			date('Y-m-d', strtotime('+1 day')), 'nv');

		$pedidosAprovado = $this->filtroPedidos(date("Y-m-d"),
			date('Y-m-d', strtotime('+1 day')), 'ap');

		$pedidosRecusado = $this->filtroPedidos(date("Y-m-d"),
			date('Y-m-d', strtotime('+1 day')), 'rc');

		$pedidosReprovaco = $this->filtroPedidos(date("Y-m-d"),
			date('Y-m-d', strtotime('+1 day')), 'rp');

		$pedidosFinalizado = $this->filtroPedidos(date("Y-m-d"),
			date('Y-m-d', strtotime('+1 day')), 'fz');

		$carrinho = $this->filtroPedidos(date("Y-m-d"),
			date('Y-m-d', strtotime('+1 day')), 'nv', '=');


		return view('pedidosDelivery/list')
		->with('tipo', 'Pedidos de Hoje')
		->with('pedidosNovo', $pedidosNovo)
		->with('pedidosAprovado', $pedidosAprovado)
		->with('pedidosRecusado', $pedidosRecusado)
		->with('pedidosReprovaco', $pedidosReprovaco)
		->with('pedidosFinalizado', $pedidosFinalizado)
		->with('carrinho', $carrinho)
		->with('somaNovos', $this->somaPedidos($pedidosNovo))
		->with('somaAprovados', $this->somaPedidos($pedidosAprovado))
		->with('somaRecusados', $this->somaPedidos($pedidosRecusado))
		->with('somaReprovados', $this->somaPedidos($pedidosReprovaco))
		->with('somaFinalizados', $this->somaPedidos($pedidosFinalizado))
		->with('carrinho', $this->somaCarrinho($carrinho))
		->with('title', 'Pedidos de Delivery');

	}

	private function somaPedidos($arr){
		$v = 0;
		foreach($arr as $r){

			$v += $r->somaItens();
		}
		return $v;
	}

	private function somaCarrinho($arr){
		$v = 0;
		foreach($arr as $r){
			$v += $r->somaCarrinho();
		}
		return $v;
	}

	public function filtro(Request $request){
		$dataInicial = $request->data_inicial;
		$dataFinal = $request->data_final;

		$pedidosNovo = $this->filtroPedidos($this->parseDate($dataInicial),
			$this->parseDate($dataFinal, true), 'nv');

		$pedidosAprovado = $this->filtroPedidos($this->parseDate($dataInicial),
			$this->parseDate($dataFinal, true), 'ap');

		$pedidosRecusado = $this->filtroPedidos($this->parseDate($dataInicial),
			$this->parseDate($dataFinal, true), 'rc');

		$pedidosReprovaco = $this->filtroPedidos($this->parseDate($dataInicial),
			$this->parseDate($dataFinal, true), 'rp');

		$pedidosFinalizado = $this->filtroPedidos($this->parseDate($dataInicial),
			$this->parseDate($dataFinal, true), 'fz');

		$carrinho = $this->filtroPedidos($this->parseDate($dataInicial),
			$this->parseDate($dataFinal, true), 'nv', '=');

		return view('pedidosDelivery/list')
		->with('tipo', 'Pedidos de Hoje')
		->with('pedidosNovo', $pedidosNovo)
		->with('pedidosAprovado', $pedidosAprovado)
		->with('pedidosRecusado', $pedidosRecusado)
		->with('pedidosReprovaco', $pedidosReprovaco)
		->with('pedidosFinalizado', $pedidosFinalizado)
		->with('somaNovos', $this->somaPedidos($pedidosNovo))
		->with('somaAprovados', $this->somaPedidos($pedidosAprovado))
		->with('somaRecusados', $this->somaPedidos($pedidosRecusado))
		->with('somaReprovados', $this->somaPedidos($pedidosReprovaco))
		->with('somaFinalizados', $this->somaPedidos($pedidosFinalizado))
		->with('carrinho', $this->somaCarrinho($carrinho))
		->with('dataInicial', $dataInicial)
		->with('dataFinal', $dataFinal)
		->with('title', 'Pedidos de Delivery periodo '. $dataInicial . ' até ' . $dataFinal);
	}

	public function verCarrinhos(){
		$pedidos = PedidoDelivery::
		where('estado', 'nv')
		->where('valor_total', 0)
		->get();

		return view('pedidosDelivery/carrinhos')
		->with('pedidos', $pedidos)
		->with('title', 'Carrinhos em Aberto');
	}

	public function verCarrinho($id){
		$pedido = PedidoDelivery::
		where('id', $id)
		->first();

		return view('pedidosDelivery/verCarrinho')
		->with('pedido', $pedido)
		->with('title', 'Itens do Carrinho Aberto');
	}

	public function push($id){
		$pedido = PedidoDelivery::
		where('id', $id)
		->first();

		return view('push/new')
		->with('pushJs', true)
		->with('titulo', $this->randomTitles())
		->with('mensagem', $this->randomMensagem($pedido))
		->with('title', 'Nova Push');
	}

	private function randomTitles(){
		$titles = [
			'Fecha seu carrinho conosco',
			'Vamos finaizar o pedido',
			'Não perca isso',
			'Não deixe de finalizar'
		];
		return $titles[rand(0,3)];
	}

	private function randomMensagem($pedido){
		$messages = [
			'Seu carrinho esta em, R$ '. number_format($pedido->somaItens(), 2),
			'Vamos fechar este pedido preparamos um desconto para você',
			'Finalize já este carrinho conosco, preparamos o melhor para você :)',
		];
		return $messages[rand(0,2)];
	}

	public function verPedido($id){
		$pedido = PedidoDelivery
		::where('id', $id)
		->first();

		$saldoSms = 0;
		try{
			$creditService = new CreditService(getenv('SMS_KEY'));

			if(getenv("SMS_KEY") != '') {
				$saldoSms = $creditService->get_my_credits();
			} 

			return view('pedidosDelivery/detalhe')
			->with('tipo', 'Detalhes do Pedido')
			->with('pedido', $pedido)
			->with('saldoSms', $saldoSms)
			->with('pedidoDeliveryJs', true)
			->with('title', 'Pedidos de Delivery');
		}catch(\Exception $e){
			echo "Você esta com promlemas na API Comtele SMS, deixe em branco no arquivo .env!";
		}
	}

	public function alterarStatus($id){
		$item = ItemPedidoDelivery
		::where('id', $id)
		->first();

		$item->status = true;
		$item->save();
		return redirect("/pedidosDelivery/verPedido/".$item->pedido->id);
	}

	public function alterarPedido(Request $request){
		$id = $request->id;
		$tipo = $request->tipo;

		$pedido = PedidoDelivery
		::where('id', $id)
		->first();

		return view('pedidosDelivery/alterarEstado')
		->with('tipo', 'Detalhes do Pedido')
		->with('pedido', $pedido)
		->with('tipo', $tipo)
		->with('title', 'Pedidos de Delivery');
	}

	public function emAberto(){
		$pedidos = PedidoDelivery::
		where('estado', 'nv')
		->where('valor_total', '>', 0)
		->get();

		return response()->json(count($pedidos), 200);
	}

	public function confirmarAlteracao(Request $request){
		$config = ConfigNota::first();

		if($config == null){
			session()->flash('color', 'red');
			session()->flash('message', 'Defina a configuração do emitente para continuar!');
			return redirect()->back();
		}

		$id = $request->id;
		$tipo = $request->tipo;

		$pedido = PedidoDelivery
		::where('id', $id)
		->first();

		$valorEntrega = 0;
		// if($pedido->endereco_id != NULL){
		// 	$config = DeliveryConfig::first();
		// 	$valorEntrega = $config->valor_entrega;
		// }

		$pedido->estado = $tipo;
		$pedido->motivoEstado = $request->motivoEstado ?? '';

		$pedido->save();

		$observacao = "Pedido: " . $pedido->id . ", Cliente: " . $pedido->cliente->nome . " " 
		.$pedido->cliente->sobre_nome .
		($pedido->endereco_id !=  NULL ? 
			" - Endereço: " . $pedido->endereco->rua .", " . $pedido->endereco->numero ." - "
			.$pedido->endereco->bairro : '');

		if($tipo == 'fz'){
			//Abrir frente de caixa

			$tiposPagamento = VendaCaixa::tiposPagamento();
			return view('frontBox/main')
			->with('itens', $this->addAtributes($pedido->itens))
			->with('frenteCaixa', true)
			->with('delivery_id', $pedido->id)
			->with('valor_total', $pedido->valor_total)
			->with('tiposPagamento', $tiposPagamento)
			->with('config', $config)
			->with('observacao', $observacao)
			->with('valor_entrega', $valorEntrega)
			->with('title', 'Finalizar Comanda '.$id);
		}else{
			session()->flash('color', 'green');
			session()->flash('message', 'Pedido Alterado!');
			return redirect('/pedidosDelivery');
		}

	}

	private function addAtributes($itens){
		$temp = [];
		foreach($itens as $i){
			$i->produto;

			$valorAdicional = 0;

			foreach($i->itensAdicionais as $ad){
				$valorAdicional += $ad->adicional->valor;
			}

			$i->valorAdicional = $valorAdicional;
			$somaValores = 0;
			if(sizeof($i->sabores) > 0){
				$i->sabores;

				$maiorValor = 0;

				foreach($i->sabores as $sb){

					
					$sb->produto->produto;
					
					$v = $sb->maiorValor($sb->sabor_id, $i->tamanho_id);
					$somaValores += $v;
					if($v > $maiorValor) $maiorValor = $v;


				}
				if(getenv("DIVISAO_VALOR_PIZZA") == 1){
					$maiorValor = $somaValores/sizeof($i->sabores);
				}

				$i->maiorValor = $maiorValor;
			}else{
				$i->produto->valor_venda = $i->produto->valor;
			}
			$i->produto_id = $i->produto->produto->id; // converte id de delivery para produto comum
			$i->produto->nome = $i->produto->produto->nome;
			array_push($temp, $i);
		}
    	// echo json_encode($temp);
		return $temp;
	}

	public function irParaFrenteCaixa($id){

		$pedido = PedidoDelivery
		::where('id', $id)
		->first();

		$config = ConfigNota::first();
		$tiposPagamento = VendaCaixa::tiposPagamento();

		$observacao = "Pedido: " . $pedido->id . ", Cliente: " . $pedido->cliente->nome . " " 
		.$pedido->cliente->sobre_nome .
		($pedido->endereco_id !=  NULL ? 
			" - Endereço: " . $pedido->endereco->rua .", " . $pedido->endereco->numero ." - "
			.$pedido->endereco->bairro : '');

		// if($pedido)
		return view('frontBox/main')
		->with('itens', $this->addAtributes($pedido->itens))
		->with('valor_total', $pedido->valor_total)
		->with('frenteCaixa', true)
		->with('tiposPagamento', $tiposPagamento)
		->with('config', $config)
		->with('observacao', $observacao)
		->with('title', 'Finalizar Comanda '.$id);
		
	}

	private function parseDate($date, $plusDay = false){
		if($plusDay == false)
			return date('Y-m-d', strtotime(str_replace("/", "-", $date)));
		else
			return date('Y-m-d', strtotime("+1 day",strtotime(str_replace("/", "-", $date))));
	}

	private function filtroPedidos($dataInicial, $dataFinal, $estado, $sinal = '>'){
		$pedidos = PedidoDelivery::
		whereBetween('data_registro', [$dataInicial, 
			$dataFinal])
		->where('estado', $estado)
		->where('valor_total', $sinal, 0)
		->get();
		return $pedidos;
	}

	public function print($id){
		$pedido = PedidoDelivery::
		where('id', $id)
		->first();

		$public = getenv('SERVIDOR_WEB') ? 'public/' : '';
		$ped = new PedidoPrint($pedido);
		$ped->monta();
		$pdf = $ped->render();

		header('Content-Type: application/pdf');

		file_put_contents($public.'pdf/PEDIDODELIVERY.pdf',$pdf);
		return redirect($public.'pdf/PEDIDODELIVERY.pdf');
		// echo $pdf;
	}

	public function sendSms(Request $request){

		$phone = $request['telefone'];
		$msg = $request['texto'];
		$res = $this->sendGo($phone, $msg);
		echo json_encode($res);
	}

	private function sendGo($phone, $msg){
		$nomeEmpresa = getenv('SMS_NOME_EMPRESA');
		$nomeEmpresa = str_replace("_", " ",  $nomeEmpresa);
		$nomeEmpresa = str_replace("_", " ",  $nomeEmpresa);
		$content = $msg . " Att, $nomeEmpresa";
		$textMessageService = new TextMessageService(getenv('SMS_KEY'));
		$res = $textMessageService->send("Sender", $content, [$phone]);
		return $res;
	}

	public function sendPush(Request $request){
		$cliente = ClienteDelivery::where('id', $request->cliente)
		->first();
		$tkTemp = [];
		if(count($cliente->tokens) > 0){
			foreach($cliente->tokens as $t){
				if(!in_array($t->token, $tkTemp)){

					array_push($tkTemp, $t->user_id);
				}
			}

			$data = [
				'heading' => [
					"en" => $request->titulo
				],
				'content' => [
					"en" => $request->texto
				],
				'image' => $request->imagem ?? '',
				'referencia_produto' => 0,
			];

			$this->sendMessageOneSignal($data, $tkTemp);
		}
		echo json_encode('sucesso');

	}

	public function sendMessageOneSignal($data, $tokens = null){

		$fields = [
			'app_id' => getenv('ONE_SIGNAL_APP_ID'),
			'contents' => $data['content'],
			'headings' => $data['heading'],
			'large_icon' => getenv('PATH_URL').'/imgs/logo.png',
			'small_icon' => 'notification_icon'
		];

		if($data['image'] != '')
			$fields['big_picture'] = $data['image'];

		if($tokens == null){
			$fields['included_segments'] = array('All');
			if($data['image'] != '')
				$fields['chrome_web_image'] = $data['image'];
		}else{
			$fields['include_player_ids'] = $tokens;
		}


		if($data['referencia_produto'] > 0){
			$fields['web_url'] = getenv('PATH_URL') . "/cardapio/verProduto/" . $data['referencia_produto'];
			$produtoDelivery = ProdutoDelivery::find($data['referencia_produto']);
			if($produtoDelivery != null){
				$produtoDelivery->pizza;
				$produtoDelivery->galeria;
				$produtoDelivery->categoria;
				$produtoDelivery->produto;
				$fields['data'] = ["referencia" => $produtoDelivery];
			}
		}

		$fields = json_encode($fields);
		print("\nJSON sent:\n");
		print($fields);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
			'Authorization: Basic '.getenv('ONE_SIGNAL_KEY')));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		$response = curl_exec($ch);
		curl_close($ch);
		return $response;
	}

	public function frente(){

		return view('pedidosDelivery/frente')
		->with('frentePedidoDeliveryJs', true)
		->with('title', 'Frente de Pedido');

	}

	public function clientes(){
		$clientes = ClienteDelivery::all();
		$arr = array();
		foreach($clientes as $c){
			$arr[$c->id. ' - ' .$c->nome] = null;
                //array_push($arr, $temp);
		}
		return response()->json($arr, 200);
	}

	public function abrirPedidoCaixa(Request $request){

		if(isset($request->cliente)){
			$pedidoEmAberto = PedidoDelivery::where('estado', 'nv')
			->where('cliente_id', $request->cliente)
			->first();
			if($pedidoEmAberto == null){
				$pedido = PedidoDelivery::create([
					'cliente_id' => $request->cliente,
					'valor_total' => 0,
					'telefone' => '',
					'observacao' => '',
					'forma_pagamento' => '',
					'estado'=> 'nv',
					'motivoEstado'=> '',
					'endereco_id' => NULL,
					'troco_para' => 0,
					'desconto' => 0,
					'cupom_id' => NULL,
					'app' => false
				]);
				return response()->json($pedido, 200);

			}else{
				session()->flash('color', 'red');
				session()->flash('message', 'Este cliente possui um pedido em aberto, PEDIDO ' . $pedidoEmAberto->id. '!');
				return response()->json($pedidoEmAberto, 200);

			}
		}
		return response()->json(false, 403);

	}

	public function novoClienteDeliveryCaixa(Request $request){
		$cli = ClienteDelivery::create(
			[
				'nome' => $request->nome,
				'sobre_nome' => $request->sobre_nome,
				'celular' => $request->celular,
				'email' => '',
				'token' => '',
				'ativo' => 1,
				'senha' => md5(rand(1000, 9999))
			]
		);
		if($cli){
			// novo cliente renderiza nova view caixa
			$cliente = ClienteDelivery::find($cli->id);
			$pedido = PedidoDelivery::create([
				'cliente_id' => $cliente->id,
				'valor_total' => 0,
				'telefone' => '',
				'observacao' => '',
				'forma_pagamento' => '',
				'estado'=> 'nv',
				'motivoEstado'=> '',
				'endereco_id' => NULL,
				'troco_para' => 0,
				'desconto' => 0,
				'cupom_id' => NULL,
				'app' => false
			]);
			// criou o pedido
			if($pedido){
				return redirect('pedidosDelivery/frenteComPedido/'.$pedido->id);
			}
		}else{
			return redirect('pedidosDelivery/frenteErro');
		}
	}

	public function frenteComPedido($id){
		$pedido = PedidoDelivery::find($id);

		if($pedido->estado == 'ap' || $pedido->valor_total > 0){
			return redirect('/pedidosDelivery/verPedido/' . $pedido->id);
		}
		$config = DeliveryConfig::first();

		return view('pedidosDelivery/frente')
		->with('frentePedidoDeliveryJs', true)
		->with('frentePedidoDeliveryPedidoJs', true)
		->with('pedido', $pedido)
		->with('config', $config)
		->with('title', 'Frente de Pedido');

	}

	public function setEnderecoCaixa(Request $request){
		$pedido = PedidoDelivery::find($request->pedido_id);
		$pedido->endereco_id = $request->endereco;
		if($request->endereco == 'NULL') $pedido->endereco_id = NULL;
		$pedido->save();
		return response()->json($pedido, 200);
	}

	public function novoEnderecoClienteCaixa(Request $request){
		$pedido = PedidoDelivery::find($request->pedido_id);

		$endereco = EnderecoDelivery::create(
			[
				'cliente_id' => $pedido->cliente_id,
				'rua' => $request->rua ?? '',
				'numero' => $request->numero ?? '',
				'bairro' => $request->bairro ?? '',
				'referencia' => $request->referencia ?? '',
				'latitude' => '',
				'longitude' => ''
			]
		);

		$pedido->endereco_id = $endereco->id;
		$pedido->save();
		return redirect('/pedidosDelivery/frenteComPedido/'.$pedido->id);
	}

	public function saveItemCaixa(Request $request){
		$pedido = PedidoDelivery::find($request->pedido_id);

		$this->_validateItem($request);

		$produto = $request->input('produto');
		$produto = explode("-", $produto);
		$produto = $produto[0];

		$result = ItemPedidoDelivery::create([
			'pedido_id' => $pedido->id,
			'produto_id' => $produto,
			'quantidade' => str_replace(",", ".", $request->quantidade),
			'status' => false,
			'tamanho_id' => $request->tamanho_pizza_id ?? NULL,
			'observacao' => $request->observacao ?? '',
			'valor' => str_replace(",", ".", $request->valor)
		]);

		$saborDup = false;
		if($request->tamanho_pizza_id && $request->sabores_escolhidos){
			$saborDup = false;

			$sabores = explode(",", $request->sabores_escolhidos);
			if(count($sabores) > 0){
				foreach($sabores as $sab){
					$prod = Produto
					::where('nome', $sab)
					->first();

					$item = ItemPizzaPedido::create([
						'item_pedido' => $result->id,
						'sabor_id' => $prod->delivery->id,
					]);

					if($prod->id == $produto) $saborDup = true;
				}
			}else{
				$item = ItemPizzaPedido::create([
					'item_pedido' => $result->id,
					'sabor_id' => $produto_id,
				]);
			}
		}

		if(!$saborDup && $request->tamanho_pizza_id){

			$item = ItemPizzaPedido::create([
				'item_pedido' => $result->id,
				'sabor_id' => $produto,
			]);

		}

		else if($request->tamanho_pizza_id){

			$item = ItemPizzaPedido::create([
				'item_pedido' => $result->id,
				'sabor_id' => $produto,
			]);
		}


		if($request->adicioanis_escolhidos){
			$adicionais = explode(",", $request->adicioanis_escolhidos);
			foreach($adicionais as $ad){
				$nome = explode("-", $ad);

				$adicional = ComplementoDelivery
				::where('nome', $nome)
				->first();


				$item = ItemPedidoComplementoDelivery::create([
					'item_pedido_id' => $result->id,
					'complemento_id' => $adicional->id,
					'quantidade' => str_replace(",", ".", $request->quantidade),
				]);
			}
		}

		session()->flash('color', 'green');
		session()->flash('message', 'Item Adicionado!');
		return redirect('/pedidosDelivery/frenteComPedido/'.$pedido->id);

	}

	private function _validateItem(Request $request){
		$validaTamanho = false;
		if($request->input('produto')){
			$produto = $request->input('produto');
			$produto = explode("-", $produto);
			$produto = $produto[0];

			$p = ProdutoDelivery::
			where('id', $produto)
			->first();

			if(strpos(strtolower($p->categoria->nome), 'izza') !== false){
				$validaTamanho = true;
			}
		}
		$rules = [
			'produto' => 'required|min:5',
			'quantidade' => 'required',
			'tamanho_pizza_id' => $validaTamanho ? 'required' : '',
		];

		$messages = [
			'produto.required' => 'O campo produto é obrigatório.',
			'produto.min' => 'Clique sobre o produto desejado.',
			'quantidade.required' => 'O campo quantidade é obrigatório.',
			'tamanho_pizza_id.required' => 'Selecione um tamanho.',
		];

		$this->validate($request, $rules, $messages);
	}

	public function produtos(){
		$products = ProdutoDelivery::all();
		$arr = array();
		foreach($products as $p){
			$arr[$p->id. ' - ' .$p->produto->nome] = null;
                //array_push($arr, $temp);
		}
		echo json_encode($arr);
	}

	public function deleteItem($id){
		$item = ItemPedidoDelivery::find($id);
		$item->delete();

		session()->flash('color', 'green');
		session()->flash('message', 'Item Removido!');
		return redirect('/pedidosDelivery/frenteComPedido/'.$item->pedido->id);
	}

	public function getProdutoDelivery($id){
		$produto = ProdutoDelivery::find($id);
		foreach($produto->pizza as $tp){
			$tp->tamanho;
		}
		$produto->produto;
		return response()->json($produto, 200);
	}

	public function frenteComPedidoFinalizar(Request $request){
		$pedido = PedidoDelivery::find($request->pedido_id);
		$total = $pedido->somaItens();
		if($pedido->endereco_id != NULL){
			$config = DeliveryConfig::first();
			$total -= $config->valor_entrega;
		}

		$total += str_replace(",", ".", $request->taxa_entrega);

		$pedido->valor_total = $total;
		$pedido->estado = 'ap';
		$pedido->telefone = $request->telefone;
		$pedido->troco_para = str_replace(",", ".", $request->troco_para);
		$pedido->data_registro = date('Y-m-d H:i:s');
		$pedido->save();

		session()->flash('color', 'green');
		session()->flash('message', 'Pedido realizado!');


		echo "<script>window.open('". getenv('PATH_URL') . '/pedidosDelivery/print/' . $pedido->id ."', '_blank');</script>";

		return redirect('/pedidosDelivery/frente');
	}
}
