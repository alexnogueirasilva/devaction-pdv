<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ApplianceNoutFound;

class ApplianceNotFounController extends Controller
{

	public function index(){
		$registers = ApplianceNoutFound
		::orderBy('id', 'desc')
		->get();
    	return view('notFound/list')
    	->with('registers', $registers)
    	->with('title', 'Registro de Celular não encontrdos');
    }

    public function save(Request $request){
    	$this->_validate($request);

    	$result = ApplianceNoutFound::create($request->all());

    	if($result){
            session()->flash('color', 'blue');
            session()->flash("message", "Dados salvos, obrigado por nos ajudar!.");
        }else{
            session()->flash('color', 'red');
            session()->flash('message', 'Erro ao salvar dados.');
        }

    	return redirect("naoencontrei");
    }

    public function delete($id){
    	$delete = ApplianceNoutFound
    	::where('id', $id)
    	->delete();

    	if($delete){
            session()->flash('color', 'blue');
            session()->flash('message', 'Registro removido!');
        }else{
            session()->flash('color', 'red');
            session()->flash('message', 'Erro!');
        }
        return redirect('/semRegistro');

    }

    private function _validate(Request $request){
        $rules = [
            'name' => 'required|max:50',
            'phone' => 'required|max:20',
            'brand' => 'required|max:30',
            'model' => 'required|max:30',
        ];

        $messages = [
            'name.required' => 'O campo nome é obrigatório.',
            'name.max' => '50 caracteres maximos permitidos.',
            'phone.required' => 'O campo Celular é obrigatório.',
            'phone.max' => '20 caracteres maximos permitidos.',

            'brand.required' => 'O campo nome é obrigatório.',
            'brand.max' => '30 caracteres maximos permitidos.',
            'model.required' => 'O campo Celular é obrigatório.',
            'model.max' => '30 caracteres maximos permitidos.',

        ];
        $this->validate($request, $rules, $messages);
    }
}
