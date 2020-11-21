@extends('default.layout')
@section('content')

<div class="row">

	<div class="col s12">
		<div class="row">

			<br>
			<div class="card col s12">
				<h5>Novo Apontamento de Alteração Estoque</h5>

				<p class="red-text">*Será aplicada a conversão unitária do produto, apontada no cadastro</p>

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
							<p id="convert" style="display: none; margin-top: -10px;" class="red-text">Conversão deste produto <strong id="convert-html">1</strong></p>
						</div>
						<br>
						<p class="red-text">*Produto não composto!</p>
					</div>

					<div class="row">
						<div class="input-field col s2">
							<input type="text" value="{{old('quantidade')}}" id="quantidade" name="quantidade">
							<label>Quantidade</label>
							@if($errors->has('quantidade'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('quantidade') }}</span>
							</div>
							@endif
						</div>


						<div class="input-field col s3">
							<select name="tipo" id="tipo">
								<option value="reducao">Redução de estoque</option>
								<option value="incremento">Incremento de estoque</option>
							</select>
							<label>Tipo</label>

						</div>
					</div>
					<div class="row">
						<div class="input-field col s6">
							<input type="text" id="observacao" name="observacao">
							<label>Observação</label>
							
						</div>
						<div class="col s4">
							<button class="btn-large green accent-3" type="submit">Salvar</button>

						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection	
