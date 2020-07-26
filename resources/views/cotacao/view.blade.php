@extends('default.layout')
@section('content')

<div class="row">
	
	<div class="card">
		<div class="row">
			<div class="col s12">
				<h4>Fornecedor: {{$cotacao->fornecedor->razao_social}}</h4>
				<h4>Data de registro: 
					{{ \Carbon\Carbon::parse($cotacao->data_registro)->format('d/m/Y H:i')}}</h4>

					<div class="col s6">
						<h5>Referencia: {{$cotacao->referencia}}</h5>
						<h5>Observação: {{$cotacao->observacao}}</h5>
						<h5>Link: <strong class="blue-text"> <a href="{{getenv('PATH_URL')}}/response/{{$cotacao->link}}" target="_blank">{{getenv('PATH_URL')}}/response/{{$cotacao->link}}</a></strong></h5>
					</div>

					<div class="col s6">
						<h5>Ativa: 
							@if($cotacao->ativa)
							<i class="material-icons green-text">lens</i>
							@else
							<i class="material-icons red-text">lens</i>
							@endif
						</h5>

						<h5>Respondida: 
							@if($cotacao->resposta)
							<i class="material-icons green-text">lens</i>
							@else
							<i class="material-icons red-text">lens</i>
							@endif
						</h5>
					</div>

				</div>
			</div>
		</div>

		<div class="card">
			<div class="row">
				<div class="col s12">
					<h4>Itens da Cotação</h4>
					<table class="striped col s12">
						<thead>
							<tr>
								<th>#</th>
								<th>Produto</th>
								<th>Quantidade</th>
								<th>Valor</th>
								<th>Ações</th>
							</tr>
						</thead>
						<tbody>
							@foreach($cotacao->itens as $i)
							<tr>
								<td>{{$i->id}}</td>
								<td>{{$i->produto->nome}}</td>
								<td>{{$i->quantidade}}</td>
								<td>{{number_format($i->valor, 2, ',', '.')}}</td>
								<td>
									<a href="/cotacao/deleteItem/{{$i->id}}">
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

		<div class="card">
			<div class="row">
				<div class="col s12">
					<h4>Total: <strong class="red-text">R$ 
						{{number_format($cotacao->valor, 2, ',', '.')}}</strong></h4>

						<h4>Total de itens: <strong>
							{{count($cotacao->itens)}}
						</strong></h4>

						<h5>Forma de Pagamento: <strong>{{$cotacao->forma_pagamento}}</strong></h5>
						<h5>Responsável: <strong>{{$cotacao->responsavel}}</strong></h5>

						<a href="/cotacao/clonar/{{$cotacao->id}}" class="btn orange">
							Clonar
						</a>
						@if(!$cotacao->escolhida())
						<a onclick = "if (! confirm('Deseja marcar como escolhida esta cotação?')) { return false; }" href="/cotacao/escolher/{{$cotacao->id}}" class="btn green">
							Marcar como Escolhida
						</a>
						@else
							@if($cotacao->escolhida()->id == $cotacao->id)
							<h5 class="red-text">Essa cotação já foi escolhida!</h5>
							@else
							<br>
							<h5><a href="/cotacao/view/{{$cotacao->escolhida()->id}}">Essa refernência já foi definida para cotação {{$cotacao->escolhida()->id}}</a></h5>
							@endif
						
						@endif
					</div>
				</div><br>
			</div>
		</div>


		@endsection	