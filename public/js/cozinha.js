$(function () {

	console.log('buscando')
	buscar((res) => {
		console.log(res)
		if(res != false){
			montaHtml(res)
		}
	})

	setInterval(() => {
		$('.progress').css('display', 'block')
		buscar((res) => {
			if(res != false){
				montaHtml(res)
				$('.progress').css('display', 'none')

			}
		})
	}, 5000)

})

function buscar(call){
	$.get(path+'controleCozinha/buscar')
	.done((data) => {
		call(data)
	})
	.fail((err) => {
		console.log(err)
		call(false)
	})
}

function pronto(id){
	console.log(id)
	let js = {
		id: id
	}

	$.get(path+'controleCozinha/concluido', js)
	.done((success) => {
		console.log(success)
		location.reload()
	})
	.fail((err) => {
		console.log(err)
	})
}

function montaHtml(obj){
	let html = '';
	obj.map((v) => {
		console.log(v)
		criaDiv(v.comanda, v.produto.nome, v.quantidade, v.data, v.id, 
			v.adicionais, v.saboresPizza, v.tamanhoPizza, (res) => {

				html += res;
			})
	})
	$('#itens').html(html);

	$('.progress').css('display', 'none')
}

function criaDiv(comanda, nome, quantidade, data, item_id, adicionais, 
	saboresPizza, tamanhoPizza, call){
	console.log(tamanhoPizza)
	let html = '<div class="col s12 m12 l6">'+
	'<div class="card">'+
	'<div class="row">'+
	'<div class="card-header"><br>'+

	'<h5 class="center-align">Mesa/Comanda <strong class="blue-text">'+
	comanda+'</strong> <strong class="red-text">'+data+'</strong></h5>'+

	'</div>'+

	'<div class="card-content">'+


	'<h6>Item: <strong class="red-text">'+nome+'</strong> '+ (tamanhoPizza != false ? ' - Tamanho: <strong class="red-text">'+tamanhoPizza+'</strong>' : '')+
	'<h6>Quantidade: <strong class="red-text">'+quantidade+'</strong></h6>'+
	'<h6>Adicionais: <strong class="red-text">'+adicionais+'</strong></h6>'+


	'<h6>Sabores: <strong class="red-text">'+saboresPizza+'</strong></h6>'+



	'</div>'+

	'<div class="card-footer">'+
	'<a onclick="pronto('+item_id+')" style="width: 100%" href="#!" class="btn btn-large green accent-3">'+
	'<i class="material-icons right">check</i> Pronto</a>'+
	'</div>'+

	'</div>'+
	'</div>'+
	'</div>';



	call(html);
}


