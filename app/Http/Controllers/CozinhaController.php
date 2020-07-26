<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pedido;
use App\ItemPedido;

class CozinhaController extends Controller
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
    
    public function index(){
    	return view('controleCozinha/index')
    	->with('cozinhaJs', true)
    	->with('title', 'Controle de Pedidos');
    }

    public function buscar(){
    	$itens = ItemPedido::
    	where('status', false)
    	->orderBy('created_at', 'asc')
    	->get();

    	foreach($itens as $i){
    		$i->produto;
            $i->comanda = $i->pedido->comanda;

    		$adicionais = "";
    		foreach($i->itensAdicionais as $key => $a){

    			$adicionais .= $a->adicional->nome . ($key < count($i->itensAdicionais)-1 ? " | " : "");
    		}

    		$saboresPizza = "";

    		foreach($i->sabores as $key => $s){
                $saboresPizza .= $s->produto->produto->nome . ($key < count($i->sabores)-1 ? " | " : "");
            }
            
    		$i->tamanhoPizza = $i->tamanho != null ? $i->tamanho->nome : false;


    		$i->adicionais = $adicionais;
    		$i->saboresPizza = $saboresPizza;
    		$i->data = \Carbon\Carbon::parse($i->created_at)->format('H:i:s');
    	}

    	return response()->json($itens, 200);
    }

    public function concluido(Request $request){
    	$item = ItemPedido::find($request->id);
    	$item->status = true;

    	return response()->json($item->save(), 200);

    }
}
