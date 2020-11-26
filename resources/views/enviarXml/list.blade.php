@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">

		<h4 class="center-align">ENVIAR XML PARA O ESCRITÓRIO</h4>

		<div class="row container">
			<br>
			<form method="get" action="/enviarXml/filtro">
				<div class="row">

					<div class="col s4 offset-s1 input-field">
						<input type="text" class="datepicker" name="data_inicial">
						<label>Data Inicial</label>
					</div>
					<div class="col s4 input-field">
						<input type="text" class="datepicker" name="data_final">
						<label>Data Final</label>
					</div>

					<div class="col s2">
						<button style="width: 100%;" type="submit" class="btn-large black">
							<i class="material-icons">search</i>
						</button>
					</div>
				</div>
			</form>
		</div>

		

		@if(isset($xml)&& count($xml) > 0)
		<div class="row">
			<div class="card">
				<div class="row">
					<div class="col s12">
						<h5>Total de NFe: <strong class="orange-text">{{count($xml)}}</strong></h5>

						<div class="container">
							<div class="row">
								<div class="col s6">
									<a target="_blank" style="width: 100%;" href="/enviarXml/download" class="btn">Baixar Arquivos de XML NFe</a>
								</div>
								<div class="col s6">
									<a style="width: 100%;" target="_blank" href="/enviarXml/email/{{$dataInicial}}/{{$dataFinal}}" class="btn orange">Enviar Arquivos de XML NFe</a>
								</div>
							</div>
							
							<table class="striped">
								<thead>
									<tr>
										<th>#</th>
										<th>Cliente</th>
										<th>Valor</th>
										<th>Data</th>
									</tr>
								</thead>
								<tbody>
									@foreach($xml as $x)
									<tr>
										<td>{{$x->id}}</td>
										<td>{{$x->cliente->razao_social}}</td>
										<td>{{number_format($x->valor_total, 2, ',', '.')}}</td>
										<td>{{ \Carbon\Carbon::parse($x->data_registro)->format('d/m/Y H:i:s')}}</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
						
					</div>
				</div><br>
			</div>
		</div>
		@endif

		@if(isset($xmlNfce) && count($xmlNfce) > 0)
		<div class="row">
			<div class="card">
				<div class="row">
					<div class="col s12">
						<h5>Total de NFCe: <strong class="orange-text">{{count($xmlNfce)}}</strong></h5>

						<div class="container">
							<div class="row">
								<div class="col s6">
									<a target="_blank" style="width: 100%;" href="/enviarXml/downloadNfce" class="btn">Baixar Arquivos de XML NFCe</a>
								</div>
								<div class="col s6">
									<a style="width: 100%;" target="_blank" href="/enviarXml/emailNfce/{{$dataInicial}}/{{$dataFinal}}" class="btn orange">Enviar Arquivos de XML NFCe</a>
								</div>
							</div>
							
							<table class="striped">
								<thead>
									<tr>
										<th>#</th>
										<th>Cliente</th>
										<th>Valor</th>
										<th>Data</th>
									</tr>
								</thead>
								<tbody>
									@foreach($xmlNfce as $x)
									<tr>
										<td>{{$x->id}}</td>
										@if($x->cliente)
										<td>{{$x->cliente->razao_social}}</td>
										@else
										<td>--</td>
										@endif
										<td>{{number_format($x->valor_total, 2, ',', '.')}}</td>
										<td>{{ \Carbon\Carbon::parse($x->data_registro)->format('d/m/Y H:i:s')}}</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
						
					</div>
				</div><br>
			</div>
		</div>
		@endif

		@if(isset($xmlCte) && count($xmlCte) > 0)
		<div class="row">
			<div class="card">
				<div class="row">
					<div class="col s12">
						<h5>Total de CTe: <strong class="orange-text">{{count($xmlCte)}}</strong></h5>

						<div class="container">
							<div class="row">
								<div class="col s6">
									<a target="_blank" style="width: 100%;" href="/enviarXml/downloadCte" class="btn">Baixar Arquivos de XML CTe</a>
								</div>
								<div class="col s6">
									<a style="width: 100%;" target="_blank" href="/enviarXml/emailCte/{{$dataInicial}}/{{$dataFinal}}" class="btn orange">Enviar Arquivos de XML Cte</a>
								</div>
							</div>
							
							<table class="striped">
								<thead>
									<tr>
										<th>#</th>
										<th>Cliente</th>
										<th>Valor</th>
										<th>Data</th>
									</tr>
								</thead>
								<tbody>
									@foreach($xmlCte as $c)
									<tr>
										<td>{{$c->id}}</td>
										@if($c->cliente)
										<td>{{$c->cliente->razao_social}}</td>
										@else
										<td>--</td>
										@endif
										<td>{{number_format($c->valor_total, 2, ',', '.')}}</td>
										<td>{{ \Carbon\Carbon::parse($c->data_registro)->format('d/m/Y H:i:s')}}</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
						
					</div>
				</div><br>
			</div>
		</div>
		@endif



		@if(isset($xmlMdfe) && count($xmlMdfe) > 0)
		<div class="row">
			<div class="card">
				<div class="row">
					<div class="col s12">
						<h5>Total de MDF-e: <strong class="orange-text">{{count($xmlMdfe)}}</strong></h5>

						<div class="container">
							<div class="row">
								<div class="col s6">
									<a target="_blank" style="width: 100%;" href="/enviarXml/downloadMdfe" class="btn">Baixar Arquivos de XML MDFe</a>
								</div>
								<div class="col s6">
									<a style="width: 100%;" target="_blank" href="/enviarXml/emailMdfe/{{$dataInicial}}/{{$dataFinal}}" class="btn orange">Enviar Arquivos de XML MDFe</a>
								</div>
							</div>
							
							<table class="striped">
								<thead>
									<tr>
										<th>#</th>
										<th>Cliente</th>
										<th>Valor</th>
										<th>Data</th>
									</tr>
								</thead>
								<tbody>
									@foreach($xmlMdfe as $c)
									<tr>
										<td>{{$c->id}}</td>
										@if($c->cliente)
										<td>{{$c->cliente->razao_social}}</td>
										@else
										<td>--</td>
										@endif
										<td>{{number_format($c->valor_total, 2, ',', '.')}}</td>
										<td>{{ \Carbon\Carbon::parse($c->data_registro)->format('d/m/Y H:i:s')}}</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
						
					</div>
				</div><br>
			</div>
		</div>
		@endif

		@if(isset($xml) && isset($xmlNfce) && isset($xmlCte) && isset($xmlMdfe) && 
		count($xml) == 0 && count($xmlNfce) == 0 && count($xmlCte) == 0 && count($xmlMdfe) == 0)
		<h2 class="center-align red-text">Nenhum arquivo encontrado</h2>
		@endif
	</div>
</div>


@endsection	