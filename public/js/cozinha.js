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

function pronto(id, ehDelivery){
	console.log(id)
	let js = {
		id: id,
		ehDelivery: ehDelivery ? 1 : 0
	}	

	$.get(path+'controleCozinha/concluido', js)
	.done((success) => {
		console.log(success)
		swal("Sucesso", "Item pronto", "success")
		.then(v => {
			location.reload()
		})
	})
	.fail((err) => {
		swal("Erro", "Algo deu errado", "warning")

		console.log(err)
	})
}

function montaHtml(obj){
	let html = '';
	contDelivery = 0;
	contComanda = 0
	obj.map((v) => {
		if(v.comanda == null){
			contDelivery++;
		}else{
			contComanda++;
		}
		let nome = v.produto.nome;
		if(!nome) nome = v.produto.produto.nome;
		criaDiv(v.comanda, nome, v.quantidade, v.data, v.id, 
			v.adicionais, v.saboresPizza, v.tamanhoPizza, v.pedido_id, (res) => {

				html += res;
			})
	})
	$('#contDelivery').html(contDelivery);
	$('#contComanda').html(contComanda);
	$('#itens').html(html);

	$('.progress').css('display', 'none')
}

function criaDiv(comanda, nome, quantidade, data, item_id, adicionais, 
	saboresPizza, tamanhoPizza, pedidoId, call){

	let tipo = comanda != null ? 'Comanda' : 'Item Delivery Pedido <strong class="blue-text">' + pedidoId + '</strong>';

	let html = '<div class="col s12 m12 l6">'+
	'<div class="card">'+
	'<div class="row">'+
	'<div class="card-header"><br>'+

	'<h5 class="center-align">'+tipo+' <strong class="blue-text">'+
	(comanda != null ? comanda : '') +'</strong> <strong class="red-text">'+data+'</strong></h5>'+

	'</div>'+

	'<div class="card-content">'+


	'<h6>Item: <strong class="red-text">'+nome+'</strong> '+ (tamanhoPizza != false ? ' - Tamanho: <strong class="red-text">'+tamanhoPizza+'</strong>' : '')+
	'<h6>Quantidade: <strong class="red-text">'+quantidade+'</strong></h6>'+
	'<h6>Adicionais: <strong class="red-text">'+adicionais+'</strong></h6>'+


	'<h6>Sabores: <strong class="red-text">'+saboresPizza+'</strong></h6>'+



	'</div>'+

	'<div class="card-footer">'+
	'<a onclick="pronto('+item_id+', '+ (comanda == null ? true : false) +')" style="width: 100%" href="#!" class="btn btn-large green accent-3">'+
	'<i class="material-icons right">check</i> Pronto</a>'+
	'</div>'+

	'</div>'+
	'</div>'+
	'</div>';



	call(html);
}


