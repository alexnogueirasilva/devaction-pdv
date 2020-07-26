@extends('default.layout')
@section('content')

<div class="row">
	
	<div class="card">
		<div class="row">
			<div class="col s12">
				<h3>Cotação Referência: <strong>{{$cotacoes[0]->referencia}}</strong></h3>

				<div class="card">
					<div class="row">
						<div class="col s12">
							<h3>Fornecedores</h3>
							@foreach($cotacoes as $c)

							<h5 class="red-text">{{$c->fornecedor->razao_social}}</h5>
							@endforeach
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="card">
		<div class="row">
			<div class="col s12">
				<h4>Itens da Cotação com Melhores Resultados</h4>
				<table class="striped col s12">
					<thead>
						<tr>

							<th>Item</th>
							<th>Valor Unit</th>
							<th>Quantidade</th>
							<th>Valor Total</th>
							<th>Fornecedor</th>
						</tr>
					</thead>
					<tbody>
						@foreach($itens as $i)
						<tr>
							<td>{{$i['item']}}</td>
							<td>{{number_format($i['valor_unitario'], 2)}}</td>
							<td>{{number_format($i['quantidade'], 2)}}</td>
							<td>{{number_format($i['valor_total'], 2)}}</td>
							<td>{{$i['fornecedor']}}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="card">
		<div class="row">
			<div class="col s12">
				<h4>Melhor Resultado</h4>

				<h5>Fornecedor: <strong class="red-text">{{$melhorResultado->fornecedor->razao_social}}</strong></h5>
				<h5>Valor: <strong class="red-text">{{number_format($melhorResultado->valor, 2)}}</strong></h5>

				<a href="/cotacao/view/{{$melhorResultado->id}}" class="btn">Ir Para Cotação</a>
				<br><br>
			</div>
		</div>
	</div>

	<div class="card">
		<div class="row">
			<div class="col s12">
				<h4>Imprimir Melhor Resultado Por Fornecedor</h4>

				@foreach($fornecedores as $f)

				<div class="col s6">

					<form method="get" action="/cotacao/imprimirMelhorResultado">
						<input type="hidden" name="fornecedor" value="{{$f['fornecedor']}}">
						<input type="hidden" name="referencia" value="{{$cotacoes[0]->referencia}}">
						<button type="submit" style="width: 100%" href="" class="btn">{{$f['fornecedor']}} - Itens Ganhos: {{$f['qtd']}}</button>
					</form>
					
				</div>
				@endforeach
				<br><br>
			</div>
		</div>
	</div>



	@endsection	