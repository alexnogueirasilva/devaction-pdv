@extends('default.layout')
@section('content')

	<div class="row">
		<div class="col s12">

			<h4>Lista de Fornecedores</h4>

			@if(session()->has('message'))
			<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
				<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
			</div>
			@endif

			<div class="row"></div>
			<div class="row">
				<a href="/fornecedores/new" class="btn green accent-3">
			      	<i class="material-icons left">add</i>	
			      	Novo Fornecedor		
				</a>
			</div>

			

			<div class="row">
				<div class="col s12 m12 l12">
					<label>Numero de registros: {{count($fornecedores)}}</label>					
				</div>
				<table class="">
					<thead>
						<tr>
							<th>Código</th>
							<th>Razão Social</th>
							<th>CPF/CNPJ</th>
							<th>Rua</th>
							<th>Número</th>
							<th>Bairro</th>
							<th>Telefone</th>
							<th>Celular</th>
							<th>Email</th>
							<th>Ações</th>
						</tr>
					</thead>

					<tbody>
						@foreach($fornecedores as $p)
						<tr>
							<th>{{ $p->id }}</th>
							<th>{{ $p->razao_social }}</th>
							<th>{{ $p->cpf_cnpj }}</th>
							<th>{{ $p->rua }}</th>
							<th>{{ $p->numero }}</th>
							<th>{{ $p->bairro }}</th>
							<th>{{ $p->telefone }}</th>
							<th>{{ $p->celular }}</th>
							<th>{{ $p->email }}</th>

							<th>
								<a href="/fornecedores/edit/{{ $p->id }}">
	      							<i class="material-icons left">edit</i>					
								</a>
								<a onclick = "if (! confirm('Deseja excluir este registro?')) { return false; }" href="/fornecedores/delete/{{ $p->id }}">
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