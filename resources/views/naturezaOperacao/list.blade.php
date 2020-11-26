@extends('default.layout')
@section('content')

	<div class="row">
		<div class="col s12">

			<h4>Lista de Naturezas de Operação</h4>

			@if(session()->has('message'))
			<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
				<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
			</div>
			@endif

			<div class="row"></div>
			<div class="row">
				<a href="/naturezaOperacao/new" class="btn green accent-3">
			      	<i class="material-icons left">add</i>	
			      	Nova Natureza de Operação	
				</a>
			</div>

			

			<div class="row">
				<div class="col s10">
					<label>Numero de registros: {{count($naturezas)}}</label>					
				</div>
				<table class="col s10">
					<thead>
						<tr>
							<th>#</th>
							<th>Nome</th>
							<th>CFOP Estadual Saida</th>
							<th>CFOP Estadual Entrada</th>

							<th>CFOP Interestadual Saida</th>
							<th>CFOP Interestadual Entrada</th>
							<th>Ações</th>
						</tr>
					</thead>

					<tbody>
						@foreach($naturezas as $c)
						<tr>
							<th>{{ $c->id }}</th>
							<th>{{ $c->natureza }}</th>
							<th>{{ $c->CFOP_saida_estadual }}</th>
							<th>{{ $c->CFOP_entrada_estadual }}</th>
							<th>{{ $c->CFOP_saida_inter_estadual }}</th>
							<th>{{ $c->CFOP_entrada_inter_estadual }}</th>
							<th>
								<a href="/naturezaOperacao/edit/{{ $c->id }}">
	      							<i class="material-icons left">edit</i>					
								</a>
								<a onclick = "if (! confirm('Deseja excluir este registro?')) { return false; }" href="/naturezaOperacao/delete/{{ $c->id }}">
	      							<i class="material-icons left red-text">delete</i>					
								</a>
							</th>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
@endsection	