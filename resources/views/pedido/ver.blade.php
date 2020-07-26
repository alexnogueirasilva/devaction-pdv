@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">
		@if(session()->has('message'))
		<div class="row">
			<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
				<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
			</div>
		</div>
		@endif

		<h3>Comanda: <strong class="red-text">{{$pedido->comanda}}</strong></h3>
		@if($pedido->mesa_id != NULL)
		<h3>Mesa: <strong class="red-text">{{$pedido->mesa->nome}}</strong></h3>
		@else
		<h3>Mesa: <strong class="red-text">Avulsa</strong></h3>
		@endif
		<input type="hidden" id="DIVISAO_VALOR_PIZZA" value="{{getenv('DIVISAO_VALOR_PIZZA')}}" name="">
		@if($pedido->observacao != '')
		<h5>Observação: <strong>{{$pedido->observacao}}</strong></h5>
		@endif
		<form class="col s12" method="post" action="/pedidos/saveItem">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" id="pedido_id" name="id" value="{{$pedido->id}}">
			<br>

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
					<input type="text" value="0" id="valor" name="valor">
					<label>Valor</label>
					@if($errors->has('valor'))
					<div class="center-align red lighten-2">
						<span class="white-text">{{ $errors->first('valor') }}</span>
					</div>
					@endif
				</div>


				<div class="col s2">
					<button type="submit" class="btn-large green accent-3">
						<i class="material-icons">add</i>
					</button>
				</div>
			</div>
		</div>
	</form>

	<div class="row">


		@if(sizeof($pedido->itens) > 0)
		<div class="col s3">
			<a href="/pedidos/imprimirPedido/{{$pedido->id}}" target="_blank" class="btn brown" style="width: 100%">Imprimir pedido</a>
		</div>

		<div class="col s3">
			<a onclick="imprimirItens()" target="_blank" class="btn red" style="width: 100%">Imprimir itens</a>
		</div>

		@endif

	</div>
	<div class="row">
		<table class="striped col s12">
			<thead>
				<tr>
					<th>#</th>

					<th>Produto</th>
					<th>Tamanho de Pizza</th>
					<th>Sabores</th>
					<th>Adicionais</th>
					<th>Status</th>
					<th>Valor</th>
					<th>Quantidade</th>
					<th>Subtotal+adicional</th>
					<th>Observação</th>
					<th>Ações</th>
				</tr>
			</thead>

			<?php $finalizado = 0; $pendente = 0; ?>
			<tbody id="body">
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
					<td>{{$i->produto->nome}}</td>


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

					$valorVenda = $i->valor;

					?>

					<td>{{number_format($valorVenda, 2, ',', '.')}}</td>

					<td>{{$temp->quantidade}}</td>
					<td>{{number_format((($valorVenda * $i->quantidade) + $somaAdicionais), 2, ',', '.')}}</td>
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
						<a onclick = "if (! confirm('Deseja excluir este registro?')) { return false; }" href="/pedidos/deleteItem/{{$i->id}}">
							<i class="material-icons left red-text">delete</i>					
						</a>
						@if(!$i->status)
						<a href="/pedidos/alterarStatus/{{$i->id}}">
							<i class="material-icons green-text">check</i>
						</a>
						@endif
					</td>

					<?php 
					if($i->status) $finalizado++;
					else $pendente++;
					?>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	<div class="row">
		<div class="input-field col s4">
			<i class="material-icons prefix">map</i>
			<select id="bairro" name="bairro">
				<option value="0">Selecione o Bairro</option>
				@foreach($bairros as $b)
				<option @if($pedido->bairro_id == $b->id) selected @endif value="{{$b->id}}">{{$b->nome}} - R$ {{$b->valor_entrega}}</option>
				@endforeach
			</select>
			<label for="bairro">Bairro</label>

		</div>
		<div class="input-field col s1">
			<a href="#modal-endereco" class="btn modal-trigger blue accent-3">
				<i class="material-icons">add_location</i>
			</a>
		</div>

	</div>

	@if($pedido->rua != '')
	<div class="col s12">
		<p>Nome: <strong class="red-text">{{$pedido->nome}}</strong></p>
		<p>Rua: <strong class="red-text">{{$pedido->rua}}, {{$pedido->numero}}</strong>, Telefone: <strong class="red-text">{{$pedido->telefone}}</strong></p>
		<p>Refêrencia: <strong class="red-text">{{$pedido->referencia}}</strong></p>
	</div>
	@endif

	<div class="row">
		<div class="col s12">
			<h5>TOTAL PRODUTOS: <strong class="green-text">{{number_format($pedido->somaItems(), 2, ',', '.')}}</strong></h5>
			@if($pedido->bairro_id != null)
			<h5>ENTREGA: <strong class="red-text">{{number_format($pedido->bairro->valor_entrega, 2, ',', '.')}}</strong></h5>
			<h4>TOTAL GERAL: <strong class="red-text">{{number_format($pedido->somaItems() + $pedido->bairro->valor_entrega, 2, ',', '.')}}</strong></h4>
			@endif

			<h5>ITENS FINALIZADOS: <strong class="green-text">{{$finalizado}}</strong></h5>
			<h5>ITENS PENDENTES: <strong class="red-text">{{$pendente}}</strong></h5>
		</div>
		<br>

		

		<div class="row">
			<div class="col s12">

				<a class="btn-large @if($pendente > 0 || $pedido->status) disabled @endif green accent-4" href="/pedidos/finalizar/{{$pedido->id}}">Finalizar</a>
			</div>
		</div>
		<input type="hidden" id="_token" value="{{csrf_token()}}">
	</div>
</div>
</div>


</div>

<div id="modal1" class="modal">
	<div class="modal-content">
		<h4>Envio de SMS</h4>

		<div class="row">
			<div class="input-field col s6">
				<input type="text" id="numero_sms">
				<label for="correcao">Numero</label>
			</div>
		</div>

		<div class="row">
			<div class="input-field col s12">
				<textarea id="msg_sms" class="materialize-textarea"></textarea>
				<label for="msg_sms">Mensagem</label>
			</div>
		</div>

		<div class="row" id="preloader1" style="display: none">
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
	<div class="modal-footer">
		<a href="#!" class="modal-action modal-close btn grey">Fechar</a>
		<button onclick="sendSms()" class="btn blue">enviar</button>
	</div>
</div>

<div id="modal2" class="modal">

	<div class="modal-content">

		<div class="row">
			<div class="input-field col s4">
				<input type="text" id="numero_whats">
				<label>WhatsApp</label>
			</div>
		</div>

		<div class="row">
			<div class="input-field col s12">
				<input type="text" id="msg_whats">
				<label>Texto</label>
			</div>
		</div>

	</div>
	<div class="modal-footer">
		<a href="#!" onclick="enviarWhatsApp()" class="btn modal-action waves-effect waves-green green">Enviar</a>
		<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Fechar</a>
	</div>
</div>

<div id="modal-endereco" class="modal">

	<form method="get" action="/pedidos/setarEndereco">
		<div class="modal-content">
			<h4>Endereço</h4>
			<input type="hidden" name="pedido_id" value="{{$pedido->id}}">
			<div class="row">
				<div class="input-field col s10">
					<input type="text" name="nome" value="{{$pedido->nome}}" id="nome" data-length="50">
					<label>Nome</label>
				</div>
				<div class="input-field col s8">
					<input type="text" name="rua" value="{{$pedido->rua}}" id="rua" data-length="50">
					<label>Rua</label>
				</div>
				<div class="input-field col s2">
					<input type="text" name="numero" value="{{$pedido->numero}}" id="numero" data-length="10">
					<label>Número</label>
				</div>
			</div>

			<div class="row">
				<div class="input-field col s5">
					<input type="text" name="referencia" value="{{$pedido->referencia}}" id="referencia" data-length="30">
					<label>Referência</label>
				</div>
				<div class="input-field col s5">
					<input type="text" name="telefone" value="{{$pedido->telefone}}" id="telefone" data-length="15">
					<label>Telefone</label>
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