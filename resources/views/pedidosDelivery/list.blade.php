@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">
		<form method="get" action="/pedidosDelivery/filtro">
			<div class="row">
				<div class="col s12">
					
					<div class="col s3 input-field">
						<input value="{{{ isset($dataInicial) ? $dataInicial : '' }}}" type="text" class="datepicker" name="data_inicial">
						<label>Data Inicial</label>
					</div>
					<div class="col s3 input-field">
						<input value="{{{ isset($dataFinal) ? $dataFinal : '' }}}" type="text" class="datepicker" name="data_final">
						<label>Data Final</label>
					</div>

					<div class="col s2">
						<button type="submit" class="btn-large">
							<i class="material-icons">search</i>
						</button>
					</div>
				</div>

			</div>
		</form>
		<div class="row">
			<h4 class="center-align">{{$tipo}}</h4>
		</div>

		@if(session()->has('message'))
		<div class="row">
			<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
				<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
			</div>
		</div>
		@endif

		<div class="row">
			<div class="col s12">
				<h4>Total de Pedidos: <strong>
					{{
						count($pedidosNovo) + count($pedidosAprovado) +
						count($pedidosRecusado) + count($pedidosReprovaco) + 
						count($pedidosFinalizado)

					}}
				</strong></h4>
				<div class="col s6">
					<h5>Valor de Pedidos Novos R$ <strong class="blue-text">R$ {{number_format($somaNovos, 2, ',', '.')}}</strong></h5>
					<h5>Valor de Pedidos Aprovados R$ <strong class="green-text">R$ {{number_format($somaAprovados, 2, ',', '.')}}</strong></h5>
					<h5>Valor de Pedidos Recusados R$ <strong class="yellow-text">R$ {{number_format($somaRecusados, 2, ',', '.')}}</strong></h5>
				</div>
				<div class="col s6">
					<h5>Valor de Pedidos Reprovados R$ <strong class="red-text">R$ {{number_format($somaReprovados, 2, ',', '.')}}</strong></h5>
					<h5>Valor de Pedidos Finalizados R$ <strong class="cyan-text">R$ {{number_format($somaFinalizados, 2, ',', '.')}}</strong></h5>
					<h5 class="red-text">Carrinho <strong class="black-text">R$ {{number_format($carrinho, 2, ',', '.')}}</strong></h5>
				</div>
			</div>
		</div>
		<ul class="collapsible popout" data-collapsible="accordion">
			<li>
				<div class="collapsible-header"><i class="material-icons blue-text">panorama_fish_eye</i>Pedidos Novos </div>
				<div class="collapsible-body">
					@if(count($pedidosNovo) > 0)
					@foreach($pedidosNovo as $p)
					<div class="row">
						<a href="/pedidosDelivery/verPedido/{{$p->id}}" class="btn blue">
							Pedido N: {{$p->id}}, Cliente: {{$p->cliente->nome}}, Valor do pedido R$ 
							{{number_format($p->somaItens(), 2, ',', '.')}}, 
							Horario: {{ \Carbon\Carbon::parse($p->data_registro)->format('H:i:s')}}
						</a>
					</div>
					@endforeach
					@else
					<h5>Nenhum pedido neste estado!</h5>
					@endif

				</div>
			</li>
			<li>
				<div class="collapsible-header"><i class="material-icons green-text">panorama_fish_eye</i>Pedidos Aprovados</div>
				<div class="collapsible-body">
					@if(count($pedidosAprovado) > 0)
					@foreach($pedidosAprovado as $p)
					<div class="row">
						<a href="/pedidosDelivery/verPedido/{{$p->id}}" class="btn green">
							Pedido N: {{$p->id}}, Cliente: {{$p->cliente->nome}}, Valor R$ 
							{{number_format($p->somaItens(), 2, ',', '.')}}, 
							Horario: {{ \Carbon\Carbon::parse($p->data_registro)->format('H:i:s')}}
						</a>
					</div>
					@endforeach
					@else
					<h5>Nenhum pedido neste estado!</h5>
					@endif
				</div>
			</li>

			<li>
				<div class="collapsible-header"><i class="material-icons yellow-text">panorama_fish_eye</i>Pedidos Recusados</div>
				<div class="collapsible-body">
					@if(count($pedidosRecusado) > 0)
					@foreach($pedidosRecusado as $p)
					<div class="row">
						<a href="/pedidosDelivery/verPedido/{{$p->id}}" class="btn yellow">
							Pedido N: {{$p->id}}, Cliente: {{$p->cliente->nome}}, Valor R$ 
							{{number_format($p->somaItens(), 2, ',', '.')}}, 
							Horario: {{ \Carbon\Carbon::parse($p->data_registro)->format('H:i:s')}}
						</a>
					</div>
					@endforeach
					@else
					<h5>Nenhum pedido neste estado!</h5>
					@endif
				</div>
			</li>

			<li>
				<div class="collapsible-header"><i class="material-icons red-text">panorama_fish_eye</i>Pedidos Reprovados</div>
				<div class="collapsible-body">
					@if(count($pedidosReprovaco) > 0)
					@foreach($pedidosReprovaco as $p)
					<div class="row">
						<a href="/pedidosDelivery/verPedido/{{$p->id}}" class="btn red">
							Pedido N: {{$p->id}}, Cliente: {{$p->cliente->nome}}, Valor R$ 
							{{number_format($p->somaItens(), 2, ',', '.')}}, 
							Horario: {{ \Carbon\Carbon::parse($p->data_registro)->format('H:i:s')}}
						</a>
					</div>
					@endforeach
					@else
					<h5>Nenhum pedido neste estado!</h5>
					@endif
				</div>
			</li>
			<li>
				<div class="collapsible-header"><i class="material-icons cyan-text">panorama_fish_eye</i>Pedidos Finalizados</div>
				<div class="collapsible-body">
					@if(count($pedidosFinalizado) > 0)
					@foreach($pedidosFinalizado as $p)
					<div class="row">
						<a href="/pedidosDelivery/verPedido/{{$p->id}}" class="btn cyan">
							Pedido N: {{$p->id}}, Cliente: {{$p->cliente->nome}}, Valor R$ 
							{{number_format($p->somaItens(), 2, ',', '.')}}, 
							Horario: {{ \Carbon\Carbon::parse($p->data_registro)->format('H:i:s')}}
						</a>
					</div>
					@endforeach
					@else
					<h5>Nenhum pedido neste estado!</h5>
					@endif
				</div>
			</li>

			<li>
				<div class="collapsible-header"><i class="material-icons black-text">panorama_fish_eye</i>Pedidos do Carrinho/A Finalizar</div>
				<div class="collapsible-body">
					@if(!empty($carrinho))
						<a href="/pedidosDelivery/verCarrinhos" class="btn black">
							Ver carrinhos em aberto
						</a>
					@else
					<h5>Nenhum carrinho em aberto!</h5>
					@endif
				</div>
			</li>
		</ul>


	</div>
</div>
@endsection	