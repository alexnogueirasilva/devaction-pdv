@extends('default.layout')
@section('content')
<div class="row">
	<div class="col s12">
		<h4>Editar Endereço <strong>{{$endereco->id}}</strong></h4>
		<form method="post" action="/clientesDelivery/updateEndereco" enctype="multipart/form-data">
			<input type="hidden" name="id" value="{{$endereco->id}}">

			<section class="section-1">
				<div class="row">
					<div class="input-field col s6">
						<input value="{{$endereco->rua}}" id="rua" name="rua" type="text" class="validate">
						<label for="rua">Rua</label>

						@if($errors->has('rua'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('rua') }}</span>
						</div>
						@endif
					</div>

					<div class="input-field col s2">
						<input value="{{$endereco->numero}}" id="numero" name="numero" type="text" class="validate">
						<label for="numero">Numero</label>

						@if($errors->has('numero'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('numero') }}</span>
						</div>
						@endif
					</div>
				</div>

				@if($config->usar_bairros == 1)
				<div class="row">
					<div class="input-field col s4">
						<select name="bairro_id">
							@foreach($bairros as $b)
							<option value="{{$b->id}}" @if($b->id == $endereco->bairro_id) selected @endif>{{$b->nome}} R$ {{number_format($b->valor_entrega, 2)}}</option>
							@endforeach
						</select>
						<label for="bairro">Bairro </label>

						@if($errors->has('bairro'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('bairro') }}</span>
						</div>
						@endif
					</div>
				</div>
				@else
				<div class="row">
					<div class="input-field col s4">
						<input value="{{$endereco->bairro}}" id="bairro" name="bairro" type="text" class="validate">
						<label for="bairro">Bairro</label>

						@if($errors->has('bairro'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('bairro') }}</span>
						</div>
						@endif
					</div>
				</div>
				@endif

				<div class="row">
					<div class="input-field col s6">
						<input value="{{$endereco->referencia}}" id="referencia" name="referencia" type="text" class="validate">
						<label for="referencia">Referência</label>

						@if($errors->has('referencia'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('referencia') }}</span>
						</div>
						@endif
					</div>
				</div>

				<div class="row">
					<div class="input-field col s2">
						<input value="{{$endereco->latitude}}" id="latitude" name="latitude" type="text" class="validate">
						<label for="latitude">Latitude</label>

						@if($errors->has('latitude'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('latitude') }}</span>
						</div>
						@endif
					</div>

					<div class="input-field col s2">
						<input value="{{$endereco->longitude}}" id="longitude" name="longitude" type="text" class="validate">
						<label for="longitude">Longitude</label>

						@if($errors->has('longitude'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('longitude') }}</span>
						</div>
						@endif
					</div>
				</div>
				
			</section>

			<br>
			<div class="row">
				<a class="btn-large red lighten-2" href="/clientesDelivery">Cancelar</a>
				<input type="submit" value="Salvar" class="btn-large green">
			</div>
			@csrf
		</form>
	</div>
</div>
@endsection