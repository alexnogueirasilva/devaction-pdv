@extends('default.layout')
@section('content')

<div class="row" id="anime" style="display: none">
	<div class="col s8 offset-s2">
		<lottie-player 
		src="/anime/success.json"  background="transparent"  speed="0.8"  style="width: 100%; height: 300px;"    autoplay >
	</lottie-player>
</div>
</div>

<div class="row" id="content" style="display: block">
	<div class="col s12">
		<h1 class="center-align">Importando XML</h1>
		<h4 class="center-align">Nota Fiscal <strong class="grey-text">{{$dadosNf['nNf']}}</strong></h4>
		<h4 class="center-align">Chave <strong class="grey-text">{{$dadosNf['chave']}}</strong></h4>

		@if(count($dadosAtualizados) > 0)
		<div class="row">
			<div class="col s12">
				<h5 class="cyan-text">Dados Atualizados do fornecedor</h5>
				@foreach($dadosAtualizados as $d)
				<p class="red-text">{{$d}}</p>
				@endforeach
			</div>
		</div>
		@endif

		<div class="card">
			<div class="card-content">
				<div class="row">
					<div class="col s8">
						<h5>Fornecedor: <strong>{{$dadosEmitente['razaoSocial']}}</strong></h5>
						<h5>Nome Fantasia: <strong>{{$dadosEmitente['nomeFantasia']}}</strong></h5>
					</div>
					<div class="col s4">
						<h5>CNPJ: <strong>{{$dadosEmitente['cnpj']}}</strong></h5>
						<h5>IE: <strong>{{$dadosEmitente['ie']}}</strong></h5>
					</div>
				</div>
				<div class="row">
					<div class="col s8">
						<h5>Logradouro: <strong>{{$dadosEmitente['logradouro']}}</strong></h5>
						<h5>Numero: <strong>{{$dadosEmitente['numero']}}</strong></h5>
						<h5>Bairro: <strong>{{$dadosEmitente['bairro']}}</strong></h5>
					</div>
					<div class="col s4">
						<h5>CEP: <strong>{{$dadosEmitente['cep']}}</strong></h5>
						<h5>Fone: <strong>{{$dadosEmitente['fone']}}</strong></h5>
					</div>
				</div>
				
			</div>
		</div>
		<input type="hidden" id="xmlEntrada" value="{{$pathXml}}">
		<input type="hidden" id="idFornecedor" value="{{$idFornecedor}}">
		<input type="hidden" id="nNf" value="{{$dadosNf['nNf']}}">
		<input type="hidden" id="vDesc" value="{{$dadosNf['vDesc']}}">
		<input type="hidden" id="vFrete" value="{{$dadosNf['vFrete']}}">
		<input type="hidden" id="chave" value="{{$dadosNf['chave']}}">
		<input type="hidden" id="totalNF" value="{{$dadosNf['vProd']}}">

		<div class="card">
			<div class="row">
				<div class="col s12">
					<h4>Itens da NF</h4>
					<p class="red-text">* Produtos em vermelho ainda não cadastrado no sistma</p>
					
					<table class="striped" id="tbl">
						<thead>
							<tr>
								<th>Código</th>
								<th>Produto</th>
								<th>NCM</th>
								<th>CFOP</th>
								<th>Cod Barra</th>
								<th>Un. Compra</th>
								<th>Valor</th>
								<th>Quantidade</th>
								<th>Subtotal</th>
								<th>Ações</th>
							</tr>
						</thead>

						<input type="hidden" id="itens_nf" value="{{json_encode($itens)}}">
						<tbody id="tbody">
						</tbody>
					</table>
					<div class="row">
						<h5>Soma dos Itens: <strong id="soma-itens" class="red-text"></strong></h5>
					</div>
				</div>
				
			</div>
		</div>

		<div class="card">
			<div class="row">
				<div class="col s12">

					<div class="card-content">
						<h4>Fatura Integral</h4>
						<input type="hidden" id="fatura" value="{{json_encode($fatura)}}">
						<div class="row">
							@foreach($fatura as $f)
							<div class="card col s4" style="border-bottom: 3px solid #EE6E73">
								<div class="row">
									<h5>Número: <strong>{{$f['numero']}}</strong></h5>
									<h5>Vencimento: <strong>{{$f['vencimento']}}</strong></h5>
									<h5>Valor de Parcela: <strong>{{$f['valor_parcela']}}</strong></h5>
								</div>
							</div>
							@endforeach
						</div>

					</div>
				</div>
			</div>
		</div>


		<div class="card">

			<br>
			<div class="row">
				<div class="col s12">
					<div class="card-content">
						<div class="input-field col s6">
							<select id="natureza">
								@foreach($naturezas as $n)
								<option value="{{$n->id}}">{{$n->natureza}}</option>
								@endforeach
							</select>
							<label>Natureza de Operação</label>
						</div>


						<div class="row">
							<div class="input-field col s10">
								<textarea id="motivo" data-length="100" class="materialize-textarea"></textarea>
								<label for="textarea1">Motivo</label>
							</div>
						</div>

						<div class="row">
							<div class="input-field col s6">
								<textarea data-length="50" id="obs" class="materialize-textarea"></textarea>
								<label for="textarea1">Observação</label>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col s6">
					<h4>Total Integral da NF: <strong id="valorDaNF" class="blue-text">{{$dadosNf['vProd']}}</strong></h4>
				</div>
				<input type="hidden" value="{{csrf_token()}}" id="_token">
				<div class="col s6 right-align">
					<button id="savar-devolucao" class="btn-large red">Salvar Devolução</button>
				</div>
			</div><br><br>

			<div id="preloader2" style="display: none">
				<div class="col s12 center-align">
					<div class="preloader-wrapper active">
						<div class="spinner-layer spinner--only">
							<div class="circle-clipper left">
								<div class="circle"></div>
							</div><div class="gap-patch">
								<div class="circle"></div>
							</div><div class="circle-clipper right">
								<div class="circle"></div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</div>

	<div id="modal2" class="modal col s12">
		<div class="modal-header"><br>
			<h3 class="center-align">Editar Item</h3>
		</div>
		<div class="modal-content">
			<div class="row">
				<div class="col s12">
					<input type="text" class="validate" id="nomeEdit">
					<label for="nome">Nome do Item</label>
				</div> 
				<input id="idEdit" type="hidden" value="">

				<div class="col s6">
					<input type="text" class="validate qCom" id="quantidadeEdit">
					<label>Quantidade</label>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<a href="#!" class="modal-action btn-large red modal-close waves-effect waves-green ">Fechar</a>

			<button id="salvarEdit" class="btn-large">Salvar</button>
		</div><br>
	</div>

	@endsection	