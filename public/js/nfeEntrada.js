
function enviar(id){
	// var r = confirm("Deseja gerar entrada fiscal desta Compra?");
	swal("Confirmação", "Deseja gerar entrada fiscal desta Compra?", "warning")
	.then(() => {
		
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
				console.log(e)
				$('#preloader').css('display', 'none')

				let js = e.responseJSON;

				let mensagem = js.substring(5,js.length);
				js = JSON.parse(mensagem)
				console.log(js)

				$('#modal-alert-erro').modal('open');
				$('#evento-erro').html("[" + js.protNFe.infProt.cStat + "] : " + js.protNFe.infProt.xMotivo);


			}
		});
	})

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
			$('#preloader5').css('display', 'none');

			// alert(js.retEvento.infEvento.xMotivo)
			swal("Sucesso", js.retEvento.infEvento.xMotivo, "success")
			.then((value) => {
				location.reload();
			});
		}, error: function(e){
			console.log(e)
			Materialize.toast('Erro de comunicação contate o desenvolvedor', 5000)
			$('#preloader5').css('display', 'none');
		}
	});
}