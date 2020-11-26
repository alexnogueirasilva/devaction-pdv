@extends('default.layout')
@section('content')
<div class="row">
	<div class="col s12">
		<h4>Atualizar Preço</h4>

		<h5>Produto: <strong class="red-text">{{$produto->produto->nome}}</strong></h5>
		<form method="post" action="/listaDePrecos/salvarPreco" >
		<input type="hidden" name="id" value="{{$produto->id}}">

		<div class="row">
			

			<div class="input-field col s2">
				<input value="{{{ isset($produto->valor) ? $produto->valor : old('novo_valor') }}}" id="novo_valor" name="novo_valor" type="text" class="validate" required="">
				<label for="percentual_alteracao">Valor</label>

				@if($errors->has('novo_valor'))
				<div class="center-align red lighten-2">
					<span class="white-text">{{ $errors->first('novo_valor') }}</span>
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