@extends('default.layout')
@section('content')

	<div class="row">
		<div class="col s12">

			<h4>Lista de Adiconais de Delivery</h4>

			@if(session()->has('message'))
			<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
				<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
			</div>
			@endif

			<div class="row"></div>
			<div class="row">
				<a href="/deliveryComplemento/new" class="btn green accent-3">
			      	<i class="material-icons left">add</i>	
			      	Novo Adicional		
				</a>
			</div>

			

			<div class="row">
				<div class="col s12">
					<label>Numero de registros: {{count($complementos)}}</label>					
				</div>
				<table class="col s12">
					<thead>
						<tr>
							<th>#</th>
							<th>Nome</th>
							<th>Valor</th>
							<th>Ações</th>
						</tr>
					</thead>

					<tbody>
						@foreach($complementos as $c)
						<tr>
							<th>{{ $c->id }}</th>
							<th>{{ $c->nome }}</th>
							<th>{{ $c->valor }}</th>

							<th>
								<a href="/deliveryComplemento/edit/{{ $c->id }}">
	      							<i class="material-icons left">edit</i>					
								</a>
								<a onclick = "if (! confirm('Deseja excluir este registro?')) { return false; }" href="/deliveryComplemento/delete/{{ $c->id }}">
	      							<i class="material-icons left red-text">delete</i>					
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