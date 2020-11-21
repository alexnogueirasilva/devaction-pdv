

@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">
		<h4>Lista de Cotações Por Referência</h4>


		@if(session()->has('message'))
		<div class="row">
			<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
				<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
			</div>
		</div>
		@endif


		<form method="get" action="/cotacao/listaPorReferencia/filtro">
			<div class="row">
				<div class="col s4 input-field">
					<input value="{{{ isset($fornecedor) ? $fornecedor : '' }}}" type="text" class="validate" name="fornecedor">
					<label>Fornecedor</label>
				</div>

				<div class="col s2 input-field">
					<input value="{{{ isset($data_inicial) ? $data_inicial : '' }}}" type="text" class="datepicker" name="data_inicial">
					<label>Data Inicial</label>
				</div>
				<div class="col s2 input-field">
					<input value="{{{ isset($data_final) ? $data_final : '' }}}" type="text" class="datepicker" name="data_final">
					<label>Data Final</label>
				</div>

				<div class="col s2">
					<button type="submit" class="btn-large black">
						<i class="material-icons">search</i>
					</button>
				</div>
			</div>
		</form>


		<div class="row">
			<div class="col s12 m12 l12">
				<label>Numero de registros: {{count($cotacoes)}}</label>					
			</div>
			
			<table class="">
				<thead>
					<tr>
						<th>Referência</th>
						<th>Total de Itens</th>
						<th>Total de Fornecedores</th>
						<th>Maior Valor</th>
						<th>Menor Valor</th>
						<th>Data Criação</th>
						<th>Estado</th>
						<th>Ações</th>

					</tr>
				</thead>

				<tbody>
					@foreach($cotacoes as $c)
					<tr>
						<td>{{$c->referencia}}</td>
						<td>{{$c->contaItens()}}</td>
						<td>{{$c->contaFornecedores()}}</td>
						<td>{{number_format($c->getValores(true), 2)}}</td>
						<td>{{number_format($c->getValores(), 2)}}</td>
						<td>{{ \Carbon\Carbon::parse($c->created_at)->format('d/m/Y H:i:s')}}</td>
						<td>
							@if(!$c->escolhida())
							<i class="material-icons red-text">lens</i>
							@else
							<i class="material-icons green-text">lens</i>
							@endif
						</td>

						<td>
							<a href="/cotacao/referenciaView/{{ $c->referencia }}">
								<i class="material-icons left green-text">visibility</i>			
							</a>
						</td>

					</tr>
					@endforeach
				</tbody>

			</table>
		</div>

	</div>
</div>

@endsection	