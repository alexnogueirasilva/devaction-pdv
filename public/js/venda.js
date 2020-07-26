
var ITENS = [];
var FATURA = [];
var TOTAL = 0;
var TOTALQTD = 0;
var CLIENTE = null;
var receberContas = [];

$(function () {

	let itensDeCredito = $('#itens_credito').val();
	let cli = $('#cliente_crediario').val();
	if(itensDeCredito){
		let js = JSON.parse(itensDeCredito);
		let obs = "Correspondente as compras numero: ";
		let anterior = '';
		js.map((v) => {
			console.log(v)
			addItemDeCredito(v)
			receberContas.push(v.id);
			if(v.id != anterior)
				obs += v.id + ",";
			anterior = v.id;
		})
		obs = obs.substring(0, obs.length - 1)
		$('#obs').val(obs)
	}

	if(cli){
		CLIENTE = JSON.parse(cli);
		console.log(CLIENTE)
	}

	$("#formaPagamento option.teste").attr('disabled', 'false');
	getClientes(function(data){
		$('input.autocomplete-cliente').autocomplete({
			data: data,
			limit: 20, 
			onAutocomplete: function(val) {
				var cliente = $('#autocomplete-cliente').val().split('-');
				getCliente(cliente[0], (d) => {
					console.log(d)
					
					$('#cliente').css('display', 'block');
					$('#razao_social').html(d.razao_social)
					$('#nome_fantasia').html(d.nome_fantasia)
					$('#logradouro').html(d.rua)
					$('#numero').html(d.numero)

					$('#cnpj').html(d.cpf_cnpj)
					$('#ie').html(d.ie_rg)
					$('#fone').html(d.telefone)
					$('#cidade').html(d.cidade.nome)
					$('#limite').html(d.limite_venda)
					console.log("limite: " + d.limite_venda)
					CLIENTE = d;
					if(d.limite_venda <= 0){
						$('#col-credito').css('display', 'none');
						$('#sem_crediario').css('display','block');
					}else{
						$('#col-credito').css('display', 'block');
						$('#sem_crediario').css('display','none');
					}
					habilitaBtnSalarVenda();
				})
			},
			minLength: 1,
		});
	});


	getTransportadoras(function(data){
		$('input.autocomplete-transportadora').autocomplete({
			data: data,
			limit: 20, 
			onAutocomplete: function(val) {
				var transportadora = $('#autocomplete-transportadora').val().split('-');
				console.log(transportadora)
				getTransportadora(transportadora[0], (d) => {
					console.log(d)
					if(d){
						$('#transp-selecionada').css('display', 'block');
						$('#razao_social_transp').html(d.razao_social)
						$('#logradouro_transp').html(d.logradouro)
						$('#cnpj_transp').html(d.cnpj_cpf)
						$('#cidade_transp').html(d.cidade.nome)
					}
					

				})
			},
			minLength: 1,
		});
	});

	getProdutos(function(data){
		$('input.autocomplete-produto').autocomplete({
			data: data,
			limit: 20, 
			onAutocomplete: function(val) {
				$('#preloader1').css('display', 'block');
				var prod = $('#autocomplete-produto').val().split('-');
				console.log(prod)
				getProduto(prod[0], (d) => {
					console.log(d)

					$('#valor').val(d.valor_venda.replace(".", ','))
					$('#quantidade').val('1,000')
					$('#preloader1').css('display', 'none');
					calcSubtotal();

				})
			},
			minLength: 1,
		});
	});
});

function addItemDeCredito(item){

	let codigo = item.produto_id;
	let nome = item.nome;
	let quantidade = item.quantidade;
	let valor = item.valor;
	
	addItemTable(codigo, nome, quantidade, valor);
}


$('#addProd').click(() => {
	let prod = $('#autocomplete-produto').val().split('-');
	let codigo = prod[0];
	let nome = prod[1];
	let quantidade = $('#quantidade').val();
	let valor = $('#valor').val();
	if(codigo.length > 0 && nome.length > 0 && quantidade.length > 0 
		&& parseFloat(quantidade.replace(',','.')) && 
		valor.length > 0 && parseFloat(valor.replace(',','.')) > 0) {
		if(valor.length > 6) valor = valor.replace(".", "");
	valor = valor.replace(",", ".");

	addItemTable(codigo, nome, quantidade, valor);
}else{
	Materialize.toast('Informe corretamente os campos para continuar!', 4000)
}
})

function formatReal(v)
{
	return v.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
}

function atualizaTotal(){
	$('#totalNF').html(formatReal(TOTAL));
	$('#soma-quantidade').html(TOTALQTD);
}

function verificaProdutoIncluso(){
	if(ITENS.length == 0) return false;
	if($('#prod tbody tr').length == 0) return false;
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

function montaTabela(){
	let t = ""; 
	ITENS.map((v) => {
		t += "<tr>";
		t += "<td>"+v.id+"</td>";
		t += "<td class='cod'>"+v.codigo+"</td>";
		t += "<td>"+v.nome+"</td>";
		t += "<td>"+v.quantidade+"</td>";
		t += "<td>"+v.valor+"</td>";
		t += "<td>"+formatReal(v.valor.replace(',','.')*v.quantidade.replace(',','.'))+"</td>";
		t += "<td><a href='#prod tbody' onclick='deleteItem("+v.id+")'>"
		t += "<i class=' material-icons red-text'>delete</i></a></td>";
		t+= "</tr>";
	});
	return t
}

function refatoreItens(){
	let cont = 1;
	let temp = [];
	ITENS.map((v) => {
		v.id = cont;
		temp.push(v)
		cont++;
	})
	console.log(temp)
	ITENS = temp;
}

function maskMoney(v){
	return v.toFixed(2);
}

$('#valor').on('keyup', () => {
	calcSubtotal()
})

function calcSubtotal(){
	let quantidade = $('#quantidade').val();
	let valor = $('#valor').val();
	let subtotal = parseFloat(valor.replace(',','.'))*(quantidade.replace(',','.'));
	console.log(subtotal)
	let sub = maskMoney(subtotal)
	$('#subtotal').val(sub)
}

function addItemTable(codigo, nome, quantidade, valor){
	if(!verificaProdutoIncluso()) {
		limparDadosFatura();
		TOTAL += parseFloat(valor.replace(',','.'))*(quantidade.replace(',','.'));
		TOTAL = parseFloat(TOTAL.toFixed(2));
		TOTALQTD += parseFloat(quantidade.replace(',','.'));
		ITENS.push({id: (ITENS.length+1), codigo: codigo, nome: nome, 
			quantidade: quantidade, valor: valor})

	// apagar linhas tabela
	$('#prod tbody').html("");
	refatoreItens();

	
	atualizaTotal();
	limparCamposFormProd();
	let t = montaTabela();
	$('#prod tbody').html(t)
}
}

$('#delete-parcelas').click(() => {
	limparDadosFatura();
})

function deleteItem(id){
	let temp = [];
	ITENS.map((v) => {
		if(v.id != id){
			temp.push(v)
		}else{
			TOTAL -= parseFloat(v.valor.replace(',','.'))*(v.quantidade.replace(',','.'));
			TOTALQTD -= parseFloat(v.quantidade.replace(',','.'));
		}
	});
	ITENS = temp;
	refatoreItens()
	let t = montaTabela(); // para remover
	$('#prod tbody').html(t)

	atualizaTotal();
}

function limparCamposFormProd(){
	$('#autocomplete-produto').val('');
	$('#quantidade').val('0');
	$('#valor').val('0');
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

function getCliente(id, data){
	$.ajax
	({
		type: 'GET',
		url: path + 'clientes/find/'+id,
		dataType: 'json',
		success: function(e){
			data(e)

		}, error: function(e){
			console.log(e)
		}

	});
}


function getTransportadoras(data){
	$.ajax
	({
		type: 'GET',
		url: path + 'transportadoras/all',
		dataType: 'json',
		success: function(e){
			data(e)
		}, error: function(e){
			console.log(e)
		}

	});
}

function getTransportadora(id, data){
	$.ajax
	({
		type: 'GET',
		url: path + 'transportadoras/find/'+id,
		dataType: 'json',
		success: function(e){
			data(e)
		}, error: function(e){
			console.log(e)
		}

	});
}

$('#edit-cliente').click(() => {
	$('autocomplete-cliente').removeClass('disabled');
})

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

function getProduto(id, data){
	console.log(id)
	$.ajax
	({
		type: 'GET',
		url: path + 'produtos/getProduto/'+id,
		dataType: 'json',
		success: function(e){
			data(e)

		}, error: function(e){
			console.log(e)
		}

	});
}



// Pagamentos

$('#add-pag').click(() => {
	let qtdParcelas = $('#qtdParcelas').val();
	let desconto = $('#desconto').val();
	if(desconto.length == 0) desconto = 0;
	else desconto = desconto.replace(",", ".");

	if(!verificaValorMaiorQueTotal()){
		let data = $('#data').val();
		let valor = $('#valor_parcela').val();
		let cifrao = valor.substring(0, 2);
		if(cifrao == 'R$') valor = valor.substring(3, valor.length)
			if(data.length > 0 && valor.length > 0 && parseFloat(valor.replace(',','.')) > 0) {

				addpagamento(data, valor);

				if(qtdParcelas == FATURA.length+1){
					somaParcelas((v) => {
						let dif = (TOTAL - parseFloat(desconto)) - v;
						$('#valor_parcela').val(formatReal(dif))
					})
				}
			}else{
				Materialize.toast('Informe corretamente os campos para continuar!', 4000)
			}
		}
	})

function verificaValorMaiorQueTotal(data){
	let retorno;
	let valorParcela = $('#valor_parcela').val();
	let qtdParcelas = $('#qtdParcelas').val();
	let desconto = $('#desconto').val();
	
	if(desconto.length == 0) desconto = 0;
	else desconto = desconto.replace(',', '.');

	let cifrao = valorParcela.substring(0, 2);
	if(cifrao == 'R$') valorParcela = valorParcela.substring(3, valorParcela.length)

		console.log(valorParcela)

	if(valorParcela.length > 6){
		valorParcela = valorParcela.replace(".", "");
	}
	valorParcela = valorParcela.replace(",", ".");


	if(valorParcela <= 0){
		Materialize.toast('Valor deve ser maior que 0!', 4000)
		retorno = true;

	}

	else if(valorParcela > (TOTAL - parseFloat(desconto))){
		Materialize.toast('Valor da parcela maior que o total da venda!', 4000)
		retorno = true;
		
	}

	else if(qtdParcelas > 1){
		somaParcelas((v) => {
			console.log(v)
			// if(valorParcela.length > 6){
			// 	// valorParcela = valorParcela.replace('.', '')
			// 	valorParcela = valorParcela.replace(',', '.')
			// }else{
			// 	valorParcela = valorParcela.replace(',', '.')
			// }
			valorParcela = valorParcela.replace(',', '.')

			console.log(parseFloat(valorParcela))
			console.log(TOTAL)
			console.log(v)
			console.log(parseFloat(valorParcela))


			let parcelaMaisSoma = parseFloat((v+parseFloat(valorParcela)).toFixed(2));
			console.log(parcelaMaisSoma)
			

			//Valida Parcela maior que 1000

			if(parcelaMaisSoma > (TOTAL - parseFloat(desconto))){
				Materialize.toast('Valor ultrapassaou o total!', 4000)
				retorno = true;
			}
			else if(parcelaMaisSoma == (TOTAL  - parseFloat(desconto)) && (FATURA.length+1) < parseInt(qtdParcelas)){
				Materialize.toast('Respeite a quantidade de parcelas pré definido!', 4000)
				retorno = true;
				
			}
			else if(parcelaMaisSoma < (TOTAL  - parseFloat(desconto)) && 
				(FATURA.length+1) == parseInt(qtdParcelas)){
				Materialize.toast('Somátoria incorreta!', 4000)
			let dif = (TOTAL - parseFloat(desconto)) - v;
			$('#valor_parcela').val(formatReal(dif))
			retorno = true;

		}
		else{
			retorno = false;	
		}

	})
	}
	else{
		retorno = false;
	}

	return retorno;
}

function somaParcelas(call){
	let soma = 0;
	FATURA.map((v) => {
		console.log(v.valor)
		// if(v.valor.length > 6){
		// 	v = v.valor.replace('.','');
		// 	v = v.replace(',','.');
		// 	soma += parseFloat(v);

		// }else{
		// 	soma += parseFloat(v.valor.replace(',','.'));
		// }
		soma += parseFloat(v.valor.replace(',','.'));

	})
	call(soma)
}

function verificaValor(){
	console.log('verificando valor...')
	let soma = 0;
	FATURA.map((v) => {
		// if(v.valor.length > 6){
		// 	v = v.valor.replace('.','');
		// 	v = v.replace(',','.');
		// 	soma += parseFloat(v);

		// }else{
		// 	soma += parseFloat(v.valor.replace(',','.'));
		// }

		soma += parseFloat(v.valor.replace(',','.'));
	})

	let desconto = $('#desconto').val();
	if(desconto.length == 0) desconto = 0;
	else desconto = desconto.replace(",", ".");
	
	console.log(TOTAL)
	console.log("soma: "+ soma)
	if(soma >= (TOTAL - parseFloat(desconto))){
		$('#add-pag').addClass("disabled");
		// alert("Parcela de Pagamento OK...")
	}
}

function addpagamento(data, valor){
	let result = verificaProdutoIncluso();
	if(!result){
		if(valor.length > 6){
			valor = valor.replace(".", "");
		}
		valor = valor.replace(",", ".");

		FATURA.push({data: data, valor: valor, numero: (FATURA.length + 1)})

			$('#fatura tbody').html(""); // apagar linhas da tabela
			let t = ""; 
			FATURA.map((v) => {
				t += "<tr>";
				t += "<td class='numero'>"+v.numero+"</td>";
				t += "<td>"+v.data+"</td>";
				t += "<td>"+v.valor.replace(',','.')+"</td>";
				t+= "</tr>";
			});

			$('#fatura tbody').html(t)
			verificaValor();
		}
		habilitaBtnSalarVenda();
	}


	function limparDadosFatura(){
		$('#fatura tbody').html('')
		$("#data").val("");
		$("#valor_parcela").val("");
		$('#add-pag').removeClass("disabled");
		FATURA = [];

	}

	$('#qtdParcelas').on('keyup', () => {
		limparDadosFatura();
		if($("#qtdParcelas").val()){
			let desconto = $('#desconto').val();
			if(desconto.length == 0) desconto = 0;
			else desconto = desconto.replace(',', '.');

			let qtd = $("#qtdParcelas").val();
			// alert((TOTAL - parseFloat(desconto))/qtd)

			$('#valor_parcela').val(formatReal((TOTAL - parseFloat(desconto))/qtd));
		}
	})


	function habilitaBtnSalarVenda(){
		let desconto = $('#desconto').val();
		if(desconto.length == 0) desconto = 0;
		else desconto = desconto.replace(',', '.');
		
		if(ITENS.length > 0 && FATURA.length > 0 && (TOTAL - parseFloat(desconto)) > 0 && CLIENTE != null){
			$('#salvar-venda').removeClass('disabled')
			$('#salvar-orcamento').removeClass('disabled')
		}
	}

	function verificaLimite(call){
		$.ajax
		({
			type: 'GET',
			data: {
				id: parseInt(CLIENTE.id),
			},
			url: path + 'clientes/verificaLimite',
			dataType: 'json',
			success: function(e){
				call(e.limite_venda)
			}, error: function(e){
				call(false)
				$('#preloader2').css('display', 'none');
			}
		});
	}

	function validaFrete(call){
		let tipoFrete = $('#frete').val();
		if(tipoFrete != '9'){
			let placa = $('#placa').val();
			let valor = $('#valor_frete').val();
			if(placa.length == 8 && valor.length > 0 && 
				parseFloat(valor.replace(",", ".")) >= 0 && 
				$('#uf_placa').val() != '--'){
				call(true);
		}else{
			call(false);
		}
	}else{
		call(true);
	}
}

$('#frete').change(() => {
	if($('#frete').val() == '9'){

		$('#placa').attr('disabled', true)
		$('#valor_frete').attr('disabled', true)

	}else{
		$('#placa').removeAttr('disabled')
		$('#valor_frete').removeAttr('disabled')

	}
})

$('#desconto').on('keyup', () => {
	limparDadosFatura()
	let desconto = $('#desconto').val();
	if(TOTAL > 0){
		desconto = desconto.replace(",", ".");
		let t = parseFloat(TOTAL) - parseFloat(desconto)
		console.log(t)
	}else{
		alert("Adicione itens para despois informar o desconto")
		$('#desconto').val('')
	}
	//atualizaTotal();
});

$('#formaPagamento').change(() => {
	limparDadosFatura();
	let now = new Date();
	let data = (now.getDate() < 10 ? "0"+now.getDate() : now.getDate()) + 
	"/"+ ((now.getMonth()+1) < 10 ? "0" + (now.getMonth()+1) : (now.getMonth()+1)) + 
	"/" + now.getFullYear();

	var date = new Date(new Date().setDate(new Date().getDate() + 30));
	let data30 = (date.getDate() < 10 ? "0"+date.getDate() : date.getDate()) + 
	"/"+ ((date.getMonth()+1) < 10 ? "0" + (date.getMonth()+1) : (date.getMonth()+1)) + 
	"/" + date.getFullYear();

	let desconto = $('#desconto').val();
	desconto = desconto.replace(",", ".");
	if(desconto.length == 0) desconto = 0;

	$("#qtdParcelas").attr("disabled", true);
	$("#data").attr("disabled", true);
	$("#valor_parcela").attr("disabled", true);
	$("#qtdParcelas").val('1');
	if($('#formaPagamento').val() == 'a_vista'){
		$("#qtdParcelas").val(1)
		$('#valor_parcela').val(formatReal((TOTAL - parseFloat(desconto))));
		$('#data').val(data);
	}else if($('#formaPagamento').val() == '30_dias'){
		$("#qtdParcelas").val(1)
		$('#valor_parcela').val(formatReal((TOTAL - parseFloat(desconto))));
		$('#data').val(data30);
	}else if($('#formaPagamento').val() == 'personalizado'){
		$("#qtdParcelas").removeAttr("disabled");
		$("#data").removeAttr("disabled");
		$("#valor_parcela").removeAttr("disabled");
		$("#data").val("");
		$("#qtdParcelas").val(1)
		$("#valor_parcela").val(formatReal(TOTAL - parseFloat(desconto)));
	}
	else if($('#formaPagamento').val() == 'conta_crediario'){

		if(CLIENTE == null || CLIENTE.limite <= 0){
			Materialize.toast('Limite do cliente deve ser maior que Zero!', 4000)

		}else{

			$("#qtdParcelas").val(1);
			$("#data").val(data);
			$("#valor_parcela").val(formatReal(TOTAL - parseFloat(desconto)));
		}
	}
})



function salvarVenda(btnClick) {

	verificaLimite((limite) => {

		if(limite){

			validaFrete((validaFrete) => {
				if(validaFrete){
					$('#preloader2').css('display', 'block');

					let vol = {
						'especie': $('#especie').val(),
						'numeracaoVol': $('#numeracaoVol').val(),
						'qtdVol': $('#qtdVol').val(),
						'pesoL': $('#pesoL').val(),
						'pesoB': $('#pesoB').val(),
					}




					var transportadora = $('#autocomplete-transportadora').val().split('-');
					if($('#autocomplete-transportadora').val().length > 0 && transportadora.length > 0){
						transportadora = transportadora[0]
					}else{
						transportadora = null;
					}
					let js = {
						cliente: parseInt(CLIENTE.id),
						transportadora: transportadora,
						formaPagamento: $('#formaPagamento').val(),
						tipoPagamento: $('#tipoPagamento').val(),
						naturezaOp: parseInt($('#natureza').val()),
						frete: $('#frete').val(),
						placaVeiculo: $('#placa').val(),
						ufPlaca: $('#uf_placa').val(),
						valorFrete: $('#valor_frete').val(),
						itens: ITENS,
						fatura: FATURA,
						volume: vol,
						receberContas: receberContas,
						total: TOTAL,
						observacao: $('#obs').val(),
						desconto: $('#desconto').val(),
						btn: btnClick
					}
					let token = $('#_token').val();
					console.log(js)
					$.ajax
					({
						type: 'POST',
						data: {
							venda: js,
							_token: token
						},
						url: path + 'vendas/salvar',
						dataType: 'json',
						success: function(e){
							$('#preloader2').css('display', 'none');
							sucesso(e)

						}, error: function(e){
							console.log(e)
							$('#preloader2').css('display', 'none');
						}
					});

					if(btnClick == 'cp_fiscal'){
					// eviar NF
				}
			}else{
				Materialize.toast('Informe placa e valor de frete!', 4000)
			}
		})
		}else{

			Materialize.toast('Erro Limite!', 4000)

		}
	})

}

function salvarOrcamento() {

	verificaLimite((limite) => {

		if(limite){

			validaFrete((validaFrete) => {
				if(validaFrete){
					$('#preloader2').css('display', 'block');

					let vol = {
						'especie': $('#especie').val(),
						'numeracaoVol': $('#numeracaoVol').val(),
						'qtdVol': $('#qtdVol').val(),
						'pesoL': $('#pesoL').val(),
						'pesoB': $('#pesoB').val(),
					}




					var transportadora = $('#autocomplete-transportadora').val().split('-');
					if($('#autocomplete-transportadora').val().length > 0 && transportadora.length > 0){
						transportadora = transportadora[0]
					}else{
						transportadora = null;
					}
					let js = {
						cliente: parseInt(CLIENTE.id),
						transportadora: transportadora,
						formaPagamento: $('#formaPagamento').val(),
						tipoPagamento: $('#tipoPagamento').val(),
						naturezaOp: parseInt($('#natureza').val()),
						frete: $('#frete').val(),
						placaVeiculo: $('#placa').val(),
						ufPlaca: $('#uf_placa').val(),
						valorFrete: $('#valor_frete').val(),
						itens: ITENS,
						fatura: FATURA,
						volume: vol,
						receberContas: receberContas,
						total: TOTAL,
						observacao: $('#obs').val(),
						desconto: $('#desconto').val()
					}
					let token = $('#_token').val();
					console.log(js)
					$.ajax
					({
						type: 'POST',
						data: {
							venda: js,
							_token: token
						},
						url: path + 'orcamentoVenda/salvar',
						dataType: 'json',
						success: function(e){
							$('#preloader2').css('display', 'none');
							sucesso2(e)

						}, error: function(e){
							console.log(e)
							$('#preloader2').css('display', 'none');
						}
					});

					if(btnClick == 'cp_fiscal'){
					// eviar NF
				}
			}else{
				Materialize.toast('Informe placa e valor de frete!', 4000)
			}
		})
		}else{

			Materialize.toast('Erro Limite!', 4000)

		}
	})

}


function sucesso(){
	$('#content').css('display', 'none');
	$('#anime').css('display', 'block');
	setTimeout(() => {
		location.href = path+'vendas/lista';
	}, 4000)
}

function sucesso2(){
	$('#content').css('display', 'none');
	$('#anime').css('display', 'block');
	setTimeout(() => {
		location.href = path+'orcamentoVenda';
	}, 4000)
}




