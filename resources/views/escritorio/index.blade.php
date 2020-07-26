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
		<h4>{{{ isset($config) ? "Editar": "Cadastrar" }}} Escritório</h4>
		<form method="post" action="/escritorio/save" enctype="multipart/form-data">
			<input type="hidden" name="id" value="{{{ isset($escritorio->id) ? $escritorio->id : 0 }}}">

			<section class="section-1">
				<div class="row">
					<div class="input-field col s7">
						<input value="{{{ isset($escritorio->razao_social) ? $escritorio->razao_social : old('razao_social') }}}" id="razao_social" name="razao_social" type="text" class="validate upper-input">
						<label for="razao_social">Razao Social</label>

						@if($errors->has('razao_social'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('razao_social') }}</span>
						</div>
						@endif

					</div>
				</div>

				<div class="row">
					<div class="input-field col s7">
						<input value="{{{ isset($escritorio->nome_fantasia) ? $escritorio->nome_fantasia : old('nome_fantasia') }}}" id="nome_fantasia" name="nome_fantasia" type="text" class="validate upper-input">
						<label for="nome_fantasia">Nome Fantasia</label>

						@if($errors->has('nome_fantasia'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('nome_fantasia') }}</span>
						</div>
						@endif

					</div>
				</div>

				<div class="row">
					<div class="input-field col s3">
						<input value="{{{ isset($escritorio->cnpj) ? $escritorio->cnpj : old('cnpj') }}}" id="cnpj" name="cnpj" type="text" class="validate">
						<label>CNPJ</label>

						@if($errors->has('cnpj'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('cnpj') }}</span>
						</div>
						@endif
					</div>

					<div class="input-field col s3">
						<input value="{{{ isset($escritorio->ie) ? $escritorio->ie : old('ie') }}}" id="ie" name="ie" type="text" class="validate">
						<label>Inscrição Estadual</label>

						@if($errors->has('ie'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('ie') }}</span>
						</div>
						@endif
					</div>
				</div>
			</section>
			<section class="section-2">

				<div class="row">
					<div class="input-field col s5">
						<input value="{{{ isset($escritorio->logradouro) ? $escritorio->logradouro : old('logradouro') }}}" id="logradouro" name="logradouro" type="text" class="validate upper-input">
						<label for="logradouro">Logradouro</label>

						@if($errors->has('logradouro'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('logradouro') }}</span>
						</div>
						@endif

					</div>
					<div class="input-field col s2">
						<input value="{{{ isset($escritorio->numero) ? $escritorio->numero : old('numero') }}}" id="numero" name="numero" type="text" class="validate upper-input">
						<label for="numero">Numero</label>

						@if($errors->has('numero'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('numero') }}</span>
						</div>
						@endif

					</div>
				</div>

				<div class="row">

					<div class="input-field col s3">
						<input value="{{{ isset($escritorio->bairro) ? $escritorio->bairro : old('bairro') }}}" id="bairro" name="bairro" type="text" class="validate upper-input">
						<label for="bairro">Bairro</label>

						@if($errors->has('bairro'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('bairro') }}</span>
						</div>
						@endif

					</div>
					<div class="input-field col s3">
						<input value="{{{ isset($escritorio->cep) ? $escritorio->cep : old('cep') }}}" id="cep" name="cep" type="text" class="validate">
						<label for="cep">CEP</label>

						@if($errors->has('cep'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('cep') }}</span>
						</div>
						@endif

					</div>
				</div>
				<div class="row">
					<div class="input-field col s3">
						<input value="{{{ isset($escritorio->email) ? $escritorio->email : old('email') }}}" id="email" name="email" type="text" class="validate upper-input">
						<label for="email">Email</label>

						@if($errors->has('email'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('email') }}</span>
						</div>
						@endif

					</div>

					<div class="input-field col s3">
						<input value="{{{ isset($escritorio->fone) ? $escritorio->fone : old('fone') }}}" id="telefone" name="fone" type="text" class="validate upper-input">
						<label for="fone">Telefone</label>

						@if($errors->has('fone'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('fone') }}</span>
						</div>
						@endif

					</div>
				</div>

				<input type="hidden" name="_token" value="{{ csrf_token() }}">

			</section>


			<br>
			<div class="row">
				<a class="btn-large red lighten-2" href="/">Cancelar</a>
				<input type="submit" value="Salvar" class="btn-large green accent-3">
			</div>
		</form>
	</div>
</div>
@endsection