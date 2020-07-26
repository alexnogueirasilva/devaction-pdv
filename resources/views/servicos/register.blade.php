@extends('default.layout')
@section('content')
	<div class="row">
		<div class="col s12">
			<h4>{{{ isset($servico) ? "Editar": "Cadastrar" }}} Serviço</h4>
			<form method="post" action="{{{ isset($servico) ? '/servicos/update': '/servicos/save' }}}" enctype="multipart/form-data">
				<input type="hidden" name="id" value="{{{ isset($servico->id) ? $servico->id : 0 }}}">
				
				<div class="row">
					<div class="input-field col s6">
			          <input value="{{{ isset($servico->nome) ? $servico->nome : old('nome') }}}" id="nome" name="nome" type="text" class="validate">
			          <label for="nome">Nome</label>
			          
			          @if($errors->has('nome'))
			          <div class="center-align red lighten-2">
			          	<span class="white-text">{{ $errors->first('nome') }}</span>
			          </div>
			          @endif

			        </div>
				</div>

				<div class="row">
					<div class="input-field col s4">
			          <input value="{{{ isset($servico->valor) ? $servico->valor : old('valor') }}}" id="valor" name="valor" type="text" class="validate">
			          <label for="valor">Valor</label>
			          
			          @if($errors->has('valor'))
			          <div class="center-align red lighten-2">
			          	<span class="white-text">{{ $errors->first('valor') }}</span>
			          </div>
			          @endif

			        </div>
				</div>

				<div class="row">
					<div class="col s4 input-field">
						<select name="categoria_id">
							@foreach($categorias as $c)
								<option value="{{$c->id}}">{{$c->nome}}</option>
							@endforeach
						</select>
						<label>Categoria</label>
					</div>
				</div>



				<div class="row">
					
					<div class="col s4 input-field">
						<select name="unidade_cobranca">
							<option @isset($servico) @if($servico->unidade_cobranca == 'UN') selected @endif @endisset value="UN">UN</option>
							<option @isset($servico) @if($servico->unidade_cobranca == 'HR') selected @endif @endisset  value="HR">HR</option>
							<option @isset($servico) @if($servico->unidade_cobranca == 'MIN') selected @endif @endisset  value="MIN">MIN</option>
						</select>
						<label>Unidade de cobrança</label>
					</div>
				</div>

				<input type="hidden" name="_token" value="{{ csrf_token() }}">

				<br>
				<div class="row">
					<a class="btn-large red lighten-2" href="/servicos">Cancelar</a>
					<input type="submit" value="Salvar" class="btn-large green accent-3">
				</div>
			</form>
		</div>
	</div>
@endsection