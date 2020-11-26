<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CategoriaConta;

class CategoriaContaController extends Controller {
	
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
		$categorias = CategoriaConta::all();
		return view('categoriasConta/list')
		->with('categorias', $categorias)
		->with('title', 'Categoria de Contas');
	}

	public function new(){
		return view('categoriasConta/register')
		->with('title', 'Cadastrar Categoria de Conta');
	}

	public function save(Request $request){
		$categoria = new CategoriaConta();
		$this->_validate($request);

		$result = $categoria->create($request->all());

		if($result){
			session()->flash('color', 'blue');
			session()->flash("message", "Categoria cadastrada com sucesso.");
		}else{
			session()->flash('color', 'red');
			session()->flash('message', 'Erro ao cadastrar categoria.');
		}
		
		return redirect('/categoriasConta');
	}

	public function edit($id){
        $categoria = new CategoriaConta(); //Model

        $resp = $categoria
        ->where('id', $id)->first();  

        return view('categoriasConta/register')
        ->with('categoria', $resp)
        ->with('title', 'Editar Categoria de Conta');

    }

    public function update(Request $request){
    	$categoria = new CategoriaConta();

    	$id = $request->input('id');
    	$resp = $categoria
    	->where('id', $id)->first(); 

    	$this->_validate($request);
    	

    	$resp->nome = $request->input('nome');

    	$result = $resp->save();
    	if($result){
    		session()->flash('color', 'green');
    		session()->flash('message', 'Categoria editada com sucesso!');
    	}else{
    		session()->flash('color', 'red');
    		session()->flash('message', 'Erro ao editar categoria!');
    	}
    	
    	return redirect('/categoriasConta'); 
    }

    public function delete($id){
    	$delete = CategoriaConta
    	::where('id', $id)
    	->delete();
    	if($delete){
    		session()->flash('color', 'blue');
    		session()->flash('message', 'Registro removido!');
    	}else{
    		session()->flash('color', 'red');
    		session()->flash('message', 'Erro!');
    	}
    	return redirect('/categoriasConta');
    }


    private function _validate(Request $request){
    	$rules = [
    		'nome' => 'required|max:50'
    	];

    	$messages = [
    		'nome.required' => 'O campo nome é obrigatório.',
    		'nome.max' => '50 caracteres maximos permitidos.'
    	];
    	$this->validate($request, $rules, $messages);
    }
}
