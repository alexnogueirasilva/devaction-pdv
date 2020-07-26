<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Usuario;
use App\ConfigNota;
class UserController extends Controller
{

    public function newAccess(){
    	return view('login/access_2');
    }

    public function request(Request $request){
    	$login = $request->input('login');
    	$senha = $request->input('senha');

    	$user = new Usuario();

    	$usr = $user
    	->where('login', $login)
    	->where('senha', md5($senha))
    	->first();

    	if($usr != null){
    		$config = ConfigNota::first();
            $ambiente = 'Não configurado';
            if($config != null){
                $ambiente = $config->ambiente == 1 ? 'Produção' : 'Homologação'; 
            }
            
    		$session = [
    			'id' => $usr->id,
    			'nome' => $usr->nome,
    			'adm' => $usr->adm,
                'ambiente' => $ambiente,
                'delivery' => getenv("DELIVERY") == 1 || getenv("DELIVERY_MERCADO") == 1 ? true : false,
                'acesso_cliente' => $usr->acesso_cliente,
                'acesso_fornecedor' => $usr->acesso_fornecedor,
                'acesso_produto' => $usr->acesso_produto,
                'acesso_financeiro' => $usr->acesso_financeiro,
                'acesso_caixa' => $usr->acesso_caixa,
                'acesso_estoque' => $usr->acesso_estoque,
                'acesso_compra' => $usr->acesso_compra,
                'acesso_fiscal' => $usr->acesso_fiscal,
    		];
    		session(['user_logged' => $session]);
            return redirect('/' . getenv('ROTA_INICIAL'));
    	}else{
    		session()->flash('color', 'red');
            session()->flash('message', 'Credencial incorreta!');
    		return redirect('/login');
    	}
    }

    public function logoff(){
    	session()->forget('user_logged');

        session()->flash('color', 'green');
        session()->flash('message_logoff', 'Logoff realizado.');
        return redirect("/login");
    }

}
