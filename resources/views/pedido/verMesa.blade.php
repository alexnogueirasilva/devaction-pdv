@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">
		<h2><strong class="red-text">{{$mesa->nome}}</strong></h2>

	</div>

</div>

@if(sizeof($pedidos) > 0)
@foreach($pedidos as $p)
<?php $pedido = $p; ?>
<div class="row">


	<div class="col s12">
		@if(session()->has('message'))
		<div class="row">
			<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
				<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
			</div>
		</div>
		@endif

		<h3>Comanda: <strong class="red-text">{{$pedido->comanda != '' ? $pedido->comanda : '*'}}</strong></h3>
		

		<div class="row">


			@if(sizeof($pedido->itens) > 0)
			<div class="col s3">
				<a href="/pedidos/imprimirPedido/{{$pedido->id}}" target="_blank" class="btn brown" style="width: 100%">Imprimir pedido</a>
			</div>

			<!-- <div class="col s3">
				<a onclick="imprimirItens()" target="_blank" class="btn red" style="width: 100%">Imprimir itens</a>
			</div> -->

			@endif

		</div>
		<div class="row">
			<table class="striped col s12">
				<thead>
					<tr>
						<!-- <th>#</th> -->

						<th>Produto</th>
						<th>Tamanho de Pizza</th>
						<th>Sabores</th>
						<th>Adicionais</th>
						<th>Status</th>
						<th>Valor</th>
						<th>Quantidade</th>
						<th>Subtotal+adicional</th>
						<th>Observação</th>

					</tr>
				</thead>

				<?php $finalizado = 0; $pendente = 0; ?>
				<tbody id="body">
					@foreach($pedido->itens as $i)
					<tr>
						<?php $temp = $i; ?>

						<!-- <td id="checkbox">

							<p>
								<input type="checkbox" class="check" @if($i->impresso == 0) checked @endif id="item_{{$i->id}}" />
								<label for="item_{{$i->id}}"></label>
							</p>

						</td> -->
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

					<a class="btn-large green accent-4" href="/pedidos/finalizar/{{$pedido->id}}">Finalizar</a>
				</div>
			</div>

			<input type="hidden" id="_token" value="{{csrf_token()}}">
		</div>
	</div>
</div>

<hr>

@endforeach
@else
<hr>
<h4 class="center-align">Nada encontrado!!</h4>
@endif



@endsection	