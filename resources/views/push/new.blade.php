@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">
		<form method="post" action="{{{ isset($push) ? '/push/update': '/push/save' }}}">
			@csrf

			
			<h4>{{{ isset($push) ? "Editar": "Cadastrar" }}} Notificação Push</h4>
			@if(!isset($titulo) && !isset($mensagem))

			<input type="hidden" name="id" value="{{{isset($push) ? $push->id : 0}}}">
			<div class="row">
				<div class="col s4">
					<div class="input-field">
						<input value="{{{ isset($push) ? $push->titulo : old('titulo') }}}" type="text" name="titulo" data-length="50">
						<label>Titulo</label>
					</div>
					@if($errors->has('titulo'))
					<div class="center-align red lighten-2">
						<span class="white-text">{{ $errors->first('titulo') }}</span>
					</div>
					@endif
				</div>
			</div>

			<div class="row">
				<div class="col s10">
					<div class="input-field">
						<input value="{{{ isset($push) ? $push->texto : old('texto') }}}" type="text" name="texto" data-length="100">
						<label>Texto</label>
					</div>
					@if($errors->has('texto'))
					<div class="center-align red lighten-2">
						<span class="white-text">{{ $errors->first('texto') }}</span>
					</div>
					@endif
				</div>
			</div>

			<div class="row">
				<div class="col s8">
					<div class="input-field">
						<input value="{{{ isset($push) ? $push->path_img : old('path_img') }}}" type="text" id="path_img" name="path_img" data-length="200">
						<label>Endereço da Imagem (opcional)</label>
					</div>
					
				</div>
				<div class="col s3">
					<div class="input-field">
						<input value="{{{ isset($push) ? $push->referencia_produto : old('referencia_produto') }}}" type="text" id="referencia_produto" name="referencia_produto" data-length="100">
						<label>Código do Produto (opcional)</label>
					</div>
					
				</div>
			</div>

			
			@else

			<div class="row">
				<div class="col s4">
					<div class="input-field">
						<input value="{{{ isset($titulo) ? $titulo : old('titulo') }}}" type="text" name="titulo" data-length="50">
						<label>Titulo</label>
					</div>
					@if($errors->has('titulo'))
					<div class="center-align red lighten-2">
						<span class="white-text">{{ $errors->first('titulo') }}</span>
					</div>
					@endif
				</div>
			</div>

			<div class="row">
				<div class="col s10">
					<div class="input-field">
						<input value="{{{ isset($mensagem) ? $mensagem : old('texto') }}}" type="text" name="texto" data-length="100">
						<label>Texto</label>
					</div>
					@if($errors->has('texto'))
					<div class="center-align red lighten-2">
						<span class="white-text">{{ $errors->first('texto') }}</span>
					</div>
					@endif
				</div>
			</div>

			<div class="row">
				<div class="col s8">
					<div class="input-field">
						<input value="{{{ isset($imagem) ? getenv('PATH_URL').'/imagens_produtos/'.$imagem : old('path_img') }}}" type="text" id="path_img" name="path_img" data-length="100">
						<label>Endereço da Imagem (opcional)</label>
					</div>
					
				</div>
				<div class="col s3">
					<div class="input-field">
						<input value="{{{ isset($referencia) ? $referencia : old('referencia_produto') }}}" type="text" id="referencia_produto" name="referencia_produto" data-length="100">
						<label>Código do Produto (opcional)</label>
					</div>
					
				</div>
			</div>


			@endif

			<div id="div-img" style="display: none">
				<div class="row">
					<div class="col s12">
						<img src="" style="width: 300px; height: 200px" id="img-view">
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col s12">
					@isset($push)
					@if($push->cliente)
					<p class="red-text">Notificaçao para cliente 
						<strong>{{$push->cliente->nome}}</strong></p><br>

					@else
					<p class="red-text">Notificaçao para todos os clientes</p><br>
					@endif
					@endisset
				</div>
				@if(!isset($push))
				<div class="col s3">
					Todos os Clientes
					<div class="switch">
						<label class="">
							Não
							<input id="todos" name="todos" class="red-text" type="checkbox">
							<span class="lever"></span>
							Sim
						</label>
					</div>
				</div>
				<div class="input-field col s6" id="cliente">
					<i class="material-icons prefix">person</i>
					<input autocomplete="off" value="{{{ isset($cliente) ? $cliente : old('cli') }}}" type="text" name="cli" id="autocomplete-cliente" class="autocomplete-cliente">
					<label for="autocomplete-cliente">Cliente do Delivery</label>
					@if($errors->has('cliente'))
					<div class="center-align red lighten-2">
						<span class="white-text">{{ $errors->first('cliente') }}</span>
					</div>
					@endif
				</div>
				@endisset
			</div>

			<div class="row">
				<a href="/push" class="btn-large red">Cancelar</a>
				<button type="submit" class="btn-large green accent-3">Salvar</button>
			</div>

			
		</form>
	</div>
</div>
@endsection	