@extends('default.layout')
@section('content')
<div class="row">
	<div class="col s12">
		<h4>Receita Produto <strong>{{$produto->nome}}</strong></h4>
		@if(!$produto->receita)

		<!-- Bloco para cadastrar as descricoes -->

		@if(session()->has('message'))
		<div class="row">
			<div style="border-radius: 10px;" class="col s12 {{ session('color') }}">
				<h5 class="center-align white-text">{{ session()->get('message') }}</h5>
			</div>
		</div>
		@endif

		<h5 class="text-center">Dados iniciais da receita</h5>
		<section class="section-1">
			<form method="post" action="/receita/save">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input type="hidden" name="produto_id" name="" value="{{$produto->id}}">

				<div class="row">
					<div class="col s12">
						<input type="text" name="descricao">
						<label>Descrição</label>
					</div>
				</div>

				<div class="row">
					<div class="col s3">
						<input type="text" name="rendimento" class="rendimento" value="{{old('rendimento')}}">
						<label>Rendimento</label>
						@if($errors->has('rendimento'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('rendimento') }}</span>
						</div>
						@endif
					</div>

					<div class="col s3">
						<input type="text" class="tempo_preparo" value="{{old('tempo_preparo')}}" id="tempo_preparo" name="tempo_preparo">
						<label>Tempo de Preparo (Minutos)</label>
						@if($errors->has('tempo_preparo'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('tempo_preparo') }}</span>
						</div>
						@endif
					</div>
				</div>

				<div class="row">
					<div class="col s3">
						<input type="text" id="pedacos" name="pedacos" value="{{old('pedacos')}}">
						<label>Quantidade de Pedaços (opcional)</label>
						@if($errors->has('pedacos'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('pedacos') }}</span>
						</div>
						@endif
						
					</div>

					<div class="col s9">
						<p class="red-text">Informe um valor no campo pedaços, se seu produto for uma pizza</p>
					</div>
				</div>

				<button href="" type="reset" class="btn-large red">Limpar</button>
				<button href="" type="submit" class="btn-large green accent-3">Salvar</button>
			</form>
		</section>

		@else

		<!-- Bloco para itens da receita -->
		<div class="row">
			<ul class="collapsible" data-collapsible="accordion">
				<li>
					<div class="collapsible-header"><i class="material-icons">unfold_more</i>Receita</div>
					<div class="collapsible-body">
						<form method="post" action="/receita/update">
							<input type="hidden" value="{{$produto->receita->id}}" name="receita_id">
							<div class="row">
								<div class="col s8 input-field">
									<input value="{{$produto->receita->descricao}}" type="text" name="descricao">
									<label>Descrição</label>
								</div>
							</div>
							<div class="row">
								<div class="col s8 input-field">
									<input value="{{$produto->receita->rendimento}}" 
									class="rendimento" type="text" name="rendimento">
									<label>Rendimento</label>
								</div>
							</div>
							<div class="row">
								<div class="col s8 input-field">
									<input value="{{$produto->receita->tempo_preparo}}" type="text" name="tempo_preparo" class="tempo_preparo">
									<label>Tempo de preparo</label>
								</div>
							</div>
							<div class="row">
								<div class="col s8 input-field">
									<input value="{{$produto->receita->pedacos}}" type="text" name="pedacos">
									<label>Pedaços</label>
								</div>
							</div>
							<input type="hidden" name="_token" value="{{ csrf_token() }}">

							<div class="row">
								<button type="submit" class="btn-large">ATUALIZAR</button>
							</div>
						</form>
						
					</div>
				</li>
			</ul>
		</div>
		<div class="row">
			<form method="post" action="/receita/saveItem">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">

				<div class="card">
					<input type="hidden" name="produto_id" name="" value="{{$produto->id}}">
					<input type="hidden" value="{{$produto->receita->id}}" name="receita_id">
					<div class="row">
						<div class="col s5">
							<div class="card">

								<div class="row">
									<div class="col s12">
										<h5 class="center-align">Adicionar Item</h5>
									</div>
								</div>

								<div class="row">
									<div class="input-field col s12">
										<input autocomplete="off" type="text" name="produto" id="autocomplete-produto" class="autocomplete-produto">
										<label for="autocomplete-produto">Produto</label>
										@if($errors->has('produto'))
										<div class="center-align red lighten-2">
											<span class="white-text">{{ $errors->first('produto') }}</span>
										</div>
										@endif
									</div>
								</div>

								<div class="row">
									<div class="col s8 input-field">
										<input type="text" id="quantidade" name="quantidade">
										<label>Quatidade</label>
										@if($errors->has('quantidade'))
										<div class="center-align red lighten-2">
											<span class="white-text">{{ $errors->first('quantidade') }}</span>
										</div>
										@endif
									</div>
								</div>
								<div class="row">
									<div class="col s8 input-field">
										<select name="medida">
											<option value="Kilo">Kilo</option>
											<option value="Unidade">Unidade</option>
											<option value="Litro">Litro</option>
										</select>
										<label>Unidade de Quantidade</label>
									</div>
								</div>
								<div class="row">
									<div class="col s5">
										<button style="width: 100%;" type="reset" class="btn red">Limpar</button>
									</div>
									<div class="col s5">
										<button style="width: 100%;" type="submit" class="btn green accent-3">Adicionar</button>
									</div>
								</div>
								<br>
							</div>
						</div>
						<div class="col s7">
							<p>Registros: <strong>{{count($produto->receita->itens)}}</strong></p>
							<table class="striped">
								<thead>
									<tr>
										<th>Produto</th>
										<th>Quantidade</th>
										<th>Medida</th>
										<th>Ações</th>
									</tr>
								</thead>
								<tbody>
									@foreach($produto->receita->itens as $i)
									<tr>
										<td>{{$i->produto->nome}}</td>
										<td>{{$i->quantidade}}</td>
										<td>{{$i->medida}}</td>
										<td>
											<a href="/receita/deleteItem/{{$i->id}}">
												<i class="material-icons red-text">delete</i>
											</a>
										</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
					
				</div>
				
				
			</form>
		</div>
		
		@endif
	</div>
</div>
@endsection