@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">

		<h4>Lista de Preços de Produtos</h4>

		@if(session()->has('message'))
		<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
			<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
		</div>
		@endif

		<div class="row"></div>
		<div class="row">
			<a href="/listaDePrecos/new" class="btn green accent-3">
				<i class="material-icons left">add</i>	
				Nova Lista de Preço		
			</a>

			<a href="/listaDePrecos/pesquisa" class="btn red accent-3">
				<i class="material-icons left">search</i>	
				Consultar Preços		
			</a>
		</div>

		

		<div class="row">
			<div class="col s12">
				<label>Numero de registros: {{count($lista)}}</label>					
			</div>
			<table class="col s12">
				<thead>
					<tr>
						<th>#</th>
						<th>Nome</th>
						<th>Precentual de Alteração</th>
						<th>Ações</th>
					</tr>
				</thead>

				<tbody>
					@foreach($lista as $l)
					<tr>
						<th>{{ $l->id }}</th>
						<th>{{ $l->nome }}</th>
						<th>{{ $l->percentual_alteracao }}</th>
						<th>
							<a href="/listaDePrecos/edit/{{ $l->id }}">
								<i class="material-icons left">edit</i>					
							</a>

							<a onclick='swal("Atenção!", "Deseja remover este registro?", "warning").then((sim) => {if(sim){ location.href="/listaDePrecos/delete/{{ $l->id }}" }else{return false} })' href="#!">
								<i class="material-icons left red-text">delete</i>					
							</a>

							<a href="/listaDePrecos/ver/{{ $l->id }}">
								<i class="material-icons left green-text">format_list_bulleted</i>					
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