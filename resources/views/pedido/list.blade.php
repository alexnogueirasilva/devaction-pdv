@extends('default.layout')
@section('content')
<style type="text/css">
h1{
	font-size: 110px;
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
	@if(count($pedidos) > 0)
	<div class="col s12">

		<div class="row">
			<div class="col s4 offset-s4">
				<a class="btn-large modal-trigger green accent-3" style="width: 100%;" href="#modal1">Abrir Comanda</a>
			</div>
		</div>

		<h5 class="green-text">Comandas em verde já finalizadas</h5>
		@foreach($pedidos as $p)
		<div class="col s3">
			<div class="card @if($p->status) green lighten-4 @endif">
				<div class="card-content">

					<h5 class="center-align grey-text">COMANDA</h5>

					<h1 class="center-align">{{$p->comanda}}</h1>

					<h5>Total: <strong>R$ {{number_format($p->somaItems(),2 , ',', '.')}}</strong></h5>
					<h5>Horário Abertura: <strong>{{ \Carbon\Carbon::parse($p->data_registro)->format('H:i')}}</strong></h5>
					<h5>Total de itens: <strong class="red-text">{{count($p->itens)}}</strong></h5>
					<h5>Itens Pendentes: <strong class="red-text">{{$p->itensPendentes()}}</strong></h5>
					<h5>Mesa: 
						@if($p->mesa != null)
						<strong class="red-text">{{$p->mesa->nome}}</strong>
						@else
						<strong class="red-text">AVULSA</strong>
						@endif
					</h5>

					<a class="btn white red-text" onclick = "if (! confirm('Deseja desativar esta comanda? os dados não poderam ser retomados!')) { return false; }" href="/pedidos/desativar/{{$p->id}}"><i class="material-icons red-text left">close</i> desativar</a>
				</div>

				<a href="/pedidos/ver/{{$p->id}}" style="width: 100%;" class="btn orange">Visualizar</a>
			</div>
			

		</div>
		@endforeach

		<div class="row">
			<div class="col s12">

				<div class="col s6 offset-s3">
					<a style="width: 100%;" href="/pedidos/mesas" class="btn-large red">VER MESAS</a>
				</div>
			</div>
		</div>
	</div>
	@else
	<div class="col s12">
		<h4 class="center-align">Nenhuma comanda aberta!</h4>
		<div class="col s4 offset-s4">
			<a class="btn-large pulse modal-trigger green accent-3" style="width: 100%;" href="#modal1">Abrir Comanda</a>
		</div>
	</div>
	@endif
</div>


<div id="modal1" class="modal">
	<form method="post" action="/pedidos/abrir">
		
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<div class="modal-content">
			<h4>Abrir Comanda</h4>
			<div class="row">
				<div class="input-field col s6">
					<input type="text" name="comanda" id="comanda" data-length="20">
					<label>Código da Comanda</label>
				</div>

				<div class="input-field col s4">
					<select name="mesa_id">
						<option value="null">*</option>
						@foreach($mesas as $m)
						<option value="{{$m->id}}">{{$m->nome}}</option>
						@endforeach
					</select>
					<label>Mesa</label>
				</div>
			</div>

			<div class="row">
				<div class="input-field col s12">
					<textarea type="text" class="materialize-textarea" data-length="200" name="observacao" id="observacao"></textarea>
					<label>Observação</label>
				</div>
			</div>

		</div>
		<div class="modal-footer">
			<a href="#!" class="modal-action modal-close red white-text waves-effect waves-green btn-flat">Fechar</a>
			<button href="#!" class="modal-action waves green accent-3 btn">Abrir</button>
		</div>
	</form>
</div>
@endsection	