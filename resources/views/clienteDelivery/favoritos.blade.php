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
			<h4>Lista de Produtos Favoritos Cliente <strong>{{$cliente->nome}} {{$cliente->sobre_nome}}</strong></h4>

			@if(session()->has('message'))
			<div class="row">
				<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
					<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
				</div>
			</div>
			@endif
			

			<div class="row">
				<div class="col s12">
					<label>Numero de registros: {{count($favoritos)}}</label>					
				</div>
				<table class="col s12">
					<thead>
						<tr>
							<th>#</th>
							<th>Produto</th>
							<th>Total de vezes que Comprou</th>
							<th>Ações</th>
						</tr>
					</thead>

					<tbody>
						@foreach($favoritos as $f)
						<tr>

							<th>{{ $f->id }}</th>
							<th>{{ $f->produto->produto->nome }}</th>
							<th>{{ $f->totalCompras() }}</th>
							<th>
								@if(count($f->cliente->tokens) > 0)
								<a href="/clientesDelivery/push/{{ $f->id }}">
									<i class="material-icons left green-text">notifications</i>					
								</a>
								@endif

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