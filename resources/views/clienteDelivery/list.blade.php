@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">
		<nav class="black">
			<div class="nav-wrapper">
				<form method="get" action="/clientes/pesquisa">
					<div class="input-field">
						<input placeholder="Pesquisa de Cliente" id="search" name="pesquisa" 
						type="search" required>
						<label class="label-icon" for="search">
							<i class="material-icons">search</i></label>
							<i class="material-icons">close</i>
						</div>

					</form>
				</div>
			</nav>
			<h4>Lista de Clientes de Delivery</h4>

			@if(session()->has('message'))
			<div class="row">
				<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
					<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
				</div>
			</div>
			@endif
			

			<div class="row">
				<div class="col s12">
					<label>Numero de registros: {{count($clientes)}}</label>					
				</div>
				<table class="col s12">
					<thead>
						<tr>
							<th>Código</th>
							<th>Nome</th>
							<th>Sobrenome</th>
							<th>Celular</th>
							<th>Email</th>
							<th>Produtos Favoritos</th>
							<th>Ações</th>
						</tr>
					</thead>

					<tbody>
						@foreach($clientes as $c)
						<tr>
							<th>{{ $c->id }}</th>
							<th>{{ $c->nome }}</th>
							<th>{{ $c->sobre_nome }}</th>
							<th>{{ $c->celular }}</th>
							<th>{{ $c->email }}</th>
							<th>{{ count($c->favoritos) }}</th>
							<th>

								<a onclick = "if (! confirm('Deseja excluir este cliente')) { return false; }" href="/clientesDelivery/delete/{{ $c->id }}">
									<i class="material-icons left red-text">delete</i>					
								</a>
								<a href="/clientesDelivery/edit/{{ $c->id }}">
									<i class="material-icons left yellow-text">edit</i>					
								</a>

								<a title="Pedidos" href="/clientesDelivery/pedidos/{{ $c->id }}">
									<i class="material-icons left black-text">shopping_cart</i>					
								</a>
								
								<a title="Enderecos" href="/clientesDelivery/enderecos/{{ $c->id }}">
									<i class="material-icons left green-text">map</i>					
								</a>


								<a title="Favoritos" href="/clientesDelivery/favoritos/{{ $c->id }}">
									<i class="material-icons left blue-text">favorite</i>					
								</a>
								
							</th>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			@if(isset($links))
			<ul class="pagination center-align">
				<li class="waves-effect">{{$clientes->links()}}</li>
			</ul>
			@endif
		</div>
	</div>
	@endsection	