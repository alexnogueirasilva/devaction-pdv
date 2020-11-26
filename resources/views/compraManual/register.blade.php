@extends('default.layout')
@section('content')
<div class="row" id="anime" style="display: none">
	<div class="col s8 offset-s2">
		<lottie-player 
		src="/anime/success.json"  background="transparent"  speed="0.8"  style="width: 100%; height: 300px;"    autoplay >
	</lottie-player>
</div>
</div>

<div class="row" id="content" style="display: block">
	<div class="col s12">
		<div class="card">
			<div class="row">
				<div class="col s12">
					<h5 class="grey-text">DADOS INICIAIS</h5>
				</div>
				
				<div class="row">
					<div class="col s12">
						<div class="input-field col s6">
							<i class="material-icons prefix">person</i>
							<input autocomplete="off" type="text" name="fornecedor" id="autocomplete-fornecedor" class="autocomplete-fornecedor">
							<label for="autocomplete-cliente">Fornecedor</label>
							@if($errors->has('fornecedor'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('fornecedor') }}</span>
							</div>
							@endif
						</div>
					</div>
				</div>

				<div class="row" id="fornecedor" style="display: none">
					<div class="col s12">
						<h5>FORNECEDOR SELECIONADO</h5>
						<div class="col s6">
							<h5>Razão Social: <strong id="razao_social" class="red-text">--</strong></h5>
							<h5>Nome Fantasia: <strong id="nome_fantasia" class="red-text">--</strong></h5>
							<h5>Logradouro: <strong id="logradouro" class="red-text">--</strong></h5>
							<h5>Numero: <strong id="numero" class="red-text">--</strong></h5>

						</div>
						<div class="col s6">
							<h5>CPF/CNPJ: <strong id="cnpj" class="red-text">--</strong></h5>
							<h5>RG/IE: <strong id="ie" class="red-text">--</strong></h5>
							<h5>Fone: <strong id="fone" class="red-text">--</strong></h5>
							<h5>Cidade: <strong id="cidade" class="red-text">--</strong></h5>
							
						</div>
					</div>
					
				</div>

			</div>
		</div>

		<div class="row">
			<div class="col s12">
				<ul class="tabs">
					<li class="tab col s4"><a href="#itens" class="blue-text">ITENS</a></li>
					<li class="tab col s4"><a class="blue-text" href="#pagamento">PAGAMENTO</a></li>

				</ul>
			</div>
			<div id="itens" class="col s12">
				<div class="card">
					<div class="row">
						<div class="col s12">
							<h5 class="grey-text">ITENS</h5>
						</div>

						

						<div class="row">
							<div class="col s12">
								<div class="input-field col s4">
									<i class="material-icons prefix">inbox</i>
									<input autocomplete="off" type="text" name="produto" id="autocomplete-produto" class="autocomplete-produto">
									<label for="autocomplete-produto">Produto</label>

								</div>

								<div class="col s2 input-field">
									<input type="text" value="0" id="quantidade">
									<label>Quantidade</label>
								</div>

								<div class="col s2 input-field">
									<input type="text" id="valor" value="0">
									<label>Valor Unitário</label>
								</div>
								<div class="col s2 input-field">
									<input type="text" id="subtotal" value="0" disabled="">
									<label>Subtotal</label>
								</div>

								<div class="col s1">
									<button id="addProd" class="btn-large orange">
										<i class="material-icons">add</i>

									</button>
								</div>

								<div class="col s2">
									<div id="preloader1" style="display: none">
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
								</div>
								<div class="col s8" id="last-purchase" style="display: none">
									<p>Valor unitário deste produto na ultima compra: R$ <strong id="last-valor"></strong></p>
									<p>Quantidade: <strong id="last-quantidade"></strong></p>
									<p>Fornecedor: <strong id="last-fornecedor"></strong></p>
									<p>Data: <strong id="last-data"></strong></p>
								</div>
								<div id="preloader-last-purchase" style="display: none;">
									<div class="col s12 center-align">
										<div class="preloader-wrapper active">
											<div class="spinner-layer spinner-blue-only">
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
							</div>
							
						</div>

						<div class="row">
							<div class="col s12">
								<table id="prod" class="striped">
									<thead>
										<tr>
											<th>Item</th>
											<th>Código Produto</th>
											<th>Nome</th>
											<th>Quantidade</th>
											<th>Valor</th>
											<th>SubTotal</th>
											<th>Ação</th>
										</tr>
									</thead>

									<tbody>
									</tbody>
								</table>
							</div>
						</div>

					</div>
				</div>

			</div>

			<input type="hidden" id="_token" value="{{ csrf_token() }}">

			<div id="pagamento" class="col s12">
				<div class="card">
					<div class="row">
						<div class="col s4">
							<h5 class="grey-text">PAGAMENTO</h5>

							<div class="row">
								<div class="col s12 input-field">
									<select id="formaPagamento">
										<option value="--">Selecione a forma de pagamento</option>
										<option value="a_vista">A vista</option>
										<option value="30_dias">30 Dias</option>
										<option value="personalizado">Personalizado</option>
									</select>
									<label>Forma de Pagamento</label>
								</div>
							</div>
							<div class="row">
								<div class="col s6">
									<input disabled type="text" class="" id="qtdParcelas">
									<label>Quantidade de Parcelas</label>
								</div>
							</div>
							<div class="row">
								<div class="col s6">
									<input disabled type="text" class="datepicker" id="data">
									<label>Data Vencimento</label>
								</div>
								<div class="col s6">
									<input disabled type="text" id="valor_parcela">
									<label>Valor Parcela</label>
								</div>
							</div>
							<div class="row">
								<button id="add-pag" style="width: 100%;" class="btn green accent-3">
									<i class="material-icons left">add</i>
								Adicionar</button>
							</div>
							
							
						</div>
						<div class="col s7">
							<div class="row">
								<div class="col s12">
									<table id="fatura">
										<thead>
											<tr>
												<th>Parc</th>
												<th>Data</th>
												<th>Valor</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td></td>
												<td></td>
												<td></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
						
					</div>
				</div>
			</div>
		</div>

		<div class="card">
			<div class="row">
				<div class="col s3"><br>
					<h5>Valor Total R$ <strong id="total" class="cyan-text">0,00</strong></h5>
				</div>

				<div class="col s2 input-field"><br>
					<input type="text" id="desconto" name="">
					<label>Desconto</label>
				</div>

				<div class="col s5 input-field"><br>
					<input type="text" id="obs" name="">
					<label>Observação</label>
				</div>

				<div class="col s2"><br>
					<!-- <a id="salvar-venda" href="#modal1" style="width: 100%;" class="btn-large red disabled modal-trigger waves-effect waves-light">Salvar Venda</a> -->

					<a id="salvar-venda" style="width: 100%;" href="#" onclick="salvarCompra()" class="btn-large green accent-3 disabled">Salvar</a>
				</div>
			</div>
			<div class="row" >
				<div id="preloader2" style="display: none;">
					<div class="col s12 center-align">
						<div class="preloader-wrapper active">
							<div class="spinner-layer spinner-red-only">
								<div class="circle-clipper left">
									<div class="circle red"></div>
								</div><div class="gap-patch">
									<div class="circle"></div>
								</div><div class="circle-clipper right">
									<div class="circle"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>


<div id="modal1" class="modal">
	<div class="modal-content">
		<h4 class="center-align">Selecione o Modelo para a Venda</h4>
		<div class="row">
			<div class="col s4">
				<button onclick="salvarVenda('cp_fiscal')" style="width: 100%;" id="cupom" class="btn-large green">Cupom Fiscal</button>
			</div>
			<div style="display: none" class="col s4" id="col-credito">
				<button onclick="salvarVenda('credito')" style="width: 100%;" id="cupom" class="btn-large red">Credito Cliente</button>
			</div>

			<div class="col s4" id="col-sem-credito"></div>
			<div class="col s4">
				<button onclick="salvarVenda('cp_nao_fiscal')" style="width: 100%;" id="cupom" class="btn-large orange">Cupom Não Fiscal</button>
			</div>
		</div>

	</div>
</div>
@endsection