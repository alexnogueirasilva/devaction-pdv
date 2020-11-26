@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">

		<h4>Lista de Preço <strong class="blue-text">{{$lista->nome}}</strong></h4>
		<h4>Percentual de alteração: <strong class="red-text">{{$lista->percentual_alteracao}}%</strong></h4>

		@if(session()->has('message'))
		<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
			<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
		</div>
		@endif

		<h5>Total de produtos cadastrados no sistema: <strong class="red-text">{{sizeof($produtos)}}</strong></h5>

		@if(sizeof($lista->itens) > 0)

		<table>
			<thead>
				<tr>
					<th>Produto</th>
					<th>Valor venda padrão</th>
					<th>Valor de compra</th>
					<th>Valor venda da lista</th>
					<th>Percentual de lucro</th>
					<th>Ações</th>
				</tr>
			</thead>

			<tbody>
				@foreach($lista->itens as $i)
				<tr>
					<td>{{$i->produto->nome}}</td>
					<td>{{number_format($i->produto->valor_venda, 2)}}</td>
					<td>{{number_format($i->produto->valor_compra, 2)}}</td>
					<td>{{number_format($i->valor, 2)}}</td>
					<td>{{number_format($i->percentual_lucro, 2)}}</td>
					<td>
						<a href="/listaDePrecos/editValor/{{ $i->id }}">
							<i class="material-icons left">edit</i>					
						</a>
					</td>
				</tr>

				@endforeach
			</tbody>
		</table>

		@else
		<br>
		<h5 class="center-align red-text">Esta lista ainda não tem produtos cadastrados <a class="btn" href="/listaDePrecos/gerar/{{$lista->id}}">Gerar Lista de Produtos</a></h5>

		@endif

	</div>
</div>
@endsection	