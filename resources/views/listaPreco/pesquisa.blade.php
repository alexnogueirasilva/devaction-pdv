@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">

		<h4>Pesquisa de Preços</h4>


		<form method="get" action="/listaDePrecos/filtro">
			<div class="row">
				<div class="input-field col s2">
					<select name="lista" id="lista">
						@foreach($listas as $l)
						<option value="{{$l->id}}">{{$l->nome}}</option>
						@endforeach
					</select>
					<label>Lista</label>
				</div>

				<div class="input-field col s3">
					<input type="text" name="produto">
					<label>Produto</label>
				</div>

				<div class="input-field col s2">
					<button class="btn" type="submit">
						Pesquisar
					</button>
				</div>
			</div>
		</form>


		<div class="row">
			<div class="col s12">
				<label>Numero de registros: {{sizeof($resultados)}}</label>					
			</div>
			<table class="col s12">
				<thead>
					<tr>
						<th>Produto</th>
						<th>Valor Padrão de Venda</th>
						<th>Valor de Compra</th>
						<th>Valor da lista</th>
					</tr>
				</thead>

				<tbody>
					@foreach($resultados as $r)
					<tr>
						<td>{{$r->nome}}</td>
						<td>{{$r->valor_venda}}</td>
						<td>{{$r->valor_compra}}</td>
						<td>{{$r->valor_lista}}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection	