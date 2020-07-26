@extends('default.layout')
@section('content')
<div class="row">
	<div class="col s12">
		<h4>{{{ isset($funcionario) ? "Editar": "Cadastrar" }}} Funcionario</h4>
		<form method="post" action="{{{ isset($funcionario) ? '/funcionarios/update': '/funcionarios/save' }}}">
			<input type="hidden" name="id" value="{{{ isset($funcionario->id) ? $funcionario->id : 0 }}}">

			<section class="section-1">
				<div class="row">
					<div class="input-field col l7 m12 s12">
						<input value="{{{ isset($funcionario->nome) ? $funcionario->nome : old('nome') }}}" id="nome" name="nome" type="text" class="validate">
						<label for="nome">Nome</label>

						@if($errors->has('nome'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('nome') }}</span>
						</div>
						@endif

					</div>
				</div>


				<div class="row">
					<div class="input-field col l3 m6 s8">
						<input value="{{{ isset($funcionario->cpf) ? $funcionario->cpf : old('cpf') }}}" id="cpf" name="cpf" type="text" class="validate">
						<label id="lbl_cpf_cnpj" for="cpf">CPF</label>

						@if($errors->has('cpf'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('cpf') }}</span>
						</div>
						@endif
					</div>

					<div class="input-field col l3 m6 s8">
						<input value="{{{ isset($funcionario->rg) ? $funcionario->rg : old('rg') }}}" id="rg" name="rg" type="text" class="validate">
						<label id="" for="rg">RG</label>

						@if($errors->has('rg'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('rg') }}</span>
						</div>
						@endif
					</div>
				</div>
			</section>
			<section class="section-2">
				<div class="row">
					<div class="input-field col l5 m8 s12">
						<input value="{{{ isset($funcionario->rua) ? $funcionario->rua : old('rua') }}}" id="rua" name="rua" type="text" class="validate">
						<label for="rua">Rua</label>

						@if($errors->has('rua'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('rua') }}</span>
						</div>
						@endif

					</div>
					<div class="input-field col l2 m4 s6">
						<input value="{{{ isset($funcionario->numero) ? $funcionario->numero : old('numero') }}}" id="numero" name="numero" type="text" class="validate">
						<label for="numero">Numero</label>

						@if($errors->has('numero'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('number') }}</span>
						</div>
						@endif

					</div>
				</div>

				<div class="row">
					<div class="input-field col l3 m12 s12">
						<input value="{{{ isset($funcionario->bairro) ? $funcionario->bairro : old('bairro') }}}" id="bairro" name="bairro" type="text" class="validate">
						<label for="bairro">Bairro</label>

						@if($errors->has('bairro'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('bairro') }}</span>
						</div>
						@endif

					</div>
					<div class="input-field col l3 m12 s12">
						<input value="{{{ isset($funcionario->email) ? $funcionario->email : old('email') }}}" id="email" name="email" type="text" class="validate">
						<label for="email">Email</label>

						@if($errors->has('email'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('email') }}</span>
						</div>
						@endif

					</div>
				</div>



				<div class="row">
					<div class="input-field col l3 m6 s8">
						<input value="{{{ isset($funcionario->telefone) ? $funcionario->telefone : old('telefone') }}}" id="telefone" name="telefone" type="text" class="validate">
						<label for="telefone">Telefone</label>

						@if($errors->has('telefone'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('telefone') }}</span>
						</div>
						@endif

					</div>
					<div class="input-field col l3 m6 s8">
						<input value="{{{ isset($funcionario->celular) ? $funcionario->celular : old('celular') }}}" id="celular" name="celular" type="text" class="validate">
						<label for="celular">Celular</label>

						@if($errors->has('celular'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('celular') }}</span>
						</div>
						@endif

					</div>
				</div>

				@if(!isset($funcionario))
				<div class="row">
					<div class="input-field col l3 m6 s8">
						<input value="{{{ isset($funcionario->data_registro) ? \Carbon\Carbon::parse($funcionario->data_registro)->format('d/m/Y') : old('data_registro') }}}" id="data_registro" name="data_registro" type="text" class="datepicker">
						<label for="celular">Data de Registro</label>

						@if($errors->has('data_registro'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('data_registro') }}</span>
						</div>
						@endif

					</div>
				</div>

				
				@endif

				<input type="hidden" name="_token" value="{{ csrf_token() }}">

			</section>

			<input type="hidden" name="_token" value="{{ csrf_token() }}">

			<br>
			<div class="row">
				<a class="btn-large red lighten-2" href="/funcionarios">Cancelar</a>
				<input type="submit" value="Salvar" class="btn-large green accent-3">
			</div>
		</form>
	</div>
</div>
@endsection