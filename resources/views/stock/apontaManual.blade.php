@extends('default.layout')
@section('content')

<div class="row">
	<div class="card col s12">
		<div class="row">

			<br>
			<h5>Novo Apontamento</h5>
			<form class="" method="post" action="/estoque/saveApontamentoManual">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<div class="row">
					<div class="input-field col s6">
						<input autocomplete="off" type="text" name="produto" id="autocomplete-produto" class="autocomplete-produto">
						<label for="autocomplete-produto">Produto</label>
						@if($errors->has('produto'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('produto') }}</span>
						</div>
						@endif
					</div>
					<br>
					<p class="red-text">*Produto não composto!</p>
				</div>
				<div class="row">
					<div class="input-field col s4">
						<input type="text" value="{{old('quantidade')}}" id="quantidade" name="quantidade">
						<label>Quantidade</label>
						@if($errors->has('quantidade'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('quantidade') }}</span>
						</div>
						@endif
					</div>

					<div class="col s4">
						<button class="btn-large green accent-3" type="submit">Salvar</button>

					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection	
