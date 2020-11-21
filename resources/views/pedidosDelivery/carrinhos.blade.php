@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">
		<div class="">
			@foreach($pedidos as $p)

			<div class="col s12 m12 l4 x4">
				<div class="card">
					<div class="card-content" style="border-bottom: 10px solid orange">

						<h4>Cliente: <strong>{{$p->cliente->nome}}</strong></h4>
						<h4>Itens do Pedido: {{count($p->itens)}}</h4>
						<h4>Soma parcial: {{number_format($p->somaItens(), 2)}}</h4>
						<a href="/pedidosDelivery/verCarrinho/{{$p->id}}" style="width: 100%;" class="btn orange">Visualizar</a>
						<a href="/pedidosDelivery/removerCarrinho/{{$p->id}}" style="width: 100%;" class="btn red">Excluir</a>
					</div>	
				</div>
			</div>


			@endforeach
		</div>
	</div>
</div>
@endsection	