
var MEDIDAS = [];
var COMPONENTES = [];
var TOTALQTD = 0;
var REMETENTE = null;
var DESTINATARIO = null;
var xmlValido = false;
var SOMACOMPONENTES = 0;

$(function () {

	var cliente = $('#autocomplete-destinatario').val().split('-');

	if(cliente){
		getCliente(cliente[0], (d) => {
			console.log(d)
			DESTINATARIO = d;
			$('#info-destinatario').css('display', 'block');
			$('#nome-destinatario').html(d.razao_social)
			$('#cnpj-destinatario').html(d.cpf_cnpj)
			$('#ie-destinatario').html(d.ie_rg)
			$('#rua-destinatario').html(d.rua)
			$('#nro-destinatario').html(d.numero)
			$('#bairro-destinatario').html(d.bairro)
			$('#cidade-destinatario').html(d.cidade.nome + "-"+ d.cidade.uf)

			habilitaBtnSalarCTe();
		})
	}

	var remetente = $('#autocomplete-remetente').val().split('-');
	if(remetente){
		getCliente(remetente[0], (d) => {
			console.log(d)
			REMETENTE = d;

			$('#info-remetente').css('display', 'block');
			$('#nome-remetente').html(d.razao_social)
			$('#cnpj-remetente').html(d.cpf_cnpj)
			$('#ie-remetente').html(d.ie_rg)
			$('#rua-remetente').html(d.rua)
			$('#nro-remetente').html(d.numero)
			$('#bairro-remetente').html(d.bairro)
			$('#cidade-remetente').html(d.cidade.nome + "-"+ d.cidade.uf)

			habilitaBtnSalarCTe();
		})
	}

	let chave = removeEspacoChave();
	if(chave){
		chaveNfeDuplicada(chave, (chRes) => {
			if(chRes == false){
				$.get(path+'cte/consultaChave', {chave: chave})
				.done((data) => {
					data = JSON.parse(data);
					console.log(data)

					if(data.xMotivo == 'Autorizado o uso da NF-e'){
						xmlValido = true;
						$('#chave_nfe').attr('disabled', true)
					}else{
						xmlValido = false;

					}
					habilitaBtnSalarCTe();
				})
				.fail(function(err){
					console.log(err)
					xmlValido = false;
				})
			}else{
				$('#chave_nfe').val('');
				$('#chave-referenciada').css('display', 'block')

			}
		});
	}

	getCidades(function(data){
		$('input.autocomplete-cidade-envio').autocomplete({
			data: data,
			limit: 20, 
			onAutocomplete: function(val) {
			},
			minLength: 1,
		});
		$('input.autocomplete-cidade-inicio').autocomplete({
			data: data,
			limit: 20, 
			onAutocomplete: function(val) {
			},
			minLength: 1,
		});
		$('input.autocomplete-cidade-final').autocomplete({
			data: data,
			limit: 20, 
			onAutocomplete: function(val) {
			},
			minLength: 1,
		});
		$('input.autocomplete-cidade-tomador').autocomplete({
			data: data,
			limit: 20, 
			onAutocomplete: function(val) {
			},
			minLength: 1,
		});
	});
	getClientes(function(data){
		$('input.autocomplete-remetente').autocomplete({
			data: data,
			limit: 20, 
			onAutocomplete: function(val) {
				var cliente = $('#autocomplete-remetente').val().split('-');
				getCliente(cliente[0], (d) => {
					console.log(d)
					REMETENTE = d;
					
					$('#info-remetente').css('display', 'block');
					$('#nome-remetente').html(d.razao_social)
					$('#cnpj-remetente').html(d.cpf_cnpj)
					$('#ie-remetente').html(d.ie_rg)
					$('#rua-remetente').html(d.rua)
					$('#nro-remetente').html(d.numero)
					$('#bairro-remetente').html(d.bairro)
					$('#cidade-remetente').html(d.cidade.nome + "-"+ d.cidade.uf)
					
					habilitaBtnSalarCTe();
				})
			},
			minLength: 1,
		});

		$('input.autocomplete-destinatario').autocomplete({
			data: data,
			limit: 20, 
			onAutocomplete: function(val) {
				var cliente = $('#autocomplete-destinatario').val().split('-');
				getCliente(cliente[0], (d) => {
					console.log(d)
					DESTINATARIO = d;
					$('#info-destinatario').css('display', 'block');
					$('#nome-destinatario').html(d.razao_social)
					$('#cnpj-destinatario').html(d.cpf_cnpj)
					$('#ie-destinatario').html(d.ie_rg)
					$('#rua-destinatario').html(d.rua)
					$('#nro-destinatario').html(d.numero)
					$('#bairro-destinatario').html(d.bairro)
					$('#cidade-destinatario').html(d.cidade.nome + "-"+ d.cidade.uf)
					
					habilitaBtnSalarCTe();
				})
			},
			minLength: 1,
		});
	});

});

function removeEspacoChave(){
	let chave = $('#chave_nfe').val();
	return chave.replace(' ', '').replace(' ', '').replace(' ', '')
	.replace(' ', '').replace(' ', '').replace(' ', '').replace(' ', '')
	.replace(' ', '').replace(' ', '').replace(' ', '');
}

$('.type-ref').on('keyup', () => {
	habilitaBtnSalarCTe();
})

$('#file').change(function() {
	$('#form-import').submit();
});

$('#chave_nfe').on('keyup', () => {
	console.log('passou');
	
	let chave = removeEspacoChave();
	console.log(xmlValido)
	if(chave.length == 44 && xmlValido == false){

		chaveNfeDuplicada(chave, (chRes) => {
			if(chRes == false){
				$.get(path+'cte/consultaChave', {chave: chave})
				.done((data) => {
					data = JSON.parse(data);
					console.log(data)

					if(data.xMotivo == 'Autorizado o uso da NF-e'){
						xmlValido = true;
						$('#chave_nfe').attr('disabled', true)
					}else{
						xmlValido = false;

					}
					habilitaBtnSalarCTe();
				})
				.fail(function(err){
					console.log(err)
					xmlValido = false;
				})
			}else{
				$('#chave_nfe').val('');
				$('#chave-referenciada').css('display', 'block')

			}
		});
	}
});

$('.ref-nfe').click(() => {
	$('#descOutros').val("")
	$('#nDoc').val("")
	$('#vDocFisc').val("")
})

$('.ref-out').click(() => {
	$('#chave_nfe').val("")

})

function chaveNfeDuplicada(chave, call){

	$.get(path+'cte/chaveNfeDuplicada', {chave: chave})
	.done((success) => {
		call(success)
	})
	.fail((err) => {
		console.log(err)
		call(err)
	})
}

$('input.autocomplete-remetente').on('keyup', () => {
	var cliente = $('#autocomplete-remetente').val().split('-');
	if(!cliente[0] || !cliente[1] && REMETENTE != null){
		$('input.autocomplete-remetente').val('')
	}
})


function getClientes(data){
	$.ajax
	({
		type: 'GET',
		url: path + 'clientes/all',
		dataType: 'json',
		success: function(e){
			data(e)
		}, error: function(e){
			console.log(e)
		}

	});
}

function getCliente(id, data){
	$.ajax
	({
		type: 'GET',
		url: path + 'clientes/find/'+id,
		dataType: 'json',
		success: function(e){
			data(e)

		}, error: function(e){
			console.log(e)
		}

	});
}

function habilitaBtnSalarCTe(){
	console.log("testando")
	let tipoDocumento = false;
	let inputs = false;

	if(!xmlValido && $('#descOutros').val() != "" && $('#nDoc').val() != "" && $('#vDocFisc').val() != ""){
		tipoDocumento = true;
	}else if(xmlValido && $('#descOutros').val() == "" && $('#nDoc').val() == "" && 
		$('#vDocFisc').val() == ""){
		tipoDocumento = true
	}

	if($('#prod_predominante').val() != "" && $('#valor_carga').val() != ""
		&& $('#valor_transporte').val() != "" && $('#valor_receber').val() != ""
		&& $('#autocomplete-cidade-envio').val() != "" && $('#autocomplete-cidade-inicio').val() != "" 
		&& $('#autocomplete-cidade-final').val() != ""){
		inputs = true;
}

console.log(tipoDocumento)

if(MEDIDAS.length > 0 && COMPONENTES.length > 0 && DESTINATARIO != null && 
	REMETENTE != null &&
	tipoDocumento && inputs){
	$('#finalizar').removeClass('disabled')

}
}

$('#endereco-destinatario').click(() => {
	let v = $('#endereco-destinatario').is(':checked');
	$('#endereco-remetente').prop('checked', false);
	if(v){
		if(DESTINATARIO){
			$('#rua_tomador').val(DESTINATARIO.rua)
			$('#numero_tomador').val(DESTINATARIO.numero)
			$('#bairro_tomador').val(DESTINATARIO.bairro)
			$('#cep_tomador').val(DESTINATARIO.cep)
			$('#autocomplete-cidade-tomador').val(DESTINATARIO.cidade.id +' - '+
				DESTINATARIO.cidade.nome)

			habilitaCampos();

		}else{
			alert('Destinatário não selecionado!');
			$('#endereco-destinatario').prop('checked', false); 
			
		}
	}else{
		desabilitaCampos();
	}
})

$('#endereco-remetente').click(() => {
	let v = $('#endereco-remetente').is(':checked');
	$('#endereco-destinatario').prop('checked', false);
	if(v){
		if(REMETENTE){
			$('#rua_tomador').val(REMETENTE.rua)
			$('#numero_tomador').val(REMETENTE.numero)
			$('#bairro_tomador').val(REMETENTE.bairro)
			$('#cep_tomador').val(REMETENTE.cep)
			$('#autocomplete-cidade-tomador').val(REMETENTE.cidade.id +' - '+
				REMETENTE.cidade.nome)

			habilitaCampos();

		}else{
			alert('Remetente não selecionado!');
			$('#endereco-remetente').prop('checked', false); 
		}
	}else{
		desabilitaCampos();
	}
})

function habilitaCampos(){
	$('#rua_tomador').prop('disabled', true)
	$('#numero_tomador').prop('disabled', true)
	$('#bairro_tomador').prop('disabled', true)
	$('#cep_tomador').prop('disabled', true)
	$('#autocomplete-cidade-tomador').prop('disabled', true)
}

function desabilitaCampos(){
	$('#rua_tomador').removeAttr('disabled')
	$('#numero_tomador').removeAttr('disabled')
	$('#bairro_tomador').removeAttr('disabled')
	$('#cep_tomador').removeAttr('disabled')
	$('#autocomplete-cidade-tomador').removeAttr('disabled')
}

function getCidades(data){
	$.ajax
	({
		type: 'GET',
		url: path + 'cidades/all',
		dataType: 'json',
		success: function(e){
			data(e)

		}, error: function(e){
			console.log(e)
		}

	});
}

$('#addComponente').click(() => {
	let nome_componente = $('#nome_componente').val();
	let valor_componente = $('#valor_componente').val();
	COMPONENTES.push({id: (COMPONENTES.length+1), valor: valor_componente,
		nome: nome_componente});
	let t = montaTabelaComponentes();
	$('#componentes tbody').html(t)
	habilitaBtnSalarCTe();
});

$('#addMedida').click(() => {
	let unidade_medida = $('#unidade_medida').val();
	let tipo_medida = $('#tipo_medida').val();
	let quantidade = $('#quantidade_carga').val();
	if(quantidade.includes(',')){
		MEDIDAS.push({id: (MEDIDAS.length+1), unidade_medida: unidade_medida,
			tipo_medida: tipo_medida, quantidade: quantidade});

		console.log(MEDIDAS)
		let t = montaTabela();
		$('#prod tbody').html(t)

		habilitaBtnSalarCTe()
	}else{
		alert('Quantidade inválida, utilize 4 casas decimais exemplo: 1,0000')
	}
});

function montaTabela(){
	let t = ""; 
	MEDIDAS.map((v) => {
		t += "<tr>";
		t += "<td>"+v.id+"</td>";
		t += "<td>"+v.unidade_medida+"</td>";
		t += "<td>"+v.tipo_medida+"</td>";
		t += "<td>"+v.quantidade+"</td>";
		t += "<td><a href='#prod tbody' onclick='deleteItem("+v.id+")'>"
		t += "<i class=' material-icons red-text'>delete</i></a></td>";
		t+= "</tr>";
	});
	return t;
}

$('#autocomplete-remetente').focus(() => {
	$('#info-remetente').css('display', 'none');
	REMETENTE = null;
})

$('#autocomplete-destinatario').focus(() => {
	$('#info-destinatario').css('display', 'none');
	DESTINATARIO = null;
})

function montaTabelaComponentes(){
	let t = ""; 
	SOMACOMPONENTES = 0;
	COMPONENTES.map((v) => {
		t += "<tr>";
		t += "<td>"+v.id+"</td>";
		t += "<td>"+v.nome+"</td>";
		t += "<td>"+v.valor+"</td>";
		t += "<td><a href='#componentes tbody' onclick='deleteComponente("+v.id+")'>"
		t += "<i class=' material-icons red-text'>delete</i></a></td>";
		t+= "</tr>";

		SOMACOMPONENTES += parseFloat(v.valor.replace(',', '.'));
	});
	$('#valor_receber').val(SOMACOMPONENTES.toFixed(2));
	$('#valor_transporte').val(SOMACOMPONENTES.toFixed(2));
	return t;
}

function deleteItem(id){
	let temp = [];
	MEDIDAS.map((v) => {
		if(v.id != id){
			temp.push(v)
		}
	});
	MEDIDAS = temp;
	refatoreItens()
	let t = montaTabela(); // para remover
	$('#prod tbody').html(t)

}

function refatoreItens(){
	let cont = 1;
	let temp = [];
	MEDIDAS.map((v) => {
		v.id = cont;
		temp.push(v)
		cont++;
	})
	MEDIDAS = temp;
}

function deleteComponente(id){
	let temp = [];
	COMPONENTES.map((v) => {
		if(v.id != id){
			temp.push(v)
		}
	});
	COMPONENTES = temp;
	refatoreComponentes()
	let t = montaTabelaComponentes(); // para remover
	$('#componentes tbody').html(t)

}

function refatoreComponentes(){
	let cont = 1;
	let temp = [];
	COMPONENTES.map((v) => {
		v.id = cont;
		temp.push(v)
		cont++;
	})
	COMPONENTES = temp;
}

function salvarCTe(){
	let msg = "";

	let valorTransporte = $('#valor_transporte').val();
	let valorCarga = $('#valor_carga').val();
	let valorReceber = $('#valor_receber').val();
	let data = $('#data_prevista_entrega').val();
	if(valorTransporte == 0 || valorTransporte.length == 0){
		msg += "\nInforme o valor de transporte";
	}

	if(valorCarga == 0 || valorCarga.length == 0){
		msg += "\nInforme o valor da carga";
	}

	if(valorReceber == 0 || valorReceber.length == 0){
		msg += "\nInforme o valor a receber";
	}

	if(data == "" || valorReceber.length == 0){
		msg += "\nInforme a data de entrega";
	}


	if(msg == ""){
		let js = {
			chave_nfe: removeEspacoChave(),
			remetente: parseInt(REMETENTE.id),
			destinatario: parseInt(DESTINATARIO.id),
			tomador: $('#tomador').val(),
			municipio_envio: $('#cidade_envio').val(),
			municipio_inicio: $('#cidade_inicio').val(),
			municipio_fim: $('#cidade_fim').val(),
			numero_tomador: $('#numero_tomador').val(),
			bairro_tomador: $('#bairro_tomador').val(),
			municipio_tomador: $('#autocomplete-cidade-tomador').val(),
			logradouro_tomador: $('#rua_tomador').val(),
			cep_tomador: $('#cep_tomador').val(),
			municipio_envio: $('#autocomplete-cidade-envio').val(),
			municipio_inicio: $('#autocomplete-cidade-inicio').val(),
			municipio_fim: $('#autocomplete-cidade-final').val(),
			medidias: MEDIDAS,
			componentes: COMPONENTES,
			valor_carga: valorCarga,
			valor_receber: $('#valor_receber').val(),
			valor_transporte: valorTransporte,
			produto_predominante: $('#prod_predominante').val(),
			data_prevista_entrega: $('#data_prevista_entrega').val(),
			natureza: $('#natureza').val(),
			obs: $('#obs').val(),
			retira: $('#retira').val(),
			detalhes_retira: $('#detalhes_retira').val(),
			modal: $('#modal-transp').val(),
			veiculo_id: $('#veiculo_id').val(),
			
			tpDoc: $('#tpDoc').val(),
			descOutros: $('#descOutros').val(),
			nDoc: $('#nDoc').val(),
			vDocFisc: $('#vDocFisc').val(),

		}
		console.log(js)
		$.post(path+'cte/salvar', {data: js, _token: $('#_token').val()})
		.done(function(v){
			console.log(v)
			sucesso();
		})
		.fail(function(err){
			console.log(err)
		})
	}else{
		alert("Informe corretamente os campos para continuar!"+msg)

	}
}

function sucesso(){
	$('#content').css('display', 'none');
	$('#anime').css('display', 'block');
	setTimeout(() => {
		location.href = path+'cte';
	}, 4500)
}
