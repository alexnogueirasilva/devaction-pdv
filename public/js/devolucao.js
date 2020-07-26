var ITENS = [];
var SOMAITENS = 0;

$(function () {

	ITENS = JSON.parse($('#itens_nf').val());

	prepara((res) => {
		let t = montaTabela();
		$('#tbl tbody').html(t)
	});
	
});

function prepara(call){
	let temp = [];
	ITENS.map((v) => {
		let js = {
			CFOP: v.CFOP[0],
			NCM: v.NCM[0],
			codBarras: v.codBarras[0],
			codigo: v.codigo[0],
			qCom: v.qCom[0],
			uCom: v.uCom[0],
			vUnCom: v.vUnCom[0],
			xProd: v.xProd[0],
			parcial: 0
		}
		temp.push(js)
	})
	ITENS = temp;
	call(true)
}

function montaTabela(){
	SOMAITENS = 0;
	let t = ""; 
	ITENS.map((v) => {

		t += "<tr>";
		t += "<td>"+v.codigo+"</td>";
		t += "<td class='cod'>"+v.xProd+"</td>";
		t += "<td>"+v.NCM+"</td>";
		t += "<td>"+v.CFOP+"</td>";
		t += "<td>"+v.codBarras+"</td>";
		t += "<td>"+v.uCom+"</td>";
		t += "<td>"+v.vUnCom+"</td>";
		t += "<td>"+v.qCom+"</td>";
		t += "<td>"+formatReal(v.vUnCom*v.qCom)+"</td>";
		t += "<td><a href='#tbl tbody' onclick='deleteItem("+v.codigo+")'>"
		t += "<i class=' material-icons red-text'>delete</i></a></td>";
		t += "<td><a href='#tbl tbody' onclick='editItem("+v.codigo+")'>"
		t += "<i class=' material-icons blue-text'>edit</i></a></td>";
		t+= "</tr>";

		SOMAITENS += v.vUnCom*v.qCom;
	});
	$('#soma-itens').html(formatReal(SOMAITENS))
	return t;
}

function formatReal(v)
{
	return v.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});
}

function deleteItem(item){
	if (confirm('Deseja excluir este item, se confirmar sua NF ficará informal?')) { 

		percorreDelete(item, (res) => {
			let t = montaTabela();
			$('#tbl tbody').html(t);
			Materialize.toast('Item Removido!', 3000)

		})
		return false;
	}
}

function percorreDelete(id, call){
	let temp = [];
	ITENS.map((v) => {
		if(v.codigo != id){
			temp.push(v);
		}

	});
	ITENS = temp;
	call(true);
}

function editItem(item){
	
	getItem(item, (res) => {
		console.log(res)
		$("#nomeEdit").val(res.xProd)
		$("#quantidadeEdit").val(res.qCom)
		$("#idEdit").val(res.codigo)
		$('#modal2').modal('open');
	})	
}

$('#salvarEdit').click(() => {
	let id = $('#idEdit').val();
	let nome = $('#nomeEdit').val();
	let quantidade = $('#quantidadeEdit').val();

	percorreEdit(id, nome, quantidade, (res) => {
		console.log(res)
		let t = montaTabela();
		$('#tbl tbody').html(t)
		$('#modal2').modal('close');
	})

})

function percorreEdit(id, nome, quantidade, call){
	let temp = [];
	ITENS.map((v) => {
		if(v.codigo == id){
			console.log(quantidade)
			v.xProd = nome;
			v.parcial = quantidade != v.qCom ? 1 : 0;
			v.qCom = quantidade;

		}

		temp.push(v);
	});
	ITENS = temp;

	call(true);
}

function getItem(id, call){
	let obj = null;
	ITENS.map((v) => {
		if(v.codigo == id){
			obj = v;
		}
	})
	call(obj)
}


$('#savar-devolucao').click(() => {
	$('#preloader2').css('display', 'block');
	let natureza = $('#natureza').val();
	let xmlEntrada = $('#xmlEntrada').val();
	let fornecedorId = $('#idFornecedor').val();
	let nNf = $('#nNf').val();
	let vDesc = $('#vDesc').val();
	let vFrete = $('#vFrete').val();
	let totalNF = $('#totalNF').val();
	let obs = $('#obs').val();
	let motivo = $('#motivo').val();


	let data = {
		natureza: natureza,
		xmlEntrada: xmlEntrada.substring(0, 44),
		fornecedorId: fornecedorId,
		nNf: nNf,
		vDesc: vDesc,
		vFrete: vFrete,
		itens: ITENS,
		devolucao_parcial: SOMAITENS != totalNF,
		valor_integral: totalNF,
		valor_devolvido: SOMAITENS,
		motivo: motivo,
		obs: obs

	};

	console.log(data)
	let token = $('#_token').val();

	$.post(path+'/devolucao/salvar', {_token: token, data: data})
	.done((success) => {
		console.log(success)
		$('#preloader2').css('display', 'none');
		sucesso();
	})
	.fail((err) => {
		console.log(err)
		$('#preloader2').css('display', 'none');
		
	})

})

function sucesso(){
	$('#content').css('display', 'none');
	$('#anime').css('display', 'block');
	setTimeout(() => {
		location.href = path+'devolucao';
	}, 4000)
}


