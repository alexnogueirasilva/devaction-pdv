@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">

		<h4 class="center-align">DF-e</h4>
		<form class="row" method="get" action="/dfe/filtro">
			<div class="input-field col s2">
				<input type="text" value="{{{isset($data_inicial) ? $data_inicial : ''}}}" name="data_inicial" class="datepicker">
				<label>Data Inicial</label>
			</div>
			<div class="input-field col s2">
				<input type="text" name="data_final" value="{{{ isset($data_final) ? $data_final : '' }}}" class="datepicker">
				<label>Data Final</label>
			</div>
			<div class="input-field col s2">
				<select name="tipo">
					<option value="--">TODOS</option>
					<option value="1">CIÊNCIA</option>
					<option value="2">CONFIRMADA</option>
					<option value="3">DESCONHECIDA</option>
					<option value="4">NÃO REALIZADA</option>
					<option value="0">SEM AÇÃO</option>
				</select>
			</div>
			<div class="col s2">
				<button type="submit" class="btn-large black">
					<i class="material-icons">search</i>
				</button>
			</div>
			
		</form>

		@if(session()->has('message'))
		<div class="row">
			<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
				<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
			</div>
		</div>
		@endif

		<input type="hidden" value="{{json_encode($docs)}}" id="docs">

		<a target="_blank" href="/dfe/novaConsulta" class="btn-large">
			<i class="material-icons left">refresh</i>
			Nova Consulta
		</a>
		<p>Total de Registros: <strong id="total-documentos">{{sizeof($docs)}}</strong></p>
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
						<th>Estado</th>
						<th>Ações</th>
					</tr>
				</thead>
				<tbody id="tbl">
					@if(sizeof($docs) == 0)
					<tr>
						<th class="center-align red-text" colspan="7">Nada encontrado, clique em nova consulta!!</th>
					</tr>
					@endif
					@foreach($docs as $d)
					<tr>
						<td>{{$d->nome}}</td>
						<td>{{$d->documento}}</td>
						<td>{{number_format($d->valor, 2)}}</td>
						<td>{{ \Carbon\Carbon::parse($d->data_emissao)->format('d/m/Y H:i:s')}}</td>

						<td>{{$d->num_prot}}</td>
						<td>{{$d->chave}}</td>
						<td>{{$d->estado()}}</td>

						<td>
							@if($d->tipo == 1 || $d->tipo == 2)
							<a style="width: 100%;" target="_blank" href="/dfe/download/{{$d->chave}}" class="btn green">Completa</a>
							<a style="width: 100%;" target="_blank" href="/dfe/imprimirDanfe/{{$d->chave}}" class="btn blue">Imprimir</a>
							@elseif($d->tipo == 3)
							<a style="width: 100%;" class="btn red">Desconhecida</a>
							@elseif($d->tipo == 4)
							<a style="width: 100%;" class="btn red">Não realizada</a>

							@else
							<a style="width: 100%;" href="#modal1" onclick="setarEvento('{{$d->chave}}')" class="btn red modal-trigger">Manifestar</a>
							@endif
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		
	</div>
</div>

<div id="modal1" class="modal">
	<form method="get" action="/dfe/manifestar">
		

		<div class="modal-content">
			<h4>MANIFESTAÇÃO DE DESTINATÁRIO</h4>
			<div class="row">
				
				<div class="input-field col s10">
					<select name="evento" id="tipo_evento">
						<option value="1">Ciencia de operção</option>
						<option value="2">Confirmação</option>
						<option value="3">Desconhecimento</option>
						<option value="4">Operação não realizada</option>
						
					</select>
					<label>Evento</label>
				</div>
			</div>

			<input type="hidden" id="nome" name="nome" />
			<input type="hidden" id="cnpj" name="cnpj" />
			<input type="hidden" id="valor" name="valor" />
			<input type="hidden" id="data_emissao" name="data_emissao" />
			<input type="hidden" id="num_prot" name="num_prot" />
			<input type="hidden" id="chave" name="chave" />

			<div class="row">
				<div class="input-field col s12" style="display: none" id="div-just">
					<input type="text" name="justificativa" id="justificativa" data-length="100">
					
					<label>Justificativa</label>
				</div>
			</div>

		</div>
		<div class="modal-footer">
			<a href="#!" class="modal-action modal-close red white-text waves-effect waves-green btn-flat">Fechar</a>
			<button href="#!" class="modal-action waves green accent-3 btn">OK</button>
		</div>
	</form>
</div>


@endsection	