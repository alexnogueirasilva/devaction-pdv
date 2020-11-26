<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\NaturezaOperacao;
class NaturezaOperacaoController extends Controller
{
    //
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

    function sanitizeString($str){
        return preg_replace('{\W}', ' ', preg_replace('{ +}', ' ', strtr(
            utf8_decode(html_entity_decode($str)),
            utf8_decode('ÀÁÃÂÉÊÍÓÕÔÚÜÇÑàáãâéêíóõôúüçñ'),
            'AAAAEEIOOOUUCNaaaaeeiooouucn')));
    }

    public function index(){
      $naturezas = NaturezaOperacao::all();
      return view('naturezaOperacao/list')
      ->with('naturezas', $naturezas)
      ->with('title', 'Naturezas de Operação');
  }

  public function new(){
      return view('naturezaOperacao/register')
      ->with('title', 'Cadastrar Natureza de Operação');
  }

  public function save(Request $request){
      $natureza = new NaturezaOperacao();
      $this->_validate($request);
      $request->merge([ 'natureza' => strtoupper(
        $this->sanitizeString($request->input('natureza')))]);

      $result = $natureza->create($request->all());

      if($result){
         session()->flash('color', 'blue');
         session()->flash("message", "Natureza de Operação cadastrada com sucesso.");
     }else{
         session()->flash('color', 'red');
         session()->flash('message', 'Erro ao cadastrar natureza de operação.');
     }

     return redirect('/naturezaOperacao');
 }

 public function edit($id){
        $naturezaOperacao = new NaturezaOperacao(); //Model

        $resp = $naturezaOperacao
        ->where('id', $id)->first();  

        return view('naturezaOperacao/register')
        ->with('natureza', $resp)
        ->with('title', 'Editar natureza de operação');

    }

    public function update(Request $request){
    	$natureza = new NaturezaOperacao();

    	$id = $request->input('id');
    	$resp = $natureza
    	->where('id', $id)->first(); 

    	$this->_validate($request);
    	
        $resp->natureza = $this->sanitizeString(strtoupper($request->input('natureza')));
        $resp->CFOP_entrada_estadual = $request->input('CFOP_entrada_estadual');
        $resp->CFOP_entrada_inter_estadual = $request->input('CFOP_entrada_inter_estadual');
        $resp->CFOP_saida_estadual = $request->input('CFOP_saida_estadual');
        $resp->CFOP_saida_inter_estadual = $request->input('CFOP_saida_inter_estadual');

        $result = $resp->save();
        if($result){
          session()->flash('color', 'green');
          session()->flash('message', 'Natureza de operação editada com sucesso!');
      }else{
          session()->flash('color', 'red');
          session()->flash('message', 'Erro ao editar categoria!');
      }

      return redirect('/naturezaOperacao'); 
  }

  public function delete($id){
   $delete = NaturezaOperacao
   ::where('id', $id)
   ->delete();
   if($delete){
      session()->flash('color', 'blue');
      session()->flash('message', 'Registro removido!');
  }else{
      session()->flash('color', 'red');
      session()->flash('message', 'Erro!');
  }
  return redirect('/naturezaOperacao');
}


private function _validate(Request $request){
   $rules = [
      'natureza' => 'required|max:80',
      'CFOP_entrada_estadual' => 'required|min:4',
      'CFOP_entrada_inter_estadual' => 'required|min:4',
      'CFOP_saida_estadual' => 'required|min:4',
      'CFOP_saida_inter_estadual' => 'required|min:4',
  ];

  $messages = [
      'natureza.required' => 'O campo nome é obrigatório.',
      'natureza.max' => '80 caracteres maximos permitidos.',
      'CFOP_entrada_estadual.required' => 'Campo obritatório.',
      'CFOP_entrada_estadual.min' => 'Minimo de 4 digitos.',
      'CFOP_entrada_inter_estadual.required' => 'Campo obritatório.',
      'CFOP_entrada_inter_estadual.min' => 'Minimo de 4 digitos.',
      'CFOP_saida_estadual.required' => 'Campo obritatório.',
      'CFOP_saida_estadual.min' => 'Minimo de 4 digitos.',
      'CFOP_saida_inter_estadual.required' => 'Campo obritatório.',
      'CFOP_saida_inter_estadual.min' => 'Minimo de 4 digitos.',
  ];
  $this->validate($request, $rules, $messages);
}
}
