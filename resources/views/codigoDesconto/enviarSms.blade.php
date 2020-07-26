@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">
		<div class="card">
			<div class="card-content">

				@if($cupom->sms)
				<h4 class="red-text center-align">SMS JÁ ENVIADO</h4>
				@endif
				<h3>Envio de SMS</h3>
				<h4>Créditos da API: {{$totalSms}}</h4>
				<h6>Cupom: <strong>{{$cupom->id}}</strong></h6>
				<h6>Código: <strong>{{$cupom->codigo}}</strong></h6>
				<h6>Cliente: 
					@if($cupom->cliente_id != null)
					<strong>{{$cupom->cliente->nome}}</strong>
					@else
					<strong>Envio para todos ativos <strong>{{$cupom->totalDeClientesAtivosCad()}}</strong> cliente(s) ativo(s)</strong>
					@endif
				</h6>
				@if($cupom->cliente_id != null)
				<p class="red-text">Este SMS é para usuário unico vocé gastará o total de <strong class="black-text">1</strong> crédito de sua api</p>
				@else

				@endif


				<div class="row">
					<br>
					<form class="col s12" method="post" action="/codigoDesconto/sms">
						@csrf
						<input type="hidden" value="{{$cupom->id}}" name="cupom_id">
						<div class="row">
							<div class="input-field col s12">
								<textarea data-length="80" id="textarea1" name="mensagem" class="materialize-textarea">Utilize {{$cupom->codigo}} para desconto de @if($cupom->tipo == 'valor')R$@endif{{number_format($cupom->valor, 2)}}@if($cupom->tipo == 'percentual')%@endif, especial para você@if($cupom->cliente_id != null) {{$cupom->cliente->nome}}@endif!
								</textarea>
								<label for="textarea1">Mensagem</label>
							</div>
						</div>

						<button class="btn">
							<i class="material-icons left">send</i>
							Enviar
						</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection	