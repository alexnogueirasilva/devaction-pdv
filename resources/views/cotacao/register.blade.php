

@extends('default.layout')
@section('content')

<div class="row" id="anime" style="display: none">
	<div class="col s8 offset-s2">
		<lottie-player 
		src="/anime/success-upload.json" background="transparent" speed="0.8" style="width: 100%; height: 300px;" autoplay >
	</lottie-player>
</div>
</div>

<div class="row" id="content" style="display: block">
	<div class="col s12">
		<div class="row">
			<div class="input-field col s6">
				<i class="material-icons prefix">person</i>
				<input autocomplete="off" type="text" name="fornecedor" id="autocomplete-fornecedor" class="autocomplete-fornecedor">
				<label for="autocomplete-fornecedor">Fornecedor</label>
				@if($errors->has('fornecedor'))
				<div class="center-align red lighten-2">
					<span class="white-text">{{ $errors->first('fornecedor') }}</span>
				</div>
				@endif
			</div>
		</div>
		<input type="hidden" id="_token" value="{{ csrf_token() }}">

		<div class="card">
			<div class="row">
				<div class="col s12">
					<h4>Itens da Cotação</h4>

					<div class="row">
						<div class="input-field col s4">
							<i class="material-icons prefix">inbox</i>
							<input autocomplete="off" type="text" name="produto" id="autocomplete-produto" class="autocomplete-produto">
							<label for="autocomplete-produto">Produto</label>
							@if($errors->has('produto'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('produto') }}</span>
							</div>
							@endif
						</div>

						<div class="input-field col s2">
							<input type="text" id="quantidade">
							<label>Quantidade</label>
						</div>

						<div class="col s2">
							<a id="addProd" href="#!" class="btn-large green accent-3">
								<i class="material-icons">add</i>
							</a>
						</div>
					</div>

					<div class="row">
						<h6>ITENS</h6>
						<table class="col s8 striped">
							<thead>
								<tr>
									<th>#</th>
									<th>Código Produto</th>
									<th>Produto</th>
									<th>Quantidade</th>
									<th>Ações</th>
								</tr>
							</thead>
							<tbody id="body">
								
							</tbody>

						</table>

					</div>

					<div class="row">
						<h5>Total de Itens: <strong class="blue-text" id="total_itens">0</strong></h5>
						
					</div>

				</div>
			</div>
		</div>

		<div class="card">
			<div class="row">
				<div class="col s12">
					<h4>Dados Adicionais</h4>
					<div class="row">
						<div class="col s6 input-field">
							<input type="text" id="referencia" data-length="20">
							<label>Referencia <strong class="red-text">(Necessário para clonar)</strong></label>
						</div>
					</div>
					<div class="row">
						<div class="col s10 input-field">
							<input type="text" id="obs" data-length="100">
							<label>Observação</label>
						</div>

					</div>
					<div class="row">
						<div class="col s3">
							<a href="/cotacao" class="btn-large red cancelar">Cancelar</a>

							<a href="#!" id="salvar-cotacao" class="btn-large green accent-3">Salvar</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection	