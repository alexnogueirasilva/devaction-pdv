@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">

		<h4>Estoque</h4>

		<div class="row">
			<a href="/estoque/apontamentoManual" class="btn red">
				<i class="material-icons left">inbox</i>	
				Apontamento Manual		
			</a>
			<a target="_blank" href="/estoque/listApontamentos" class="btn blue">
				<i class="material-icons left">inbox</i>	
				Listar Alterações		
			</a>
		</div>

		<div class="row">
			<br>
			<!-- <div class="row">
				<i class="material-icons red-text">
					brightness_1
				</i> Itens sem valor de venda
				<br>
				<i class="material-icons yellow-text">
					brightness_1
				</i> Itens com valor de compra maior que valor de venda
			</div> -->
			<div class="col s12">
				<label>Numero de registros: {{count($estoque)}}</label>					
			</div>

			<table class="col s12">
				<thead>
					<tr>
						<th>Produto</th>
						<th>Categoria</th>
						<th>Quantidade</th>
						<th>Un. Compra</th>
						<th>Un. Venda</th>
						<th>Valor de Compra</th>
						<th>Subtotal</th>
					</tr>
				</thead>

				<tbody>
					<?php 
					$subtotal = 0;
					?>
					@foreach($estoque as $e)

					<tr>

						<td>{{$e->produto->nome}} | {{$e->produto->cor}}</td>
						<td>{{$e->produto->categoria->nome}}</td>
						<td>{{$e->quantidade}}</td>
						<td>{{$e->produto->unidade_compra}}</td>
						<td>{{$e->produto->unidade_venda}}</td>
						<td>{{ number_format($e->valor_compra, 2, ',', '.') }} {{$e->produto->unidade_compra}}</td>
						<td>{{ number_format($e->valorCompraUnitário(), 2, ',', '.') }}</td>

						<?php 
						$subtotal += $e->valorCompraUnitário();
						?>

					</tr>
					@endforeach
					<tr class="green lighten-4 gray-text">
						<td colspan="6" class="center-align">TOTAL EM ESTOQUE</td>
						<td>{{ number_format($subtotal, 2, ',', '.') }}</td>
					</tr>
				</tbody>
			</table>
		</div>

		@if(isset($links))
		<ul class="pagination center-align">
			<li class="waves-effect">{{$estoque->links()}}</li>
		</ul>
		@endif

		


	</div>
</div>
@endsection	