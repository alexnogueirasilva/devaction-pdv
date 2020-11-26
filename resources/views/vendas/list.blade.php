@extends('default.layout')
@section('content')
<div class="row">
	<div class="col s12">

		<h4>Lista de Vendas</h4>

		<a href="/vendas/nova" class="btn green">
			<i class="material-icons left">add</i>
			Nova Venda
		</a>

		<div class="row">
			<br>
			<form method="get" action="/vendas/filtro">
				<div class="row">

					<input type="hidden" id="_token" value="{{ csrf_token() }}">

					<div class="col s4 input-field">
						<input value="{{{ isset($cliente) ? $cliente : '' }}}" type="text" class="validate" name="cliente">
						<label>Cliente</label>
					</div>

					<div class="col s2 input-field">
						<input value="{{{ isset($dataInicial) ? $dataInicial : '' }}}" type="text" class="datepicker" name="data_inicial">
						<label>Data Inicial</label>
					</div>
					<div class="col s2 input-field">
						<input value="{{{ isset($dataFinal) ? $dataFinal : '' }}}" type="text" class="datepicker" name="data_final">
						<label>Data Final</label>
					</div>

					<div class="col s2 input-field">
						<select name="estado">
							<option @if(isset($estado) && $estado == 'DISPONIVEL') selected @endif value="DISPONIVEL">DISPONIVEIS</option>
							<option @if(isset($estado) && $estado == 'REJEITADO') selected @endif value="REJEITADO">REJEITADAS</option>
							<option @if(isset($estado) && $estado == 'CANCELADO') selected @endif value="CANCELADO">CANCELADAS</option>
							<option @if(isset($estado) && $estado == 'APROVADO') selected @endif value="APROVADO">APROVADAS</option>
							<option @if(isset($estado) && $estado == 'TODOS') selected @endif value="TODOS">TODOS</option>
						</select>
						<label>Estado</label>
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
			<div class="col s12">
				<label>Numero de registros: {{count($vendas)}}</label>					
			</div>

			<table class="col s12">
				<thead>
					<tr>
						<th></th>
						<th>Código</th>
						<th>Cliente</th>
						<th>Usuario</th>
						<th>Valor</th>
						<th>Desconto</th>
						<th>Estado</th>
						<th>Data</th>
						<th>NF</th>
						<th>Forma de Pagamento</th>
						<th>Tipo de Pagamento</th>
						<th>Ações</th>
					</tr>
				</thead>

				<tbody id="body">
					<?php 
					$total = 0;
					?>
					@foreach($vendas as $v)

					<tr>
						<td id="checkbox">
							@if(!$v->status)
							<p>
								<input type="checkbox" class="check" id="test_{{$v->id}}" />
								<label for="test_{{$v->id}}"></label>
							</p>
							@endif
						</td>
						<td id="id">{{$v->id}}</td>
						
						<td>{{$v->cliente->razao_social}}</td>
						<td>{{$v->usuario->nome}}</td>
						<td>{{ number_format($v->valor_total, 2, ',', '.') }}</td>
						<td>{{ number_format($v->desconto, 2, ',', '.') }}</td>
						<td id="estado_{{$v->id}}">{{$v->estado}}</td>
						<th>{{ \Carbon\Carbon::parse($v->data_registro)->format('d/m/Y H:i:s')}}</th>
						<td id="numeroNf">{{$v->NfNumero}}</td>

						<td>{{$v->forma_pagamento}}</td>
						<td>{{$v->getTipoPagamento()}}</td>
						<td>
							@if($v->estado == 'DISPONIVEL')
							<a onclick='swal("Atenção!", "Deseja remover este registro?", "warning").then((sim) => {if(sim){ location.href="/vendas/delete/{{ $v->id }}" }else{return false} })' href="#!">
								<i class="material-icons left red-text">delete</i>					
							</a>
							@endif
							@if($v->estado == 'DISPONIVEL' || $v->estado == 'REJEITADO')

							<a onclick='swal("Atenção!", "Deseja editar este registro?", "warning").then((sim) => {if(sim){ location.href="/vendas/edit/{{ $v->id }}" }else{return false} })' href="#!">
								<i class="material-icons left blue-text">edit</i>					
							</a>
							@endif
							<a href="/vendas/detalhar/{{ $v->id }}">
								<i class="material-icons left orange-text">visibility</i>
							</a>

							<a title="Clonar venda" href="/vendas/clone/{{ $v->id }}">
								<i class="material-icons left purple-text">content_copy</i>
							</a>

							<a target="_blank" title="Ver XML" href="/vendas/gerarXml/{{ $v->id }}">
								<i class="material-icons left red-text">insert_drive_file</i>
							</a>
						</td>

					</tr>
					<?php 
					$total += $v->valor_total;
					?>
					@endforeach
					@if(isset($total))
					<tr class="blue lighten-4 gray-text">
						<td colspan="4" class="center-align">Total</td>
						<td>{{ number_format($total, 2, ',', '.') }}</td>
						<td colspan="7"></td>
					</tr>
					@endif
				</tbody>
			</table>
		</div>



		@if(isset($links))
		<ul class="pagination center-align">
			<li class="waves-effect">{{$vendas->links()}}</li>
		</ul>
		@endif


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

		@if($certificado != null)
		<div class="row">
			<div class="col s2">
				<a id="btn-enviar" onclick="enviar()" style="width: 100%" class="btn-large green" href="#!">Enviar</a>
			</div>

			<div class="col s2">
				<a id="btn-imprimir" onclick="imprimir()" style="width: 100%" class="btn-large grey" href="#!">Imprimir</a>
			</div>

			<div class="col s2">
				<a id="btn-consultar" onclick="consultar()" style="width: 100%" class="btn-large orange" href="#!">Consultar</a>
			</div>

			<div class="col s2">
				<a id="btn-cancelar" onclick="setarNumero()" style="width: 100%" class="btn-large red modal-trigger" href="#modal1">Cancelar</a>
			</div>

			<div class="col s2">
				<a id="btn-correcao" onclick="setarNumero()" style="width: 100%" class="btn-large cyan waves-light modal-trigger" href="#modal4">CC-e</a>
			</div>

			<div class="col s2">
				<a id="btn-inutilizar" style="width: 100%" class="btn-large blue waves-light modal-trigger" href="#modal3">Inutilizar</a>
			</div>
		</div>
		<div class="row">

			<div class="col s2">
				<a id="btn-danfe" target="_blank" style="width: 100%" class="btn-large blue waves-light">Gerar Danfe</a>
			</div>

			<div class="col s2">
				<a id="btn-baixar-xml" onclick="baixarXml()" target="_blank" style="width: 100%" class="btn-large brown waves-light">Baixar XML</a>
			</div>

			<div class="col s2">
				<a id="btn-xml" onclick="setarNumero(true)" style="width: 100%" class="btn-large purple waves-light modal-trigger" href="#modal5">Enviar XML</a>
			</div>

			<div class="col s2">
				<a onclick="modalWhatsApp()" style="width: 100%" class="btn-large green waves-light modal-trigger" href="#!">WhatsApp</a>
			</div>

			<div class="col s2">
				<a id="btn-imprimir-cce" onclick="imprimirCCe()" style="width: 100%" class="btn-large cyan waves-light" href="#!">Imprimir CC-e</a>
			</div>

			<div class="col s2">
				<a id="btn-imprimir-cancelar" onclick="imprimirCancela()" style="width: 100%" class="btn-large red lighten-2 waves-light" href="#!">Imprimir Cancela</a>
			</div>
		</div>
		@else

		<input type="hidden" id="semCertificado" value="true" name="">
		@endif
	</div>
</div>

<div id="modal1" class="modal">
	<div class="modal-content">
		<h4>Cancelamento da NF <strong class="orange-text" id="numero_cancelamento"></strong></h4>
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

<div id="modal-whatsApp" class="modal">

	<div class="modal-content">

		<div class="row">
			<div class="input-field col s4">
				<input type="text" id="celular">
				<label>WhatsApp</label>
			</div>
		</div>

		<div class="row">
			<div class="input-field col s8">
				<input type="text" id="texto">
				<label>Texto</label>
			</div>
		</div>

	</div>
	<div class="modal-footer">
		<a href="#!" onclick="enviarWhatsApp()" class="btn modal-action waves-effect waves-green green">Enviar</a>
		<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Fechar</a>
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

<div id="modal2" class="modal">
	<div class="modal-content">
		<h5>Chave: <strong id="chave"></strong></h5>
		<h5>Motivo: <strong id="motivo"></strong></h5>
		<h5>Protocolo: <strong id="protocolo"></strong></h5>
		
	</div>
	<div class="modal-footer">
		<a href="#!" class="modal-action modal-close btn grey">Fechar</a>
	</div>
</div>

<div id="modal3" class="modal">
	<div class="modal-content">
		<h4>Inutilização de NF</h4>
		<div class="row">
			<div class="input-field col s4">
				<input class="validate" type="text" id="nInicio">
				<label for="nInicio">Numero NF  Inicial</label>
			</div>
			<div class="input-field col s4">
				<input class="validate" type="text" id="nFinal">
				<label for="nFianal">Numero NF Final</label>
			</div>
		</div>
		<div class="row">
			<div class="input-field col s12">
				<textarea id="justificativa" class="materialize-textarea"></textarea>
				<label for="justificativa">Justificativa</label>
			</div>
		</div>

		<div class="row" id="preloader3" style="display: none">
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
		<button onclick="inutilizar()" class="btn blue">Inutilizar</button>
	</div>
</div>

<div id="modal4" class="modal">
	<div class="modal-content">
		<h4>Carta de Correção da NF <strong class="orange-text" id="numero_correcao"></strong></h4>

		<div class="row">
			<div class="input-field col s12">
				<textarea id="correcao" class="materialize-textarea"></textarea>
				<label for="correcao">Correção</label>
			</div>
		</div>

		<div class="row" id="preloader4" style="display: none">
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
		<button onclick="cartaCorrecao()" class="btn blue">Corrigir</button>
	</div>
</div>

<div id="modal5" class="modal">
	<div class="modal-content">
		<h4>Enviar XML da NF <strong class="orange-text" id="numero_nf"></strong></h4>

		<div class="row">
			<p class="blue-text" id="info-email"></p>
			<div class="input-field col s12">
				<input type="email" id="email" name="">
				<label for="email">Email</label>

			</div>
		</div>

		<input type="hidden" id="venda_id">
		<div class="row" id="preloader6" style="display: none">
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
		<button onclick="enviarEmailXMl()" class="btn blue">Enviar Email XML</button>
	</div>
</div>
@endsection	