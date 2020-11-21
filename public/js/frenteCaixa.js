
var TOTAL = 0;
var ITENS = [];
var caixaAberto = false;
var PRODUTO = null;
var CLIENTE = null;
var TOTALEMABERTOCLIENTE = null;
var COMANDA = 0;
var VALORBAIRRO = 0;
var VALORACRESCIMO = 0;
var OBSERVACAO = "";
var OBSERVACAOITEM = "";
var DESCONTO = 0;
var LISTAID = 0;
var PDV_VALOR_RECEBIDO = 0;

var VALORPAG1 = 0
var VALORPAG2 = 0
var VALORPAG3 = 0
var TIPOPAG1 = ''
var TIPOPAG2 = ''
var TIPOPAG3 = ''
$(function () {
	novaHora();
	novaData();

	let semCertificado = $('#semCertificado').val()
	if(semCertificado){
		swal("Aviso", "Para habilitar o cupom fiscal, realize o upload do certificado digital!!", "warning")
	}

	PDV_VALOR_RECEBIDO = $('#PDV_VALOR_RECEBIDO').val()

	let valor_entrega = $('#valor_entrega').val();

	VALORACRESCIMO = parseFloat(valor_entrega);
	let obs = $('#obs').val();
	if(obs) OBSERVACAO = obs;
	getClientes(function(data){
		$('input.autocomplete-cliente').autocomplete({
			data: data,
			limit: 20, 
			onAutocomplete: function(val) {
				var cliente = $('#autocomplete-cliente').val().split('-');
				getCliente(cliente[0], (d) => {
					if(d){
						CLIENTE = d;
						$('#cliente-nao').css('display', 'none');
						$('#edit-cliente').css('display', 'block');
						$('#autocomplete-cliente').attr('disabled', 'true');

						if(CLIENTE.limite_venda > 0){
							getVendasEmAbertoContaCredito(CLIENTE.id, (res) => {
								TOTALEMABERTOCLIENTE = res;

							})
						}
						$('#conta_credito-btn').removeClass('disabled')
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
				if(caixaAberto){
					$('#preloader1').css('display', 'block');
					var prod = $('#autocomplete-produto').val().split('-');
					getProduto(prod[0], (d) => {
						console.log(d)
						if(d.gerenciar_estoque == 1 && d.estoque_atual < 1){
							$('#autocomplete-produto').val('')
							$('#nome-produto').html('-');
							$('#valor_item').val('0');
							swal("Erro", 'Estoque insuficiente!!', "warning")
						}else{

							PRODUTO = d;
							$('#nome-produto').html(d.nome);
							$('#valor_item').val(d.valor_venda);
							if(d.estoque_atual <= d.estoque_minimo){
								$('#prod-nome').removeClass('green');
								$('#prod-nome').addClass('red');
							}else{
								$('#prod-nome').addClass('green');
								$('#prod-nome').removeClass('red');
							}
						}
					})
				}else{
					// alert('Por favor abra o caixa!');
					swal("Erro", 'Por favor abra o caixa!', "warning")
					.then((v) => {
						location.href = path+'frenteCaixa'
					})

					// location.reload();
				}
			},
			minLength: 1,
		});
	});

	verificaCaixa((v) => {
		console.log(v)
		caixaAberto = v >= 0 ? true : false;
		if(v < 0){
			$('#modal1').modal('open');
		}
	})

	let itensPedido = $('#itens_pedido').val();

	//Verifica se os dados estao vindo da comanda
	//Controller Pedido
	if(itensPedido){
		itensPedido = JSON.parse(itensPedido);

		if($('#bairro').val() != 0){
			console.log($('#bairro').val())
			let bairro = JSON.parse($('#bairro').val());

			VALORBAIRRO = parseFloat(bairro.valor_entrega);
		}
		let cont = 1;
		itensPedido.map((v) => {
			console.log(v)
			let nome = '';
			let valorUnit = 0;
			if(v.sabores.length > 0){

				let cont = 0;
				v.sabores.map((sb) => {
					cont++;
					valorUnit = v.maiorValor;
					nome += sb.produto.produto.nome + 
					(cont == v.sabores.length ? '' : ' | ')
				})


			}else{
				nome = v.produto.nome;
				valorUnit = v.produto.valor_venda
			}

			let item = {
				cont: cont++,
				id: v.produto_id,
				nome: nome,
				quantidade: v.quantidade,
				valor: parseFloat(valorUnit) + parseFloat(v.valorAdicional),
				pizza: v.maiorValor ? true : false,
				itemPedido: v.item_pedido,
				delivery: true
			}


			ITENS.push(item)


			TOTAL += parseFloat((item.valor * item.quantidade));
		});
		let t = montaTabela();

		let valor_total = $('#valor_total').val();
		if(valor_total > TOTAL){ 
			TOTAL = valor_total
			VALORACRESCIMO = 0;
		}


		atualizaTotal();
		$('#body').html(t);
		let codigo_comanda = $('#codigo_comanda_hidden').val();
		COMANDA = codigo_comanda;
	}

});

$('#desconto').keyup( () => {
	$('#acrescimo').val('0')
	let desconto = $('#desconto').val();
	// if(!desconto){ $('#desconto').val('0'); desconto = 0}

	if(desconto){
		desconto = parseFloat(desconto.replace(",", "."))
		DESCONTO = 0;
		if(desconto > TOTAL && $('#desconto').val().length > 2){
			Materialize.toast('ERRO, Valor desconto maior que o valor total', 4000)
			$('#desconto').val("");
		}else{
			DESCONTO = desconto;

			atualizaTotal();
		}
	}
})

function pad(s) {
	return (s < 10) ? '0' + s : s;
}

function novaHora() {

	var date = new Date();
	let v = [date.getHours(), date.getMinutes()].map(pad).join(':');
	$('#horas').html(v);
}

function novaData() {
	var date = new Date();
	let v = [date.getDate(), date.getMonth()+1, date.getFullYear()].map(pad).join('/');
	$('#data').html(v);
}

function setarObservacao(){
	let obs = $('#obs').val();
	OBSERVACAO = obs;
	if(obs != "") $('#btn-obs').css('border-left', '5px solid #1de9b6');
	else $('#btn-obs').css('border-left', 'none');
	$('#modal-obs').modal('close')
}

function setarObservacaoItem(){
	let obs = $('#obs-item').val();
	OBSERVACAOITEM = obs;

	$('#modal-obs-item').modal('close')
}

$('#autocomplete-cliente').on('keyup', () => {
	$('#cliente-nao').css('display', 'block');
	CLIENTE = null;
})

$('#edit-cliente').click(() => {
	$('#conta_credito-btn').addClass('disabled')
	$('#autocomplete-cliente').removeAttr('disabled');
	$('#autocomplete-cliente').val('');
	$('#edit-cliente').css('display', 'none');
	$('#cliente-nao').css('display', 'block');
})

function formatReal(v){
	return v.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});;
}

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

function getVendasEmAbertoContaCredito(id, data){
	$.ajax
	({
		type: 'GET',
		url: path + 'vendasEmCredito/somaVendas/'+id,
		dataType: 'json',
		success: function(e){
			data(e)
		}, error: function(e){
			console.log(e)
		}

	});
}

$('#lista_id').change(() => {
	let lista = $('#lista_id').val();
	LISTAID = lista;
})

function getProduto(id, data){

	console.log(LISTAID)
	$.ajax
	({
		type: 'GET',
		url: path + 'produtos/getProdutoVenda/' + id + '/' + LISTAID,
		dataType: 'json',
		success: function(e){
			data(e)
		}, error: function(e){
			console.log(e)
		}
	});
}

function addItem(){
	verificaProdutoIncluso((call) => {
		console.log("cal", call)
		if(call >= 0){
			let quantidade = $('#quantidade').val();
			quantidade = quantidade.replace(",", ".");
			let valor = $('#valor_item').val();
			console.log("teste", (parseFloat(quantidade) + parseFloat(call)));
			if(PRODUTO.gerenciar_estoque == 1 && (parseFloat(quantidade) + parseFloat(call)) > PRODUTO.estoque_atual){
				swal("Erro", 'O estoque atual deste produto é de ' + PRODUTO.estoque_atual, "warning")
				$('#quantidade').val('1')

			}else{

				if(quantidade.length > 0 && parseFloat(quantidade.replace(",", ".")) > 0 && valor.length > 0 && parseFloat(valor.replace(",", ".")) > 0 && PRODUTO != null){
					
					let vTemp = valor.replace(',','.') * quantidade.replace(',','.')
					console.log(vTemp)
					TOTAL = parseFloat(TOTAL) + parseFloat(vTemp);
					console.log(TOTAL)

					let item = {
						cont: (ITENS.length+1),
						obs: OBSERVACAOITEM,
						id: PRODUTO.id,
						nome: PRODUTO.nome,
						quantidade: $('#quantidade').val(),
						valor: $('#valor_item').val()
					}

					$('#body').html("");
					ITENS.push(item);

					limparCamposFormProd();
					atualizaTotal();

					let v = $('#valor_recebido').val();
					v = v.replace(",", ".");

					if(PDV_VALOR_RECEBIDO == 0){
						$('#valor_recebido').val(TOTAL)
						Materialize.updateTextFields();
					}

					if(ITENS.length > 0 && ((parseFloat(v) >= TOTAL))){
						$('#finalizar-venda').removeClass('disabled');
					}else{
						$('#finalizar-venda').addClass('disabled');
					}

					let t = montaTabela();

					$('#body').html(t);
					PRODUTO = null;
					$('#obs-item').val('');
					OBSERVACAOITEM = "";
					$('#prod-nome').addClass('green');
					$('#prod-nome').removeClass('red');
				}
			}
		}else{
			Materialize.toast('Informe corretamente os campos para continuar!', 4000)
		}
	});
}

$('#adicionar-item').click(() => {
	addItem();
})

function atualizaTotal(){

	let valor_recebido = $('#valor_recebido').val();
	if(!valor_recebido) valor_recebido = 0;
	if(valor_recebido > 0){
		valor_recebido = valor_recebido.replace(",", ".");
		valor_recebido = parseFloat(valor_recebido)
	}
	console.log(TOTAL + VALORBAIRRO + VALORACRESCIMO - DESCONTO)
	if((TOTAL + VALORBAIRRO + VALORACRESCIMO - DESCONTO) > valor_recebido){
		$('#finalizar-venda').addClass('disabled')
	}else{
		$('#finalizar-venda').removeClass('disabled')
	}
	console.log(valor_recebido)
	if(!$('#valor_recebido').val()){
		$('#finalizar-venda').addClass('disabled')
	}
	// $('#total-venda').html(formatReal(TOTAL + VALORBAIRRO + VALORACRESCIMO - DESCONTO));
	console.log(VALORACRESCIMO)
	$('#total-venda').html(formatReal(TOTAL + VALORBAIRRO + VALORACRESCIMO - DESCONTO));
}

function montaTabela(){
	let t = ""; 
	let quantidades = 0;
	ITENS.map((v) => {
		console.log(v)
		t += "<tr>";
		t += "<td>"+v.cont+"</td>";
		t += "<td class='cod'>"+v.id+"</td>";
		t += "<td>"+v.nome + (v.obs ? " [OBS: "+v.obs+"]" : "")
		+"</td>";

		t += "<td>"+v.quantidade+"</td>";
		t += "<td>"+formatReal(v.valor)+"</td>";
		t += "<td>"+formatReal(v.valor*v.quantidade.replace(',', '.'))+"</td>";
		if(!v.delivery){
			t += "<td><a href='#prod tbody' onclick='deleteItem("+v.cont+")'>"
			t += "<i class=' material-icons red-text'>delete</i></a></td>";
		}
		t+= "</tr>";
		quantidades += parseInt(v.quantidade);
	});
	$('#qtd-itens').html(ITENS.length);
	$('#_qtd').html(quantidades);

	return t
}

function deleteItem(id){
	let temp = [];
	ITENS.map((v) => {
		if(v.cont != id){
			temp.push(v)
		}else{
			TOTAL -= parseFloat(v.valor.replace(',','.'))*(v.quantidade.replace(',','.'));
		}
	});
	ITENS = temp;
	let t = montaTabela(); // para remover
	$('#body').html(t)
	$('#body-modal').html(t)
	if(ITENS.length == 0) $('#finalizar-venda').addClass('disabled');
	let v = $('#valor_recebido').val();
	v = v.replace(",", ".");
	if(parseFloat(v) > TOTAL){
		$('#finalizar-venda').removeClass('disabled');
	}
	atualizaTotal();
}

function limparCamposFormProd(){
	$('#autocomplete-produto').val('');
	$('#quantidade').val('1');
	$('#valor_item').val('0,00');
}

function verificaProdutoIncluso(call){
	let cont = 0;
	ITENS.map((rs) => {
		if(PRODUTO.id == rs.id){
			cont += parseFloat(rs.quantidade);
		}
	})
	call(cont);
}

function getProdutoCodBarras(cod, data){
	let tamanho = ITENS.length;
	console.log(tamanho)
	$.ajax
	({
		type: 'GET',
		url: path + 'produtos/getProdutoCodBarras/'+cod,
		dataType: 'json',
		success: function(e){
			data(e)
			if(e){
				PRODUTO = e;
				$('#nome-produto').html(e.nome);
				$('#valor_item').val(e.valor_venda);
			}else{
				if(cod.length == 13){
					//validar pelo cod balança

					let id = parseInt(cod.substring(1,5));

					console.log(id)

					$.get(path+'produtos/getProduto/'+id)
					.done((res) => {

						let valor = cod.substring(7,12);

						let temp = valor.substring(0,3) + '.' +valor.substring(3,5);
						valor = parseFloat(temp)
						console.log(valor)

						PRODUTO = JSON.parse(res);

						$('#nome-produto').html(PRODUTO.nome);
						let quantidade = 1;
						if(PRODUTO.unidade_venda == 'KG'){
							let valor_venda = PRODUTO.valor_venda;
							quantidade = valor/valor_venda;
							quantidade = quantidade.toFixed(3);
							valor = valor_venda;
						}
						$('#valor_item').val(valor);
						$('#quantidade').val(quantidade);
						let tamanho2 = ITENS.length;
						if(tamanho2 == tamanho){
							console.log("inserindo");
							$('#adicionar-item').trigger('click');
						}

					})
					.fail((err) => {
						// alert('Produto nao encontrado!')
						swal("Erro", 'Produto nao encontrado!', "warning")

						$('#autocomplete-produto').val('')

					})
					
					

				}
			}

		}, error: function(e){
			console.log(e)
		}

	});
}

function verificaCaixa(data){
	$.ajax
	({
		type: 'GET',
		url: path + 'aberturaCaixa/verificaHoje',
		dataType: 'json',
		success: function(e){
			console.log(e)
			data(e)

		}, error: function(e){
			console.log(e)
		}

	});
}

function abrirCaixa(){
	let token = $('#_token').val();
	let valor = $('#valor').val();
	valor = valor.length >= 0 ? valor.replace(",", ".") : 0 ;
	if(parseFloat(valor) >= 0){
		$.ajax
		({
			type: 'POST',
			url: path + 'aberturaCaixa/abrir',
			dataType: 'json',
			data: {
				valor: $('#valor').val(),
				_token: token
			},
			success: function(e){
				caixaAberto = true;
				$('#modal1').modal('close');
				swal("Sucesso", "Caixa aberto", "success")


			}, error: function(e){
				swal("Erro", "Erro ao abrir caixa", "error")
				console.log(e)
			}

		});
	}else{
		// alert('Insira um valor válido')
		swal("Erro", 'Insira um valor válido', "warning")

	}
	
}

function sangriaCaixa(){
	let token = $('#_token').val();

	$.ajax
	({
		type: 'POST',
		url: path + 'sangriaCaixa/save',
		dataType: 'json',
		data: {
			valor: $('#valor_sangria').val(),
			_token: token
		},
		success: function(e){

			caixaAberto = true;
			$('#modal2').modal('close');
			$('#valor_sangria').val('');
			var $toastContent = $('<span>Sangria realizada!</span>').add($('<button class="btn-flat toast-action">OK</button>'));
			Materialize.toast($toastContent, 5000);


		}, error: function(e){
			console.log(e)
		}

	});
}

function getSangriaDiaria(data){
	$.ajax
	({
		type: 'GET',
		url: path + 'sangriaCaixa/diaria',
		dataType: 'json',
		success: function(e){
			data(e)

		}, error: function(e){
			console.log(e)
		}

	});
}

function getAberturaDiaria(data){

	$.ajax
	({
		type: 'GET',
		url: path + 'aberturaCaixa/verificaHoje',
		dataType: 'json',
		success: function(e){
			console.log(e)
			data(e)

		}, error: function(e){
			console.log(e)
		}

	});
}

function getVendaDiaria(data){
	$.ajax
	({
		type: 'GET',
		url: path + 'vendasCaixa/diaria',
		dataType: 'json',
		success: function(e){
			data(e)

		}, error: function(e){
			console.log(e)
		}

	});
}

function fluxoDiario(){
	$('#preloader1').css('display', 'block');
	getSangriaDiaria((sangrias) => {

		let elem = "";
		let totalSangria = 0;
		sangrias.map((v) => {

			elem += "<p> Horario: "
			elem += "<strong>" + v.data_registro.substring(10, 16) + "</strong>, Valor: "
			elem += "<strong> R$ " + formatReal(v.valor) + "</strong>, Usuario: "
			elem += "<strong>" + v.nome_usuario + "</strong>"
			elem += "</p>";
			totalSangria += parseFloat(v.valor);
		})
		elem += "<h6>Total: <strong class='orange-text'>" + formatReal(totalSangria) + "</strong></h6>";
		$('#fluxo_sangrias').html(elem)
		getAberturaDiaria((abertura) => {
			abertura = abertura.replace(",", ".")
			elem = "<p> Valor: ";
			elem += "<strong class='orange-text'>R$ "+formatReal(abertura)+"</strong>";
			elem += "</p>";
			$('#fluxo_abertura_caixa').html(elem);
			getVendaDiaria((vendas) => {

				elem = "";
				let totalVendas = 0;
				vendas.map((v) => {
					console.log(v)
					elem += "<p> Horario: "
					elem += "<strong>" + v.data_registro.substring(10, 16) + "</strong>, Valor: "
					elem += "<strong> R$ " + formatReal(parseFloat(v.valor_total) + parseFloat(v.acrescimo) - 
						parseFloat(v.desconto)) + "</strong>, Tipo Pagamento: "
					elem += "<strong>" + v.tipo_pagamento + "</strong>"
					elem += "</p>";
					totalVendas += parseFloat(parseFloat(v.valor_total) + parseFloat(v.acrescimo) - 
						parseFloat(v.desconto));
				})
				elem += "<h6>Total: <strong class='orange-text'>" + formatReal(totalVendas) + "</strong></h6>";

				$('#fluxo_vendas').html(elem);
				$('#total_caixa').html(formatReal((totalVendas+parseFloat(abertura)) - totalSangria));

				$('#preloader1').css('display', 'none');
			});
		})
	})
	if(caixaAberto){
		$('#modal3').modal('open');
	}else{

		var $toastContent = $('<span>Por favor abra o caixa!</span>').add($('<button class="btn-flat toast-action">OK</button>'));
		Materialize.toast($toastContent, 5000);
		location.reload();
	}
}

function esconderTodasMoedas(){
	$('.50_reais').css('display', 'none');
	$('.20_reais').css('display', 'none');
	$('.10_reais').css('display', 'none');
	$('.5_reais').css('display', 'none');
	$('.2_reais').css('display', 'none');
	$('.1_real').css('display', 'none');
	$('.50_centavo').css('display', 'none');
	$('.25_centavo').css('display', 'none');
	$('.50_centavo').css('display', 'none');
	$('.5_centavo').css('display', 'none');
}

$('#valor_recebido').on('keyup', (event) => {
	esconderTodasMoedas();
	let t = TOTAL;
	let v = $('#valor_recebido').val();
	v = v.replace(",", ".");

	if(ITENS.length > 0 && (parseFloat(v) >= (TOTAL + VALORBAIRRO + VALORACRESCIMO - DESCONTO))){
		$('#finalizar-venda').removeClass('disabled');
	}else{
		$('#finalizar-venda').addClass('disabled');
	}


	if(v.length > 0 && parseFloat(v) > TOTAL && TOTAL > 0){
		v = parseFloat(v);

		if (event.keyCode === 13) {
			let troco = v - t;
			$("#valor_troco").html(formatReal(troco))
			$('#modal4').modal('open');

			let resto = troco;
			notas = [];

			if(parseInt(troco / 50) > 0 && resto > 0){

				resto = troco % 50;
				$('#qtd_50_reais').html(' X'+1);
				$('.50_reais').css('display', 'block');

			}
			if(parseInt(resto / 20) > 0){
				numeroNotas = parseInt(resto/20);
				$('#qtd_20_reais').html(' X'+numeroNotas);
				resto = resto%(20*numeroNotas);
				$('.20_reais').css('display', 'block');

			}
			if(parseInt(resto / 10) > 0){
				numeroNotas = parseInt(resto/10);
				$('#qtd_10_reais').html(' X'+numeroNotas);
				resto = resto%(10*numeroNotas);
				$('.10_reais').css('display', 'block');

			}
			if(parseInt(resto / 5) > 0){
				numeroNotas = parseInt(resto/5);
				$('#qtd_5_reais').html(' X'+numeroNotas);
				resto = duasCasas(resto%(5*numeroNotas));
				$('.5_reais').css('display', 'block');

			}
			if(parseInt(resto / 2) > 0){
				numeroNotas = parseInt(resto/2);
				$('#qtd_2_reais').html(' X'+numeroNotas);
				resto = duasCasas(resto%(2*numeroNotas));
				$('.2_reais').css('display', 'block');

			}

			if(parseInt(resto / 1) > 0){
				numeroNotas = parseInt(resto/1);
				$('#qtd_1_real').html(' X'+numeroNotas);
				resto = duasCasas(resto%(1*numeroNotas));
				$('.1_real').css('display', 'block');

			}

			if(parseInt(resto / 0.5) > 0){
				numeroNotas = parseInt(resto/0.5);
				$('#qtd_50_centavos').html(' X'+numeroNotas);
				resto = duasCasas(resto%(0.5*numeroNotas));
				$('.50_centavo').css('display', 'block');

			}

			if(parseInt(resto / 0.25) > 0){
				numeroNotas = parseInt(resto/0.25);
				$('#qtd_25_centavos').html(' X'+numeroNotas);
				resto = duasCasas(resto%(0.25*numeroNotas));
				$('.25_centavo').css('display', 'block');

			}

			if(parseInt(resto / 0.10) > 0){
				numeroNotas = parseInt(resto/0.10);
				$('#qtd_10_centavos').html(' X'+numeroNotas);
				resto = duasCasas(resto%(0.10*numeroNotas));
				$('.10_centavo').css('display', 'block');

			}


			if(parseInt(resto / 0.05) > 0){
				numeroNotas = parseInt(resto/0.05);
				$('#qtd_5_centavos').html(' X'+numeroNotas);
				resto = resto%(0.05*numeroNotas);
				$('.5_centavo').css('display', 'block');

			}

		}
	}
})

function duasCasas(valor){
	return parseFloat(valor.toFixed(2));
}

$('#autocomplete-produto').on('keyup', () => {
	let val = $('#autocomplete-produto').val();
	if($.isNumeric(val) && val.length > 6){
		getProdutoCodBarras(val, (data) => {
			setTimeout(() => {
				addItem();
				
			}, 400)
		})
	}
})

function verificaCliente(){
	if(CLIENTE == null){
		$('#modal-venda').modal('close');
		$('#modal-cpf-nota').modal('open');
	} 
	else{ 
		finalizarVenda('fiscal')
	}
}

function validaCpf(){

	if(CLIENTE != null) return true;

	let strCPF = $('#cpf').val();
	let nome = $('#nome').val();
	if(strCPF.length == 0) return true;

	// if(nome == '' || nome == null || nome.length == 0) return false;
	
	strCPF = strCPF.replace(".", "");
	strCPF = strCPF.replace(".", "");
	strCPF = strCPF.replace("-", "");
	var Soma;
	var Resto;
	Soma = 0;
	if (strCPF == "00000000000") return false;

	for (i=1; i<=9; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (11 - i);
		Resto = (Soma * 10) % 11;

	if ((Resto == 10) || (Resto == 11))  Resto = 0;
	if (Resto != parseInt(strCPF.substring(9, 10)) ) return false;;

	Soma = 0;
	for (i = 1; i <= 10; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (12 - i);
		Resto = (Soma * 10) % 11;

	if ((Resto == 10) || (Resto == 11))  Resto = 0;
	if (Resto != parseInt(strCPF.substring(10, 11) ) ) return false;;
	
	return true;
}

$('#tipo-pagamento').change(() => {
	$('#valor_recebido').val('');

	let tipo = $('#tipo-pagamento').val();

	if(tipo == '01'){
		$('#valor_recebido').removeAttr('disabled');
		$('#finalizar-venda').addClass('disabled');

	}else{
		$('#valor_recebido').attr('disabled', 'true');
		$('#finalizar-venda').removeClass('disabled');

	}
})

function finalizarVenda(acao) {

	let validCpf = validaCpf();
	if(validCpf == true || acao != 'fiscal'){
		
		let valorRecebido = $('#valor_recebido').val();
		let troco = 0;
		if(valorRecebido.length > 0 && parseFloat(valorRecebido) > (TOTAL + VALORACRESCIMO + VALORBAIRRO - DESCONTO)){
			troco = parseFloat(valorRecebido) - (TOTAL + VALORACRESCIMO + VALORBAIRRO - DESCONTO);
		}

		let desconto = $('#desconto').val();
		desconto = parseFloat(desconto.replace(",", "."))

		let js = { 
			itens: ITENS,
			cliente: CLIENTE == null ? null : CLIENTE.id,
			valor_total: TOTAL,
			acrescimo: VALORBAIRRO + VALORACRESCIMO,
			troco: troco,
			tipo_pagamento: $('#tipo-pagamento').val(),
			forma_pagamento: '',
			dinheiro_recebido: valorRecebido ? valorRecebido : TOTAL,
			acao: acao,
			nome: $('#nome').val(),
			cpf: $('#cpf').val(),
			delivery_id: $('#delivery_id').val(),
			pedido_local: $('#pedidoLocal').val() ? true : false,
			codigo_comanda: COMANDA,
			desconto: desconto ? desconto : 0,
			observacao: OBSERVACAO,
			tipo_pagamento_1: TIPOPAG1,
			tipo_pagamento_2: TIPOPAG2,
			tipo_pagamento_3: TIPOPAG3,
			valor_pagamento_1: VALORPAG1,
			valor_pagamento_2: VALORPAG2,
			valor_pagamento_3: VALORPAG3
		}

		console.log(js)
		let token = $('#_token').val();

		if(acao != 'credito'){
			$('#btn_nao_fiscal').addClass('disabled')
			$.ajax
			({
				type: 'POST',
				url: path + 'vendasCaixa/save',
				dataType: 'json',
				data: {
					venda: js,
					_token: token
				},
				success: function(e){
					if(acao == 'fiscal'){
						$('#preloader2').css('display', 'block');
						$('#preloader9').css('display', 'block');
						emitirNFCe(e.id);	
					} else{
						swal({
							title: "Sucesso",
							text: "Deseja imprimir comprovante?",
							icon: "success",
							buttons: ["Não", 'Imprimir'],
							dangerMode: true,
						})
						.then((v) => {
							if (v) {
								window.open(path + 'nfce/imprimirNaoFiscal/'+e.id, '_blank');
								location.href=path+'frenteCaixa';
							} else {
								location.href=path+'frenteCaixa';
							}
						});
						
					}

				}, error: function(e){
					console.log(e)
					$('#preloader2').css('display', 'none');
					$('#preloader9').css('display', 'none');
					$('#modal-venda').modal('close')
				}

			});
		}else{
			// let valorUltrapassadoConfirma = true;
			// if(CLIENTE.limite_venda < TOTALEMABERTOCLIENTE+TOTAL){
			// 	valorUltrapassadoConfirma = confirm("Valor do limite de conta crédito ultrapassado, confirma venda?!");
			// }

			if(CLIENTE.limite_venda < TOTALEMABERTOCLIENTE+TOTAL){
				swal({
					title: "Valor do limite de conta crédito ultrapassado, confirma venda?!",
					icon: 'warning',
					buttons: ["Não", "Vender"],
				}).then(sim => {
					if (sim) {
						salvarCredito(js, token)
					}else{
						$('#preloader2').css('display', 'none');
						$('#preloader9').css('display', 'none');
						$('#modal-venda').modal('close')
					}
				});

			}else{
				salvarCredito(js, token)
			}
			
		}
	}else{
		Materialize.toast('CPF Inválido!', 5000);
	}

}

function salvarCredito(js, token){
	$.ajax
	({
		type: 'POST',
		url: path + 'vendas/salvarCrediario',
		dataType: 'json',
		data: {
			venda: js,
			_token: token
		},
		success: function(e){
			$('#modal-venda').modal('close')

			window.open(path + 'nfce/imprimirNaoFiscalCredito/'+e.id, '_blank');
			$('#modal-credito').modal('open');
			$('#evento-conta-credito').html('Venda salva na conta crédito do cliente ' +
				CLIENTE.razao_social)

		}, error: function(e){
			console.log(e)
			$('#preloader2').css('display', 'none');
			$('#preloader9').css('display', 'none');
			$('#modal-venda').modal('close')
		}

	});
}

function emitirNFCe(vendaId){
	// $('#modal-venda').modal('close')

	$('#preloader_'+vendaId).css('display', 'inline-block');

	let token = $('#_token').val();
	$.ajax
	({
		type: 'POST',
		url: path + 'nfce/gerar',
		dataType: 'json',
		data: {
			vendaId: vendaId,
			_token: token
		},
		success: function(e){
			$('#modal-cpf-nota').modal('close')
			$('#preloader_'+vendaId).css('display', 'none');

			let recibo = e;
			let retorno = recibo.substring(0,4);
			let mensagem = recibo.substring(5,recibo.length);
			if(retorno == 'Erro'){
				console.log(e)
				try{
					let m = JSON.parse(mensagem);
					$('#modal-alert-erro').modal('open');
					$('#evento-erro').html("[" + m.protNFe.infProt.cStat + "] : " + m.protNFe.infProt.xMotivo)
				}catch(e){
					$('#modal-alert-erro').modal('open');
					$('#evento-erro').html(e)
				}

			}
			else if(retorno == 'erro'){
				$('#modal-alert-erro').modal('open');
				$('#evento-erro').html("WebService sefaz em manutenção, falha de comunicação SOAP ")

			}
			else if(e == 'Apro'){

				var $toastContent = $('<span>Esta NF já esta aprovada, não é possível enviar novamente!</span>').add($('<button class="btn-flat toast-action">OK</button>'));
				Materialize.toast($toastContent, 5000);
			}
			else{
				$('#modal-venda').modal('close')
				$('#modal-alert').modal('open');
				$('#evento').html("NFCe gerada com sucesso RECIBO: " +recibo)
				window.open(path + 'nfce/imprimir/'+vendaId, '_blank');
				
			}
			$('#preloader2').css('display', 'none');
			$('#preloader9').css('display', 'none');
			$('#preloader1').css('display', 'none');
		}, error: function(err){

			console.log(err)
			$('#preloader_'+vendaId).css('display', 'none');

			// deletarVenda(vendaId)

			var $toastContent = $('<span>Erro ao enviar NFC-e</span>').add($('<button class="btn-flat toast-action">OK</button>'));
			Materialize.toast($toastContent, 5000);
			$('#preloader2').css('display', 'none');
			$('#preloader9').css('display', 'none');

			let js = err.responseJSON;
			console.log(js)
			if(js.message){
				Materialize.toast(js.message, 5000)
			}else{
				let err = "";
				js.map((v) => {
					err += v + "\n";
				});
				// alert(err);
				swal("Erro", err, "warning")

			}

			$('#preloader1').css('display', 'none');
			
		}
	})

}

function deletarVenda(id){
	$.get(path + 'nfce/deleteVenda/'+id)
	.done((data) => {
		console.log(data)
	})
	.fail((err) => {
		console.log(err)
	})
	
}

function redireciona(){
	location.href=path+'frenteCaixa';
}

function modalCancelar(id){
	$('#modal').modal('open');
	$('#venda_id').val(id)
}


function cancelar(){
	$('#preloader').css('display', 'block');

	let justificativa = $('#justificativa').val();
	let id = $('#venda_id').val();
	let token = $('#_token').val();
	$.ajax
	({
		type: 'POST',
		data: {
			id: id,
			justificativa: justificativa,
			_token: token
		},
		url: path + 'nfce/cancelar',
		dataType: 'json',
		success: function(e){
			
			// alert(e.retEvento.infEvento.xMotivo)
			swal("Sucesso", e.retEvento.infEvento.xMotivo, "success")
			.then((v) => {
				location.reload()
			})


			$('#preloader').css('display', 'none');
		}, error: function(e){
			$('#preloader').css('display', 'none');
			console.log(e)
			let js = e.responseJSON;
			if(e.status == 404){
				// alert(js.mensagem)
				swal("Erro", js.mensagem, "warning")

			}else{
				// alert(js.retEvento.infEvento.xMotivo)
				swal("Erro", js.retEvento.infEvento.xMotivo, "warning")

				// Materialize.toast('Erro de comunicação contate o desenvolvedor', 5000)
				
			}
		}
	});
}

function verItens(){
	$('#modal-itens').modal('open');
	let t = montaTabela();
	$('#body-modal').html(t);

}

function modalWhatsApp(){
	$('#modal-whatsApp').modal('open')
}

function enviarWhatsApp(){
	let celular = $('#celular').val();
	let texto = $('#texto').val();

	let mensagem = texto.split(" ").join("%20");

	let celularEnvia = '55'+celular.replace(' ', '');
	celularEnvia = celularEnvia.replace('-', '');
	let api = 'https://api.whatsapp.com/send?phone='+celularEnvia
	+'&text='+mensagem;
	window.open(api)
}

function apontarComanda(){
	let cod = $('#cod-comanda').val()
	$.get(path+'pedidos/itensParaFrenteCaixa', {cod: cod})
	.done((success) => {
		montarComanda(success, (rs) => {
			if(rs){
				COMANDA = cod;
				$('#modal-comanda').modal('close')
				var $toastContent = $('<span>Comanda setada!</span>').add($('<button class="btn-flat toast-action">OK</button>'));
				Materialize.toast($toastContent, 3000);
			}
		})
	})
	.fail((err) => {
		if(err.status == 401){

			var $toastContent = $('<span>Nada encontrado!!!</span>').add($('<button class="btn-flat toast-action">OK</button>'));
			Materialize.toast($toastContent, 5000);
		}
		console.log(err)
	})
}

function montarComanda(itens, call){
	let cont = 0;
	itens.map((v) => {
		let nome = '';
		let valorUnit = 0;
		if(v.sabores.length > 0){

			let cont = 0;
			v.sabores.map((sb) => {
				cont++;
				valorUnit = v.maiorValor;
				nome += sb.produto.produto.nome + 
				(cont == v.sabores.length ? '' : ' | ')
			})


		}else{
			nome = v.produto.nome;
			valorUnit = v.produto.valor_venda
		}

		let item = {
			cont: cont+1,
			id: v.produto_id,
			nome: nome,
			quantidade: v.quantidade,
			valor: parseFloat(valorUnit) + parseFloat(v.valorAdicional),
			pizza: v.maiorValor ? true : false,
			itemPedido: v.item_pedido
		}

		ITENS.push(item)
		TOTAL += parseFloat(item.valor)*(item.quantidade);
	});
	let t = montaTabela();

	atualizaTotal();
	$('#body').html(t);
	call(true)
}

$('#acrescimo').keyup(() => {
	let acrescimo = $('#acrescimo').val();
	if(acrescimo > 0) $('#desconto').val('0')

		let total = TOTAL+VALORBAIRRO;
	
	if(acrescimo.substring(0, 1) == "%"){

		let perc = acrescimo.substring(1, acrescimo.length);

		VALORACRESCIMO = total * (perc/100);


	}else{
		acrescimo = acrescimo.replace(",", ".")
		VALORACRESCIMO = parseFloat(acrescimo)
	}

	if(acrescimo.length == 0) VALORACRESCIMO = 0;
	atualizaTotal();


})

function consultarNFCe(id){
	$('#preloader_'+id).css('display', 'inline-block');

	$.get(path + 'nfce/consultar/'+id)
	.done((data) => {
		$('#preloader_'+id).css('display', 'none');
		console.log(data)
		let js = JSON.parse(data)
		console.log(js)
		swal("Consulta", "[" + js.protNFe.infProt.cStat + "] " + js.protNFe.infProt.xMotivo ,"success");
	})
	.fail((err) => {
		$('#preloader_'+id).css('display', 'none');
		console.log(err)
	})
}

$('#btn-plus').click((target) => {
	let quantidade = parseInt($('#quantidade').val());
	$('#quantidade').val(quantidade+1)
})

$('#click-multi').click(() => {
	if(TOTAL <= 0){
		swal("Erro", "Valor da venda deve ser maior que Zero!!", "error")
		.then(() => {
			$('#modal-pag-mult').modal('close')
		})
	}
	$('#total-multi').html(formatReal(TOTAL))
})

$('#btn-ok-multi').click(() => {
	$('#modal-pag-mult').modal('close')

	VALORPAG1 = $('#valor_pagamento_1').val() ? parseFloat($('#valor_pagamento_1').val()) : 0;
	VALORPAG2 = $('#valor_pagamento_2').val() ? parseFloat($('#valor_pagamento_2').val()) : 0;
	VALORPAG3 = $('#valor_pagamento_3').val() ? parseFloat($('#valor_pagamento_3').val()) : 0;

	TIPOPAG1 = $('#tipo_pagamento_1').val()
	TIPOPAG2 = $('#tipo_pagamento_2').val()
	TIPOPAG3 = $('#tipo_pagamento_3').val()


	console.log(VALORPAG1, VALORPAG2, VALORPAG3)
	console.log(TIPOPAG1, TIPOPAG2, TIPOPAG3)
	$('#modal-venda').modal('open')
})

$('#valor_pagamento_1').keyup((target) => {
	somaMultiplo();
})
$('#valor_pagamento_2').keyup((target) => {
	somaMultiplo();
})
$('#valor_pagamento_3').keyup((target) => {
	somaMultiplo();
})

function somaMultiplo(){
	let v1 = $('#valor_pagamento_1').val() ? parseFloat($('#valor_pagamento_1').val()) : 0;
	let v2 = $('#valor_pagamento_2').val() ? parseFloat($('#valor_pagamento_2').val()) : 0;
	let v3 = $('#valor_pagamento_3').val() ? parseFloat($('#valor_pagamento_3').val()) : 0;

	let soma = v1 + v2 + v3;
	if(soma == TOTAL){
		$('#btn-ok-multi').removeClass('disabled')
	}else if(soma > TOTAL){
		// swal("Alerta", "Valor de pagamentos ultrapassou o valor da venda", "warning")
		$('#btn-ok-multi').addClass('disabled')
	}else{
		$('#btn-ok-multi').addClass('disabled')
	}
}

$('#close-multi').click(() => {
	$('#modal-pag-mult').modal('close')
	VALORPAG1 = 0
	VALORPAG2 = 0
	VALORPAG3 = 0
	TIPOPAG1 = ''
	TIPOPAG2 = ''
	TIPOPAG3 = ''
})
//modal-venda



