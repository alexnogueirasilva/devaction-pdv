@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">

		<h4>Lista de Mesas</h4>

		@if(session()->has('message'))
		<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
			<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
		</div>
		@endif

		<div class="row"></div>


		<div class="row">
			<div class="col s12">
				<label>Numero de registros: {{count($mesas)}}</label><br>				
			</div>

			@foreach($mesas as $m)

			<div class="col s12 l4 m6">
				<div class="card">
					<div class="card-title" style="margin-left: 10px; margin-top: 10px;">
						{{$m->nome}}
					</div>
					<div class="card-content">
						<img style="height: 320px; width: 100%;" src="/mesas/issue/{{$m->id}}">
						 <a target="_blank" href="/mesas/imprimirQrCode/{{$m->id}}" class="btn-floating btn-large waves-effect waves-light red"><i class="material-icons">print</i></a>
					</div>
					
				</div>
				
			</div>

			@endforeach
		</div>
	</div>
</div>
@endsection	