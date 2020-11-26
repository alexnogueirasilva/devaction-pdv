@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">

		<h4 class="center-align">Nova Consulta</h4>
		<br><br>
		<div class="row" id="preloader1" style="display: block">
			<div class="col s12 center-align">
				<div class="preloader-wrapper active">
					<div class="spinner-layer spinner--only">
						<div class="circle-clipper left">
							<div class="circle"></div>
						</div><div class="gap-patch">
							<div class="circle"></div>
						</div><div class="circle-clipper right">
							<div class="circle"></div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<p id="aguarde" class="center-align">Consultado novos documentos, aguarde ...</p>
		<p id="sem-resultado" style="display: none" class="center-align red-text">Nenhum novo resultado...</p>

		<div class="row" id="table" style="display: none">
			<p class="center-align red-text">Novos resultados</p>
			<table>
				<thead>
					<tr>
						<th>NOME</th>
						<th>CNPJ</th>
						<th>VALOR</th>
						<th>CHAVE</th>
					</tr>
				</thead>
				<tbody>
					
				</tbody>
			</table>
		</div>
	</div>
</div>

@endsection	