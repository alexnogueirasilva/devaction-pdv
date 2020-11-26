@extends('default.layout')
@section('content')
<style type="text/css">
.divider{
	background: #999;
	height: 1px;
	width: 100%;
}
</style>
<div class="row">
	<div class="col s12">

		@if(session()->has('message'))
		<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
			<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
		</div>
		<br>
		@endif

		<div class="row">
			<div class="col s12">
				<div class="card">
					<div class="row">
						<div class="card-content">
							<div class="col s6">
								<a target="_blank" href="/ordemServico/imprimir/{{$ordem->id}}" class="btn">
									<i class="material-icons left">print</i> Imprimir
								</a>
								<h5>Status da OS:
									@if($ordem->estado == 'pd')
									<strong class="yellow-text">Pendente</strong>
									@elseif($ordem->estado == 'ap')
									<strong class="green-text">Aprovado</strong>
									@elseif($ordem->estado == 'rp')
									<strong class="red-text">Reprovado</strong>
									@else
									<strong class="blue-text">Finalizado</strong>
									@endif

									<a href="/ordemServico/alterarEstado/{{$ordem->id}}" class="btn-floating orange">
										<i class="material-icons">refresh</i>
									</a>
								</h5>

								<h5>NFSe: 
									@if($ordem->NfNumero)
									<strong>{{$ordem->NfNumero}}</strong>
									@else
									<strong> -- </strong>
									@endif
								</h5>

								
							</div>

							<div class="col s6">
								<h5>Total: <strong class="green-text">R$ {{number_format($ordem->valor, 2, ',', '.')}}
								</strong></h5>
								<h5>Usuario responsável: <strong>{{$ordem->usuario->nome}}</strong></h5>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="divider"></div>
		<h4>Adicionar Serviço</h4>
		
		<form method="post" action="/ordemServico/addServico">
			<section class="section-1">
				<div class="row">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="ordem_servico_id" name="" value="{{$ordem->id}}">
					<div class="input-field col s6">
						<i class="material-icons prefix">person</i>
						<input autocomplete="off" type="text" name="servico" id="autocomplete-servico" class="autocomplete-servico">
						<label for="autocomplete-servico">Serviço</label>
						@if($errors->has('servico'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('servico') }}</span>
						</div>
						@endif
					</div>

					<div class="input-field col s3">
						<i class="material-icons prefix">plus_one</i>
						<input type="text" name="quantidade" id="quantidade" class="validate">
						<label for="quantidade">Quantidade</label>
						@if($errors->has('quantidade'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('quantidade') }}</span>
						</div>
						@endif
					</div>


					<div class="col s2">
						<input type="submit" value="Adicionar" class="btn-large green accent-3">
					</div>
				</div>

			</section>

		</form>
		<br>
		<div class="row">
			<h4 class="grey-text">Serviços</h4>
			<p class="blue-text">Registros: {{count($ordem->servicos)}}</p>
			
			<table class="striped col s12">
				<thead>
					<tr>
						<th>Serviço</th>
						<th>Quantidade</th>
						<th>Status</th>
						<th>Total</th>
						<th>Ações</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$total = 0;
					?>
					@foreach($ordem->servicos as $s)
					<tr>
						<th>{{$s->servico->nome}}</th>
						<th>{{$s->quantidade}}</th>
						<th>
							@if($s->status == true)
							<i class="material-icons green-text">brightness_1</i>
							@else
							<i class="material-icons red-text">brightness_1</i>
							@endif
						</th>
						<th>{{number_format(($s->servico->valor * $s->quantidade), 2, ',', '.')}}</th>
						<?php 
						$total += $s->servico->valor * $s->quantidade;
						?>
						<th>

							<a onclick = "if (! confirm('Deseja excluir este registro?')) { return false; }" href="/ordemServico/deleteServico/{{ $s->id }}">
								<i class="material-icons left red-text">delete</i>					
							</a>
							@if($s->status)
							<a href="/ordemServico/alterarStatusServico/{{ $s->id }}">
								<i class="material-icons left yellow-text">remove</i>	
							</a>
							@else
							<a href="/ordemServico/alterarStatusServico/{{ $s->id }}">
								<i class="material-icons left green-text">check</i>	
							</a>
							@endif
						</th>
					</tr>
					@endforeach
					<tr class="grey">
						<th colspan="3"></th>
						<th colspan="2">{{number_format($total, 2, ',', '.')}}</th>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="divider"></div>

		<h4>Adicionar Funcioanrio</h4>
		<form method="post" action="/ordemServico/saveFuncionario">
			<section class="section-1">
				<div class="row">
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="ordem_servico_id" name="" value="{{$ordem->id}}">
					<div class="input-field col s6">
						<i class="material-icons prefix">person</i>
						<input autocomplete="off" type="text" name="funcionario" id="autocomplete-funcionario" class="autocomplete-funcionario">
						<label for="autocomplete-funcionario">Funcionario</label>
						@if($errors->has('funcionario'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('funcionario') }}</span>
						</div>
						@endif
					</div>

					<div class="input-field col s4">
						<i class="material-icons prefix">note</i>
						<input type="text" name="funcao" id="funcao" class="validate">
						<label for="funcao">Função</label>
						@if($errors->has('funcao'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('funcao') }}</span>
						</div>
						@endif
					</div>


					<div class="col s2">
						<input type="submit" value="Adicionar" class="btn-large green accent-3">
					</div>
				</div>

			</section>

		</form>

		<div class="row">
			<h4 class="grey-text">Funcionarios</h4>
			<p class="blue-text">Registros: {{count($ordem->funcionarios)}}</p>

			<table class="striped col s12">
				<thead>
					<tr>
						<th>Nome</th>
						<th>Função</th>
						<th>Telefone</th>
						<th>Ações</th>
					</tr>
				</thead>
				<tbody>
					@foreach($ordem->funcionarios as $f)
					<tr>
						<th>{{$f->funcionario->nome}}</th>
						<th>{{$f->funcao}}</th>
						<th>{{$f->funcionario->telefone}} / {{$f->funcionario->celular}}</th>
						<th>
							<a onclick = "if (! confirm('Deseja excluir este registro?')) { return false; }" href="/ordemServico/deleteFuncionario/{{ $f->id }}">
								<i class="material-icons left red-text">delete</i>					
							</a>
						</th>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>

		<br>
		<div class="row">
			<h4 class="grey-text">Relatórios</h4>
			<p class="blue-text">Registros: {{count($ordem->relatorios)}}</p>
			<a href="/ordemServico/addRelatorio/{{$ordem->id}}" class="btn black">
				<i class="material-icons left">add</i>
				Adicionar Relatório
			</a>
			<table class="striped col s12">
				<thead>
					<tr>
						<th>Código</th>
						<th>Data</th>
						<th>Usuario</th>
						<th>Ações</th>
					</tr>
				</thead>

				<tbody>
					@foreach($ordem->relatorios as $r)
					<tr>
						<th>{{$r->id}}</th>
						<th>{{ \Carbon\Carbon::parse($r->data_registro)->format('d/m/Y H:i:s')}}</th>
						<th>{{$r->usuario->nome}}</th>
						<th>
							<a onclick = "if (! confirm('Deseja excluir este registro?')) { return false; }" href="/ordemServico/deleteRelatorio/{{ $r->id }}">
								<i class="material-icons left red-text">delete</i>					
							</a>

							<a href="/ordemServico/editRelatorio/{{ $r->id }}">
								<i class="material-icons left blue-text">edit</i>					
							</a>

							<a class="waves-effect waves-light modal-trigger" 
							onclick="modal('{{ \Carbon\Carbon::parse($r->data_registro)->format('d/m/Y H:i:s')}}', '{{$r->texto}}')"><i class="material-icons left green-text">visibility</i>
						</a>
					</th>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>


	<!-- Modal -->



	<!-- Fim Modal -->

</div>
</div>
<div id="modal1" class="modal">
	<div class="modal-content">
		<h4 id="data"></h4>
		<p id="texto"></p>
	</div>
	<div class="modal-footer">
		<a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat">Fechar</a>
	</div>
</div>
@endsection
