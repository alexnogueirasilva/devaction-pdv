@extends('default.layout')
@section('content')
<div class="row">
	<div class="col s12">
		<h4>{{{ isset($conta) ? "Editar": "Cadastrar" }}} Conta a Pagar</h4>

		@if(session()->has('message'))
		<div class="row">
			<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
				<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
			</div>
		</div>
		@endif

		<form method="post" action="{{{ isset($conta) ? '/contasPagar/update': '/contasPagar/save' }}}" enctype="multipart/form-data">
			<input type="hidden" name="id" value="{{{ isset($conta->id) ? $conta->id : 0 }}}">

			<section class="section-2">

				<div class="row">
					<div class="col s6 input-field">
						<input value="{{{ isset($conta->referencia) ? $conta->referencia : old('referencia') }}}" type="text" name="referencia">
						<label>Referencia</label>
						@if($errors->has('referencia'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('referencia') }}</span>
						</div>
						@endif
					</div>
				</div>

				<div class="row">
					<div class="input-field col s3">

						<select name="categoria_id">
							@foreach($categorias as $cat)
							<option value="{{$cat->id}}" @isset($conta)
								@if($cat->id == $conta->categoria_id)
								selected
								@endif
								@endisset >{{$cat->nome}}</option>

								@endforeach
							</select>
							<label>Categoria</label>
							@if($errors->has('categoria_id'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('categoria_id') }}</span>
							</div>
							@endif

						</div>
						<div class="col s3 input-field">
							<input value="{{{ isset($conta->data_vencimento) ? \Carbon\Carbon::parse($conta->data_vencimento)->format('d/m/Y') : old('data_vencimento') }}}" type="text" name="vencimento" class="datepicker">
							<label>Data de Vencimento</label>
							@if($errors->has('vencimento'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('vencimento') }}</span>
							</div>
							@endif
						</div>
					</div>



					<div class="row">
						
						<div class="col s3 input-field">
							<input value="{{{ isset($conta->valor_integral) ? $conta->valor_integral : old('valor_integral') }}}" type="text" id="valor" name="valor" class="text">
							<label>Valor</label>
							@if($errors->has('valor'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('valor') }}</span>
							</div>
							@endif
						</div>
						@if(!isset($conta))
						<div class="col s3"><br>
							<p>
								<input 
								@if(isset($conta) && $conta->status) checked 
								@endif type="checkbox" id="pago" name="status" />
								<label for="pago">Conta Paga</label>
							</p>
						</div>
						@endif
						
					</div>

					@if(!isset($conta))
					<div class="row">
						
						<div class="col s3 input-field">
							<input placeholder="mm/aa" type="text" id="recorrencia" name="recorrencia" class="text">
							<label>Salvar até este mês (opcional) </label>
						</div><br>
						<p class="red-text"> *Este campo deve ser preenchido se ouver recorrencia para este registro
						</p>
					</div>
					@endif

					<input type="hidden" name="_token" value="{{ csrf_token() }}">

				</section>


				<br>
				<div class="row">
					<a class="btn-large red lighten-2" href="/contasPagar">Cancelar</a>
					<input type="submit" value="Salvar" class="btn-large green accent-3">
				</div>
			</form>
		</div>
	</div>
	@endsection