
<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>{{ $title }}</title>
	<link href="/css/material-icons.css" rel="stylesheet">
	<link rel="stylesheet" href="/css/materialize.min.css">
	<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
	<link
	href="/css/font.css"
	rel="stylesheet"
	/>
	

	<link rel="stylesheet" href="../../css/style.css">
</head>
<body>
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
		<!-- <h4 class="center-align">Mapa Endereço <strong>{{$endereco->id}}</strong></h4> -->
		<input type="hidden" value="{{$config->latitude}}" id="lat_local">
		<input type="hidden" value="{{$config->longitude}}" id="lng_local">
		<input type="hidden" value="{{$endereco->latitude}}" id="lat_cliente">
		<input type="hidden" value="{{$endereco->longitude}}" id="lng_cliente">
		<input type="hidden" value="{{$endereco->rua}}" id="rua">
		<input type="hidden" value="{{$endereco->numero}}" id="numero">
		<input type="hidden" value="{{getenv('CIDADE_MAPS')}}" id="cidade">
		<div class="row">
			<div class="col s12">
				<?php 
				$nome = getenv("SMS_NOME_EMPRESA");
				$ex = explode("_", $nome);
				$nome = $ex[0] . " " . $ex[1];
				?>
				<h5>{{$nome}}</h5>
				<h6>Distância: <strong class="cyan-text" id="distancia"></strong></h6>
				<h6>Duração percurso: <strong class="cyan-text" id="duracao"></strong></h6>
			</div>
		</div>
		
		<div id="map"></div>
	</div>
</div>
<?php $path = getenv('PATH_URL')."/";?>
<script type="text/javascript">
	const path = "{{$path}}";
</script>
<script type="text/javascript" src="/js/jquery-3.2.1.min.js"></script>
<script src="/js/materialize.min.js"></script>
<script type="text/javascript" src="/js/init.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{getenv('API_KEY_MAPS')}}"
async defer></script>
<script type="text/javascript" src="/js/map2.js"></script>
</body>
