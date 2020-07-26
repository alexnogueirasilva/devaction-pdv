$('#testar').click(() => {
	$('#preloader').css('display', 'block')
	
	$.ajax
	({
		type: 'GET',
		url: path + 'configNF/teste',
		dataType: 'json',
		success: function(e){
			if(e.status == 200){
				alert('Ambiente ok')
			}
		}, error: function(e){
			if(e.status == 200){
				$('#preloader').css('display', 'none')

				alert('Ambiente ok')
				alert(e.responseText)

			}else{
				$('#preloader').css('display', 'none')

				alert('Algo esta errado, verifique o console do navegador!')
				console.log(e)
			}

		}
	});
})