@extends('default.layout')
@section('content')
<div class="row">
	<div class="col s12">
		<h4>{{{ isset($produto) ? "Editar": "Cadastrar" }}} Produto de Delivery</h4>
		<form method="post" action="{{{ isset($produto) ? '/deliveryProduto/update': '/deliveryProduto/save' }}}" enctype="multipart/form-data">
			<input type="hidden" name="id" value="{{{ isset($produto->id) ? $produto->id : 0 }}}">

			<p class="red-text">O produto de delivery depende do produto principal, isso é necessário para baixa de estoque</p>
			<div class="row">
				<div class="input-field col s6">
					<i class="material-icons prefix">inbox</i>
					<input @isset($produto) disabled @endisset autocomplete="off" type="text" name="produto" id="autocomplete-produto" value="{{{ isset($produto) ? $produto->produto->nome : old('produto') }}}" class="autocomplete-produto">
					<label for="autocomplete-produto">Produto de referência</label>
					@if($errors->has('produto'))
					<div class="center-align red lighten-2">
						<span class="white-text">{{ $errors->first('produto') }}</span>
					</div>
					@endif
				</div>

				<div class="col s3 input-field">
					<select name="categoria_id" id="categoria-select">
						@foreach($categorias as $c)
						<option

						
						@if($c->id == old('categoria_id'))
						selected=""
						@endif


						@isset($produto)
						@if($c->id == $produto->categoria_id)
						selected=""
						@endif
						@endisset
						value="{{$c->id}}">{{$c->nome}}</option>
						@endforeach
					</select>
					<label>Categoria</label>

				</div>
			</div>


			<div class="row" id="produto-comum">
				<div class="input-field col s2">
					<input type="text" id="valor" value="{{{ isset($produto->valor) ? $produto->valor : old('valor') }}}" name="valor">
					<label>Valor de Venda</label>

					@if($errors->has('valor'))
					<div class="center-align red lighten-2">
						<span class="white-text">{{ $errors->first('valor') }}</span>
					</div>
					@endif
				</div>

				<div class="input-field col s2">
					<input type="text" id="valor_anterior" value="{{{ isset($produto->valor_anterior) ? $produto->valor_anterior : old('valor_anterior') }}}" name="valor_anterior">
					<label>Valor Anterior</label>

					@if($errors->has('valor_anterior'))
					<div class="center-align red lighten-2">
						<span class="white-text">{{ $errors->first('valor_anterior') }}</span>
					</div>
					@endif
				</div>

			</div>


			<?php $controleEdit = []; ?>
			
			<div class="row" id="produto-pizza" style="display: none">

				@foreach($tamanhos as $key => $t)

				<div class="input-field col s2">
					@if(isset($produto) && count($produto->pizza) > 0)

					@foreach($produto->pizza as $pp)

						@if($pp->tamanho_id == $t->id)
						<input type="text" class="valor_pizza"  value="{{{ isset($pp->valor) ? $pp->valor : old('valor_{{$t->nome}}') }}}" name="valor_{{$t->nome}}">
						@else

							@if(!$pp->tamanhoNaoCadastrado($t->id, $pp->produto) && !in_array($t->id, $controleEdit))
							<input type="text" class="valor_pizza" 
							value="" name="valor_{{$t->nome}}">

							<?php array_push($controleEdit, $t->id); ?>
							
							@endif
						@endif
					@endforeach
					
					@else
					<input type="text" class="valor_pizza" value="{{{ isset($pp->valor) ? $pp->valor : old('valor_'.$t->nome) }}}" name="valor_{{$t->nome}}">
					@endif

					<label>Valor {{$t->nome}}</label>

					@if($errors->has('valor_'.$t->nome))
					<div class="center-align red lighten-2">
						<span class="white-text">{{ $errors->first('valor_'.$t->nome) }}</span>
					</div>
					@endif
				</div>

				@endforeach

			</div>

			<div class="row">
				<div class="input-field col s10">
					<textarea name="descricao" id="descricao" class="materialize-textarea">{{{ isset($produto->descricao) ? $produto->descricao : old('descricao') }}}</textarea>
					<label for="descricao">Descrição</label>

					@if($errors->has('descricao'))
					<div class="center-align red lighten-2">
						<span class="white-text">{{ $errors->first('descricao') }}</span>
					</div>
					@endif
				</div>

			</div>

			<div class="row">
				<div class="input-field col s10">
					<textarea name="ingredientes" id="ingredientes" class="materialize-textarea">{{{ isset($produto->ingredientes) ? $produto->ingredientes : old('ingredientes') }}}</textarea>
					<label for="ingredientes">Ingredientes</label>

					@if($errors->has('ingredientes'))
					<div class="center-align red lighten-2">
						<span class="white-text">{{ $errors->first('ingredientes') }}</span>
					</div>
					@endif
				</div>

			</div>

			<div class="row">
				<div class="input-field col s3">
					<input type="text" value="{{{ isset($produto->limite_diario) ? $produto->limite_diario : old('valor') }}}" id="limite_diario" name="limite_diario">
					<label>Limite Diário de venda</label>

					@if($errors->has('limite_diario'))
					<div class="center-align red lighten-2">
						<span class="white-text">{{ $errors->first('limite_diario') }}</span>
					</div>
					@endif
				</div>
			</div>

			<div class="row">
				<div class="col s2">
					<label>Destaque</label>

					<div class="switch">
						<label class="">
							Não
							<input @if(isset($produto->destaque) && $produto->destaque) checked @endisset value="true" name="destaque" class="red-text" type="checkbox">
							<span class="lever"></span>
							Sim
						</label>
					</div>
				</div>

				<div class="col s2">
					<label>Status</label>

					<div class="switch">
						<label class="">
							Desativado
							<input @if(isset($produto->status) && $produto->status) checked @endisset value="true" name="status" class="red-text" type="checkbox">
							<span class="lever"></span>
							Ativo
						</label>
					</div>
				</div>
			</div>

			<input type="hidden" name="_token" value="{{ csrf_token() }}">


			<br>
			<div class="row">
				<a class="btn-large red lighten-2" href="/deliveryProduto">Cancelar</a>
				<input type="submit" value="Salvar" class="btn-large green accent-3">
			</div>
		</form>
	</div>
</div>
@endsection