<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Venda;
use App\NaturezaOperacao;
use App\ItemVenda;
use App\Produto;
use App\Pedido;
use App\Categoria;
use App\Tributacao;
use App\ConfigNota;
use App\Certificado;
use App\CreditoVenda;
use App\ContaReceber;
use App\Frete;
use App\Cliente;
use App\ListaPreco;
use App\Helpers\StockMove;
use App\Services\NFService;
use NFePHP\DA\NFe\Danfe;
use Dompdf\Dompdf;

class VendaController extends Controller
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

	public function lista(){
		$vendas = Venda::
		where('estado', 'DISPONIVEL')
		->where('forma_pagamento', '!=', 'conta_crediario')
		->paginate(10);

		$menos30 = $this->menos30Dias();
		$date = date('d/m/Y');

		$certificado = Certificado::first();

		return view("vendas/list")
		->with('vendas', $vendas)
		->with('nf', true)
		->with('links', true)
		->with('dataInicial', $menos30)
		->with('dataFinal', $date)
		->with('certificado', $certificado)
		->with('title', "Lista de Vendas");

	}

	public function nova(){
		$config = ConfigNota::first();
		if($config == null){
			return redirect('configNF');
		}
		$lastNF = Venda::lastNF();

		$naturezas = NaturezaOperacao::all();

		$config = ConfigNota::first();
		$categorias = Categoria::all();
		$produtos = Produto::all();
		$tributacao = Tributacao::first();
		$clientes = Cliente::all();
		$tiposPagamento = Venda::tiposPagamento();

		if(count($naturezas) == 0 || count($produtos) == 0 || $config == null || count($categorias) == 0 || $tributacao == null || count($clientes) == 0){

			return view("vendas/alerta")
			->with('produtos', count($produtos))
			->with('categorias', count($categorias))
			->with('clientes', count($clientes))
			->with('naturezas', $naturezas)
			->with('config', $config)
			->with('tributacao', $tributacao)
			->with('title', "Validação para Emitir");
		}else{
			return view("vendas/register")
			->with('naturezas', $naturezas)
			->with('vendaJs', true)
			->with('config', $config)
			->with('tiposPagamento', $tiposPagamento)
			->with('lastNF', $lastNF)
			->with('listaPreco', ListaPreco::all())
			->with('title', "Nova Venda");
		}
	}

	public function detalhar($id){
		$venda = Venda::
		where('id', $id)
		->first();

		$menos30 = $this->menos30Dias();
		$date = date('d/m/Y');
		
		return view("vendas/detalhe")
		->with('venda', $venda)
		->with('title', "Detalhe de Venda $id");
	}

	public function delete($id){
		$venda = Venda::
		where('id', $id)
		->first();

		// $this->removerDuplicadas($venda);
		$venda->delete();
		
		session()->flash('color', 'blue');
		session()->flash("message", "Venda removida!");

		return redirect('/vendas/lista');
	}

	private function removerDuplicadas($venda){
		foreach($venda->duplicatas as $dp){
			$c = ContaReceber::
			where('id', $dp->id)
			->delete();
		}
	}

	function sanitizeString($str){
		return preg_replace('{\W}', ' ', preg_replace('{ +}', ' ', strtr(
			utf8_decode(html_entity_decode($str)),
			utf8_decode('ÀÁÃÂÉÊÍÓÕÔÚÜÇÑàáãâéêíóõôúüçñ'),
			'AAAAEEIOOOUUCNaaaaeeiooouucn')));
	}

	public function salvar(Request $request){
		$venda = $request->venda;
		$valorFrete = str_replace(".", "", $venda['valorFrete'] ?? 0);
		$valorFrete = str_replace(",", ".", $valorFrete );
		$vol = $venda['volume'];

		if($vol['pesoL']){
			$pesoLiquido = str_replace(".", "", $vol['pesoL']);
			$pesoLiquido = str_replace(",", ".", $pesoLiquido);
		}else{
			$pesoLiquido = 0;
		}

		if($vol['pesoB']){
			$pesoBruto = str_replace(".", "", $vol['pesoB']);
			$pesoBruto = str_replace(",", ".", $pesoBruto);
		}else{
			$pesoBruto = 0;
		}

		if($vol['qtdVol']){
			$qtdVol = str_replace(".", "", $vol['qtdVol']);
			$qtdVol = str_replace(",", ".", $qtdVol);
		}else{
			$qtdVol = 0;
		}

		$frete = null;
		if($venda['frete'] != '9'){
			$frete = Frete::create([
				'placa' => $venda['placaVeiculo'],
				'valor' => $valorFrete,
				'tipo' => (int)$venda['frete'],
				'qtdVolumes' => $qtdVol,
				'uf' => $venda['ufPlaca'],
				'numeracaoVolumes' => $vol['numeracaoVol'] ?? '0',
				'especie' => $vol['especie'] ?? '*',
				'peso_liquido' => $pesoLiquido,
				'peso_bruto' => $pesoBruto
			]);
		}


		$totalVenda = str_replace(",", ".", $venda['total']);

		$desconto = 0;
		if($venda['desconto']){
			$desconto = str_replace(".", "", $venda['desconto']);
			$desconto = str_replace(",", ".", $desconto);
		}

		$result = Venda::create([
			'cliente_id' => $venda['cliente'],
			'transportadora_id' => $venda['transportadora'],
			'forma_pagamento' => $venda['formaPagamento'],
			'tipo_pagamento' => $venda['tipoPagamento'],
			'usuario_id' => get_id_user(),
			'valor_total' => $totalVenda,
			'desconto' => $desconto,
			'frete_id' => $frete != null ? $frete->id : null,
			'NfNumero' => 0,
			'natureza_id' => $venda['naturezaOp'],
			'path_xml' => '',
			'chave' => '',
			'sequencia_cce' => 0,
			'observacao' => $this->sanitizeString($venda['observacao']) ?? '',
			'estado' => 'DISPONIVEL',
		]);

		if($venda['formaPagamento'] == 'conta_crediario'){ 
			$credito = CreditoVenda::create([
				'venda_id' => $result->id,
				'cliente_id' => $venda['cliente'],
				'status' => false,	
			]);
		}

		$itens = $venda['itens'];
		$stockMove = new StockMove();
		foreach ($itens as $i) {
			ItemVenda::create([
				'venda_id' => $result->id,
				'produto_id' => (int) $i['codigo'],
				'quantidade' => (float) str_replace(",", ".", $i['quantidade']),
				'valor' => (float) str_replace(",", ".", $i['valor'])
			]);

			$prod = Produto
			::where('id', $i['codigo'])
			->first();

			if(!empty($prod->receita)){
				//baixa por receita
				$receita = $prod->receita; 
				foreach($receita->itens as $rec){

					if(!empty($rec->produto->receita)){ // se item da receita for receita
						$receita2 = $rec->produto->receita; 

						foreach($receita2->itens as $rec2){
							$stockMove->downStock(
								$rec2->produto_id, 
								(float) str_replace(",", ".", $i['quantidade']) * 
								($rec2->quantidade/$receita2->rendimento)
							);
						}
					}else{

						$stockMove->downStock(
							$rec->produto_id, 
							(float) str_replace(",", ".", $i['quantidade']) * 
							($rec->quantidade/$receita->rendimento)
						);
					}
				}
			}else{
				$stockMove->downStock(
					(int) $i['codigo'], (float) str_replace(",", ".", $i['quantidade']));
			}
		}

		if(isset($venda['receberContas'])){
			$receberContas = $venda['receberContas'];
			foreach($receberContas as $r){ 
				$c = CreditoVenda::where('id', $r)
				->first();
				$c->status = true;
				$c->save();
			}
		}

		if($venda['formaPagamento'] != 'a_vista' && $venda['formaPagamento'] != 'conta_crediario'){
			$fatura = $venda['fatura'];

			foreach ($fatura as $f) {
				$valorParcela = str_replace(",", ".", $f['valor']);

				$resultFatura = ContaReceber::create([
					'venda_id' => $result->id,
					'data_vencimento' => $this->parseDate($f['data']),
					'data_recebimento' => $this->parseDate($f['data']),
					'valor_integral' => $valorParcela,
					'valor_recebido' => 0,
					'status' => false,
					'referencia' => "Parcela, ".$f['numero'].", da Venda " . $result->id,
					'categoria_id' => 2,
				]);
			}
		}
		echo json_encode($result);
	}

	public function atualizar(Request $request){
		$request = $request->venda;
		$venda_id = $request['venda_id'];
		$venda = $vendaAnterior = Venda::find($venda_id);

		$valorFrete = str_replace(".", "", $request['valorFrete'] ?? 0);
		$valorFrete = str_replace(",", ".", $valorFrete );

		$vol = $request['volume'];

		if($vol['pesoL']){
			$pesoLiquido = str_replace(".", "", $vol['pesoL']);
			$pesoLiquido = str_replace(",", ".", $pesoLiquido);
		}else{
			$pesoLiquido = 0;
		}

		if($vol['pesoB']){
			$pesoBruto = str_replace(".", "", $vol['pesoB']);
			$pesoBruto = str_replace(",", ".", $pesoBruto);
		}else{
			$pesoBruto = 0;
		}

		if($vol['qtdVol']){
			$qtdVol = str_replace(".", "", $vol['qtdVol']);
			$qtdVol = str_replace(",", ".", $qtdVol);
		}else{
			$qtdVol = 0;
		}

		$frete = null;
		if($request['frete'] != '9'){
			$frete = Frete::create([
				'placa' => $request['placaVeiculo'],
				'valor' => $valorFrete,
				'tipo' => (int)$request['frete'],
				'qtdVolumes' => $qtdVol,
				'uf' => $request['ufPlaca'],
				'numeracaoVolumes' => $vol['numeracaoVol'] ?? '0',
				'especie' => $vol['especie'] ?? '*',
				'peso_liquido' => $pesoLiquido,
				'peso_bruto' => $pesoBruto
			]);
		}

		$totalVenda = str_replace(",", ".", $request['total']);

		$desconto = 0;
		if($request['desconto']){
			$desconto = str_replace(".", "", $request['desconto']);
			$desconto = str_replace(",", ".", $desconto);
		}

		$venda->transportadora_id = $request['transportadora'];
		$venda->forma_pagamento = $request['formaPagamento'];
		$venda->tipo_pagamento = $request['tipoPagamento'];
		$venda->usuario_id = get_id_user();
		$venda->valor_total = $totalVenda;
		$venda->desconto = $desconto;
		$venda->frete_id = $frete != null ? $frete->id : null;
		$venda->NfNumero = 0;
		$venda->natureza_id = $request['naturezaOp'];
		$venda->observacao = $this->sanitizeString($request['observacao']) ?? '';

		$venda->save();
		$itens = $request['itens'];
		$this->reverteEstoque($venda->itens);
		$this->deleteItens($venda);
		$stockMove = new StockMove();
		foreach ($itens as $i) {
			ItemVenda::create([
				'venda_id' => $venda->id,
				'produto_id' => (int) $i['codigo'],
				'quantidade' => (float) str_replace(",", ".", $i['quantidade']),
				'valor' => (float) str_replace(",", ".", $i['valor'])
			]);

			$prod = Produto
			::where('id', $i['codigo'])
			->first();

			if(!empty($prod->receita)){
				//baixa por receita
				$receita = $prod->receita; 
				foreach($receita->itens as $rec){

					if(!empty($rec->produto->receita)){ // se item da receita for receita
						$receita2 = $rec->produto->receita; 

						foreach($receita2->itens as $rec2){
							$stockMove->downStock(
								$rec2->produto_id, 
								(float) str_replace(",", ".", $i['quantidade']) * 
								($rec2->quantidade/$receita2->rendimento)
							);
						}
					}else{

						$stockMove->downStock(
							$rec->produto_id, 
							(float) str_replace(",", ".", $i['quantidade']) * 
							($rec->quantidade/$receita->rendimento)
						);
					}
				}
			}else{
				$stockMove->downStock(
					(int) $i['codigo'], (float) str_replace(",", ".", $i['quantidade']));
			}
		}

		$this->deleteFatura($venda);
		$resultFatura = null;
		if($request['formaPagamento'] != 'a_vista' && $request['formaPagamento'] != 'conta_crediario'){
			$fatura = $request['fatura'];

			foreach ($fatura as $f) {
				$valorParcela = str_replace(",", ".", $f['valor']);

				$resultFatura = ContaReceber::create([
					'venda_id' => $venda->id,
					'data_vencimento' => $this->parseDate($f['data']),
					'data_recebimento' => $this->parseDate($f['data']),
					'valor_integral' => $valorParcela,
					'valor_recebido' => 0,
					'status' => false,
					'referencia' => "Parcela, ".$f['numero'].", da Venda " . $venda->id,
					'categoria_id' => 2,
				]);
			}
		}

		echo json_encode($resultFatura);
		
	}

	private function reverteEstoque($itens){
		$stockMove = new StockMove();
		foreach($itens as $i){
			if(!empty($i->produto->receita)){
				//baixa por receita
				$receita = $i->produto->receita; 
				foreach($receita->itens as $rec){

					if(!empty($rec->produto->receita)){ // se item da receita for receita
						$receita2 = $rec->produto->receita; 
						foreach($receita2->itens as $rec2){
							$stockMove->pluStock(
								$rec2->produto_id, 
								(float) str_replace(",", ".", $i->quantidade) * 
								($rec2->quantidade/$receita2->rendimento)
							);
						}
					}else{

						$stockMove->pluStock(
							$rec->produto_id, 
							(float) str_replace(",", ".", $i->quantidade) * 
							($rec->quantidade/$receita->rendimento)
						);
					}
				}
			}else{
				$stockMove->pluStock(
					$i->produto_id, (float) str_replace(",", ".", $i->quantidade));
			}
		}
	}

	private function deleteItens($venda){
		ItemVenda::where('venda_id', $venda->id)->delete();
	}

	private function deleteFatura($venda){
		ContaReceber::where('venda_id', $venda->id)->delete();
	}

	public function salvarCrediario(Request $request){
		$venda = $request->venda;
		$valorFrete = 0;

		$totalVenda = str_replace(",", ".", $venda['valor_total']);

		$desconto = 0;

		$result = Venda::create([
			'cliente_id' => $venda['cliente'],
			'transportadora_id' => null,
			'forma_pagamento' => 'conta_crediario',
			'tipo_pagamento' => '05',
			'usuario_id' => get_id_user(),
			'valor_total' => $totalVenda,
			'desconto' => $desconto,
			'frete_id' => null,
			'NfNumero' => 0,
			'natureza_id' => 1,  
			'path_xml' => '',
			'chave' => '',
			'sequencia_cce' => 0,
			'observacao' => '',
			'estado' => 'DISPONIVEL'	
		]);


		$credito = CreditoVenda::create([
			'venda_id' => $result->id,
			'cliente_id' => $venda['cliente'],
			'status' => false,	
		]);

		if($venda['codigo_comanda'] > 0){
			$pedido = Pedido::
			where('comanda', $venda['codigo_comanda'])
			->where('status', 0)
			->where('desativado', 0)
			->first();

			$pedido->status = 1;
			$pedido->desativado = 1;
			$pedido->save();
		}


		$itens = $venda['itens'];
		$stockMove = new StockMove();
		foreach ($itens as $i) {
			ItemVenda::create([
				'venda_id' => $result->id,
				'produto_id' => (int) $i['id'],
				'quantidade' => (float) str_replace(",", ".", $i['quantidade']),
				'valor' => (float) str_replace(",", ".", $i['valor'])
			]);
			$stockMove->downStock(
				(int) $i['id'], (float) str_replace(",", ".", $i['quantidade']));
		}

		echo json_encode($result);
	}

	private function menos30Dias(){
		return date('d/m/Y', strtotime("-30 days",strtotime(str_replace("/", "-", 
			date('Y-m-d')))));
	}

	private function parseDate($date, $plusDay = false){
		if($plusDay == false)
			return date('Y-m-d', strtotime(str_replace("/", "-", $date)));
		else
			return date('Y-m-d', strtotime("+1 day",strtotime(str_replace("/", "-", $date))));
	}

	public function filtro(Request $request){

		$dataInicial = $request->data_inicial;
		$dataFinal = $request->data_final;
		$cliente = $request->cliente;
		$estado = $request->estado;
		$vendas = null;

		if(isset($cliente) && isset($dataInicial) && isset($dataFinal)){
			$vendas = Venda::filtroDataCliente(
				$cliente, 
				$this->parseDate($dataInicial),
				$this->parseDate($dataFinal, true),
				$estado
			);
		}else if(isset($dataInicial) && isset($dataFinal)){
			$vendas = Venda::filtroData(
				$this->parseDate($dataInicial),
				$this->parseDate($dataFinal, true),
				$estado
			);
		}else if(isset($cliente)){
			$vendas = Venda::filtroCliente(
				$cliente,
				$estado
			);

		}else{
			$vendas = Venda::filtroEstado(
				$estado
			);
		}

		$certificado = Certificado::first();

		return view("vendas/list")
		->with('vendas', $vendas)
		->with('nf', true)
		->with('cliente', $cliente)
		->with('certificado', $certificado)
		->with('dataInicial', $dataInicial)
		->with('dataFinal', $dataFinal)
		->with('estado', $estado)

		->with('title', "Filtro de Vendas");
	}

	public function teste(){
		ItemVenda::create([
			'venda_id' => 2,
			'produto_id' => 1,
			'quantidade' => 1,
			'valor' => 2
		]);
	}

	public function rederizarDanfe($id){
		$venda = Venda::find($id);
		$config = ConfigNota::first();

		$cnpj = str_replace(".", "", $config->cnpj);
		$cnpj = str_replace("/", "", $cnpj);
		$cnpj = str_replace("-", "", $cnpj);
		$cnpj = str_replace(" ", "", $cnpj);

		$nfe_service = new NFService([
			"atualizacao" => date('Y-m-d h:i:s'),
			"tpAmb" => (int)$config->ambiente,
			"razaosocial" => $config->razao_social,
			"siglaUF" => $config->UF,
			"cnpj" => $cnpj,
			"schemes" => "PL_009_V4",
			"versao" => "4.00",
			"tokenIBPT" => "AAAAAAA",
			"CSC" => $config->csc,
			"CSCid" => $config->csc_id
		]);
		$nfe = $nfe_service->gerarNFe($id);
		if(!isset($nfe['erros_xml'])){
			$xml = $nfe['xml'];

			$public = getenv('SERVIDOR_WEB') ? 'public/' : '';
			$logo = 'data://text/plain;base64,'. base64_encode(file_get_contents($public.'imgs/logo.jpg'));

			try {
				$danfe = new Danfe($xml);
				$id = $danfe->monta();
				$pdf = $danfe->render();
				header('Content-Type: application/pdf');
				return response($pdf)
				->header('Content-Type', 'application/pdf');
			} catch (InvalidArgumentException $e) {
				echo "Ocorreu um erro durante o processamento :" . $e->getMessage();
			} 
		} else{
			foreach($nfe['erros_xml'] as $e) {
				echo $e;
			}
		}

	}

	public function imprimirPedido($id){
		$venda = Venda::find($id);

		$p = view('vendas/print')
		->with('venda', $venda);
		// return $p;

		$domPdf = new Dompdf(["enable_remote" => true]);
		$domPdf->loadHtml($p);

		$pdf = ob_get_clean();

		$domPdf->setPaper("A4");
		$domPdf->render();
		$domPdf->stream("relatorio de compras.pdf");

	}

	public function baixarXml($id){
		$venda = Venda::find($id);

		$public = getenv('SERVIDOR_WEB') ? 'public/' : '';

		return response()->download($public.'xml_nfe/'.$venda->chave.'.xml');

	}

	public function edit($id){
		$venda = Venda::find($id);


		$config = ConfigNota::first();
		if($config == null){
			return redirect('configNF');
		}
		$lastNF = Venda::lastNF();

		$naturezas = NaturezaOperacao::all();

		$config = ConfigNota::first();
		$categorias = Categoria::all();
		$produtos = Produto::all();
		$tributacao = Tributacao::first();
		$clientes = Cliente::all();
		$tiposPagamento = Venda::tiposPagamento();

		foreach($venda->itens as $i){
			$i->produto;
		}
		$venda->duplicatas;
		$venda->natureza;
		$venda->cliente;
		$venda->frete;

		return view("vendas/edit")
		->with('naturezas', $naturezas)
		->with('vendaJs', true)
		->with('config', $config)
		->with('venda', $venda)
		->with('tiposPagamento', $tiposPagamento)
		->with('lastNF', $lastNF)
		->with('listaPreco', ListaPreco::all())
		->with('title', "Editar Venda");
		
	}

	public function clone($id){
		$lastNF = Venda::lastNF();
		$venda = Venda::find($id);
		$config = ConfigNota::first();

		return view("vendas/clone")
		->with('vendaJs', true)
		->with('config', $config)
		->with('venda', $venda)
		->with('lastNF', $lastNF)
		->with('title', "Clonar Venda");
	}

	public function salvarClone(Request $request){
		$cliente = $request->cliente;
		$vendaId = $request->venda_id;

		$clienteId = (int)explode("-", $cliente)[0];
		$venda = Venda::find($vendaId);

		$freteId = null;
		if($venda->frete_id != NULL){
			$frete = Frete::create([
				'placa' => $venda->frete->placa,
				'valor' => $venda->frete->valor,
				'tipo' => $venda->frete->tipo,
				'qtdVolumes' => $venda->frete->qtdVolumes,
				'uf' => $venda->frete->uf,
				'numeracaoVolumes' => $venda->frete->numeracaoVolumes,
				'especie' => $venda->frete->especie,
				'peso_liquido' => $venda->frete->peso_liquido,
				'peso_bruto' => $venda->frete->peso_bruto
			]);
			$freteId = $frete->id;
		}

		$novaVenda = [ 
			'cliente_id' => $clienteId,
			'usuario_id' => get_id_user(),
			'frete_id' => $freteId,
			'valor_total' => $venda->valor_total,
			'forma_pagamento' => $venda->forma_pagamento,
			'NfNumero' => 0,
			'natureza_id' => $venda->natureza_id,
			'chave' => '',
			'path_xml' => '',
			'estado' => 'DISPONIVEL',
			'observacao' => $venda->observacao,
			'desconto' => $venda->desconto,
			'transportadora_id' => $venda->transportadora_id,
			'sequencia_cce' => 0,
			'tipo_pagamento' => $venda->tipo_pagamento
		];

		$result = Venda::create($novaVenda);

		$itens = $venda->itens;
		$stockMove = new StockMove();
		foreach ($itens as $i) {
			ItemVenda::create([
				'venda_id' => $result->id,
				'produto_id' => $i->produto_id,
				'quantidade' => $i->quantidade,
				'valor' => $i->valor
			]);

			$prod = Produto
			::where('id', $i->produto_id)
			->first();

			if(!empty($prod->receita)){
				//baixa por receita
				$receita = $prod->receita; 
				foreach($receita->itens as $rec){

					if(!empty($rec->produto->receita)){ // se item da receita for receita
						$receita2 = $rec->produto->receita; 

						foreach($receita2->itens as $rec2){
							$stockMove->downStock(
								$rec2->produto_id, 
								$i->quantidade * 
								($rec2->quantidade/$receita2->rendimento)
							);
						}
					}else{

						$stockMove->downStock(
							$rec->produto_id, 
							$i->quantidade* 
							($rec->quantidade/$receita->rendimento)
						);
					}
				}
			}else{
				$stockMove->downStock(
					$i->produto_id, $i->quantidade);
			}
		}

		if($venda->forma_pagamento != 'a_vista' && $venda->forma_pagamento != 'conta_crediario'){
			$fatura = $venda->duplicatas;

			foreach ($fatura as $key => $f) {
				$valorParcela = str_replace(",", ".", $f['valor']);

				$resultFatura = ContaReceber::create([
					'venda_id' => $result->id,
					'data_vencimento' => $f->data_vencimento,
					'data_recebimento' => $f->data_recebimento,
					'valor_integral' => $f->valor_integral,
					'valor_recebido' => 0,
					'status' => false,
					'referencia' => "Parcela, ".($key+1).", da Venda " . $result->id,
					'categoria_id' => 2,
				]);
			}
		}

		session()->flash('color', 'green');
		session()->flash("message", "Venda duplicada com sucesso!");

		return redirect('/vendas/lista');

	}

	public function gerarXml($id){
		$venda = Venda::find($id);
		$config = ConfigNota::first();

		$cnpj = str_replace(".", "", $config->cnpj);
		$cnpj = str_replace("/", "", $cnpj);
		$cnpj = str_replace("-", "", $cnpj);
		$cnpj = str_replace(" ", "", $cnpj);

		$nfe_service = new NFService([
			"atualizacao" => date('Y-m-d h:i:s'),
			"tpAmb" => (int)$config->ambiente,
			"razaosocial" => $config->razao_social,
			"siglaUF" => $config->UF,
			"cnpj" => $cnpj,
			"schemes" => "PL_009_V4",
			"versao" => "4.00",
			"tokenIBPT" => "AAAAAAA",
			"CSC" => $config->csc,
			"CSCid" => $config->csc_id
		]);
		$nfe = $nfe_service->gerarNFe($id);
		if(!isset($nfe['erros_xml'])){
			$xml = $nfe['xml'];

			return response($xml)
			->header('Content-Type', 'application/xml');
			
		} else{
			foreach($nfe['erros_xml'] as $e) {
				echo $e;
			}
		}
	}

}
