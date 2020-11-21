<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Venda;
use App\ItemVenda;
use App\VendaCaixa;
use App\ItemVendaCaixa;
use App\Compra;
use App\Estoque;
use App\Produto;
use Dompdf\Dompdf;

class RelatorioController extends Controller
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
		return view('relatorios/index')
		->with('relatorioJS', true)
		->with('title', 'Relatórios');
	}

	public function filtroVendas(Request $request){
		$data_inicial = $request->data_inicial;
		$data_final = $request->data_final;
		$total_resultados = $request->total_resultados;
		$ordem = $request->ordem;

		if($data_final && $data_final){
			$data_inicial = $this->parseDate($data_inicial);
			$data_final = $this->parseDate($data_final, true);
		}

		$vendas = Venda
		::select(\DB::raw('DATE_FORMAT(vendas.data_registro, "%d-%m-%Y") as data, sum(vendas.valor_total) as total, sum(item_vendas.quantidade) as itens'))
		->join('item_vendas', 'item_vendas.venda_id', '=', 'vendas.id')
		->orWhere(function($q) use ($data_inicial, $data_final){
			if($data_final && $data_final){
				return $q->whereBetween('vendas.data_registro', [$data_inicial, 
					$data_final]);
			}
		})
		->groupBy('data')
		->orderBy($ordem == 'data' ? 'data' : 'total', $ordem == 'data' ? 'desc' : $ordem)


		->limit($total_resultados ?? 1000000)
		->get();

		$vendasCaixa = VendaCaixa
		::select(\DB::raw('DATE_FORMAT(venda_caixas.data_registro, "%d-%m-%Y") as data, sum(venda_caixas.valor_total) as total, sum(item_venda_caixas.quantidade) as itens'))
		->join('item_venda_caixas', 'item_venda_caixas.venda_caixa_id', '=', 'venda_caixas.id')

		->orWhere(function($q) use ($data_inicial, $data_final){
			if($data_final && $data_final){
				return $q->whereBetween('venda_caixas.data_registro', [$data_inicial, 
					$data_final]);
			}
		})
		->groupBy('data')
		->orderBy($ordem == 'data' ? 'data' : 'total', $ordem == 'data' ? 'desc' : $ordem)
		->limit($total_resultados ?? 1000000)
		->get();

		$arr = $this->uneArrayVendas($vendas, $vendasCaixa);
		if($total_resultados){
			$arr = array_slice($arr, 0, $total_resultados);
		}
		usort($arr, function($a, $b) use ($ordem){
			if($ordem == 'asc') return $a['total'] > $b['total'];
			else if($ordem == 'desc') return $a['total'] < $b['total'];
			else return $a['data'] < $b['data'];
		});

		if(sizeof($arr) == 0){
			session()->flash('color', 'red');
			session()->flash("message", "Relatório sem registro!");
			return redirect('/relatorios');
		}

		$p = view('relatorios/relatorio_venda')
		->with('ordem', $ordem == 'asc' ? 'Menos' : 'Mais')

		->with('data_inicial', $request->data_inicial)
		->with('data_final', $request->data_final)
		->with('vendas', $arr);

		// return $p;

		$domPdf = new Dompdf(["enable_remote" => true]);
		$domPdf->loadHtml($p);

		$pdf = ob_get_clean();

		$domPdf->setPaper("A4");
		$domPdf->render();
		$domPdf->stream("relatorio_venda.pdf");
	}

	public function filtroCompras(Request $request){
		$data_inicial = $request->data_inicial;
		$data_final = $request->data_final;
		$total_resultados = $request->total_resultados;
		$ordem = $request->ordem;

		if($data_final && $data_final){
			$data_inicial = $this->parseDate($data_inicial);
			$data_final = $this->parseDate($data_final, true);
		}

		$compras = Compra
		::select(\DB::raw('DATE_FORMAT(compras.created_at, "%d-%m-%Y") as data, sum(compras.valor) as total,
			count(id) as compras_diarias'))
		// ->join('item_compras', 'item_compras.compra_id', '=', 'item_compras.id')
		->orWhere(function($q) use ($data_inicial, $data_final){
			if($data_final && $data_final){
				return $q->whereBetween('compras.date_register', [$data_inicial, 
					$data_final]);
			}
		})
		->groupBy('data')
		->orderBy('total', $ordem)

		->limit($total_resultados ?? 1000000)
		->get();

		if(sizeof($compras) == 0){
			session()->flash('color', 'red');
			session()->flash("message", "Relatório sem registro!");
			return redirect('/relatorios');
		}

		$p = view('relatorios/relatorio_compra')
		->with('data_inicial', $request->data_inicial)
		->with('data_final', $request->data_final)
		->with('compras', $compras);

		// return $p;

		$domPdf = new Dompdf(["enable_remote" => true]);
		$domPdf->loadHtml($p);

		$pdf = ob_get_clean();

		$domPdf->setPaper("A4");
		$domPdf->render();
		$domPdf->stream("relatorio de compras.pdf");
	}

	public function filtroVendaProdutos(Request $request){
		$data_inicial = $request->data_inicial;
		$data_final = $request->data_final;
		$total_resultados = $request->total_resultados;
		$ordem = $request->ordem;

		if($data_final && $data_final){
			$data_inicial = $this->parseDate($data_inicial);
			$data_final = $this->parseDate($data_final, true);
		}

		$itensVenda = ItemVenda
		::select(\DB::raw('produtos.id as id, produtos.nome as nome, produtos.valor_venda as valor_venda, sum(item_vendas.quantidade) as total, sum(item_vendas.quantidade * item_vendas.valor) as total_dinheiro'))
		->join('produtos', 'produtos.id', '=', 'item_vendas.produto_id')
		->orWhere(function($q) use ($data_inicial, $data_final){
			if($data_final && $data_final){
				return $q->whereBetween('item_vendas.created_at', [$data_inicial, 
					$data_final]);
			}
		})
		->groupBy('produtos.id')
		->orderBy('total', $ordem)

		// ->limit($total_resultados ?? 1000000)
		->get();



		$itensVendaCaixa = ItemVendaCaixa
		::select(\DB::raw('produtos.id as id, produtos.nome as nome, produtos.valor_venda as valor_venda, sum(item_venda_caixas.quantidade) as total, sum(item_venda_caixas.quantidade * item_venda_caixas.valor) as total_dinheiro'))
		->join('produtos', 'produtos.id', '=', 'item_venda_caixas.produto_id')
		->orWhere(function($q) use ($data_inicial, $data_final){
			if($data_final && $data_final){
				return $q->whereBetween('item_venda_caixas.created_at', [$data_inicial, 
					$data_final]);
			}
		})
		->groupBy('produtos.id')
		->orderBy('total', $ordem)

		// ->limit($total_resultados ?? 1000000)
		->get();

		$arr = $this->uneArrayProdutos($itensVenda, $itensVendaCaixa);

		if(sizeof($arr) == 0){
			session()->flash('color', 'red');
			session()->flash("message", "Relatório sem registro!");
			return redirect('/relatorios');
		}

		if($total_resultados){
			$arr = array_slice($arr, 0, $total_resultados);
		}

		usort($arr, function($a, $b) use ($ordem){
			if($ordem == 'asc') return $a['total'] > $b['total'];
			else return $a['total'] < $b['total'];
		});
		$p = view('relatorios/relatorio_venda_produtos')
		->with('ordem', $ordem == 'asc' ? 'Menos' : 'Mais')
		->with('data_inicial', $request->data_inicial)
		->with('data_final', $request->data_final)
		->with('itens', $arr);

		// return $p;	

		$domPdf = new Dompdf(["enable_remote" => true]);
		$domPdf->loadHtml($p);

		$pdf = ob_get_clean();

		$domPdf->setPaper("A4");
		$domPdf->render();
		$domPdf->stream("relatorio de produtos.pdf");
	}


	public function filtroVendaClientes(Request $request){
		$data_inicial = $request->data_inicial;
		$data_final = $request->data_final;
		$total_resultados = $request->total_resultados;
		$ordem = $request->ordem;

		if($data_final && $data_final){
			$data_inicial = $this->parseDate($data_inicial);
			$data_final = $this->parseDate($data_final, true);
		}

		$vendas = Venda
		::select(\DB::raw('clientes.id as id, clientes.razao_social as nome, count(*) as total, sum(valor_total) as total_dinheiro'))
		->join('clientes', 'clientes.id', '=', 'vendas.cliente_id')
		->orWhere(function($q) use ($data_inicial, $data_final){
			if($data_final && $data_final){
				return $q->whereBetween('vendas.data_registro', [$data_inicial, 
					$data_final]);
			}
		})
		->groupBy('clientes.id')
		->orderBy('total', $ordem)

		->limit($total_resultados ?? 1000000)
		->get();

		if(sizeof($vendas) == 0){
			session()->flash('color', 'red');
			session()->flash("message", "Relatório sem registro!");
			return redirect('/relatorios');
		}


		$p = view('relatorios/relatorio_clientes')
		->with('ordem', $ordem == 'asc' ? 'Menos' : 'Mais')
		->with('data_inicial', $request->data_inicial)
		->with('data_final', $request->data_final)
		->with('vendas', $vendas);

		// return $p;



		$domPdf = new Dompdf(["enable_remote" => true]);
		$domPdf->loadHtml($p);

		$pdf = ob_get_clean();

		$domPdf->setPaper("A4");
		$domPdf->render();
		$domPdf->stream("relatorio de compras.pdf");
	}

	public function filtroEstoqueMinimo(Request $request){
		$data_inicial = $request->data_inicial;
		$data_final = $request->data_final;
		$total_resultados = $request->total_resultados;
		$ordem = $request->ordem;
		
		if($data_final && $data_final){
			$data_inicial = $this->parseDate($data_inicial);
			$data_final = $this->parseDate($data_final, true);
		}

		$produtos = Produto::all();
		$arrDesfalque = [];
		foreach($produtos as $p){
			if($p->estoque_minimo > 0){
				$estoque = Estoque::where('produto_id', $p->id)->first();
				$temp = null;
				if($estoque == null){
					$temp = [
						'id' => $p->id,
						'nome' => $p->nome,
						'estoque_minimo' => $p->estoque_minimo,
						'estoque_atual' => 0,
						'total_comprar' => $p->estoque_minimo,
						'valor_compra' => 0
					];
				}else{
					$temp = [
						'id' => $p->id,
						'nome' => $p->nome,
						'estoque_minimo' => $p->estoque_minimo,
						'estoque_atual' => $estoque->quantidade,
						'total_comprar' => $p->estoque_minimo - $estoque->quantidade,
						'valor_compra' => $estoque->valor_compra
					];
				}

				array_push($arrDesfalque, $temp);

			}
		}

		if($total_resultados){
			$arrDesfalque = array_slice($arrDesfalque, 0, $total_resultados);
		}

		// print_r($arrDesfalque);

		$p = view('relatorios/relatorio_estoque_minimo')
		->with('ordem', $ordem == 'asc' ? 'Menos' : 'Mais')
		->with('data_inicial', $request->data_inicial)
		->with('data_final', $request->data_final)
		->with('itens', $arrDesfalque);

		// return $p;

		$domPdf = new Dompdf(["enable_remote" => true]);
		$domPdf->loadHtml($p);

		$pdf = ob_get_clean();

		$domPdf->setPaper("A4");
		$domPdf->render();
		$domPdf->stream("relatorio de estoque minimo.pdf");
	}

	public function filtroVendaDiaria(Request $request){
		$data = $request->data_inicial;
		$total_resultados = $request->total_resultados;
		$ordem = $request->ordem;

		$data_inicial = null;
		$data_final = null;
		if(strlen($data) == 0){
			session()->flash('color', 'red');
			session()->flash("message", "Informe o dia para gerar o relatório!");
			return redirect('/relatorios');
		}else{
			$data_inicial = $this->parseDateDay($data);
			$data_final = $this->parseDateDay($data, true);
		}

		$vendas = Venda
		::select(\DB::raw('vendas.id, DATE_FORMAT(vendas.data_registro, "%d-%m-%Y %H:%i") as data, valor_total'))
		->join('item_vendas', 'item_vendas.venda_id', '=', 'vendas.id')
		->orWhere(function($q) use ($data_inicial, $data_final){
			if($data_final && $data_final){
				return $q->whereBetween('vendas.created_at', [$data_inicial, 
					$data_final]);
			}
		})
		->groupBy('vendas.id')

		->limit($total_resultados ?? 1000000)
		->get();

		$vendasCaixa = VendaCaixa
		::select(\DB::raw('venda_caixas.id, DATE_FORMAT(venda_caixas.data_registro, "%d-%m-%Y %H:%i") as data, valor_total'))
		->join('item_venda_caixas', 'item_venda_caixas.venda_caixa_id', '=', 'venda_caixas.id')

		->orWhere(function($q) use ($data_inicial, $data_final){
			if($data_final && $data_final){
				return $q->whereBetween('venda_caixas.created_at', [$data_inicial, 
					$data_final]);
			}
		})
		->groupBy('venda_caixas.id')
		->limit($total_resultados ?? 1000000)
		->get();


		$arr = $this->uneArrayVendasDay($vendas, $vendasCaixa);
		if($total_resultados){
			$arr = array_slice($arr, 0, $total_resultados);
		}

		// usort($arr, function($a, $b) use ($ordem){
		// 	if($ordem == 'asc') return $a['total'] > $b['total'];
		// 	else if($ordem == 'desc') return $a['total'] < $b['total'];
		// 	else return $a['data'] < $b['data'];
		// });

		if(sizeof($arr) == 0){
			session()->flash('color', 'red');
			session()->flash("message", "Relatório sem registro!");
			return redirect('/relatorios');
		}

		$p = view('relatorios/relatorio_diario')
		->with('ordem', $ordem == 'asc' ? 'Menos' : 'Mais')

		->with('data_inicial', $request->data_inicial)
		->with('data_final', $request->data_final)
		->with('vendas', $arr);

		// return $p;

		$domPdf = new Dompdf(["enable_remote" => true]);
		$domPdf->loadHtml($p);

		$pdf = ob_get_clean();

		$domPdf->setPaper("A4");
		$domPdf->render();
		$domPdf->stream("relatorio de vendas.pdf");
	}

	private function uneArrayVendas($vendas, $vendasCaixa){
		$adicionados = [];
		$arr = [];

		foreach($vendas as $v){
			$temp = [
				'data' => $v->data,
				'total' => $v->valor_total,
				'itens' => $v->itens
			];
			array_push($adicionados, $v->id);
			array_push($arr, $temp);
			
		}

		foreach($vendasCaixa as $v){
			if(!in_array($v->data, $adicionados)){
				$temp = [
					'data' => $v->data,
					'total' => $v->valor_total,
					'itens' => $v->itens
				];
				array_push($adicionados, $v->data);
				array_push($arr, $temp);
			}else{
				for($aux = 0; $aux < count($arr); $aux++){
					if($arr[$aux]['data'] == $v->data){
						$arr[$aux]['total'] += $i->total;
						$arr[$aux]['itens'] += $i->itens;
					}
				}
			}
		}
		return $arr;
	}

	private function uneArrayVendasDay($vendas, $vendasCaixa){
		$adicionados = [];
		$arr = [];

		foreach($vendas as $v){

			$temp = [
				'id' => $v->id,
				'data' => $v->data,
				'total' => $v->valor_total,
				'itens' => $v->itens
			];
			array_push($adicionados, $v->id);
			array_push($arr, $temp);
			
		}

		foreach($vendasCaixa as $v){

			$temp = [
				'id' => $v->id,
				'data' => $v->data,
				'total' => $v->valor_total,
				'itens' => $v->itens
			];

			array_push($adicionados, $v->data);
			array_push($arr, $temp);
			
		}
		return $arr;
	}

	private function uneArrayProdutos($itemVenda, $itemVendasCaixa){
		$adicionados = [];
		$arr = [];

		foreach($itemVenda as $i){

			$temp = [
				'id' => $i->id,
				'nome' => $i->nome,
				'valor_venda' => $i->valor_venda,
				'total' => $i->total,
				'total_dinheiro' => $i->total_dinheiro,
			];
			array_push($adicionados, $i->id);
			array_push($arr, $temp);
			
		}

		foreach($itemVendasCaixa as $i){
			if(!in_array($i->id, $adicionados)){
				$temp = [
					'id' => $i->id,
					'nome' => $i->nome,
					'valor_venda' => $i->valor_venda,
					'total' => $i->total,
					'total_dinheiro' => $i->total_dinheiro,
				];
				array_push($adicionados, $i->id);
				array_push($arr, $temp);
			}else{
				for($aux = 0; $aux < count($arr); $aux++){
					if($arr[$aux]['id'] == $i->id){
						$arr[$aux]['total'] += $i->total;
						$arr[$aux]['total_dinheiro'] += $i->total;
					}
				}
			}
		}

		return $arr;
	}

	private static function parseDate($date, $plusDay = false){
		if($plusDay == false)
			return date('Y-m-d', strtotime(str_replace("/", "-", $date)));
		else
			return date('Y-m-d', strtotime("+1 day",strtotime(str_replace("/", "-", $date))));
	}

	private static function parseDateDay($date, $plusDay = false){
		if($plusDay == false)
			return date('Y-m-d', strtotime(str_replace("/", "-", $date))) . " 00:00";
		else
			return date('Y-m-d', strtotime(str_replace("/", "-", $date))) . " 23:59";

	}
}
