@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">
		
		<h4>Lista de Funcionamento @isset($funcionamento)
			<a class="btn orange" href="/funcionamentoDelivery">Novo Dia</a>
		@endisset</h4>

		<form method="post" action="/funcionamentoDelivery/save">
			@csrf
			<input type="hidden" name="id" value="{{{ isset($funcionamento->id) ? $funcionamento->id : 0 }}}">
			<div class="row">
				<div class="input-field col s3">
					@if(!isset($funcionamento))
					<select name="dia">
						@foreach($dias as $d)
						<option value="{{$d}}">{{$d}}</option>
						@endforeach
					</select>
					@else
					<input type="text" name="dia" value="{{$funcionamento->dia}}" disabled="">
					@endif

				</div>
				<div class="input-field col s3">
					<input type="text" class="picker" id="inicio" name="inicio" value="{{{ isset($funcionamento->inicio_expediente) ? $funcionamento->inicio_expediente : '18:00' }}}">
					<label>Inicio</label>
					@if($errors->has('inicio'))
					<div class="center-align red lighten-2">
						<span class="white-text">{{ $errors->first('inicio') }}</span>
					</div>
					@endif
				</div>
				<div class="input-field col s3">
					<input type="text" class="picker" id="fim" name="fim" value="{{{ isset($funcionamento->fim_expediente) ? $funcionamento->fim_expediente : '23:59' }}}">
					<label>Fim</label>
					@if($errors->has('fim'))
					<div class="center-align red lighten-2">
						<span class="white-text">{{ $errors->first('fim') }}</span>
					</div>
					@endif
				</div>
				<div class="col s2">
					<button type="submit" id="btn-salvar" class="btn-large disabled">
						@if(isset($funcionamento->id))
						<span>EDITAR</span>
						@else
						<span>SALVAR</span>
						@endif
					</button>
				</div>
			</div>
			@if(count($funcionamentos) == 7)
			<h3 class="red-text center-align">Todos os dias da semana adicionados!</h3>
			@endif
		</form>

		@if(session()->has('message'))
		<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
			<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
		</div>
		@endif


		<div class="row container">
			<table class="col s12">
				<thead>
					<tr>
						<th>Dia</th>
						<th>Inicio</th>
						<th>Fim</th>
						<th>Ativo</th>
						<th>Ações</th>
					</tr>
				</thead>

				<tbody>
					@foreach($funcionamentos as $f)
					<tr class="{{$f->ativo ? 'green' : 'red'}} lighten-4">
						<th>{{ $f->dia }}</th>
						<th>{{ $f->inicio_expediente }}</th>
						<th>{{ $f->fim_expediente }}</th>
						<th>
							@if($f->ativo)
							<i class="material-icons green-text">brightness_1</i>
							@else
							<i class="material-icons red-text">brightness_1</i>

							@endif
						</th>

						<th>
							<a href="/funcionamentoDelivery/edit/{{ $f->id }}">
								<i class="material-icons left">edit</i>					
							</a>

							@if($f->ativo)
							<a title="desativar" href="/funcionamentoDelivery/alterarStatus/{{ $f->id }}">
								<i class="material-icons left red-text">close</i>					
							</a>
							@else
							<a href="/funcionamentoDelivery/alterarStatus/{{ $f->id }}">
								<i class="material-icons left green-text">check</i>					
							</a>
							@endif

						</th>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>

@endsection	