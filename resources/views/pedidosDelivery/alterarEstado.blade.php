@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">
		<div class="row">
			<h3 class="center-align">Alteração de Estado do Pedido Delivery <strong class="red-text">{{$pedido->id}}</strong></h3>
		</div>

		@if(session()->has('message'))
		<div class="row">
			<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
				<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
			</div>
			<br>
			<a style="margin-top: 10px; width: 100%" class="btn center-align" href="/configNF">ir para Configuração do Emitente</a>

		</div>
		@endif

		
		<div class="container">
			<h5>Você tem certeza que irá alterar o estado desse pedido de 
				@if($pedido->estado == 'nv')
				<strong>NOVO</strong>
				@elseif($pedido->estado == 'rp')
				<strong>REPORVADO</strong>
				@elseif($pedido->estado == 'rc')
				<strong>RECUSADO</strong>
				@elseif($pedido->estado == 'ap')
				<strong>APROVADO</strong>
				@else
				<strong>FINALIZADO</strong>
				@endif 
				para 
				@if($tipo == 'nv')
				<strong>NOVO</strong>
				@elseif($tipo == 'rp')
				<strong>REPORVADO</strong>
				@elseif($tipo == 'rc')
				<strong>RECUSADO</strong>
				@elseif($tipo == 'ap')
				<strong>APROVADO</strong>
				@else
				<strong>FINALIZADO</strong>
				@endif 
			</h5>
		</div>

		<div class="container">
			<p class="red-text">Você será redirecionado para frente de caixa após esta tela</p>
			<form action="/pedidosDelivery/confirmarAlteracao" method="get">
				<input type="hidden" name="tipo" value="{{$tipo}}">
				<input type="hidden" name="id" value="{{$pedido->id}}">

				<div class="row">
					
					<div class="input-field col s12">
						<textarea data-length="50" name="motivoEstado" id="motivoEstado" class="materialize-textarea"></textarea>
						<label for="motivoEstado">Motivo da alteração (Opcional)</label>
					</div>
				</div>


				<button style="width: 100%;" 
				class="btn @if($tipo == 'rp') red @elseif($tipo == 'rc') yellow @elseif($tipo == 'ap') green @else cyan @endif" type="submit">Confirmar Alteração</button>
			</form>
		</div>

	</div>
</div>
@endsection	