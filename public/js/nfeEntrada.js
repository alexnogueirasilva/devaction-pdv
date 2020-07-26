
function enviar(id){
	var r = confirm("Deseja gerar entrada fiscal desta Compra?");
	if (r == true) {
		
		$('#preloader').css('display', 'block')
		let token = $('#_token').val();
		$.ajax
		({
			type: 'POST',
			data: {
				compra_id: id,
				natureza: $('#natureza').val(),
				tipo_pagamento: $('#tipo_pagamento').val(),
				_token: token
			},
			url: path + 'compras/gerarEntrada',
			dataType: 'json',
			success: function(e){
				$('#preloader').css('display', 'none')

				console.log(e)

				$('#modal-alert').modal('open');
				$('#evento').html("NF-e de Entrada emitida com sucesso RECIBO: "+e)
				window.open(path+"compras/imprimir/"+id, "_blank");

			}, error: function(e){
				$('#preloader').css('display', 'none')

				let js = e.responseJSON;

				let mensagem = js.substring(5,js.length);
				js = JSON.parse(mensagem)
				console.log(js)

				$('#modal-alert-erro').modal('open');
				$('#evento-erro').html("[" + js.protNFe.infProt.cStat + "] : " + js.protNFe.infProt.xMotivo);


			}
		});
	}else{

	}

}

function redireciona(){
	location.reload();
}

function cancelar(){
	$('#preloader5').css('display', 'block')
	let token = $('#_token').val();
	$.ajax
	({
		type: 'POST',
		data: {
			compra_id: $('#compra_id').val(),
			justificativa: $('#justificativa').val(),
			_token: token
		},
		url: path + 'compras/cancelarEntrada',
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