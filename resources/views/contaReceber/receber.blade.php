@extends('default.layout')
@section('content')
<div class="row">
	<div class="col s12">
		<h4>Receber Conta</h4>

		<form method="post" action="/contasReceber/receber" enctype="multipart/form-data">
			<input type="hidden" name="id" value="{{$conta->id}}">

			<section class="section-1">

				<div class="row">
					<div class="col s12">
						@if($conta->venda_id != null)
						<h5>Cliente: <strong>{{$conta->venda->cliente->razao_social}}</strong></h5>
						@endif

						<h5>Data de registro: <strong>{{ \Carbon\Carbon::parse($conta->data_registro)->format('d/m/Y')}}</strong></h5>
						<h5>Data de vencimento: <strong>{{ \Carbon\Carbon::parse($conta->data_vencimento)->format('d/m/Y')}}</strong></h5>
						<h5>Valor: <strong>{{ number_format($conta->valor_integral, 2, ',', '.') }}</strong></h5>
						<h5>Categoria: <strong>{{$conta->categoria->nome}}</strong></h5>
						<h5>Referencia: <strong>{{$conta->referencia}}</strong></h5>
					</div>
				</div>

				<div class="row">

					<div class="col s3 input-field">
						<input type="text" id="valor" name="valor" class="text">
						<label>Valor Recebido</label>
						@if($errors->has('valor'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('valor') }}</span>
						</div>
						@endif
					</div>


				</div>

				<input type="hidden" name="_token" value="{{ csrf_token() }}">

			</section>


			<br>
			<div class="row">
				<a class="btn-large red lighten-2" href="/contasReceber">Cancelar</a>
				<input type="submit" value="Salvar" class="btn-large green accent-3">
			</div>
		</form>
	</div>
</div>
@endsection