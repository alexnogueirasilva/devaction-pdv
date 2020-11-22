<?php

namespace App\Http\Controllers\AppFiscal;

use Illuminate\Http\Request;
use App\Produto;
use App\Cliente;
use App\ContaReceber;
use App\ContaPagar;
use App\ClienteDelivery;
use App\VendaCaixa;
use App\Venda;

class HomeController extends Controller
{
	public function dadosGrafico(){
		return response()->json($this->totalizacao(), 200);
	}
	
	private function totalizacao(){
        $totalDeProdutos = count(Produto::all());
        $totalDeClientes = count(Cliente::all()) + 
        count(ClienteDelivery::where('ativo', true)->get());


        return [
            'totalDeClientes' => $totalDeClientes,
            'totalDeProdutos' => $totalDeProdutos,
            'totalDeVendas' => $this->totalDeVendasHoje(),
            'totalDeContaReceber' => $this->totalDeContaReceberHoje(),
            'totalDeContaPagar' => $this->totalDeContaPagarHoje(),
            'faturamentoDosUltimosSeteDias' => $this->faturamentoDosUltimosSeteDias()
        ];
    }

    private function totalDeVendasHoje(){
        $vendas = Venda::
        select(\DB::raw('sum(valor_total) as total'))
        ->whereBetween('data_registro', [date("Y-m-d"), 
            date('Y-m-d', strtotime('+1 day'))])
        ->first();

        $vendaCaixas = VendaCaixa::
        select(\DB::raw('sum(valor_total) as total'))
        ->whereBetween('data_registro', [date("Y-m-d"), 
            date('Y-m-d', strtotime('+1 day'))])
        ->first();


        return $vendas->total + $vendaCaixas->total;

    }


    private function totalDeContaReceberHoje(){
        $contas = ContaReceber::
        select(\DB::raw('sum(valor_integral) as total'))
        ->whereBetween('data_vencimento', [date("Y-m-d"), 
            date('Y-m-d', strtotime('+1 day'))])
        ->where('status', false)
        ->first(); 
        return $contas->total ?? 0;
    }

    private function totalDeContaPagarHoje(){
        $contas = ContaPagar::
        select(\DB::raw('sum(valor_integral) as total'))
        ->whereBetween('date_register', [date("Y-m-d"), 
            date('Y-m-d', strtotime('+1 day'))])
        ->where('status', false)
        ->first(); 
        return $contas->total ?? 0;
    }



    public function faturamentoDosUltimosSeteDias(){

        $arrayVendas = [];
        for($aux = 0; $aux > -7; $aux--){
            $vendas = Venda::
            select(\DB::raw('sum(valor_total) as total'))
            ->whereBetween('data_registro', 
                [
                    date('Y-m-d', strtotime($aux.' day')), 
                    date('Y-m-d', strtotime(($aux+1).' day'))
                ]
            )
            ->first();


            $vendaCaixas = VendaCaixa::
            select(\DB::raw('sum(valor_total) as total'))
            ->whereBetween('data_registro', 
                [
                    date('Y-m-d', strtotime($aux.' day')), 
                    date('Y-m-d', strtotime(($aux+1).' day'))
                ]
            )
            ->first();
            $temp = [
                'data' => date('d/m', strtotime(($aux).' day')),
                'total' => number_format(($vendas->total + $vendaCaixas->total), 2)
            ];
            array_push($arrayVendas, $temp);
        }
        return array_reverse($arrayVendas);
        
    }

    public function faturamentoFiltrado(Request $request){

        $dataInicial = strtotime(str_replace("/", "-", $request->data_inicial));
        $dataFinal = strtotime(str_replace("/", "-", $request->data_final));

        $diferenca = ($dataFinal - $dataInicial)/86400; //86400 segundos do dia

        $arrayVendas = [];
        
        if($diferenca+1 > 30){ //filtrar por mes

            $total = 0;
            for($aux = 0; $aux > (($diferenca+1)*-1); $aux--){
                $vendas = Venda::
                select(\DB::raw('sum(valor_total) as total'))
                ->whereBetween('data_registro', 
                    [
                        date('Y-m-d', strtotime($aux.' day')), 
                        date('Y-m-d', strtotime(($aux+1).' day'))
                    ]
                )
                ->first();


                $vendaCaixas = VendaCaixa::
                select(\DB::raw('sum(valor_total) as total'))
                ->whereBetween('data_registro', 
                    [
                        date('Y-m-d', strtotime($aux.' day')), 
                        date('Y-m-d', strtotime(($aux+1).' day'))
                    ]
                )
                ->first();

                if($this->confereMesNoArray($arrayVendas, date('m/Y', strtotime(($aux).' day')))){
                    $cont = 0;
                    foreach($arrayVendas as $arr){
                        if($arr['data'] == date('m/Y', strtotime(($aux).' day'))){
                            $arrayVendas[$cont]['total'] += $vendas->total + $vendaCaixas->total;

                        }
                        $cont++;
                    }
                }else{
                    $temp = [
                        'data' => date('m/Y', strtotime(($aux).' day')),
                        'total' => number_format($total, 2)
                    ];
                    array_push($arrayVendas, $temp);
                }
                
            }
            
        }else{ //filtro por dia
            for($aux = 0; $aux > (($diferenca+1)*-1); $aux--){
                $vendas = Venda::
                select(\DB::raw('sum(valor_total) as total'))
                ->whereBetween('data_registro', 
                    [
                        date('Y-m-d', strtotime($aux.' day')), 
                        date('Y-m-d', strtotime(($aux+1).' day'))
                    ]
                )
                ->first();


                $vendaCaixas = VendaCaixa::
                select(\DB::raw('sum(valor_total) as total'))
                ->whereBetween('data_registro', 
                    [
                        date('Y-m-d', strtotime($aux.' day')), 
                        date('Y-m-d', strtotime(($aux+1).' day'))
                    ]
                )
                ->first();
                $temp = [
                    'data' => date('d/m', strtotime(($aux).' day')),
                    'total' => number_format(($vendas->total + $vendaCaixas->total), 2)
                ];
                array_push($arrayVendas, $temp);
            }
        }
        return response()->json(array_reverse($arrayVendas));
        
    }

    private function confereMesNoArray($arr, $mes){
        foreach($arr as $a){
            if($a['data'] == $mes) return true;
        }
        return false;
    }
}