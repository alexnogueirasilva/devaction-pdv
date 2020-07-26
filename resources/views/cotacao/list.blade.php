

@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">
		<h4>Lista de Cotações</h4>

		<div class="row">
			<a href="/cotacao/new" class="btn green accent-3">
				<i class="material-icons left">add</i>	
				Nova Cotação		
			</a>

			<a href="/cotacao/listaPorReferencia" class="btn blue accent-3">
				<i class="material-icons left">playlist_add_check</i>	
				Cotações por referencia		
			</a>
		</div>

		@if(session()->has('message'))
		<div class="row">
			<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
				<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
			</div>
		</div>
		@endif


		<form method="get" action="/cotacao/filtro">
			<div class="row">
				<div class="col s4 input-field">
					<input value="{{{ isset($fornecedor) ? $fornecedor : '' }}}" type="text" class="validate" name="fornecedor">
					<label>Fornecedor</label>
				</div>

				<div class="col s2 input-field">
					<input value="{{{ isset($data_inicial) ? $data_inicial : '' }}}" type="text" class="datepicker" name="data_inicial">
					<label>Data Inicial</label>
				</div>
				<div class="col s2 input-field">
					<input value="{{{ isset($data_final) ? $data_final : '' }}}" type="text" class="datepicker" name="data_final">
					<label>Data Final</label>
				</div>

				<div class="col s2">
					<button type="submit" class="btn-large black">
						<i class="material-icons">search</i>
					</button>
				</div>
			</div>
		</form>


		<div class="row">
			<div class="col s12 m12 l12">
				<label>Numero de registros: {{count($cotacoes)}}</label>					
			</div>
			
			<table class="">
				<thead>
					<tr>
						<th>#</th>
						<th>Fornecedor</th>
						<th>Ativa</th>
						<th>Respondida</th>
						<th>Referencia</th>
						<th>Data</th>
						<th>Ações</th>

					</tr>
				</thead>

				<tbody>
					@foreach($cotacoes as $c)
					<tr>
						<td>{{$c->id}}</td>
						<td>{{$c->fornecedor->razao_social}}</td>

						<td>
							@if(!$c->ativa)
							<i class="material-icons red-text">lens</i>
							@else
							<i class="material-icons green-text">lens</i>
							@endif
						</td>

						<td>
							@if(!$c->resposta)
							<i class="material-icons red-text">lens</i>
							@else
							<i class="material-icons green-text">lens</i>
							@endif
						</td>

						<td>
							{{$c->referencia}}
						</td>
						<td>{{ \Carbon\Carbon::parse($c->data_registro)->format('d/m/Y H:i')}}</td>

						<td>
							<a href="/cotacao/edit/{{ $c->id }}">
								<i class="material-icons left">edit</i>					
							</a>
							<a onclick = "if (! confirm('Deseja excluir este registro?')) { return false; }" href="/cotacao/delete/{{ $c->id }}">
								<i class="material-icons left red-text">delete</i>					
							</a>

							<a href="/cotacao/view/{{ $c->id }}">
								<i class="material-icons left cyan-text">visibility</i>			
							</a>

							<a href="/cotacao/sendMail/{{ $c->id }}">
								<i class="material-icons left green-text">mail</i>			
							</a>

							<a target="_blank" href="/response/{{ $c->link }}">
								<i class="material-icons left blue-text">insert_link</i>			
							</a>
							@if($c->ativa == true)
							<a title="desativar" href="/cotacao/alterarStatus/{{$c->id}}/0">
								<i class="material-icons left orange-text">
									cancel
								</i>
							</a>
							@else
							<a title="desativar" href="/cotacao/alterarStatus/{{$c->id}}/1">
								<i class="material-icons left green-text">
									check
								</i>
							</a>
							@endif
						</td>
					</tr>
					@endforeach
				</tbody>

			</table>
		</div>

	</div>
</div>

@endsection	