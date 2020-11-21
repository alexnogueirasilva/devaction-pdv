@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">
		<div class="row">
			<h3 class="center-align">Pedido Delivery <strong class="red-text">{{$pedido->id}}</strong></h3>
		</div>
		
		<div class="row">
			<div class="col s12">
				<h4>Cliente: <strong class="cyan-text">{{$pedido->cliente->nome}}</strong></h4>
				<h4>Telefone: <strong class="cyan-text">{{$pedido->telefone}}</strong></h4>
				@if($pedido->app == 0)
				<h4><strong class="red-text">Pedido realizado através do WebDelivery</strong></h4>
				@else
				<h4><strong class="red-text">Pedido realizado através do App</strong></h4>
				@endif
				<h4>Horario: <strong class="cyan-text">{{ \Carbon\Carbon::parse($pedido->data_registro)->format('H:i:s')}}</strong></h4>
				<h4>Estado Atual: @if($pedido->estado == 'nv')
					<strong class="blue-text">NOVO</strong>
					@elseif($pedido->estado == 'rp')
					<strong class="red-text">REPROVADO</strong>
					@elseif($pedido->estado == 'rc')
					<strong class="yellow-text">RECUSADO</strong>
					@elseif($pedido->estado == 'ap')
					<strong class="green-text">APROVADO</strong>
					@else
					<strong class="cyan-text">FINALIZADO</strong>
					@endif 

					<a class="btn lighten-2 tooltipped" data-position="bottom" data-delay="50" data-tooltip="{{$pedido->motivoEstado}}"
						@if(empty($pedido->motivoEstado))
						disabled
						@endif
						>
						<i class="material-icons">message</i>

					</a>
				</h4>
				@if($pedido->forma_pagamento == 'dinheiro')
				<h4>Troco para: R$ {{number_format($pedido->troco_para, 2)}}</h4>
				@endif

				@if($pedido->endereco_id != null)
				<a class="btn cyan waves-light modal-trigger" href="#modal-endereco">
					<i class="material-icons left">map</i> Ver Endereço
				</a>

				<a class="btn red waves-light" target="_blank" href="/clientesDelivery/enderecosMap/{{$pedido->endereco_id }}">
					<i class="material-icons left">place</i> Ver Mapa
				</a>
				@endif

				<a target="_blank" class="btn cyan waves-light green" href="/pedidosDelivery/print/{{$pedido->id}}">
					<i class="material-icons left">print</i> Imprimir Pedido
				</a>
				@if($pedido->app == 1)
				<a class="btn cyan waves-light blue modal-trigger" href="#modal-push">
					<i class="material-icons left">notifications</i> Enviar Push
				</a>

				@else

				@if(sizeof($pedido->cliente->tokensWeb) > 0)
				<a class="btn cyan waves-light blue modal-trigger" href="#modal-push-web">
					<i class="material-icons left">notifications</i> Enviar Push Web
				</a>

				@endif
				@endif

				<a onclick="setaTelefone('{{$pedido->telefone}}')" class="btn cyan waves-light orange modal-trigger" href="#modal-sms">
					<i class="material-icons left">send</i> Enviar SMS
				</a>


			</div>

			<div class="col s12">
				<h4>Valor da Entrega: <strong class="cyan-text">{{number_format($pedido->calculaFrete(), 2 , ',', '.')}}</strong></h4>
				<h4>Total do Pedido: <strong class="cyan-text">{{number_format($pedido->valor_total,2 , ',', '.')}}</strong></h4>
				
				<h4>Total de Itens: <strong class="cyan-text">{{count($pedido->itens)}}</strong></h4>
				<h4>Forma de pagamento: <strong class="cyan-text">{{strtoupper($pedido->forma_pagamento)}}</strong></h4>

				@if($pedido->observacao != '')
				<h4>Observação: <strong class="cyan-text">{{$pedido->observacao}}</strong></h4>
				@endif
				@if($pedido->troco_para > 0)
				<h4>Troco Para: <strong class="cyan-text">{{$pedido->troco_para}}</strong></h4>
				@endif
			</div>
		</div>

		@if($pedido->forma_pagamento == 'pagseguro')
		<div class="card">
			<div class="row">
				
				<div class="card-content">
					<h4 class="center-align">Dados do Pagamento PagSeguro</h4>
					@if($pedido->pagseguro->status == '1' || $pedido->pagseguro->status == '2')
					<h5 class="red-text">CUIDADO, CONSULTE A TRANSAÇÃO, VERIFIQUE SE AUTORIZADA!!</h5>
					@endif
					<h5>Parcelas: <strong class="red-text">{{strtoupper($pedido->pagseguro->parcelas)}}</strong></h5>
					<h5>Referência: <strong class="red-text">{{strtoupper($pedido->pagseguro->referencia)}}</strong></h5>
					<h5>Código da transação: <strong class="red-text">{{strtoupper($pedido->pagseguro->codigo_transacao)}}</strong></h5>
					<h5>Número do Cartão: <strong class="red-text">{{strtoupper($pedido->pagseguro->numero_cartao)}}</strong></h5>
					<h5>CPF: <strong class="red-text">{{strtoupper($pedido->pagseguro->cpf)}}</strong></h5>
					<button onclick="consultar('{{$pedido->pagseguro->codigo_transacao}}')" class="btn">Consultar Transação</button>

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
			</div>
		</div>
		@endif

		@if(!empty($pedido->cupom))
		<div class="card">
			<div class="row">
				

				<div class="col s12">
					<h4 class="center-align">Cliente utilizou cupom de desconto</h4>
					<h5>Cupom de: <strong class="red-text">{{$pedido->cupom->tipo == 'valor' ? 'R$' : ''}} {{number_format($pedido->cupom->valor, 2)}} {{$pedido->cupom->tipo == 'percentual' ? '%' : ''}}</strong></h5>
					<h5>Cupom de Desconto: <strong class="red-text">{{$pedido->cupom->codigo}}</strong></h5>
					<h5>Valor de Desconto: <strong class="red-text">R$ {{$pedido->desconto}}</strong></h5>
				</div>
			</div>
		</div>
		@endif

		<div class="row">
			<table>
				<thead>
					<tr>
						<th>#</th>
						<th>Nome</th>
						<th>Quantidade</th>
						<th>Sabores</th>
						<th>Valor Unit</th>
						<th>Status</th>
						<th>Adicionais</th>
						<th>SubTotal</th>
						<th>SubTotal + Adicional</th>
						<th>Ações</th>
					</tr>
				</thead>

				<tbody>
					@foreach($pedido->itens as $i)
					<tr class="@if($i->status) green lighten-4 @endif">
						<td>{{$i->produto->id}}</td>
						<td>{{$i->produto->produto->nome}}</td>
						<td>{{$i->quantidade}}</td>
						<td>
							@if(count($i->sabores) > 0)
							@foreach($i->sabores as $s)
							{{$s->produto->produto->nome}}<br>

							@endforeach
							<strong>Tamanho: {{$i->tamanho->nome()}} - {{$i->tamanho->pedacos}} pedaços</strong>
							@else
							--
							@endif
						</td>
						<td>
							@if(count($i->sabores) > 0)
							<?php 
							$maiorValor = 0; 
							$somaValores = 0;
							foreach($i->sabores as $it){
								$v = $it->maiorValor($it->produto->id, $i->tamanho_id);
								$somaValores += $v;
								if($v > $maiorValor) $maiorValor = $v;
							}

							if(getenv("DIVISAO_VALOR_PIZZA") == 1){
								$maiorValor = number_format(($somaValores/sizeof($i->sabores)),2);
							}


							?>
							{{number_format($maiorValor, 2, ',', '.')}}
							@else
							{{number_format($i->produto->valor, 2, ',', '.')}}
							@endif

						</td>
						<td>
							@if($i->status)
							<i class="material-icons green-text">brightness_1</i>
							@else
							<i class="material-icons red-text">brightness_1</i>
							@endif
						</td>
						<td>
							@if(count($i->itensAdicionais) > 0)

							@foreach($i->itensAdicionais as $key => $ad)
							{{$ad->adicional->nome()}} 
							@if($key < count($i->itensAdicionais)-1)
							|
							@endif
							@endforeach

							@else
							Nenhum 
							@endif
						</td>
						<?php  
						if(sizeof($i->sabores) > 0){
							$subTotal = $subComAdicional = $maiorValor * $i->quantidade;
						}else{
							$subTotal = $subComAdicional = $i->produto->valor * $i->quantidade;
						}
						foreach($i->itensAdicionais as $a){
							$subComAdicional += $i->quantidade * $a->adicional->valor;
						}
						?>
						<td>{{number_format($subTotal, 2, ',', '.')}}</td>
						<td>{{number_format($subComAdicional, 2, ',', '.')}}</td>
						<td>
							<a onclick = "if (! confirm('Deseja excluir este item do pedido?')) { return false; }" href="/pedidosDelivery/deleteItem/{{ $i->id }}">
								<i class="material-icons left red-text">delete</i>					
							</a>

							@if(!$i->status)
							<a onclick = "if (! confirm('Deseja marcar este item como concluido?')) { return false; }" href="/pedidosDelivery/alterarStatus/{{ $i->id }}">
								<i class="material-icons green-text">check</i>
							</a>
							@endif
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>

		</div>

		<input type="hidden" id="token" value="{{csrf_token()}}">
		<input type="hidden" id="cliente" value="{{$pedido->cliente->id}}">
		<div class="row">
			@if($pedido->estado == 'nv' || $pedido->estado == 'ap')
			<div class="col s3">
				<form action="/pedidosDelivery/alterarPedido" method="get">
					<input type="hidden" name="id" value="{{$pedido->id}}">
					<input type="hidden" name="tipo" value="rc">
					<button style="width: 100%" class="btn yellow" type="submit">Alterar para Recusado</button>
				</form>
			</div>
			@endif
			@if($pedido->estado == 'nv')
			<div class="col s3">
				<form action="/pedidosDelivery/alterarPedido" method="get">
					<input type="hidden" name="id" value="{{$pedido->id}}">
					<input type="hidden" name="tipo" value="rp">
					<button style="width: 100%" class="btn red" type="submit">Alterar para Reprovado</button>
				</form>
			</div>
			@endif
			@if($pedido->estado == 'nv')
			<div class="col s3">
				<form action="/pedidosDelivery/alterarPedido" method="get">
					<input type="hidden" name="id" value="{{$pedido->id}}">
					<input type="hidden" name="tipo" value="ap">
					<button style="width: 100%" class="btn green" type="submit">Alterar para Aprovado</button>
				</form>
			</div>
			@endif
			@if($pedido->estado == 'ap')
			<div class="col s3">
				<form action="/pedidosDelivery/alterarPedido" method="get">
					<input type="hidden" name="id" value="{{$pedido->id}}">
					<input type="hidden" name="tipo" value="fz">
					<button style="width: 100%" class="btn green" type="submit">Alterar para Finalizado</button>
				</form>
			</div>
			@endif

			@if($pedido->estado == 'fz')
			<a class="btn green" href="/pedidosDelivery/irParaFrenteCaixa/{{$pedido->id}}">Ir para frente de caixa</a>
			@endif

		</div>
	</div>
</div>

@if($pedido->endereco)
<div id="modal-endereco" class="modal">
	<div class="modal-content">
		<h5>Rua: <strong id="rua">{{$pedido->endereco->rua}}, {{$pedido->endereco->numero}}</strong></h5>
		<h5>Bairro: <strong id="bairro">{{$pedido->endereco->bairroComValor()}}</strong></h5>
		<h5>Referência: <strong id="referencia">{{$pedido->endereco->referencia}}</strong></h5>

	</div>
	<div class="modal-footer">
		<a href="#!" class="modal-action modal-close btn grey">Fechar</a>
	</div>
</div>
@endif

<div id="modal-push" class="modal">
	<div class="row">
		<div class="col s2 offset-s5">
			<i class="material-icons large pink-text">notifications</i>
		</div>
	</div>
	<div class="modal-content">
		<div class="row">
			<div class="col s6 input-field">
				<input type="text" id="titulo-push">
				<label>Titulo Push</label>
			</div>
		</div>
		<div class="row">
			<div class="col s12 input-field">
				<input type="text" id="texto-push">
				<label>Texto Push</label>
			</div>
		</div>

		<div class="row">
			<div class="col s12 input-field">
				<input type="text" id="imagem-push">
				<label>URL Imagem (opcional)</label>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#!" id="btn-enviar-push" class="modal-action btn blue">Enviar Push</a>
		<a href="#!" class="modal-action modal-close btn grey">Fechar</a>
	</div>
</div>

<div id="modal-push-web" class="modal">
	<div class="row">
		<div class="col s2 offset-s5">
			<i class="material-icons large pink-text">notifications</i>
		</div>
	</div>
	<div class="modal-content">
		<div class="row">
			<div class="col s6 input-field">
				<input type="text" id="titulo-push-web">
				<label>Titulo Push</label>
			</div>
		</div>
		<div class="row">
			<div class="col s12 input-field">
				<input type="text" id="texto-push-web">
				<label>Texto Push</label>
			</div>
		</div>

		<div class="row">
			<div class="col s12 input-field">
				<input type="text" id="imagem-push-web">
				<label>URL Imagem (opcional)</label>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#!" id="btn-enviar-push-web" class="modal-action btn blue">Enviar Push Web</a>
		<a href="#!" class="modal-action modal-close btn grey">Fechar</a>
	</div>
</div>

<div id="modal-consulta" class="modal">
	<div class="modal-content">
		<div class="col s12">
			<h5>Referencia: <strong id="referencia"></strong></h5>
			<h5>Status: <strong id="status"></strong></h5>
			<h5>Total: <strong id="total"></strong></h5>
			<h5>Taxa: <strong id="taxa"></strong></h5>
		</div>
	</div>
	<div class="modal-footer">
		<a href="#!" class="modal-action modal-close btn grey">Fechar</a>
	</div>
</div>

<div id="modal-sms" class="modal">
	<div class="row">
		<div class="col s2 offset-s5">
			<i class="material-icons large orange-text">send</i>
		</div>

	</div>
	<div class="modal-content">
		<h4>Enviar SMS</h4>
		<p>Saldo: <strong class="red-text">{{$saldoSms}}</strong></p>
		<div class="row">
			<div class="col s6 input-field">
				<input type="tel" id="telefone-sms">
				<label>Telefone SMS</label>
			</div>
		</div>
		<div class="row">
			<div class="col s12 input-field">
				<input type="text" value="Pedido saiu para entrega" id="texto-sms">
				<label>Texto SMS</label>
			</div>
		</div>
		@if($saldoSms == 0)
		<p class="red-text">Saldo Vazio!!</p>
		@endif
	</div>
	<div class="modal-footer">
		<a href="#!" class="modal-action modal-close btn grey">Fechar</a>
		<a href="#!" id="btn-enviar-sms" class="modal-action btn blue @if($saldoSms == 0) disabled @endif">Enviar SMS</a>

	</div>
</div>
@endsection	