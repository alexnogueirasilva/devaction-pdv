@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">

		<h4>Lista de Vendas de Frente de Caixa</h4>

		@if(session()->has('message'))
		<div class="row">
			<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
				<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
			</div>
		</div>
		@endif



		<br>
		<form method="get" action="/frenteCaixa/filtro">
			<div class="row">
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
		<input type="hidden" id="_token" value="{{ csrf_token() }}">

		<a href="/frenteCaixa" class="btn-large blue">
			<i class="material-icons left">
				inbox
			</i>
		FRENTE DE CAIXA</a>

		<a href="#!" onclick="modalWhatsApp()" class="btn-large green accent-3">
			<i class="material-icons left">
				message
			</i>
		ENVIAR WHATSAPP</a>

		<a target="_blank" href="/relatorios/filtroVendaDiaria?data_inicial={{date('d-m-Y')}}&total_resultados=" class="btn-large red accent-3">
			<i class="material-icons left">
				insert_drive_file
			</i>
		BAIXAR RELATÓRIO</a>

		<a href="#modal-somas" class="btn-large modal-trigger orange accent-3">
			<i class="material-icons left">
				attach_money
			</i>
		SOMA DETALHADA</a>

		<p class="red-text">{{$info}}</p>

		<div class="row">
			<div class="col s12">
				<label>Numero de registros: {{count($vendas)}} </label>					
			</div>
			<table class="col s12">
				<thead>
					<tr>
						<th>#</th>
						<th>Cliente</th>
						<th>Data</th>
						<th>Tipo de pagamento</th>
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

					<tr class="green lighten-3">

						@elseif($v->estado == 'REJEITADO')
						<tr class="red lighten-4">
							@else
							<tr class="blue lighten-3">


								@endif
								<th>{{ $v->id }}</th>
								<th>{{ $v->cliente->razao_social ?? 'NAO IDENTIFCADO' }}</th>
								<th>{{ \Carbon\Carbon::parse($v->data_registro)->format('d/m/Y H:i:s')}}</th>
								<th>
									@if($v->tipo_pagamento == '99')

									<a class="btn green lighten-2 tooltipped" data-position="bottom" data-delay="50" data-tooltip="{{$v->multiplo()}}">
										<i class="material-icons">money</i>

									</a>

									@else
									{{$v->getTipoPagamento($v->tipo_pagamento)}}
									@endif

								</th>
								<th>{{ $v->estado }}</th>


								<th>{{ $v->NFcNumero > 0 ? $v->NFcNumero : '--' }}</th>
								<th>{{ $v->usuario->nome }}</th>
								<th>{{ number_format($v->valor_total, 2, ',', '.') }}</th>
								<th>

									@if($v->NFcNumero && $v->estado == 'APROVADO')
									<a target="_blank" title="CUPOM FISCAL" href="/nfce/imprimir/{{$v->id}}">
										<i class="material-icons green-text">print</i>
									</a>

									<a onclick="consultarNFCe('{{$v->id}}')" title="CONSULTAR" href="#!">
										<i class="material-icons orange-text">find_in_page</i>
									</a>

									<a target="_blank" title="BAIXAR XML" href="/nfce/baixarXml/{{$v->id}}">
										<i class="material-icons red-text">file_download</i>
									</a>

									
									@else
									<a class="disabled">
										<i class="material-icons grey-text">print</i>
									</a>
									@endif
									<a target="_blank" title="CUPOM NAO FISCAL" href="/nfce/imprimirNaoFiscal/{{$v->id}}">
										<i class="material-icons blue-text">print</i>
									</a>

									@if(!$v->NFcNumero)
									<a data-position="top" data-delay="50" data-tooltip="Enviar para Sefaz" onclick='swal("Atenção!", "Deseja enviar esta venda para Sefaz?", "warning").then((sim) => {if(sim){ emitirNFCe({{$v->id}}) }else{return false} })' href="#!" class="tooltipped">
										<i class="material-icons green-text">nfc</i>
									</a>
									@endif

									

									<div id="preloader_{{$v->id}}" style="display: none" class="preloader-wrapper small active">
										<div class="spinner-layer spinner-red-only">
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


									<!-- <a href="/frenteCaixa/cancelarNFCe/{{$v->id}}" onclick = "if (! confirm('Deseja  esta venda?')) { return false; }else{emitirNFCe({{$v->id}})}" title="CANCELAR" >
										<i class="material-icons red-text">close</i>
									</a> -->

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
				<a href="#!" class="modal-action red white-text modal-close waves-effect waves-green btn-flat">Fechar</a>
			</div>
		</div>

		<div id="modal-alert" class="modal">

			<div class="modal-content">
				<p class="center-align"><i class="large material-icons green-text">check_circle</i></p>
				<h4 class="center-align">Tudo Certo!</h4>
				<p class="center-align" id="evento"></p>

			</div>
			<div class="modal-footer">
				<a href="#!" onclick="redireciona()" class="modal-action modal-close waves-effect waves-green btn-flat">Fechar</a>
			</div>
		</div>

		<div id="modal-somas" class="modal">

			<div class="modal-content">
				@foreach($somaTiposPagamento as $key => $s)
				@if($s > 0)
				<h4 class="center-align">{{App\VendaCaixa::getTipoPagamento($key)}} = <strong class="red-text">R$ {{number_format($s, 2)}}</strong></h4>
				@endif
				@endforeach

			</div>
			<div class="modal-footer">
				<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Fechar</a>
			</div>
		</div>

		<div id="modal-credito" class="modal">

			<div class="modal-content">
				<p class="center-align"><i class="large material-icons green-text">check_circle</i></p>
				<h4 class="center-align">Tudo Certo!</h4>
				<p class="center-align" id="evento-conta-credito"></p>

			</div>
			<div class="modal-footer">
				<a href="#!" onclick="redireciona()" class="modal-action modal-close waves-effect waves-green btn-flat">Fechar</a>
			</div>
		</div>

		<div id="modal-alert-erro" class="modal">

			<div class="modal-content">
				<p class="center-align"><i class="large material-icons red-text">error</i></p>
				<h4 class="center-align">Aldo deu errado!</h4>
				<p class="center-align" id="evento-erro"></p>

			</div>
			<div class="modal-footer">
				<a href="#!" onclick="redireciona()" class="modal-action modal-close waves-effect waves-green btn-flat">Fechar</a>
			</div>
		</div>
		@endsection	