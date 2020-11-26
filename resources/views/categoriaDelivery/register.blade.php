@extends('default.layout')
@section('content')
<div class="row">
	<div class="col s12">
		<h4>{{{ isset($categoria) ? "Editar": "Cadastrar" }}} Categoria de Delivery</h4>
		<form method="post" action="{{{ isset($categoria) ? '/deliveryCategoria/update': '/deliveryCategoria/save' }}}" enctype="multipart/form-data">
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

			<div class="row">
				<div class="input-field col s10">
					<textarea name="descricao" id="descricao" class="materialize-textarea">{{{ isset($categoria->descricao) ? $categoria->descricao : old('descricao') }}}</textarea>
					<label for="descricao">Descrição</label>

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
							<input value="{{{ isset($categoria->path) ? $categoria->path : old('path') }}}" name="file" accept=".png, .jpg, .jpeg" type="file">
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

				@if(isset($categoria))
				<div class="col s6">
					<img style="width: 250px; height: 200px;" src="/imagens_categorias/{{$categoria->path}}">
				</div>

				<input type="hidden" name="file_out" value="{{$categoria->path}}">
				@endif
				
			</div>

			<input type="hidden" name="_token" value="{{ csrf_token() }}">


			<br>
			<div class="row">
				<a class="btn-large red lighten-2" href="/deliveryCategoria">Cancelar</a>
				<input type="submit" value="Salvar" class="btn-large green accent-3">
			</div>
		</form>
	</div>
</div>
@endsection