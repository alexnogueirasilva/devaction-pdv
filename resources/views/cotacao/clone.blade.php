

@extends('default.layout')
@section('content')


<div class="row" id="anime" style="display: none">
	<div class="col s8 offset-s2">
		<lottie-player 
		src="/anime/clone.json"  background="transparent"  speed="0.8"  style="width: 100%; height: 300px;"    autoplay >
	</lottie-player>
</div>
</div>

<div class="row" id="content" style="display: block">
	<div class="card">

		<div class="row">
			<div class="col s12">
				<input type="hidden" id="cotacao" value="{{$cotacao->id}}">
				<input type="hidden" id="fornecedor-atual" value="{{$cotacao->fornecedor->id}}">
				<h4>Cotação: {{$cotacao->id}}</h4>
				<p>*Informe os fornecedores abaixo para clonar esta cotação</p>
				<div class="row">
					<div class="input-field col s6">
						<i class="material-icons prefix">person</i>
						<input autocomplete="off" type="text" name="fornecedor" id="autocomplete-fornecedor" class="autocomplete-fornecedor">
						<label for="autocomplete-fornecedor">Fornecedor</label>
						@if($errors->has('fornecedor'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('fornecedor') }}</span>
						</div>
						@endif
					</div>
					<div class="col s2">
						<a id="add" href="#!" class="btn-large">
							<i class="material-icons">
								add
							</i>
						</a>
					</div>
				</div>

			</div>

			
			<input type="hidden" id="_token" value="{{ csrf_token() }}">

			<div class="row">
				<div class="card">
					<div class="col s12">
						<h5>Lista de Fornecedores para clonar:</h5>

						<div id="fornecedores">
							
						</div>
						
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col s4">
					<a href="/cotacao" class="btn-large red">Cancelar</a>
					<button id="btn-clonar" disabled class="btn-large green accent-3">Clonar</button>

				</div>
			</div>

		</div>
	</div>
</div>

@endsection	