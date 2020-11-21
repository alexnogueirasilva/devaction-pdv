@extends('default.layout')
@section('content')

<div class="row">


	<div class="col s12">
		@if(session()->has('message'))
		<div class="row">
			<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
				<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
			</div>
		</div>
		@endif


		<div class="card">
			<div class="row">
				<div class="col s6">
					<h4>Código: <strong>{{$compra->id}}</strong></h4>
					<h4>Nº NF-e Importada: <strong class="red-text">{{$compra->nf > 0 ? $compra->nf : '*'}}</strong></h4>
					<h4>Nº NF-e Emitida: <strong class="green-text">{{$compra->numero_emissao > 0 ? $compra->numero_emissao : '*'}}</strong></h4>
					<h4>Usuário: <strong>{{$compra->usuario->nome}}</strong></h4>
					<h4>Estado: <strong>{{$compra->estado}}</strong></h4>
					

				</div>
				<div class="col s6">
					<h4>Fornecedor: <strong>{{$compra->fornecedor->razao_social}}</strong></h4>
					<h4>Data: <strong>{{ \Carbon\Carbon::parse($compra->date_register)->format('d/m/Y H:i:s')}}</strong></h4>
					<h4>Observação: {{$compra->observacao}}</h4>
					@if($compra->numero_emissao > 0)
					<h4>Data de Emissão: <strong>{{ \Carbon\Carbon::parse($compra->updated_at)->format('d/m/Y H:i:s')}}</strong></h4>
					@endif
				</div>
				
			</div>
		</div>
		
		
		<div class="row">
			<div class="col s12">
				<h4>ITENS</h4>
				<table class="striped">
					<thead>
						<tr>
							<th>#</th>
							<th>Produto</th>
							<th>Valor</th>
							<th>Quantidade</th>
							<th>Subtotal</th>
							<!-- <th>Ações</th> -->
						</tr>
					</thead>

					<tbody>
						@foreach($compra->itens as $i)
						<tr>
							<td>{{$i->produto_id}}</td>
							<td>{{$i->produto->nome}}</td>
							<td>{{number_format($i->valor_unitario, 2, ',', '.')}}</td>
							<td>{{$i->quantidade}}</td>
							<td>{{number_format(($i->valor_unitario * $i->quantidade), 2, ',', '.')}}</td>
							<!-- <td>
								<a onclick = "if (! confirm('Deseja excluir este registro?')) { return false; }" href="/pedidos/deleteItem/{{ $i->id }}">
									<i class="material-icons left red-text">delete</i>					
								</a>
							</td> -->
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
		<input type="hidden" id="_token" value="{{csrf_token()}}">

		<div class="row">
			<div class="col s6">
				<h4>FATURA</h4>
				<table class="striped">
					<thead>
						<tr>

							<th>Vencimento</th>
							<th>Valor</th>
						</tr>
					</thead>
					@if(sizeof($compra->fatura) > 0)
					<tbody>
						@foreach($compra->fatura as $f)
						<tr>
							<th>{{ \Carbon\Carbon::parse($f->data_vencimento)->format('d/m/Y')}}</th>
							<td>{{number_format(($f->valor_integral), 2, ',', '.')}}</td>
							
						</tr>
						@endforeach
					</tbody>
					@else
					<tbody>
						<tr>
							<th>{{ \Carbon\Carbon::parse($compra->created_at)->format('d/m/Y')}}</th>
							<td>{{number_format(($compra->valor), 2, ',', '.')}}</td>
							
						</tr>
					</tbody>
					@endif
				</table>
			</div>

			<div class="col s6">
				<div class="col s10 input-field">

					<select id="natureza">
						@foreach($naturezas as $n)
						<option value="{{$n->id}}">{{$n->natureza}} - {{$n->CFOP_entrada_estadual}}/{{$n->CFOP_entrada_inter_estadual}}</option>
						@endforeach
					</select>
					<label>Natureza de Operação</label>
				</div>

				<div class="col s6 input-field">

					<select id="tipo_pagamento">
						@foreach($tiposPagamento as $key => $t)
						<option value="{{$key}}">{{$key}} - {{$t}}</option>
						@endforeach
					</select>
					<label>Tipo de pagamento</label>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col s7">
				<h4>TOTAL: <strong class="green-text">{{number_format($compra->somaItems(), 2, ',', '.')}}</strong></h4>

			</div>
			<div class="col s5">
				<br>

				@if($compra->chave != '')

				@if($compra->estado != 'CANCELADO')
				<a target="_blank" style="width: 100%" href="/compras/imprimir/{{$compra->id}}" class="btn blue">
					<i class="material-icons left">print</i> Imprimir
				</a>
				<a target="_blank" style="width: 100%" href="/compras/downloadXml/{{$compra->id}}" class="btn">
					<i class="material-icons left">archive</i> Downlaod XML
				</a>
				<a style="width: 100%" href="#modal-cancelar" class="btn modal-trigger red">
					<i class="material-icons left">close</i>Cancelar
				</a>
				@else
				<a target="_blank" style="width: 100%" href="/compras/downloadXmlCancela/{{$compra->id}}" class="btn red">
					<i class="material-icons left">archive</i> Downlaod XML Cancelamento
				</a>
				@endif
				@else
				<a target="_blank" onclick="enviar({{$compra->id}})" style="width: 90%" class="btn red">
					<i class="material-icons left">nfc</i> Transmitir

				</a>


				@endif
			</div>
		</div>
		<div class="row">
			<div class="col s2 offset-s10">

				<div id="preloader" style="display: none;" class="preloader-wrapper active">
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
		</div>
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

<div id="modal-cancelar" class="modal">
	<div class="modal-content">
		<h4>Cancelamento da NF <strong class="orange-text">{{$compra->numero_emissao}}</strong></h4>
		<div class="row">
			<div class="input-field col s12">
				<textarea id="justificativa" class="materialize-textarea"></textarea>
				<label for="justificativa">Justificativa minimo de 15 caracteres</label>
			</div>
		</div>

		<input type="hidden" id="compra_id" value="{{$compra->id}}" name="">
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