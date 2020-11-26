@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">
		<nav class="black">
			<div class="nav-wrapper">
				<form method="get" action="/codigoDesconto/pesquisa">
					<div class="input-field">
						<input placeholder="Pesquisa por Cliente" id="search" name="pesquisa" 
						type="search" required>
						<label class="label-icon" for="search">
							<i class="material-icons">search</i></label>
							<i class="material-icons">close</i>
						</div>

					</form>
				</div>
			</nav>
			<h4>Códigos de Desconto</h4>

			@if(session()->has('message'))
			<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
				<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
			</div>
			@endif

			<div class="row"></div>
			<div class="row">
				<a href="/codigoDesconto/new" class="btn green accent-3">
					<i class="material-icons left">add</i>	
					Novo Código de Desconto		
				</a>
			</div>

			

			<div class="row">
				<div class="col s12">
					<label>Numero de registros: {{count($codigos)}}</label>					
				</div>
				<table class="col s12">
					<thead>
						<tr>
							<th>#</th>
							<th>Cliente</th>
							<th>Código</th>
							<th>Status</th>
							<th>SMS</th>
							<th>PUSH</th>
							<th>Tipo</th>
							<th>Valor</th>
							<th>Ações</th>
						</tr>
					</thead>

					<tbody>
						@foreach($codigos as $c)
						<tr>
							<th>{{ $c->id }}</th>
							@if($c->cliente)
							<th>{{ $c->cliente->nome }}</th>
							@else
							<th>TODOS</th>
							@endif
							<th>{{ $c->codigo }}</th>
							<th>
								@if($c->ativo)
								<i class="material-icons green-text">brightness_1</i>
								@else
								<i class="material-icons red-text">brightness_1</i>
								@endif
							</th>

							<th>
								@if($c->sms)
								<i class="material-icons green-text">brightness_1</i>
								@else
								<i class="material-icons red-text">brightness_1</i>
								@endif
							</th>

							<th>
								@if($c->push)
								<i class="material-icons green-text">brightness_1</i>
								@else
								<i class="material-icons red-text">brightness_1</i>
								@endif
							</th>

							<th>{{ $c->tipo }}</th>
							<th>{{ number_format($c->valor, 2, ',', '.') }}</th>

							<th>
								<a href="/codigoDesconto/edit/{{ $c->id }}">
									<i class="material-icons left">edit</i>					
								</a>
								<a onclick = "if (! confirm('Deseja excluir este registro?')) { return false; }" href="/codigoDesconto/delete/{{ $c->id }}">
									<i class="material-icons left red-text">delete</i>					
								</a>
								@if($c->ativo)
								<a href="/codigoDesconto/push/{{$c->id}}">
									<i class="material-icons purple-text">notifications</i>
								</a>

								<a href="/codigoDesconto/sms/{{$c->id}}">
									<i class="material-icons green-text">sms</i>
								</a>

								<a href="/codigoDesconto/alterarStatus/{{$c->id}}">
									<i class="material-icons yellow-text">close</i>
								</a>
								@else

								<a href="/codigoDesconto/alterarStatus/{{$c->id}}">
									<i class="material-icons green-text">check</i>
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