@extends('default.layout')
@section('content')
	<div class="row">
		<div class="col s12">
			<h4>{{{ isset($complemento) ? "Editar": "Cadastrar" }}} Adicional</h4>
			<form method="post" action="{{{ isset($complemento) ? '/deliveryComplemento/update': '/deliveryComplemento/save' }}}">
				<input type="hidden" name="id" value="{{{ isset($complemento->id) ? $complemento->id : 0 }}}">
				
				<div class="row">
					<div class="input-field col s6">
			          <input value="{{{ isset($complemento->nome) ? $complemento->nome : old('nome') }}}" id="nome" name="nome" type="text" class="validate">
			          <label for="nome">Nome</label>
			          
			          @if($errors->has('nome'))
			          <div class="center-align red lighten-2">
			          	<span class="white-text">{{ $errors->first('nome') }}</span>
			          </div>
			          @endif

			        </div>
				</div>

				<div class="row">
					<div class="input-field col s4">
			          <input value="{{{ isset($complemento->valor) ? $complemento->valor : old('valor') }}}" id="valor" name="valor" type="text" class="validate">
			          <label for="valor">Valor</label>
			          
			          @if($errors->has('valor'))
			          <div class="center-align red lighten-2">
			          	<span class="white-text">{{ $errors->first('valor') }}</span>
			          </div>
			          @endif

			        </div>
				</div>

				<input type="hidden" name="_token" value="{{ csrf_token() }}">

				
				<br>
				<div class="row">
					<a class="btn-large red lighten-2" href="/deliveryComplemento">Cancelar</a>
					<input type="submit" value="Salvar" class="btn-large green accent-3">
				</div>
			</form>
		</div>
	</div>
@endsection