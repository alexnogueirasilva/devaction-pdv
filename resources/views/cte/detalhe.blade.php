@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">

		<div class="row">

			<div class="row">

				<h4>Código: <strong>{{$cte->id}}</strong></h4>
				<h4>Data de registro: <strong>{{ \Carbon\Carbon::parse($cte->data_registro)->format('d/m/Y H:i:s')}}</strong></h4>
				<h4>Valor de transporte: R$ <strong>{{ number_format($cte->valor_transporte, 2, ',', '.') }}</strong></h4>
				<h4>Valor a receber: R$ <strong>{{ number_format($cte->valor_receber, 2, ',', '.') }}</strong></h4>
				<h4>Valor da carga: R$ <strong>{{ number_format($cte->valor_carga, 2, ',', '.') }}</strong></h4>
				<h4>Chave: <strong>{{$cte->chave_nfe}}</strong></h4>


			</div>

			<div class="row">
				<div class="col s6">
					<div class="card">
						<div class="card-content">
							<h4 class="center-align green-text">REMETENTE</h4>

							<h5>Razao Social: <strong>{{$cte->remetente->razao_social}}</strong></h5>
							<h5>CNPJ: <strong>{{$cte->remetente->cpf_cnpj}}</strong></h5>
							<h5>Rua: <strong>{{$cte->remetente->rua}}, {{$cte->remetente->numero}}</strong></h5>
							<h5>Bairro: <strong>{{$cte->remetente->bairro}}</strong></h5>
							<h5>Cidade: <strong>{{$cte->remetente->cidade->nome}}</strong></h5>
						</div>
					</div>
				</div>

				<div class="col s6">
					<div class="card">
						<div class="card-content">
							<h4 class="center-align green-text">DESTINATÁRIO</h4>
							<h5>Razao Social: <strong>{{$cte->destinatario->razao_social}}</strong></h5>
							<h5>CNPJ: <strong>{{$cte->destinatario->cpf_cnpj}}</strong></h5>
							<h5>Rua: <strong>{{$cte->destinatario->rua}}, {{$cte->destinatario->numero}}</strong></h5>
							<h5>Bairro: <strong>{{$cte->destinatario->bairro}}</strong></h5>
							<h5>Cidade: <strong>{{$cte->destinatario->cidade->nome}}</strong></h5>
						</div>
					</div>
				</div>

				<div class="col s6">
					<div class="card">
						<div class="card-content">
							<h4 class="center-align blue-text">VEICULO</h4>

							<h5>Marca: <strong>{{$cte->veiculo->marca}}</strong></h5>
							<h5>Modelo: <strong>{{$cte->veiculo->modelo}}</strong></h5>
							<h5>Placa: <strong>{{$cte->veiculo->placa}}</strong></h5>
							<h5>Cor: <strong>{{$cte->veiculo->cor}}</strong></h5>
							<h5>RNTRC: <strong>{{$cte->veiculo->cor}}</strong></h5>
						</div>
					</div>
				</div>

				<div class="col s6">
					<div class="card">
						<div class="card-content">
							<h4 class="center-align blue-text">TOMADOR</h4>

							<h5>Rua: <strong>{{$cte->logradouro_tomador}}</strong></h5>
							<h5>Numero: <strong> {{$cte->numero_tomador}}</strong></h5>
							<h5>Bairro: <strong>{{$cte->bairro_tomador}}</strong></h5>
							<h5>CEP: <strong>{{$cte->cep_tomador}}</strong></h5>
							<h5>Cidade: <strong>{{$cte->municipio_tomador}}</strong></h5>
							<h5></h5>
						</div>
					</div>
				</div>
			</div>

			<div class="row">
				
			</div>
			<div class="divider"></div>
			<div class="row">
				<div class="col s6">
					<h5 class="cyan-text">Componentes da CT-e</h5>

					<table>
						<thead>
							<tr>
								<th>Nome</th>
								<th>Valor</th>
							</tr>
						</thead>
						<tbody>
							@foreach($cte->componentes as $c)
							<td>{{$c->nome}}</td>
							<td>{{number_format($c->valor, 2)}}</td>
							@endforeach
						</tbody>
					</table>
				</div>

				<div class="col s6">
					<h5 class="cyan-text">Medidas da CT-e</h5>

					<table>
						<thead>
							<tr>
								<th>Tipo</th>
								<th>Quantidade</th>
								<th>Código</th>
							</tr>
						</thead>
						<tbody>
							@foreach($cte->medidas as $m)
							<td>{{$m->tipo_medida}}</td>
							<td>{{$m->quantidade_carga}}</td>
							<td>{{$m->cod_unidade}}</td>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>


			<div class="divider"></div>


		</div>


	</div>
</div>
@endsection	