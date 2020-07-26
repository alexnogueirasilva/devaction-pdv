@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">
		<nav class="black">
			<div class="nav-wrapper">
				<form method="get" action="/servicos/pesquisa">
					<div class="input-field">
						<input placeholder="Pesquisa de Serviço" id="search" name="pesquisa" 
						type="search" required>
						<label class="label-icon" for="search">
							<i class="material-icons">search</i></label>
						<i class="material-icons">close</i>
					</div>

				</form>
			</div>
		</nav>
		<h4>Lista de Serviços</h4>

		@if(session()->has('message'))
		<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
			<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
		</div>
		@endif

		<div class="row"></div>
		<div class="row">
			<a href="/servicos/new" class="btn green accent-3">
				<i class="material-icons left">add</i>	
				Novo Servico		
			</a>
		</div>



		<div class="row">
			<div class="col s12">
				<label>Numero de registros: {{count($servicos)}}</label>					
			</div>
			<table class="col s12">
				<thead>
					<tr>
						<th>Código</th>
						<th>Nome</th>
						<th>Valor</th>
						<th>Categoria</th>
						<th>Unidade Cobrança</th>
						<th>Ações</th>
					</tr>
				</thead>

				<tbody>
					@foreach($servicos as $s)
					<tr>
						<th>{{ $s->id }}</th>
						<th>{{ $s->nome }}</th>
						<th>{{ number_format($s->valor, 2, ',', '.') }}</th>
						<th>{{ $s->categoria->nome }}</th>
						<th>{{ $s->unidade_cobranca }}</th>

						<th>
							<a href="/servicos/edit/{{ $s->id }}">
								<i class="material-icons left">edit</i>					
							</a>
							<a onclick = "if (! confirm('Deseja excluir este registro?')) { return false; }" href="/servicos/delete/{{ $s->id }}">
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