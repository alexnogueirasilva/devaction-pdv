@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">

		<h4>Lista de Usuários</h4>

		@if(session()->has('message'))
		<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
			<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
		</div>
		@endif

		<div class="row"></div>
		<div class="row">
			<a href="/usuarios/new" class="btn green accent-3">
				<i class="material-icons left">add</i>	
				Novo Usuario		
			</a>
		</div>



		<div class="row">
			<div class="col s12">
				<label>Numero de registros: {{count($usuarios)}}</label>					
			</div>
			<table class="col s12">
				<thead>
					<tr>
						<th>Código</th>
						<th>Nome</th>
						<th>Login</th>
						<th>ADM</th>
						<th>Ações</th>
					</tr>
				</thead>

				<tbody>
					@foreach($usuarios as $c)
					<tr>
						<th>{{ $c->id }}</th>
						<th>{{ $c->nome }}</th>
						<th>{{ $c->login }}</th>
						<th>
							@if($c->adm == true)
							<i class="material-icons green-text">brightness_1</i>
							@else
							<i class="material-icons red-text">brightness_1</i>
							@endif
						</th>

						<th>
							
							<a href="/usuarios/edit/{{ $c->id }}">
								<i class="material-icons left">edit</i>					
							</a>
							@if(!$c->adm)
							<a onclick = "if (! confirm('Deseja excluir este registro?')) { return false; }" href="/usuarios/delete/{{ $c->id }}">
								<i class="material-icons left red-text">delete</i>					
							</a>
							@endif
							
						</th>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection	