<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\StockMove;
use App\Estoque;
use App\Produto;
use App\Apontamento;

class StockController extends Controller
{
	public function __construct(){
        $this->middleware(function ($request, $next) {
            $value = session('user_logged');
            if(!$value){
                return redirect("/login");
            }else{
                if($value['acesso_estoque'] == 0){
                    return redirect("/sempermissao");
                }
            }
            return $next($request);
        });
    }

    public function index(){
      $estoque = Estoque::
      orderBy('updated_at', 'desc')
      ->paginate(20);
      return view('stock/list')
      ->with('estoque', $estoque)
      ->with('links', true)
      ->with('title', 'Estoque');
  }

  public function view($id){
     $stockMove = new StockMove();
     $stockMove->downStock(1,1,1);
 }

 public function apontamento(){
    $apontamentos = Apontamento::
    limit(5)
    ->orderBy('id', 'desc')
    ->get();

    return view('stock/apontamento')
    ->with('apontamentos', $apontamentos)
    ->with('produtoJs', true)
    ->with('title', 'Apontamento');
}

public function apontamentoManual(){
   return view('stock/apontaManual')
   ->with('produtoJs', false)
   ->with('title', 'Apontamento Manual');
}

public function todosApontamentos(){
    $apontamentos = Apontamento::
    orderBy('id', 'desc')
    ->paginate(10);
    return view("stock/todosApontamentos")
    ->with('apontamentos', $apontamentos)
    ->with('links', true)
    ->with('title', 'Todos os apontamentos');
}

public function filtroApontamentos(Request $request){
    $apontamentos = Apontamento::
    whereBetween('data_registro', 
        [$this->parseDate($request->dataInicial), 
            $this->parseDate($request->dataFinal)])
    ->orderBy('data_registro', 'desc')
    ->get();
    return view("stock/todosApontamentos")
    ->with('apontamentos', $apontamentos)
    ->with('links', false)
    ->with('dataInicial', $request->dataInicial)
    ->with('dataFinal', $request->dataFinal)
    ->with('title', 'Todos os apontamentos');
}

private function parseDate($date){
    return date('Y-m-d', strtotime(str_replace("/", "-", $date)));
}

public function saveApontamento(Request $request){
    $this->_validateApontamento($request);

    $produto = $request->input('produto');
    $produto = explode("-", $produto);
    $produto = $produto[0];

    $result = Apontamento::create([
        'quantidade' => str_replace(",", ".", $request->quantidade),
        'usuario_id' => get_id_user(),
        'produto_id' => $produto
    ]);

    $prod = Produto::
    where('id', $produto)
    ->first();

    $stockMove = new StockMove();
    $stockMove->pluStock((int) $produto, 
        str_replace(",", ".", $request->quantidade),
        str_replace(",", ".", $prod->valor_venda));

    $this->downEstoquePorReceita($produto, str_replace(",", ".", $request->quantidade));

    if($result){
        session()->flash('color', 'blue');
        session()->flash("message", "Apontamento cadastrado com sucesso!");
    }else{
        session()->flash('color', 'red');
        session()->flash('message', 'Erro ao cadastrar apontamento!');
    }

    return redirect("/estoque/apontamentoProducao");
}


public function saveApontamentoManual(Request $request){
    $this->_validateApontamento($request);

    $produto = $request->input('produto');
    $produto = explode("-", $produto);
    $produto = $produto[0];

    $prod = Produto::
    where('id', $produto)
    ->first();


    $stockMove = new StockMove();
    $result = $stockMove->pluStock((int) $produto, 
        str_replace(",", ".", $request->quantidade),
        str_replace(",", ".", $prod->valor_venda));

    if($result){
        session()->flash('color', 'blue');
        session()->flash("message", "Apontamento Manual cadastrado com sucesso!");
    }else{
        session()->flash('color', 'red');
        session()->flash('message', 'Erro ao cadastrar apontamento manual!');
    }

    return redirect("/estoque");
}

private function downEstoquePorReceita($idProduto, $quantidade){
    $produto = Produto::
    where('id', $idProduto)
    ->first();
    $stockMove = new StockMove();
    foreach($produto->receita->itens as $i){
        $stockMove->downStock($i->produto->id, $i->quantidade * $quantidade);
    }

}

public function deleteApontamento($id){
    $ap = Apontamento::
    where('id', $id)
    ->first();
    foreach($ap->produto->receita->itens as $i){
        echo $i->quantidade;
    }
}

private function _validateApontamento(Request $request){
    $rules = [
        'produto' => 'required|min:5',
        'quantidade' => 'required|min:4',
    ];

    $messages = [
        'produto.required' => 'O campo produto é obrigatório.',
        'produto.min' => 'Clique sobre o produto desejado.',
        'quantidade.required' => 'O campo quantidade é obrigatório.',
        'quantidade.min' => 'Informe o valor do campo em casas decimais, ex: 1,000.'
    ];

    $this->validate($request, $rules, $messages);

}

}
