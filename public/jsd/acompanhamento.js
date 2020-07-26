
let adicionais = [];
function selet_add(id, adicional){

	verificaAdicionado(id, (res) => {

		if(res == true){
			$('#adicional_'+id).css('background', '#fff')
			removeElemento(id)
		}else{
			$('#adicional_'+id).css('background', '#81c784')
			adicionais.push({
				'id': adicional.id,
				'valor': adicional.valor
			})
		}

		somaTotal();
	})

}

function removeElemento(elem_id){
	let temp = [];
	adicionais.map((v) => {
		if(v.id != elem_id){
			temp.push(v)
		}
	});

	adicionais = temp;
}

function verificaAdicionado(elem_id, call){
	let b= false;
	adicionais.map((v) => {
		if(v.id == elem_id){

			b = true;
		}
	});
	call(b);
}

function somaTotal(){
	let valorProduto = $('#valor_produto').html();
	valorProduto = parseFloat(valorProduto)
	adicionais.map((v) => {
		valorProduto += parseFloat(v.valor);
	})
	$('#valor_total').html(convertMoney(valorProduto))
}

function convertMoney(v){
	return v.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
}


function adicionar(){
	let tk = $('#_token').val();
	let produto_id = $('#produto_id').val();
	let quantidade = $('#quantidade').val();
	let observacao = $('#observacao').val();

	$.post(path + "carrinho/add", 
	{
		_token: tk, 
		produto_id: produto_id,
		adicionais: adicionais,
		quantidade: quantidade,
		observacao: observacao
	})
	.done(function(data) {
		if(data == '401'){
			location.href = path;
		}else if(data == 'false'){
			alert("Você está com um pedido pendente, aguarde o processamento");
		}else{
			sucesso();
		}
	})
	.fail( function(err) {
		console.log(err)

	});

}

function sucesso(){
	$('#content').css('display', 'none');
	$('#anime').css('display', 'block');
	setTimeout(() => {
		location.href = path + 'carrinho';
	}, 3000)
}
