@extends('default.layout')
@section('content')
<div class="row">
	<div class="col s12">
		<h4>{{{ isset($produto) ? "Editar": "Cadastrar" }}} Produto</h4>
		<form method="post" action="{{{ isset($produto) ? '/produtos/update': '/produtos/save' }}}" enctype="multipart/form-data">
			<input type="hidden" name="id" value="{{{ isset($produto->id) ? $produto->id : 0 }}}">
			<section class="section-1">
				<p class="red-text">Campos com (*) obrigatório</p>

				<div class="row">
					<div class="input-field col s8">
						<input value="{{{ isset($produto->nome) ? $produto->nome : old('nome') }}}" id="name" name="nome" type="text" class="validate">
						<label for="name">Nome *</label>

						@if($errors->has('nome'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('nome') }}</span>
						</div>
						@endif

					</div>
				</div>

				<div class="row">
					<div class="input-field col s3">
						<input value="{{{ isset($produto->valor_venda) ? $produto->valor_venda : old('valor_venda') }}}" id="valor" name="valor_venda" type="text" class="validate">
						<label for="value_sale">Valor de Venda *</label>

						@if($errors->has('valor_venda'))
						<div class="center-align red lighten-2">
							<span class="white-text">{{ $errors->first('valor_venda') }}</span>
						</div>
						@endif

					</div>

					<div class="col s2">
						<label>Valor Livre</label>

						<div class="switch">
							<label class="">
								Não
								<input @if(isset($produto->valor_livre) && $produto->valor_livre) checked @endisset value="true" name="valor_livre" class="red-text" type="checkbox">
								<span class="lever"></span>
								Sim
							</label>
						</div>
					</div>

					<div class="input-field col s3">

						<select name="categoria_id">
							@foreach($categorias as $cat)
							<option value="{{$cat->id}}"
								@isset($produto)
								@if($cat->id == $produto->categoria_id)
								selected=""
								@endif
								@endisset >{{$cat->nome}}</option>

								@endforeach
							</select>
							<label>Categoria</label>
							@if($errors->has('categoria_id'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('categoria_id') }}</span>
							</div>
							@endif

						</div>
					</div>

					<div class="row">
						<div class="input-field col s4">
							<select name="cor">
								<option value="--">--</option>
								<option value="Preto">Preto</option>
								<option value="Branco">Branco</option>
								<option value="Dourado">Dourado</option>
								<option value="Vermelho">Vermelho</option>
								<option value="Azul">Azul</option>
								<option value="Rosa">Rosa</option>
							</select>
							<label>Cor</label>
						</div>

						<div class="input-field col s2">
							<input value="{{{ isset($produto->alerta_vencimento) ? $produto->alerta_vencimento : old('alerta_vencimento') }}}" id="alerta_vencimento" name="alerta_vencimento" type="text" class="validate">
							<label for="value_sale">Alerta de Venc. (Dias)</label>
							
						</div>
					</div>

					<input type="hidden" name="_token" value="{{ csrf_token() }}">
				</section>

				

				<section class="section-2">
					<div class="row">
						<div class="input-field col s3">
							<select id="unidade_compra" name="unidade_compra">
								@foreach($unidadesDeMedida as $u)
								<option @if(isset($produto))
								@if($u == $produto->unidade_compra)
								selected
								@endif
								@else
								@if($u == 'UNID') 
								selected 
								@endif 
								@endif value="{{$u}}">{{$u}}</option>
								@endforeach
							</select>
							<label for="unidade_compra">Unidade de compra *</label>
						</div>
						<div class="input-field col s2" id="conversao" style="display: none">
							<input value="{{{ isset($produto->conversao_unitaria) ? $produto->conversao_unitaria : old('conversao_unitaria') }}}" id="conversao_unitaria" name="conversao_unitaria" type="text" class="validate">
							<label for="conversao_unitaria">Conversão Unitária</label>

							@if($errors->has('conversao_unitaria'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('conversao_unitaria') }}</span>
							</div>
							@endif
						</div>

						<div class="input-field col s3">
							<select id="unidade_venda" name="unidade_venda">
								@foreach($unidadesDeMedida as $u)
								<option @if(isset($produto))
								@if($u == $produto->unidade_venda)
								selected
								@endif
								@else
								@if($u == 'UNID') 
								selected 
								@endif 
								@endif value="{{$u}}">{{$u}}</option>
								@endforeach
							</select>
							<label>Unidade de venda *</label>
						</div>
					</div>

					<div class="row">
						<div class="input-field col s2">
							<input value="{{{ isset($produto->NCM) ? $produto->NCM : old('NCM') }}}" id="ncm" name="NCM" type="text" class="validate">
							<label for="NCM">NCM *</label>

							@if($errors->has('NCM'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('NCM') }}</span>
							</div>
							@endif

						</div>

						<div class="input-field col s2">
							<input value="{{{ isset($produto->CEST) ? $produto->CEST : old('CEST') }}}" id="cest" name="CEST" type="text" class="validate">
							<label for="CEST">CEST</label>

							@if($errors->has('CEST'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('CEST') }}</span>
							</div>
							@endif

						</div>

					</div>

					<div class="row">


						<div class="input-field col s8">
							
							<select name="CST_CSOSN">
								@foreach($listaCSTCSOSN as $key => $c)
								<option value="{{$key}}"
								@if($config != null)
									@if(isset($produto))
										@if($key == $produto->CST_CSOSN)
										selected
										@endif
									@else
									@if($key == $config->CST_CSOSN_padrao)
									selected
									@endif
								@endif

								@endif
								>{{$key}} - {{$c}}</option>
								@endforeach
							</select>

							<label for="CEST">CST/CSOSN *</label>

						</div>
					</div>

					<div class="row">
						<div class="input-field col s5">
							
							<select name="CST_PIS">
								@foreach($listaCST_PIS_COFINS as $key => $c)
								<option value="{{$key}}"
								@if($config != null)
								@if(isset($produto))
								@if($key == $produto->CST_PIS)
								selected
								@endif
								@else
								@if($key == $config->CST_PIS_padrao)
								selected
								@endif
								@endif

								@endif
								>{{$key}} - {{$c}}</option>
								@endforeach
							</select>

							<label for="CEST">CST PIS *</label>

						</div>

						<div class="input-field col s5">
							
							<select name="CST_COFINS">
								@foreach($listaCST_PIS_COFINS as $key => $c)
								<option value="{{$key}}"
								@if($config != null)
								@if(isset($produto))
								@if($key == $produto->CST_COFINS)
								selected
								@endif
								@else
								@if($key == $config->CST_COFINS_padrao)
								selected
								@endif
								@endif

								@endif
								>{{$key}} - {{$c}}</option>
								@endforeach
							</select>

							<label for="CEST">CST COFINS *</label>

						</div>
					</div>

					<div class="row">
						<div class="input-field col s5">
							
							<select name="CST_IPI">
								@foreach($listaCST_IPI as $key => $c)
								<option value="{{$key}}"
								@if($config != null)
								@if(isset($produto))
								@if($key == $produto->CST_IPI)
								selected
								@endif
								@else
								@if($key == $config->CST_IPI_padrao)
								selected
								@endif
								@endif

								@endif
								>{{$key}} - {{$c}}</option>
								@endforeach
							</select>

							<label for="CEST">CST IPI *</label>

						</div>
					</div>


					<div class="row">
						<div class="input-field col s3">
							<input value="{{{ isset($produto->codBarras) ? $produto->codBarras : old('codBarras') }}}" id="codBarras" name="codBarras" type="text" class="validate">
							<label for="codBarras">Código de Barras EAN13</label>

							@if($errors->has('codBarras'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('codBarras') }}</span>
							</div>
							@endif

						</div> 

						<div class="input-field col s2">
							<input value="{{{ isset($produto->CFOP_saida_estadual) ? $produto->CFOP_saida_estadual : $natureza->CFOP_saida_estadual}}}" id="CFOP_saida_estadual" name="CFOP_saida_estadual" type="text" class="validate">
							<label for="CFOP_saida_estadual">CFOP saida interno *</label>

							@if($errors->has('CFOP_saida_estadual'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('CFOP_saida_estadual') }}</span>
							</div>
							@endif

						</div> 

						<div class="input-field col s2">
							<input value="{{{ isset($produto->CFOP_saida_inter_estadual) ? $produto->CFOP_saida_inter_estadual : $natureza->CFOP_saida_inter_estadual}}}" id="CFOP_saida_inter_estadual" name="CFOP_saida_inter_estadual" type="text" class="validate">
							<label for="CFOP_saida_inter_estadual">CFOP saida externo *</label>

							@if($errors->has('CFOP_saida_inter_estadual'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('CFOP_saida_inter_estadual') }}</span>
							</div>
							@endif

						</div> 


					</div>

					<div class="row">
						<div class="input-field col s2">
							<input value="{{{ isset($produto->perc_icms) ? $produto->perc_icms : $tributacao->icms}}}" id="perc_icms" name="perc_icms" type="text" class="validate imposto">
							<label for="perc_icms">%ICMS *</label>

							@if($errors->has('perc_icms'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('perc_icms') }}</span>
							</div>
							@endif

						</div> 
						<div class="input-field col s2">
							<input value="{{{ isset($produto->perc_pis) ? $produto->perc_pis : $tributacao->pis }}}" id="perc_pis" name="perc_pis" type="text" class="validate imposto">
							<label for="perc_pis">%PIS *</label>

							@if($errors->has('perc_pis'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('perc_pis') }}</span>
							</div>
							@endif

						</div> 
						<div class="input-field col s2">
							<input value="{{{ isset($produto->perc_cofins) ? $produto->perc_cofins : $tributacao->cofins }}}" id="perc_cofins" name="perc_cofins" type="text" class="validate imposto">
							<label for="perc_cofins">%COFINS *</label>

							@if($errors->has('perc_cofins'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('perc_cofins') }}</span>
							</div>
							@endif

						</div> 
						<div class="input-field col s2">
							<input value="{{{ isset($produto->perc_ipi) ? $produto->perc_ipi : $tributacao->ipi }}}" id="perc_ipi" name="perc_ipi" type="text" class="validate imposto">
							<label for="perc_ipi">%IPI *</label>

							@if($errors->has('perc_ipi'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('perc_ipi') }}</span>
							</div>
							@endif

						</div> 

						
					</div>

					<div class="row">
						<div class="input-field col s2">
							<input value="{{{ isset($produto->perc_iss) ? $produto->perc_iss : '0.00' }}}" id="perc_iss" name="perc_iss" type="text" class="validate imposto">
							<label for="perc_iss">%ISS</label>

							@if($errors->has('perc_iss'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('perc_iss') }}</span>
							</div>
							@endif

						</div> 

						<div class="input-field col s2">
							<input value="{{{ isset($produto->cListServ) ? $produto->cListServ : '' }}}" id="cListServ" name="cListServ" type="text" class="validate cListServ">
							<label for="cListServ">Cod Lista Serviço (ISS)</label>

							@if($errors->has('cListServ'))
							<div class="center-align red lighten-2">
								<span class="white-text">{{ $errors->first('cListServ') }}</span>
							</div>
							@endif

						</div> 
					</div>
					

					<div class="row">

						<div class="input-field col s6">
							<select name="anp">
								<option value="">--</option>
								@foreach($anps as $key => $a)
								<option value="{{$key}}"
								@isset($produto->codigo_anp)
								@if($key == $produto->codigo_anp)
								selected=""
								@endif
								@endisset >[{{$key}}] - {{$a}}</option>

								@endforeach
							</select>
							<label>Identificaçao ANP</label>

						</div> 
					</div>

					<div class="row">
						<div class="col s6">

							<div class="file-field input-field">

								<div class="btn black">
									<span>Imagem</span>
									<input value="{{{old('path') }}}" name="file" accept=".png, .jpg, .jpeg" type="file">

								</div>
								<div class="file-path-wrapper">
									<input class="file-path validate" type="text">
								</div>

							</div>
						</div>
						@if(isset($produto) && $produto->imagem)
						<div class="col s6">
							<img src="/imgs_produtos/{{$produto->imagem}}">
							<span>Imagem do produto</span>
						</div>
						@endif
					</div>

					<div class="row">
						<div class="col s2">
							<label>Composto</label>

							<div class="switch">
								<label class="">
									Não
									<input @if(isset($produto->composto) && $produto->composto) checked @endisset value="true" name="composto" class="red-text" type="checkbox">
									<span class="lever"></span>
									Sim
								</label>
							</div>
						</div>

						<div class="col s10">
							<p class="red-text">*Produzido no estabelecimento composto de outros produtos já cadastrados, deverá ser criado uma receita para redução de estoque. </p>
						</div>
					</div>

					@if(getenv('DELIVERY') == 1)
					<div class="row">
						<div class="col s2">
							<label>Atribuir ao Delivery</label>

							<div class="switch">
								<label class="">
									Não
									<input @if(isset($produto->delivery) && $produto->delivery) checked @endisset value="true" name="atribuir_delivery" class="red-text" type="checkbox" id="atribuir_delivery">
									<span class="lever"></span>
									Sim
								</label>
							</div>
						</div>
					</div>
					@endif
					@if(getenv('DELIVERY') == 1)
					<div id="delivery" style="display: none">

						<div class="card">
							<div class="card-content">
								<div class="row">
									<h5 class="center-align">DELIVERY</h5>
									<div class="col s2">
										<label>Destaque</label>

										<div class="switch">
											<label class="">
												Não
												<input @if(isset($produto->delivery->destaque) && $produto->delivery->destaque) checked @endisset value="true" name="destaque" class="red-text" type="checkbox">
												<span class="lever"></span>
												Sim
											</label>
										</div>
									</div>

									<div class="input-field col s3">
										<input type="text" value="{{{ isset($produto->delivery->limite_diario) ? $produto->delivery->limite_diario : old('valor') }}}" id="limite_diario" name="limite_diario">
										<label>Limite Diário de venda *</label>

										@if($errors->has('limite_diario'))
										<div class="center-align red lighten-2">
											<span class="white-text">{{ $errors->first('limite_diario') }}</span>
										</div>
										@endif
									</div>

									<div class="input-field col s3">

										<select name="categoria_delivery_id">
											@foreach($categoriasDelivery as $cat)
											<option value="{{$cat->id}}"
												@isset($produto->delivery)
												@if($cat->id == $produto->delivery->categoria_id)
												selected=""
												@endif
												@endisset >{{$cat->nome}}</option>

												@endforeach
											</select>
											<label>Categoria de Delivery</label>

										</div>
									</div>




									<div class="row">
										<div class="input-field col s6">
											<textarea name="descricao" id="descricao" class="materialize-textarea">{{{ isset($produto->delivery->descricao) ? $produto->delivery->descricao : old('descricao') }}}</textarea>
											<label for="descricao">Descrição</label>

											@if($errors->has('descricao'))
											<div class="center-align red lighten-2">
												<span class="white-text">{{ $errors->first('descricao') }}</span>
											</div>
											@endif
										</div>
										<div class="input-field col s6">
											<textarea name="ingredientes" id="ingredientes" class="materialize-textarea">{{{ isset($produto->delivery->ingredientes) ? $produto->delivery->ingredientes : old('ingredientes') }}}</textarea>
											<label for="ingredientes">Ingredientes</label>

											@if($errors->has('ingredientes'))
											<div class="center-align red lighten-2">
												<span class="white-text">{{ $errors->first('ingredientes') }}</span>
											</div>
											@endif
										</div>
									</div>
								</div>
							</div>
						</div>
						@endif


					</section>

					<br>
					<div class="row">
						<a class="btn-large red" href="/produtos">Cancelar</a>
						<input type="submit" value="Salvar" class="btn-large green accent-3">
					</div>
				</form>
			</div>
		</div>
		@endsection