@extends('default.layout')
@section('content')


<div class="row" id="anime" style="display: none">
	<div class="col s8 offset-s2">
		<lottie-player 
		src="/anime/success.json" background="transparent" speed="0.8"  
		style="width: 100%; height: 300px;" autoplay >
	</lottie-player>
</div>
</div>

<div class="row" id="content" style="display: block">
	<div class="col s12">
		<div class="card">
			<div class="row">
				<div class="col s12">
					<h4 class="center-align">EMISSÃO DE CT-e</h4>

					<div class="row">
						<form id="form-import" method="post" action="/cte/importarXml" enctype="multipart/form-data">
							@csrf
							<div class="col s3">
								<div class="file-field input-field">
									<div class="btn black">
										<i class="material-icons left">save</i>
										<span>Importar XML</span>
										<input name="file" id="file" accept=".xml" type="file">
									</div>
									<div class="file-path-wrapper" style="visibility: hidden;">
										<input class="file-path validate" type="text">
									</div>

									@if($errors->has('file'))
									<div class="center-align red lighten-2">
										<span class="white-text">{{ $errors->first('file') }}</span>
									</div>
									@endif
								</div>
							</div>
						</form>
					</div>

					<h5 class="grey-text">DADOS INICIAIS</h5>

					<div class="row">
						<div class="col s4">
							<h6>Ultima CT-e: <strong class="red-text">{{$lastCte}}</strong></h6>
						</div>
						<div class="col s4">

							@if($config->ambiente == 2)
							<h6>Ambiente: <strong class="blue-text">Homologação</strong></h6>
							@else
							<h6>Ambiente: <strong class="green-text">Produção</strong></h6>
							@endif
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col s12">
						<div class="input-field col s4">
							<i class="material-icons prefix">featured_play_list</i>
							<select id="natureza">
								@foreach($naturezas as $n)
								<option value="{{$n->id}}">{{$n->natureza}}</option>
								@endforeach
							</select>
							<label>
								Natureza de Operação
							</label>
						</div>
					</div>
				</div>


				<div class="row">
					<div class="input-field col s4">
						<i class="material-icons prefix">send</i>
						<input autocomplete="off" type="text" name="remetente" id="autocomplete-remetente" class="autocomplete-remetente">
						<label for="autocomplete-remetente">Remetente</label>

						<div class="card" id="info-remetente" style="display: none">
							<div class="row">
								<h5 class="center-align">Remetente</h5>
								<div class="col s12">
									Razao Social: <strong id="nome-remetente" class="red-text"></strong>
								</div>
								<div class="col s6">
									CNPJ: <strong id="cnpj-remetente" class="red-text"></strong>
								</div>
								<div class="col s6">
									IE: <strong id="ie-remetente" class="red-text"></strong>
								</div>

								<div class="col s9">
									Rua: <strong id="rua-remetente" class="red-text"></strong>
								</div>
								<div class="col s3">
									Nro: <strong id="nro-remetente" class="red-text"></strong>
								</div>

								<div class="col s6">
									Bairro: <strong id="bairro-remetente" class="red-text"></strong>
								</div>
								<div class="col s6">
									Cidade: <strong id="cidade-remetente" class="red-text"></strong>
								</div>
							</div>
						</div>
					</div>

					<div class="input-field col s4 offset-s2">
						<i class="material-icons prefix">markunread_mailbox</i>
						<input autocomplete="off" type="text" name="destinatario" id="autocomplete-destinatario" class="autocomplete-destinatario">
						<label for="autocomplete-destinatario">Destinatário</label>

						<div class="card" id="info-destinatario" style="display: none">
							<div class="row">
								<h5 class="center-align">Destinatário</h5>
								<div class="col s12">
									Razao Social: <strong id="nome-destinatario" class="blue-text"></strong>
								</div>
								<div class="col s6">
									CNPJ: <strong id="cnpj-destinatario" class="blue-text"></strong>
								</div>
								<div class="col s6">
									IE: <strong id="ie-destinatario" class="blue-text"></strong>
								</div>
								<div class="col s9">
									Rua: <strong id="rua-destinatario" class="blue-text"></strong>
								</div>
								<div class="col s3">
									Nro: <strong id="nro-destinatario" class="blue-text"></strong>
								</div>

								<div class="col s6">
									Bairro: <strong id="bairro-destinatario" class="blue-text"></strong>
								</div>
								<div class="col s6">
									Cidade: <strong id="cidade-destinatario" class="blue-text"></strong>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col s12">
					<div class="card">
						<div class="card-header center-align">
							<h5>Referencia de Documento para CT-e</h5>
						</div>

						<ul class="tabs">
							<li class="tab col s6 ref-nfe"><a href="#ref-nfe" class="red-text">NF-e</a></li>
							<li class="tab col s6 ref-out"><a class="red-text" href="#ref-outros">OUTROS</a></li>


						</ul>

						<div id="ref-nfe">
							<div class="card">
								<div class="card-content">

									<div class="row">
										<div class="input-field col s8">
											<i class="material-icons prefix">code</i>
											<input autocomplete="off" type="text" id="chave_nfe">
											<label for="chave_nfe">Chave da NF-e</label>

										</div>
										<div class="col s4" style="display: none" id="chave-referenciada">
											<p class="red-text">
												Chave já referenciada em outra CT-e!
											</p>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div id="ref-outros">
							<div class="card">
								<div class="card-content">
									<div class="row">
										<div class="input-field col s2">
											<select name="tpDoc" id="tpDoc">
												<option value="00">Declaração</option>
												<option value="10">Dutoviário</option>
												<option value="59">CF-e SAT</option>
												<option value="65">NFC-e</option>
												<option value="99">Outros</option>
											</select>
											<label>Tipo do Doc.</label>
										</div>

										<div class="input-field col s4">
											<input class="type-ref" id="descOutros" type="text" name="descOutros">
											<label>Descrição do Documento</label>
										</div>


										<div class="input-field col s2">
											<input class="type-ref" id="nDoc" type="text" name="nDoc">
											<label>Numero do Doc.</label>
										</div>

										<div class="input-field col s2">
											<input class="type-ref" id="vDocFisc" type="text" name="vDocFisc">
											<label>Valor do Doc.</label>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>

		<div class="card">
			<div class="">
				<div class="col s12">
					<ul class="tabs">
						<li class="tab col s6"><a href="#itens" class="blue-text">INFORMAÇÕES DA CARGA</a></li>
						<li class="tab col s6"><a class="blue-text" href="#entrega">INFORMAÇÕES DE ENTREGA</a></li>


					</ul>
				</div>
				<div id="itens" class="col s12">
					<div class="card">
						<div class="row">
							<div class="col s12">
								<h5 class="grey-text">INFORMAÇÕES DA CARGA</h5>
							</div>

							<div class="col s12">
								<div class="input-field col s3">
									<select id="veiculo_id">
										@foreach($veiculos as $v)
										<option value="{{$v->id}}">{{$v->modelo}} {{$v->placa}}</option>
										@endforeach
									</select>
									<label>Veiculo</label>
								</div>
							</div>
							<div class="col s12">
								<div class="input-field col s4">
									<input autocomplete="off" type="text" name="prod_predominante" id="prod_predominante" class="prod_predominante type-ref">
									<label for="prod_predominante">Produto predominante</label>

								</div>

								<div class="col s3 input-field" >
									<select id="tomador">
										@foreach($tiposTomador as $key => $t)
										<option value="{{$key}}">{{$key ."-".$t}}</option>
										@endforeach
									</select>
									<label>Tomador</label>
								</div>
							</div>

							<div class="col s12">
								<div class="input-field col s3">
									<input autocomplete="off" type="text" id="valor_carga" class="valor_carga">
									<label for="valor_carga">Valor da Carga</label>
								</div>

								<div class="col s2 input-field">
									<select id="modal-transp">
										@foreach($modals as $key => $t)
										<option value="{{$key}}">{{$key ."-".$t}}</option>
										@endforeach
									</select>
									<label>Modelo de Transporte</label>
								</div>

							</div>
							<input type="hidden" value="{{csrf_token()}}" id="_token">
							<div class="row">
								<div class="col s12">
									<h6 class="blue-text">INFORMAÇÕES DE QUANTIDADE</h6>

									<div class="input-field col s2">
										<select id="unidade_medida">
											@foreach($unidadesMedida as $key => $u)
											<option value="{{$key}}-{{$u}}">{{$key}}-{{$u}}</option>
											@endforeach
										</select>
										<label>Unidade medida</label>
									</div>

									<div class="input-field col s2">
										<select id="tipo_medida">
											@foreach($tiposMedida as $u)
											<option value="{{$u}}">{{$u}}</option>
											@endforeach
										</select>
										<label>Tipo de medida</label>
									</div>

									<div class="col s2 input-field">
										<input type="text" value="0" id="quantidade_carga">
										<label>Quantidade</label>
									</div>

									<div class="col s1">
										<button id="addMedida" class="btn-large blue">
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
								</div>
							</div>

							<div class="row">
								<div class="col s10">
									<table id="prod" class="striped">
										<thead>
											<tr>
												<th>Item</th>
												<th>Código Unidade</th>
												<th>Tipo de Medida</th>
												<th>Quantidade</th>
												<th>Ação</th>
											</tr>
										</thead>

										<tbody>

										</tbody>

									</table>
								</div>
							</div>


							<div class="row">
								<div class="col s12">
									<h6 class="red-text">COMPONENTES DA CARGA</h6>
									<p class="black-text">*A soma dos valores dos componentes deve ser igual ao valor a receber</p>
									<div class="input-field col s3">
										<input data-length="20" type="text" id="nome_componente">
										<label>Nome do Componente</label>
									</div>

									<div class="col s2 input-field">
										<input type="text" value="" id="valor_componente">
										<label for="valor_componente">Valor</label>
									</div>

									<div class="col s1">
										<button id="addComponente" class="btn-large red">
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
								</div>
							</div>

							<div class="row">
								<div class="col s10">
									<table id="componentes" class="striped">
										<thead>
											<tr>
												<th>Item</th>
												<th>Nome</th>
												<th>Valor</th>
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
				<div id="entrega" class="col s12">

					<div class="card">
						<div class="row">
							<div class="col s12">
								<h5 class="grey-text">INFORMAÇÕES DA ENTREGA</h5>
							</div>
							<div class="col s12">

								<h6>Endereço do Tomador</h6>
								<div class="col s12">
									<p>
										<input type="checkbox" id="endereco-destinatario" />
										<label for="endereco-destinatario">Endereço do Destinatário</label>
									</p>

									<p>
										<input type="checkbox" id="endereco-remetente" />
										<label for="endereco-remetente">Endereço do Rementente</label>
									</p>
								</div>
								<div class="col s6 input-field">
									<input type="text" value=" " id="rua_tomador">
									<label>Rua</label>
								</div>
								<div class="col s2 input-field">
									<input type="text" value=" " id="numero_tomador">
									<label>Numero</label>
								</div>

								<div class="col s2 input-field">
									<input type="text" value=" " id="cep_tomador">
									<label>CEP</label>
								</div>

								<div class="col s4 input-field">
									<input type="text" value=" " id="bairro_tomador">
									<label>Bairro</label>
								</div>

								<div class="col s3 input-field">
									<input autocomplete="off" type="text" name="cidade_tomador" id="autocomplete-cidade-tomador" value=" " class="autocomplete-cidade-tomador">
									<label>Cidade</label>
								</div>

							</div>

						</div>
						<div class="row">

							<div class="col s12">
								<div class="col s2 input-field">
									<input type="text" class="datepicker" value=" " id="data_prevista_entrega">
									<label>Data Prevista de Entrega</label>
								</div>

								<div class="input-field col s3">
									<input autocomplete="off" type="text" id="valor_transporte" class="valor_transporte type-ref">
									<label for="valor_transporte">Valor da Prestação de Serviço</label>
								</div>
								<div class="input-field col s3">
									<input autocomplete="off" type="text" id="valor_receber" class="valor_receber type-ref">
									<label for="valor_receber">Valor a Receber</label>
								</div>

							</div>
						</div>

						<div class="row">
							<div class="col s12">
								<div class="col s3 input-field">
									<input autocomplete="off" type="text" name="cidade_envio" id="autocomplete-cidade-envio" value="{{old('cidade_envio')}}" class="autocomplete-cidade-envio type-ref">
									<label>Municipio de Envio</label>
								</div>
								<div class="col s3 input-field">
									<input autocomplete="off" type="text" name="cidade_inicio" id="autocomplete-cidade-inicio" value="{{old('cidade_inicio')}}" class="autocomplete-cidade-inicio type-ref">
									<label>Municipio de Inicio</label>
								</div>
								<div class="col s3 input-field">
									<input autocomplete="off" type="text" name="cidade_fim" id="autocomplete-cidade-final" value="{{old('cidade_fim')}}" class="autocomplete-cidade-final type-ref">
									<label>Municipio Final</label>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col s12">
								<div class="col s2 input-field">
									<select id="retira">
										<option value="1">Sim</option>
										<option value="0">Não</option>
									</select>
									<label>Retira</label>
								</div>
								<div class="col s10 input-field">
									<input type="text" id="detalhes_retira">
									<label>Detalhes(opcional)</label>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>

			<div class="row">
				<div class="col s12">
					<div class="col s5 input-field"><br>
						<input type="text" id="obs" name="">
						<label>Informação Adicional</label>
					</div>

					<div class="col s2 offset-s5"><br>

						<a id="finalizar" style="width: 100%;" href="#" onclick="salvarCTe()" class="btn-large green accent-3 disabled">Finalizar</a>
					</div>
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

@endsection