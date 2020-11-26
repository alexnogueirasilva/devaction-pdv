@extends('default.layout')
@section('content')

<style type="text/css">
.card-style{
	height: 100px;
}
.card-style-grafico{
	height: 500px;
}
.tamanho-icone{
	font-size: 60px;
	margin-left: -10px;
}
h6{
	color: white;
}
</style>
<div class="row">

	<div class="col s2">
		<div class="card card-style indigo darken-2">
			<div class="card-content">
				<div class="col s4">
					<i class="material-icons tamanho-icone white-text">person</i>
				</div>
				<div class="col s8">
					<h6>Clientes</h6>
					<h6>{{$totalDeClientes}}</h6>
				</div>
			</div>
		</div>
	</div>
	<div class="col s2">
		<div class="card card-style indigo accent-2">
			<div class="card-content">
				<div class="col s4">
					<i class="material-icons tamanho-icone white-text">local_grocery_store</i>
				</div>
				<div class="col s8">
					<h6>Produtos</h6>
					<h6>{{$totalDeProdutos}}</h6>
				</div>
			</div>
		</div>
	</div>
	<div class="col s2">
		<div class="card card-style cyan lighten-3">
			<div class="card-content">
				<div class="col s4">
					<i class="material-icons tamanho-icone white-text">local_offer</i>
				</div>
				<div class="col s8">
					<h6>Vendas</h6>
					<h6>R$ {{$totalDeVendas}}</h6>
				</div>
			</div>
		</div>
	</div>

	<div class="col s2">
		<div class="card card-style cyan darken-2">
			<div class="card-content">
				<div class="col s4">
					<i class="material-icons tamanho-icone white-text">local_grocery_store</i>
				</div>
				<div class="col s8">
					<h6 style="font-size: 11px;">Total de Pedidos</h6>
					<h6>{{$totalDePedidos}}</h6>
				</div>
			</div>
		</div>
	</div>
	<div class="col s2">
		<div class="card card-style teal accent-3">
			<div class="card-content">
				<div class="col s4">
					<i class="material-icons tamanho-icone white-text">attach_money</i>
				</div>
				<div class="col s8">
					<h6 style="font-size: 11px;">Contas a Receber</h6>
					<h6 style="font-size: 15px;">R$ {{$totalDeContaReceber}}</h6>
				</div>
			</div>
		</div>
	</div>
	<div class="col s2">
		<div class="card card-style orange lighten-3">
			<div class="card-content">
				<div class="col s4">
					<i class="material-icons tamanho-icone white-text">money_off</i>
				</div>
				<div class="col s8">
					<h6 style="font-size: 12px;">Contas a Pagar</h6>
					<h6 style="font-size: 15px;">R$ {{$totalDeContaPagar}}</h6>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">

	<div class="col s12">
		<div class="card card-style-grafico">
			<div class="card-content">
				<div class="col s3" style="border-right: 1px solid #000;">
					<h5>Faturamento</h5>
					<br>
					<p>Modelo do Gráfico:</p>
					<form action="#" >
						<p>
							<input name="group1" type="radio" id="test1" onchange="alteraModeloGrafico('line')" checked />
							<label for="test1">Linha</label>
						</p>
						<p>
							<input name="group1" type="radio" id="test2" onchange="alteraModeloGrafico('pie')" />
							<label for="test2">Pizza</label>
						</p>
						<p>
							<input class="with-gap" name="group1" type="radio" id="test3" onchange="alteraModeloGrafico('bar')"/>
							<label for="test3">Barra</label>
						</p>
					</form>

					<br>
					<p>Filtro de Data</p>
					<div class="row">
						<div class="col s10 input-field">
							<input class="date-input" value="{{$dataInicial}}" type="text" id="data_inicial" name="">
							<label>Inicial</label>
						</div>
						<div class="col s10 input-field">
							<input class="date-input" value="{{$dataFinal}}" type="text" id="data_final" name="">
							<label>Final</label>
						</div>
						<button class="btn-large" onclick="filtrar()" style="width: 100%;">Filtrar</button>
					</div>
				</div>
				<div class="col s8" id="novo-faturamento" style="width: 60%; margin-left: -10px; margin-top: 0px;">
					<canvas id="grafico-faturamento"></canvas>

				</div>
			</div>
		</div>
	</div>
	
</div>
@endsection	