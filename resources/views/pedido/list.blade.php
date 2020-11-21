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
	<div class="col s12">	

		@if(sizeof($mesasFechadas) > 0)
		<div class="row">
			<div class="col s12">
				<div class="card">
					<div class="card-content">
						<h4>Mesas com pedido de fechamento:</h4>
						@foreach($mesasFechadas as $m)
						<a href="/pedidos/verMesa/{{$m->mesa->id}}" target="_blank" class="btn red">Ver {{$m->mesa->nome}}</a>
						@endforeach
					</div>
				</div>
			</div>
		</div>
		@endif

		@if(sizeof($mesasParaAtivar) > 0)
		<div class="row">
			<div class="col s12">
				<div class="card">
					<div class="card-content">
						<h4>Mesas a serem ativadas:</h4>
						@foreach($mesasParaAtivar as $m)
						<a onclick='swal("Atenção!", "Deseja ativar esta mesa?", "warning").then((sim) => {if(sim){ location.href="/pedidos/ativarMesa/{{ $m->id }}" }else{return false} })' href="#!" class="btn {{$m->randomColor()}}">Ativar {{$m->mesa->nome}}</a>
						@endforeach
					</div>
				</div>
			</div>
		</div>
		@endif

		<div class="row">
			<div class="col s4 offset-s4">
				<a class="btn-large modal-trigger green accent-3" style="width: 100%;" href="#modal1">Abrir Comanda</a>
			</div>
		</div>
		@if(count($pedidos) > 0)

		<h5 class="green-text">Comandas em verde já finalizadas</h5>
		@foreach($pedidos as $p)
		<div class="col s4">
			<div class="card @if($p->status) green lighten-4 @endif">
				<div class="card-content">

					<h5 class="center-align grey-text">COMANDA</h5>
					@if($p->comanda == '')
					<a style="margin-top: 20px;" class="btn modal-trigger red accent-3" style="width: 100%;" onclick="atribuir('{{$p->id}}', '{{$p->mesa->nome}}')" href="#modal-comanda">Atribuir comanda</a>
					<h2><br></h2>
					@else
					<h1 class="center-align">{{$p->comanda}}</h1>
					@endif

					<h5>Total: <strong>R$ {{number_format($p->somaItems(),2 , ',', '.')}}</strong></h5>
					<h5>Horário Abertura: <strong>{{ \Carbon\Carbon::parse($p->data_registro)->format('H:i')}}</strong></h5>
					<h5>Total de itens: <strong class="red-text">{{count($p->itens)}}</strong></h5>
					<h5>Itens Pendentes: <strong class="red-text">{{$p->itensPendentes()}}</strong></h5>
					<h5>Mesa: 
						@if($p->mesa != null)
						<strong class="red-text">{{$p->mesa->nome}}</strong>
						@else
						<strong class="red-text">AVULSA</strong> 
						<a onclick="setarMesa('{{$p->id}}', '{{$p->comanda}}')" href="#modal-set-mesa" class="btn modal-trigger">
							setar
						</a>
						@endif
					</h5>

					@if($p->referencia_cliete != '')
					<h5 class="blue-text">Mesa QrCode</h5>
					@else
					<h5><br></h5>
					@endif

					<a class="btn white red-text" 
					onclick='swal("Atenção!", "Deseja desativar esta comanda? os dados não poderam ser retomados!", "warning").then((sim) => {if(sim){ location.href="/pedidos/desativar/{{ $p->id }}" }else{return false} })' href="#!"><i class="material-icons red-text left">close</i> desativar</a>
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

<div id="modal-comanda" class="modal">
	<form method="post" action="/pedidos/atribuirComanda">
		
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<input type="hidden" id="pedido_id" name="pedido_id">
		<div class="modal-content">
			<h4>Abrir Comanda</h4>
			<div class="row">
				<div class="input-field col s6">
					<input required type="text" name="comanda" id="comanda" data-length="20">
					<label>Código da Comanda</label>
				</div>

				<div class="input-field col s4">
					<input type="text" name="mesa" id="mesa_atribuida" disabled>
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

<div id="modal-set-mesa" class="modal">
	<form method="post" action="/pedidos/atribuirMesa">
		
		<input type="hidden" name="_token" value="{{ csrf_token() }}">
		<input type="hidden" id="pedido_id_mesa" name="pedido_id">
		<div class="modal-content">
			<h4>Setar Mesa Comanda</h4>
			<div class="row">
				<div class="input-field col s4">
					<input required type="text" name="comanda" id="comanda_mesa" data-length="20">
					<label>Código da Comanda</label>
				</div>

				<div class="input-field col s4">
					<select name="mesa">
						@foreach($mesas as $m)
						<option value="{{$m->id}}">{{$m->nome}}</option>
						@endforeach
					</select>
					<label>Mesa</label>
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