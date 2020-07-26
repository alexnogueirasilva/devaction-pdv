@extends('default.layout')
@section('content')
<div class="row">
	<div class="col s12">
		<h4>{{{ isset($transp) ? "Editar": "Cadastrar" }}} Transportadora</h4>
		<form method="post" action="{{{ isset($transp) ? '/transportadoras/update': '/transportadoras/save' }}}" enctype="multipart/form-data">
			<input type="hidden" name="id" value="{{{ isset($transp->id) ? $transp->id : 0 }}}">

			<section class="section-1">
				<div class="row">
					<div class="input-field col s7">
						<input value="{{{ isset($transp->razao_social) ? $transp->razao_social : old('razao_social') }}}" id="razao_social" name="razao_social" type="text" class="validate">
						<label for="razao_social">Razao Social</label>

						@if($errors->has('razao_social'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('razao_social') }}</span>
						</div>
						@endif

					</div>
				</div>
				
				<div class="row">
					<div class="col s2">
						<p>
							<input class="with-gap"  name="group1" type="radio" id="pessoaFisica" 
							@if(isset($transp)) @if(strlen($transp->cnpj_cpf) < 15) checked @endif @endif/>
							<label for="pessoaFisica">Pessoa Fisica</label>
						</p>
					</div>
					<div class="col s2">
						<p>
							<input class="with-gap"  name="group1" type="radio" id="pessoaJuridica"
							@if(isset($transp)) @if(strlen($transp->cnpj_cpf) > 15) checked @endif @endif />
							<label for="pessoaJuridica">Pessoa Juridica</label>
						</p>
					</div>
				</div>

				<div class="row">
					<div class="input-field col s3">
						<input value="{{{ isset($transp->cnpj_cpf) ? $transp->cnpj_cpf : old('cnpj_cpf') }}}" id="cpf_cnpj" name="cnpj_cpf" type="text" class="validate">
						<label id="lbl_cpf_cnpj" for="cnpj_cpf">CPF</label>

						@if($errors->has('cnpj_cpf'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('cnpj_cpf') }}</span>
						</div>
						@endif
					</div>
				</div>
				
				<div class="row">
					<div class="input-field col s5">
						<input value="{{{ isset($transp->logradouro) ? $transp->logradouro : old('logradouro') }}}" id="logradouro" name="logradouro" type="text" class="validate">
						<label for="logradouro">Logradouro</label>

						@if($errors->has('logradouro'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('logradouro') }}</span>
						</div>
						@endif

					</div>
				</div>

				

				<div class="row">
					
					<div class="input-field col s4">

						<input autocomplete="off" type="text" name="cidade" value="{{old('cidade')}}" id="autocomplete-cidade" class="autocomplete-cidade">
						<label for="autocomplete-cidade">Cidade</label>
						<input type="hidden" id="cidadeId" value="{{{ isset($transp) ? $transp->cidade_id : 0 }}}">
						@if($errors->has('cidade'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('cidade') }}</span>
						</div>
						@endif

					</div>
				</div>

				<input type="hidden" name="_token" value="{{ csrf_token() }}">

			</section>


			<br>
			<div class="row">
				<a class="btn-large red lighten-2" href="/transportadoras">Cancelar</a>
				<input type="submit" value="Salvar" class="btn-large green accent-3">
			</div>
		</form>
	</div>
</div>
@endsection