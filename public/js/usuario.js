$('#adm').click(() =>{
	if($('#adm').is(':checked')){
		console.log("sim")
		marcarTodos(true);
	}else{
		desmarcarTodos();
	}
})

function marcarTodos(){
	$('#acesso_cliente').attr('checked', true);
	$('#acesso_fornecedor').attr('checked', true);
	$('#acesso_produto').attr('checked', true);
	$('#acesso_financeiro').attr('checked', true);
	$('#acesso_caixa').attr('checked', true);
	$('#acesso_estoque').attr('checked', true);
	$('#acesso_compra').attr('checked', true);
	$('#acesso_fiscal').attr('checked', true);
}

function desmarcarTodos(){
	$('#acesso_cliente').removeAttr('checked');
	$('#acesso_fornecedor').removeAttr('checked');
	$('#acesso_produto').removeAttr('checked');
	$('#acesso_financeiro').removeAttr('checked');
	$('#acesso_caixa').removeAttr('checked');
	$('#acesso_estoque').removeAttr('checked');
	$('#acesso_compra').removeAttr('checked');
	$('#acesso_fiscal').removeAttr('checked');
}


