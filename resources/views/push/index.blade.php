@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">
		

		@if(session()->has('message'))
		<div class="row">
			<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
				<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
			</div>
		</div>
		@endif

		<div class="col s2 offset-s1">
			<a href="/push/new" class="btn green accent-3">
				<i class="material-icons left">notifications_active</i>
			Novo Push</a>
		</div>

		<table class="col s10 offset-s1">
			<thead>
				<tr>
					<th>#</th>
					<th>Titulo</th>
					<th>Texto</th>
					<th>Destino</th>
					<th>Status</th>
					<th>Ações</th>
				</tr>
			</thead>
			<tbody>
				@foreach($pushes as $p)
				<tr class="@if($p->status) green lighten-3 @endif">
					<td>{{$p->id}}</td>
					<td>{{$p->titulo}}</td>
					<td>{{$p->texto}}</td>
					@if($p->cliente_id)
					<td>{{$p->cliente->nome}}</td>
					@else
					<th>Todos</th>
					@endif

					<td>
						@if($p->status)
						<i class="material-icons green-text">
							brightness_1
						</i>
						@else
						<i class="material-icons red-text">
							brightness_1
						</i>
						@endif
					</td>
					<td>
						@if(!$p->status)
						<a href="/push/edit/{{$p->id}}">
							<i class="material-icons">edit</i>
						</a>
						<a href="/push/send/{{$p->id}}">
							<i class="material-icons green-text">send</i>
						</a>
						<a href="/push/delete/{{$p->id}}">
							<i class="material-icons red-text">delete</i>
						</a>
						@endif
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
@endsection	