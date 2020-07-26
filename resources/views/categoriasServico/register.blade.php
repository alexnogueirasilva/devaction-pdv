@extends('default.layout')
@section('content')
	<div class="row">
		<div class="col s12">
			<h4>{{{ isset($categoria) ? "Editar": "Cadastrar" }}} Categoria de Serviço</h4>
			<form method="post" action="{{{ isset($categoria) ? '/categoriasServico/update': '/categoriasServico/save' }}}">
				<input type="hidden" name="id" value="{{{ isset($categoria->id) ? $categoria->id : 0 }}}">
				
				<div class="row">
					<div class="input-field col s6">
			          <input value="{{{ isset($categoria->nome) ? $categoria->nome : old('nome') }}}" id="nome" name="nome" type="text" class="validate">
			          <label for="nome">Nome</label>
			          
			          @if($errors->has('nome'))
			          <div class="center-align red lighten-2">
			          	<span class="white-text">{{ $errors->first('nome') }}</span>
			          </div>
			          @endif

			        </div>
				</div>

				<input type="hidden" name="_token" value="{{ csrf_token() }}">

				
				<br>
				<div class="row">
					<a class="btn-large red lighten-2" href="/categoriasServico">Cancelar</a>
					<input type="submit" value="Salvar" class="btn-large green accent-3">
				</div>
			</form>
		</div>
	</div>
@endsection