@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">

		<h4>Galeria do Produto <strong class="orange-text">{{$produto->produto->nome}}</strong></h4>

		@if(session()->has('message'))
		<div class="row">
			<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
				<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
			</div>
		</div>
		@endif

		@if(count($produto->galeria) < 3)
		<form method="post" action="/deliveryProduto/saveImagem" enctype="multipart/form-data">

			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="id" value="{{ $produto->id }}">
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

				<div class="col s3">
					<button class="btn-large green accent-3" type="submit">Salvar</button>
				</div>
			</div>
		</form>
		@endif

		@if(count($produto->galeria) > 0)
		@foreach($produto->galeria as $v => $g)
		<img style="width: 300px; height: 200px;" src="/imagens_produtos/{{$g->path}}">
		<a href="/deliveryProduto/deleteImagem/{{$g->id}}" class="btn red">Remover</a>
		<p>Imagem {{$v+1}}</p>

		@endforeach
		@else
		<h4 class="center-align red-text">Nenhum imagem cadastrada</h4>
		@endif

		
	</div>
</div>
@endsection	