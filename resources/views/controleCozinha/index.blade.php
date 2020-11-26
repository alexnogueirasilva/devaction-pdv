@extends('default.layout')
@section('content')

<style type="text/css">
.dismiss{

}
</style>
<div class="row">
	<h2 class="center-align">Controle de Pedidos</h2>
	<div class="col s6">
		<h5>Pedidos do Delivery: <strong class="red-text" id="contDelivery">0</strong></h5>
	</div>
	<div class="col s6">
		<h5>Pedidos Comanda: <strong class="red-text" id="contComanda">0</strong></h5>
	</div>
	<div class="progress">
		<div class="col s10">
			<div class="indeterminate blue"></div>
		</div>
	</div>
	<div id="itens">
		
	</div>
</div>
@endsection	