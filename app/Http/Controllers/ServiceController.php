<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Servico;
use App\CategoriaServico;
use App\TypeService;
class ServiceController extends Controller
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
        $servicos = Servico::all();
        return view('servicos/list')
        ->with('servicos', $servicos)
        ->with('title', 'Serviços');
    }

    public function pesquisa(Request $request){
        $pesquisa = $request->input('pesquisa');
        $servicos = Servico::where('nome', 'LIKE', "%$pesquisa%")->get();
        return view('servicos/list')
        ->with('servicos', $servicos)
        ->with('title', 'Serviços');
    }

    public function new(){
        $categorias = CategoriaServico::all();
        if(sizeof($categorias) == 0){
            session()->flash('color', 'red');
            session()->flash('message', 'Cadastre uma categoria primeiramente!');
            return redirect('/categoriasServico');
        }
        return view('servicos/register')
        ->with('categorias', $categorias)
        ->with('title', 'Cadastrar Servico');
    }

    public function save(Request $request){
        $servico = new Servico();
        $this->_validate($request);
        $valor = str_replace(".", "", $request->input('valor'));
        $valor = str_replace(",", ".", $valor);
        $request->merge([ 'valor' => $valor]);

        $result = $servico->create($request->all());

        if($result){
            session()->flash('color', 'blue');
            session()->flash("message", "Serviço cadastrado com sucesso!");
        }else{
            session()->flash('color', 'red');
            session()->flash('message', 'Erro ao cadastrar serviço!');
        }
        
        return redirect('/servicos');
    }

    public function edit($id){
        $servico = new Servico(); //Model
        $categorias = CategoriaServico::all();
        $resp = $servico
        ->where('id', $id)->first();  

        return view('servicos/register')
        ->with('servico', $resp)
        ->with('categorias', $categorias)
        ->with('title', 'Editar Serviço');

    }

    public function update(Request $request){
        $servico = new Servico();

        $id = $request->input('id');
        $resp = $servico
        ->where('id', $id)->first(); 

        $this->_validate($request);
        $valor = str_replace(".", "", $request->input('valor'));
        $valor = str_replace(",", ".", $valor);

        $resp->nome = $request->input('nome');
        $resp->unidade_cobranca = $request->input('unidade_cobranca');
        $resp->categoria_id = $request->input('categoria_id');
        $resp->valor = $valor;

        $result = $resp->save();
        if($result){
            session()->flash('color', 'Serviço');
            session()->flash('message', 'Categoria editada com sucesso!');
        }else{
            session()->flash('color', 'red');
            session()->flash('message', 'Erro ao editar serviço!');
        }
        
        return redirect('/servicos'); 
    }

    public function delete($id){
        try{
            $delete = Servico
            ::where('id', $id)
            ->delete();
            if($delete){
                session()->flash('color', 'blue');
                session()->flash('message', 'Serviço removido!');
            }else{
                session()->flash('color', 'red');
                session()->flash('message', 'Erro!');
            }
            return redirect('/servicos');
        }catch(\Exception $e){
            return view('errors.sql')
            ->with('title', 'Erro ao deletar serviços')
            ->with('motivo', 'Não é possivel remover serviços, incluidos em OS!');
        }
    }

    private function _validate(Request $request){
        $rules = [
            'valor' => 'required',
            'nome' => 'required|max:60'
        ];

        $messages = [
            'valor.required' => 'O campo valor é obrigatório.',
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.max' => '60 caracteres maximos permitidos.'
        ];
        $this->validate($request, $rules, $messages);
    }

    public function all(){
        $services = Servico::all();
        $arr = array();
        foreach($services as $s){
            $arr[$s->id. ' - ' .$s->nome] = null;
                //array_push($arr, $temp);
        }
        echo json_encode($arr);
    }

    public function getValue(Request $request){
        $id = $request->input('id');
        $service = Servico::
        where('id', $id)
        ->first();
        echo json_encode($service->value);
    }
}
