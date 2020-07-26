@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">
		<div class="card">
			<div class="card-content">
				<div class="row">
					<h1>Cliente: {{$pedido->cliente->nome}}</h1>
					<h1>Soma: {{number_format($pedido->somaItens(), 2)}}</h1>
				</div>

				<div class="row">
					<table>
						<thead>
							<tr>
								<th>Produto</th>
								<th>Quantidade</th>
								<th>V. Unit</th>
								<th>SubTotal</th>
								<th>Adicionais</th>
							</tr>
						</thead>
						<tbody>
							@foreach($pedido->itens as $i)
							<tr>
								<td>
									@if(count($i->sabores) > 0)
									@foreach($i->sabores as $key => $sb)
									<strong>{{$sb->produto->produto->nome}}</strong>
									@if($key < count($i->sabores)-1) | @endif
									@endforeach
									@else
									<strong>{{$i->produto->produto->nome}}</strong>
									@endif
								</td>
								<td>{{$i->quantidade}}</td>


								<?php 
								$valor = 0; 
								$valorMaisAdd = 0;
								if(count($i->sabores) > 0){
									foreach($i->sabores as $it){
										$v = $it->maiorValor($it->produto->id, $i->tamanho_id);
										if($v > $valor) $valor = $v;
									}
								}else{
									$valor = $i->produto->valor;
								}

								if(count($i->itensAdicionais) > 0){
									foreach($i->itensAdicionais as $it){
										$valorMaisAdd = $valor + ($i->quantidade * $it->adicional->valor);
									}
								}else{
									$valorMaisAdd = $valor;
								}

								?>


								<td>{{number_format($valor,2)}}</td>

								<td>{{number_format($valorMaisAdd, 2)}}</td>

								<td>
									@if(count($i->itensAdicionais)>0)
									@foreach($i->itensAdicionais as $a)
									<strong>{{$a->adicional->nome}}</strong>

									@endforeach
									@else
									--
									@endif
								</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>

				<p>Abertura do carrinho: <strong>{{\Carbon\Carbon::parse($pedido->created_at)->format('d/m/Y H:i:s')}}</strong></p>
				@if(count($pedido->cliente->tokens) > 0)
				<a class="btn" href="/pedidosDelivery/push/{{$pedido->id}}">
					<i class="material-icons left">notifications</i>
					Enviar push App
				</a>
				@endif
			</div>
		</div>
	</div>
</div>
@endsection	