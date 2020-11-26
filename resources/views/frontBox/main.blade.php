@extends('default.layout')
@section('content')
<style type="text/css">
.table{
	height: 344px;
	overflow-x:auto;
}
table tbody{

}

.money-cel{
	width: 120px;
	height: 50px;
}

.money-moeda{
	width: 80px;
}

#btn-plus{
	margin-left: -8px;
	padding: 3px;
	background: #69f0ae;
	border-radius: 10px;
	/*border: 1px solid #444;*/
}

#btn-plus:hover{
	cursor: pointer;
}

</style>


<div class="row">
	<input type="hidden" id="_token" value="{{ csrf_token() }}">

	<input type="hidden" id="valor_entrega" @if(isset($valor_entrega)) value="{{$valor_entrega}}" @else value='0' @endif>
	
	@if(isset($itens))
	<input type="hidden" id="itens_pedido" value="{{json_encode($itens)}}">
	<input type="hidden" id="valor_total" @if(isset($valor_total)) value="{{$valor_total}}" @else value='0' @endif>
	<input type="hidden" id="delivery_id" @if(isset($delivery_id)) value="{{$delivery_id}}" @else value='0' @endif>
	<input type="hidden" id="bairro" @if(isset($bairro)) value="{{$bairro}}" @else value='0' @endif>
	
	<input type="hidden" id="codigo_comanda_hidden" @if(isset($cod_comanda)) value="{{$cod_comanda}}" @else value='0' @endif name="">
	@endif

	<input type="hidden" id="PDV_VALOR_RECEBIDO" value="{{ getenv('PDV_VALOR_RECEBIDO') }}">


	<div class="col s5">
		<div class="card">
			<div class="row">
				<div class="input-field col s10">
					<i class="material-icons prefix">person</i>
					<input autocomplete="off" type="text" name="cliente" id="autocomplete-cliente" class="autocomplete-cliente">
					<label for="autocomplete-cliente green-text">Cliente</label>

				</div>
				<div class="col s2"><br>
					<button style="display: none" id="edit-cliente" class="btn"><i class="material-icons">edit</i></button>
				</div>
				
				<div class="col s12">
					<h6 id="cliente-nao" class="red-text">*Nenhum cliente identificado</h6>
				</div>
			</div>
		</div>
		<div class="card">
			<div class="row">

				<input type="hidden" id="semCertificado" value="{{$certificado == null ? true : false}}" name="">
				<div class="row">

					@if(isset($listaPreco))

					<div class="input-field col s12">
						<i class="material-icons prefix">attach_money</i>
						<select id="lista_id">
							<option value="0">Padrão</option>
							@foreach($listaPreco as $l)
							<option value="{{$l->id}}">{{$l->nome}} - {{$l->percentual_alteracao}}%</option>
							@endforeach
						</select>
						<label>
							Lista de Preço
						</label>
					</div>

					@else

					<div class="input-field col s12">
						<i class="material-icons prefix">attach_money</i>
						<select disabled id="">
							<option value="0">Padrão</option>
							
						</select>
						<label>
							Lista de Preço
						</label>
					</div>

					@endif
					<div class="input-field col s12" style="margin-top: 15px;">
						<i class="material-icons prefix">inbox</i>
						<input autofocus="true" autocomplete="off" type="text" name="produto" id="autocomplete-produto" class="autocomplete-produto">
						<label for="autocomplete-produto green-text">Produto</label>

					</div>
					
				</div>

				<div class="row">
					<div class="input-field col s6">
						<i id="btn-plus" class="material-icons prefix">exposure_plus_1</i>
						<input type="text" id="quantidade" value="1">
						<label for="quantidade">Quantidade</label>

					</div>

					<div class="input-field col s6">
						<i class="material-icons prefix">attach_money</i>
						<input type="text" id="valor_item" value="0.00">
						<label for="valor_item">Valor do Item</label>

					</div>
				</div>


				
				<div style="margin-bottom: 75px;">
					<div class="col s2 no-padding">
						<button class="btn-large indigo accent-3 modal-trigger" style="width: 98%" href="#modal-obs-item">
							<i class="material-icons">note</i>
						</button>
					</div>
					<div class="col s10 no-padding">
						<a id="adicionar-item" class="btn-large green accent-3" style="width: 100%;">Adicionar Item</a>
					</div>
				</div>
				


				<div class="row">
					<br>
					<h5 class="center-align">ATALHOS</h5>
					<div class="row">
						<div class="col s12" style="margin-top: 10px;">
							<button style="width: 100%;" href="#modal-comanda" class="btn modal-trigger teal lighten-1">Apontar Comanda</button>
						</div>
					</div>
					<div >
						<div class="col s6" style="margin-top: 10px;">
							<button style="width: 100%;" href="#modal2" class="btn modal-trigger grey lighten-1">Sangria</button>
						</div>
						<div class="col s6" style="margin-top: 10px;">
							<a href="/frenteCaixa/devolucao" style="width: 100%;" class="btn red lighten-1">Devolução</a>
						</div>
					</div>
					<div class="">
						<div class="col s6" style="margin-top: 10px;">
							<button onclick="fluxoDiario()" style="width: 100%;" class="btn blue lighten-1">Fluxo Diário</button>
						</div>

						<div class="col s6" style="margin-top: 10px;">
							<a href="/frenteCaixa/list" style="width: 100%;" class="btn green lighten-1">Lista de Vendas</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</div>
	<div class="col s7" style="margin-top: 0px;">
		<div class="card">
			<div class="row">
				<div class="row">
					<div class="col s12 green accent-3" id="prod-nome">
						<h5 id="nome-produto" class="center-align white-text">--</h5>
					</div>
				</div>
				<div class="col s12">

					<div class="card table">
						<div class="row">
							<div class="col s12">
								<h6>ITENS</h6>
							</div>
							<table class="stiped">
								<thead>
									<tr>
										<th>ITEM</th>
										<th>ID</th>
										<th>PRODUTO</th>
										<th>QTD</th>
										<th>V.UNIT</th>
										<th>SUB</th>
									</tr>
								</thead>
								<tbody id="body">

								</tbody>
							</table>
						</div>
					</div>
					<div class="">
						<div class="col s3">
							<h6>Itens: <strong class="green-text" id="qtd-itens">0</strong></h6>
						</div>
						<div class="col s3">
							<h6>Quantidade: <strong class="green-text" id="_qtd">0</strong></h6>
						</div>
						<div class="col s5 offset-s1">
							<button style="width: 100%" onclick="verItens()" href="" class="btn black">Ver todos os itens</button>
						</div>

					</div>

					<div class="">
						<div class="col s4 input-field">
							<input type="text" id="valor_recebido" name="" value="">
							<label>Valor Recebido</label>
						</div>
						<div class="col s3 input-field">
							<input type="text" id="desconto" name="" value="0">
							<label>Desconto R$</label>
						</div>
						<div class="input-field col s3">
							<input type="text" id="acrescimo" name="" value="0">
							<label>Acrescimo % ou R$</label>
						</div>
						

					</div>

					<div class="row">
						<div class="input-field col s6">
							<select id="tipo-pagamento">
								<option value="--">Selecione o Tipo de pagamento</option>
								@foreach($tiposPagamento as $key => $t)
								<option 
								@if($config->tipo_pagamento_padrao == $key)
								selected
								@endif
								value="{{$key}}">{{$key}} - {{$t}}</option>
								@endforeach
							</select>
							<label>Forma de Pagamento</label>
						</div>

						<div class="input-field col s2">
							<a id="click-multi" class="btn-large green lighten-2 modal-trigger" href="#modal-pag-mult">
								<i class="material-icons">list</i>
							</a>
						</div>
					</div>


					<div class="row">
						<div class="col s2 no-padding" >
							<button id="btn-obs" style="width: 97%; background-color: : #000" class="btn-large indigo lighten-2 modal-trigger" href="#modal-obs">
								<i class="material-icons">note</i>
							</button>
						</div>

						<div class="col s10 no-padding">
							<button id="finalizar-venda" style="width: 100%; font-size: 28px;" class="btn-large green accent-3 modal-trigger disabled" href="#modal-venda">Finalizar <strong id="total-venda" class="indigo-text" >R$ 0,00</strong></button>
						</div>
					</div>
				</div>
			</div>
			

		</div>
	</div>
</div>

<div id="modal-comanda" class="modal">
	<div class="modal-content">
		<h5>Informe o código da comanda</h5>
		<div class="row">
			<div class="col s6 input-field">
				<input type="text" id="cod-comanda" name="">
				<label>Código</label>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#!" onclick="apontarComanda()" class="btn indigo lighten-2">Apontar</a>
	</div>
</div>

<div id="modal1" class="modal">
	<div class="modal-content">
		<h4>É necessário abrir o caixa com um valor</h4>
		<div class="row">
			<div class="col s6 input-field">
				<input type="text" id="valor" name="">
				<label>Valor</label>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#!" onclick="abrirCaixa()" class="btn indigo lighten-2">Abrir</a>
	</div>
</div>



<div id="modal-obs-item" class="modal">
	<div class="modal-content">
		<h4>Observação do Item (opcional)</h4>
		<div class="row">
			<div class="col s12 input-field">
				<input type="text" id="obs-item" name="" data-length="80">
				<label>Observação</label>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#!" onclick="setarObservacaoItem()" class="btn indigo accent-2">OK</a>
	</div>
</div>

<div id="modal-obs" class="modal">
	<div class="modal-content">
		<h4>Observação da Venda (opcional)</h4>
		<div class="row">
			<div class="col s12 input-field">
				<input value="{{{isset($observacao) ? $observacao : ''}}}" type="text" id="obs" name="" data-length="100">
				<label>Observação</label>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#!" onclick="setarObservacao()" class="btn indigo lighten-2">OK</a>
	</div>
</div>

<div id="modal2" class="modal">
	<div class="modal-content">
		<h4>Sangria de caixa</h4>
		<div class="row">
			<div class="col s10 input-field">
				<input type="text" id="valor_sangria" name="">
				<label>Valor</label>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#!" onclick="sangriaCaixa()" class="btn">Salvar</a>
	</div>
</div>

<div id="modal3" class="modal">
	<div class="modal-content">
		<h4>Fluxo Diário</h4>

		<div id="preloader1" style="display: none">
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

		<div class="card">
			<div class="row">
				<div class="col s12">
					<h5>Abertura de Caixa:</h5>
					<div id="fluxo_abertura_caixa"></div>
				</div>
			</div>
			<div class="row">
				<div class="col s12">
					<h5>Sangrias:</h5>
					<div id="fluxo_sangrias"></div>
				</div>
			</div>
			<div class="row">
				<div class="col s12">
					<h5>Vendas:</h5>
					<div id="fluxo_vendas"></div>
				</div>
			</div>

			<div class="row">
				<div class="col s12">
					<h5>Total em caixa: 
						<strong id="total_caixa" class="green-text"></strong></h5>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Fechar</a>
		</div>
	</div>

	<div id="modal4" class="modal">
		<div class="modal-content">
			<div class="row">
				<h4>Valor do troco: <strong id="valor_troco" class="orange-text">0,00</strong></h4>

				<h5>Sugestão:</h5>
				<div class="row 50_reais" style="display: none">
					<div class="col s3">
						<img class="money-cel" src="/imgs/50_reais.jpeg"> 
					</div>
					<div class="col s3">
						<h4 id="qtd_50_reais"></h4>
					</div>
				</div>
				<div class="row 20_reais" style="display: none">
					<div class="col s3">
						<img class="money-cel" src="/imgs/20_reais.jpeg"> 
					</div>
					<div class="col s3">
						<h4 id="qtd_20_reais"></h4>
					</div>
				</div>

				<div class="row 10_reais" style="display: none">
					<div class="col s3">
						<img class="money-cel" src="/imgs/10_reais.jpeg"> 
					</div>
					<div class="col s3">
						<h4 id="qtd_10_reais"></h4>
					</div>
				</div>

				<div class="row 5_reais" style="display: none">
					<div class="col s3">
						<img class="money-cel" src="/imgs/5_reais.jpeg"> 
					</div>
					<div class="col s3">
						<h4 id="qtd_5_reais"></h4>
					</div>
				</div>

				<div class="row 2_reais" style="display: none">
					<div class="col s3">
						<img class="money-cel" src="/imgs/2_reais.jpeg"> 
					</div>
					<div class="col s3">
						<h4 id="qtd_2_reais"></h4>
					</div>
				</div>

				<div class="row 1_real" style="display: none">
					<div class="col s3">
						<img class="money-moeda" src="/imgs/1_real.png"> 
					</div>
					<div class="col s3">
						<h4 id="qtd_1_real"></h4>
					</div>
				</div>

				<div class="row 50_centavo" style="display: none">
					<div class="col s3">
						<img class="money-moeda" src="/imgs/50_centavo.png"> 
					</div>
					<div class="col s3">
						<h4 id="qtd_50_centavos"></h4>
					</div>
				</div>

				<div class="row 25_centavo" style="display: none">
					<div class="col s3">
						<img class="money-moeda" src="/imgs/25_centavo.png"> 
					</div>
					<div class="col s3">
						<h4 id="qtd_25_centavos"></h4>
					</div>
				</div>

				<div class="row 10_centavo" style="display: none">
					<div class="col s3">
						<img class="money-moeda" src="/imgs/10_centavo.png"> 
					</div>
					<div class="col s3">
						<h4 id="qtd_10_centavos"></h4>
					</div>
				</div>


				<div class="row 5_centavo" style="display: none">
					<div class="col s3">
						<img class="money-moeda" src="/imgs/5_centavo.png"> 
					</div>
					<div class="col s3">
						<h4 id="qtd_5_centavos"></h4>
					</div>
				</div>

			</div>
		</div>
		<div class="modal-footer">
			<div class="modal-footer">
				<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Fechar</a>
			</div>

		</div>
	</div>

	<div id="modal-credito" class="modal">

		<div class="modal-content">
			<p class="center-align"><i class="large material-icons green-text">check_circle</i></p>
			<h4 class="center-align">Tudo Certo!</h4>
			<p class="center-align" id="evento-conta-credito"></p>

		</div>
		<div class="modal-footer">
			<a href="#!" onclick="redireciona()" class="modal-action modal-close waves-effect waves-green btn-flat">Fechar</a>
		</div>
	</div>

	<div id="modal-alert" class="modal">

		<div class="modal-content">
			<p class="center-align"><i class="large material-icons green-text">check_circle</i></p>
			<h4 class="center-align">Tudo Certo!</h4>
			<p class="center-align" id="evento"></p>

		</div>
		<div class="modal-footer">
			<a href="#!" onclick="redireciona()" class="modal-action modal-close waves-effect waves-green btn-flat">Fechar</a>
		</div>
	</div>

	<div id="modal-alert-erro" class="modal">

		<div class="modal-content">
			<p class="center-align"><i class="large material-icons red-text">error</i></p>
			<h4 class="center-align">Aldo deu errado!</h4>
			<p class="center-align" id="evento-erro"></p>

		</div>
		<div class="modal-footer">
			<a href="#!" onclick="redireciona()" class="modal-action modal-close waves-effect waves-green btn-flat">Fechar</a>
		</div>
	</div>

	<div id="modal-venda" class="modal">
		<div class="modal-content">

			<div class="row">

				
				<div class="col @if($usuario->venda_nao_fiscal == 1) s4 @else s6 @endif">
					<a class="btn-large blue modal-trigger @if($certificado == null) disabled @endif" style="width: 100%" onclick="verificaCliente()">Cupom Fiscal</a>
				</div>


				@if($usuario->venda_nao_fiscal == 1)
				<div class="col s4">
					<button id="btn_nao_fiscal" onclick="finalizarVenda('nao_fiscal')" style="width: 100%" class="btn-large red">Cupom Não Fiscal</button>
				</div>
				@endif

				<div class="col @if($usuario->venda_nao_fiscal == 1) s4 @else s6 @endif">
					<button id="conta_credito-btn" onclick="finalizarVenda('credito')" style="width: 100%" class="btn-large orange disabled">Conta Crédito</button>
				</div>

				<div id="preloader9" class="row" style="display: none">
					<div class="col s12 center-align"><br>
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
					<p class="center-align">Emitindo NFCe</p>
				</div>
			</div>
		</div>
	</div>



	<div id="modal-cpf-nota" class="modal">
		<div class="modal-content">
			<h4 class="center-align">CPF NA NOTA?</h4>
			<div class="row">
				<div class="col s6 input-field">
					<input type="text" id="nome" name="">
					<label>Nome</label>
				</div>
			</div>

			<div class="row">
				<div class="col s6 input-field">
					<input type="text" id="cpf" name="">
					<label>CPF</label>
				</div>
			</div>

			<div id="preloader2" class="row" style="display: none">
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
				<p class="center-align">Emitindo NFCe</p>
			</div>

			<div class="col s6 ofsset-s3">
				<button onclick="finalizarVenda('fiscal')" style="width: 100%" class="btn-large green">OK</button>
				
				<!-- <button onclick="teste()" style="width: 100%" class="btn-large green">OK</button> -->
			</div>
		</div>
	</div>

	<div id="modal-itens" class="modal">
		<div class="modal-content">

			<table class="stiped">
				<thead>
					<tr>
						<th>ITEM</th>
						<th>ID</th>
						<th>NOME</th>
						<th>QTD</th>
						<th>V.UNIT</th>
						<th>SUB</th>
					</tr>
				</thead>
				<tbody id="body-modal">

				</tbody>
			</table>
		</div>
		<div class="modal-footer">
			<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Fechar</a>
		</div>
	</div>

	<div id="modal-pag-mult" class="modal">

		<div class="modal-content">
			<div class="row">

				<div class="col s3">
					<a href="#!" id="close-multi" class="btn red">Fechar</a>

				</div>
				<div class="col s9">

					<h4 class="">PAGAMENTO MULTIPLO</h4>
				</div>
			</div>

			<h5 class="center-align">Valor: R$ <strong id="total-multi"></strong></h5>
			<div class="row">
				<div class="col s4 input-field">
					<select id="tipo_pagamento_1">
						@foreach($tiposPagamentoMulti as $t)
						<option value="{{$t}}">{{$t}}</option>
						@endforeach
					</select>
					<label>Tipo pagamento 1</label>
				</div>

				<div class="col s4 input-field">
					<input type="text" class="money" id="valor_pagamento_1" name="">
					<label>Valor pagamento 1</label>
				</div>
			</div>

			<div class="row">
				<div class="col s4 input-field">
					<select id="tipo_pagamento_2">
						@foreach($tiposPagamentoMulti as $t)
						<option value="{{$t}}">{{$t}}</option>
						@endforeach
					</select>
					<label>Tipo pagamento 2</label>
				</div>

				<div class="col s4 input-field">
					<input type="text" class="money" id="valor_pagamento_2" name="">
					<label>Valor pagamento 2</label>
				</div>
			</div>

			<div class="row">
				<div class="col s4 input-field">
					<select id="tipo_pagamento_3">
						@foreach($tiposPagamentoMulti as $t)
						<option value="{{$t}}">{{$t}}</option>
						@endforeach
					</select>
					<label>Tipo pagamento 3</label>
				</div>

				<div class="col s4 input-field">
					<input type="text" class="money" id="valor_pagamento_3" name="">
					<label>Valor pagamento 3</label>
				</div>
			</div>

			
			<div class="col s6 ofsset-s3">
				<button id="btn-ok-multi" style="width: 100%" class="btn-large blue lighten-2 disabled">OK</button>
				
				<!-- <button onclick="teste()" style="width: 100%" class="btn-large green">OK</button> -->
			</div>

		</div>
	</div>

	@endsection