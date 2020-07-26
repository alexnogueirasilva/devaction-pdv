<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Funcionario;
use App\ContatoFuncionario;
class FuncionarioController extends Controller
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
        $funcionarios = Funcionario::all();
        return view('funcionarios/list')
        ->with('funcionarios', $funcionarios)
        ->with('title', 'Funcionarios');
    }

    public function new(){
        return view('funcionarios/register')
        ->with('title', 'Cadastrar Funcionario');
    }

    private function parseDate($date){
    	return date('Y-m-d', strtotime(str_replace("/", "-", $date)));
    }

    public function save(Request $request){
        $funcionario = new Funcionario();
        $this->_validate($request);

        $dataRegsitro = $this->parseDate($request->input('data_registro'));
        $request->merge([ 'data_registro' => $dataRegsitro]);


        $result = $funcionario->create($request->all());

        if($result){
            session()->flash('color', 'blue');
            session()->flash("message", "Funcionario cadastrado com sucesso!");
        }else{
            session()->flash('color', 'red');
            session()->flash('message', 'Erro ao cadastrar funcionario!');
        }
        
        return redirect('/funcionarios');
    }

    public function edit($id){
        $funcionario = new Funcionario(); //Model
        
        $resp = $funcionario
        ->where('id', $id)->first();  

        return view('funcionarios/register')
        ->with('pessoaFisicaOuJuridica', true)
        ->with('funcionario', $resp)
        ->with('title', 'Editar Funcionario');

    }

    public function update(Request $request){
        $funcionario = new Funcionario();

        $id = $request->input('id');
        $resp = $funcionario
        ->where('id', $id)->first(); 

        $request->merge([ 'data_registro' => '01/01/2000']);

        $this->_validate($request);
        

        $resp->nome = $request->input('nome');
        $resp->cpf = $request->input('cpf');

        $resp->rua = $request->input('rua');
        $resp->numero = $request->input('numero');
        $resp->bairro = $request->input('bairro');

        $resp->telefone = $request->input('telefone');
        $resp->celular = $request->input('celular');
        $resp->email = $request->input('email');

        $result = $resp->save();
        if($result){
            session()->flash('color', 'green');
            session()->flash('message', 'Funcionario editado com sucesso!');
        }else{
            session()->flash('color', 'red');
            session()->flash('message', 'Erro ao editar funcionario!');
        }
        
        return redirect('/fornecedores'); 
    }

    public function delete($id){
        $delete = Funcionario
        ::where('id', $id)
        ->delete();
        if($delete){
            session()->flash('color', 'blue');
            session()->flash('message', 'Registro removido!');
        }else{
            session()->flash('color', 'red');
            session()->flash('message', 'Erro!');
        }
        return redirect('/funcionarios');
    }


    private function _validate(Request $request){
        $rules = [
            'nome' => 'required|max:50',
            'cpf' => 'required',
            'rua' => 'required|max:80',
            'numero' => 'required|max:10',
            'bairro' => 'required|max:50',
            'telefone' => 'required|max:20',
            'celular' => 'required|max:20',
            'email' => 'required|email|max:40',
            'rg' => 'required',
            'data_registro' => 'required',
        ];

        $messages = [
            'nome.required' => 'O campo Nome é obrigatório.',
            'data_registro.required' => 'O campo data de registro é obrigatório.',
            'nome.max' => '50 caracteres maximos permitidos.',
            'cpf.required' => 'O campo CPF é obrigatório.',
            'rua.required' => 'O campo Rua é obrigatório.',
            'rg.required' => 'O campo IE/RG é obrigatório.',
            'rua.max' => '80 caracteres maximos permitidos.',
            'numero.required' => 'O campo Numero é obrigatório.',
            'numero.max' => '10 caracteres maximos permitidos.',
            'bairro.required' => 'O campo Bairro é obrigatório.',
            'bairro.max' => '50 caracteres maximos permitidos.',
            'telefone.required' => 'O campo Celular é obrigatório.',
            'telefone.max' => '20 caracteres maximos permitidos.',
            'celular.required' => 'O campo Celular 2 é obrigatório.',
            'celular.max' => '20 caracteres maximos permitidos.',

            'email.required' => 'O campo Email é obrigatório.',
            'email.max' => '40 caracteres maximos permitidos.',
            'email.email' => 'Email inválido.',


        ];
        $this->validate($request, $rules, $messages);
    }

    private function _validateContato(Request $request){
        $rules = [
            'nome' => 'required|max:40',
            'telefone' => 'required|max:20',
        ];

        $messages = [
            'nome.required' => 'O campo nome é obrigatório.',
            'nome.max' => '40 caracteres maximos permitidos.',
            
            'telefone.required' => 'O campo Celular é obrigatório.',
            'telefone.max' => '20 caracteres maximos permitidos.',

        ];
        $this->validate($request, $rules, $messages);
    }

    public function contatos($id, $edit = false){
        $funcionario = Funcionario::
        where('id', $id)
        ->first();

        return view('funcionarios/contatos')
        ->with('funcionario', $funcionario)
        ->with('edit', $edit)
        ->with('title', 'Contato Funcionario');
    }

    public function editContato($id){
        $contato = ContatoFuncionario::
        where('id', $id)
        ->first();

        $funcionario = $contato->funcionario;

        return view('funcionarios/contatos')
        ->with('funcionario', $funcionario)
        ->with('contato', $contato)
        ->with('title', 'Contato Funcionario');
    }

    public function deleteContato($id){
        $funcionario = ContatoFuncionario::
        where('id', $id)
        ->first();

        $delete = $funcionario->delete();

        if($delete){
            session()->flash('color', 'blue');
            session()->flash('message', 'Registro removido!');
        }else{
            session()->flash('color', 'red');
            session()->flash('message', 'Erro!');
        }

        return redirect("/funcionarios/contatos/$funcionario->id");
    }

    public function saveContato(Request $request){
        $this->_validateContato($request);

        $result = null;
        if($request->id > 0){
            $contato = ContatoFuncionario::
            where('id', $request->id)
            ->first();

            $contato->nome = $request->nome;
            $contato->telefone = $request->telefone;

            $result = $contato->save();
        }else{
            $result = ContatoFuncionario::create($request->all());
        }
        if($result){
            session()->flash('color', 'blue');
            session()->flash("message", "Contato cadastrado/editado com sucesso!");
        }else{
            session()->flash('color', 'red');
            session()->flash('message', 'Erro ao cadastrar contato!');
        }
        
        return redirect("/funcionarios/contatos/$request->funcionario_id");
    }

    public function all(){
        $funcionarios = Funcionario::all();
        $arr = array();
        foreach($funcionarios as $c){
            $arr[$c->id. ' - ' .$c->nome] = null;
                //array_push($arr, $temp);
        }
        echo json_encode($arr);
    }
}
