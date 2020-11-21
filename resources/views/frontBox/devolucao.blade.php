@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">

		<h4>Lista para Devolução</h4>

		@if(session()->has('message'))
		<div class="row">
			<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
				<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
			</div>
		</div>
		@endif

		<br>
		<div class="row">
			
			<form method="get" class="col s4" action="/frenteCaixa/filtroCliente">
				<div class="row">
					<div class="input-field col s10">
						<input type="text" name="nome" value="{{$nome}}">
						<label>Nome</label>
					</div>
					<div class="col s2">
						<button type="submit" class="btn-large">
							<i class="material-icons">search</i>
						</button>
					</div>
				</div>
			</form>

			<form method="get" class="col s3 offset-s1" action="/frenteCaixa/filtroNFCe">
				<div class="row">
					<div class="input-field col s10">
						<input type="text" name="nfce" value="{{$nfce}}">
						<label>NFCe</label>
					</div>
					<div class="col s2">
						<button type="submit" class="btn-large red">
							<i class="material-icons">nfc</i>
						</button>
					</div>
				</div>
			</form>


			<form method="get" class="col s2 offset-s1" action="/frenteCaixa/filtroValor">
				<div class="row">
					<div class="input-field col s10">
						<input type="text" id="numeros" name="valor" value="{{$valor}}">
						<label>Valor</label>
					</div>
					<div class="col s2">
						<button type="submit" class="btn-large yellow">
							<i class="material-icons">attach_money</i>
						</button>
					</div>
				</div>
			</form>
		</div>

		<a href="/frenteCaixa" class="btn-large blue">
			<i class="material-icons left">
				inbox
			</i>
		FRENE DE CAIXA</a>

		<p class="red-text">{{$info}}</p>


		<div class="row">
			
			<table class="col s12">
				<thead>
					<tr>
						<th>#</th>
						<th>Cliente</th>
						<th>Data</th>
						<th>Estado</th>
						<th>NFCe</th>
						<th>Usuário</th>
						<th>Valor</th>
						<th>Ações</th>
					</tr>
				</thead>

				<tbody>
					<?php 
					$total = 0;
					?>
					@foreach($vendas as $v)
					@if($v->estado == 'APROVADO')
					<tr class="green lighten-4">
						@elseif($v->estado == 'REJEITADO')
						<tr class="red lighten-4">
							@else
							<tr class="blue lighten-4">
								@endif
								<th>{{ $v->id }}</th>
								<th>{{ $v->cliente->razao_social ?? 'NAO IDENTIFCADO' }}</th>
								<th>{{ \Carbon\Carbon::parse($v->data_registro)->format('d/m/Y H:i:s')}}</th>
								<th>{{ $v->estado }}</th>

								<th>{{ $v->NFcNumero > 0 ? $v->NFcNumero : '--' }}</th>
								<th>{{ $v->usuario->nome }}</th>
								<th>{{ number_format($v->valor_total, 2, ',', '.') }}</th>
								<th>
									@if($v->estado == 'APROVADO')
									<a href="#!" onclick="modalCancelar({{$v->id}})" class="waves-light">
										<i class="material-icons red-text">close</i>
									</a>
									@else
									<a onclick = "if (! confirm('Deseja excluir este registro?')) { return false; }" href="/frenteCaixa/deleteVenda/{{$v->id}}" class="waves-light">
										<i class="material-icons red-text">delete</i>
									</a>
									@endif
								</th>
							</tr>

							<?php
							$total += $v->valor_total;
							?>
							@endforeach
							<tr class="red lighten-3">
								<th class="center-align" colspan="6">TOTAL</th>
								<th>{{ number_format($total, 2, ',', '.') }}</th>
								<th></th>
							</tr>
						</tbody>
					</table>
				</div>

			</div>
		</div>

		<div id="modal" class="modal">
			<input type="hidden" id="_token" value="{{ csrf_token() }}">

			<div class="modal-content">
				<h4>Cancelamento de NFCe</h4>
				<input type="hidden" id="venda_id" name="">
				<div class="row">
					<div class="input-field col s12">
						<textarea id="justificativa" class="materialize-textarea"></textarea>
						<label for="justificativa">Justificativa</label>
					</div>
				</div>

				<div class="row" id="preloader" style="display: none">
					<div class="col s12 center-align">
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
			<div class="modal-footer">
				<a href="#!" class="modal-action modal-close btn grey">Fechar</a>
				<button onclick="cancelar()" class="btn red">Cancelar</button>
			</div>
		</div>

		@endsection	