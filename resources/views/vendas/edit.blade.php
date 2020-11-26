@extends('default.layout')
@section('content')


<div class="row" id="anime" style="display: none">
	<div class="col s8 offset-s2">
		<lottie-player 
		src="/anime/success.json"  background="transparent"  speed="0.8"  style="width: 100%; height: 300px;"    autoplay >
	</lottie-player>
</div>
</div>


<input type="hidden" value="{{json_encode($venda)}}" id="venda_edit" name="">
<div class="row" id="content" style="display: block">
	<div class="col s12">
		<div class="card">
			<div class="row">
				<div class="col s12">
					<h5 class="grey-text">DADOS INICIAIS</h5>
					<div class="row">
						<div class="col s4">
							<h6>Ultima NF-e: <strong>{{$lastNF}}</strong></h6>

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
								@if($venda->natureza->id == $n->id)
								selected
								@endif
								value="{{$n->id}}">{{$n->natureza}}</option>
								@endforeach
							</select>
							<label>
								Natureza de Operação
							</label>
						</div>

						@if(isset($listaPreco))

						<div class="input-field col s4">
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


						@endif
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
							<input disabled autocomplete="off" type="text" name="cliente" id="autocomplete-cliente" class="autocomplete-cliente">
							<label for="autocomplete-cliente">Cliente</label>
							@if($errors->has('cliente'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('cliente') }}</span>
							</div>
							@endif
						</div>
					</div>
				</div>
				<div class="row" id="cliente" style="display: block">
					<div class="col s12">
						<h4 class="center-align">CLIENTE SELECIONADO</h4>
						<div class="col s6">
							<h5>Razão Social: <strong id="razao_social" class="red-text">{{$venda->cliente->razao_social}}</strong></h5>
							<h5>Nome Fantasia: <strong id="nome_fantasia" class="red-text">
								{{$venda->cliente->nome_fantasia}}
							</strong></h5>
							<h5>Logradouro: <strong id="logradouro" class="red-text">{{$venda->cliente->rua}}</strong></h5>
							<h5>Numero: <strong id="numero" class="red-text">{{$venda->cliente->numero}}</strong></h5>
							<h5>Limite: <strong id="limite" class="red-text">{{$venda->cliente->limite_venda}}</strong></h5>
						</div>
						<div class="col s6">
							<h5>CPF/CNPJ: <strong id="cnpj" class="red-text">{{$venda->cliente->cpf_cnpj}}</strong></h5>
							<h5>RG/IE: <strong id="ie" class="red-text">{{$venda->cliente->ie_rg}}</strong></h5>
							<h5>Fone: <strong id="fone" class="red-text">{{$venda->cliente->telefone}}</strong></h5>
							<h5>Cidade: <strong id="cidade" class="red-text">{{$venda->cliente->cidade->nome}}</strong></h5>
							
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
										<option @if(isset($venda->frete) && $venda->frete->tipo == 0) selected @endif value="0">0 - Emitente</option>
										<option @if(isset($venda->frete) && $venda->frete->tipo == 1) selected @endif value="1">1 - Destinatário</option>
										<option @if(isset($venda->frete) && $venda->frete->tipo == 2) selected @endif   value="2">2 - Terceiros</option>
										<option @if(!isset($venda->frete)) selected @endif value="9">9 - Sem Frete</option>
									</select>
									<label>Tipo Frete</label>
								</div>


								<div class="col s2 input-field">
									<input type="text" id="placa" value="{{$venda->frete ? $venda->frete->placa : ''}}" class="upper-input">
									<label>Placa Veiculo</label>
								</div>

								<div class="col s1 input-field">
									<select id="uf_placa">
										<option value="--">--</option>
										<option @if(isset($venda->frete) && $venda->frete->uf == 'AC') selected @endif value="AC">AC</option>
										<option @if(isset($venda->frete) && $venda->frete->uf == 'AL') selected @endif value="AL">AL</option>
										<option @if(isset($venda->frete) && $venda->frete->uf == 'AM') selected @endif value="AM">AM</option>
										<option @if(isset($venda->frete) && $venda->frete->uf == 'AP') selected @endif value="AP">AP</option>
										<option @if(isset($venda->frete) && $venda->frete->uf == 'BA') selected @endif value="BA">BA</option>
										<option @if(isset($venda->frete) && $venda->frete->uf == 'CE') selected @endif value="CE">CE</option>
										<option @if(isset($venda->frete) && $venda->frete->uf == 'DF') selected @endif value="DF">DF</option>
										<option @if(isset($venda->frete) && $venda->frete->uf == 'ES') selected @endif value="ES">ES</option>
										<option @if(isset($venda->frete) && $venda->frete->uf == 'GO') selected @endif value="GO">GO</option>
										<option @if(isset($venda->frete) && $venda->frete->uf == 'MA') selected @endif value="MA">MA</option>
										<option @if(isset($venda->frete) && $venda->frete->uf == 'MG') selected @endif value="MG">MG</option>
										<option @if(isset($venda->frete) && $venda->frete->uf == 'MS') selected @endif value="MS">MS</option>
										<option @if(isset($venda->frete) && $venda->frete->uf == 'MT') selected @endif value="MT">MT</option>
										<option @if(isset($venda->frete) && $venda->frete->uf == 'PA') selected @endif value="PA">PA</option>
										<option @if(isset($venda->frete) && $venda->frete->uf == 'PB') selected @endif value="PB">PB</option>
										<option @if(isset($venda->frete) && $venda->frete->uf == 'PE') selected @endif value="PE">PE</option>
										<option @if(isset($venda->frete) && $venda->frete->uf == 'PI') selected @endif value="PI">PI</option>
										<option @if(isset($venda->frete) && $venda->frete->uf == 'PR') selected @endif value="PR">PR</option>
										<option @if(isset($venda->frete) && $venda->frete->uf == 'RJ') selected @endif value="RJ">RJ</option>
										<option @if(isset($venda->frete) && $venda->frete->uf == 'RN') selected @endif value="RN">RN</option>
										<option @if(isset($venda->frete) && $venda->frete->uf == 'RS') selected @endif value="RS">RS</option>
										<option @if(isset($venda->frete) && $venda->frete->uf == 'RO') selected @endif value="RO">RO</option>
										<option @if(isset($venda->frete) && $venda->frete->uf == 'RR') selected @endif value="RR">RR</option>
										<option @if(isset($venda->frete) && $venda->frete->uf == 'SC') selected @endif value="SC">SC</option>
										<option @if(isset($venda->frete) && $venda->frete->uf == 'SE') selected @endif value="SE">SE</option>
										<option @if(isset($venda->frete) && $venda->frete->uf == 'SP') selected @endif value="SP">SP</option>
										<option @if(isset($venda->frete) && $venda->frete->uf == 'TO') selected @endif value="TO">TO</option>
									</select>
									<label>UF</label>

								</div>
								<div class="col s2 input-field">
									<input id="valor_frete" value="{{$venda->frete ? $venda->frete->valor : ''}}" type="text">
									<label>Valor</label>
								</div>
							</div>

							<div class="col s12">
								<h5 class="grey-text">VOLUME</h5>
							</div>
							<div class="row">

								<div class="col s3 input-field">
									<input value="{{$venda->frete ? $venda->frete->especie : ''}}" id="especie" type="text">
									<label>Espécie</label>
								</div>

								<div class="col s2 input-field">
									<input value="{{$venda->frete ? $venda->frete->numeracaoVolumes : ''}}" id="numeracaoVol" type="text">
									<label>Nuneração de Volumes</label>
								</div>
								<div class="col s2 input-field">
									<input value="{{$venda->frete ? $venda->frete->qtdVolumes : ''}}" id="qtdVol" type="text">
									<label>Quantidade de Volumes</label>
								</div>

								<div class="col s2 input-field">
									<input value="{{$venda->frete ? $venda->frete->peso_liquido : ''}}" id="pesoL" type="text">
									<label>Peso Liquido</label>
								</div>

								<div class="col s2 input-field">
									<input value="{{$venda->frete ? $venda->frete->peso_bruto : ''}}" id="pesoB" type="text">
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
											@if($venda->tipo_pagamento == $key)
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
											<option @if($venda->forma_pagamento == 'a_vista') selected @endif value="a_vista">A vista</option>
											<option @if($venda->forma_pagamento == '30_dias') selected @endif value="30_dias">30 Dias</option>
											<option @if($venda->forma_pagamento == 'personalizado') selected @endif value="personalizado">Personalizado</option>
											<option @if($venda->forma_pagamento == 'conta_crediario') selected @endif value="conta_crediario">Conta crediario</option>
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
						<input type="text" id="desconto" value="{{$venda->desconto > 0 ? $venda->desconto : ''}}">
						<label>Desconto</label>
					</div>

					<div class="col s5 input-field"><br>
						<input type="text" id="obs" value="{{$venda->observacao}}" name="">
						<label>Informação Adicional</label>
					</div>

				</div>

				<div class="row">
					<div class="col s6">
						<a style="width: 100%;" href="#" onclick="salvarOrcamento()" class="btn-large blue accent-3 @if(isset($venda)) disabled @endif)">Salvar como Orçamento</a>
					</div>
					<div class="col s6">
						<a id="salvar-venda" style="width: 100%;" href="#" @if(isset($venda)) onclick="atualizarVenda('nfe')" @else onclick="salvarVenda('nfe')" @endif class="btn-large green accent-3 disabled">
							@if(isset($venda))
							Atualizar Venda
							@else
							Salvar Venda
							@endif
						</a>
						
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