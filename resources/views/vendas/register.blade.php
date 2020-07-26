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
		<div class="card">
			<div class="row">
				<div class="col s12">
					<h5 class="grey-text">DADOS INICIAIS</h5>
					<div class="row">
						<div class="col s4">
							<h6>Ultima NF: <strong>{{$lastNF}}</strong></h6>

						</div>
						<div class="col s4">
							
							@if($config->ambiente == 2)
							<h6>Ambiente: <strong class="blue-text">Homologação</strong></h6>
							@else
							<h6>Ambiente: <strong class="green-text">Produção</strong></h6>
							@endif
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col s12">
						<div class="input-field col s4">
							<i class="material-icons prefix">featured_play_list</i>
							<select id="natureza">
								@foreach($naturezas as $n)
								<option 
								@if($config->nat_op_padrao == $n->id)
								selected
								@endif
								value="{{$n->id}}">{{$n->natureza}}</option>
								@endforeach
							</select>
							<label>
								Natureza de Operação
							</label>
						</div>
					</div>
				</div>
				
				@if(isset($cliente))
				<input type="hidden" id="cliente_crediario" value="{{$cliente}}">

				<div class="col s6">
					<h5>Razão Social: <strong id="razao_social" class="red-text">{{$cliente->razao_social}}</strong></h5>
					<h5>Nome Fantasia: <strong id="nome_fantasia" class="red-text">
						{{$cliente->nome_fantasia}}
					</strong></h5>
					<h5>Logradouro: <strong id="logradouro" class="red-text">
						{{$cliente->rua}}
					</strong></h5>
					<h5>Numero: <strong id="numero" class="red-text">
						{{$cliente->rua}}
					</strong></h5>
					<h5>Limite: <strong id="limite" class="red-text">
						{{$cliente->limite_venda}}
					</strong></h5>
				</div>

				@else

				<div class="row">
					<div class="col s12">
						<div class="input-field col s6">
							<i class="material-icons prefix">person</i>
							<input autocomplete="off" type="text" name="cliente" id="autocomplete-cliente" class="autocomplete-cliente">
							<label for="autocomplete-cliente">Cliente</label>
							@if($errors->has('cliente'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('cliente') }}</span>
							</div>
							@endif
						</div>
					</div>
				</div>
				<div class="row" id="cliente" style="display: none">
					<div class="col s12">
						<h4 class="center-align">CLIENTE SELECIONADO</h4>
						<div class="col s6">
							<h5>Razão Social: <strong id="razao_social" class="red-text">--</strong></h5>
							<h5>Nome Fantasia: <strong id="nome_fantasia" class="red-text">--</strong></h5>
							<h5>Logradouro: <strong id="logradouro" class="red-text">--</strong></h5>
							<h5>Numero: <strong id="numero" class="red-text">--</strong></h5>
							<h5>Limite: <strong id="limite" class="red-text"></strong></h5>
						</div>
						<div class="col s6">
							<h5>CPF/CNPJ: <strong id="cnpj" class="red-text">--</strong></h5>
							<h5>RG/IE: <strong id="ie" class="red-text">--</strong></h5>
							<h5>Fone: <strong id="fone" class="red-text">--</strong></h5>
							<h5>Cidade: <strong id="cidade" class="red-text">--</strong></h5>
							
						</div>
					</div>
					
				</div>
				@endif

			</div>
		</div>

		<div class="row">
			<div class="col s12">
				<ul class="tabs">
					<li class="tab col s4"><a href="#itens" class="blue-text">ITENS</a></li>
					<li class="tab col s4"><a class="blue-text" href="#transporte">TRANSPORTE</a></li>
					<li class="tab col s4"><a class="blue-text" href="#pagamento">PAGAMENTO</a></li>

				</ul>
			</div>
			<div id="itens" class="col s12">
				<div class="card">
					<div class="row">
						<div class="col s12">
							<h5 class="grey-text">ITENS</h5>
						</div>

						<div class="row">
							<div class="col s12">
								<div class="input-field col s4">
									<i class="material-icons prefix">inbox</i>
									<input autocomplete="off" type="text" name="produto" id="autocomplete-produto" class="autocomplete-produto">
									<label for="autocomplete-produto">Produto</label>

								</div>

								<div class="col s2 input-field">
									<input type="text" value="0" id="quantidade">
									<label>Quantidade</label>
								</div>

								<div class="col s2 input-field">
									<input type="text" id="valor" value="0">
									<label>Valor Unitário</label>
								</div>

								<div class="col s2 input-field">
									<input type="text" id="subtotal" value="0" disabled="">
									<label>Subtotal</label>
								</div>

								<div class="col s1">
									<button id="addProd" class="btn-large orange">
										<i class="material-icons">add</i>

									</button>
								</div>

								<div class="col s2">
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
								</div>
							</div>
						</div>
						@if(isset($itens))
						<input type="hidden" value="{{json_encode($itens)}}" id="itens_credito">
						@endif
						<div class="row">
							<div class="col s12">
								<table id="prod" class="striped">
									<thead>
										<tr>
											<th>Item</th>
											<th>Código Produto</th>
											<th>Nome</th>
											<th>Quantidade</th>
											<th>Valor</th>
											<th>SubTotal</th>
											<th>Ação</th>
										</tr>
									</thead>

									<tbody>
										
									</tbody>
									
								</table>
							</div>
						</div>

					</div>
				</div>

			</div>
			<div id="transporte" class="col s12">
				
				<div class="card">
					<div class="row">

						<div class="col s12">
							<h5 class="grey-text">TRANPORTADORA</h5>
						</div>
						<div class="row">
							<div class="col s7">
								<div class="input-field col s12">
									<i class="material-icons prefix">directions_bus</i>
									<input autocomplete="off" type="text" name="transportadora" id="autocomplete-transportadora" class="autocomplete-transportadora">
									<label for="autocomplete-transportadora">Transportadora</label>
									@if($errors->has('transportadora'))
									<div class="center-align red lighten-2">
										<span class="white-text">{{ $errors->first('transportadora') }}</span>
									</div>
									@endif
								</div>
							</div>

							<div class="col s5" style="display: none" id="transp-selecionada">
								<div class="col s12">
									<h5>TRANSPORTADORA SELECIONADA</h5>
									<div class="col s6">
										<h6>Razão Social: <strong id="razao_social_transp" class="blue-text">--</strong></h6>

										<h6>Logradouro: <strong id="logradouro_transp" class="blue-text">--</strong></65>

											<h6>CPF/CNPJ: <strong id="cnpj_transp" class="blue-text">--</strong></h6>
											<h6>Cidade: <strong id="cidade_transp" class="blue-text">--</strong></h6>

										</div>
									</div>
								</div>
							</div>
							<div class="col s12">
								<h5 class="grey-text">FRETE</h5>
							</div>
							<div class="row">

								<div class="col s3 input-field">
									<select id="frete">
										<option @if($config->frete_padrao == '0') selected @endif value="0">0 - Emitente</option>
										<option @if($config->frete_padrao == '1') selected @endif  value="1">1 - Destinatário</option>
										<option @if($config->frete_padrao == '2') selected @endif  value="2">2 - Terceiros</option>
										<option @if($config->frete_padrao == '9') selected @endif  value="9">9 - Sem Frete</option>
									</select>
									<label>Tipo Frete</label>
								</div>


								<div class="col s2 input-field">
									<input type="text" id="placa" class="upper-input">
									<label>Placa Veiculo</label>
								</div>

								<div class="col s1 input-field">
									<select id="uf_placa">
										<option value="--">--</option>
										<option value="AC">AC</option>
										<option value="AL">AL</option>
										<option value="AM">AM</option>
										<option value="AP">AP</option>
										<option value="BA">BA</option>
										<option value="CE">CE</option>
										<option value="DF">DF</option>
										<option value="ES">ES</option>
										<option value="GO">GO</option>
										<option value="MA">MA</option>
										<option value="MG">MG</option>
										<option value="MS">MS</option>
										<option value="MT">MT</option>
										<option value="PA">PA</option>
										<option value="PB">PB</option>
										<option value="PE">PE</option>
										<option value="PI">PI</option>
										<option value="PR">PR</option>
										<option value="RJ">RJ</option>
										<option value="RN">RN</option>
										<option value="RS">RS</option>
										<option value="RO">RO</option>
										<option value="RR">RR</option>
										<option value="SC">SC</option>
										<option value="SE">SE</option>
										<option value="SP">SP</option>
										<option value="TO">TO</option>
									</select>
									<label>UF</label>

								</div>
								<div class="col s2 input-field">
									<input id="valor_frete" type="text">
									<label>Valor</label>
								</div>
							</div>

							<div class="col s12">
								<h5 class="grey-text">VOLUME</h5>
							</div>
							<div class="row">

								<div class="col s3 input-field">
									<input id="especie" type="text">
									<label>Espécie</label>
								</div>

								<div class="col s2 input-field">
									<input id="numeracaoVol" type="text">
									<label>Nuneração de Volumes</label>
								</div>
								<div class="col s2 input-field">
									<input id="qtdVol" type="text">
									<label>Quantidade de Volumes</label>
								</div>

								<div class="col s2 input-field">
									<input id="pesoL" type="text">
									<label>Peso Liquido</label>
								</div>

								<div class="col s2 input-field">
									<input id="pesoB" type="text">
									<label>Peso Bruto</label>
								</div>

							</div>
						</div>
					</div>
				</div>
				<input type="hidden" id="_token" value="{{ csrf_token() }}">

				<div id="pagamento" class="col s12">
					<div class="card">
						<div class="row">
							<div class="col s4">
								<h5 class="grey-text">PAGAMENTO</h5>

								<div class="row">
									<div class="col s12 input-field">
										<select id="tipoPagamento">
											<option value="--">Selecione o Tipo de pagamento</option>
											@foreach($tiposPagamento as $key => $t)
											<option 
											@if($config->tipo_pagamento_padrao == $key)
											selected
											@endif
											value="{{$key}}">{{$key}} - {{$t}}</option>
											@endforeach
										</select>
										<label>Tipo de Pagamento</label>

									</div>
								</div>

								<div class="row">
									<div class="col s12 input-field">
										<select id="formaPagamento">
											<option value="--">Selecione a forma de pagamento</option>
											<option value="a_vista">A vista</option>
											<option value="30_dias">30 Dias</option>
											<option value="personalizado">Personalizado</option>
											<option value="conta_crediario">Conta crediario</option>
										</select>
										<label>Forma de Pagamento</label>

									</div>
								</div>

								
								<div class="row">
									<div class="col s6">
										<input disabled type="text" class="" id="qtdParcelas">
										<label>Quantidade de Parcelas</label>
									</div>
								</div>
								<div class="row">
									<div class="col s6">
										<input disabled type="text" class="datepicker" id="data">
										<label>Data Vencimento</label>
									</div>
									<div class="col s6">
										<input disabled type="text" id="valor_parcela">
										<label>Valor Parcela</label>
									</div>
								</div>
								<div class="row">
									<button id="add-pag" style="width: 100%;" class="btn blue lighten-2">
										<i class="material-icons left">add</i>
									Adicionar</button>
								</div>


							</div>
							<div class="col s7">
								<div class="row">
									<div class="col s12">
										<table id="fatura">
											<thead>
												<tr>
													<th>Parc</th>
													<th>Data</th>
													<th>Valor</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td></td>
													<td></td>
													<td></td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
								<button id="delete-parcelas" class="btn yellow">
									<i class="material-icons left">close</i>
								Excluir Parcelas</button>
							</div>

						</div>
					</div>
				</div>
			</div>

			<div class="card">
				<div class="row">
					<div class="col s3"><br>
						<h5>Soma de Quantidade: <strong id="soma-quantidade" class="orange-text">0</strong></h5>
						<h5>Valor Total R$ <strong id="totalNF" class="cyan-text">0,00</strong></h5>
					</div>

					<div class="col s2 input-field"><br>
						<input type="text" id="desconto">
						<label>Desconto</label>
					</div>

					<div class="col s5 input-field"><br>
						<input type="text" id="obs" name="">
						<label>Informação Adicional</label>
					</div>

				</div>

				<div class="row">
					<div class="col s6">
						<a id="salvar-orcamento" style="width: 100%;" href="#" onclick="salvarOrcamento()" class="btn-large blue accent-3 disabled">Salvar como Orçamento</a>
					</div>
					<div class="col s6">
						<a id="salvar-venda" style="width: 100%;" href="#" onclick="salvarVenda('nfe')" class="btn-large green accent-3 disabled">Salvar Venda</a>
						
					</div>
				</div>
				<div class="row" >
					<div id="preloader2" style="display: none;">
						<div class="col s12 center-align">
							<div class="preloader-wrapper active">
								<div class="spinner-layer spinner-red-only">
									<div class="circle-clipper left">
										<div class="circle red"></div>
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
	</div>


	<div id="modal1" class="modal">
		<div class="modal-content">
			<h4 class="center-align">Selecione o Modelo para a Venda</h4>
			<div class="row">
				<div class="col s4">
					<button onclick="salvarVenda('cp_fiscal')" style="width: 100%;" id="cupom" class="btn-large green">Cupom Fiscal</button>
				</div>
				<div style="display: none" class="col s4" id="col-credito">
					<button onclick="salvarVenda('credito')" style="width: 100%;" id="cupom" class="btn-large red">Credito Cliente</button>
				</div>

				<div class="col s4" id="col-sem-credito"></div>
				<div class="col s4">
					<button onclick="salvarVenda('cp_nao_fiscal')" style="width: 100%;" id="cupom" class="btn-large orange">Cupom Não Fiscal</button>
				</div>
			</div>

		</div>
	</div>
	@endsection