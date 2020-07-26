@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">

		<h4 class="center-align">DF-e</h4>
		<div class="row">
			<div class="input-field col s2">
				<input type="text" value="{{{isset($data_inicial) ? $data_inicial : ''}}}" id="data_inicial" class="datepicker">
				<label>Data Inicial</label>
			</div>
			<div class="input-field col s2">
				<input type="text" id="data_final" value="{{{ isset($data_final) ? $data_final : '' }}}" class="datepicker">
				<label>Data Final</label>
			</div>
			<div class="col s2">
				<button onclick="filtrar()" type="submit" class="btn-large black">
					<i class="material-icons">search</i>
				</button>
			</div>
			<div class="row" id="preloader" style="display: none">
				<div class="col s2 center-align">
					<div class="preloader-wrapper active">
						<div class="spinner-layer spinner--only">
							<div class="circle-clipper left">
								<div class="circle"></div>
							</div><div class="gap-patch">
								<div class="circle"></div>
							</div><div class="circle-clipper right">
								<div class="circle"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		@if(session()->has('message'))
		<div class="row">
			<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
				<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
			</div>
		</div>
		@endif

		<p>Total de Documentos: <strong id="total-documentos">0</strong></p>
		<div class="row">
			<table>
				<thead>
					<tr>
						<th>Nome</th>
						<th>Documento</th>
						<th>Valor</th>
						<th>Data Emissão</th>
						<th>Num. Protocolo</th>
						<th>Chave</th>
						<th>Ações</th>
					</tr>
				</thead>
				<tbody id="tbl">
					<tr>
						
					</tr>
				</tbody>
			</table>
		</div>
		
	</div>
</div>


@endsection	