
$(function () {
	getProdutos(function(data){
		$('input.autocomplete-produto').autocomplete({
			data: data,
			limit: 20, 
			onAutocomplete: function(val) {
				$('#preloader1').css('display', 'block');
				var prod = $('#autocomplete-produto').val().split('-');
				console.log(prod)
				getProduto(prod[0], (d) => {
					console.log(d)

					$('#valor').val(d.valor_venda.replace(".", ','))
					$('#quantidade').val('1,000')
					$('#preloader1').css('display', 'none');
					calcSubtotal();

				})
			},
			minLength: 1,
		});
	});

})

$('#valor').on('keyup', () => {
	calcSubtotal()
})

function maskMoney(v){
	return v.toFixed(2);
}

function calcSubtotal(){
	let quantidade = $('#quantidade').val();
	let valor = $('#valor').val();
	let subtotal = parseFloat(valor.replace(',','.'))*(quantidade.replace(',','.'));
	console.log(subtotal)
	let sub = maskMoney(subtotal)
	$('#subtotal').val(sub)
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

function setaEmail(){
	buscarDadosCliente();
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

function getProduto(id, data){
	console.log(id)
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

function enviarEmail(){

	$('#preloader6').css('display', 'block');

	let id = 0;
	let cont = 0;
	$('#body tr').each(function(){
		if($(this).find('#checkbox input').is(':checked')){
			id = $(this).find('#id').html();
			cont++
		}
	})

	let email = $('#email').val();

	$.get(path+'orcamentoVenda/enviarEmail', {id: id, email: email})
	.done(function(data){
		console.log(data)
		$('#preloader6').css('display', 'none');
		// alert('Email enviado com sucesso!');
		swal("Sucesso", 'Email enviado com sucesso!', "success")

	})
	.fail(function(err){
		console.log(err)
		$('#preloader6').css('display', 'none');
		// alert('Erro ao enviar email!')
		swal("Erro", 'Erro ao enviar email!', "warning")

	})
}

$('#btn-danfe').click(() => {
	let id = 0
	$('#body tr').each(function(){
		if($(this).find('#checkbox input').is(':checked'))
			id = $(this).find('#id').html();
	})
	window.open(path + 'orcamentoVenda/rederizarDanfe/' + id);
})

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
		window.open(path+"orcamentoVenda/imprimir/"+id, "_blank");
	}
}

function imprimirCompleto(){
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
		window.open(path+"orcamentoVenda/imprimirCompleto/"+id, "_blank");
	}
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