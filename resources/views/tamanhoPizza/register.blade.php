@extends('default.layout')
@section('content')
	<div class="row">
		<div class="col s12">
			<h4>{{{ isset($tamanho) ? "Editar": "Cadastrar" }}} Tamanho de Pizza</h4>
			<form method="post" action="{{{ isset($tamanho) ? '/tamanhosPizza/update': '/tamanhosPizza/save' }}}">
				<input type="hidden" name="id" value="{{{ isset($tamanho->id) ? $tamanho->id : 0 }}}">
				
				<div class="row">
					<div class="input-field col s6">
			          <input value="{{{ isset($tamanho->nome) ? $tamanho->nome : old('nome') }}}" id="nome" name="nome" type="text" class="validate">
			          <label for="nome">Nome</label>
			          
			          @if($errors->has('nome'))
			          <div class="center-align red lighten-2">
			          	<span class="white-text">{{ $errors->first('nome') }}</span>
			          </div>
			          @endif

			        </div>
				</div>

				<div class="row">
					<div class="input-field col s3">
			          <input value="{{{ isset($tamanho->pedacos) ? $tamanho->pedacos : old('pedacos') }}}" id="pedacos" name="pedacos" type="text" class="validate">
			          <label for="pedacos">Pedaços</label>
			          
			          @if($errors->has('pedacos'))
			          <div class="center-align red lighten-2">
			          	<span class="white-text">{{ $errors->first('pedacos') }}</span>
			          </div>
			          @endif

			        </div>
			        <div class="input-field col s3">
			          <input value="{{{ isset($tamanho->maximo_sabores) ? $tamanho->maximo_sabores : old('maximo_sabores') }}}" id="maximo_sabores" name="maximo_sabores" type="text" class="validate">
			          <label for="maximo_sabores">Maximo de Sabores</label>
			          
			          @if($errors->has('maximo_sabores'))
			          <div class="center-align red lighten-2">
			          	<span class="white-text">{{ $errors->first('maximo_sabores') }}</span>
			          </div>
			          @endif

			        </div>
				</div>

				<input type="hidden" name="_token" value="{{ csrf_token() }}">

				
				<br>
				<div class="row">
					<a class="btn-large red lighten-2" href="/tamanhosPizza">Cancelar</a>
					<input type="submit" value="Salvar" class="btn-large green accent-3">
				</div>
			</form>
		</div>
	</div>
@endsection