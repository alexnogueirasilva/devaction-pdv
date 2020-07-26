

function redireciona(){
	location.href= path + "mdfe";
}

function enviar(){
	$('#preloader').css('display', 'block');
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
		url: path + 'mdfeSefaz/enviar',
		dataType: 'json',
		success: function(e){
			$('#preloader').css('display', 'none');

			console.log(e)


			$('#modal-alert').modal('open');
			$('#evento').html("MDF-e gerada com sucesso Protocolo: " + e.protocolo)
			window.open(path+"/mdfeSefaz/imprimir/"+id, "_blank");
			

		}, error: function(e){
			// Materialize.toast('Erro de comunicação contate o desenvolvedor', 5000)
			$('#preloader').css('display', 'none');

			console.log(e)
			if(e.status == '403'){
				let js = e.responseJSON;
				console.log(js)
				$('#modal-alert-erro').modal('open');
				$('#evento-erro').html('[' + js.cStat + ']: ' + js.message);
			}
			else if(e.status == '401'){
				let js = e.responseJSON;
				console.log(js)
				$('#modal-alert-erro').modal('open');
				$('#evento-erro').html('Esta ao transmitir');
			}

			if(e.status == '500'){
				let js = e.responseJSON;
				console.log(js)
				$('#modal-alert-erro').modal('open');
				$('#evento-erro').html('Erro no XML');
			}
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
		window.open(path+"mdfeSefaz/imprimir/"+id, "_blank");
	}
}

function consultar(){
	$('#preloader').css('display', 'block');
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
			url: path + 'mdfeSefaz/consultar',
			dataType: 'json',
			success: function(js){

				console.log(js)
				console.log(js.xMotivo)
				$('#motivo').html(js.xMotivo);
				$('#chave').html(js.protMDFe.infProt.chMDFe);
				$('#protocolo').html(js.protMDFe.infProt.nProt);
				$('#modal2').modal('open');
				$('#preloader').css('display', 'none');
			}, error: function(e){
				console.log(e)
				Materialize.toast('Erro de comunicação contate o desenvolvedor', 5000)
				$('#preloader').css('display', 'none');
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
			url: path + 'mdfeSefaz/cancelar',
			dataType: 'json',
			success: function(js){
				console.log(js)
				$('#preloader5').css('display', 'none');


				$('#modal-alert-cancel').modal('open');
				$('#evento-cancel').html(js.infEvento.xMotivo)
				

			}, error: function(e){
				$('#preloader5').css('display', 'none');
				let js = e.responseJSON;
				console.log(js)
				$('#modal-alert-cancel-erro').modal('open');
				$('#evento-erro-cancel').html(js.infEvento.xMotivo)
			}
		});
	}
}

function reload(){
	location.reload();
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
	let id = 0
	$('#body tr').each(function(){
		if($(this).find('#checkbox input').is(':checked'))
			id = $(this).find('#id').html();
	})
	console.log(id);
	$('#preloader6').css('display', 'block');
	

	let email = $('#email').val();

	$.get(path+'mdfeSefaz/enviarXml', {id: id, email: email})
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


