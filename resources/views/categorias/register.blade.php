@extends('default.layout')
@section('content')
<div class="row">
	<div class="col s12">
		<h4>{{{ isset($categoria) ? "Editar": "Cadastrar" }}} Categoria</h4>
		<form method="post" action="{{{ isset($categoria) ? '/categorias/update': '/categorias/save' }}}" 
		enctype="multipart/form-data">
		<input type="hidden" name="id" value="{{{ isset($categoria->id) ? $categoria->id : 0 }}}">

		<div class="row">
			<div class="input-field col s4">
				<input value="{{{ isset($categoria->nome) ? $categoria->nome : old('nome') }}}" id="nome" name="nome" type="text" class="validate">
				<label for="nome">Nome</label>

				@if($errors->has('nome'))
				<div class="center-align red lighten-2">
					<span class="white-text">{{ $errors->first('nome') }}</span>
				</div>
				@endif

			</div>
		</div>

		@if(!isset($categoria))
		@if(getenv('DELIVERY') == 1)
		<div class="row">
			<div class="col s2">
				<label>Atribuir ao Delivery</label>

				<div class="switch">
					<label class="">
						Não
						<input value="true" @if(old('atribuir_delivery')) checked @endif name="atribuir_delivery" class="red-text" type="checkbox" id="atribuir_delivery">
						<span class="lever"></span>
						Sim
					</label>
				</div>
			</div>
		</div>
		@endif


		<div id="imagem" style="display: none"> 

			<div class="row">
				<div class="input-field col s10">
					<input value="{{{ old('descricao') }}}" id="descricao" name="descricao" type="text" class="validate">
					<label for="descricao">Descricão</label>

					@if($errors->has('descricao'))
					<div class="center-align red lighten-2">
						<span class="white-text">{{ $errors->first('descricao') }}</span>
					</div>
					@endif

				</div>
			</div>
			<div class="row">
				<div class="col s6">
					<div class="file-field input-field">
						<div class="btn black">
							<span>Imagem</span>
							<input value="{{{old('path') }}}" name="file" accept=".png, .jpg, .jpeg" type="file">
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
			</div>
		</div>
		@endif

		<input type="hidden" name="_token" value="{{ csrf_token() }}">


		<br>
		<div class="row">
			<a class="btn-large red lighten-2" href="/categorias">Cancelar</a>
			<input type="submit" value="Salvar" class="btn-large green accent-3">
		</div>
	</form>
</div>
</div>
@endsection