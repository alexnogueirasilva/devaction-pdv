<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ComplementoDelivery;

class DeliveryComplementoController extends Controller
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
		$complementos = ComplementoDelivery::all();
		return view('complementoDelivery/list')
		->with('complementos', $complementos)
		->with('title', 'Adicional de produto para Delivery');
	}

	public function new(){

		return view('complementoDelivery/register')
		->with('title', 'Cadastrar Adicional para Delivery');
	}

	public function save(Request $request){
		
		$complemento = new ComplementoDelivery();

		$this->_validate($request);

		$request->merge([ 'valor' => str_replace(",", ".", $request->valor) ]);

		$result = $complemento->create($request->all());
		if($result){
			session()->flash('color', 'green');
			session()->flash("message", "Adicional cadastrado com sucesso.");
		}else{
			session()->flash('color', 'red');
			session()->flash('message', 'Erro ao cadastrar adicional.');
		}
		
		
		return redirect('/deliveryComplemento');
	}

	public function edit($id){
        $complemento = new ComplementoDelivery(); //Model

        $resp = $complemento
        ->where('id', $id)->first();  

        return view('complementoDelivery/register')
        ->with('complemento', $resp)
        ->with('title', 'Editar Complemento para Delivery');

    }

    public function update(Request $request){
    	$complemento = new ComplementoDelivery();

    	$id = $request->input('id');
    	$resp = $complemento
    	->where('id', $id)->first(); 

    	
    	$this->_validate($request, false);

    	$resp->nome = $request->input('nome');
    	$resp->valor = str_replace(",", ".", $request->input('valor'));

    	$result = $resp->save();
    	if($result){
    		session()->flash('color', 'green');
    		session()->flash('message', 'Adicional editado com sucesso!');
    	}else{
    		session()->flash('color', 'red');
    		session()->flash('message', 'Erro ao editar adicional!');
    	}

    	return redirect('/deliveryComplemento'); 
    }

    public function delete($id){
    	$complemento = ComplementoDelivery
    	::where('id', $id)
    	->first();

    	if($complemento->delete()){
    		session()->flash('color', 'blue');
    		session()->flash('message', 'Registro removido!');
    	}else{
    		session()->flash('color', 'red');
    		session()->flash('message', 'Erro!');
    	}
    	return redirect('/deliveryComplemento');
    }


    private function _validate(Request $request, $fileExist = true){
    	$rules = [
    		'nome' => 'required|max:50',
    		'valor' => 'required'
    	];

    	$messages = [
    		'nome.required' => 'O campo nome é obrigatório.',
    		'nome.max' => '50 caracteres maximos permitidos.',
    		'valor.required' => 'O campo valor é obrigatório.'
    	];
    	$this->validate($request, $rules, $messages);
    }

    public function all(){
        $complementos = ComplementoDelivery::all();
        $arr = array();
        foreach($complementos as $c){
            $arr[$c->id. ' - ' .$c->nome] = null;
                //array_push($arr, $temp);
        }
        echo json_encode($arr);
    }

    public function allPedidoLocal(){
        $complementos = ComplementoDelivery::all();
        
        echo json_encode($complementos);
    }

}
