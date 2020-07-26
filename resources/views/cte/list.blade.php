@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">

		<h4>Lista de CT-e</h4>
		<a href="/cte/nova" class="btn green">
			<i class="material-icons left">add</i>
			Nova CT-e
		</a>
		<div class="row">
			<br>
			<form method="get" action="/cte/filtro">
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
				<label>Numero de registros: {{count($ctes)}}</label>					
			</div>

			<table class="col s12">
				<thead>
					<tr>
						<th></th>
						<th>#</th>
						<th>Destinatário</th>
						<th>Remetente</th>
						<th>Valor do Serviço</th>
						<th>Valor a Receber</th>
						<th>Estado</th>
						<th>Data de Cadastro</th>
						<th>Tomador</th>
						<th>Nro CT-e</th>
						<th>Saldo</th>
						<th>Ações</th>
					</tr>
				</thead>

				<tbody id="body">
					<?php 
					$total = 0;
					?>
					@foreach($ctes as $c)

					<tr>
						<td id="checkbox">
							<p>
								<input type="checkbox" class="check" id="test_{{$c->id}}" />
								<label for="test_{{$c->id}}"></label>
							</p>
						</td>
						<td id="id">{{$c->id}}</td>
						
						<td>{{$c->destinatario->razao_social}}</td>
						<td>{{$c->remetente->razao_social}}</td>

						<td>{{ number_format($c->valor_transporte, 2, ',', '.') }}</td>
						<td>{{ number_format($c->valor_receber, 2, ',', '.') }}</td>
						<td id="estado_{{$c->id}}">{{$c->estado}}</td>
						<th>{{ \Carbon\Carbon::parse($c->data_registro)->format('d/m/Y H:i:s')}}</th>
						<th>{{$c->getTomador()}}</th>
						<td id="numeroNf">{{$c->cte_numero}}</td>

						<td class="
						@if($c->somaReceita() - $c->somaDespesa() > 0)
						green-text
						@elseif($c->somaReceita() - $c->somaDespesa() == 0)
						blue-text
						@else
						red-text
						@endif">
							{{number_format($c->somaReceita() - $c->somaDespesa(), 2)}}
						</td>
						<td>
							@if($c->estado == 'DISPONIVEL')
							<a onclick = "if (! confirm('Deseja excluir este registro?')) { return false; }" href="/cte/delete/{{ $c->id }}">
								<i class="material-icons left red-text">delete</i>					
							</a>
							@endif
							<a href="/cte/detalhar/{{ $c->id }}">
								<i class="material-icons left orange-text">visibility</i>
							</a>

							<a href="/cte/custos/{{ $c->id }}">
								<i class="material-icons left green-text">attach_money
								</i>
							</a>
						</td>

					</tr>
					
					@endforeach
					
				</tbody>
			</table>
		</div>



		@if(isset($links))
		<ul class="pagination center-align">
			<li class="waves-effect">{{$ctes->links()}}</li>
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


		<div class="row">
			<div class="col s2">
				<a id="btn-enviar" onclick="enviar()" style="width: 100%;" class="btn-large orage" href="#!">Enviar</a>
			</div>

			<div class="col s2">
				<a id="btn-imprimir" onclick="imprimir()" style="width: 100%" class="btn-large purple" href="#!">Imprimir</a>
			</div>

			<div class="col s2">
				<a id="btn-consultar" onclick="consultar()" style="width: 100%" class="btn-large green lighten-2" href="#!">Consultar</a>
			</div>

			<div class="col s2">
				<a id="btn-cancelar" onclick="setarNumero()" style="width: 100%" class="btn-large red modal-trigger" href="#modal1">Cancelar</a>
			</div>

			<div class="col s2">
				<a id="btn-correcao" onclick="setarNumero()" style="width: 100%" class="btn-large teal accent-3 waves-light modal-trigger" href="#modal4">CC-e</a>
			</div>

			<div class="col s2">
				<a id="btn-inutilizar" style="width: 100%" class="btn-large lime lighten-1 waves-light modal-trigger" href="#modal3">Inutilizar</a>
			</div>

		</div>


		<div class="row">
			<div class="col s2">
				<a id="btn-xml" onclick="setarNumero(true)" style="width: 100%" class="btn-large grey darken-1 waves-light modal-trigger" href="#modal5">Enviar XML</a>
			</div>

			<div class="col s2">
				<a id="" onclick="imprimirCCe()" style="width: 100%" class="btn-large cyan" href="#!">Imprimir CC-e</a>
			</div>

			<div class="col s2">
				<a id="" onclick="imprimirCancela()" style="width: 100%" class="btn-large red lighten-2" href="#!">Imprime Cancela</a>
			</div>
		</div>
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
		<h4 class="center-align">Algo dando errado</h4>
		<p class="center-align" id="evento-erro"></p>

	</div>
	<div class="modal-footer">
		<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Fechar</a>
	</div>
</div>

<div id="modal1" class="modal">
	<div class="modal-content">
		<h4>Cancelamento da CT-e <strong class="orange-text" id="numero_cancelamento"></strong></h4>
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
		<button onclick="cancelar()" class="btn red">Cancelar CT-e</button>

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

<div id="modal-alert-cancel" class="modal">

	<div class="modal-content">
		<p class="center-align"><i class="large material-icons green-text">cancel</i></p>
		<h4 class="center-align">CT-e Cancelada</h4>
		<p class="center-align" id="evento-cancel"></p>
	</div>
	<div class="modal-footer">
		<a href="#!" onclick="reload()" class="modal-action modal-close waves-effect waves-green btn-flat">Fechar</a>
	</div>
</div>

<div id="modal-alert-inut-erro" class="modal">

	<div class="modal-content">
		<p class="center-align"><i class="large material-icons red-text">cancel</i></p>
		<h4 class="center-align">Algo deu errado!</h4>
		<p class="center-align" id="evento-inut-erro"></p>
	</div>
	<div class="modal-footer">
		<a href="#!" onclick="reload()" class="modal-action modal-close waves-effect waves-green btn-flat">Fechar</a>
	</div>
</div>


<div id="modal-alert-inut" class="modal">

	<div class="modal-content">
		<p class="center-align"><i class="large material-icons green-text">check</i></p>
		<h4 class="center-align">Inutilizadas</h4>
		<p class="center-align" id="evento-inut"></p>
	</div>
	<div class="modal-footer">
		<a href="#!" onclick="reload()" class="modal-action modal-close waves-effect waves-green btn-flat">Fechar</a>
	</div>
</div>

<div id="modal-inut-cancel-erro" class="modal">

	<div class="modal-content">
		<p class="center-align"><i class="large material-icons red-text">cancel</i></p>
		<h4 class="center-align">Algo deu errado!</h4>
		<p class="center-align" id="evento-cancel-erro"></p>
	</div>
	<div class="modal-footer">
		<a href="#!" onclick="reload()" class="modal-action modal-close waves-effect waves-green btn-flat">Fechar</a>
	</div>
</div>

<div id="modal-alert-cce" class="modal">

	<div class="modal-content">
		<p class="center-align"><i class="large material-icons green-text">cancel</i></p>
		<h4 class="center-align">Carta de Correção CT-e</h4>
		<p class="center-align" id="evento-cce"></p>
	</div>
	<div class="modal-footer">
		<a href="#!" onclick="reload()" class="modal-action modal-close waves-effect waves-green btn-flat">Fechar</a>
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
		<h4>Inutilização de CT-e</h4>
		<div class="row">
			<div class="input-field col s4">
				<input class="validate" type="text" id="nInicio">
				<label for="nInicio">Numero CT-e Inicial</label>
			</div>
			<div class="input-field col s4">
				<input class="validate" type="text" id="nFinal">
				<label for="nFianal">Numero Ct-e Final</label>
			</div>
		</div>
		<div class="row">
			<div class="input-field col s12">
				<textarea id="justificativa-inut" class="materialize-textarea"></textarea>
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
		<h4>Carta de Correção da CT-e <strong class="orange-text" id="numero_correcao"></strong></h4>

		<div class="row">
			<div class="input-field col s6">
				<select id="grupo">
					@foreach($grupos as $g)
					<option>{{$g}}</option>
					@endforeach
				</select>
				<label for="grupo">Grupo</label>

			</div>
			<div class="input-field col s6">
				<input type="text" id="campo">
				<label for="campo">Campo</label>
			</div>

		</div>
		
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
		<h4>Enviar XML da CT-e <strong class="orange-text" id="numero_nf"></strong></h4>
		<input type="hidden" id="numero_cte">
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