@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">

		<div class="row">
			<div class="container">
				<div class="row">

					<h3 class="center-align">Venda código: <strong>{{$venda->id}}</strong></h3>

					<h4>Cliente: <strong class="red-text">{{$venda->cliente->razao_social}}</strong></h4>
					<h5>CNPJ: <strong class="red-text">{{$venda->cliente->cpf_cnpj}}</strong></h5>
					<h5>Data: <strong class="red-text">{{ \Carbon\Carbon::parse($venda->data_registro)->format('d/m/Y H:i:s')}}</strong></h5>
					<h5>Valor Total: <strong class="red-text">{{ number_format($venda->valor_total, 2, ',', '.') }}</strong></h5>
					<h5>Cidade: <strong class="red-text">{{ $venda->cliente->cidade->nome }} ({{ $venda->cliente->cidade->uf }})</strong></h5>
				</div>

				<div class="divider"></div>
				<div class="row">
					<h5 class="cyan-text">Itens da NF</h5>

					<table>
						<thead>
							<tr>
								<th>#</th>
								<th>Produto</th>
								<th>Quantidade</th>
								<th>Valor</th>
								<th>Subtotal</th>
							</tr>
						</thead>
						<tbody>
							<?php $somaItens = 0; ?>
							@foreach($venda->itens as $i)

							<tr>
								<td>{{$i->id}}</td>
								<td>{{$i->produto->nome}}</td>
								<td>{{$i->quantidade}}</td>
								<td>{{number_format($i->valor, 2, ',', '.')}}</td>
								<td>{{number_format($i->valor*$i->quantidade, 2, ',', '.')}}</td>
							</tr>
							<?php $somaItens+=  $i->valor * $i->quantidade?>
							@endforeach
							<tr>
								<td colspan="4">Soma dos Itens</td>
								<td>{{number_format($somaItens, 2, ',', '.')}}</td>
							</tr>
						</tbody>
					</table>
				</div>


				<div class="divider"></div>
				<div class="row">
					<h5 class="brown-text">Fatura da NF</h5>
					<p>Forma de pagamento: <strong>{{$venda->forma_pagamento}}</strong></p>

					<table>
						<thead>
							<tr>
								<th>Vencimento</th>
								<th>Valor</th>
							</tr>
						</thead>
						@if(count($venda->duplicatas))
						<tbody>
							@foreach($venda->duplicatas as $dp)

							<tr>
								<td>{{ \Carbon\Carbon::parse($dp->data_vencimento)->format('d/m/Y')}}</td>
								<td>{{number_format($dp->valor_integral, 2, ',', '.')}}</td>
							</tr>
							@endforeach
						</tbody>
						@else
						<tbody>
							<tr>
								<td>{{ \Carbon\Carbon::parse($venda->data_registro)->format('d/m/Y')}}</td>
								<td>{{number_format($venda->valor_total, 2, ',', '.')}}</td>
							</tr>
						</tbody>
						@endif
					</table>
				</div>

				<div class="row">
					<a target="_blank" href="/vendas/imprimirPedido/{{$venda->id}}" class="btn">
						<i class="material-icons left">print</i>
						Imprimir
					</a>
				</div>

			</div>
		</div>


	</div>
</div>
@endsection	