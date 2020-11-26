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
		<h4>{{{ isset($config) ? "Editar": "Cadastrar" }}} Configuração de Delivery</h4>
		<form method="post" action="/configDelivery/save" enctype="multipart/form-data">
			<input type="hidden" name="id" value="{{{ isset($config->id) ? $config->id : 0 }}}">

			<section class="section-1">
				<div class="row">
					<div class="input-field col s7">
						<input value="{{{ isset($config->link_face) ? $config->link_face : old('link_face') }}}" id="link_face" name="link_face" type="text" class="validate">
						<label for="link_face">Link do FaceBook</label>

						@if($errors->has('link_face'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('link_face') }}</span>
						</div>
						@endif

					</div>
				</div>

				<div class="row">
					<div class="input-field col s7">
						<input value="{{{ isset($config->link_twiteer) ? $config->link_twiteer : old('link_twiteer') }}}" id="link_twiteer" name="link_twiteer" type="text" class="validate">
						<label for="link_twiteer">Link do Twiter</label>

						@if($errors->has('link_twiteer'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('link_twiteer') }}</span>
						</div>
						@endif

					</div>
				</div>

				<div class="row">
					<div class="input-field col s7">
						<input value="{{{ isset($config->link_google) ? $config->link_google : old('link_google') }}}" id="link_google" name="link_google" type="text" class="validate">
						<label for="link_google">Link do Google</label>

						@if($errors->has('link_google'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('link_google') }}</span>
						</div>
						@endif

					</div>
				</div>

				<div class="row">
					<div class="input-field col s7">
						<input value="{{{ isset($config->link_instagram) ? $config->link_instagram : old('link_instagram') }}}" id="link_instagram" name="link_instagram" type="text" class="validate">
						<label for="link_instagram">Link do Instagram</label>

						@if($errors->has('link_instagram'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('link_instagram') }}</span>
						</div>
						@endif

					</div>
				</div>

				<div class="row">
					<div class="input-field col s7">
						<input value="{{{ isset($config->endereco) ? $config->endereco : old('endereco') }}}" id="endereco" name="endereco" type="text" class="validate">
						<label>Endereco</label>

						@if($errors->has('endereco'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('endereco') }}</span>
						</div>
						@endif
					</div>
				</div>

				<div class="row">
					<div class="input-field col s3">
						<input value="{{{ isset($config->telefone) ? $config->telefone : old('telefone') }}}" id="telefone" name="telefone" type="text" class="validate">
						<label>Telefone</label>

						@if($errors->has('telefone'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('telefone') }}</span>
						</div>
						@endif
					</div>
				</div>

				<div class="row">
					<div class="input-field col s3">
						<input value="{{{ isset($config->tempo_medio_entrega) ? $config->tempo_medio_entrega : old('tempo_medio_entrega') }}}" id="tempo_medio_entrega" name="tempo_medio_entrega" type="text" class="validate" data-mask="00">
						<label>Tempo Medio de Entrega (minutos)</label>

						@if($errors->has('tempo_medio_entrega'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('tempo_medio_entrega') }}</span>
						</div>
						@endif
					</div>

					<div class="input-field col s3">
						<input value="{{{ isset($config->valor_entrega) ? $config->valor_entrega : old('valor_entrega') }}}" id="valor_entrega" name="valor_entrega" type="text" class="validate">
						<label>Valor de Entrega Padrão</label>

						@if($errors->has('valor_entrega'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('valor_entrega') }}</span>
						</div>
						@endif
					</div>

					<div class="input-field col s3">
						<input value="{{{ isset($config->tempo_maximo_cancelamento) ? $config->tempo_maximo_cancelamento : old('tempo_maximo_cancelamento') }}}" placeholder="00:15" id="tempo_maximo_cancelamento" name="tempo_maximo_cancelamento" type="text" class="picker">
						<label>Tempo para Cancelamento HH:mm</label>

						@if($errors->has('tempo_maximo_cancelamento'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('tempo_maximo_cancelamento') }}</span>
						</div>
						@endif
					</div>
				</div>

				<div class="row">

					<div class="input-field col s3">
						<input value="{{{ isset($config->valor_km) ? number_format($config->valor_km, 2) : old('valor_km') }}}" id="valor_km" name="valor_km" type="text" class="validate">
						<label>Valor por KM R$</label>

						@if($errors->has('valor_km'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('valor_km') }}</span>
						</div>
						@endif
					</div>

					<div class="input-field col s3">
						<input value="{{{ isset($config->maximo_km_entrega) ? $config->maximo_km_entrega : old('maximo_km_entrega') }}}" id="maximo_km_entrega" name="maximo_km_entrega" type="text" class="validate">
						<label>Distancia máxima para entrega (KM)</label>

						@if($errors->has('maximo_km_entrega'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('maximo_km_entrega') }}</span>
						</div>
						@endif
					</div>

					<div class="input-field col s3">
						<input value="{{{ isset($config->entrega_gratis_ate) ? $config->entrega_gratis_ate : old('entrega_gratis_ate') }}}" id="entrega_gratis_ate" name="entrega_gratis_ate" type="text" class="validate">
						<label>Frete gratis até X KM</label>

						@if($errors->has('entrega_gratis_ate'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('entrega_gratis_ate') }}</span>
						</div>
						@endif
					</div>
					
					<p class="red-text">Uitlize Valor KM 0 para entrega com valor fixo</p>


				</div>

				<div class="row">
					<div class="col s2">
						<label>Utilizar bairros</label>

						<div class="switch">
							<label class="">
								Não
								<input @if(isset($config->usar_bairros) && $config->usar_bairros) checked @endisset value="true" name="usar_bairros" class="red-text" type="checkbox">
								<span class="lever"></span>
								Sim
							</label>
						</div>
					</div>
					<p class="red-text">Se marcar esta opção desátivara o valor de entrega padrão e por KM</p>

				</div>

				<div class="row">
					<div class="input-field col s3">

						<input value="{{{ isset($config->maximo_adicionais) ? $config->maximo_adicionais : old('maximo_adicionais') }}}" id="maximo_adicionais" name="maximo_adicionais" type="text" class="validate">
						<label>Maximo de adicionais produtos</label>

						@if($errors->has('maximo_adicionais'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('maximo_adicionais') }}</span>
						</div>
						@endif

					</div>

					<div class="input-field col s3">

						<input value="{{{ isset($config->maximo_adicionais_pizza) ? $config->maximo_adicionais_pizza : old('maximo_adicionais_pizza') }}}" id="maximo_adicionais_pizza" name="maximo_adicionais_pizza" type="text" class="validate">
						<label>Maximo de adicionais pizzas</label>

						@if($errors->has('maximo_adicionais_pizza'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('maximo_adicionais_pizza') }}</span>
						</div>
						@endif

					</div>
				</div>

				<div class="row">
					<div class="input-field col s6">

						<input value="{{{ isset($config->nome_exibicao_web) ? $config->nome_exibicao_web : old('nome_exibicao_web') }}}" id="nome_exibicao_web" name="nome_exibicao_web" type="text" class="validate" placeholder="Ex: Bacana Delivery">
						<label>Nome exibição WEB</label>

						@if($errors->has('nome_exibicao_web'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('nome_exibicao_web') }}</span>
						</div>
						@endif

					</div>

					<div class="col s3">
						<p class="red-text">Utilize duas palavras</p>
					</div>
				</div>

				<div class="row">
					<div class="input-field col s3">
						<input value="{{{ isset($config->latitude) ? $config->latitude : old('latitude') }}}" id="latitude" name="latitude" type="text" class="validate">
						<label>Latitude</label>

						@if($errors->has('latitude'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('latitude') }}</span>
						</div>
						@endif
					</div>

					<div class="input-field col s3">
						<input value="{{{ isset($config->longitude) ? $config->longitude : old('longitude') }}}" id="longitude" name="longitude" type="text" class="validate">
						<label>Longitude</label>

						@if($errors->has('longitude'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('longitude') }}</span>
						</div>
						@endif
					</div>
				</div>

				<div class="row">
					<div class="col s6">
						<div class="file-field input-field">
							<div class="btn black">
								<span>Logo 60x60 .png</span>
								<input value="{{{ isset($config->logo) ? $config->logo : old('logo') }}}" name="file" accept=".png" type="file">
							</div>
							<div class="file-path-wrapper">
								<input class="file-path validate" type="text">
							</div>

							@if($errors->has('file'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('file') }}</span>
							</div>
							@endif
						</div>
					</div>
					<div class="col s3">
						<img style="width: 60px; height: 60px;" src="/images/logo.png">
						<span>Logo</span>
					</div>
				</div>

				<div class="row">
					
					<div class="input-field col s12">
					<textarea data-length="400" name="politica_privacidade" id="politica_privacidade" class="materialize-textarea">{{{ isset($config->politica_privacidade) ? $config->politica_privacidade : old('politica_privacidade') }}}</textarea>
					<label for="descricao">Politica de privacidade</label>

					@if($errors->has('politica_privacidade'))
					<div class="center-align red lighten-2">
						<span class="white-text">{{ $errors->first('politica_privacidade') }}</span>
					</div>
					@endif
				</div>
				</div>


			</section>
			
			<input type="hidden" name="_token" value="{{ csrf_token() }}">


			<br>
			<div class="row">
				<a class="btn-large red lighten-2" href="/frenteCaixa">Cancelar</a>
				<input type="submit" value="Salvar" class="btn-large green accent-3">
			</div>
		</form>
	</div>
</div>
@endsection