@extends('default.layout')
@section('content')
<div class="row">
	<div class="col s12">
		<h4>{{{ isset($ordem) ? "Editar": "Adicionar" }}} Ordem de Serviço</h4>
		<form method="post" action="{{{ isset($ordem) ? '/ordemServico/update': '/ordemServico/save' }}}">
			<input type="hidden" name="id" value="{{{ isset($ordem->id) ? $ordem->id : 0 }}}">

			<section class="section-1">
				<div class="row">
					<div class="col s12">
						<div class="input-field col s6">
							<i class="material-icons prefix">person</i>
							<input autocomplete="off" type="text" name="cliente" id="autocomplete-cliente" class="autocomplete-cliente">
							<label for="autocomplete-cliente">Cliente</label>
							@if($errors->has('cliente'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('cliente') }}</span>
							</div>
							@endif
							
						</div>
					</div>
				</div>
				<div class="row">
					<div class="input-field col s12">
						<i class="material-icons prefix">short_text</i>
						<textarea id="descricao" name="descricao" class="materialize-textarea">{{{ isset($os->descricao) ? $os->descricao : old('descricao') }}}</textarea>
						<label for="descricao">Descrição da OS</label>
						@if($errors->has('descricao'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('descricao') }}</span>
						</div>
						@endif
					</div>
				</div>

				

			</section>

			<input type="hidden" name="_token" value="{{ csrf_token() }}">

			<br>
			<div class="row">
				<a class="btn-large red lighten-2" href="/ordemServico">Cancelar</a>
				<input type="submit" value="Salvar" class="btn-large green accent-3">
			</div>
		</form>
	</div>
</div>
@endsection