

function enviar(){
	$('#preloader1').css('display', 'block');
	let id = 0
	$('#body tr').each(function(){
		if($(this).find('#checkbox input').is(':checked'))
			id = $(this).find('#id').html();
	})
	console.log(id);


	let token = $('#_token').val();
	$.ajax
	({
		type: 'POST',
		data: {
			vendaId: id,
			_token: token
		},
		url: path + 'nf/gerarNf',
		dataType: 'json',
		success: function(e){
			console.log(e)
			let recibo = e;
			let retorno = recibo.substring(0,4);
			let mensagem = recibo.substring(5,recibo.length);
			if(retorno == 'Erro'){
				let m = JSON.parse(mensagem);
				// console.log(m.protNFe.infProt.xMotivo)
				// alert("[" + m.protNFe.infProt.cStat + "] : " + m.protNFe.infProt.xMotivo)
				$('#modal-alert-erro').modal('open');
				$('#evento-erro').html("[" + m.protNFe.infProt.cStat + "] : " + m.protNFe.infProt.xMotivo)

			}
			else if(e == 'Apro'){
				alert("Esta NF já esta aprovada, não é possível enviar novamente!");
			}
			else{
				$('#modal-alert').modal('open');
				$('#evento').html("NF-e gerada com sucesso RECIBO: "+recibo)
				window.open(path+"/nf/imprimir/"+id, "_blank");
			}

			$('#preloader1').css('display', 'none');
		}, error: function(e){

			let js = e.responseJSON;
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
	});
	
}

function redireciona(){
	location.reload();
}

function imprimir(){
	let id = 0;
	let cont = 0;
	$('#body tr').each(function(){
		if($(this).find('#checkbox input').is(':checked')){
			id = $(this).find('#id').html();
			cont++
		}
	})

	if(cont > 1){
		Materialize.toast('Selecione apenas um documento para impressão!', 5000)
	}else{
		window.open(path+"/nf/imprimir/"+id, "_blank");
	}
}

function imprimirCCe(){
	let id = 0;
	let cont = 0;
	$('#body tr').each(function(){
		if($(this).find('#checkbox input').is(':checked')){
			id = $(this).find('#id').html();
			cont++
		}
	})

	if(cont > 1){
		Materialize.toast('Selecione apenas um documento para impressão!', 5000)
	}else{
		window.open(path+"/nf/imprimirCce/"+id, "_blank");
	}
}

function imprimirCancela(){
	let id = 0;
	let cont = 0;
	$('#body tr').each(function(){
		if($(this).find('#checkbox input').is(':checked')){
			id = $(this).find('#id').html();
			cont++
		}
	})

	if(cont > 1){
		Materialize.toast('Selecione apenas um documento para impressão!', 5000)
	}else{
		window.open(path+"/nf/imprimirCancela/"+id, "_blank");
	}
}

function consultar(){
	let id = 0;
	let cont = 0;
	$('#body tr').each(function(){
		if($(this).find('#checkbox input').is(':checked')){
			id = $(this).find('#id').html();
			cont++;
		}
	})

	if(cont > 1){
		Materialize.toast('Selecione apenas um documento para consultar!', 5000)
	}else{
		let token = $('#_token').val();
		$.ajax
		({
			type: 'POST',
			data: {
				id: id,
				_token: token
			},
			url: path + 'nf/consultar',
			dataType: 'json',
			success: function(e){
				console.log(e)
				let js = JSON.parse(e)
				if(js.cStat != '656'){
					$('#motivo').html(js.xMotivo);
					$('#chave').html(js.chNFe);
					$('#protocolo').html(js.protNFe.infProt.nProt);
					$('#modal2').modal('open');
					$('#preloader1').css('display', 'none');
				}else{
					alert('Consumo indevido!')
				}
			}, error: function(e){
				console.log(e)
				Materialize.toast('Erro de comunicação contate o desenvolvedor', 5000)
				$('#preloader1').css('display', 'none');
			}
		});
	}
}

function setarNumero(buscarCliente = false){
	let id = 0;
	let nf = 0;
	let cont = 0;
	$('#body tr').each(function(){
		if($(this).find('#checkbox input').is(':checked')){
			nf = $(this).find('#numeroNf').html();
			$('#numero_cancelamento').html(nf)
			$('#numero_correcao').html(nf)
			$('#numero_nf').html(nf)

			if(buscarCliente){
				buscarDadosCliente();
			}

			cont++;
		}
	})
	
	if(cont > 1){
		Materialize.toast('Selecione apenas um documento para continuar!', 5000)
	}
}

function buscarDadosCliente(){
	let id = 0;
	let cont = 0;

	$('#body tr').each(function(){
		if($(this).find('#checkbox input').is(':checked')){
			id = $(this).find('#id').html();
			cont++;
		}
	})

	if(cont > 1){
		Materialize.toast('Selecione apenas um documento para continuar!', 5000)
	}else{

		$.get(path+'nf/consultar_cliente/'+id)
		.done(function(data){
			data = JSON.parse(data)
			console.log(data.email)
			$('#email').val(data.email)
			$('#venda_id').val(id)

			if(data.email){
				$('#info-email').html('*Este é o email do cadastro');
			}else{
				$('#info-email').html('*Este cliente não possui email cadastrado');
			}
		})
		.fail(function(err){
			console.log(err)
		})
	}
}

function cancelar(){
	$('#preloader5').css('display', 'block');
	let id = 0;
	let cont = 0;
	let justificativa = $('#justificativa').val();
	$('#body tr').each(function(){
		if($(this).find('#checkbox input').is(':checked')){
			id = $(this).find('#id').html();
			cont++;
		}
	})

	if(cont > 1){
		Materialize.toast('Selecione apenas um documento para cancelar!', 5000)
	}else{
		let token = $('#_token').val();
		$.ajax
		({
			type: 'POST',
			data: {
				id: id,
				justificativa: justificativa,
				_token: token
			},
			url: path + 'nf/cancelar',
			dataType: 'json',
			success: function(e){
				console.log(e)
				let js = JSON.parse(e);
				console.log(js)
				alert(js.retEvento.infEvento.xMotivo)

				$('#preloader5').css('display', 'none');
				location.reload();
			}, error: function(e){
				console.log(e)
				Materialize.toast('Erro de comunicação contate o desenvolvedor', 5000)
				$('#preloader5').css('display', 'none');
			}
		});
	}
}

function cartaCorrecao(){
	$('#preloader4').css('display', 'block');

	let id = 0;
	let cont = 0;
	let correcao = $('#correcao').val();
	$('#body tr').each(function(){
		if($(this).find('#checkbox input').is(':checked')){
			id = $(this).find('#id').html();
			cont++;
		}
	})

	if(cont > 1){
		Materialize.toast('Selecione apenas um documento para continuar!', 5000)
	}else{
		let token = $('#_token').val();
		$.ajax
		({
			type: 'POST',
			data: {
				id: id,
				correcao: correcao,
				_token: token
			},
			url: path + 'nf/cartaCorrecao',
			dataType: 'json',
			success: function(e){
				console.log(e)
				let js = JSON.parse(e);
				console.log(js)
				alert(js.retEvento.infEvento.xMotivo)

				$('#preloader4').css('display', 'none');
			}, error: function(e){
				console.log(e)
				Materialize.toast('Erro de comunicação contate o desenvolvedor', 5000)
				$('#preloader4').css('display', 'none');
			}
		});
	}
}

function inutilizar(){

	let justificativa = $('#justificativa').val();
	let nInicio = $('#nInicio').val();
	let nFinal = $('#nFinal').val();
	
	$('#preloader3').css('display', 'block');

	let token = $('#_token').val();
	$.ajax
	({
		type: 'POST',
		data: {
			justificativa: justificativa,
			nInicio: nInicio,
			nFinal: nFinal,
			_token: token
		},
		url: path + 'nf/inutilizar',
		dataType: 'json',
		success: function(e){
			console.log(e)
			if(e.infInut){
				alert("cStat:" + e.infInut.cStat + "\n" + e.infInut.xMotivo);
			}

			$('#preloader3').css('display', 'none');
		}, error: function(e){
			console.log(e)
			Materialize.toast('Erro de comunicação contate o desenvolvedor', 5000)
			$('#preloader1').css('display', 'none');
		}
	});
	
}

$(function () {
	validaBtns();
})

$('#checkbox input').click(() => {
	validaBtns();
})

function validaBtns(){
	let cont = 0;
	let estado = "";
	$('#body tr').each(function(){
		if($(this).find('#checkbox input').is(':checked')){
			id = $(this).find('#id').html();
			estado = $(this).find('#estado_'+id).html();
			cont++;
		}
	})

	if(cont > 1 || cont == 0){
		desabilitaBotoes();
	}else{
		habilitaBotoes();
		if(estado == 'DISPONIVEL'){
			$('#btn-enviar').removeClass("disabled");
			$('#btn-imprimir').addClass("disabled");
			$('#btn-consultar').addClass("disabled");
			$('#btn-cancelar').addClass("disabled");
			$('#btn-inutilizar').removeClass("disabled");
			$('#btn-correcao').addClass("disabled");
			$('#btn-xml').addClass("disabled");

			$('#btn-danfe').removeClass("disabled");
			$('#btn-imprimir-cce').addClass("disabled");
			$('#btn-imprimir-cancelar').addClass("disabled");


		} else if(estado == 'REJEITADO'){
			$('#btn-enviar').removeClass("disabled");
			$('#btn-imprimir').addClass("disabled");
			$('#btn-inutilizar').removeClass("disabled");
			$('#btn-correcao').addClass("disabled");

			$('#btn-consultar').removeClass("disabled");
			$('#btn-cancelar').addClass("disabled");
			$('#btn-xml').addClass("disabled");

			$('#btn-danfe').removeClass("disabled");
			$('#btn-imprimir-cce').addClass("disabled");
			$('#btn-imprimir-cancelar').addClass("disabled");


		} else if(estado == 'CANCELADO'){
			$('#btn-enviar').addClass("disabled");
			$('#btn-inutilizar').addClass("disabled");
			$('#btn-correcao').addClass("disabled");
			$('#btn-imprimir').addClass("disabled");
			$('#btn-consultar').removeClass("disabled");
			$('#btn-cancelar').addClass("disabled");
			$('#btn-xml').addClass("disabled");

			$('#btn-danfe').removeClass("disabled");
			$('#btn-imprimir-cce').addClass("disabled");
			$('#btn-imprimir-cancelar').removeClass("disabled");


		} else if(estado == 'APROVADO'){
			$('#btn-enviar').addClass("disabled");
			$('#btn-inutilizar').addClass("disabled");
			$('#btn-imprimir').removeClass("disabled");
			$('#btn-consultar').removeClass("disabled");
			$('#btn-cancelar').removeClass("disabled");
			$('#btn-correcao').removeClass("disabled");
			$('#btn-xml').removeClass("disabled");

			$('#btn-danfe').addClass("disabled");
			$('#btn-imprimir-cce').removeClass("disabled");
			$('#btn-imprimir-cancelar').addClass("disabled");
		}

	}
}

function desabilitaBotoes(){
	$('#btn-enviar').addClass("disabled");
	$('#btn-imprimir').addClass("disabled");
	$('#btn-consultar').addClass("disabled");
	$('#btn-cancelar').addClass("disabled");
	$('#btn-correcao').addClass("disabled");
	$('#btn-inutilizar').addClass("disabled");
	$('#btn-xml').addClass("disabled");
	$('#btn-danfe').addClass("disabled");
	$('#btn-imprimir-cce').addClass("disabled");
	$('#btn-imprimir-cancelar').addClass("disabled");

}


function habilitaBotoes(){
	$('#btn-enviar').removeClass("disabled");
	$('#btn-imprimir').removeClass("disabled");
	$('#btn-consultar').removeClass("disabled");
	$('#btn-cancelar').removeClass("disabled");
	$('#btn-correcao').removeClass("disabled");
	$('#btn-inutilizar').removeClass("disabled");
	$('#btn-xml').removeClass("disabled");

}

function enviarEmailXMl(){
	$('#preloader6').css('display', 'block');
	
	let id = $('#venda_id').val();
	let email = $('#email').val();

	$.get(path+'nf/enviarXml', {id: id, email: email})
	.done(function(data){
		console.log(data)
		$('#preloader6').css('display', 'none');
		alert('Email enviado com sucesso!');
	})
	.fail(function(err){
		console.log(err)
		$('#preloader6').css('display', 'none');
		alert('Erro ao enviar email!')
	})
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

$('#btn-danfe').click(() => {
	let id = 0
	$('#body tr').each(function(){
		if($(this).find('#checkbox input').is(':checked'))
			id = $(this).find('#id').html();
	})
	window.open(path + 'vendas/rederizarDanfe/' + id);
})

