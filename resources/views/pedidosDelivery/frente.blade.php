@extends('default.layout')
@section('content')

<div class="row">

	<div class="col s12">
		<div class="card">
			<div class="card-content">
				<input type="hidden" value="{{getenv('DIVISAO_VALOR_PIZZA')}}" id="DIVISAO_VALOR_PIZZA">
				<h4 class="center-align">Frente de Pedido Delivery</h4>
				@if(session()->has('message'))
				<div class="row">
					<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
						<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
					</div>
				</div>
				@endif
				<div class="row">
					@if(!isset($pedido))
					<div class="input-field col s4">
						<i class="material-icons prefix">person</i>
						<input autocomplete="off" type="text" value="{{old('cliente')}}" name="cliente" id="autocomplete-cliente" class="autocomplete-cliente">
						<label for="autocomplete-cliente">Cliente Delivery</label>
						@if($errors->has('cliente'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('cliente') }}</span>
						</div>
						@endif
					</div>
					<div class="col s1">
						<a class="btn-floating btn-large waves-effect waves-light modal-trigger green" href="#modal-cliente">
							<i class="material-icons">add</i>
						</a>
					</div>
					@else

					<div class="col s4">
						<h6>Cliente: <strong class="red-text">{{$pedido->cliente->nome}} {{$pedido->cliente->sobre_nome}}</strong></h6>
						<h6>Celular: <strong class="red-text">{{$pedido->cliente->celular}}</strong></h6>
					</div>

					@endif

					<div class="input-field col s5">
						<select @if(!isset($pedido)) disabled @endif id="endereco">
							<option value="NULL">Balcão</option>
							@if(isset($pedido))
							@foreach($pedido->cliente->enderecos as $e)
							<option value="{{$e->id}}"
								@if(isset($pedido))
								@if($pedido->endereco_id == $e->id)
								selected
								@endif
								@endif
								>{{$e->rua}}, {{$e->numero}} - {{$e->bairro()}}</option>
								@endforeach
								@endif
							</select>
							<label>Endereço</label>
						</div>
						<div class="col s1">
							<a class="btn-floating btn-large waves-effect waves-light modal-trigger red @if(!isset($pedido)) disabled @endif" href="#modal-endereco">
								<i class="material-icons">add</i>
							</a>
						</div>


					</div>


					@if(isset($pedido) && $pedido->endereco)
					<div class="row">
						<div class="card">
							<div class="card-content">

								<h6>Rua: <strong class="red-text">{{$pedido->endereco->rua}}, {{$pedido->endereco->numero}}</strong> - Bairro: <strong class="red-text">{{$pedido->endereco->bairro()}}</strong></h6>
								<h6>Refenrêcia: <strong class="red-text">{{$pedido->endereco->referencia ?? '--'}}</strong></h6>
							</div>
						</div>
					</div>
					@endif
				</div>
			</div>
		</div>
		<div class="col s12">
			<div class="card">

				<div class="card-content">

					<div class="row">
						<form class="col s12" method="post" action="/pedidosDelivery/saveItemCaixa">
							<input type="hidden" id="token" name="_token" value="{{ csrf_token() }}">
							<input type="hidden" id="pedido_id" name="pedido_id" value="{{{ isset($pedido) ? $pedido->id : 0 }}}">

							<div class="row">
								<div>

									<div class="input-field col s4" style="margin-top: 70px;">
										<i class="material-icons prefix">inbox</i>
										<input autocomplete="off" type="text" value="{{old('produto')}}" name="produto" id="autocomplete-produto" class="autocomplete-produto">
										<label for="autocomplete-produto">Produto</label>
										@if($errors->has('produto'))
										<div class="center-align red lighten-2">
											<span class="white-text">{{ $errors->first('produto') }}</span>
										</div>
										@endif
									</div>
								</div>

								<div style="display: none;" id="tamanhos-pizza">
									<div class="">
										<div class="col s4">
											<i class="material-icons prefix">local_pizza</i>

											<div class="chips" id="tamanhos">

											</div>
											<label>Tamanhos de Pizza</label>
											@if($errors->has('tamanho_pizza_id'))
											<div class="center-align red lighten-2">
												<span class="white-text">{{ $errors->first('tamanho_pizza_id') }}</span>
											</div>
											@endif
										</div>
									</div>
								</div>


								<div style="display: none;" id="sabores-pizza" >

									<div class="col s4" style="margin-top: 35px;">
										<i class="material-icons prefix">local_pizza</i>

										<div id="sabores" class="chips chips-autocomplete">
										</div>
										<label>Sabores adicionais</label>
									</div>

								</div>
							</div>

							<input type="hidden" name="tamanho_pizza_id" id="tamanho_pizza_id">
							<input type="hidden" name="sabores_escolhidos" id="sabores_escolhidos">
							<input type="hidden" name="adicioanis_escolhidos" id="adicioanis_escolhidos">



							<div class="row">
								<div class="col s4">
									<i class="material-icons prefix">room_service</i>

									<div class="chips chips-autocomplete" id="adicionais">
									</div>
									<label>Adicionais para este produto</label>
								</div>

								<div class="input-field col s6" style="margin-top: 30px;">
									<i class="material-icons prefix">note</i>
									<input type="text" id="observacao" name="observacao">
									<label>Observação</label>
								</div>
							</div>



							<div class="row">

								<div class="input-field col s2">
									<i class="material-icons prefix">exposure_plus_1</i>
									<input type="text" value="1000" id="quantidade" name="quantidade">
									<label>Quantidade</label>
									@if($errors->has('quantidade'))
									<div class="center-align red lighten-2">
										<span class="white-text">{{ $errors->first('quantidade') }}</span>
									</div>
									@endif
								</div>

								<div class="input-field col s2">
									<i class="material-icons prefix">attach_money</i>
									<input disabled type="text" value="0" id="valor" name="valor">
									<label>Valor</label>
									@if($errors->has('valor'))
									<div class="center-align red lighten-2">
										<span class="white-text">{{ $errors->first('valor') }}</span>
									</div>
									@endif
								</div>


								<div class="col s2">
									<button type="submit" class="btn-large green accent-3 @if(!isset($pedido)) disabled @endif">
										<i class="material-icons">add</i>
									</button>
								</div>
							</div>

						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="col s12">
			<div class="card">

				<div class="card-content">
					<h4 class="center-align">Produtos do Pedido</h4>

					<div class="row">
						<table>
							<thead>
								<tr>
									<th>#</th>

									<th>Produto</th>
									<th>Tamanho de Pizza</th>
									<th>Sabores</th>
									<th>Adicionais</th>
									<th>Status</th>

									<th>Quantidade</th>
									<th>Subtotal+adicional</th>
									<th>Observação</th>
									<th>Ações</th>
								</tr>
							</thead>

							<?php $finalizado = 0; $pendente = 0; $soma = 0; ?>
							<tbody id="body">
								@if(isset($pedido))
								@foreach($pedido->itens as $i)
								<tr>
									<?php $temp = $i; ?>

									<td id="checkbox">

										<p>
											<input type="checkbox" class="check" @if($i->impresso == 0) checked @endif id="item_{{$i->id}}" />
											<label for="item_{{$i->id}}"></label>
										</p>

									</td>
									<td style="display: none" id="item_id">{{$i->id}}</td>
									<td>{{$i->produto->produto->nome}}</td>


									@if(!empty($i->tamanho))
									<td>{{$i->tamanho->nome}}</td>
									@else
									<td>--</td>
									@endif


									@if(count($i->sabores) > 0)
									<td>
										@foreach($i->sabores as $key => $s)
										{{$s->produto->produto->nome}}
										@if($key < count($i->sabores)-1)
										| 
										@endif
										@endforeach
									</td>
									@else
									<td>--</td>
									@endif

									<?php $somaAdicionais = 0; ?>
									@if(count($i->itensAdicionais) > 0)
									<td>
										@foreach($i->itensAdicionais as $key => $a)
										{{$a->adicional->nome}}
										<?php $somaAdicionais += $a->adicional->valor * $i->quantidade?>
										@if($key < count($i->itensAdicionais)-1)
										| 
										@endif
										@endforeach
									</td>
									@else
									<td>--</td>
									@endif

									<td>
										@if($i->status)
										<i class="material-icons green-text">brightness_1</i>
										@else
										<i class="material-icons red-text">brightness_1</i>
										@endif
									</td>

									<?php 
									$valorVenda = 0;



									$valorVenda = $i->valorProduto();

									?>

									<?php $soma += $i->quantidade * $valorVenda; ?>

									<td>{{$i->quantidade}}</td>
									<td>{{number_format((($valorVenda * $i->quantidade)), 2, ',', '.')}}</td>
									<td>
										<a class="btn red lighten-2 tooltipped" data-position="bottom" data-delay="50" data-tooltip="{{$i->observacao}}"
											@if(empty($i->observacao))
											disabled
											@endif
											>
											<i class="material-icons">message</i>

										</a>
									</td>
									<td>
										<a onclick='swal("Atenção!", "Deseja remover este registro?", "warning").then((sim) => {if(sim){ location.href="/pedidosDelivery/deleteItem/{{$i->id}}" }else{return false} })' href="#!" >
											<i class="material-icons left red-text">delete</i>					
										</a>
										<!-- @if(!$i->status)
										<a href="/pedidosDelivery/alterarStatus/{{$i->id}}">
											<i class="material-icons green-text">check</i>
										</a>
										@endif -->
									</td>

									<?php 
									if($i->status) $finalizado++;
									else $pendente++;
									?>
								</tr>
								@endforeach
								@endif
							</tbody>
							<tfoot>
								<tr>
									<th colspan="7">Total</th>
									<th>R$ {{number_format($soma, 2)}}</th>
								</tr>
							</tfoot>

						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col s12">
				@if(isset($pedido))
				<form method="get" action="/pedidosDelivery/frenteComPedidoFinalizar">

					<div class="row">

						<div class="input-field col s2">
							<input type="text" name="taxa_entrega" value="{{{ $pedido->endereco_id != NULL ? $valorEntrega : 0 }}}" id="taxa_entrega" data-length="15">
							<label>Taxa de Entrega</label>
						</div>
					</div>



					<div class="row">
						<div class="input-field col s3">
							<input type="text" name="telefone" value="{{$pedido->cliente->celular}}" id="telefone" data-length="15">
							<label>Telefone</label>
						</div>


						<div class="input-field col s2">
							<input type="text" name="troco_para" value="0" id="troco_para" data-length="15">
							<label>Troco Para</label>
						</div>
					</div>

					<input type="hidden" value="{{$pedido->id}}" name="pedido_id">
					<button type="submit" @if(!isset($pedido) || sizeof($pedido->itens) == 0) disabled @endif class="btn-large green accent-3">Salvar</button>
				</form>
				@endif


			</div>
		</div>
	</div>




	<div id="modal-endereco" class="modal">

		<form method="post" action="/pedidosDelivery/novoEnderecoClienteCaixa">
			<div class="modal-content">
				<h4>Novo Endereço</h4>
				<input type="hidden" id="pedido_id" name="pedido_id" value="{{{ isset($pedido) ? $pedido->id : 0 }}}">
				<div class="row">
					@csrf
					<div class="input-field col s8">
						<input type="text" name="rua" id="rua" data-length="50">
						<label>Rua</label>
					</div>
					<div class="input-field col s2">
						<input type="text" name="numero" id="numero" data-length="10">
						<label>Número</label>
					</div>

					@if($config->usar_bairros)
					<div class="input-field col s10">
						<select name="bairro_id">
							@foreach($bairros as $b)
							<option value="{{$b->id}}">{{$b->nome}} R$ {{$b->valor_entrega}}</option>
							@endforeach
						</select>
						<label>Bairro</label>
					</div>
					@else
					<div class="input-field col s10">
						<input type="text" name="bairro" id="bairro" data-length="50">
						<label>Bairro</label>
					</div>
					@endif
				</div>

				<div class="row">
					<div class="input-field col s5">
						<input type="text" name="referencia" id="referencia" data-length="30">
						<label>Referência</label>
					</div>
				</div>

			</div>
			<div class="modal-footer">
				<a href="#!" class="modal-action modal-close red white-text waves-effect waves-green btn-flat">Fechar</a>
				<button href="#!" class="modal-action waves @if(!isset($pedido)) disabled @endif green accent-3 btn">Salvar</button>
			</div>
		</form>
	</div>

	<div id="modal-cliente" class="modal">

		<form method="post" action="/pedidosDelivery/novoClienteDeliveryCaixa">
			<div class="modal-content">
				<input type="hidden" id="token" name="_token" value="{{ csrf_token() }}">

				<h4>Novo Cliente</h4>
				<div class="row">
					<div class="input-field col s6">
						<input type="text" name="nome" id="nome" data-length="50">
						<label>Nome</label>
					</div>
					<div class="input-field col s6">
						<input type="text" name="sobre_nome" id="sobre_nome" data-length="50">
						<label>Sobre Nome</label>
					</div>
					<div class="input-field col s6">
						<input type="text" name="celular" id="celular" data-length="15">
						<label>Celular</label>
					</div>

					<div class="input-field col s6">
						<input type="password" name="senha" id="senha">
						<label>Senha</label>
					</div>
				</div>

			</div>
			<div class="modal-footer">
				<a href="#!" class="modal-action modal-close red white-text waves-effect waves-green btn-flat">Fechar</a>
				<button href="#!" class="modal-action waves green accent-3 btn">Salvar</button>
			</div>
		</form>
	</div>

	@endsection	
