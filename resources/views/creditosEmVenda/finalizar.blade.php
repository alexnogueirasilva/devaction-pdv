@extends('default.layout')
@section('content')

<style type="text/css">
.dismiss{

}
</style>
<div class="row">
	<div class="col s12">

		@if($clienteDiferente == false)
		<h4>Receber Vendas</h4>

		<h5>Cliente: <strong class="red-text">{{$cliente}}</strong></h5>

		<p>Lista de Vendas a receber: <strong class="red-text">{{count($vendas)}}</strong></p>
		<table class="col s8 offset-s2">
			<thead>
				<tr>
					<th>#</th>
					<th>Data</th>
					<th>Valor</th>
					<th>Usuário</th>
				</tr>
			</thead>
			<tbody>
				<?php $t = 0; ?>
				@foreach($vendas as $v)
				<tr>
					<td>{{$v['id']}}</td>
					<th>{{ \Carbon\Carbon::parse($v['data'])
						->format('d/m/Y H:i:s')}}</th>
					<td>{{number_format($v['valor'], 2, ',', '.')}}</td>
					<td>{{$v['usuario']}}</td>

					<?php $t += $v['valor'] ?>
				</tr>
				@endforeach
				<tr class="green lighten-3">
					<td colspan="2"></td>
					<td>{{number_format($t, 2, ',', '.')}}</td>
					<td></td>
				</tr>
			</tbody>
		</table>

		<div class="row">
			<div class="col s12"><br>
				<div class="col s3 offset-s3">
					<a style="width: 100%" href="/vendasEmCredito/emitirNFe?arr={{$arr}}" class="btn-large blue">
						Receber e Emitir NFe
					</a>
				</div>
				<div class="col s3">
					<a style="width: 100%" href="/vendasEmCredito/apenasReceber?arr={{$arr}}" class="btn-large green accent-4">
						Receber sem Emitir NFe
					</a>
				</div>
			</div>
		</div>

		@else
		<h4 class="center-align red-text">Nao é possível receber contas de multiplos clientes!</h4>
		<h5 class="center-align">Selecione novamente!</h5>
		@endif
	</div>
</div>


@endsection	