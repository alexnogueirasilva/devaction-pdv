@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">

		<div class="row">
			<div class="container">
				<div class="row">

					<div class="row">
						<div class="col s6 offset-s3">
							<a href="/vendas/nova" class="btn-large green" style="width: 100%;">
								<i class="material-icons left">add</i>
								Nova Venda
							</a>
						</div>
					</div>
					<div class="row">
						<div class="col s6 offset-s3">
							<a href="/vendas/lista" class="btn-large orange" style="width: 100%">
								<i class="material-icons left">list</i>
								Lista de Vendas
							</a>
						</div>
					</div>
				</div>

			</div>
		</div>


	</div>
</div>
@endsection	