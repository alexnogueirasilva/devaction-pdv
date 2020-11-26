@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">

		<h4>Lista de pedidos do cliente: <strong>{{$cliente->nome}}</strong></h4>

		<div class="row"></div>


		<div class="row">
			<div class="col s12">
				<label>Numero de registros: {{count($cliente->pedidos)}}</label>					
			</div>
			<table class="col s12">
				<thead>
					<tr>
						<th>#</th>
						<th>Valor</th>
						<th>Data</th>
						<th>Forma de Pagamento</th>
						<th>Estado</th>
						<th>Ações</th>
					</tr>
				</thead>

				<tbody>
					@foreach($cliente->pedidos as $p)
					<tr>
						<th>{{ $p->id }}</th>
						<th>{{ number_format($p->valor_total,2) }}</th>
						<th>{{ \Carbon\Carbon::parse($p->data_registro)->format('d/m/Y H:i:s')}}</th>
						<th>
							@if($p->forma_pagamento == 'dinheiro')
							Dinheiro
							@elseif($p->forma_pagamento == 'credito')
							Cartão de crédito
							@else
							Cartão de débito
							@endif

						</th>
						<th>
							@if($p->estado == 'nv')
							<strong class="blue-text">NOVO</strong>
							@elseif($p->estado == 'rp')
							<strong class="red-text">REPORVADO</strong>
							@elseif($p->estado == 'rc')
							<strong class="yellow-text">RECUSADO</strong>
							@elseif($p->estado == 'ap')
							<strong class="green-text">APROVADO</strong>
							@else
							<strong class="cyan-text">FINALIZADO</strong>
							@endif 
						</th>
						<th>
							<a title="ver pedido" href="/pedidosDelivery/verPedido/{{$p->id}}">
								<i class="material-icons">list</i>
							</a>

						</th>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection	