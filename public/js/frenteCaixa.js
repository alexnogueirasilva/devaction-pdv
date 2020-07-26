
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

$(function () {
	novaHora();
	novaData();

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
						PRODUTO = d;
						$('#nome-produto').html(d.nome);
						$('#valor_item').val(d.valor_venda);
					})
				}else{
					alert('Por favor abra o caixa!');
					location.href = path+'frenteCaixa'
					location.reload();
				}
			},
			minLength: 1,
		});
	});

	verificaCaixa((v) => {
		caixaAberto = v;
		if(v == false){
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
				itemPedido: v.item_pedido
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

function getProduto(id, data){
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

function addItem(){
	verificaProdutoIncluso((call) => {

		if(!call){
			let quantidade = $('#quantidade').val();
			quantidade = quantidade.replace(",", ".");
			let valor = $('#valor_item').val();

			if(quantidade.length > 0 && parseFloat(quantidade.replace(",", ".")) > 0 && valor.length > 0 && 
				parseFloat(valor.replace(",", ".")) > 0
				&& PRODUTO != null){
				TOTAL += parseFloat(valor.replace(',','.'))*(quantidade.replace(',','.'));

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
			// var audio = new Audio('/notificacao/beep.mp3');
			// audio.play();
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
	valor_recebido = valor_recebido.replace(",", ".");
	valor_recebido = parseFloat(valor_recebido)
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
		t += "<tr>";
		t += "<td>"+v.cont+"</td>";
		t += "<td class='cod'>"+v.id+"</td>";
		t += "<td>"+v.nome + (v.obs ? " [OBS: "+v.obs+"]" : "")
		+"</td>";

		t += "<td>"+v.quantidade+"</td>";
		t += "<td>"+formatReal(v.valor)+"</td>";
		t += "<td>"+formatReal(v.valor*v.quantidade)+"</td>";
		t += "<td><a href='#prod tbody' onclick='deleteItem("+v.cont+")'>"
		t += "<i class=' material-icons red-text'>delete</i></a></td>";
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


	call(false);
}

function getProdutoCodBarras(cod, data){
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

					$.get(path+'produtos/getProduto/'+id)
					.done((res) => {

						let valor = cod.substring(7,12);

						let temp = valor.substring(0,3) + '.' +valor.substring(3,5);
						valor = parseFloat(temp)

						PRODUTO = JSON.parse(res);
						$('#nome-produto').html(PRODUTO.nome);
						$('#valor_item').val(valor);

					})
					.fail((err) => {
						alert('Produto nao encontrado!')
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
			data(e)

		}, error: function(e){
			console.log(e)
		}

	});
}

function abrirCaixa(){
	let token = $('#_token').val();
	let valor = $('#valor').val();
	valor = valor.length > 0 ? valor.replace(",", ".") : 0 ;
	if(parseFloat(valor) > 0){
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


			}, error: function(e){
				console.log(e)
			}

		});
	}else{
		alert('Insira um valor válido')
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
		if(valorRecebido.length > 0 && parseFloat(valorRecebido) > TOTAL){
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
			observacao: OBSERVACAO
		}

		let token = $('#_token').val();
		if(acao != 'credito'){
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
						console.log("Imprime nao fiscal");
						window.open(path + 'nfce/imprimirNaoFiscal/'+e.id, '_blank');
						location.href=path+'frenteCaixa';
					}

				}, error: function(e){
					console.log(e)
					$('#preloader2').css('display', 'none');
					$('#preloader9').css('display', 'none');
					$('#modal-venda').modal('close')
				}

			});
		}else{
			let valorUltrapassadoConfirma = true;
			if(CLIENTE.limite_venda < TOTALEMABERTOCLIENTE+TOTAL){
				valorUltrapassadoConfirma = confirm("Valor do limite de conta crédito ultrapassado, confirma venda?!");
			}


			if(valorUltrapassadoConfirma == true){
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
			
		}
	}else{
		Materialize.toast('CPF Inválido!', 5000);
	}

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
				let m = JSON.parse(mensagem);
				$('#modal-alert-erro').modal('open');
				$('#evento-erro').html("[" + m.protNFe.infProt.cStat + "] : " + m.protNFe.infProt.xMotivo)

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
				alert(err);
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
			
			alert(e.retEvento.infEvento.xMotivo)

			$('#preloader').css('display', 'none');
		}, error: function(e){
			$('#preloader').css('display', 'none');
			console.log(e)
			let js = e.responseJSON;
			if(e.status == 404){
				alert(js.mensagem)
			}else{
				alert(js.retEvento.infEvento.xMotivo)
				Materialize.toast('Erro de comunicação contate o desenvolvedor', 5000)
				
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



