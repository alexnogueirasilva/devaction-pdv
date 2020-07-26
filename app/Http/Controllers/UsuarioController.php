<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Usuario;

class UsuarioController extends Controller
{

	public function __construct(){
		$this->middleware(function ($request, $next) {
			$value = session('user_logged');
			if(!$value){
				return redirect("/login");
			}else{
				if($value['adm'] == 0){
					return redirect("/sempermissao");
				}
			}
			return $next($request);
		});
	}

	public function lista(){
		$usuarios = Usuario::all();
		return view('usuarios/list')
		->with('usuarios', $usuarios)
		->with('title', 'Lista de Usuarios');
	}

	public function new(){
		return view('usuarios/register')
		->with('usuarioJs', true)
		->with('title', 'Cadastrar Usuarios');
	}

	public function save(Request $request){
		$result = Usuario::create([
			'nome' => $request->nome,
			'login' => $request->login,
			'senha' => md5($request->senha),
			'adm' => $request->adm ? true : false,
			'acesso_cliente' => $request->acesso_cliente ? true : false,
			'acesso_fornecedor' => $request->acesso_fornecedor ? true : false,
			'acesso_produto' => $request->acesso_produto ? true : false,
			'acesso_financeiro' => $request->acesso_financeiro ? true : false,
			'acesso_caixa' => $request->acesso_caixa ? true : false,
			'acesso_estoque' => $request->acesso_estoque ? true : false,
			'acesso_compra' => $request->acesso_compra ? true : false,
			'acesso_fiscal' => $request->acesso_fiscal ? true : false,
			'ativo' => true
		]);

		if($result){
			session()->flash('color', 'blue');
			session()->flash("message", "Usuário salvo!");
		}else{
			session()->flash('color', 'red');
			session()->flash('message', 'Erro ao criar usuário!');
		}

		return redirect('/usuarios');
	}

	public function update(Request $request){

		$usr = Usuario::
		where('id', $request->id)
		->first();

		$usr->nome = $request->nome;
		$usr->login = $request->login;
		$usr->senha = md5($request->senha);
		$usr->adm = $request->adm ? true : false;
		$usr->acesso_cliente = $request->acesso_cliente ? true : false;
		$usr->acesso_fornecedor = $request->acesso_fornecedor ? true : false;
		$usr->acesso_produto = $request->acesso_produto ? true : false;
		$usr->acesso_financeiro = $request->acesso_financeiro ? true : false;
		$usr->acesso_caixa = $request->acesso_caixa ? true : false;
		$usr->acesso_estoque = $request->acesso_estoque ? true : false;
		$usr->acesso_compra = $request->acesso_compra ? true : false;
		$usr->acesso_fiscal = $request->acesso_fiscal ? true : false;

		$result = $usr->save();
		if($result){
			session()->flash('color', 'blue');
			session()->flash("message", "Usuário atualizado!");
		}else{
			session()->flash('color', 'red');
			session()->flash('message', 'Erro ao atualizar usuário!');
		}

		return redirect('/usuarios');
	}


	public function edit($id){
		$usuario = Usuario::
		where('id', $id)
		->first();

		return view('usuarios/register')
		->with('usuarioJs', true)
		->with('usuario', $usuario)
		->with('title', 'Cadastrar Usuarios');
	}

	public function delete($id){
		$usuario = Usuario::
		where('id', $id)
		->first();

		if($usuario->delete()){
			session()->flash('color', 'blue');
			session()->flash("message", "Usuário removido!");
		}else{
			session()->flash('color', 'red');
			session()->flash('message', 'Erro ao remover usuário!');
		}

		return redirect('/usuarios');
	}


	private function _validate(Request $request){
		$rules = [
			'nome' => 'required',
			'login' => 'required',
			'senha' => 'required',
		];

		$messages = [
			'nome.required' => 'O campo nome é obrigatório.',
			'login.max' => 'O campo login é obrigatório.',
			'senha.max' => 'O campo senha é obrigatório'
		];
		$this->validate($request, $rules, $messages);
	}
}
