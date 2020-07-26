FORNECEDORES = [];
$('#add').click(() => {
	var forn = $('#autocomplete-fornecedor').val().split('-');
	let fornecedorAtual = $('#fornecedor-atual').val();

	getFornecedor(forn[0], (d) => {

		console.log(fornecedorAtual)
		if(d.id != fornecedorAtual){
			validaDuplicidade(forn[0], (duplicidade) => {

				console.log(duplicidade)
				if(!duplicidade){
					FORNECEDORES.push(d.id);
					let t = '<label>'+
					'<i class="material-icons left green-text">check</i>'+
					d.razao_social+
					'</label>';

					$('#fornecedores').html(t);
					console.log(FORNECEDORES)
					$('#btn-clonar').removeAttr('disabled');

				}else{
					Materialize.toast('Fornecedor ja adicionado!', 4000)
				}
			})
		}else{
			Materialize.toast('Esta cotação já pertence a este fornecedor!', 4000)
			$('#autocomplete-fornecedor').val('')

		}

	})

})



function validaDuplicidade(id, call){
	let t = false;
	FORNECEDORES.map((v) => {
		if(v == id){
			t = true;
		}
	})
	call(t)
}


$('#btn-clonar').click(() => {
	console.log(FORNECEDORES)
	let js = {
		fornecedores: FORNECEDORES,
		cotacao: $('#cotacao').val()
	}

	console.log(js)
	let token = $('#_token').val();
	$.ajax
	({
		type: 'POST',
		data: {
			data: js,
			_token: token
		},
		url: path + 'cotacao/clonarSave',
		dataType: 'json',
		success: function(e){
			console.log(e)
			sucessoClone();
			// sucesso(e)

		}, error: function(e){
			console.log(e)
		}
	});
})

function sucessoClone(){
	$('#content').css('display', 'none');
	$('#anime').css('display', 'block');
	setTimeout(() => {
		location.href = path+'cotacao';
	}, 4000)
}