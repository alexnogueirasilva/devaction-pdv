@extends('default.layout')
@section('content')

<form class="row" action="/compras/salvarValidade" method="post">

	@csrf
	<div class="col s12">
		@if(session()->has('message'))
		<div class="row">
			<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
				<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
			</div>
		</div>
		@endif
		<p class="red-text">*Datas com formato inválido serão desconsideradas</p>
		<input type="hidden" name="tamanho_array" value="{{sizeof($itens)}}">

		<table class="striped col s12">
			<thead>
				<tr>
					<th>#</th>
					<th>Produto</th>
					<th>Fornececor</th>
					<th>Data da Compra</th>
					<th>Valor Unit.</th>
					<th>Quantidade</th>
					<th style="width: 170px;">Validade</th>

				</tr>
			</thead>


			<tbody>
				@foreach($itens as $key => $i)
				<tr>
					<td>{{$i->id}}</td>
					<td class="red-text">{{$i->produto->nome}}</td>
					<td>{{$i->compra->fornecedor->razao_social}}</td>
					<td>{{$i->produto->nome}}</td>
					<td>{{number_format($i->valor_unitario, 2)}}</td>
					<td>{{number_format($i->quantidade, 2)}}</td>
					<td>
						<div class="input-field" style="margin-right: 10px;">
							<input value="" id="data" name="validade_{{$key}}" type="text" class="validate date-input">
							<input value="{{$i->id}}" name="id_{{$key}}" type="hidden" class="validate">

						</div>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		
	</div>
	
	<div class="row">
		<div class="col s2 offset-s10">
			<br>
			<button style="width: 100%;" type="submit" class="btn-large red">SALVAR</button>
		</div>
	</div>
	
</form>
@endsection	