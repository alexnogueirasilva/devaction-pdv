@extends('default.layout')
@section('content')
<div class="row">
	<div class="col s12">
		<h4>{{{ isset($usuario) ? "Editar": "Cadastrar" }}} Usuario</h4>
		<form method="post" action="{{{ isset($usuario) ? '/usuarios/update': '/usuarios/save' }}}">
			<input type="hidden" name="id" value="{{{ isset($usuario->id) ? $usuario->id : 0 }}}">

			<div class="row">
				<div class="input-field col s6">
					<input value="{{{ isset($usuario->nome) ? $usuario->nome : old('nome') }}}" id="nome" name="nome" type="text" class="validate">
					<label for="nome">Nome</label>

					@if($errors->has('nome'))
					<div class="center-align red lighten-2">
						<span class="white-text">{{ $errors->first('nome') }}</span>
					</div>
					@endif

				</div>
			</div>

			<div class="row">
				<div class="input-field col s6">
					<input value="{{{ isset($usuario->login) ? $usuario->login : old('login') }}}" id="login" name="login" type="text" class="validate">
					<label for="login">Login</label>

					@if($errors->has('login'))
					<div class="center-align red lighten-2">
						<span class="white-text">{{ $errors->first('login') }}</span>
					</div>
					@endif

				</div>
			</div>

			<div class="row">
				<div class="input-field col s3">
					<input value="{{old('senha')}}" id="senha" name="senha" type="password" class="validate">
					<label for="senha">Senha</label>

					@if($errors->has('senha'))
					<div class="center-align red lighten-2">
						<span class="white-text">{{ $errors->first('senha') }}</span>
					</div>
					@endif
				</div>

			</div>

			<div class="row">
				<div class="col s2">
					<label>USUARIO ADM</label>

					<div class="switch">
						<label class="">
							Não
							<input id="adm" @if(isset($usuario->adm) && $usuario->adm) checked @endisset name="adm" class="red-text" type="checkbox">
							<span class="lever"></span>
							Sim
						</label>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col s2">
					<p>
						<input @if(isset($usuario) && $usuario->acesso_cliente) checked @endisset type="checkbox" class="check" id="acesso_cliente" name="acesso_cliente" />
						<label for="acesso_cliente">Acesso Cliente</label>
					</p>
				</div>
				<div class="col s2">
					<p>
						<input @if(isset($usuario) && $usuario->acesso_fornecedor) checked @endisset type="checkbox" class="check" name="acesso_fornecedor" id="acesso_fornecedor" />
						<label for="acesso_fornecedor">Acesso Fornecedor</label>
					</p>
				</div>
				<div class="col s2">
					<p>
						<input @if(isset($usuario) && $usuario->acesso_produto) checked @endisset type="checkbox" class="check" name="acesso_produto" id="acesso_produto" />
						<label for="acesso_produto">Acesso Produto</label>
					</p>
				</div>
				<div class="col s2">
					<p>
						<input @if(isset($usuario) && $usuario->acesso_financeiro) checked @endisset type="checkbox" class="check" name="acesso_financeiro" id="acesso_financeiro" />
						<label for="acesso_financeiro">Acesso Financeiro</label>
					</p>
				</div>
				<div class="col s2">
					<p>
						<input @if(isset($usuario) && $usuario->venda_nao_fiscal) checked @endisset type="checkbox" class="check" name="venda_nao_fiscal" id="venda_nao_fiscal" />
						<label for="venda_nao_fiscal">Venda Não Fiscal</label>
					</p>
				</div>
			</div>

			<div class="row">
				<div class="col s2">
					<p>
						<input @if(isset($usuario) && $usuario->acesso_caixa) checked @endisset type="checkbox" class="check" name="acesso_caixa" id="acesso_caixa" />
						<label for="acesso_caixa">Acesso Caixa</label>
					</p>
				</div>
				<div class="col s2">
					<p>
						<input @if(isset($usuario) && $usuario->acesso_estoque) checked @endisset type="checkbox" class="check" name="acesso_estoque" id="acesso_estoque" />
						<label for="acesso_estoque">Acesso Estoque</label>
					</p>
				</div>
				<div class="col s2">
					<p>
						<input @if(isset($usuario) && $usuario->acesso_compra) checked @endisset type="checkbox" class="check" name="acesso_compra" id="acesso_compra" />
						<label for="acesso_compra">Acesso Compra</label>
					</p>
				</div>
				<div class="col s2">
					<p>
						<input @if(isset($usuario) && $usuario->acesso_fiscal) checked @endisset type="checkbox" class="check" name="acesso_fiscal" id="acesso_fiscal" />
						<label for="acesso_fiscal">Acesso Fiscal</label>
					</p>
				</div>

				
			</div>

			<input type="hidden" name="_token" value="{{ csrf_token() }}">


			<br>
			<div class="row">
				<a class="btn-large red lighten-2" href="/usuarios">Cancelar</a>
				<input type="submit" value="Salvar" class="btn-large green accent-3">
			</div>
		</form>
	</div>
</div>
@endsection