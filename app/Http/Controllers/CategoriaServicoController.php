<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CategoriaServico;
class CategoriaServicoController extends Controller
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
        $categorias = CategoriaServico::all();
        return view('categoriasServico/list')
        ->with('categorias', $categorias)
        ->with('title', 'Categorias');
    }

    public function new(){
        return view('categoriasServico/register')
        ->with('title', 'Cadastrar Categoria de Serviço');
    }

    public function save(Request $request){
        $category = new CategoriaServico();
        $this->_validate($request);

        $result = $category->create($request->all());

        if($result){
            session()->flash('color', 'blue');
            session()->flash("message", "Categoria cadastrada com sucesso.");
        }else{
            session()->flash('color', 'red');
            session()->flash('message', 'Erro ao cadastrar categoria.');
        }
        
        return redirect('/categoriasServico');
    }

    public function edit($id){
        $categoria = new CategoriaServico(); //Model

        $resp = $categoria
        ->where('id', $id)->first();  

        return view('categoriasServico/register')
        ->with('categoria', $resp)
        ->with('title', 'Editar Categoria de Serviço');

    }

    public function update(Request $request){
        $categoria = new CategoriaServico();

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
        
        return redirect('/categoriasServico'); 
    }

    public function delete($id){
        try{
            $delete = CategoriaServico
            ::where('id', $id)
            ->delete();
            if($delete){
                session()->flash('color', 'blue');
                session()->flash('message', 'Registro removido!');
            }else{
                session()->flash('color', 'red');
                session()->flash('message', 'Erro!');
            }
            return redirect('/categoriasServico');
        }catch(\Exception $e){
            return view('errors.sql')
            ->with('title', 'Erro ao deletar categoria de serviço')
            ->with('motivo', 'Não é possivel remover categorias presentes em serviços!');
        }
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
