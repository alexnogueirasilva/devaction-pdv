@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">
		
		<h4>Tamanhos de Pizza</h4>

		@if(session()->has('message'))
		<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
			<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
		</div>
		@endif

		<div class="row"></div>
		<div class="row">
			<a href="/tamanhosPizza/new" class="btn green accent-3">
				<i class="material-icons left">add</i>	
				Novo Tamanho de Pizza	
			</a>
		</div>



		<div class="row">
			<div class="col s12">
				<label>Numero de registros: {{count($tamanhos)}}</label>					
			</div>
			<table class="col s12">
				<thead>
					<tr>
						<th>#</th>
						<th>Nome</th>
						<th>Pedaços</th>
						<th>Maximo de sabores</th>
						<th>Ações</th>
					</tr>
				</thead>

				<tbody>
					@foreach($tamanhos as $t)
					<tr>
						<th>{{ $t->id }}</th>
						<th>{{ $t->nome }}</th>
						<th>{{ $t->pedacos }}</th>
						<th>{{ $t->maximo_sabores }}</th>
						
						<th>
							<a href="/tamanhosPizza/edit/{{ $t->id }}">
								<i class="material-icons left">edit</i>					
							</a>
							<a onclick = "if (! confirm('Deseja excluir este registro?')) { return false; }" href="/tamanhosPizza/delete/{{ $t->id }}">
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