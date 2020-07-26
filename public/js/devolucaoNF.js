$(function () {
	validaBtns();
})

$('#checkbox input').click(() => {
	validaBtns();
})
function enviar(){
	let id = 0
	let cont = 0;
	$('#body tr').each(function(){
		if($(this).find('#checkbox input').is(':checked')){
			id = $(this).find('#id').html();
			cont++;
		}
	})

	if(cont > 1){
		alert('Por favor selecione apenas um item da tabela!')
	}else{
		if(cont > 0){
			$('#preloader1').css('display', 'block');

			let token = $('#_token').val();

			$.ajax
			({
				type: 'POST',
				data: {
					devolucao_id: id,
					_token: token
				},
				url: path + 'devolucao/enviarSefaz',
				dataType: 'json',
				success: function(e){

					console.log(e)
					let recibo = e;
					let retorno = recibo.substring(0,4);
					console.log(retorno)
					let mensagem = recibo.substring(5,recibo.length);
					if(retorno == 'Erro'){
						let m = JSON.parse(mensagem);

						$('#modal-alert-erro').modal('open');
						$('#evento-erro').html("[" + m.protNFe.infProt.cStat + "] : " + m.protNFe.infProt.xMotivo)

					}
					else if(e == 'false'){
						alert("Esta NF já esta aprovada, não é possível enviar novamente!");
					}
					else{
						$('#modal-alert').modal('open');
						$('#evento').html("Devolução emitida com sucesso RECIBO: "+recibo)
						window.open(path+"/devolucao/imprimir/"+id, "_blank");
					}

					$('#preloader1').css('display', 'none');
				}, error: function(e){
					Materialize.toast('Erro de comunicação contate o desenvolvedor', 5000)
					console.log(e)
					$('#preloader1').css('display', 'none');
				}
			});
		}else{
			alert('Selecione um documento para envio!')
		}
	}
}

function imprimir(){
	let id = 0;
	let cont = 0;
	$('#body tr').each(function(){
		if($(this).find('#checkbox input').is(':checked')){
			id = $(this).find('#id').html();
			cont++;
		}
	})
	if(cont > 1){
		alert('Selecione apenas um documento da lista!');
	}else{
		if(id > 0){
			window.open(path+"/devolucao/imprimir/"+id, "_blank");
		}else{
			Materialize.toast('Selecione um documento para imprimir', 4000)

		}
	}

}

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

	console.log(estado)

	if(cont > 1 || cont == 0){
		desabilitaBotoes();
	}else{
		habilitaBotoes();
		if(estado == 0){
			$('#btn-enviar').removeClass("disabled");
			$('#btn-imprimir').addClass("disabled");

			$('#btn-cancelar').addClass("disabled");


		} else if(estado == 2){
			$('#btn-enviar').removeClass("disabled");
			$('#btn-imprimir').addClass("disabled");

			$('#btn-cancelar').addClass("disabled");

		}  else if(estado == 1){
			$('#btn-enviar').addClass("disabled");
			$('#btn-imprimir').removeClass("disabled");

			$('#btn-cancelar').removeClass("disabled");
		} else if(estado == 3){
			$('#btn-enviar').addClass("disabled");
			$('#btn-imprimir').removeClass("disabled");

			$('#btn-cancelar').addClass("disabled");
		}

	}
}

function desabilitaBotoes(){
	$('#btn-enviar').addClass("disabled");
	$('#btn-imprimir').addClass("disabled");
	$('#btn-cancelar').addClass("disabled");

}

function habilitaBotoes(){
	$('#btn-enviar').removeClass("disabled");
	$('#btn-imprimir').removeClass("disabled");
	$('#btn-cancelar').removeClass("disabled");

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
				devolucao_id: id,
				justificativa: justificativa,
				_token: token
			},
			url: path + 'devolucao/cancelar',
			dataType: 'json',
			success: function(e){
				$('#modal1').modal('close');
				console.log(e)
				$('#preloader5').css('display', 'none');

				let js = JSON.parse(e);
				// console.log(js)
				// alert(js.retEvento.infEvento.xMotivo)
				$('#modal-sucesso-cancela').modal('open');
				$('#evento-cancela').html("[" + js.retEvento.infEvento.cStat + "] : " + js.retEvento.infEvento.xMotivo)

				// $('#preloader5').css('display', 'none');
			}, error: function(e){
				console.log(e)
				Materialize.toast('Erro de comunicação contate o desenvolvedor', 5000)
				$('#preloader5').css('display', 'none');
			}
		});
	}
}

function redireciona(){
	location.reload();
}

