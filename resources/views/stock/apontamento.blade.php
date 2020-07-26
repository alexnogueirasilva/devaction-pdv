@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">
		
		<h4>Apontamento de produção</h4>

		@if(session()->has('message'))
		<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
			<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
		</div><br>
		@endif

		<div class="container"><br>
			<h5>Ultimos 5 Apontamentos</h5>

			<table class="striped">
				<thead>
					<tr>
						<th>Produto</th>
						<th>Quantidade</th>
						<th>Data de Registro</th>
						<th>Usuario</th>
						<th>Ações</th>
					</tr>
				</thead>
				<tbody>
					@foreach($apontamentos as $a)
					<tr>
						<td>{{$a->produto->nome}}</td>
						<td>{{$a->quantidade}}</td>
						<td>{{ \Carbon\Carbon::parse($a->data_registro)->format('d/m/Y H:i:s')}}</td>
						<td>{{$a->usuario->nome}}</td>
						<td>
							<a onclick = "if (! confirm('Deseja excluir este registro? O estoque de produtos será alterado!')) { return false; }" title="Remover Apontamento" 
							href="/estoque/deleteApontamento/{{$a->id}}">
							<i class="material-icons red-text">delete</i>
						</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		<div class="row"><br>
			<div class="col s4 offset-s4">
				<a style="width: 100%;" href="/estoque/todosApontamentos" class="btn black">
					<i class="material-icons left">list</i>
				Ver todos</a>
			</div>
		</div>
		
	</div>
	<br>
	<div class="card col s12">
		<div class="row">


			<input type="hidden" id="composto" value="true" name="">
			<br>
			<h5>Novo Apontamento</h5>
			<form class="" method="post" action="/estoque/saveApontamento">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<div class="row">
					<div class="input-field col s6">
						<input autocomplete="off" type="text" name="produto" id="autocomplete-produto" class="autocomplete-produto">
						<label for="autocomplete-produto">Produto</label>
						@if($errors->has('produto'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('produto') }}</span>
						</div>
						@endif
					</div>
					<br>
					<p class="red-text">*Somente os produtos que estão cadastrados como compostos</p>
				</div>
				<div class="row">
					<div class="input-field col s4">
						<input type="text" value="{{old('quantidade')}}" id="quantidade" name="quantidade">
						<label>Quantidade</label>
						@if($errors->has('quantidade'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('quantidade') }}</span>
						</div>
						@endif
					</div>

					<div class="col s4">
						<button class="btn-large green accent-3" type="submit">Salvar</button>

					</div>
				</div>
			</form>
		</div>
	</div>
</div>
</div>
@endsection	