@extends('default.layout')
@section('content')
<div class="row">
	<div class="col s12">
		<h4>Editar Cliente</h4>
		<form method="post" action="/clientesDelivery/update" enctype="multipart/form-data">
			<input type="hidden" name="id" value="{{$cliente->id}}">

			<section class="section-1">
				<div class="row">
					<div class="input-field col s7">
						<input value="{{$cliente->nome}}" id="nome" name="nome" type="text" class="validate">
						<label for="nome">Nome</label>

						@if($errors->has('nome'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('nome') }}</span>
						</div>
						@endif
					</div>
				</div>
				<div class="row">
					<div class="input-field col s7">
						<input value="{{$cliente->sobre_nome}}" id="sobre_nome" name="sobre_nome" type="text" class="validate">
						<label for="sobre_nome">Sobrenome</label>

						@if($errors->has('sobre_nome'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('sobre_nome') }}</span>
						</div>
						@endif
					</div>
				</div>
				<div class="row">
					<div class="input-field col s7">
						<input value="{{$cliente->email}}" id="email" name="email" type="text" class="validate">
						<label for="email">Email</label>

						@if($errors->has('email'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('email') }}</span>
						</div>
						@endif
					</div>
				</div>

				<div class="row">
					<div class="input-field col s7">
						<input value="{{$cliente->celular}}" id="celular" name="celular" type="text" class="validate">
						<label for="celular">Celular</label>

						@if($errors->has('celular'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('celular') }}</span>
						</div>
						@endif
					</div>
				</div>
			</section>

			<br>
			<div class="row">
				<a class="btn-large red lighten-2" href="/clientesDelivery">Cancelar</a>
				<input type="submit" value="Salvar" class="btn-large green accent-3">
			</div>
			@csrf
		</form>
	</div>
</div>
@endsection