@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">


		<h4>Lista de Produtos de Delivery</h4>

		@if(session()->has('message'))
		<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
			<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
		</div>
		@endif

		<div class="row"></div>
		<div class="row">
			<a href="/deliveryProduto/new" class="btn green accent-3">
				<i class="material-icons left">add</i>	
				Novo Produto de Delivery	
			</a>
		</div>



		<div class="row">
			<div class="col s12">
				<label>Numero de registros: {{count($produtos)}}</label>					
			</div>
			<table class="col s12">
				<thead>
					<tr>
						<th>Código</th>
						<th>Nome</th>
						<th>Categoria</th>
						<th>Valor</th>
						<th>Descrição</th>
						<th>Ingredientes</th>
						<th>Limite Diário</th>
						<th>Total de Imagens</th>
						<th>Destaque</th>
						<th>Ativo</th>
						<th>Ações</th>
					</tr>
				</thead>

				<tbody>
					@foreach($produtos as $p)
					<tr>
						<th>{{ $p->id }}</th>
						<th>{{ $p->produto->nome }}</th>
						<th>{{ $p->categoria->nome }}</th>
						@if(count($p->pizza) > 0)
						<th>
							@foreach($p->pizza as $key => $pz)
							{{$pz->valor}} {{$key < count($p->pizza)-1 ? '|' : ''}}
							@endforeach
						</th>
						@else
						<th>{{ $p->valor }}</th>
						@endif
						<th>
							<a class="btn brown lighten-2 tooltipped" data-position="bottom" data-delay="50" data-tooltip="{{$p->descricao}}"
								@if(empty($p->descricao))
								disabled
								@endif
								>
								<i class="material-icons">message</i>

							</a>
						</th>

						<th>
							<a class="btn brown lighten-2 tooltipped" data-position="bottom" data-delay="50" data-tooltip="{{$p->ingredientes}}"
								@if(empty($p->ingredientes))
								disabled
								@endif
								>
								<i class="material-icons">message</i>

							</a>
						</th>
						<th>{{$p->limite_diario}}</th>
						<th>{{count($p->galeria)}}</th>
						<th>
							<div class="switch">
								<label class="">
									
									<input onclick="alterarDestaque({{$p->id}})" @if($p->destaque) checked @endif value="true" name="status" class="red-text" type="checkbox">
									<span class="lever"></span>
									
								</label>
							</div>
						</th>
						<th>
							<div class="switch">
								<label class="">

									<input onclick="alterarStatus({{$p->id}})" @if($p->status) checked @endif value="true" name="status" class="red-text" type="checkbox">
									<span class="lever"></span>
									
								</label>
							</div>
						</th>
						<th>
							<a href="/deliveryProduto/edit/{{ $p->id }}">
								<i class="material-icons left">edit</i>					
							</a>

							<a href="/deliveryProduto/galeria/{{ $p->id }}">
								<i class="material-icons left orange-text">photo</i>					
							</a>
							<a onclick = "if (! confirm('Deseja excluir este registro?')) { return false; }" href="/deliveryProduto/delete/{{ $p->id }}">
								<i class="material-icons left red-text">delete</i>					
							</a>

							<a href="/deliveryProduto/push/{{$p->id}}">
								<i class="material-icons">notifications_active</i>
							</a>

							@if($p->produto->composto)
							<a href="/produtos/receita/{{ $p->produto->id }}">
								<i class="material-icons left green-text">import_contacts</i>					
							</a>
							@endif
						</th>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		@if(isset($links))
		<ul class="pagination center-align">
			<li class="waves-effect">{{$produtos->links()}}</li>
		</ul>
		@endif
	</div>

</div>
@endsection	