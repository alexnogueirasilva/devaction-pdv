@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">

		<div class="row">
			<div class="">
				<div class="row">

					@if(session()->has('message'))
					<div class="row">
						<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
							<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
						</div>
					</div>
					@endif

					<h3 class="center-align">Orçamento código: <strong>{{$orcamento->id}}</strong></h3>

					<h4>Cliente: <strong class="red-text">{{$orcamento->cliente->razao_social}}</strong></h4>
					<h5>CNPJ: <strong class="red-text">{{$orcamento->cliente->cpf_cnpj}}</strong></h5>
					<h5>Data: <strong class="red-text">{{ \Carbon\Carbon::parse($orcamento->created_at)->format('d/m/Y H:i:s')}}</strong></h5>
					<h5>Valor Total: <strong class="red-text">{{ number_format($orcamento->valor_total, 2, ',', '.') }}</strong></h5>
					<h5>Cidade: <strong class="red-text">{{ $orcamento->cliente->cidade->nome }} ({{ $orcamento->cliente->cidade->uf }})</strong></h5>
					<h5>Dias restantes para o vencimento: <strong class="red-text">{{ $diasParaVencimento }}</strong></h5>

					<h4>Estado: 
						@if($orcamento->estado == 'NOVO')
						<strong class="blue-text">NOVO</strong>
						@elseif($orcamento->estado == 'APROVADO')
						<strong class="green-text">APROVADO</strong>
						@else
						<strong class="red-text">REPROVADO</strong>
						@endif
					</h4>

					<form class="row" method="post" action="/orcamentoVenda/setValidade">
						@csrf
						<input type="hidden" name="orcamento_id" value="{{$orcamento->id}}">
						<div class="col s2 input-field">
							<input @if($orcamento->estado != 'NOVO') disabled @endif type="text" name="validade" class="datepicker" value="{{ \Carbon\Carbon::parse($orcamento->validade)->format('d/m/Y')}}">
							<label>Data de validade</label>
						</div>
						<div class="col s1">
							<button @if($orcamento->estado != 'NOVO') disabled @endif id="addProd" class="btn-large accent-3">
								<i class="material-icons">date_range</i>
							</button>
						</div>
					</form>
				</div>

				<div class="divider"></div>

				<div class="card">
					<div class="card-content">

						<form class="row" method="post" action="/orcamentoVenda/addItem">
							@csrf
							<input type="hidden" name="orcamento_id" value="{{$orcamento->id}}">
							<div class="col s12">
								<div class="input-field col s4">
									<i class="material-icons prefix">inbox</i>
									<input @if($orcamento->estado != 'NOVO') disabled @endif autocomplete="off" type="text" name="produto" id="autocomplete-produto" class="autocomplete-produto">
									<label for="autocomplete-produto">Produto</label>

								</div>

								<div class="col s2 input-field">
									<input @if($orcamento->estado != 'NOVO') disabled @endif type="text" id="quantidade" value="0" name="quantidade">
									<label>Quantidade</label>
								</div>

								<div class="col s2 input-field">
									<input @if($orcamento->estado != 'NOVO') disabled @endif type="text" id="valor" name="valor" value="0">
									<label>Valor Unitário</label>
								</div>

								<div class="col s2 input-field">
									<input @if($orcamento->estado != 'NOVO') disabled @endif type="text" id="subtotal" value="0" disabled="">
									<label>Subtotal</label>
								</div>

								<div class="col s1">
									<button @if($orcamento->estado != 'NOVO') disabled @endif id="addProd" class="btn-large green accent-3">
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
						</form>
						<div class="row">
							<h5 class="cyan-text">Itens da NF</h5>

							<table>
								<thead>
									<tr>
										<th>#</th>
										<th>Produto</th>
										<th>Quantidade</th>
										<th>Valor</th>
										<th>Subtotal</th>
										<th>Ações</th>
									</tr>
								</thead>
								<tbody>
									<?php $somaItens = 0; ?>
									@foreach($orcamento->itens as $i)

									<tr>
										<td>{{$i->id}}</td>
										<td>{{$i->produto->nome}}</td>
										<td>{{$i->quantidade}}</td>
										<td>{{number_format($i->valor, 2, ',', '.')}}</td>
										<td>{{number_format($i->valor*$i->quantidade, 2, ',', '.')}}</td>
										<td>
											@if($orcamento->estado == 'NOVO') 
											<a href="/orcamentoVenda/deleteItem/{{$i->id}}">
												<i class="material-icons red-text">delete</i>
											</a>
											@endif
										</td>
									</tr>
									<?php $somaItens+=  $i->valor * $i->quantidade?>
									@endforeach
									<tr>
										<td colspan="4">Soma dos Itens</td>
										<td>{{number_format($somaItens, 2, ',', '.')}}</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>


				<div class="divider"></div>

				<div class="card">
					<div class="card-content">
						<div class="row">

							<form class="row" method="post" action="/orcamentoVenda/addPag">

								@csrf
								<input type="hidden" name="orcamento_id" value="{{$orcamento->id}}">

								

								<div class="col s2 input-field">
									<input @if($orcamento->estado != 'NOVO') disabled @endif type="text" class="money" id="valor" name="valor" value="">
									<label>Valor</label>
								</div>

								<div class="col s2 input-field">
									<input @if($orcamento->estado != 'NOVO') disabled @endif type="text" name="data" class="datepicker" >
									<label>Data de vencimento</label>
								</div>

								<div class="col s1">
									<button @if($orcamento->estado != 'NOVO') disabled @endif id="addProd" class="btn-large green accent-3">
										<i class="material-icons">date_range</i>

									</button>
								</div>


							</form>
							<p>Forma de pagamento: <strong class="red-text">{{$orcamento->forma_pagamento}}</strong></p>

							<table>
								<thead>
									<tr>
										<th>Vencimento</th>
										<th>Valor</th>
										<th>Ações</th>

									</tr>
								</thead>
								@if(count($orcamento->duplicatas))
								<tbody>
									<?php $soma = 0; ?>
									@foreach($orcamento->duplicatas as $dp)

									<tr>
										<td>{{ \Carbon\Carbon::parse($dp->vencimento)->format('d/m/Y')}}</td>
										<td>{{number_format($dp->valor, 2, ',', '.')}}</td>
										<td>
											@if($orcamento->estado == 'NOVO') 
											<a href="/orcamentoVenda/deleteParcela/{{$dp->id}}">
												<i class="material-icons red-text">delete</i>
											</a>
											@endif
										</td>
									</tr>
									<?php $soma += $dp->valor; ?>
									@endforeach
								</tbody>
								<tr>
									<td>Soma Fatura</td>
									<td>{{number_format($soma, 2, ',', '.')}}</td>
								</tr>
								@else
								<tbody>
									<tr>
										<td>--</td>
										<td>--</td>
										<td>--</td>
										
									</tr>
								</tbody>
								@endif
							</table>
						</form>
					</div>
				</div>

				<div class="row">
					<div class="col s12">

						<a target="_blank" href="/orcamentoVenda/imprimir/{{$orcamento->id}}" class="btn blue">
							<i class="material-icons left">print</i>
							Imprimir
						</a>

						<a @if($orcamento->estado != 'NOVO') disabled @endif href="/orcamentoVenda/reprovar/{{$orcamento->id}}" class="btn red">
							<i class="material-icons left">close</i>
							Alterar para reprovado
						</a>

					</div>
				</div>

				<div class="row">
					<form method="get" action="/orcamentoVenda/enviarEmail">
						<input type="hidden" name="id" value="{{$orcamento->id}}">
						<input type="hidden" name="redirect" value="true">
						<div class="col s4 input-field">
							<input name="email" type="email">
							<label>Email</label>
						</div>
						<div class="col s4 input-field">
							<button class="btn">Enivar email</button>
						</div>
					</form>
				</div>

				<div class="divider"></div>


				<br>

			</div>
		</div>

		<div class="card">
			<div class="card-content">
				<form method="post" action="/orcamentoVenda/gerarVenda">
					@csrf
					<input type="hidden" name="orcamento_id" value="{{$orcamento->id}}">
					<h5>Frete</h5>
					<div class="row">

						<div class="col s3 input-field">
							<select id="frete" name="tipo_frete">
								<option @if($orcamento->frete_id != null) selected @endif value="0">0 - Emitente</option>
								<option value="1">1 - Destinatário</option>
								<option value="2">2 - Terceiros</option>
								<option @if($orcamento->frete_id == null) selected @endif value="9">9 - Sem Frete</option>
							</select>
							<label>Tipo Frete</label>
						</div>


						<div class="col s2 input-field">
							<input type="text" id="placa" name="placa" class="upper-input">
							<label>Placa Veiculo</label>
						</div>

						<div class="col s1 input-field">
							<select name="uf_placa" id="uf_placa">
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
							<input name="valor_frete" class="money" type="text">
							<label>Valor</label>
						</div>

					</div>

					<h5>Natureza de Operação</h5>
					<div class="row">

						<div class="col s4 input-field">
							<select name="natureza" id="natureza">
								@foreach($naturezas as $n)
								<option @if($n->id == $orcamento->natureza_id) selected @endif value="{{$n->id}}">{{$n->natureza}}</option>
								@endforeach
							</select>
							<label>Natureza de Operação</label>
						</div>


					</div>

					<button @if(!$orcamento->validaGerarVenda() || $orcamento->estado != 'NOVO') disabled @endif type="submit" class="btn">
						<i class="material-icons left">shopping_basket</i>
						Gerar Venda
					</button>
				</form>
			</div>
		</div>


	</div>
</div>
@endsection	