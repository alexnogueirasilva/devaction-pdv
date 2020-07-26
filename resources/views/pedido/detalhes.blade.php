@extends('default.layout')
@section('content')

<div class="row">

	<div class="col s12">
		<h3 class="centera-lign">Itens Da Comanda</h3>
		<table class="striped">
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

				// if(count($i->sabores) > 0){
				// 	$maiorValor = 0;
				// 	foreach($i->sabores as $s){
				// 		$v = $s->maiorValor($s->sabor_id, $i->tamanho_pizza_id);
				// 		if($v > $maiorValor) $maiorValor = $v;
				// 	}
				// 	$valorVenda = $maiorValor;
				// }else if(isset($i->produto->produto) && $i->produto->produto->valor_venda > 0){
				// 	$valorVenda = $i->produto->produto->valor_venda;
				// }else{
				// 	$valorVenda = $i->produto->valor_venda;
				// }


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

	<div class="col s12">
		<h3 class="centera-lign">Itens Removidos</h3>
		<table class="striped">
			<thead>
				<tr>
					<th>Produto</th>
					<th>Quantidade</th>
					<th>Valor</th>
					<th>Data de Inserção</th>
					<th>Data de Remoção</th>

				</tr>
			</thead>

			<tbody id="body">
				@foreach($removidos as $r)
				<tr>
					<td>{{$r->produto}}</td>
					<td>{{number_format($r->quantidade, 2)}}</td>
					<td>{{number_format($r->valor, 2)}}</td>
					<td>{{$r->data_insercao}}</td>
					<td>{{ \Carbon\Carbon::parse($r->updated_at)->format('d/m/Y H:i:s')}}</td>

				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endsection	