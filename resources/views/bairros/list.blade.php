@extends('default.layout')
@section('content')

	<div class="row">
		<div class="col s12">

			<h4>Lista de Bairros</h4>

			@if(session()->has('message'))
			<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
				<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
			</div>
			@endif

			<div class="row"></div>
			<div class="row">
				<a href="/bairrosDelivery/new" class="btn green accent-3">
			      	<i class="material-icons left">add</i>	
			      	Novo Bairro		
				</a>
			</div>

			

			<div class="row">
				<div class="col s12">
					<label>Numero de registros: {{count($bairros)}}</label>					
				</div>
				<table class="col s12">
					<thead>
						<tr>
							<th>Código</th>
							<th>Nome</th>
							<th>Valor Entrega</th>
						</tr>
					</thead>

					<tbody>
						@foreach($bairros as $b)
						<tr>
							<th>{{ $b->id }}</th>
							<th>{{ $b->nome }}</th>
							<th>{{ $b->valor_entrega }}</th>
							<th>
								<a href="/bairrosDelivery/edit/{{ $b->id }}">
	      							<i class="material-icons left">edit</i>					
								</a>
								<a onclick='swal("Atenção!", "Deseja remover o bairro?", "warning").then((sim) => {if(sim){ location.href="/bairrosDelivery/delete/{{ $b->id }}" }else{return false} })' href="#!">
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