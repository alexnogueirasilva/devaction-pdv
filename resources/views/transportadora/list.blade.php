@extends('default.layout')
@section('content')

	<div class="row">
		<div class="col s12">

			<h4>Lista de Transportadoras</h4>

			@if(session()->has('message'))
			<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
				<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
			</div>
			@endif

			<div class="row"></div>
			<div class="row">
				<a href="/transportadoras/new" class="btn green accent-3">
			      	<i class="material-icons left">add</i>	
			      	Nova Transportadora		
				</a>
			</div>

			

			<div class="row">
				<div class="col s12">
					<label>Numero de registros: {{count($transportadoras)}}</label>					
				</div>
				<table class="col s12">
					<thead>
						<tr>
							<th>#</th>
							<th>Razão Social</th>
							<th>CPF/CNPJ</th>
							<th>Endereço</th>
							<th>Cidade</th>
							<th>Ações</th>
						</tr>
					</thead>

					<tbody>
						@foreach($transportadoras as $t)
						<tr>
							<th>{{ $t->id }}</th>
							<th>{{ $t->razao_social }}</th>
							<th>{{ $t->cnpj_cpf }}</th>
							<th>{{ $t->logradouro }}</th>
							<th>{{ $t->cidade->nome }}</th>

							<th>
								<a href="/transportadoras/edit/{{ $t->id }}">
	      							<i class="material-icons left">edit</i>					
								</a>
								<a onclick = "if (! confirm('Deseja excluir este registro?')) { return false; }" href="/transportadoras/delete/{{ $t->id }}">
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