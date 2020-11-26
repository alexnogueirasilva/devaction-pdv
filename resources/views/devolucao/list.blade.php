@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">

		<h4>Lista de Devoluções</h4>

		<div class="row">
			<br>
			<form method="get" action="/devolucao/filtro">
				<div class="row">

					<input type="hidden" id="_token" value="{{ csrf_token() }}">

					<div class="col s4 input-field">
						<input value="{{{ isset($fornecedor) ? $fornecedor : '' }}}" type="text" class="validate" name="cliente">
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
						<button type="submit" class="btn-large black">
							<i class="material-icons">search</i>
						</button>
					</div>
				</div>
			</form>

			@if(session()->has('message'))
			<div class="row">
				<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
					<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
				</div>
			</div>
			@endif

			<a href="/devolucao/nova" class="btn-large red">nova devolução</a>

			<div class="col s12">
				<label>Numero de registros: {{count($devolucoes)}}</label>					
			</div>

			<table class="col s12">
				<thead>
					<tr>
						<th></th>
						<th>Código</th>
						<th>Fornecedor</th>
						<th>Usuario</th>
						<th>Valor Integral</th>
						<th>Valor Devolvido</th>
						<th>Estado</th>
						<th>Data</th>
						<th>NF Entrada</th>
						<th>NF Devolução</th>


						<th>Motivo</th>
						<th>Ações</th>
					</tr>
				</thead>

				<tbody id="body">
					<?php 
					$total = 0;
					?>
					@foreach($devolucoes as $d)

					<tr>	
						<td id="checkbox">

							<p>
								<input type="checkbox" class="check" id="test_{{$d->id}}" />
								<label for="test_{{$d->id}}"></label>
							</p>

						</td>
						
						<td id="id">{{$d->id}}</td>
						<td style="display: none" id="estado_{{$d->id}}">{{$d->estado}}</td>
						
						<td>{{$d->fornecedor->razao_social}}</td>
						<td>{{$d->usuario->nome}}</td>
						<td>{{ number_format($d->valor_integral, 2, ',', '.') }}</td>
						<td>{{ number_format($d->valor_devolvido, 2, ',', '.') }}</td>

						<td>
							@if($d->estado == 1)
							<i class="material-icons green-text">
								brightness_1
							</i>
							@elseif($d->estado == 2)
							<i class="material-icons yellow-text">
								brightness_1
							</i>
							@elseif($d->estado == 3)
							<i class="material-icons red-text">
								brightness_1
							</i>
							@else
							<i class="material-icons blue-text">
								brightness_1
							</i>

							@endif
						</td>
						<th>{{ \Carbon\Carbon::parse($d->data_registro)->format('d/m/Y H:i:s')}}</th>
						<td id="numeroNf">{{$d->nNf}}</td>
						<td id="numeroNf">{{$d->numero_gerado ?? '--'}}</td>

						<td>
							<a class="btn brown lighten-2 tooltipped" data-position="bottom" data-delay="50" data-tooltip="{{$d->motivo}}"
								@if(empty($d->motivo))
								disabled
								@endif
								>
								<i class="material-icons">message</i>

							</a>
						</td>

						<td>
							@if($d->estado != 1)
							<a href="/devolucao/delete/{{$d->id}}">
								<i class="material-icons red-text">delete</i>
							</a>	

							@else
							<a href="/devolucao/ver/{{$d->id}}">
								<i class="material-icons blue-text">visibility</i>
							</a>
							@endif
						</td>

					</tr>
					
					@endforeach
					
				</tbody>
			</table>
		</div>
		<div class="row" id="preloader1" style="display: none">
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

		<div class="row">
			<div class="col s2">
				<a id="btn-enviar" onclick="enviar()" style="width: 100%" class="btn-large green accent-4" href="#!">Enviar</a>
			</div>

			<div class="col s2">
				<a id="btn-imprimir" onclick="imprimir()" style="width: 100%" class="btn-large purple" href="#!">Imprimir</a>
			</div>

			<div class="col s2">
				<a id="btn-cancelar" onclick="setarNumero()" style="width: 100%" class="btn-large red modal-trigger" href="#modal1">Cancelar</a>
			</div>
		</div>

		<input type="hidden" id="token" value="{{csrf_token()}}">
		@if(isset($devolucoes))
		<ul class="pagination center-align">
			<li class="waves-effect">{{$devolucoes->links()}}</li>
		</ul>
		@endif

		
	</div>

</div>


<div id="modal-alert" class="modal">

	<div class="modal-content">
		<p class="center-align"><i class="large material-icons green-text">check_circle</i></p>
		<h4 class="center-align">Tudo Certo</h4>
		<p class="center-align" id="evento"></p>

	</div>
	<div class="modal-footer">
		<a href="#!" onclick="redireciona()" class="modal-action modal-close waves-effect waves-green btn-flat">Fechar</a>
	</div>
</div>

<div id="modal-sucesso-cancela" class="modal">

	<div class="modal-content">
		<p class="center-align"><i class="large material-icons green-text">check_circle</i></p>
		<h4 class="center-align">Devolução Cancelada</h4>
		<p class="center-align" id="evento-cancela"></p>

	</div>
	<div class="modal-footer">
		<a href="#!" onclick="redireciona()" class="modal-action modal-close waves-effect waves-green btn-flat">Fechar</a>
	</div>
</div>

<div id="modal-alert-erro" class="modal">

	<div class="modal-content">
		<p class="center-align"><i class="large material-icons red-text">error</i></p>
		<h4 class="center-align">Algo deu errado</h4>
		<p class="center-align" id="evento-erro"></p>

	</div>
	<div class="modal-footer">
		<a href="#!" onclick="redireciona()" class="modal-action modal-close waves-effect waves-green btn-flat">Fechar</a>
	</div>
</div>

<div id="modal1" class="modal">
	<div class="modal-content">
		<h4>Cancelamento da Devolução <strong class="orange-text" id="numero_cancelamento"></strong></h4>
		<div class="row">
			<div class="input-field col s12">
				<textarea id="justificativa" class="materialize-textarea"></textarea>
				<label for="justificativa">Justificativa minimo de 15 caracteres</label>
			</div>
		</div>
	</div>
	<div class="row" id="preloader5" style="display: none">
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
	<div class="modal-footer">
		<a href="#!" class="modal-action modal-close btn grey">Fechar</a>
		<button onclick="cancelar()" class="btn red">Cancelar Nota</button>

	</div>
</div>


@endsection	