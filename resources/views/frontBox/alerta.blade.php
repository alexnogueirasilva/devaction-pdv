@extends('default.layout')
@section('content')

<div class="row">
	<div class="col s12">
		<h5>Para acessar o PDV é necessario ter cadastrado:</h5>
		<h4>* Emitente fiscal com certificado digital</h4>
		<h4>* Natureza de operação cadastrado</h4>
		<h4>* Categorias</h4>
		<h4>* Produtos</h4>
		<h4>* Tributção</h4>

		<div class="row">
			@if(count($naturezas) == 0)
			<a href="/naturezaOperacao" class="btn-large">ir para naturezas de operação</a>
			@endif

			@if($config == null)
			<a href="/configNF" class="btn-large">ir para emitente fiscal</a>
			@endif

			@if($tributacao == null)
			<a href="/tributos" class="btn-large">ir para tributação</a>
			@endif

			@if($categorias == 0)
			<a href="/categorias" class="btn-large">ir para categorias</a>
			@endif

			@if($produtos == 0)
			<a href="/produtos" class="btn-large">ir para produtos</a>
			@endif

			
		</div>
	</div>
</div>
@endsection	