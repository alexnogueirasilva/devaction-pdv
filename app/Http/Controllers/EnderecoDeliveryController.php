<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ClienteDelivery;
use App\EnderecoDelivery;

class EnderecoDeliveryController extends Controller
{

    public function save(Request $request){
    	$data = $request['data'];
    	$result = EnderecoDelivery::create([
    		'cliente_id' => $data['cliente_id'],
    		'rua' => $data['rua'],
    		'numero' => $data['numero'],
    		'bairro' => $data['bairro'],
            'referencia' => $data['referencia'],
            'latitude' => $data['latitude'] ? substr($data['latitude'], 0, 10) : '',
    		'longitude' => $data['longitude'] ? substr($data['longitude'], 0, 10) : ''
    	]);
        if($result) echo json_encode($result);
        else echo json_encode(false);
    }
}
