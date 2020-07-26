@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">

		<h4>Lista de Categorias de Delivery</h4>

		@if(session()->has('message'))
		<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
			<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
		</div>
		@endif

		@if($existeCategoriaPizza)

		<p class="red-text">VOCE AINDA NÃO FEZ CADASTROS DE TAMANHOS DE PIZZA</p>
		<a href="/tamanhosPizza" class="btn-large blue pulse"><i class="material-icons left">
		local_pizza</i>Ir para tamanhos de pizza</a>

		@endif

		<div class="row"></div>
		<div class="row">

			<a href="/deliveryCategoria/new" class="btn green accent-3">
				<i class="material-icons left">add</i>	
				Nova Categoria de Delivery	
			</a>
		</div>


		



		<div class="row">
			<div class="col s12">
				<label>Numero de registros: {{count($categorias)}}</label>					
			</div>
			<table class="col s12">
				<thead>
					<tr>
						<th>Código</th>
						<th>Nome</th>
						<th>Descrição</th>
						<th>Imagem</th>
						<th>Ações</th>
					</tr>
				</thead>

				<tbody>
					@foreach($categorias as $c)
					<tr>
						<th>{{ $c->id }}</th>
						<th>{{ $c->nome }}</th>

						<th>
							<a class="btn brown lighten-2 tooltipped" data-position="bottom" data-delay="50" data-tooltip="{{$c->descricao}}"
								@if(empty($c->descricao))
								disabled
								@endif
								>
								<i class="material-icons">message</i>

							</a>
						</th>

						<th>
							<img style="width: 80px;" src="imagens_categorias/{{$c->path}}">
						</th>

						<th>
							<a href="/deliveryCategoria/edit/{{ $c->id }}">
								<i class="material-icons left">edit</i>					
							</a>

							<a title="Adicionais" href="/deliveryCategoria/additional/{{ $c->id }}">
								<i class="material-icons left green-text">import_contacts</i>					
							</a>
							<a onclick = "if (! confirm('Deseja excluir este registro?')) { return false; }" href="/deliveryCategoria/delete/{{ $c->id }}">
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