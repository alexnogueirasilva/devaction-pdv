<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VendaCaixa;
use App\Venda;
use NFePHP\DA\NFe\Danfe;
use NFePHP\DA\NFe\Danfce;
use NFePHP\DA\NFe\Cupom;
use NFePHP\DA\Legacy\FilesFolders;
use App\ConfigNota;
use App\Helpers\StockMove;
use App\Services\NFCeService;
class NFCeController extends Controller
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
	
	public function gerar(Request $request){

		$vendaId = $request->vendaId;
		$venda = VendaCaixa::
		where('id', $vendaId)
		->first();

		$config = ConfigNota::first();

		$cnpj = str_replace(".", "", $config->cnpj);
		$cnpj = str_replace("/", "", $cnpj);
		$cnpj = str_replace("-", "", $cnpj);
		$cnpj = str_replace(" ", "", $cnpj);

		$nfe_service = new NFCeService([
			"atualizacao" => date('Y-m-d h:i:s'),
			"tpAmb" => (int)$config->ambiente,
			"razaosocial" => $config->razao_social,
			"siglaUF" => $config->UF,
			"cnpj" => $cnpj,
			"schemes" => "PL_009_V4",
			"versao" => "4.00",
			"tokenIBPT" => "AAAAAAA",
			"CSC" => $config->csc,
			"CSCid" => $config->csc_id
		]);

		if($venda->estado == 'REJEITADO' || $venda->estado == 'DISPONIVEL'){
			header('Content-type: text/html; charset=UTF-8');

			$nfce = $nfe_service->gerarNFCe($vendaId);
			if(!isset($nfce['erros_xml'])){
				$public = getenv('SERVIDOR_WEB') ? 'public/' : '';
				$signed = $nfe_service->sign($nfce['xml']);
			// file_put_contents($public.'xml_nfce/'.$venda->id.'.xml',$signed);
				$resultado = $nfe_service->transmitirNfce($signed, $nfce['chave']);

				if(substr($resultado, 0, 4) != 'Erro'){
					$venda->chave = $nfce['chave'];
					$venda->path_xml = $nfce['chave'] . '.xml';
					$venda->estado = 'APROVADO';

					$venda->NFcNumero = $nfce['nNf'];
					$venda->save();
				}else{
					$venda->estado = 'REJEITADO';
					$venda->save();
				}
				echo json_encode($resultado);
			}else{
				return response()->json($nfce['erros_xml'], 401);
			}

		}else{
			echo json_encode("Apro");
		}

	}


	public function imprimir($id){
		$venda = VendaCaixa::
		where('id', $id)
		->first();
		$public = getenv('SERVIDOR_WEB') ? 'public/' : '';
		$xml = file_get_contents($public.'xml_nfce/'.$venda->chave.'.xml');
		$logo = 'data://text/plain;base64,'. base64_encode(file_get_contents($public.'imgs/logo.jpg'));
		try {

			$danfce = new Danfce($xml);
			$danfce->monta($logo);
			$pdf = $danfce->render();

			// header('Content-Type: application/pdf');
			// echo $pdf;
			return response($pdf)
			->header('Content-Type', 'application/pdf');

		} catch (\Exception $e) {
			echo $e->getMessage();
		}
	}

	public function baixarXml($id){
		$venda = VendaCaixa::
		where('id', $id)
		->first();
		try {

			$public = getenv('SERVIDOR_WEB') ? 'public/' : '';

			return response()->download($public.'xml_nfce/'.$venda->chave.'.xml');
		} catch (\Exception $e) {
			echo $e->getMessage();
		}
	}

	public function imprimirNaoFiscal($id){
		$venda = VendaCaixa::
		where('id', $id)
		->first();
		$public = getenv('SERVIDOR_WEB') ? 'public/' : '';
		$pathLogo = $public.'imgs/logo.jpg';

		$cupom = new Cupom($venda, $pathLogo);
		$cupom->monta();
		$pdf = $cupom->render();

		// header('Content-Type: application/pdf');
		// echo $pdf;
		return response($pdf)
		->header('Content-Type', 'application/pdf');
	}

	public function imprimirNaoFiscalCredito($id){
		$venda = Venda::
		where('id', $id)
		->first();
		$public = getenv('SERVIDOR_WEB') ? 'public/' : '';
		$pathLogo = $public.'imgs/logo.jpg';

		$cupom = new Cupom($venda, $pathLogo);
		$cupom->monta();
		$pdf = $cupom->render();

		// header('Content-Type: application/pdf');
		// echo $pdf;
		return response($pdf)
		->header('Content-Type', 'application/pdf');
	}

	public function cancelar(Request $request){

		$config = ConfigNota::first();

		$cnpj = str_replace(".", "", $config->cnpj);
		$cnpj = str_replace("/", "", $cnpj);
		$cnpj = str_replace("-", "", $cnpj);
		$cnpj = str_replace(" ", "", $cnpj);
		$nfe_service = new NFCeService([
			"atualizacao" => date('Y-m-d h:i:s'),
			"tpAmb" => (int)$config->ambiente,
			"razaosocial" => $config->razao_social,
			"siglaUF" => $config->UF,
			"cnpj" => $cnpj,
			"schemes" => "PL_009_V4",
			"versao" => "4.00",
			"tokenIBPT" => "AAAAAAA",
			"CSC" => $config->csc,
			"CSCid" => $config->csc_id
		]);


		$nfce = $nfe_service->cancelarNFCe($request->id, $request->justificativa);
		
		if(!isset($nfce['cStat'])){
			return response()->json($nfce, 404);
		}
		if($nfce['retEvento']['infEvento']['cStat'] == 135){
			$venda = VendaCaixa::
			where('id', $request->id)
			->first();
			$venda->estado = 'CANCELADO';
			$venda->save();
			// if($venda){
			// 	$stockMove = new StockMove();

			// 	foreach($venda->itens as $i){
			// 		$stockMove->pluStock($i->produto_id, 
			// 			$i->quantidade, -50); // -50 na altera valor compra
			// 	}
			// }
			return response()->json($nfce, 200);

		}else{
			return response()->json($nfce, 401);
		}
		
		
	}

	public function deleteVenda($id){
		$result = VendaCaixa::where('id', $id)
		->delete();
		echo json_encode($result);
	}

	public function consultar($id){
		$venda = VendaCaixa::find($id);

		$config = ConfigNota::first();

		$cnpj = str_replace(".", "", $config->cnpj);
		$cnpj = str_replace("/", "", $cnpj);
		$cnpj = str_replace("-", "", $cnpj);
		$cnpj = str_replace(" ", "", $cnpj);
		try{
			$nfe_service = new NFCeService([
				"atualizacao" => date('Y-m-d h:i:s'),
				"tpAmb" => (int)$config->ambiente,
				"razaosocial" => $config->razao_social,
				"siglaUF" => $config->UF,
				"cnpj" => $cnpj,
				"schemes" => "PL_009_V4",
				"versao" => "4.00",
				"tokenIBPT" => "AAAAAAA",
				"CSC" => $config->csc,
				"CSCid" => $config->csc_id
			]);

			$c = $nfe_service->consultarNFCe($venda);

			return response()->json($c, 200);
		}catch(\Exception $r){
			return response()->json($e->getMessage(), 401);

		}
	}
}
