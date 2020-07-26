@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">

		<h4>Todos os Apontamentos</h4>

		<form method="get" action="/estoque/filtroApontamentos">
			<div class="row">
				<div class="input-field col s3">
					<input value="{{isset($dataInicial) ? $dataInicial : ''}}" type="text" class="datepicker" name="dataInicial">
					<label>Data Inicial</label>
				</div>
				<div class="input-field col s3">
					<input value="{{isset($dataFinal) ? $dataFinal : ''}}" type="text" class="datepicker" name="dataFinal">
					<label>Data Final</label>
				</div>
				<div class="col s2">
					<button class="btn-large black" type="submit">
						<i class="material-icons">search</i>
					</button>
				</div>
			</div>
		</form>

		<div class="row">
			<br>
			<div class="col s12">
				<label>Numero de registros: {{count($apontamentos)}}</label>					
			</div>

			<table class="col s12 striped">
				<thead>
					<tr>
						<th>Produto</th>
						<th>Quantidade</th>
						<th>Data do Registro</th>
						<th>Un. Compra</th>
						<th>Un. Venda</th>
						<th>Valor de Venda</th>
						<th>Ações</th>
					</tr>
				</thead>

				<tbody>
					<?php 
					$somaQuatidade = 0;
					?>
					@foreach($apontamentos as $a)

					<tr>

						<td>{{$a->produto->nome}}</td>
						<td>{{$a->quantidade}}</td>
						<td>{{ \Carbon\Carbon::parse($a->data_registro)->format('d/m/Y H:i:s')}}</td>
						<td>{{$a->produto->unidade_compra}}</td>
						<td>{{$a->produto->unidade_venda}}</td>
						<td>{{number_format($a->produto->valor_venda, 2, ',', '.') }}</td>
						<td>
							<a onclick = "if (! confirm('Deseja excluir este registro? O estoque de produtos será alterado!')) { return false; }" title="Remover Apontamento" 
							href="/estoque/deleteApontamento/{{$a->id}}">
							<i class="material-icons red-text">delete</i>
						</a>
					</td>
					<?php 
					$somaQuatidade += $a->quantidade;
					?>

				</tr>
				@endforeach
				<tr class="red lighten-4 gray-text">
					<td class="center-align">Total</td>
					<td>{{ number_format($somaQuatidade, 3, ',', '.') }}</td>
					<td colspan="5"></td>
				</tr>
			</tbody>
		</table>
	</div>

	<!-- links -->
	@if($links)
	<ul class="pagination center-align">
		<li class="waves-effect">{{$apontamentos->links()}}</li>
	</ul>
	@endif
	<!-- fim links -->

	
</div>
</div>
@endsection	