@extends('default.layout')
@section('content')
<div class="row">
	<div class="col s12">
		<h4>{{{ isset($cliente) ? "Editar": "Cadastrar" }}} Cliente</h4>
		<form method="post" action="{{{ isset($cliente) ? '/clientes/update': '/clientes/save' }}}" enctype="multipart/form-data">
			<input type="hidden" name="id" value="{{{ isset($cliente->id) ? $cliente->id : 0 }}}">

			<section class="section-1">
				<div class="row">
					<div class="input-field col s7">
						<input value="{{{ isset($cliente->razao_social) ? $cliente->razao_social : old('razao_social') }}}" id="razao_social" name="razao_social" type="text" class="validate upper-input">
						<label for="razao_social">Razao Social/Nome</label>

						@if($errors->has('razao_social'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('razao_social') }}</span>
						</div>
						@endif

					</div>
				</div>

				<div class="row">
					<div class="input-field col s7">
						<input value="{{{ isset($cliente->nome_fantasia) ? $cliente->nome_fantasia : old('nome_fantasia') }}}" id="nome_fantasia" name="nome_fantasia" type="text" class="validate upper-input">
						<label for="nome_fantasia">Nome Fantasia</label>

						@if($errors->has('nome_fantasia'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('nome_fantasia') }}</span>
						</div>
						@endif

					</div>
				</div>
				
				<div class="row">
					<div class="col s2">
						<p>
							<input class="with-gap" value="f" name="group1" type="radio" id="pessoaFisica" 
							@if(isset($cliente)) @if(strlen($cliente->cpf_cnpj) < 15) checked @endif @else 
							@if(old('group1') == 'f') checked @endif @endif/>
							<label for="pessoaFisica">Pessoa Fisica</label>
						</p>
					</div>
					<div class="col s2">
						<p>
							<input class="with-gap" value="j" name="group1" type="radio" id="pessoaJuridica"
							@if(isset($cliente)) @if(strlen($cliente->cpf_cnpj) > 15) checked @endif @else 
							@if(old('group1') == 'j') checked @endif @endif />
							<label for="pessoaJuridica">Pessoa Juridica</label>
						</p>
					</div>
				</div>

				<div class="row">
					<div class="input-field col s3">
						<input value="{{{ isset($cliente->cpf_cnpj) ? $cliente->cpf_cnpj : old('cpf_cnpj') }}}" id="cpf_cnpj" name="cpf_cnpj" type="text" class="validate">
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
								@foreach($estados as $c)
								<option value="{{$c}}">{{$c}}</option>
								@endforeach
							</select>
							<label>UF</label>
						</div>
						<div class="col s6"><br>
							<a href="#!" class="btn green accent-3" onclick="consultaCadastro()">
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

					<div class="input-field col s3">
						<input value="{{{ isset($cliente->ie_rg) ? $cliente->ie_rg : old('ie_rg') }}}" id="ie_rg" name="ie_rg" type="text" class="validate">
						<label id="lbl_ie_rg" for="ie_rg">RG</label>

						@if($errors->has('ie_rg'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('ie_rg') }}</span>
						</div>
						@endif
					</div>

					
				</div>


				<div class="row">
					<div class="input-field col s2">
						<select name="consumidor_final">
							<option value=""></option>
							<option @if(isset($cliente) && $cliente->consumidor_final == 1) selected @endif value="1" @if(old('consumidor_final') == 1) selected @endif selected>SIM</option>
							<option @if(isset($cliente) && $cliente->consumidor_final == 0)
								selected @endif value="0" @if(old('consumidor_final') == 0)  @endif>NAO</option>
							</select>
							<label>Consumidor Final</label>

							@if($errors->has('consumidor_final'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('consumidor_final') }}</span>
							</div>
							@endif
						</div>

						<div class="input-field col s2">
							<select name="contribuinte">
								<option value=""></option>
								<option @if(isset($cliente) && $cliente->contribuinte == 1)
									selected @endif value="1" @if(old('contribuinte') == 1) selected @endif selected>SIM</option>
									<option @if(isset($cliente) && $cliente->contribuinte == 0)
										selected @endif value="0" @if(old('contribuinte') == 0)  @endif>NAO</option>
									</select>
									<label>Contribuinte</label>

									@if($errors->has('contribuinte'))
									<div class="center-align red lighten-2">
										<span class="white-text">{{ $errors->first('contribuinte') }}</span>
									</div>
									@endif
								</div>

								<div class="input-field col s3">
									<input value="{{{ isset($cliente->limite_venda) ? $cliente->limite_venda : old('limite_venda') }}}" id="valor" name="limite_venda" type="text" class="validate">
									<label>Limite de Venda</label>

								</div>
							</div>
						</section>
						<section class="section-2">
							<h5>Endereço de Faturamento</h5>
							<div class="row">
								<div class="input-field col s5">
									<input value="{{{ isset($cliente->rua) ? $cliente->rua : old('rua') }}}" id="rua" name="rua" type="text" class="validate upper-input">
									<label for="rua">Rua</label>

									@if($errors->has('rua'))
									<div class="center-align red lighten-2">
										<span class="white-text">{{ $errors->first('rua') }}</span>
									</div>
									@endif

								</div>
								<div class="input-field col s2">
									<input value="{{{ isset($cliente->numero) ? $cliente->numero : old('numero') }}}" id="numero" name="numero" type="text" class="validate upper-input">
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
									<input value="{{{ isset($cliente->bairro) ? $cliente->bairro : old('bairro') }}}" id="bairro" name="bairro" type="text" class="validate upper-input">
									<label for="bairro">Bairro</label>

									@if($errors->has('bairro'))
									<div class="center-align red lighten-2">
										<span class="white-text">{{ $errors->first('bairro') }}</span>
									</div>
									@endif

								</div>
								<div class="input-field col s4">
									<input value="{{{ isset($cliente->email) ? $cliente->email : old('email') }}}" id="email" name="email" type="text" class="validate">
									<label for="email">Email</label>

									@if($errors->has('email'))
									<div class="center-align red lighten-2">
										<span class="white-text">{{ $errors->first('email') }}</span>
									</div>
									@endif

								</div>
							</div>

							<div class="row">
								<div class="input-field col s3">
									<input value="{{{ isset($cliente->cep) ? $cliente->cep : old('cep') }}}" id="cep" name="cep" type="text" class="validate">
									<label for="cep">CEP</label>

									@if($errors->has('cep'))
									<div class="center-align red lighten-2">
										<span class="white-text">{{ $errors->first('cep') }}</span>
									</div>
									@endif

								</div>


								<div class="input-field col s4">

									<input autocomplete="off" type="text" name="cidade" id="autocomplete-cidade" value="{{old('cidade')}}" class="autocomplete-cidade">
									<label for="autocomplete-cidade">Cidade</label>
									<input type="hidden" id="cidadeId" value="{{{ isset($cliente) ? $cliente->cidade_id : 0 }}}" 
									>
									@if($errors->has('cidade'))
									<div class="center-align red lighten-2">
										<span class="white-text">{{ $errors->first('cidade') }}</span>
									</div>
									@endif

								</div>
							</div>

							<div class="row">
								<div class="input-field col s3">
									<input value="{{{ isset($cliente->telefone) ? $cliente->telefone : old('telefone') }}}" id="telefone" name="telefone" type="text" class="validate">
									<label for="telefone">Telefone (Opcional)</label>

									@if($errors->has('telefone'))
									<div class="center-align red lighten-2">
										<span class="white-text">{{ $errors->first('telefone') }}</span>
									</div>
									@endif

								</div>
								<div class="input-field col s3">
									<input value="{{{ isset($cliente->celular) ? $cliente->celular : old('celular') }}}" id="celular" name="celular" type="text" class="validate">
									<label for="celular">Celular (Opcional)</label>

									@if($errors->has('celular'))
									<div class="center-align red lighten-2">
										<span class="white-text">{{ $errors->first('celular') }}</span>
									</div>
									@endif

								</div>
							</div>

							<input type="hidden" name="_token" value="{{ csrf_token() }}">

						</section>

						<section class="section-3">
							<h5>Endereço de Cobrança (Opcional)</h5>
							<div class="row">
								<div class="input-field col s5">
									<input value="{{{ isset($cliente->rua_cobranca) ? $cliente->rua_cobranca : old('rua_cobranca') }}}" id="rua" name="rua_cobranca" type="text" class="validate upper-input">
									<label for="rua_cobranca">Rua</label>

									@if($errors->has('rua_cobranca'))
									<div class="center-align red lighten-2">
										<span class="white-text">{{ $errors->first('rua_cobranca') }}</span>
									</div>
									@endif

								</div>
								<div class="input-field col s2">
									<input value="{{{ isset($cliente->numero_cobranca) ? $cliente->numero_cobranca : old('numero_cobranca') }}}" id="numero_cobranca" name="numero_cobranca" type="text" class="validate upper-input">
									<label for="numero_cobranca">Numero</label>

									@if($errors->has('numero_cobranca'))
									<div class="center-align red lighten-2">
										<span class="white-text">{{ $errors->first('numero_cobranca') }}</span>
									</div>
									@endif

								</div>
							</div>

							<div class="row">
								<div class="input-field col s3">
									<input value="{{{ isset($cliente->bairro_cobranca) ? $cliente->bairro_cobranca : old('bairro_cobranca') }}}" id="bairro_cobranca" name="bairro_cobranca" type="text" class="validate upper-input">
									<label for="bairro_cobranca">Bairro</label>

									@if($errors->has('bairro_cobranca'))
									<div class="center-align red lighten-2">
										<span class="white-text">{{ $errors->first('bairro_cobranca') }}</span>
									</div>
									@endif

								</div>
								
							
								<div class="input-field col s3">
									<input value="{{{ isset($cliente->cep_cobranca) ? $cliente->cep_cobranca : old('cep_cobranca') }}}" id="cep_cobranca" name="cep_cobranca" type="text" class="validate cep">
									<label for="cep_cobranca">CEP</label>

									@if($errors->has('cep_cobranca'))
									<div class="center-align red lighten-2">
										<span class="white-text">{{ $errors->first('cep_cobranca') }}</span>
									</div>
									@endif

								</div>


								<div class="input-field col s4">

									<input autocomplete="off" type="text" name="cidade_cobranca" id="autocomplete-cidade-cobranca" value="{{old('cidade_cobranca')}}" class="autocomplete-cidade-cobranca">
									<label for="autocomplete-cidade-cobranca">Cidade</label>
									<input type="hidden" id="cidadeCobrancaId" value="{{{ isset($cliente) ? $cliente->cidade_cobranca_id : 0 }}}" 
									>
									@if($errors->has('cidade_cobranca_id'))
									<div class="center-align red lighten-2">
										<span class="white-text">{{ $errors->first('cidade_cobranca_id') }}</span>
									</div>
									@endif

								</div>
							</div>

							

							<input type="hidden" name="_token" value="{{ csrf_token() }}">

						</section>


						<br>
						<div class="row">
							<a class="btn-large red lighten-2" href="/clientes">Cancelar</a>
							<input type="submit" value="Salvar" class="btn-large green accent-3">
						</div>
					</form>
				</div>
			</div>
			@endsection