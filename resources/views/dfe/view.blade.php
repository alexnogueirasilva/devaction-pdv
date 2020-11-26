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

	<h1 class="center-align">XML</h1>
	@if($compraFiscal)
	<h5 class="red-text center-align">Atenção Este XML já foi incluido através da Compra Fiscal!</h5>
	@endif
	<div class="col s12">
		<h4 class="center-align">Nota Fiscal <strong class="grey-text">{{$infos['nNf']}}</strong></h4>
		<h4 class="center-align">Chave <strong class="grey-text">{{$infos['chave']}}</strong></h4>

		@if(session()->has('message'))
		<div class="row">
			<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
				<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
			</div>
		</div>
		@endif

		<div class="card">
			<div class="card-content">
				@if($fornecedor['novo_cadastrado'])
				<h5 class="red-text center-align">Fornecedor Cadastrado com Sucesso!</h5>
				@endif
				<div class="row">
					<div class="col s8">
						<h5>Fornecedor: <strong>{{$fornecedor['razaoSocial']}}</strong></h5>
						<h5>Nome Fantasia: <strong>{{$fornecedor['nomeFantasia']}}</strong></h5>
					</div>
					<div class="col s4">
						<h5>CNPJ: <strong>{{$fornecedor['cnpj']}}</strong></h5>
						<h5>IE: <strong>{{$fornecedor['ie']}}</strong></h5>
					</div>
				</div>
				<div class="row">
					<div class="col s8">
						<h5>Logradouro: <strong>{{$fornecedor['logradouro']}}</strong></h5>
						<h5>Numero: <strong>{{$fornecedor['numero']}}</strong></h5>
						<h5>Bairro: <strong>{{$fornecedor['bairro']}}</strong></h5>
					</div>
					<div class="col s4">
						<h5>CEP: <strong>{{$fornecedor['cep']}}</strong></h5>
						<h5>Fone: <strong>{{$fornecedor['fone']}}</strong></h5>
					</div>
				</div>
				
			</div>
		</div>


		<div class="card">
			<div class="row">
				<div class="col s12">
					<h4>Itens da NF</h4>
					<p class="red-text">* Produtos em vermelho ainda não cadastrado no sistma</p>
				</strong></p>
				<table class="striped">
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

					<tbody id="tbody">
						@foreach($itens as $i)
						<tr id="tr_{{$i['codigo']}}">
							<th class="codigo">{{$i['codigo']}}</th>
							<th id="th_{{$i['codigo']}}" class="nome {{$i['produtoNovo'] == true ? 
							'red-text' : ''}}">{{$i['xProd']}}</th>
							<th class="ncm">{{$i['NCM']}}</th>
							<th class="cfop">{{$i['CFOP']}}</th>
							<th class="codBarras">{{$i['codBarras']}}</th>
							<th class="unidade">{{$i['uCom']}}</th>
							<th class="valor">{{$i['vUnCom']}}</th>
							<th class="quantidade">{{$i['qCom']}}</th>
							<th class="cod" id="th_prod_id_{{$i['codigo']}}" style="display: none">{{$i['produtoId']}}</th>

							<th style="display: none" class="conv_estoque" id="th_prod_conv_unit_{{$i['codigo']}}">
								{{$i['conversao_unitaria']}}
							</th>


							<th>{{number_format((float) $i['qCom'] * (float) $i['vUnCom'], 2, 
							',', '.')}}</th>

							<th id="th_acao1_{{$i['codigo']}}" @if($i['produtoNovo'])
							style="display: block" @else style="display: none"
							@endif>

							<button onclick="cadProd('{{$i['codigo']}}','{{$i['xProd']}}','{{$i['codBarras']}}','{{$i['NCM']}}','{{$i['CFOP']}}','{{$i['uCom']}}','{{$i['vUnCom']}}','{{$i['qCom']}}', '{{$i['vUnCom']}}', '{{$infos['nNf']}}')" class="btn">
								<i class="material-icons">add</i>
							</button>


						</th>	


						<th id="th_acao2_{{$i['codigo']}}" @if($i['produtoNovo'])
						style="display: none" @else style="display: block" 
						@endif>
						<a id="th_estoque_{{$i['produtoId']}}" title="Adicionar estoque" onclick="salvarEstoque('{{$i['produtoId']}}', '{{$i['qCom']}}', '{{$i['vUnCom']}}')" class="btn yellow @if($i['produtoSetadoEstoque']) disabled @endif" href="#tbody">
							<i class="material-icons">add_shopping_cart</i>
						</a>


							<!-- <a onclick="deleteProd(this)" class="btn red" href="#tbody">
								<i class="material-icons">delete</i>
							</a> -->
						</th>


					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>


	<div class="row">
		<div class="col s12">
			<div class="card">
				<div class="card-content">
					<h4>Fatura</h4>
					<input type="hidden" id="fatura" value="{{json_encode($fatura)}}">
					<div class="row">
						@if(count($fatura) > 0)
						@foreach($fatura as $f)
						<div class="card col s4" style="border-bottom: 3px solid #EE6E73">
							<div class="row">
								<h5>Número: <strong>{{$f['numero']}}</strong></h5>
								<h5>Vencimento: <strong>{{$f['vencimento']}}</strong></h5>
								<h5>Valor de Parcela: <strong>{{$f['valor_parcela']}}</strong></h5>
							</div>
						</div>
						@endforeach
						@else
						<h5 class="red-text center-align">Nenhuma Fatura</h5>
						@endif
					</div>
					@if(count($fatura) > 0)
					<div class="row">
						<form method="get" action="/dfe/salvarFatura">
							<input type="hidden" id="" value="{{$infos['chave']}}" name="chave">
							<input type="hidden" id="" value="{{json_encode($fatura)}}" name="fatura">
							<button @if($fatura_salva) disabled @endif class="btn">Salvar Fatura no Contas a Pagar</button>
						</form>
					</div>
					@endif

				</div>
			</div>

		</div>
	</div>

	<div class="row">
		<div class="col s6">
			<h4>Total: <strong id="valorDaNF" class="blue-text">{{$infos['vProd']}}</strong></h4>
		</div>

		<div class="col s2 offset-s4">
			<a target="_blank" class="btn red" href="/dfe/downloadXml/{{$infos['chave']}}">
				<i class="material-icons left">assignment_returned</i>
				Baixar XML
			</a>
		</div>

		<!-- <div class="col s6 right-align">
			<button id="salvarNF" class="btn-large green accent-3 disabled">Salvar</button>
		</div> -->
	</div>

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

</div>


<!-- Model Cad -->
<div id="modal1" class="modal col s12">
	<div class="modal-content">
		<div class="row">
			<div class="input-field col s12">
				<input type="text" class="validate" id="nome">
				<label for="nome">Nome do Produto</label>
			</div>
		</div>

		<div class="row">
			<div class="input-field col s6">
				<input disabled type="text" class="validate" id="ncm">
				<label>NCM</label>
			</div>

			<div class="input-field col s6">
				<input disabled type="text" class="validate" id="cfop">
				<label>CFOP</label>
			</div>
		</div>

		<div class="row">
			<div class="input-field col s6">
				<input disabled type="text" class="validate" id="un_compra">
				<label>Unidade de Compra</label>
			</div>

			<div class="input-field col s6">
				<input type="text" class="validate" id="conv_estoque">
				<label>Conversão unitária para estoque</label>
			</div>
		</div>

		<div class="row">
			<div class="input-field col s6">
				<input disabled type="text" class="validate" id="valor_compra">
				<label>Valor de Compra</label>
			</div>

			<div class="input-field col s6">
				<input disabled type="text" class="validate" id="quantidade">
				<label>Quantidade</label>
			</div>
		</div>
		<input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">


		<div class="row">
			<div class="input-field col s6">
				<input type="text" class="validate" id="valor_venda">
				<label>Valor de Venda</label>
			</div>

			<div class="input-field col s6">
				<select id="unidade_venda">
					@foreach($unidadesDeMedida as $u)
					<option value="{{$u}}">{{$u}}</option>
					@endforeach
				</select>
				<label>Unidade de venda</label>
			</div>

		</div>
		
		<div class="row">
			<div class="input-field col s6">
				<select id="cor">
					<option value="--">--</option>
					<option value="Preto">Preto</option>
					<option value="Branco">Branco</option>
					<option value="Dourado">Dourado</option>
					<option value="Vermelho">Vermelho</option>
					<option value="Azul">Azul</option>
					<option value="Rosa">Rosa</option>
				</select>
				<label>Cor (Opcional)</label>
			</div>

			<div class="input-field col s6">
				<select id="categoria_id">
					@foreach($categorias as $cat)
					<option value="{{$cat->id}}">{{$cat->nome}}</option>
					@endforeach
				</select>
				<label>Categoria</label>
			</div>


			<div class="input-field col s6">
				<select id="categoria_id">
					@foreach($categorias as $cat)
					<option value="{{$cat->id}}">{{$cat->nome}}</option>
					@endforeach
				</select>
				<label>Categoria</label>
			</div>

			<div class="row">

				<div class="input-field col s12">

					<select id="CST_CSOSN">
						@foreach($listaCSTCSOSN as $key => $c)
						<option value="{{$key}}"
						@if($config != null)
							@if(isset($produto))
								@if($key == $produto->CST_CSOSN)
								selected
								@endif
							@else
							@if($key == $config->CST_CSOSN_padrao)
							selected
							@endif
						@endif
						@endif
						>{{$key}} - {{$c}}</option>
						@endforeach
					</select>

					<label for="CEST">CST/CSOSN</label>

				</div>
			</div>
			<div class="row">
				<div class="input-field col s6">

					<select id="CST_PIS">
						@foreach($listaCST_PIS_COFINS as $key => $c)
						<option value="{{$key}}"
						@if($config != null)
						@if(isset($produto))
						@if($key == $produto->CST_PIS)
						selected
						@endif
						@else
						@if($key == $config->CST_PIS_padrao)
						selected
						@endif
						@endif

						@endif
						>{{$key}} - {{$c}}</option>
						@endforeach
					</select>

					<label for="CEST">CST PIS</label>

				</div>

				<div class="input-field col s6">

					<select id="CST_COFINS">
						@foreach($listaCST_PIS_COFINS as $key => $c)
						<option value="{{$key}}"
						@if($config != null)
						@if(isset($produto))
						@if($key == $produto->CST_COFINS)
						selected
						@endif
						@else
						@if($key == $config->CST_COFINS_padrao)
						selected
						@endif
						@endif

						@endif
						>{{$key}} - {{$c}}</option>
						@endforeach
					</select>

					<label for="CEST">CST COFINS</label>

				</div>
			</div>

			<div class="row">
				<div class="input-field col s6">

					<select id="CST_IPI">
						@foreach($listaCST_IPI as $key => $c)
						<option value="{{$key}}"
						@if($config != null)
						@if(isset($produto))
						@if($key == $produto->CST_IPI)
						selected
						@endif
						@else
						@if($key == $config->CST_IPI_padrao)
						selected
						@endif
						@endif

						@endif
						>{{$key}} - {{$c}}</option>
						@endforeach
					</select>

					<label for="CEST">CST IPI</label>

				</div>
			</div>

		</div>
	</div>
	<div class="row" id="preloader" style="display: none">
		<div class="col s12 center-align">
			<div class="preloader-wrapper active">
				<div class="spinner-layer spinner-red-only">
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
	
	<div class="modal-footer">
		<a href="#!" class="modal-action btn-large red modal-close waves-effect waves-green ">Fechar</a>

		<button id="salvar" class="btn-large">Salvar</button>
	</div><br>
</div>


<!-- Model Edit -->
<div id="modal2" class="modal col s12">
	<div class="modal-header"><br>
		<h3 class="center-align">Editar Dados</h3>
	</div>
	<div class="modal-content">
		<div class="row">
			<div class="col s8">
				<input type="text" class="validate" id="nomeEdit">
				<label for="nome">Nome do Produto</label>
			</div> 
			<input id="idEdit" type="hidden" value="">

			<div class="col s6">
				<input type="text" class="validate" id="conv_estoqueEdit">
				<label>Conversão unitária para estoque</label>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#!" class="modal-action btn-large red modal-close waves-effect waves-green ">Fechar</a>

		<button id="salvarEdit" class="btn-large">Salvar</button>
	</div><br>
</div>
@endsection	