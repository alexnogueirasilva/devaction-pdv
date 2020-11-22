@extends('default.layout')
@section('content')
<div class="row">
	<div class="col s12">
		<h4>{{{ isset($lista) ? "Editar": "Cadastrar" }}} Lista de Preço</h4>
		<form method="post" action="{{{ isset($lista) ? '/listaDePrecos/update': '/listaDePrecos/save' }}}" >
		<input type="hidden" name="id" value="{{{ isset($lista->id) ? $lista->id : 0 }}}">

		<div class="row">
			<div class="input-field col s6">
				<input value="{{{ isset($lista->nome) ? $lista->nome : old('nome') }}}" id="nome" name="nome" type="text" class="validate" data-length="40">
				<label for="nome">Nome</label>

				@if($errors->has('nome'))
				<div class="center-align red lighten-2">
					<span class="white-text">{{ $errors->first('nome') }}</span>
				</div>
				@endif

			</div>

			<div class="input-field col s2">
				<input value="{{{ isset($lista->percentual_alteracao) ? $lista->percentual_alteracao : old('percentual_alteracao') }}}" id="percentual_alteracao" name="percentual_alteracao" type="text" class="validate">
				<label for="percentual_alteracao">Percentual de Alteração</label>

				@if($errors->has('percentual_alteracao'))
				<div class="center-align red lighten-2">
					<span class="white-text">{{ $errors->first('percentual_alteracao') }}</span>
				</div>
				@endif

			</div>
		</div>


		@csrf


		<br>
		<div class="row">
			<a class="btn-large red lighten-2" href="/listaDePrecos">Cancelar</a>
			<input type="submit" value="Salvar" class="btn-large green accent-3">
		</div>
	</form>
</div>
</div>
@endsection