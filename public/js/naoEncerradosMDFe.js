$(function () {

})


function encerrar(){
	
	let docs = []
	$('#body tr').each(function(){
		if($(this).find('#checkbox input').is(':checked')){
			chave = $(this).find('#chave').html();
			protocolo = $(this).find('#protocolo').html();
			docs.push({
				chave: chave,
				protocolo: protocolo
			})
			docs.push({
				chave: chave,
				protocolo: protocolo
			})
		}
	})

	if(docs.length > 0){
		$('#preloader').css('display', 'block');


		$.post(path + 'mdfeSefaz/encerrar', {data: docs, _token: $('#token').val()})
		.done((success) => {
			console.log(success)
			$('#modal-alert-success').modal('open');
			$('#preloader').css('display', 'none');
		})
		.fail((err) => {
			console.log(err)
			$('#modal-alert-erro').modal('open');
			$('#preloader').css('display', 'none');
		})
	}
}