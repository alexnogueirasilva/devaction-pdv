@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">

		<h4>Lista de Ordens de Serviço</h4>

		@if(session()->has('message'))
		<div class="row">
			<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
				<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
			</div>
		</div>
		@endif

		<div class="row">
			<form method="get" action="/ordemServico/filtro" class="row col s12">

				<div class="input-field col s3">
					<input type="text" name="cliente">
					<label>Cliente</label>
				</div>
				<div class="input-field col s2">
					<input id="data_inicio" name="data_inicio" type="text" class="datepicker">
					<label for="data_inicio">Data Inicio</label>
				</div>
				<div class="input-field col s2">
					<input id="data_fim" name="data_fim" type="text" class="datepicker">
					<label for="data_fim">Data Final</label>
				</div>

				<div class="input-field col s2">
					<select>
						<option value="pd">PENDENTE</option>
						<option value="ap">APROVADO</option>
						<option value="rp">REPROVADO</option>
						<option value="fz">FINALIZADO</option>
					</select>
					<label for="estado">Estado</label>
				</div>

				<button class="btn-large col s1 black" type="submit">
					<i class="material-icons">search</i>	
				</button>
			</form>
		</div>


		<div class="row">
			<a href="/ordemServico/new" class="btn green accent-3">
				<i class="material-icons left">add</i>	
				Nova Ordem de Serviço	
			</a>
		</div>

		<input type="hidden" id="_token" value="{{ csrf_token() }}">
		<div class="row">
			<div class="col s12">
				<label>Numero de registros: {{count($orders)}}</label>					
			</div>
			<table class="col s12">
				<thead>
					<tr>
						<th>Código</th>
						<th>Cliente</th>
						<th>Valor</th>
						<th>Descrição</th>
						<th>Usuario</th>
						<th>Data de Registro</th>
						<th>NFSe</th>
						<th>Estado</th>
						<th>Ações</th>
					</tr>
				</thead>

				<tbody>
					<?php 
					$total = 0;
					?>
					@foreach($orders as $o)
					<tr>
						<th>{{ $o->id }}</th>
						<th>{{ $o->cliente->razao_social }}</th>
						<th>{{ number_format($o->valor, 2, ',', '.') }}</th>
						<th>
							<a class="btn brown lighten-2 tooltipped" data-position="bottom" data-delay="50" data-tooltip="{{$o->descricao}}"
								@if(empty($o->descricao))
								disabled
								@endif
								>
								<i class="material-icons">message</i>

							</a>
						</th>
						<th>{{ $o->usuario->nome }}</th>
						<th>{{ \Carbon\Carbon::parse($o->data_registro)->format('d/m/Y H:i:s')}}</th>
						<th>{{ $o->NfNumero}}</th>

						<th>
							
							@if($o->estado == 'pd')
							PENDENTE
							@elseif($o->estado == 'ap')
							APROVADO
							@elseif($o->estado == 'rp')
							REPROVADO
							@else
							FINALIZADO
							@endif

						</th>

						<th>
							@if(is_adm())
							<a onclick = "if (! confirm('Deseja excluir este registro?')) { return false; }" href="/ordemServico/delete/{{ $o->id }}">
								<i class="material-icons left red-text">delete</i>					
							</a>
							@endif
							<!-- <a href="#" onclick="printOs({{$o->id}})">
								<i class="material-icons left blue-text">print</i>	
							</a> -->

							<a href="/ordemServico/servicosordem/{{$o->id}}">
								<i class="material-icons left green-text">list</i>	
							</a>
						</th>

						<?php 
						$total += $o->valor;
						?>
					</tr>

					@endforeach

					<tr class="red lighten-5">
						<td colspan="2"></td>
						<td><strong class="green-text">{{number_format($total , 2, ',', '.')}}</strong></td>
						<td colspan="6"></td>
					</tr>
				</tbody>
			</table>
		</div>
		@if(isset($orders))
		<ul class="pagination center-align">
			<li class="waves-effect">{{$orders->links()}}</li>
		</ul>
		@endif
	</div>
</div>
@endsection	