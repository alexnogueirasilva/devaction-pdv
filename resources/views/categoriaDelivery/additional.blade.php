@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">

		<h4>Adicionais para categoria <strong>{{$categoria->nome}}</strong></h4>

		@if(session()->has('message'))
		<div class="row">
			<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
				<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
			</div>
		</div>
		
		@endif

		<form method="post" action="/deliveryCategoria/saveAditional">
			@csrf
			<div class="row">
				<input type="hidden" id="categoria" name="categoria" value="{{$categoria->id}}">
				<div class="input-field col s5">
					<i class="material-icons prefix">add_box</i>
					<input autocomplete="off" type="text" name="adicional" id="autocomplete-adicional" class="autocomplete-adicional">
					<label for="autocomplete-adicional">Adicional</label>
					@if($errors->has('adicional'))
					<div class="center-align red lighten-2">
						<span class="white-text">{{ $errors->first('adicional') }}</span>
					</div>
					@endif
				</div>

				<div class="col s2">
					<button type="submit" class="btn-large green accent-3">
						<i class="material-icons">add</i>
					</button>
				</div>

			</div>
			
		</form>

		


		<div class="row">
			<div class="col s10">
				<label>Numero de registros: {{count($categoria->adicionais)}}</label>					
			</div>
			<table class="col s10">
				<thead>
					<tr>
						<th>#</th>
						<th>Nome</th>
						<th>Ações</th>
					</tr>
				</thead>

				<tbody>
					@foreach($categoria->adicionais as $c)
					<tr>
						<th>{{ $c->complemento->id }}</th>
						<th>{{ $c->complemento->nome }}</th>


						<th>
							
							<a onclick = "if (! confirm('Deseja excluir este registro?')) { return false; }" href="/deliveryCategoria/removeAditional/{{ $c->id }}">
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