@extends('default.layout')
@section('content')

<div class="row">
	<div class="row">
		<div class="col s12 blue">
			<h3 class="center-align white-text">Fluxo de Caixa</h3>
		</div>
	</div>

	<div class="row">
		<form method="get" class="col s12" action="/fluxoCaixa/filtro">
			<div class="row">

				<div class="col s3 input-field">
					<input value="{{{ isset($data_inicial) ? $data_inicial : '' }}}" type="text" class="datepicker" name="data_inicial">
					<label>Data Inicial</label>
				</div>
				<div class="col s3 input-field">
					<input value="{{{ isset($data_final) ? $data_final : '' }}}" type="text" class="datepicker" name="data_final">
					<label>Data Final</label>
				</div>
				<div class="col s2">
					<button type="submit" class="btn-large black">
						<i class="material-icons">search</i>
					</button>
				</div>

			</div>
		</form>
	</div>

	<div class="row">
		<div class="card">
			<div class="row">
				<div class="card">
					<div class="row">
						<div class="">
							<div class="col s2 orange">
								<h5 class="white-text">Data</h5>
							</div>
							<div class="col s2 green">
								<h5 class="white-text">Vendas</h5>
							</div>
							<div class="col s2 blue">
								<h5 class="white-text">Contas/Receber</h5>
							</div>
							<div class="col s2 orange lighten-2">
								<h5 class="white-text">Conta Credito</h5>
							</div>
							<div class="col s2 red">
								<h5 class="white-text">Contas a Pagar</h5>
							</div>
							<div class="col s2 grey">
								<h5 class="white-text">Resultado</h5>
							</div>
						</div>
					</div>
				</div>

				<?php  
				$totalVenda = 0;
				$totalContaReceber = 0;
				$totalContaPagar = 0;
				$totalCredito = 0;
				$totalResultado = 0; 
				?>
				@foreach($fluxo as $f)

				<div class="card">
					<div class="row">
						
						<div class="col s12">
							<div class="col s2">
								<p>{{$f['data']}}</p>
							</div>
							<div class="col s2">
								<label>Vendas: R$ {{number_format($f['venda'], 2, ',', '.')}}</label><br>
								<label>Frente de caixa: R$ {{number_format($f['venda_caixa'], 2, ',', '.')}}</label><br>
								<h5>Total R$ {{number_format($f['venda']+$f['venda_caixa'], 2, ',', '.')}}</h5>
							</div>
							<div class="col s2">
								<h5> R$ {{number_format($f['conta_receber'], 2, ',', '.')}}</h5>
							</div>
							<div class="col s2">
								<h5> R$ {{number_format($f['credito_venda'], 2, ',', '.')}}</h5>
							</div>
							<div class="col s2">
								<h5> R$ {{number_format($f['conta_pagar'], 2, ',', '.')}}</h5>
							</div>
							<?php 
							$resultado = $f['credito_venda']+$f['conta_receber']+$f['venda_caixa']+$f['venda']-$f['conta_pagar'];
							?>
							<div class="col s2">
								@if($resultado > 0)
								<h5 class="green-text"> R$ {{number_format($resultado, 2, ',', '.')}}</h5>
								@elseif($resultado == 0)
								<h5 class="blue-text"> R$ {{number_format($resultado, 2, ',', '.')}}</h5>
								@else
								<h5 class="red-text"> R$ {{number_format($resultado, 2, ',', '.')}}</h5>
								@endif
							</div>

							<?php  
							$totalVenda += $f['venda']+$f['venda_caixa'];
							$totalContaReceber += $f['conta_receber'];
							$totalContaPagar += $f['conta_pagar'];
							$totalCredito += $f['credito_venda'];
							$totalResultado += $resultado; 
							?>
						</div>
					</div>
				</div>
				@endforeach
				<div class="card">
					<div class="row">
						<div class="col s12">
							<div class="col s2">
								<h4>TOTAL</h4>
							</div>
							<div class="col s2">
								<h4>R$ {{number_format($totalVenda, 2, ',', '.')}}</h4>
							</div>
							<div class="col s2">
								<h4>R$ {{number_format($totalContaReceber, 2, ',', '.')}}</h4>
							</div>
							<div class="col s2">
								<h4>R$ {{number_format($totalCredito, 2, ',', '.')}}</h4>
							</div>
							<div class="col s2">
								<h4>R$ {{number_format($totalContaPagar, 2, ',', '.')}}</h4>
							</div>
							<div class="col s2">
								<h4>R$ {{number_format($totalResultado, 2, ',', '.')}}</h4>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
		


		<div class="col s3 offset-s9">
			@if(isset($data_inicial) && isset($data_final))
			<a style="width: 100%;" href="/fluxoCaixa/relatorioFiltro/{{$dataInicial}}/{{$dataFinal}}" type="submit" class="btn-large orange">
				<i class="material-icons left">print</i>
				Relatório
			</a>
			@else
			<a style="width: 100%;" href="/fluxoCaixa/relatorioIndex" type="submit" class="btn-large orange">
				<i class="material-icons left">print</i>
				Relatório
			</a>
			@endif
		</div>
	</div>
</div>

@endsection	