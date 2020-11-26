@extends('default.layout')
@section('content')
<div class="row">
	<div class="col s12">
		@if(session()->has('message'))
		<div class="row">
			<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
				<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
			</div>
		</div>
		
		@endif
		<h4>{{{ isset($tributo) ? "Editar": "Cadastrar" }}} Tributações</h4>
		<form method="post" action="/tributos/save" enctype="multipart/form-data">
			<input type="hidden" name="id" value="{{{ isset($tributo->id) ? $tributo->id : 0 }}}">

			<section class="section-1">
				<div class="row">
					<div class="input-field col s4">
						<input value="{{{ isset($tributo->icms) ? $tributo->icms : old('icms') }}}" id="icms" name="icms" type="text" class="validate upper-input">
						<label for="razao_social">ICMS</label>

						@if($errors->has('icms'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('icms') }}</span>
						</div>
						@endif

					</div>

					<div class="input-field col s4">
						<input value="{{{ isset($tributo->pis) ? $tributo->pis : old('pis') }}}" id="pis" name="pis" type="text" class="validate upper-input">
						<label for="pis">PIS</label>

						@if($errors->has('pis'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('pis') }}</span>
						</div>
						@endif

					</div>
				</div>

				<div class="row">
					<div class="input-field col s4">
						<input value="{{{ isset($tributo->cofins) ? $tributo->cofins : old('cofins') }}}" id="cofins" name="cofins" type="text" class="validate upper-input">
						<label for="cofins">COFINS</label>

						@if($errors->has('cofins'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('cofins') }}</span>
						</div>
						@endif

					</div>

					<div class="input-field col s4">
						<input value="{{{ isset($tributo->ipi) ? $tributo->ipi : old('ipi') }}}" id="ipi" name="ipi" type="text" class="validate upper-input">
						<label for="ipi">IPI</label>

						@if($errors->has('ipi'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('ipi') }}</span>
						</div>
						@endif

					</div>
				</div>

				<div class="row">

					<div class="input-field col s4">
						<input value="{{{ isset($tributo->ncm_padrao) ? $tributo->ncm_padrao : old('ncm_padrao') }}}" id="ncm_padrao" name="ncm_padrao" type="text" class="validate upper-input">
						<label for="ncm_padrao">NCM Padrão</label>

						@if($errors->has('ncm_padrao'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('ncm_padrao') }}</span>
						</div>
						@endif

					</div>

					<div class="input-field col s4">
						<select name="regime">
							@foreach($regimes as $key => $r)
							<option value="{{$key}}"
							@isset($tributo->regime)
							@if($tributo->regime == $key)
							selected=""
							@endif
							@endisset
							>{{$key}} - {{$r}}</option>
							@endforeach
						</select>
						<label>Regime da Empresa</label>
					</div>
				</div>

				
			</section>
			
			<input type="hidden" name="_token" value="{{ csrf_token() }}">




			<br>
			<div class="row">
				<a class="btn-large red lighten-2" href="/">Cancelar</a>
				<input type="submit" value="Salvar" class="btn-large green accent-3">
			</div>
		</form>
	</div>
</div>
@endsection