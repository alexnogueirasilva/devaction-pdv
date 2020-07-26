@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">

		<h3>Alterar Estado da OS <strong>{{$ordem->id}}</strong></h3>
		<h4>Estado Atual: <strong>
			@if($ordem->estado == 'pd') 
			PENDENTE
			@elseif($ordem->estado == 'ap')
			APROVADO
			@elseif($ordem->estado == 'rp')
			REPROVADO
			@else
			FINALIZADO
			@endif
		</strong>

		@if($ordem->estado != 'fz' && $ordem->estado != 'rp')

		<form method="post" action="/ordemServico/alterarEstado">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<input type="hidden" name="id" value="{{$ordem->id}}">
			<div class="row">
				<div class="col s4 input-field">
					@if($ordem->estado == 'pd')
					<select name="novo_estado">
						<option value="ap">APROVADO</option>
						<option value="rp">REPROVADO</option>
					</select>

					@elseif($ordem->estado == 'ap')
					<select name="novo_estado">
						<option value="fz">FINALIZADO</option>
					</select>
					@endif
				</div>
			</div>

			<button type="submit" class="btn green accent-3">Alterar</button>
		</form>
		@elseif($ordem->estado == 'fz')
		<h5>Ordem de Serviço finalizada!</h5>

		<a href="/ordemServico" class="btn orange">Voltar</a>

		@else
		<h5>Ordem de Serviço reprovada!</h5>

		<a href="/ordemServico" class="btn red">Voltar</a>

		@endif
		
	</h4>
</div>
</div>
@endsection	