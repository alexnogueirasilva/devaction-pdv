<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CTeService;
use App\ConfigNota;
use App\Cte;
use App\Cidade;
use App\Cliente;

use App\MedidaCte;
use App\ComponenteCte;
use App\Veiculo;

use App\CategoriaDespesaCte;
use App\NaturezaOperacao;
use App\DespesaCte;
use App\ReceitaCte;

class CTeController extends Controller
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
		$ctes = Cte::
		where('estado', 'DISPONIVEL')
		->paginate(10);

		$menos30 = $this->menos30Dias();
		$date = date('d/m/Y');

		$grupos = Cte::gruposCte();
		
		return view("cte/list")
		->with('ctes', $ctes)
		->with('cteEnvioJs', true)
		->with('links', true)
		->with('dataInicial', $menos30)
		->with('grupos', $grupos)
		->with('dataFinal', $date)
		->with('title', "Lista de CT-e");
		
	}

	public function filtro(Request $request){

		$dataInicial = $request->data_inicial;
		$dataFinal = $request->data_final;
		$cliente = $request->cliente;
		$estado = $request->estado;
		$ctes = null;

		$grupos = Cte::gruposCte();

		if(isset($cliente) && isset($dataInicial) && isset($dataFinal)){
			$ctes = Cte::filtroDataCliente(
				$cliente, 
				$this->parseDate($dataInicial),
				$this->parseDate($dataFinal, true),
				$estado
			);
		}else if(isset($dataInicial) && isset($dataFinal)){
			$ctes = Cte::filtroData(
				$this->parseDate($dataInicial),
				$this->parseDate($dataFinal, true),
				$estado
			);
		}else if(isset($cliente)){
			$ctes = Cte::filtroCliente(
				$cliente,
				$estado
			);

		}else{
			$ctes = Cte::filtroEstado(
				$estado
			);
		}

		return view("cte/list")
		->with('ctes', $ctes)
		->with('cteEnvioJs', true)
		->with('grupos', $grupos)
		->with('cliente', $cliente)
		->with('dataInicial', $dataInicial)
		->with('dataFinal', $dataFinal)
		->with('estado', $estado)

		->with('title', "Filtro de Cte");
	}

	public function nova(){
		$lastCte = Cte::lastCTe();
		$unidadesMedida = Cte::unidadesMedida();
		$tiposMedida = Cte::tiposMedida();
		$tiposTomador = Cte::tiposTomador();
		$naturezas = NaturezaOperacao::all();
		$modals = Cte::modals();
		$veiculos = Veiculo::all();
		$config = ConfigNota::first();
		$clienteCadastrado = Cliente::first();

		if(count($naturezas) == 0 || count($veiculos) == 0 || $config == null || $clienteCadastrado == null){
			return view("cte/erro")
			->with('veiculos', $veiculos)
			->with('naturezas', $naturezas)
			->with('config', $config)
			->with('clienteCadastrado', $clienteCadastrado)
			->with('title', "Validação para Emitir");

		}else{
			return view("cte/register")
			->with('naturezas', $naturezas)
			->with('cteJs', true)
			->with('unidadesMedida', $unidadesMedida)
			->with('tiposMedida', $tiposMedida)
			->with('tiposTomador', $tiposTomador)
			->with('modals', $modals)
			->with('veiculos', $veiculos)
			->with('config', $config)
			->with('lastCte', $lastCte)
			->with('title', "Nova CT-e");
		}
	}

	public function salvar(Request $request){
		$cte = $request->data;

		$municipio_envio = (int) explode("-", $cte['municipio_envio'])[0];
		$municipio_fim = (int) explode("-", $cte['municipio_fim'])[0];
		$municipio_inicio = (int) explode("-", $cte['municipio_inicio'])[0];
		$municipio_tomador = (int) explode("-", $cte['municipio_tomador'])[0];


		$result = Cte::create([
			'chave_nfe' => $cte['chave_nfe'] ?? '',
			'remetente_id' => $cte['remetente'],
			'destinatario_id' => $cte['destinatario'],
			'usuario_id' => get_id_user(),
			'natureza_id' => $cte['natureza'],
			'tomador' => $cte['tomador'],
			'municipio_envio' => $municipio_envio,
			'municipio_inicio' => $municipio_fim,
			'municipio_fim' => $municipio_inicio,
			'logradouro_tomador' => $cte['logradouro_tomador'],
			'numero_tomador' => $cte['numero_tomador'],
			'bairro_tomador' => $cte['bairro_tomador'],
			'cep_tomador' => $cte['cep_tomador'],
			'municipio_fim' => $municipio_fim,
			'municipio_tomador' => $municipio_tomador,
			'observacao' => $cte['obs'] ?? '',
			'data_previsata_entrega' => $this->parseDate($cte['data_prevista_entrega']),
			'produto_predominante' => $cte['produto_predominante'],
			'cte_numero' => 0,
			'sequencia_cce' => 0,
			'chave' => '',
			'path_xml' => '',
			'estado' => 'DISPONIVEL',

			'valor_transporte' => str_replace(",", ".", $cte['valor_transporte']),
			'valor_receber' => str_replace(",", ".", $cte['valor_receber']),
			'valor_carga' => str_replace(",", ".", $cte['valor_carga']),

			'retira' => $cte['retira'],
			'detalhes_retira' => $cte['detalhes_retira'] ?? '',
			'modal' => $cte['modal'],
			'veiculo_id' => $cte['veiculo_id'],
			'tpDoc' => $cte['tpDoc'] ?? '',
			'descOutros' => $cte['descOutros'] ?? '',
			'nDoc' => $cte['nDoc'] ?? 0,
			'vDocFisc' => $cte['vDocFisc'] ?? 0

		]);

		if(isset($cte['medidias'])){
			foreach($cte['medidias'] as $c){
				$medida = MedidaCte::create([
					'cod_unidade' => explode("-", $c['unidade_medida'])[0],
					'tipo_medida'=> $c['tipo_medida'],
					'quantidade_carga' => str_replace(",", ".", $c['quantidade']),
					'cte_id' => $result->id
				]);
			}
		}

		if(isset($cte['componentes'])){
			foreach($cte['componentes'] as $c){
				$medida = ComponenteCte::create([
					'nome' => $c['nome'],
					'valor' => str_replace(",", ".", $c['valor']),
					'cte_id' => $result->id
				]);
			}
		}
		echo json_encode($result);
	}

	public function detalhar($id){
		$cte = Cte::
		where('id', $id)
		->first();

		return view("cte/detalhe")
		->with('cte', $cte)
		->with('title', "Detalhe de Cte $id");
	}

	public function custos($id){
		$categorias = CategoriaDespesaCte::all();
		$cte = Cte::
		where('id', $id)
		->first();

		return view("cte/custos")
		->with('cte', $cte)
		->with('categorias', $categorias)
		->with('title', "Custos Cte $id");
	}

	public function saveReceita(Request $request){
		$result = ReceitaCte::create([
			'descricao' => $request->descricao,		
			'valor' => str_replace(",", ".", $request->valor),
			'cte_id' => $request->cte_id		
		]);

		if($result){
			session()->flash('color', 'green');
			session()->flash('message', 'Receita adicionada!');
		}else{
			session()->flash('color', 'red');
			session()->flash('message', 'Erro!');
		}
		return redirect('cte/custos/'.$request->cte_id);
	}

	public function saveDespesa(Request $request){
		$result = DespesaCte::create([
			'descricao' => $request->descricao,		
			'categoria_id' => $request->categoria_id,		
			'valor' => str_replace(",", ".", $request->valor),
			'cte_id' => $request->cte_id	
		]);

		if($result){
			session()->flash('color', 'green');
			session()->flash('message', 'Despesa adicionada!');
		}else{
			session()->flash('color', 'red');
			session()->flash('message', 'Erro!');
		}
		return redirect('cte/custos/'.$request->cte_id);
	}

	public function chaveNfeDuplicada(Request $request){
		$res = Cte::
		where('chave_nfe', $request->chave)
		->first();
		if($res != null){
			echo true;
		}else{
			echo false;
		}
	}

	public function delete($id){
		$despesa = Cte::
		where('id', $id)
		->first();

		if($despesa->delete()){
			session()->flash('color', 'green');
			session()->flash('message', 'CT-e removida!');
		}else{
			session()->flash('color', 'red');
			session()->flash('message', 'Erro!');
		}
		return redirect('cte');
	}

	public function deleteDespesa($id){
		$despesa = DespesaCte::
		where('id', $id)
		->first();

		if($despesa->delete()){
			session()->flash('color', 'green');
			session()->flash('message', 'Despesa removida!');
		}else{
			session()->flash('color', 'red');
			session()->flash('message', 'Erro!');
		}
		return redirect('cte/custos/'.$despesa->cte->id);
	}

	public function deleteReceita($id){
		$receita = ReceitaCte::
		where('id', $id)
		->first();

		if($receita->delete()){
			session()->flash('color', 'green');
			session()->flash('message', 'Receita removida!');
		}else{
			session()->flash('color', 'red');
			session()->flash('message', 'Erro!');
		}
		return redirect('cte/custos/'.$receita->cte->id);
	}

	private function menos30Dias(){
		return date('d/m/Y', strtotime("-30 days",strtotime(str_replace("/", "-", 
			date('Y-m-d')))));
	}

	private function parseDate($date, $plusDay = false){
		if($plusDay == false)
			return date('Y-m-d', strtotime(str_replace("/", "-", $date)));
		else
			return date('Y-m-d', strtotime("+1 day",strtotime(str_replace("/", "-", $date))));
	}

	public function importarXml(Request $request){

		if ($request->hasFile('file')){
			$arquivo = $request->hasFile('file');
			$xml = simplexml_load_file($request->file);




			$cidade = Cidade::getCidadeCod($xml->NFe->infNFe->emit->enderEmit->cMun);
			$dadosEmitente = [
				'cpf' => $xml->NFe->infNFe->emit->CPF,
				'cnpj' => $xml->NFe->infNFe->emit->CNPJ,  				
				'razaoSocial' => $xml->NFe->infNFe->emit->xNome, 				
				'nomeFantasia' => $xml->NFe->infNFe->emit->xFant,
				'logradouro' => $xml->NFe->infNFe->emit->enderEmit->xLgr,
				'numero' => $xml->NFe->infNFe->emit->enderEmit->nro,
				'bairro' => $xml->NFe->infNFe->emit->enderEmit->xBairro,
				'cep' => $xml->NFe->infNFe->emit->enderEmit->CEP,
				'fone' => $xml->NFe->infNFe->emit->enderEmit->fone,
				'ie' => $xml->NFe->infNFe->emit->IE,
				'cidade_id' => $cidade->id
			];

			$emitente = $this->verificaClienteCadastrado($dadosEmitente);

			$cidade = Cidade::getCidadeCod($xml->NFe->infNFe->dest->enderDest->cMun);
			$dadosDestinatario = [
				'cpf' => $xml->NFe->infNFe->dest->CPF,
				'cnpj' => $xml->NFe->infNFe->dest->CNPJ,  				
				'razaoSocial' => $xml->NFe->infNFe->dest->xNome, 				
				'nomeFantasia' => $xml->NFe->infNFe->dest->xFant,
				'logradouro' => $xml->NFe->infNFe->dest->enderDest->xLgr,
				'numero' => $xml->NFe->infNFe->dest->enderDest->nro,
				'bairro' => $xml->NFe->infNFe->dest->enderDest->xBairro,
				'cep' => $xml->NFe->infNFe->dest->enderDest->CEP,
				'fone' => $xml->NFe->infNFe->dest->enderDest->fone,
				'ie' => $xml->NFe->infNFe->dest->IE,
				'cidade_id' => $cidade->id
			];

			$destinatario = $this->verificaClienteCadastrado($dadosDestinatario);

			$chave = substr($xml->NFe->infNFe->attributes()->Id, 3, 44);

			$somaQuantidade = 0;
			foreach($xml->NFe->infNFe->det as $item) {
				$somaQuantidade += $item->prod->qCom;
			}

			$unidade = $xml->NFe->infNFe->det[0]->prod->uCom;
			if($unidade == 'M2'){
				$unidade = '04';
			}else if($unidade == 'M3'){
				$unidade = '00';
			}else if($unidade == 'KG'){
				$unidade = '01';
			}else if($unidade == 'UNID'){
				$unidade = '03';
			}else if($unidade == 'TON'){
				$unidade = '02';
			}


			$dadosDaNFe = [
				'remetente' => $emitente->id . " - " . $emitente->razao_social,
				'destinatario' => $destinatario->id . " - " . $destinatario->razao_social,
				'chave' => $chave,
				'produto_predominante' => $xml->NFe->infNFe->det[0]->prod->xProd,
				'unidade' => $unidade,
				'valor_carga' => $xml->NFe->infNFe->total->ICMSTot->vProd,
				'munipio_envio' => $emitente->cidade->id . " - " . $emitente->cidade->nome . "(" .$emitente->cidade->uf . ")",
				'munipio_final' => $destinatario->cidade->id . " - " . $destinatario->cidade->nome . "(" .$destinatario->cidade->uf . ")",
				'componente' => 'Transporte',
				'valor_frete' => $xml->NFe->infNFe->total->ICMSTot->vFrete,
				'quantidade' => number_format($somaQuantidade, 4),
				'data_entrega' => date('d/m/y')
			];

			// echo "<pre>";
			// print_r($dadosDaNFe);
			// echo "</pre>";

			$lastCte = Cte::lastCTe();
			$unidadesMedida = Cte::unidadesMedida();
			$tiposMedida = Cte::tiposMedida();
			$tiposTomador = Cte::tiposTomador();
			$naturezas = NaturezaOperacao::all();
			$modals = Cte::modals();
			$veiculos = Veiculo::all();
			$config = ConfigNota::first();
			$clienteCadastrado = Cliente::first();


			return view("cte/register_xml")
			->with('naturezas', $naturezas)
			->with('cteJs', true)
			->with('unidadesMedida', $unidadesMedida)
			->with('tiposMedida', $tiposMedida)
			->with('tiposTomador', $tiposTomador)
			->with('modals', $modals)
			->with('veiculos', $veiculos)
			->with('config', $config)
			->with('lastCte', $lastCte)
			->with('dadosDaNFe', $dadosDaNFe)
			->with('emitente', $emitente)
			->with('destinatario', $destinatario)
			->with('title', "Nova CT-e");

		}

	}

	private function verificaClienteCadastrado($cliente){

		if($cliente['cnpj'] != ''){
			$cli = Cliente::where('cpf_cnpj', $this->formataCnpj($cliente['cnpj']))->first();
		}else{
			$cli = Cliente::where('cpf_cnpj', $cliente['cpf'])->first();
		}
		if($cli == null){
			$result = Cliente::create(
				[
					'razao_social' => $cliente['razaoSocial'], 
					'nome_fantasia' => $cliente['nomeFantasia'] != '' ? $cliente['nomeFantasia'] : $cliente['razaoSocial'],
					'bairro' => $cliente['bairro'],
					'numero' => $cliente['numero'],
					'rua' => $cliente['logradouro'],
					'cpf_cnpj' => $cliente['cnpj'] ? $this->formataCnpj($cliente['cnpj']) : $cliente['cpf'],
					'telefone' => $cliente['razaoSocial'],
					'celular' => '',
					'email' => 'teste@teste.com',
					'cep' => $cliente['cep'],
					'ie_rg' => $cliente['ie'],
					'consumidor_final' => 0,
					'limite_venda' => 0,
					'cidade_id' => $cliente['cidade_id'],
					'contribuinte' => 1,
					'rua_cobranca' => '',
					'numero_cobranca' => '',
					'bairro_cobranca' => '',
					'cep_cobranca' => '',
					'cidade_cobranca_id' => NULL
				]
			);
			$cliente = Cliente::find($result->id);
			return $cliente;
		}
		return $cli;
	}

	private function formataCnpj($cnpj){
		$temp = substr($cnpj, 0, 2);
		$temp .= ".".substr($cnpj, 2, 3);
		$temp .= ".".substr($cnpj, 5, 3);
		$temp .= "/".substr($cnpj, 8, 4);
		$temp .= "-".substr($cnpj, 12, 2);
		return $temp;
	}


}
