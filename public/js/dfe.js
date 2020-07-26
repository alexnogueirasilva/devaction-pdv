$(function () {
	filtrar();
});

var codigo = "";
var nome = "";
var ncm = "";
var cfop = "";
var unidade = "";
var valor = "";
var valorCompra = "";
var quantidade = "";
var codBarras = "";

var semRegitro;

function filtrar(){
	$('#preloader').css('display', 'block');
	let data_inicial = $('#data_inicial').val();
	let data_final = $('#data_final').val();

	$.get(path + 'dfe/getDocumentos', {data_inicial: data_inicial, data_final: data_final})
	.done((res) => {
		$('#preloader').css('display', 'none');
		montaHTML(res, (html) => {
			$('#tbl').html(html)
		})
	})
	.fail((err) => {
		$('#preloader').css('display', 'none');
		console.log(err)
	})
}

function montaHTML(resultado, call){
	let html = "";
	resultado.map((element) => {
		// console.log(element)
		let acao = "";
		if(element.incluso){
			acao = '<a target="_blank" href="/dfe/download/'+ element.chave[0] +'" class="btn green">Completa</a>';
		}else{
			acao = '<form method="get" action="/dfe/manifestar">';
			acao += '<input type="hidden" name="nome" value="' + element.nome[0] + '">';
			acao += '<input type="hidden" name="cnpj" value="' + element.cnpj[0] + '">';
			acao += '<input type="hidden" name="valor" value="' + element.valor[0] + '">';
			acao += '<input type="hidden" name="data_emissao" value="' + element.data_emissao[0] + '">';
			acao += '<input type="hidden" name="num_prot" value="' + element.num_prot[0] + '">';
			acao += '<input type="hidden" name="chave" value="' + element.chave[0] + '">';
			acao += '<button type="submit" class="btn red">Manifestar</button>';
			acao += '</form>';
		}
		if(element.nome[0]){
			html += "<tr>";
			html += "<td>" + element.nome[0] + "</td>";
			html += "<td>" + element.cnpj[0] + "</td>";
			html += "<td>" + element.valor[0] + "</td>";
			html += "<td>" + element.data_emissao + "</td>";
			html += "<td>" + element.num_prot[0] + "</td>";
			html += "<td>" + element.chave[0] + "</td>";

			html += "<td>";
			html += acao;
			html += "</td>";
		}

	})

	call(html);
}

function _construct(codigo, nome, codBarras, ncm, cfop, unidade, valor, quantidade, valorCompra){
	this.codigo = codigo;
	this.nome = nome;
	this.ncm = ncm;
	this.cfop = cfop;
	this.unidade = unidade;
	this.valor = valor;
	this.valorCompra = valorCompra;
	this.quantidade = quantidade;
	this.codBarras = codBarras.substring(0, 13);
}

function cadProd(codigo, nome, codBarras, ncm, cfop, unidade, valor, quantidade, valorCompra){
	_construct(codigo, nome, codBarras, ncm, cfop, unidade, valor, quantidade, valorCompra);
	$('#nome').val(nome);
	$("#nome").focus();

	getUnidadeMedida((data) => {
		let achouUnidade = false;
		data.map((v) => {
			if(v == unidade){
				achouUnidade = true;
			}
		})

		if(!achouUnidade){
			alert("Unidade de compra deste produto não corresponde a nenhuma pré-determinada\n"+
				"Unidade: " + unidade);
			if(unidade == 'M3C'){
				unidade = 'M3';
				alert('M3C alterado para ' + unidade);
			}
			else if(unidade == 'M2C'){
				unidade = 'M2';
				alert('M2C alterado para ' + unidade);
			}
			else if(unidade == 'MC'){
				unidade = 'M';
				alert('MC alterado para ' + unidade);
			}
			else if(unidade == 'UN'){
				unidade = 'UNID';
				alert('UN alterado para ' + unidade);
			}else{
				unidade = 'UNID';
				alert('Unidade de compra alterado para ' + unidade);

			}

		}

		$('#ncm').val(ncm);
		$("#ncm").trigger("click");

		$('#cfop').val(cfop);
		console.log(unidade)

		$('#un_compra').val(unidade);
		$('#unidade_venda option[value="'+unidade+'"]').prop("selected", true);

		$('#valor').val(valor);

		$('#quantidade').val(quantidade);
		$('#conv_estoque').val('1');
		$('#valor_venda').val('0');
		$("#quantidade").trigger("click");
		Materialize.updateTextFields();
		$('#modal1').modal('open');
	})

}

function getUnidadeMedida(call){
	$.ajax
	({
		type: 'GET',
		url: path + 'produtos/getUnidadesMedida',
		dataType: 'json',
		success: function(e){
			console.log(e)
			call(e)

		}, error: function(e){
			console.log(e)
		}

	});
}

$('#salvar').click(() => {
	$('#preloader').css('display', 'block');
	$("#th_"+this.codigo).removeClass("red-text");
	$("#th_"+this.codigo).html($('#nome').val());
	let valorVenda = $('#valor_venda').val();
	let valorCompra = $('#valor_compra').val();
	let unidadeVenda = $('#unidade_venda').val();
	let conversaoEstoque =$('#conv_estoque').val();
	let categoria_id =$('#categoria_id').val();
	let cor = $('#cor').val();

	let CST_CSOSN =$('#CST_CSOSN').val();
	let CST_PIS =$('#CST_PIS').val();
	let CST_COFINS =$('#CST_COFINS').val();
	let CST_IPI =$('#CST_IPI').val();

	let prod = {
		valorVenda: valorVenda,
		valorCompra: this.valorCompra,
		unidadeVenda: unidadeVenda,
		conversao_unitaria: conversaoEstoque,
		categoria_id: categoria_id,
		cor: cor,
		nome: $('#nome').val(),
		ncm: this.ncm,
		cfop: this.cfop,
		unidadeCompra: this.unidade,
		valor: this.valor,
		quantidade: this.quantidade,
		codBarras: this.codBarras,
		CST_CSOSN: CST_CSOSN,
		CST_PIS: CST_PIS,
		CST_COFINS: CST_COFINS,
		CST_IPI: CST_IPI
	}
	console.log(prod.quantidade)

	//console.log(this.semRegitro)

	let token = $('#_token').val();

	$.ajax
	({
		type: 'POST',
		data: {
			produto: prod,
			_token: token
		},
		url: path + 'produtos/salvarProdutoDaNotaComEstoque',
		dataType: 'json',
		success: function(e){
			$("#th_prod_id_"+codigo).html(e.id);
			$("#th_acao1_"+codigo).css('display', 'none');
			$("#th_acao2_"+codigo).css('display', 'block');
			$('#preloader').css('display', 'none');
			$('#modal1').modal('close');
			alert("Produto Saldo, e inserido o estoque quantidade: " + prod.quantidade)
			location.reload();

		}, error: function(e){
			console.log(e)
			$('#preloader').css('display', 'none');
		}
	});
})

