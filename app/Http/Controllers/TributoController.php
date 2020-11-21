<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tributacao;
class TributoController extends Controller
{
    public function __construct(){
		$this->middleware(function ($request, $next) {
			$value = session('user_logged');
			if(!$value){
				return redirect("/login");
			}else{
                if($value['acesso_fiscal'] == 0){
                    return redirect("/sempermissao");
                }
            }
			return $next($request);
		});
	}

	public function index(){

		$regimes = Tributacao::regimes();
		$tributo = Tributacao::
		first();
		return view('tributos/index')
		->with('tributo', $tributo)
		->with('regimes', $regimes)
		->with('title', 'Configurar Tributação');
	}


	public function save(Request $request){
		$this->_validate($request);
		if($request->id == 0){
			$result = Tributacao::create([
				'icms' => $request->icms,
				'pis' => $request->pis,
				'cofins' => $request->cofins,
				'ipi' => $request->ipi,
				'regime' => $request->regime,
				'ncm_padrao' => $request->ncm_padrao
			]);
		}else{
			$trib = Tributacao::
			first();

			$trib->icms = $request->icms;
			$trib->pis = $request->pis;
			$trib->cofins = $request->cofins;
			$trib->ipi = $request->ipi;
			$trib->regime = $request->regime;
			$result = $trib->save();
		}

		if($result){
			session()->flash('color', 'blue');
			session()->flash("message", "Configurado com sucesso!");
		}else{
			session()->flash('color', 'red');
			session()->flash('message', 'Erro ao configurar!');
		}

		return redirect('/tributos');
	}


	private function _validate(Request $request){
		$rules = [
			'icms' => 'required',
			'pis' => 'required',
			'cofins' => 'required',
			'ipi' => 'required',
			'ncm_padrao' => 'required'
			
		];

		$messages = [
			'icms.required' => 'O campo ICMS é obrigatório.',
			'pis.required' => 'O campo PIS é obrigatório.',
			'cofins.required' => 'O campo COFINS é obrigatório.',
			'ipi.required' => 'O campo IPI é obrigatório.',
			'ncm_padrao.required' => 'O campo NCM é obrigatório.'

		];
		$this->validate($request, $rules, $messages);
	}
}
