@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">

		<h4>Estoque</h4>

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
				<label>Numero de registros: {{count($apontamentos)}}</label>					
			</div>

			<table class="col s12">
				<thead>
					<tr>
						<th>Produto</th>
						<th>Categoria</th>
						
						<th>Obsevação</th>
						<th>Quantidade Alterada</th>
						<th>Tipo</th>
						<th>Usuário</th>
						<th>Data</th>
						<th>Ações</th>
					</tr>
				</thead>

				<tbody>
					@foreach($apontamentos as $a)
					<tr>
						<td>{{$a->produto->nome}}</td>
						<td>{{$a->produto->categoria->nome}}</td>
						<td>
							<a class="btn blue lighten-2 tooltipped" data-position="bottom" data-delay="50" data-tooltip="{{$a->observacao}}"
								@if(empty($a->observacao))
								disabled
								@endif
								>
								<i class="material-icons">message</i>

							</a>
						</td>

						<td>{{$a->quantidade}} {{$a->produto->unidade_venda}}</td>
						<td>{{$a->tipo == 'reducao' ? 'Redução' : 'Incremento'}} </td>
						<td>{{$a->usuario->nome}}</td>
						<td>{{ \Carbon\Carbon::parse($a->created_at)->format('d/m/Y H:i:s')}}</td>

						<td>
							<a href="/estoque/listApontamentos/delete/{{$a->id}}">
								<i class="material-icons red-text">delete</i>
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