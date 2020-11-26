@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">
		<nav class="black">
			<div class="nav-wrapper">
				<form method="get" action="/produtos/pesquisa">
					<div class="input-field">
						<input placeholder="Pesquisa de Produto" id="search" name="pesquisa" 
						type="search" required>
						<label class="label-icon" for="search">
							<i class="material-icons">search</i></label>
							<i class="material-icons">close</i>
						</div>

					</form>
				</div>
			</nav>
			<br><br>
			<div class="row">
				<form method="get" action="/produtos/filtroCategoria">
					<div class="col s3">
						<select name="categoria">
							@foreach($categorias as $c)
							<option 
							@if(isset($categoria))
							@if($c->nome == $categoria)
							selected
							@endif
							@endif
							 value="{{$c->id}}">{{$c->nome}}</option>
							@endforeach
						</select>
						<label>Categoria</label>
					</div>
					<div class="col s2">
						<button type="submit" class="btn-large red">Filtrar</button>
					</div>
				</form>
			</div>

			@if(isset($categoria))
			<div class="row">
				<p class="red-text">Filtro da categoria {{$categoria}}</p>
			</div>
			@endif
			<h4>Lista de Produtos</h4>

			@if(session()->has('message'))
			<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
				<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
			</div>
			@endif

			<div class="row"></div>
			<div class="row">
				<a href="/produtos/new" class="btn green accent-3">
					<i class="material-icons left">add</i>	
					Novo Produto		
				</a>
			</div>



			<div class="row">
				<div class="col s12">
					<label>Numero de registros: {{count($produtos)}}</label>					
				</div>
				<table class="col s12 striped">
					<thead>
						<tr>
							<th>Código</th>
							<th>Nome</th>
							<th>Categoria</th>
							<th>Valor de Venda</th>
							<th>Cor</th>
							<th>Unidade Compra</th>
							<th>Unidade Venda</th>
							<th>Composto</th>
							<th>Valor Livre</th>
							<th>Estoque atual</th>
							<th>Ações</th>
						</tr>
					</thead>

					<tbody>
						@foreach($produtos as $p)
						<tr>
							<th>{{ $p->id }}</th>
							<th>{{ $p->nome }}</th>
							<th>{{ $p->categoria->nome }}</th>
							<th>{{ number_format($p->valor_venda, 2, ',', '.') }}</th>
							<th>{{ $p->cor }}</th>
							<th>{{ $p->unidade_compra }}</th>
							<th>{{ $p->unidade_venda }}</th>
							<th>
								@if($p->composto)
								<i class="material-icons green-text">brightness_1</i>
								@else
								<i class="material-icons red-text">brightness_1</i>
								@endif
							</th>

							<th>
								@if($p->valor_livre)
								<i class="material-icons green-text">brightness_1</i>
								@else
								<i class="material-icons red-text">brightness_1</i>
								@endif
							</th>
							<th>
								{{$p->estoque_atual}}
							</th>
							<th>
								<a href="/produtos/edit/{{ $p->id }}">
									<i class="material-icons left">edit</i>					
								</a>
								<!-- <a onclick = "if (! confirm('Deseja excluir este registro?')) { return false; }" href="/produtos/delete/{{ $p->id }}">
									<i class="material-icons left red-text">delete</i>					
								</a> -->

								<a onclick='swal("Atenção!", "Deseja remover este registro?", "warning").then((sim) => {if(sim){ location.href="/produtos/delete/{{ $p->id }}" }else{return false} })' href="#!">
									<i class="material-icons left red-text">delete</i>					
								</a>
								

								@if($p->composto)
								<a href="/produtos/receita/{{ $p->id }}">
									<i class="material-icons left green-text">import_contacts</i>					
								</a>
								@endif
							</th>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			@if(isset($links))
			<ul class="pagination center-align">
				<li class="waves-effect">{{$produtos->links()}}</li>
			</ul>
			@endif
		</div>
	</div>
	@endsection	