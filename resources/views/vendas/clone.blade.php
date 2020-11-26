@extends('default.layout')
@section('content')


<div class="row" id="anime" style="display: none">
	<div class="col s8 offset-s2">
		<lottie-player 
		src="/anime/success.json"  background="transparent"  speed="0.8"  style="width: 100%; height: 300px;"    autoplay >
	</lottie-player>
</div>
</div>
<form method="post" action="/vendas/clone">
	@csrf
	<div class="row" id="content" style="display: block">
		<div class="col s12">
			<div class="card">
				<div class="row">
					<div class="col s12">
						<h5 class="grey-text">Informe o Cliente</h5>
						<div class="row">
							<div class="col s4">
								<h6>Ultima NF-e: <strong>{{$lastNF}}</strong></h6>

							</div>
							<div class="col s4">

								@if($config->ambiente == 2)
								<h6>Ambiente: <strong class="blue-text">Homologação</strong></h6>
								@else
								<h6>Ambiente: <strong class="green-text">Produção</strong></h6>
								@endif
							</div>
						</div>
					</div>

					<input type="hidden" name="venda_id" value="{{$venda->id}}">

					<div class="row">
						<div class="col s12">
							<div class="input-field col s6">
								<i class="material-icons prefix">person</i>
								<input autocomplete="off" value="{{$venda->cliente->id}} - {{$venda->cliente->razao_social}}" type="text" name="cliente" id="autocomplete-cliente" class="autocomplete-cliente">
								<label for="autocomplete-cliente">Cliente</label>
								@if($errors->has('cliente'))
								<div class="center-align red lighten-2">
									<span class="white-text">{{ $errors->first('cliente') }}</span>
								</div>
								@endif
							</div>

							<div class="col s6">
								<button type="submit" class="btn-large">Salvar</button>
							</div>
						</div>
					</div>
					<div class="row" id="cliente" style="display: none">
						<div class="col s12">
							<h4 class="center-align">CLIENTE SELECIONADO</h4>
							<div class="col s6">
								<h5>Razão Social: <strong id="razao_social" class="red-text">--</strong></h5>
								<h5>Nome Fantasia: <strong id="nome_fantasia" class="red-text">--</strong></h5>
								<h5>Logradouro: <strong id="logradouro" class="red-text">--</strong></h5>
								<h5>Numero: <strong id="numero" class="red-text">--</strong></h5>
								<h5>Limite: <strong id="limite" class="red-text"></strong></h5>
							</div>
							<div class="col s6">
								<h5>CPF/CNPJ: <strong id="cnpj" class="red-text">--</strong></h5>
								<h5>RG/IE: <strong id="ie" class="red-text">--</strong></h5>
								<h5>Fone: <strong id="fone" class="red-text">--</strong></h5>
								<h5>Cidade: <strong id="cidade" class="red-text">--</strong></h5>

							</div>
						</div>

					</div>

				</div>
			</div>





		</div>
	</div>
</form>


@endsection