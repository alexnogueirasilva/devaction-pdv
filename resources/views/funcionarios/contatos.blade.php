@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">

		<h4>Lista de contatos de <strong>{{$funcionario->nome}}</strong></h4>

		@if(session()->has('message'))
		<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
			<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
		</div>
		@endif
		<div class="row"></div>

		<div class="row">
			
			<form method="post" action="/funcionarios/saveContato">
				<input type="hidden" value="{{$funcionario->id}}" name="funcionario_id">
				<input type="hidden" name="id" value="{{{ isset($contato->id) ? $contato->id : 0 }}}">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">

				<div class="row">
					<div class="input-field col  s4">
						<input value="{{{ isset($contato->id) ? $contato->nome : old('nome') }}}" id="nome" name="nome" type="text" class="validate">
						<label for="nome">Nome</label>

						@if($errors->has('nome'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('nome') }}</span>
						</div>
						@endif

					</div>

					<div class="input-field col s3">
						<input value="{{{ isset($contato->id) ? $contato->telefone : old('telefone') }}}" id="telefone" name="telefone" type="text" class="validate">
						<label for="telefone">Telefone</label>

						@if($errors->has('telefone'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('telefone') }}</span>
						</div>
						@endif

					</div>
					<div class="col s21">
						<a href="/funcionarios/contatos/{{$funcionario->id}}" class="btn yellow">Limpar</a>
					</div>
					<div class="col s2">
						<input class="btn green accent-3" type="submit"/ value="Salvar">
					</div>
					
				</div>

			</form>
		</div>


		<div class="row">
			<div class="col s12 m12 l12">
				<label>Numero de registros: {{count($funcionario->contatos)}}</label>					
			</div>
			<table class="">
				<thead>
					<tr>
						<th>Nome</th>
						<th>Telefone</th>
						<th>Ações</th>
					</tr>
				</thead>

				<tbody>
					@foreach($funcionario->contatos as $c)
					<tr>
						<th>{{ $c->nome }}</th>
						<th>{{ $c->telefone }}</th>

						<th>
							<a href="/funcionarios/editContato/{{ $c->id }}">
								<i class="material-icons left">edit</i>					
							</a>
							<a onclick = "if (! confirm('Deseja excluir este registro?')) { return false; }" href="/funcionarios/deleteContato/{{ $c->id }}">
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