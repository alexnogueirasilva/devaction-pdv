
var ITENS = [];
var TOTAL = 0;

$(function () {
	getFornecedores(function(data){
		$('input.autocomplete-fornecedor').autocomplete({
			data: data,
			limit: 20, 
			onAutocomplete: function(val) {
				var fornecedor = $('#autocomplete-fornecedor').val().split('-');

			},
			minLength: 1,
		});
	});


	getProdutos(function(data){
		$('input.autocomplete-produto').autocomplete({
			data: data,
			limit: 20, 
			onAutocomplete: function(val) {
				console.log(val)
			},
			minLength: 1,
		});
	});
});


function getFornecedores(data){
	$.ajax
	({
		type: 'GET',
		url: path + 'fornecedores/all',
		dataType: 'json',
		success: function(e){
			data(e)
		}, error: function(e){
			console.log(e)
		}

	});
}

function getFornecedor(id, data){
	$.ajax
	({
		type: 'GET',
		url: path + 'fornecedores/find/'+id,
		dataType: 'json',
		success: function(e){
			data(e)

		}, error: function(e){
			console.log(e)
		}

	});
}

function getProdutos(data){
	$.ajax
	({
		type: 'GET',
		url: path + 'produtos/naoComposto',
		dataType: 'json',
		success: function(e){
			data(e)

		}, error: function(e){
			console.log(e)
		}

	});
}

$('#addProd').click(() => {
	let prod = $('#autocomplete-produto').val().split('-');
	let codigo = prod[0];
	let nome = prod[1];
	let quantidade = $('#quantidade').val();

	if(codigo.length > 0 && nome.length > 0 && quantidade.length > 0 && parseFloat(quantidade.replace(',','.')) ) {

		addItemTable(codigo, nome, quantidade);
	}else{
		Materialize.toast('Informe corretamente os campos para continuar!', 4000)
	}
});

function addItemTable(codigo, nome, quantidade){
	if(!verificaProdutoIncluso()) {
		TOTAL += parseFloat(quantidade.replace(',','.'));
		console.log(TOTAL)
		ITENS.push({id: (ITENS.length+1), codigo: codigo, nome: nome, 
			quantidade: quantidade})
	// apagar linhas tabela
	$('#body').html("");
	
	let t = montaTabela();
	$('#total_itens').html(TOTAL)
	$('#body').html(t)

	$('#autocomplete-produto').val('');
	$('#quantidade').val('');
}
}

function montaTabela(){
	let t = ""; 
	ITENS.map((v) => {
		t += "<tr>";
		t += "<td>"+v.id+"</td>";
		t += "<td class='cod'>"+v.codigo+"</td>";
		t += "<td>"+v.nome+"</td>";
		t += "<td>"+v.quantidade+"</td>";
		t += "<td><a href='#prod tbody' onclick='deleteItem("+v.id+")'>"
		t += "<i class=' material-icons red-text'>delete</i></a></td>";
		t+= "</tr>";
	});
	return t
}

function verificaProdutoIncluso(){
	if(ITENS.length == 0) return false;
	if($('#body tr').length == 0) return false;
	let cod = $('#autocomplete-produto').val().split('-')[0];
	let duplicidade = false;

	ITENS.map((v) => {
		if(v.codigo == cod){
			duplicidade = true;
		}
	})

	let c;
	if(duplicidade) c = !confirm('Produto já adicionado, deseja incluir novamente?');
	else c = false;
	console.log(c)
	return c;
}

function deleteItem(id){
	let temp = [];
	ITENS.map((v) => {
		if(v.id != id){
			temp.push(v)
		}else{
			TOTAL -= (v.quantidade.replace(',','.'));
		}
	});
	ITENS = temp;
	let t = montaTabela(); // para remover
	$('#body').html(t)
	$('#total_itens').html(TOTAL)
}


$('#salvar-cotacao').click(() => {
	valida((msg) => {
		if(msg == ""){
			let fornecedor = $('#autocomplete-fornecedor').val().split('-')[0];
			let js = {
				obsevacao: $('#obs').val(),
				referencia: $('#referencia').val(),
				itens: ITENS,
				fornecedor: parseInt(fornecedor),
			}
			let token = $('#_token').val();
			$.ajax
			({
				type: 'POST',
				data: {
					cotacao: js,
					_token: token
				},
				url: path + 'cotacao/salvar',
				dataType: 'json',
				success: function(e){
					console.log(e)
					sucesso();

				}, error: function(e){
					console.log(e)
				}
			});
		}else{
			Materialize.toast(msg, 4000)

		}
	})

})

function valida(call){
	let msg = "";

	let fornecedor = $('#autocomplete-fornecedor').val().split('-')[0];
	if(!fornecedor){
		msg = "Informe o Fornecedor";
	}

	if(ITENS.length == 0){
		msg = "Informe os Itens";
	}

	call(msg)
}

function sucesso(){
	$('#content').css('display', 'none');
	$('#anime').css('display', 'block');
	setTimeout(() => {
		location.href = path+'cotacao';
	}, 4000)
}

