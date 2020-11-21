@extends('default.layout')
@section('content')
	<div class="row">
		<div class="col s12">
			<h4>{{{ isset($bairro) ? "Editar": "Cadastrar" }}} Bairro</h4>
			<form method="post" action="{{{ isset($bairro) ? '/bairrosDelivery/update': '/bairrosDelivery/save' }}}">
				<input type="hidden" name="id" value="{{{ isset($bairro->id) ? $bairro->id : 0 }}}">
				
				<div class="row">
					<div class="input-field col s6">
			          <input value="{{{ isset($bairro->nome) ? $bairro->nome : old('nome') }}}" id="nome" name="nome" type="text" class="validate">
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
			          <input value="{{{ isset($bairro->valor_entrega) ? $bairro->valor_entrega : old('valor_entrega') }}}" id="valor_entrega" name="valor_entrega" type="text" class="validate">
			          <label for="valor_entrega">Valor de Entrega</label>
			          
			          @if($errors->has('valor_entrega'))
			          <div class="center-align red lighten-2">
			          	<span class="white-text">{{ $errors->first('valor_entrega') }}</span>
			          </div>
			          @endif

			        </div>
				</div>

				<input type="hidden" name="_token" value="{{ csrf_token() }}">

				
				<br>
				<div class="row">
					<a class="btn-large red lighten-2" href="/bairrosDelivery">Cancelar</a>
					<input type="submit" value="Salvar" class="btn-large green accent-3">
				</div>
			</form>
		</div>
	</div>
@endsection