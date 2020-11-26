$(function () {

	getClientes(function(data){
		$('input.autocomplete-cliente').autocomplete({
			data: data,
			limit: 20, 
			onAutocomplete: function(val) {
				
			},
			minLength: 1,
		});
	});


	getProdutos(function(data){
		$('input.autocomplete-produto').autocomplete({
			data: data,
			limit: 20, 
			onAutocomplete: function(val) {
				
			},
			minLength: 1,
		});
	});

});

function getProdutos(data){
	$.ajax
	({
		type: 'GET',
		url: path + 'produtos/all',
		dataType: 'json',
		success: function(e){
			data(e)

		}, error: function(e){
			console.log(e)
		}

	});
}

function getClientes(data){
	$.ajax
	({
		type: 'GET',
		url: path + 'clientes/all',
		dataType: 'json',
		success: function(e){
			data(e)
		}, error: function(e){
			console.log(e)
		}

	});
}

