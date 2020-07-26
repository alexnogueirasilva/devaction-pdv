<?php

use Illuminate\Http\Request;

Route::group(['prefix' => 'appUser'],function(){
	Route::post('/signup', 'AppUserController@signup');
	Route::post('/login', 'AppUserController@login');
	Route::post('/novoEndereco', 'AppUserController@novoEndereco')->middleware('token');
	Route::get('/testeConn', 'AppUserController@testeConn');
	Route::get('/enderecos', 'AppUserController@enderecos')->middleware('token');

	Route::post('/saveToken', 'AppUserController@saveToken');
	Route::post('/atualizaToken', 'AppUserController@atualizaToken');
	Route::post('/appComToken', 'AppUserController@appComToken');
	Route::post('/refreshToken', 'AppUserController@refreshToken');
	Route::post('/validaToken', 'AppUserController@validaToken');
	Route::post('/validaCupom', 'AppUserController@validaCupom')->middleware('token');
	Route::post('/redefinirSenha', 'AppUserController@redefinirSenha');
	
});

Route::group(['prefix' => 'appProduto'],function(){
	Route::get('/categorias/{usuario_id}', 'AppProdutoController@categorias');
	Route::get('/destaques/{usuario_id}', 'AppProdutoController@destaques');
	Route::get('/adicionais/{produto_id}', 'AppProdutoController@adicionais');

	Route::get('/pesquisaProduto', 'AppProdutoController@pesquisaProduto');
	
	Route::post('/favorito', 'AppProdutoController@favorito')->middleware('token');
	Route::post('/enviaProduto', 'AppProdutoController@enviaProduto')->middleware('token');

	Route::get('/tamanhosPizza', 'AppProdutoController@tamanhosPizza');
	Route::post('/pizzaValorPorTamanho', 'AppProdutoController@pizzaValorPorTamanho');
	Route::post('/saboresPorTamanho', 'AppProdutoController@saboresPorTamanho');
	Route::get('/dividePizza', 'ProdutoRestController@dividePizza');

});

Route::group(['prefix' => 'appCarrinho'],function(){
	Route::get('/index', 'AppCarrinhoController@index')->middleware('token');
	Route::get('/historico', 'AppCarrinhoController@historico')->middleware('token');
	Route::get('/itensCarrinho', 'AppCarrinhoController@itensCarrinho')->middleware('token');
	Route::post('/pedirNovamente', 'AppCarrinhoController@pedirNovamente')->middleware('token');
	Route::post('/removeItem', 'AppCarrinhoController@removeItem')->middleware('token');
	Route::get('/validaPedidoEmAberto', 'AppCarrinhoController@validaPedidoEmAberto')
	->middleware('token');
	Route::get('/valorEntrega', 'AppCarrinhoController@valorEntrega');
	Route::post('/finalizar', 'AppCarrinhoController@finalizar')->middleware('token');
	Route::get('/config', 'AppCarrinhoController@config');
	Route::post('/cancelar', 'AppCarrinhoController@cancelar')->middleware('token');
	Route::get('/funcionamento', 'AppCarrinhoController@funcionamento');

});

// App Gargom
Route::group(['prefix' => 'pedidoProduto'],function(){
	Route::get('/maisPedidos', 'ProdutoRestController@maisPedidos');
	Route::get('/adicionais', 'ProdutoRestController@adicionais');
	Route::get('/tamanhosPizza', 'ProdutoRestController@tamanhosPizza');
	Route::get('/saboresPorTamanho', 'ProdutoRestController@saboresPorTamanho');
	Route::get('/pizzaValorPorTamanho', 'ProdutoRestController@pizzaValorPorTamanho');
	Route::get('/pesquisaRest', 'ProdutoRestController@pesquisa');
	Route::get('/dividePizza', 'ProdutoRestController@dividePizza');

});

Route::group(['prefix' => 'pedidos'],function(){
	Route::get('/comandasAberta', 'PedidoRestController@comandasAberta');
	Route::get('/mesas', 'PedidoRestController@mesas');
	Route::get('/mesasTodas', 'PedidoRestController@mesasTodas');
	Route::get('/abrirComanda', 'PedidoRestController@abrirComanda');
	Route::get('/addProduto', 'PedidoRestController@addProduto');
	Route::get('/deleteItem', 'PedidoRestController@deleteItem');
	Route::get('/emAberto', 'PedidoRestController@emAberto');

});

//pagseguro

Route::group(['prefix' => '/pagseguro'], function(){
	Route::get('/getSessao', 'PagSeguroController@getSessao');
	Route::get('/getFuncionamento', 'PagSeguroController@getFuncionamento');
	Route::post('/cartoes', 'PagSeguroController@cartoes')->middleware('token');
	
	Route::post('/efetuaPagamento', 'PagSeguroController@efetuaPagamentoApp');
	Route::get('/consultaJS', 'PagSeguroController@consultaJS');
});

//fim pagseguro

Route::group(['prefix' => 'appFiscal'],function(){

	Route::group(['prefix' => 'clientes'],function(){
		Route::get('/', 'AppFiscal\\ClienteController@clientes');
		Route::post('/salvar', 'AppFiscal\\ClienteController@salvar');

	});
	Route::get('/cidades', 'AppFiscal\\ClienteController@cidades');
});






