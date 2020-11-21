@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">

		<h4>Lista de endereços do cliente: <strong>{{$cliente->nome}}</strong></h4>

		@if(session()->has('message'))
		<div class="row">
			<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
				<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
			</div>
		</div>
		@endif


		<div class="row">
			<div class="col s12">
				<label>Numero de registros: {{count($cliente->enderecos)}}</label>					
			</div>
			<table class="col s12">
				<thead>
					<tr>
						<th>#</th>
						<th>Rua</th>
						<th>Bairro</th>
						<th>Referência</th>
						<th>Latitude</th>
						<th>Longitude</th>
						<th>Ações</th>
					</tr>
				</thead>

				<tbody>
					@foreach($cliente->enderecos as $e)
					<tr>
						<th>{{ $e->id }}</th>
						<th>{{ $e->rua }}, {{$e->numero}}</th>
						<th>{{$e->bairro()}}</th>
						<th>{{ $e->referencia }}</th>
						<th>{{ $e->latitude }}</th>
						<th>{{ $e->longitude }}</th>

						<th>
							<a href="/clientesDelivery/enderecosEdit/{{$e->id}}">
								<i class="material-icons">edit</i>
							</a>

							@if($e->longitude != '' && $e->latitude != '')
							<a href="/clientesDelivery/enderecosMap/{{$e->id}}">
								<i class="material-icons red-text">place</i>
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