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
			<h4>Lista de Clientes</h4>

			@if(session()->has('message'))
			<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
				<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
			</div>
			@endif

			<div class="row"></div>
			<div class="row">
				<a href="/clientes/new" class="btn green accent-3">
					<i class="material-icons left">add</i>	
					Novo Cliente		
				</a>
			</div>

			

			<div class="row">
				<div class="col s12">
					<label>Numero de registros: {{count($clientes)}}</label>					
				</div>
				<table class="col s12">
					<thead>
						<tr>
							<th>Código</th>
							<th>Nome</th>
							<th>CPF/CNPJ</th>
							<th>Rua</th>
							<th>Número</th>
							<th>Bairro</th>
							<th>Telefone</th>
							<th>Cidade</th>
							<th>Email</th>
							<th>Ações</th>
						</tr>
					</thead>

					<tbody>
						@foreach($clientes as $c)
						<tr>
							<th>{{ $c->id }}</th>
							<th>{{ $c->razao_social }}</th>
							<th>{{ $c->cpf_cnpj }}</th>
							<th>{{ $c->rua }}</th>
							<th>{{ $c->numero }}</th>
							<th>{{ $c->bairro }}</th>
							<th>{{ $c->telefone }}</th>
							<th>{{ $c->cidade->nome }}</th>
							<th>{{ $c->email }}</th>

							<th>
								<a href="/clientes/edit/{{ $c->id }}">
									<i class="material-icons left">edit</i>					
								</a>
								
								<a onclick='swal("Atenção!", "Deseja remover este registro?", "warning").then((sim) => {if(sim){ location.href="/clientes/delete/{{ $c->id }}" }else{return false} })' href="#!">
									<i class="material-icons left red-text">delete</i>					
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