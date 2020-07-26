<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VendaCaixa;
use App\Helpers\StockMove;
use App\ConfigNota;
use App\NaturezaOperacao;
use App\Categoria;
use App\Produto;
use App\Tributacao;
use App\ProdutoPizza;

class FrontBoxController extends Controller
{
	public function __construct(){
        $this->middleware(function ($request, $next) {
            $value = session('user_logged');
            if(!$value){
                return redirect("/login");
            }else{
                if($value['acesso_caixa'] == 0){
                    return redirect("/sempermissao");
                }
            }
            return $next($request);
        });
    }

    public function index(){

        $config = ConfigNota::first();
        $naturezas = NaturezaOperacao::all();
        $categorias = Categoria::all();
        $produtos = Produto::all();
        $tributacao = Tributacao::first();
        $tiposPagamento = VendaCaixa::tiposPagamento();
        $config = ConfigNota::first();

        if(count($naturezas) == 0 || count($produtos) == 0 || $config == null || count($categorias) == 0 || $tributacao == null){

            return view("frontBox/alerta")
            ->with('produtos', count($produtos))
            ->with('categorias', count($categorias))
            ->with('naturezas', $naturezas)
            ->with('config', $config)
            ->with('tributacao', $tributacao)
            ->with('title', "Validação para Emitir");
        }else{
           return view('frontBox/main')
           ->with('frenteCaixa', true)
           ->with('tiposPagamento', $tiposPagamento)
           ->with('config', $config)
           ->with('disableFooter', true)
           ->with('title', 'Frente de Caixa');
       }
   }

   private function cancelarNFCe($venda){
    $config = ConfigNota::first();

    $cnpj = str_replace(".", "", $config->cnpj);
    $cnpj = str_replace("/", "", $cnpj);
    $cnpj = str_replace("-", "", $cnpj);
    $cnpj = str_replace(" ", "", $cnpj);
    $nfe_service = new NFeService([
        "atualizacao" => date('Y-m-d h:i:s'),
        "tpAmb" => 2,
        "razaosocial" => $config->razao_social,
        "siglaUF" => $config->UF,
        "cnpj" => $cnpj,
        "schemes" => "PL_009_V4",
        "versao" => "4.00",
        "tokenIBPT" => "AAAAAAA",
        "CSC" => "XTZOH6COASX5DYLKBUZXG5TABFG7ZFTQVSA2",
        "CSCid" => "000001"
    ], 65);

    $nfce = $nfe_service->cancelarNFCe($venda->id, "Troca de produtos requisitada pelo cliente");
    return is_array($nfce);
}

public function deleteVenda($id){
    $venda = VendaCaixa
    ::where('id', $id)
    ->first();


    $stockMove = new StockMove();

    foreach($venda->itens as $i){
        if($i->produto->receita){
            $receita = $i->produto->receita;
            foreach($receita->itens as $rec){

                if($i->itemPedido != NULL && $i->itemPedido->tamanho != NULL){
                    $totalSabores = count($i->itemPedido->sabores);
                    $produtoPizza = ProdutoPizza::
                    where('produto_id', $i->produto->delivery->id)
                    ->where('tamanho_id', $i->itemPedido->tamanho->id)
                    ->first();

                    $stockMove->pluStock(
                        $rec->produto_id, $i->quantidade 
                      * 
                        ((($rec->quantidade/$totalSabores)/$receita->pedacos)*$produtoPizza->tamanho->pedacos)/$receita->rendimento
                    );

                }else{
                    $stockMove->pluStock($rec->produto_id, 
                        $i->quantidade);
                }
            }
        }else{
            $stockMove->pluStock($i->produto_id, 
                        $i->quantidade); // -50 na altera valor compra
        }
    }

    if($venda->delete()){
        session()->flash('color', 'blue');
        session()->flash("message", "Venda removida com sucesso!");
    }else{
        session()->flash('color', 'red');
        session()->flash('message', 'Erro ao remover venda!');
    }
    return redirect('/frenteCaixa/devolucao');

}

public function list(){
        // $vendas = VendaCaixa::
        // orderBy('id', 'desc')
        // ->get();

    $vendas = VendaCaixa::filtroData(
        $this->parseDate(date("Y-m-d")),
        $this->parseDate(date("Y-m-d"), true)
    );
    return view('frontBox/list')
    ->with('vendas', $vendas)
    ->with('frenteCaixa', true)
    ->with('info', "Lista de vendas de Hoje: " . date("d/m/Y") )
    ->with('title', 'Lista de Vendas na Frente de Caixa');
}

public function devolucao(){
    $vendas = VendaCaixa::
    orderBy('id', 'desc')
    ->limit(20)
    ->get();
    return view('frontBox/devolucao')
    ->with('vendas', $vendas)
    ->with('frenteCaixa', true)
    ->with('nome', '')
    ->with('nfce', '')
    ->with('valor', '')
    ->with('info', "Lista das ultimas 20 vendas")

    ->with('title', 'Devolução');
}

public function filtro(Request $request){
    $dataInicial = $request->data_inicial;
    $dataFinal = $request->data_final;

    $vendas = VendaCaixa::filtroData(
        $this->parseDate($dataInicial),
        $this->parseDate($dataFinal, true)
    );

    return view('frontBox/list')
    ->with('vendas', $vendas)
    ->with('dataInicial', $dataInicial)
    ->with('info', "Lista de vendas período: $dataInicial até $dataFinal")
    ->with('dataFinal', $dataFinal)
    ->with('frenteCaixa', true)
    ->with('info', "Lista das ultimas 20 vendas")
    ->with('title', 'Filtro de Vendas na Frente de Caixa');
}


private function parseDate($date, $plusDay = false){
    if($plusDay == false)
        return date('Y-m-d', strtotime(str_replace("/", "-", $date)));
    else
        return date('Y-m-d', strtotime("+1 day",strtotime(str_replace("/", "-", $date))));
}



public function filtroCliente(Request $request){

    $vendas = VendaCaixa::filtroCliente($request->nome);
    return view('frontBox/devolucao')
    ->with('vendas', $vendas)
    ->with('frenteCaixa', true)
    ->with('valor', '')
    ->with('nome', $request->nome)
    ->with('nfce', '')
    ->with('info', "Filtro cliente: $request->nome")

    ->with('title', 'Filtro por cliente');
}


public function filtroNFCe(Request $request){

    $vendas = VendaCaixa::filtroNFCe($request->nfce);
    return view('frontBox/devolucao')
    ->with('vendas', $vendas)
    ->with('frenteCaixa', true)
    ->with('valor', '')
    ->with('nfce', $request->nfce)
    ->with('nome', '')
    ->with('info', "Filtro NFCE: $request->nfce")
    ->with('title', 'Filtro por NFCe');
}

public function filtroValor(Request $request){

    $vendas = VendaCaixa::filtroValor($request->valor);
    return view('frontBox/devolucao')
    ->with('vendas', $vendas)
    ->with('frenteCaixa', true)
    ->with('nfce', '')
    ->with('valor', $request->valor)
    ->with('nome', '')
    ->with('info', "Filtro valor: $request->valor")

    ->with('title', 'Filtro por Valor');
}
}
