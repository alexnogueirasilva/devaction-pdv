@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">

		<h4>Lista de Compras</h4>

		@if(session()->has('message'))
		<div class="row">
			<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
				<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
			</div>
		</div>
		@endif

		<nav class="black">
			<div class="nav-wrapper">
				<form method="get" action="/compras/pesquisa">
					<div class="input-field">
						<input placeholder="Pesquisa por Produto" id="search" name="pesquisa" 
						type="search" required>
						<label class="label-icon" for="search">
							<i class="material-icons">search</i></label>
							<i class="material-icons">close</i>
						</div>

					</form>
				</div>
			</nav>
			<br>
			<form method="get" action="/compras/filtro">
				<div class="row">
					<div class="col s4 input-field">
						<input value="{{{ isset($fornecedor) ? $fornecedor : '' }}}" type="text" class="validate" name="fornecedor">
						<label>Fornecedor</label>
					</div>

					<div class="col s2 input-field">
						<input value="{{{ isset($dataInicial) ? $dataInicial : '' }}}" type="text" class="datepicker" name="data_inicial">
						<label>Data Inicial</label>
					</div>
					<div class="col s2 input-field">
						<input value="{{{ isset($dataFinal) ? $dataFinal : '' }}}" type="text" class="datepicker" name="data_final">
						<label>Data Final</label>
					</div>

					<div class="col s2">
						<button type="submit" class="btn-large">
							<i class="material-icons">search</i>
						</button>
					</div>
				</div>
			</form>
			<label><i class="material-icons left green-text">nfc</i>Emitir NF-e Entrada</label><br><br>
			<div class="row">
				<div class="col s12">
					<label>Numero de registros: {{count($compras)}} 
						@if(isset($totalRegistros))
						de 
					{{$totalRegistros}} @endif</label>					
				</div>
				<table class="col s12">
					<thead>
						<tr>
							<th>#</th>
							<th>Fornecedor</th>
							<th>Data</th>
							<th>Observacao</th>
							<th>NF</th>
							<th>Usuário</th>
							<th>Valor</th>
							<th>Desconto</th>
						</tr>
					</thead>

					<tbody>
						<?php 
						$total = 0;
						$totalDesconto = 0;
						?>
						@foreach($compras as $c)
						<tr>
							<th>{{ $c->id }}</th>
							<th>{{ $c->fornecedor->razao_social }}</th>
							<th>{{ \Carbon\Carbon::parse($c->created_at)->format('d/m/Y H:i:s')}}</th>
							<th>
								<a class="btn brown lighten-2 tooltipped" data-position="bottom" data-delay="50" data-tooltip="{{$c->observacao}}"
									@if(empty($c->observacao))
									disabled
									@endif
									>
									<i class="material-icons">message</i>

								</a>
							</th>

							<th>{{ $c->nf > 0 ? $c->nf : '--' }}</th>
							<th>{{ $c->usuario->nome }}</th>
							<th>{{ number_format($c->valor, 2, ',', '.') }}</th>
							<th>{{ number_format($c->desconto, 2, ',', '.') }}</th>

							<th>
								<a title="Detalhes" href="/compras/detalhes/{{ $c->id }}">
									<i class="material-icons left">list</i>					
								</a>
								<a onclick='swal("Atenção!", "Deseja remover este registro?", "warning").then((sim) => {if(sim){ location.href="/compras/delete/{{ $c->id }}" }else{return false} })' href="#!">
									<i class="material-icons left red-text">delete</i>					
								</a>

								<!-- a onclick = "if (! confirm('Deseja excluir este registro?')) { return false; }" href="/compras/delete/{{ $c->id }}">
									<i class="material-icons left red-text">delete</i>					
								</a> -->

								<a title="Detalhes" href="/compras/emitirEntrada/{{ $c->id }}">
									<i class="material-icons left green-text">nfc</i>					
								</a>
							</th>
						</tr>

						<?php
						$total += $c->valor;
						$totalDesconto += $c->desconto;
						?>
						@endforeach
						<tr class="red lighten-3">
							<th class="center-align" colspan="6">TOTAL</th>
							<th>{{ number_format($total, 2, ',', '.') }}</th>
							<th>{{ number_format($totalDesconto, 2, ',', '.') }}</th>
							<th></th>
						</tr>
					</tbody>
				</table>
			</div>
			@if(isset($links))
			<ul class="pagination center-align">
				<li class="waves-effect">{{$compras->links()}}</li>
			</ul>
			@endif

			<input type="hidden" id="somaContas" value="{{json_encode($somaCompraMensal)}}">

			@if(count($compras) > 0)
			<div class="row">
				<h4 class="center-align">Graficos</h4>
				<div class="col s6">
					<div style="height: 400px; width: 100%;" id="pizza"></div>

				</div>
				<div class="col s6">
					<div style="height: 400px; width: 100%;" id="coluna"></div>

				</div>
			</div>
			@endif
		</div>
	</div>
	@endsection	