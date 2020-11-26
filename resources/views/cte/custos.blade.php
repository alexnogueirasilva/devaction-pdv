@extends('default.layout')
@section('content')

<div class="row">
	@if(session()->has('message'))
	<div class="row">
		<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
			<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
		</div>
	</div>
	@endif
	<div class="col s6">
		<div class="card">
			<div class="card-content">
				<h5 class="center-align red-text">DESPESAS</h5>

				<form method="post" action="/cte/saveDespesa">
					@csrf
					<input type="hidden" name="cte_id" value="{{$cte->id}}">
					<div class="row">
						<div class="col s12 input-field">
							<input type="text" name="descricao" id="descricao" value="">
							<label for="descricao">Descrição</label>
						</div>
					</div>

					<div class="row">
						<div class="col s6 input-field">
							<select name="categoria_id">
								@foreach($categorias as $c)
								<option value="{{$c->id}}">{{$c->nome}}</option>
								@endforeach
							</select>
							<label for="descricao">Categoria</label>
						</div>

						<div class="col s6 input-field">
							<input type="text" name="valor" id="valor" value="">
							<label for="valor">Valor</label>
						</div>
					</div>

					<div class="row">
						
						<button style="width: 100%;" type="submit" class="btn-large red">Salvar</button>
					</div>

				</form>
				<br>
				<h4 class="center-align">Despesas Salvas</h4>
				<table class="striped">
					<thead>
						<tr>
							<th>#</th>
							<th>Descrição</th>
							<th>Categoria</th>
							<th>Valor</th>
							<th>Data</th>
							<th>Ações</th>
						</tr>
					</thead>
					<tbody>
						@foreach($cte->despesas as $d)
						<tr>
							<td>{{$d->id}}</td>
							<td>{{$d->descricao}}</td>
							<td>{{$d->categoria->nome}}</td>
							<td>{{number_format($d->valor)}}</td>
							<td>{{ \Carbon\Carbon::parse($d->data_registro)->format('d/m/Y H:i:s')}}</td>
							<td>
								<a href="/cte/deleteDespesa/{{$d->id}}">
									<i class="material-icons red-text">delete</i>
								</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				<div class="row">
					<h3>Total: R$ {{number_format($cte->somaDespesa(), 2)}}</h3>
				</div>
			</div>
		</div>
	</div>

	<div class="col s6">
		<div class="card">
			<div class="card-content">
				<h5 class="center-align green-text">RECEITAS</h5>
				<form method="post" action="/cte/saveReceita">
					@csrf
					<input type="hidden" name="cte_id" value="{{$cte->id}}">
					<div class="row">
						<div class="col s12 input-field">
							<input type="text" name="descricao" id="descricao" value="">
							<label for="descricao">Descrição</label>
						</div>
					</div>

					<div class="row">


						<div class="col s6 input-field">
							<input type="text" name="valor" id="valor_receita" value="">
							<label for="valor">Valor</label>
						</div>
					</div>

					<div class="row">
						
						<button style="width: 100%;" type="submit" class="btn-large green accent-3">Salvar</button>
					</div>

				</form>
				<br>
				<h4 class="center-align">Receitas Salvas</h4>
				<table class="striped">
					<thead>
						<tr>
							<th>#</th>
							<th>Descrição</th>
							<th>Valor</th>
							<th>Data</th>
							<th>Ações</th>

						</tr>
					</thead>
					<tbody>
						@foreach($cte->receitas as $r)
						<tr>
							<td>{{$r->id}}</td>
							<td>{{$r->descricao}}</td>
							<td>{{number_format($r->valor)}}</td>
							<td>{{ \Carbon\Carbon::parse($r->data_registro)->format('d/m/Y H:i:s')}}</td>
							<td>
								<a href="/cte/deleteReceita/{{$r->id}}">
									<i class="material-icons red-text">delete</i>
								</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				<div class="row">
					<h3>Total: R$ {{number_format($cte->somaReceita(), 2)}}</h3>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col s12">
			<h2>Saldo: R$ 
				<strong class=" @if($cte->somaReceita()>$cte->somaDespesa()) green-text
					@elseif($cte->somaReceita()==$cte->somaDespesa()) blue-text
					@else red-text @endif">{{number_format($cte->somaReceita()-$cte->somaDespesa(), 2)}}</strong>
			</h2>
		</div>
	</div>
</div>
@endsection	