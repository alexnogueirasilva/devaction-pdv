

function redireciona(){
	location.href= path + "cte";
}

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
			id: id,
			_token: token
		},
		url: path + 'cteSefaz/enviar',
		dataType: 'json',
		success: function(e){
			$('#preloader1').css('display', 'none');

			console.log(e)
			let recibo = e;
			let retorno = recibo.substring(0,4);
			let mensagem = recibo.substring(5,recibo.length);
			if(retorno == 'Erro'){
				let m = JSON.parse(mensagem);
				console.log(m.protCTe.infProt.xMotivo)
				// alert("[" + m.protCTe.infProt.cStat + "] : " + m.protCTe.infProt.xMotivo)
				$('#modal-alert-erro').modal('open');
				$('#evento-erro').html("[" + m.protCTe.infProt.cStat + "] : " + m.protCTe.infProt.xMotivo)
			}
			else if(e == 'Apro'){
				alert("Esta CT-e já esta aprovada, não é possível enviar novamente!");
			}
			else{
				$('#modal-alert').modal('open');
				$('#evento').html("CT-e gerada com sucesso RECIBO: "+recibo)
				window.open(path+"/cteSefaz/imprimir/"+id, "_blank");
				// location.href= path + "cte";
			}

			$('#preloader1').css('display', 'none');
		}, error: function(e){
			Materialize.toast('Erro de comunicação contate o desenvolvedor', 5000)
			console.log(e)
			$('#preloader1').css('display', 'none');
		}
	});
	
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
		window.open(path+"/cteSefaz/imprimir/"+id, "_blank");
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
		window.open(path+"/cteSefaz/imprimirCCe/"+id, "_blank");
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
		window.open(path+"/cteSefaz/imprimirCancela/"+id, "_blank");
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
			url: path + 'cteSefaz/consultar',
			dataType: 'json',
			success: function(e){
				console.log(e)
				let js = JSON.parse(e)
				console.log(js)
				$('#motivo').html(js.xMotivo);
				$('#chave').html(js.protCTe.infProt.chCTe);
				$('#protocolo').html(js.protCTe.infProt.nProt);
				$('#modal2').modal('open');
				$('#preloader1').css('display', 'none');
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
			$('#numero_cte').val(nf)

			console.log(nf)

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

		$.get(path+'cteSefaz/consultar_cliente/'+id)
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
			url: path + 'cteSefaz/cancelar',
			dataType: 'json',
			success: function(e){
				let js = JSON.parse(e);
				console.log(js)
				$('#preloader5').css('display', 'none');

				if(js.infEvento.cStat == '101' || js.infEvento.cStat == '135' || js.infEvento.cStat == '155'){
					$('#modal-alert-cancel').modal('open');
					$('#evento-cancel').html(js.infEvento.xMotivo)
				}else{
					$('#modal-alert-cancel-erro').modal('open');
					$('#evento-cancel').html(js.infEvento.xMotivo)
				}

			}, error: function(e){
				console.log(e)
				Materialize.toast('Erro de comunicação contate o desenvolvedor', 5000)
				$('#preloader5').css('display', 'none');
			}
		});
	}
}

function reload(){
	location.reload();
}

function cartaCorrecao(){
	$('#preloader4').css('display', 'block');

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
		let token = $('#_token').val();
		$.ajax
		({
			type: 'POST',
			data: {
				id: id,
				correcao: $('#correcao').val(),
				grupo: $('#grupo').val(),
				campo: $('#campo').val(),
				_token: token
			},
			url: path + 'cteSefaz/cartaCorrecao',
			dataType: 'json',
			success: function(e){
				console.log(e)
				let js = JSON.parse(e);
				console.log(js)
				$('#preloader4').css('display', 'none');
				// alert(js.infEvento.xMotivo)
				$('#modal4').modal('close')
				if(js.infEvento.cStat == '135'){
					$('#evento').html(js.infEvento.xMotivo)
					$('#modal-alert').modal('open')
				}else{
					$('#evento-erro').html(js.infEvento.xMotivo)
					$('#modal-alert-erro').modal('open')
				}
				
			}, error: function(e){
				console.log(e)
				Materialize.toast('Erro de comunicação contate o desenvolvedor', 5000)
				$('#preloader4').css('display', 'none');
			}
		});
	}
}

function inutilizar(){

	let justificativa = $('#justificativa-inut').val();
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
		url: path + 'cteSefaz/inutilizar',
		dataType: 'json',
		success: function(js){
			console.log(js)

			$('#preloader3').css('display', 'none');
			$('#modal3').modal('close');

			console.log(js.infInut.cStat)
			if(js.infInut.cStat == '102' || js.infInut.cStat == '135' || js.infInut.cStat == '155'){
				$('#modal-alert-inut').modal('open');
				$('#evento-inut').html(js.infInut.xMotivo)
				
			}else{
				$('#modal-alert-inut-erro').modal('open');
				$('#evento-inut-erro').html("[" + js.infInut.cStat + "] - " + js.infInut.xMotivo)
			}

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
		desabilitaBotoes(cont);
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


		} else if(estado == 'REJEITADO'){
			$('#btn-enviar').removeClass("disabled");
			$('#btn-imprimir').addClass("disabled");
			$('#btn-inutilizar').removeClass("disabled");
			$('#btn-correcao').addClass("disabled");

			$('#btn-consultar').removeClass("disabled");
			$('#btn-cancelar').addClass("disabled");
			$('#btn-xml').addClass("disabled");


		} else if(estado == 'CANCELADO'){
			$('#btn-enviar').addClass("disabled");
			$('#btn-inutilizar').addClass("disabled");
			$('#btn-correcao').addClass("disabled");
			$('#btn-imprimir').addClass("disabled");
			$('#btn-consultar').removeClass("disabled");
			$('#btn-cancelar').addClass("disabled");
			$('#btn-xml').addClass("disabled");


		} else if(estado == 'APROVADO'){
			$('#btn-enviar').addClass("disabled");
			$('#btn-inutilizar').addClass("disabled");
			$('#btn-imprimir').removeClass("disabled");
			$('#btn-consultar').removeClass("disabled");
			$('#btn-cancelar').removeClass("disabled");
			$('#btn-correcao').removeClass("disabled");
			$('#btn-xml').removeClass("disabled");
		}

	}
}

function desabilitaBotoes(cont){
	$('#btn-enviar').addClass("disabled");
	$('#btn-imprimir').addClass("disabled");
	$('#btn-consultar').addClass("disabled");
	$('#btn-cancelar').addClass("disabled");
	$('#btn-correcao').addClass("disabled");
	$('#btn-inutilizar').addClass("disabled");
	$('#btn-xml').addClass("disabled");

	if(cont == 0) $('#btn-inutilizar').removeClass("disabled");

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
	
	let id = $('#numero_cte').val();
	let email = $('#email').val();

	$.get(path+'cteSefaz/enviarXml', {id: id, email: email})
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


