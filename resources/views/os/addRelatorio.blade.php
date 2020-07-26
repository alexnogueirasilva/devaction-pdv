@extends('default.layout')
@section('content')
<div class="row">
	<div class="col s12">
		<h4>{{{ isset($relatorio) ? "Editar": "Cadastrar" }}} Relatório</h4>
		<form method="post" action="{{{ isset($relatorio) ? '/ordemServico/updateRelatorio': '/ordemServico/addRelatorio' }}}">
			<input type="hidden" name="ordemId" value="{{$ordem->id}}">
			<input type="hidden" name="id" value="{{isset($relatorio) ? $relatorio->id : 0}}">

			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<br>
			@if(isset($relatorio))
			<h6 class="grey-text">*Adicione um texto para este relatório {{$ordem->id}}</h6>
			@else
			<h6 class="grey-text">*Edite o texto para este relatório {{$ordem->id}}</h6>

			@endif
			<div class="row">
				<div class="row">
					<div class="input-field col s12">
						<textarea id="textarea1" name="texto" class="materialize-textarea">
							{{{ isset($relatorio->texto) ? $relatorio->texto : old('texto') }}}
						</textarea>
						<label for="textarea1">Texto</label>

						@if($errors->has('texto'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('texto') }}</span>
						</div>
						@endif
					</div>
				</div>
			</div>

			<div class="row">
				<a class="btn-large red lighten-2" href="/ordemServico/servicosordem/{{$ordem->id}}">Cancelar</a>
				<input type="submit" value="Salvar" class="btn-large green accent-3">
			</div>
		</form>
	</div>
</div>
@endsection