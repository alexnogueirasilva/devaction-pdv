@extends('default.layout')
@section('content')
<style type="text/css">
h1{
	font-size: 110px;
}

.img-mesa{
	width: 200px; height: 200px; display: block; margin-left: auto;margin-right: auto;
}
</style>
<div class="row">

	@if(session()->has('message'))
	<div class="row">
		<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
			<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
		</div>
	</div>
	@endif
	@if(count($pedidos) > 0)
	<div class="col s12">


		@foreach($pedidos as $p)
		<div class="col s4">
			<div class="card">
				<div class="card-content">

					<h5 class="center-align grey-text">MESA</h5>
					<img src="/imgs/mesa.png" class="img-mesa">
					<h3 class="center-align">{{$p->mesa->nome}}</h3>

					<h5>Total: <strong>R$ {{$p->mesa->somaItens()}}</strong></h5>
					<h5>Horário Abertura: <strong>{{ \Carbon\Carbon::parse($p->data_registro)->format('H:i')}}</strong></h5>
					<h5>Total de Comandas: <strong class="red-text">{{$p->mesa->comandas()}}</strong></h5>

					<a class="btn white red-text" onclick = "if (! confirm('Deseja desativar esta comanda? os dados não poderam ser retomados!')) { return false; }" href="/pedidos/desativar/{{$p->id}}"><i class="material-icons red-text left">close</i> desativar</a>
				</div>

				<a href="/pedidos/verMesa/{{$p->mesa->id}}" style="width: 100%;" class="btn orange">Visualizar</a>
			</div>
			

		</div>
		@endforeach

		
	</div>
	@else
	<div class="col s12">
		<h4 class="center-align">Nenhuma mesa aberta!</h4>
	</div>
	@endif
</div>



@endsection	