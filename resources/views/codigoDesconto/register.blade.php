@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">
		<form method="post" action="{{{ isset($codigo) ? '/codigoDesconto/update': '/codigoDesconto/save' }}}">
			@csrf

			
			<h4>{{{ isset($codigo) ? "Editar": "Cadastrar" }}} Código de Descotno</h4>

			<input type="hidden" name="id" value="{{{isset($codigo) ? $codigo->id : 0}}}">
			<div class="row">
				<div class="col s3">
					<div class="input-field">
						<input value="{{{ isset($codigo) ? $codigo->codigo : old('codigo') }}}" type="text" name="codigo" id="codigoPromocional" data-length="50">
						<label class="active" for="codigoPromocional">Código</label>
					</div>
					
					@if($errors->has('codigo'))
					<div class="center-align red lighten-2">
						<span class="white-text">{{ $errors->first('codigo') }}</span>
					</div>
					@endif
				</div>
				<div class="col s1 input-field">
					<a href="#!" class="btn red" id="gerar-codigo" title="gerarCodigo"><i class="material-icons">vpn_key</i></a>
				</div>
			</div>

			<div class="row">

				<div class="col s2">
					<div class="input-field">
						<select name="tipo">
							<option value="valor">Valor R$</option>
							<option value="percentual">Percentual %</option>
						</select>
						<label>Tipo</label>
					</div>
				</div>
				<div class="col s2">
					<div class="input-field">
						<input value="{{{ isset($codigo) ? number_format($codigo->valor, 2) : old('valor') }}}" type="text" name="valor" id="valor" data-length="100">
						<label>Valor</label>
					</div>
					@if($errors->has('valor'))
					<div class="center-align red lighten-2">
						<span class="white-text">{{ $errors->first('valor') }}</span>
					</div>
					@endif
				</div>
				<div class="col s2">
					<p><br>
						<input name="ativo" type="checkbox" @isset($codigo) @if($codigo->ativo) checked @endif @endisset id="test5" />
						<label for="test5">Cupom Ativo</label>
					</p>
				</div>
			</div>

			<div class="row">
				<div class="col s12">
					@isset($codigo)
					@if($codigo->cliente)
					<p class="red-text">Notificaçao para cliente 
						<strong>{{$codigo->cliente->nome}}</strong></p><br>

						@else
						<p class="red-text">Notificaçao para todos os clientes</p><br>
						@endif
						@endisset
					</div>
					@if(!isset($codigo))
					<div class="col s2">
						Todos os Clientes
						<div class="switch">
							<label class="">
								Não
								<input id="todos" name="todos" class="red-text" type="checkbox">
								<span class="lever"></span>
								Sim
							</label>
						</div>
					</div>
					<div class="input-field col s6" id="cliente">
						<i class="material-icons prefix">person</i>
						<input autocomplete="off" type="text" name="cliente" id="autocomplete-cliente" class="autocomplete-cliente">
						<label for="autocomplete-cliente">Cliente do Delivery</label>
						@if($errors->has('cliente'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('cliente') }}</span>
						</div>
						@endif
					</div>
					@endisset
				</div>

				<div class="row">
					<a href="/codigoDesconto" class="btn-large red">Cancelar</a>
					<button type="submit" class="btn-large green accent-3">Salvar</button>
				</div>


			</form>
		</div>
	</div>
	@endsection	