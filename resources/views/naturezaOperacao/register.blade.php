@extends('default.layout')
@section('content')
<div class="row">
	<div class="col s12">
		<h4>{{{ isset($natureza) ? "Editar": "Cadastrar" }}} Natureza de Operação</h4>
		<form method="post" action="{{{ isset($natureza) ? '/naturezaOperacao/update': '/naturezaOperacao/save' }}}">
			<input type="hidden" name="id" value="{{{ isset($natureza->id) ? $natureza->id : 0 }}}">

			<div class="row">
				<div class="input-field col s6">
					<input value="{{{ isset($natureza->natureza) ? $natureza->natureza : old('natureza') }}}" id="natureza" name="natureza" type="text" class="validate upper-input">
					<label for="natureza">Nome</label>

					@if($errors->has('natureza'))
					<div class="center-align red lighten-2">
						<span class="white-text">{{ $errors->first('natureza') }}</span>
					</div>
					@endif

				</div>
			</div>

			<div class="col s4">
				<div class="card">
					<div class="row">
						<div class="col s12"><br>
							<legend>CFOP INTERNO</legend>
						</div>
						<div class="input-field col s6">
							<input value="{{{ isset($natureza->CFOP_saida_estadual) ? $natureza->CFOP_saida_estadual : old('CFOP_saida_estadual') }}}" id="CFOP_saida_estadual" name="CFOP_saida_estadual" type="text" class="validate">
							<label for="CFOP_saida_estadual">Venda</label>

							@if($errors->has('CFOP_saida_estadual'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('CFOP_saida_estadual') }}</span>
							</div>
							@endif

						</div>
						<div class="input-field col s6">
							<input value="{{{ isset($natureza->CFOP_entrada_estadual) ? $natureza->CFOP_entrada_estadual : old('CFOP_entrada_estadual') }}}" id="CFOP_entrada_estadual" name="CFOP_entrada_estadual" type="text" class="validate">
							<label for="CFOP_entrada_estadual">Entrada</label>

							@if($errors->has('CFOP_entrada_estadual'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('CFOP_entrada_estadual') }}</span>
							</div>
							@endif

						</div>
					</div>
				</div>
			</div>

			<div class="col s4">
				<div class="card">
					<div class="row">
						<div class="col s12"><br>
							<legend>CFOP INTERESTADUAL</legend>
						</div>
						<div class="input-field col s6">
							<input value="{{{ isset($natureza->CFOP_saida_inter_estadual) ? $natureza->CFOP_saida_inter_estadual : old('CFOP_saida_inter_estadual') }}}" id="CFOP_saida_inter_estadual" name="CFOP_saida_inter_estadual" type="text" class="validate">
							<label for="natureza">Venda</label>

							@if($errors->has('CFOP_saida_inter_estadual'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('CFOP_saida_inter_estadual') }}</span>
							</div>
							@endif

						</div>
						<div class="input-field col s6">
							<input value="{{{ isset($natureza->CFOP_entrada_inter_estadual) ? $natureza->CFOP_entrada_inter_estadual : old('CFOP_entrada_inter_estadual') }}}" id="CFOP_entrada_inter_estadual" name="CFOP_entrada_inter_estadual" type="text" class="validate">
							<label for="CFOP_entrada_inter_estadual">Entrada</label>

							@if($errors->has('CFOP_entrada_inter_estadual'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('CFOP_entrada_inter_estadual') }}</span>
							</div>
							@endif

						</div>
					</div>
				</div>
			</div>


			<input type="hidden" name="_token" value="{{ csrf_token() }}">


			<br>
			<div class="row">
				<div class="col s12">
					<a class="btn-large red lighten-2" href="/naturezaOperacao">Cancelar</a>
					<input type="submit" value="Salvar" class="btn-large green accent-3">
				</div>
				
			</div>
		</form>
	</div>
</div>
@endsection