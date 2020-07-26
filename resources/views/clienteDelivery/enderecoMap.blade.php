@extends('default.layout')
@section('content')
<style type="text/css">
#map{
	width: 100%;
	height: 600px;
	background: #999;
	margin-bottom: 50px;
}
</style>
<div class="row">
	<div class="col s12">
		<h4 class="center-align">Mapa Endereço <strong>{{$endereco->id}}</strong></h4>
		<div class="row">
			<div class="col s6">
				<h5>Latitude Local: <strong>{{$config->latitude}}</strong></h5>
				<h5>Longitude Local: <strong>{{$config->longitude}}</strong></h5>

				<input type="hidden" value="{{$config->latitude}}" id="lat_local">
				<input type="hidden" value="{{$config->longitude}}" id="lng_local">
			</div>
			<div class="col s6">
				<h5>Latitude Cliente: <strong>{{$endereco->latitude}}</strong></h5>
				<h5>Longitude Cliente: <strong>{{$endereco->longitude}}</strong></h5>
				<input type="hidden" value="{{$endereco->latitude}}" id="lat_cliente">
				<input type="hidden" value="{{$endereco->longitude}}" id="lng_cliente">
			</div>
		</div>
		<div class="row">
			<div class="col s6">
				<h4>Distância: <strong class="cyan-text" id="distancia"></strong></h4>
				<h4>Duração percurso: <strong class="cyan-text" id="duracao"></strong></h4>
			</div>
		</div>
		
		<div id="map"></div>
	</div>
</div>
@endsection