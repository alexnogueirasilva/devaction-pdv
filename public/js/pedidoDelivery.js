$('#btn-enviar-push').click(() => {

	let titulo = $('#titulo-push').val();
	let texto = $('#texto-push').val();
	let imagem = $('#imagem-push').val();

	let js = {
		titulo: titulo,
		texto: texto,
		imagem: imagem,
		cliente: $('#cliente').val(),
		_token: $('#token').val()
	}
	console.log(path)
	$.post(path+'pedidosDelivery/sendPush', js)
	.done((success) => {
		console.log(success)
		alert('Push Enviado')
		$('#modal-push').modal('close')

	})
	.fail((err) => {
		console.log(err)
		alert('Erro ao enviar Push')
	})
})

function setaTelefone(telefone){
	telefone = telefone.replace(" ", "").replace("-", "")
	$('#telefone-sms').val(telefone)
	Materialize.updateTextFields();
}

$('#btn-enviar-sms').click(() => {


	let texto = $('#texto-sms').val();
	let telefone = $('#telefone-sms').val();

	let js = {
		telefone: telefone,
		texto: texto,
		cliente: $('#cliente').val(),
		_token: $('#token').val()
	}
	console.log(js)
	$.post(path+'pedidosDelivery/sendSms', js)
	.done((success) => {
		console.log(success)
		alert('SMS Enviado')
		$('#modal-sms').modal('close')

	})
	.fail((err) => {
		console.log(err)
		alert('Erro ao enviar SMS')
	})
})

function consultar(codigo){
	$('#preloader').css('display', 'block')
	console.log(codigo)
	$.get(path + "/pagseguro/consultaJS", {codigo: codigo})
	.done((success) => {
		$('#preloader').css('display', 'none')

		console.log(success)
		let status = success.status[0];
		let referencia = success.referencia[0];
		let total = success.total[0];
		let taxa = success.taxa[0];

		if(status == '3'){
			status = 'Aprovada';
		}else{
			status = 'Reprovada ' + status;
		}

		$('#status').html(status)
		$('#referencia').html(referencia)
		$('#total').html(total)
		$('#taxa').html(taxa)
		$('#modal-consulta').modal('open');
	})
	.fail((err) => {
		$('#preloader').css('display', 'none')
		
		console.log(err)
		alert("Ocorreu um erro ao consultar o código: " + codigo)
	})
}