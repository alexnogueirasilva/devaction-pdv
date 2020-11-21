@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">

		<h4>Controle de Comandas</h4>

		<div class="row">
			<br>
			<form method="get" action="/pedidos/filtroComanda">
				<div class="row">


					<div class="col s2 input-field">
						<input value="{{{ isset($comanda) ? $comanda : '' }}}" type="text" class="validate" name="numero_comanda">
						<label>Comanda</label>
					</div>

					<div class="col s2 input-field">
						<input value="{{{ isset($dataInicial) ? $dataInicial : '' }}}" type="text" class="datepicker" name="data_inicial">
						<label>Data Inicial</label>
					</div>
					<div class="col s2 input-field">
						<input value="{{{ isset($dataFinal) ? $dataFinal : '' }}}" type="text" class="datepicker" name="data_final">
						<label>Data Final</label>
					</div>


					<div class="col s2">
						<button type="submit" class="btn-large black">
							<i class="material-icons">search</i>
						</button>
					</div>
				</div>
			</form>

			@if(session()->has('message'))
			<div class="row">
				<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
					<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
				</div>
			</div>
			@endif
			<div class="col s12">
				<label>Numero de registros: {{count($comandas)}}</label>	
				<p class="red-text">{{$mensagem}}</p>				
				<p class="red-text">*Comanda em vermelho contém produtos deletados</p>				
			</div>

			<table class="col s12">
				<thead>
					<tr>
						<th>ID</th>
						<th>Comanda</th>
						<th>Obs</th>
						<th>Valor</th>
						<th>Data de Criação</th>
						<th>Data de Finalização</th>
						<th>Ações</th>
					</tr>
				</thead>

				<tbody id="body">
					<?php 
					$total = 0;
					?>
					@foreach($comandas as $v)

					<tr class="@if($v->temItemDeletetado()) red lighten-4 @endif">
						<td>{{$v->id}}</td>
						<td>{{$v->comanda}}</td>
						<td>{{$v->observacao}}</td>
						<td>{{number_format($v->somaItems(), 2)}}</td>
						<td>{{ \Carbon\Carbon::parse($v->created_at)->format('d/m/Y H:i:s')}}</td>
						<td>{{ \Carbon\Carbon::parse($v->updated_at)->format('d/m/Y H:i:s')}}</td>
							
						<td>
							<a target="_blank" href="/pedidos/verDetalhes/{{$v->id}}">
								<i class="material-icons">list</i>
							</a>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>



		@if(isset($links))
		<ul class="pagination center-align">
			<li class="waves-effect">{{$comandas->links()}}</li>
		</ul>
		@endif

	</div>
</div>


@endsection	