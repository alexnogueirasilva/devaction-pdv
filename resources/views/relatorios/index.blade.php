@extends('default.layout')
@section('content')
<style type="text/css">
.relatorios{
	height: 300px;
}
</style>
<div class="row">

	@if(session()->has('message'))
	<div class="row">
		<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
			<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
		</div>
	</div>
	@endif

	<div class="col s6">
		<div class="card relatorios">
			<div class="row">

				<div class="card-header">
					<h4 class="center-align">Relatório de Vendas</h4>
				</div>
				<div class="divider"></div>
				<div class="card-content">
					<form method="get" action="/relatorios/filtroVendas">
						<div class="row">
							<div class="input-field col s3">
								<input type="text" class="date-input" name="data_inicial">
								<label>Data inicial</label>
							</div>
							<div class="input-field col s3">
								<input type="text" class="date-input" name="data_final">
								<label>Data Final</label>
							</div>
							<div class="input-field col s3">
								<input type="text" placeholder="20" class="date-input" name="total_resultados">
								<label>Nro. Resultados</label>
							</div>

							<div class="input-field col s3">
								<select name="ordem">
									<option value="desc">Maior Valor</option>
									<option value="asc">Menor Valor</option>
									<option value="data">Data</option>
								</select>
								<label>Ordem</label>
							</div>
						</div>

						<div class="input-field col s12">
							<button style="width: 100%" class="btn btn-large green">
								Gerar Relatório<i class="material-icons right">insert_drive_file</i>
							</button>
						</div>

					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="col s6">
		<div class="card relatorios">
			<div class="row">

				<div class="card-header">
					<h4 class="center-align">Relatório de Compras</h4>
				</div>
				<div class="divider"></div>
				<div class="card-content">
					<form method="get" action="/relatorios/filtroCompras">
						<div class="row">
							<div class="input-field col s3">
								<input type="text" class="date-input" name="data_inicial">
								<label>Data inicial</label>
							</div>
							<div class="input-field col s3">
								<input type="text" class="date-input" name="data_final">
								<label>Data Final</label>
							</div>
							<div class="input-field col s3">
								<input type="text" placeholder="20" class="date-input" name="total_resultados">
								<label>Nro. Resultados</label>
							</div>

							<div class="input-field col s3">
								<select name="ordem">
									<option value="desc">Maior Valor</option>
									<option value="asc">Menor Valor</option>
									<option value="data">Data</option>
								</select>
								<label>Ordem</label>
							</div>
						</div>

						<div class="input-field col s12">
							<button style="width: 100%" class="btn btn-large blue">
								Gerar Relatório<i class="material-icons right">insert_drive_file</i>
							</button>
						</div>

					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="col s6">
		<div class="card relatorios">
			<div class="row">

				<div class="card-header">
					<h4 class="center-align">Relatório Vendas de Produtos</h4>
				</div>
				<div class="divider"></div>
				<div class="card-content">
					<form method="get" action="/relatorios/filtroVendaProdutos">
						<div class="row">
							<div class="input-field col s3">
								<input type="text" class="date-input" name="data_inicial">
								<label>Data inicial</label>
							</div>
							<div class="input-field col s3">
								<input type="text" class="date-input" name="data_final">
								<label>Data Final</label>
							</div>
							<div class="input-field col s3">
								<input type="text" placeholder="20" class="date-input" name="total_resultados">
								<label>Nro. Resultados</label>
							</div>

							<div class="input-field col s3">
								<select name="ordem">
									<option value="desc">Mais Vendidos</option>
									<option value="asc">Menos Vendidos</option>

								</select>
								<label>Ordem</label>
							</div>
						</div>

						<div class="input-field col s12">
							<button style="width: 100%" class="btn btn-large blue">
								Gerar Relatório<i class="material-icons right">insert_drive_file</i>
							</button>
						</div>

					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="col s6">
		<div class="card relatorios">
			<div class="row">

				<div class="card-header">
					<h4 class="center-align">Relatório Vendas para Clientes</h4>
				</div>
				<div class="divider"></div>
				<div class="card-content">
					<form method="get" action="/relatorios/filtroVendaClientes">
						<div class="row">
							<div class="input-field col s3">
								<input type="text" class="date-input" name="data_inicial">
								<label>Data inicial</label>
							</div>
							<div class="input-field col s3">
								<input type="text" class="date-input" name="data_final">
								<label>Data Final</label>
							</div>
							<div class="input-field col s3">
								<input type="text" placeholder="20" class="date-input" name="total_resultados">
								<label>Nro. Resultados</label>
							</div>

							<div class="input-field col s3">
								<select name="ordem">
									<option value="desc">Mais Vendas</option>
									<option value="asc">Menos Vendas</option>

								</select>
								<label>Ordem</label>
							</div>
						</div>

						<div class="input-field col s12">
							<button style="width: 100%" class="btn btn-large orange">
								Gerar Relatório<i class="material-icons right">insert_drive_file</i>
							</button>
						</div>

					</form>
				</div>
			</div>
		</div>
	</div>

</div>

@endsection	