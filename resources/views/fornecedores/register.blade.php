@extends('default.layout')
@section('content')
<div class="row">
	<div class="col s12">
		<h4>{{{ isset($forn) ? "Editar": "Cadastrar" }}} Fornecedor</h4>
		<form method="post" action="{{{ isset($forn) ? '/fornecedores/update': '/fornecedores/save' }}}" enctype="multipart/form-data">
			<input type="hidden" name="id" value="{{{ isset($forn->id) ? $forn->id : 0 }}}">

			<section class="section-1">
				<div class="row">
					<div class="input-field col l7 m12 s12">
						<input value="{{{ isset($forn->razao_social) ? $forn->razao_social : old('razao_social') }}}" id="razao_social" name="razao_social" type="text" class="validate">
						<label for="razao_social">Razao Social</label>

						@if($errors->has('razao_social'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('razao_social') }}</span>
						</div>
						@endif

					</div>
				</div>

				<div class="row">
					<div class="input-field col l7 m12 s12">
						<input value="{{{ isset($forn->nome_fantasia) ? $forn->nome_fantasia : old('nome_fantasia') }}}" id="nome_fantasia" name="nome_fantasia" type="text" class="validate">
						<label for="nome_fantasia">Nome Fantasia</label>

						@if($errors->has('nome_fantasia'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('nome_fantasia') }}</span>
						</div>
						@endif

					</div>
				</div>

				<div class="row">
					<div class="col l2 m6 s6">
						<p>
							<input class="with-gap"  name="group1" type="radio" id="pessoaFisica" 
							{{isset($forn) ? strlen($forn->cpf_cnpj) < 15 ? 'checked' : '' : 'checked'}}/>
							<label for="pessoaFisica">Pessoa Fisica</label>
						</p>
					</div>
					<div class="col l2 m6 s6">
						<p>
							<input class="with-gap"  name="group1" type="radio" id="pessoaJuridica"
							{{isset($forn) ? strlen($forn->cpf_cnpj) > 15 ? 'checked' : '' : ''}} />
							<label for="pessoaJuridica">Pessoa Juridica</label>
						</p>
					</div>
				</div>

				<div class="row">
					<div class="input-field col l3 m6 s8">
						<input value="{{{ isset($forn->cpf_cnpj) ? $forn->cpf_cnpj : old('cpf_cnpj') }}}" id="cpf_cnpj" name="cpf_cnpj" type="text" class="validate">
						<label id="lbl_cpf_cnpj" for="cpf_cnpj">CPF</label>

						@if($errors->has('cpf_cnpj'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('cpf_cnpj') }}</span>
						</div>
						@endif
					</div>

					<div class="col s2" id="sintegra">
						<div class="col s6 input-field">
							<select id="sigla_uf">
								<option value="--">--</option>
								<option value="AC">AC</option>
								<option value="AL">AL</option>
								<option value="AM">AM</option>
								<option value="AP">AP</option>
								<option value="BA">BA</option>
								<option value="CE">CE</option>
								<option value="DF">DF</option>
								<option value="ES">ES</option>
								<option value="GO">GO</option>
								<option value="MA">MA</option>
								<option value="MG">MG</option>
								<option value="MS">MS</option>
								<option value="MT">MT</option>
								<option value="PA">PA</option>
								<option value="PB">PB</option>
								<option value="PE">PE</option>
								<option value="PI">PI</option>
								<option value="PR">PR</option>
								<option value="RJ">RJ</option>
								<option value="RN">RN</option>
								<option value="RS">RS</option>
								<option value="RO">RO</option>
								<option value="RR">RR</option>
								<option value="SC">SC</option>
								<option value="SE">SE</option>
								<option value="SP">SP</option>
								<option value="TO">TO</option>
							</select>
							<label>UF</label>
						</div>
						<div class="col s6"><br>
							<a href="#!" class="btn" onclick="consultaCadastro()">
								<i class="material-icons">memory</i>
							</a>
						</div>
					</div>

					<div id="preloader1" class="col s1" style="display: none">
						<div class="col s12 center-align">
							<div class="preloader-wrapper active">
								<div class="spinner-layer spinner--only">
									<div class="circle-clipper left">
										<div class="circle"></div>
									</div><div class="gap-patch">
										<div class="circle"></div>
									</div><div class="circle-clipper right">
										<div class="circle"></div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="input-field col l3 m6 s8">
						<input value="{{{ isset($forn->ie_rg) ? $forn->ie_rg : old('ie_rg') }}}" id="ie_rg" name="ie_rg" type="text" class="validate">
						<label id="lbl_ie_rg" for="ie_rg">RG</label>

						@if($errors->has('ie_rg'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('ie_rg') }}</span>
						</div>
						@endif
					</div>
				</div>
			</section>
			<section class="section-2">
				<div class="row">
					<div class="input-field col l5 m8 s12">
						<input value="{{{ isset($forn->rua) ? $forn->rua : old('rua') }}}" id="rua" name="rua" type="text" class="validate">
						<label for="rua">Rua</label>

						@if($errors->has('rua'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('rua') }}</span>
						</div>
						@endif

					</div>
					<div class="input-field col l2 m4 s6">
						<input value="{{{ isset($forn->numero) ? $forn->numero : old('numero') }}}" id="numero" name="numero" type="text" class="validate">
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
						<input value="{{{ isset($forn->bairro) ? $forn->bairro : old('bairro') }}}" id="bairro" name="bairro" type="text" class="validate">
						<label for="bairro">Bairro</label>

						@if($errors->has('bairro'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('bairro') }}</span>
						</div>
						@endif

					</div>
					<div class="input-field col l3 m12 s12">
						<input value="{{{ isset($forn->email) ? $forn->email : old('email') }}}" id="email" name="email" type="text" class="validate">
						<label for="email">Email</label>

						@if($errors->has('email'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('email') }}</span>
						</div>
						@endif

					</div>
				</div>

				<div class="row">
					<div class="input-field col l3 m6 s12">
						<input value="{{{ isset($forn->cep) ? $forn->cep : old('cep') }}}" id="cep" name="cep" type="text" class="validate">
						<label for="cep">CEP</label>

						@if($errors->has('cep'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('cep') }}</span>
						</div>
						@endif

					</div>
					<div class="input-field col l3 m6 s12">
						<input autocomplete="off" type="text" name="cidade" id="autocomplete-cidade" class="autocomplete-cidade">
						<label for="autocomplete-cidade">Cidade</label>
						<input type="hidden" id="cidadeId" value="{{{ isset($forn) ? $forn->cidade_id : 0 }}}">
						@if($errors->has('cidade'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('cidade') }}</span>
						</div>
						@endif

					</div>
				</div>

				<div class="row">
					<div class="input-field col l3 m6 s8">
						<input value="{{{ isset($forn->telefone) ? $forn->telefone : old('telefone') }}}" id="telefone" name="telefone" type="text" class="validate">
						<label for="telefone">Telefone</label>

						@if($errors->has('telefone'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('telefone') }}</span>
						</div>
						@endif

					</div>
					<div class="input-field col l3 m6 s8">
						<input value="{{{ isset($forn->celular) ? $forn->celular : old('celular') }}}" id="celular" name="celular" type="text" class="validate">
						<label for="celular">Celular</label>

						@if($errors->has('celular'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('celular') }}</span>
						</div>
						@endif

					</div>
				</div>

				<input type="hidden" name="_token" value="{{ csrf_token() }}">

			</section>

			<input type="hidden" name="_token" value="{{ csrf_token() }}">

			<br>
			<div class="row">
				<a class="btn-large red lighten-2" href="/fornecedores">Cancelar</a>
				<input type="submit" value="Salvar" class="btn-large green accent-3">
			</div>
		</form>
	</div>
</div>
@endsection