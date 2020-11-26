<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VendaCaixa;
use App\Helpers\StockMove;
use App\ItemVendaCaixa;
use App\CreditoVenda;
use App\ConfigNota;
use App\PedidoDelivery;
use App\Produto;
use App\ProdutoPizza;
use App\Pedido;

class VendaCaixaController extends Controller
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
  
  public function save(Request $request){

   $venda = $request->venda;

   $config = ConfigNota::first();

   $result = VendaCaixa::create([
    'cliente_id' => $venda['cliente'],
    'usuario_id' => get_id_user(),
    'natureza_id' => $config->nat_op_padrao,
    'valor_total' => str_replace(",", ".", $venda['valor_total']) + str_replace(",", ".", $venda['acrescimo']) - str_replace(",", ".", $venda['desconto']),
    'acrescimo' => str_replace(",", ".", $venda['acrescimo']),
    'troco' => str_replace(",", ".", $venda['troco']),
    'dinheiro_recebido' => str_replace(",", ".", $venda['dinheiro_recebido']),
    'forma_pagamento' => $venda['acao'] == 'credito' ? 'credito' : " ",
    'tipo_pagamento' => $venda['tipo_pagamento_1'] ? '99' : $venda['tipo_pagamento'],
    'estado' => 'DISPONIVEL',
    'NFcNumero' => 0,
    'chave' => '',
    'path_xml' => '',
    'nome' => $venda['nome'] ?? '',
    'cpf' => $venda['cpf'] ?? '',
    'observacao' => $venda['observacao'] ?? '',
    'desconto' => $venda['desconto'],
    'pedido_delivery_id' => isset($venda['delivery_id']) ? $venda['delivery_id'] : 0,
    'tipo_pagamento_1' => $venda['tipo_pagamento_1'] ?? '', 
    'valor_pagamento_1' => $venda['valor_pagamento_1'] ?? 0,
    'tipo_pagamento_2' => $venda['tipo_pagamento_2'] ?? '',
    'valor_pagamento_2' => $venda['valor_pagamento_2'] ?? 0,
    'tipo_pagamento_3' => $venda['tipo_pagamento_3'] ?? '',
    'valor_pagamento_3' => $venda['valor_pagamento_3'] ?? 0
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

    ItemVendaCaixa::create([
      'venda_caixa_id' => $result->id,
      'produto_id' => (int) $i['id'],
      'quantidade' => (float) str_replace(",", ".", $i['quantidade']),
      'valor' => (float) str_replace(",", ".", $i['valor']),
      'item_pedido_id' => isset($i['itemPedido']) ? $i['itemPedido'] : NULL,
      'observacao' => $i['obs'] ?? ''
    ]);


    if(!isset($venda['delivery_id']) || $venda['delivery_id'] == 0){
     // nao delivery
      $prod = Produto
      ::where('id', $i['id'])
      ->first();

      if(isset($venda['pizza']) && $i['pizza'] == 1){
        $sabores = explode(" | ", $i['nome']);
        $totalSabores = count($sabores);
        foreach($sabores as $sb){

          $produto = Produto::
          where('nome', $sb)
          ->first();

          $produtoPizza = ProdutoPizza::
          where('produto_id', $i['id'])
          ->where('valor', $i['valor'])
          ->first();


          if(!empty($produto->receita)){
            $receita = $produto->receita;
            foreach($receita->itens as $rec){

              $stockMove->downStock(
                $rec->produto_id, 
                (float) str_replace(",", ".", $i['quantidade']) 
                      * 
                ((($rec->quantidade/$totalSabores)/$receita->pedacos)*$produtoPizza->tamanho->pedacos)/$receita->rendimento
              );
            }
          }


        }

      }else if(!empty($prod->receita)){


        $receita = $prod->receita; // baixa por receita
        
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
          (int) $i['id'], 
          (float) str_replace(",", ".", $i['quantidade'])
        );
      }
    }

  }

        //DELIVERY
  if(isset($venda['delivery_id']) && $venda['delivery_id'] > 0){
    $pedidoDelivery = PedidoDelivery
    ::where('id', $venda['delivery_id'])
    ->first();

    foreach($pedidoDelivery->itens as $i){

      if(count($i->sabores) > 0){

        $totalSabores = count($i->sabores);
        foreach($i->sabores as $sb){
          if(!empty($sb->produto->produto->receita)){
            $receita = $sb->produto->produto->receita;
            foreach($receita->itens as $rec){

              $stockMove->downStock(
                $rec->produto_id, 
                (float) str_replace(",", ".", $i['quantidade']) 
                      * 
                ((($rec->quantidade/$totalSabores)/$receita->pedacos)*$i->tamanho->pedacos)/$receita->rendimento
              );
            }
          }
        }
      }else{

        if(!empty($i->produto->produto->receita)){
          $receita = $i->produto->produto->receita; // baixa por receita
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
            $i->produto->produto->id, 
            (float) str_replace(",", ".", $i['quantidade'])
          );
        }
      }

    }
  }

  echo json_encode($result);
}

public function diaria(){
  date_default_timezone_set('America/Sao_Paulo');
  $hoje = date("Y-m-d") . " 00:00:00";
  $amanha = date('Y-m-d', strtotime('+1 days')). " 00:00:00";
  $vendas = VendaCaixa::
  whereBetween('data_registro', [$hoje, 
   $amanha])
  ->get();
  echo json_encode($vendas);
}
}
