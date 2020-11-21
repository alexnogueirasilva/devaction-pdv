<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pedido;
use App\ItemPedido;
use App\ItemPizzaPedidoLocal;
use App\Produto;
use App\ProdutoPizza;
use App\ItemPedidoComplementoLocal;
use App\ComplementoDelivery;
use App\VendaCaixa;
use App\ConfigNota;
use App\BairroDelivery;
use App\PedidoDelete;
use App\Mesa;
use App\Usuario;
use Comtele\Services\TextMessageService;
use NFePHP\DA\NFe\CupomPedido;
use NFePHP\DA\NFe\Itens;
use App\Certificado;

class PedidoController extends Controller{

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
    $pedidos = Pedido::
    where('desativado', false)
    ->get();

    $mesas = Mesa::all();
    $mesasParaAtivar = $this->mesasParaAtivar();

    $mesasFechadas = $this->mesasFechadas();

    return view('pedido/list')
    ->with('pedidos', $pedidos)
    ->with('mesas', $mesas)
    ->with('atribuirComandaJs', true)
    ->with('mesasParaAtivar', $mesasParaAtivar)
    ->with('mesasFechadas', $mesasFechadas)
    ->with('title', 'Lista de Pedidos');
  }

  private function mesasParaAtivar(){
    $mesas = Pedido::where('mesa_ativa', false)
    ->where('mesa_id', '!=', null)
    ->get();
    return $mesas;
  }

  private function mesasFechadas(){
    $mesas = Pedido::where('fechar_mesa', true)
    ->where('mesa_id', '!=', null)
    ->where('desativado', false)
    ->get();
    return $mesas;
  }


  public function abrir(Request $request){
    $comanda = Pedido::
    where('comanda', $request->comanda)
    ->where('desativado', false)
    ->first();
    if(empty($comanda)){
     $res = Pedido::create([
      'comanda' => $request->comanda,
      'observacao' => $request->observacao ?? '',
      'status' => false,
      'nome' => '',
      'rua' => '',
      'numero' => '',
      'bairro_id' => null,
      'referencia' => '',
      'telefone' => '',
      'desativado' => false,
      'mesa_id' => $request->mesa_id != 'null' ? $request->mesa_id : null
    ]);
     if($res) {
      session()->flash('color', 'green');
      session()->flash('message', 'Comanda aberta com sucesso!');
    }
  }else{
    session()->flash('color', 'red');
    session()->flash('message', 'Esta comanda encontra-se ativa!');
  }
  return redirect('/pedidos');
}


public function ver($id){
 $pedido = Pedido::
 where('id', $id)
 ->first();

 $bairros = BairroDelivery::orderBy('nome')->get();

 return view('pedido/ver')
 ->with('pedido', $pedido)
 ->with('bairros', $bairros)
 ->with('pedidoJs', true)
 ->with('title', 'Comanda '.$id);
}

public function alterarStatus($id){
  $item = ItemPedido::
  where('id', $id)
  ->first();

  $item->status = 1;
  $item->save();

  session()->flash('color', 'green');
  session()->flash('message', 'Produto '. $item->produto->nome . ' marcado como concluido!');
  return redirect("/pedidos/ver/".$item->pedido->id);
}

public function deleteItem($id){
 $item = ItemPedido::
 where('id', $id)
 ->first();

 //armazena item

 PedidoDelete::create(
  [
    'pedido_id' => $item->pedido_id,
    'produto' => $item->nomeDoProduto(),
    'quantidade' => $item->quantidade,
    'valor' => $item->valor,
    'data_insercao' => \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i:s')
  ]
);
 //fim armazena item

 if($item->delete()){
   session()->flash('color', 'green');
   session()->flash('message', 'Item removido!');
 }else{
   session()->flash('color', 'red');
   session()->flash('message', 'Erro');
 }
 return redirect('/pedidos/ver/'.$item->pedido_id);
}

public function desativar($id){
  $item = Pedido::
  where('id', $id)
  ->first();
  $item->desativado = true;
  $res = $item->save();

  if($res){
    session()->flash('color', 'green');
    session()->flash('message', 'Comanda desativada!');
  }else{
    session()->flash('color', 'red');
    session()->flash('message', 'Erro');
  }
  return redirect('/pedidos');
}

public function emAberto(){
  $pedidos = ItemPedido::where('status', false)
  ->get();

  return response()->json(count($pedidos), 200);
}


public function saveItem(Request $request){

  $this->_validateItem($request);
  $pedido = Pedido::
  where('id', $request->id)
  ->first();

  $produto = $request->input('produto');
  $produto = explode("-", $produto);
  $produto = $produto[0];

  $result = ItemPedido::create([
    'pedido_id' => $pedido->id,
    'produto_id' => $produto,
    'quantidade' => str_replace(",", ".", $request->quantidade),
    'status' => false,
    'tamanho_pizza_id' => $request->tamanho_pizza_id ?? NULL,
    'observacao' => $request->observacao ?? '',
    'valor' => str_replace(",", ".", $request->valor),
    'impresso' => false
  ]);

  if($request->tamanho_pizza_id && $request->sabores_escolhidos){
    $saborDup = false;

    $sabores = explode(",", $request->sabores_escolhidos);
    if(count($sabores) > 0){
      foreach($sabores as $sab){
        $prod = Produto
        ::where('nome', $sab)
        ->first();

        $item = ItemPizzaPedidoLocal::create([
          'item_pedido' => $result->id,
          'sabor_id' => $prod->delivery->id,
        ]);

        if($prod->id == $produto) $saborDup = true;
      }
    }
    
    if(!$saborDup){
      $prod = Produto
      ::where('id', $produto)
      ->first();
      $item = ItemPizzaPedidoLocal::create([
        'item_pedido' => $result->id,
        'sabor_id' => $prod->delivery->id,
      ]);
    }
  }else if($request->tamanho_pizza_id){
    $prod = Produto
    ::where('id', $produto)
    ->first();
    $item = ItemPizzaPedidoLocal::create([
      'item_pedido' => $result->id,
      'sabor_id' => $prod->delivery->id,
    ]);
  }

  if($request->adicioanis_escolhidos){
    $adicionais = explode(",", $request->adicioanis_escolhidos);
    foreach($adicionais as $ad){
      $nome = explode("-", $ad) ;

      $adicional = ComplementoDelivery
      ::where('nome', $nome)
      ->first();


      $item = ItemPedidoComplementoLocal::create([
        'item_pedido' => $result->id,
        'complemento_id' => $adicional->id,
        'quantidade' => str_replace(",", ".", $request->quantidade),
      ]);
    }
  }


  if($result){
   session()->flash('color', 'green');
   session()->flash('message', 'Item adicionado!');
 }else{
   session()->flash('color', 'red');
   session()->flash('message', 'Erro');
 }
 return redirect('/pedidos/ver/'.$pedido->id);
}

private function _validateItem(Request $request){
  $validaTamanho = false;
  if($request->input('produto')){
    $produto = $request->input('produto');
    $produto = explode("-", $produto);
    $produto = $produto[0];

    $p = Produto::
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

public function finalizar($id){
  $pedido = Pedido::
  where('id', $id)
  ->first();

  $atributes = $this->addAtributes($pedido->itens);

  $usuario = Usuario::find(get_id_user());
  $tiposPagamento = VendaCaixa::tiposPagamento();
  $config = ConfigNota::first();
  $certificado = Certificado::first();
  $tiposPagamentoMulti = VendaCaixa::tiposPagamentoMulti();
  
  return view('frontBox/main')
  ->with('itens', $atributes)
  ->with('cod_comanda', $pedido->comanda)
  ->with('frenteCaixa', true)
  ->with('tiposPagamento', $tiposPagamento)
  ->with('tiposPagamentoMulti', $tiposPagamentoMulti)
  ->with('config', $config)
  ->with('usuario', $usuario)
  ->with('certificado', $certificado)
  ->with('bairro', $pedido->bairro)
  ->with('title', 'Finalizar Comanda '.$id);
}


private function addAtributes($itens){
  $temp = [];
  foreach($itens as $i){
    $i->produto;

    if(!empty($i->sabores)){
      $i->sabores;

      $valorAdicional = 0;

      foreach($i->itensAdicionais as $ad){
        $valorAdicional += $ad->adicional->valor;
      }

      $i->valorAdicional = $valorAdicional;



      $maiorValor = 0;
      $somaValores = 0; 
      foreach($i->sabores as $sb){
        $sb->produto->produto;
        
        $v = $sb->maiorValor($sb->sabor_id, $i->tamanho_pizza_id);
        $somaValores += $v;
        if($v > $maiorValor) $maiorValor = $v;

        
      }

      if(getenv("DIVISAO_VALOR_PIZZA") == 1){
        $divide = sizeof($i->sabores);
        $divide = $divide == 0 ? 1 : $divide; 
        $i->maiorValor = $somaValores/$divide;
      }

    }
    $i->produto->valor_venda = $i->valor;

    if($i->maiorValor < $i->valor) $i->maiorValor = $i->valor;
    $i->produto_id = $i->produto->id;
    $i->produto->nome = $i->produto->nome;
    $i->item_pedido = $i->id;
    array_push($temp, $i);
  }
        // echo json_encode($temp);
  return $temp;
}

public function itensPendentes(){
  $itens = ItemPedido::
  where('status', false)
  ->get();

  echo json_encode(count($itens));
}

public function sms(Request $request){
  $data = $request->data;
  $phone = $data['numero'];
  $msg = $data['msg'];
  $res = $this->sendSms($phone, $msg);
  echo json_encode($res);
}

private function sendSms($phone, $msg){
  $nomeEmpresa = getenv('SMS_NOME_EMPRESA');
  $nomeEmpresa = str_replace("_", " ",  $nomeEmpresa);
  $nomeEmpresa = str_replace("_", " ",  $nomeEmpresa);
  $content = $msg;
  $textMessageService = new TextMessageService(getenv('SMS_KEY'));
  $res = $textMessageService->send("Sender", $content, [$phone]);
  return $res;
}

public function imprimirPedido($id){
  $pedido = Pedido::
  where('id', $id)
  ->first();

  $public = getenv('SERVIDOR_WEB') ? 'public/' : '';
  $pathLogo = $public.'imgs/logo.jpg';

  $cupom = new CupomPedido($pedido, $pathLogo);
  $cupom->monta();
  $pdf = $cupom->render();
  // file_put_contents($public.'pdf/CUPOM_PEDIDO.pdf',$pdf);
  // return redirect($public.'pdf/CUPOM_PEDIDO.pdf');

  return response($pdf)
  ->header('Content-Type', 'application/pdf');
}

public function itensParaFrenteCaixa(Request $request){
  $cod = $request->cod;

  $pedido = Pedido::
  where('comanda', $cod)
  ->where('status', 0)
  ->where('desativado', 0)
  ->first();

  if($pedido == null) return response()->json("Nao existe", 401);

  $atributes = $this->addAtributes($pedido->itens);
  return response()->json($atributes, 200);
}

public function setarBairro(Request $request){
  $pedido = Pedido::find($request->pedido_id);
  $pedido->bairro_id = $request->bairro_id;
  $res = $pedido->save();
  return response()->json($res, 200);
}

public function setarEndereco(Request $request){
  $pedido = Pedido::find($request->pedido_id);
  $pedido->nome = $request->nome;
  $pedido->rua = $request->rua;
  $pedido->numero = $request->numero;
  $pedido->telefone = $request->telefone;
  $pedido->referencia = $request->referencia;
  $res = $pedido->save();

  session()->flash('color', 'green');
  session()->flash('message', 'Endereço setado!');
  return redirect('/pedidos/ver/'.$request->pedido_id);
}

public function imprimirItens(Request $request){
  $ids = $request->ids;
  $ids = explode(",", $ids);
  $itens = [];
  


  foreach($ids as $i){
    if($i != null){
      $item = ItemPedido::find($i);
      $item->impresso = true;
      $item->save();
      array_push($itens, $item);
    }
  }
  if(sizeof($itens) > 0){

    $public = getenv('SERVIDOR_WEB') ? 'public/' : '';
    $pathLogo = $public.'imgs/logo.jpg';
    $cupom = new Itens($itens, $pathLogo);

    $pdf = $cupom->render();
    return response($pdf)
    ->header('Content-Type', 'application/pdf');
  }else{
    echo "Selecione ao menos um item!";
  }

  // header('Content-Type: application/pdf');
  // echo $pdf;

  

}

public function controleComandas(){
  $comandas = Pedido::
  limit(30)
  ->orderBy('id', 'desc')
  ->get();
  return view('pedido/controle_comandas')
  ->with('comandas', $comandas)
  ->with('mensagem', '*Listando os 30 ultimos registros')
  ->with('title', 'Controle de Comandas');
}

public function verDetalhes($id){
  $pedido = Pedido::find($id);
  $removidos = PedidoDelete::where('pedido_id', $id)->get();

  return view('pedido/detalhes')
  ->with('pedido', $pedido)
  ->with('removidos', $removidos)
  ->with('title', 'Detalhes comanda ' . $pedido->comanda);
}

public function filtroComanda(Request $request){
  if($request->data_inicial == null || $request->data_final == null){
    return redirect()->back();
  }

  $data_inicial = $this->parseDate($request->data_inicial);
  $data_final = $this->parseDate($request->data_final, true);
  $numero_comanda = $request->numero_comanda;

  if($numero_comanda != null){
    $comandas = Pedido::
    whereBetween('created_at', [$data_inicial, 
      $data_final])
    ->where('comanda', $numero_comanda)
    ->get();
  }else{
    $comandas = Pedido::
    whereBetween('created_at', [$data_inicial, 
      $data_final])
    ->get();
  }

  return view('pedido/controle_comandas')
  ->with('comandas', $comandas)
  ->with('mensagem', '*Listando os resultados do filtro')
  ->with('title', 'Controle de Comandas');
}

private function parseDate($date, $plusDay = false){
  if($plusDay == false)
    return date('Y-m-d', strtotime(str_replace("/", "-", $date)));
  else
    return date('Y-m-d', strtotime("+1 day",strtotime(str_replace("/", "-", $date))));
}

public function mesas(){
  $pedidos = Pedido::
  where('desativado', false)
  ->where('mesa_id', '!=', null)
  ->groupBy('mesa_id')
  ->get();
  return view('pedido/mesas')
  ->with('pedidos', $pedidos)
  ->with('title', 'Mesas em aberto');
}

public function verMesa($mesa_id){
  $mesa = Mesa::find($mesa_id);
  $pedidos = Pedido::
  where('mesa_id', $mesa_id)
  ->where('desativado', false)
  ->where('status', false)
  ->get();
  return view('pedido/verMesa')
  ->with('mesa', $mesa)
  ->with('pedidos', $pedidos)
  ->with('title', 'Comandas da Mesa');
}

public function ativarMesa($id){
  $pedido = Pedido::find($id);

  $pedido->mesa_ativa = true;
  $pedido->Save();
  session()->flash('color', 'green');
  session()->flash('message', 'Mesa ativada com sucesso!');

  return redirect('/pedidos');
}

public function atribuirComanda(Request $request){
  $pedido = Pedido::find($request->pedido_id);
  $pedido->observacao = $request->observacao;
  $pedido->comanda = $request->comanda;
  $pedido->Save();
  session()->flash('color', 'green');
  session()->flash('message', 'Comanda atribuida a ' . $pedido->mesa->nome . '!');

  return redirect('/pedidos');

}

public function atribuirMesa(Request $request){
  $pedido = Pedido::find($request->pedido_id);
  $pedido->mesa_id = $request->mesa;

  $pedido->Save();
  session()->flash('color', 'green');
  session()->flash('message', 'Mesa atribuida a comanda ' . $pedido->comanda . '!');

  return redirect('/pedidos');

}

}
