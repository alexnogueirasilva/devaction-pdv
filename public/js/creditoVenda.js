
function receber(){
	let arr = []
	$('.body tr').each(function(){
		let checked = $(this).find('input').is(':checked');
		if(checked){
			let id = $(this).find('#id').html();
			arr.push(id)
		}
	});

	let param = { 
		arr: arr 
	};
	location.href=path+'vendasEmCredito/receber?arr='+arr;

}

$('.check').click(() => {
	$('#btn-receber').removeClass('disabled');
	percorreTabela();
})

function formatReal(v){
	return v.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'});;
}

function percorreTabela(){
	let temp = 0;

	$('.body tr').each(function(){
		if($(this).find('#checkbox input').is(':checked')){
			let v = $(this).find('#valor').html();
			v = v.replace(",", ".")
			temp += parseFloat(v);
		}
	});
	$('#total-select').html(formatReal(temp))
	console.log(temp)

}

google.load("visualization", "1", {packages:["corechart"]});

$(function(){ // inicia os graficos

	let js = $('#creditos').val();
	js = JSON.parse(js);
      //montando o array com os dados
      let indices = [''];
      let valores = [''];
      let p = [];
      $.each( js, function( key, value ) {
      	indices.push(key);
      	valores.push(parseInt(value));
      });
      p.push(['Tipo', 'Valor'])
      $.each( js, function( key, value ) {

      	p.push([key, parseInt(value)])
      });

      var data = google.visualization.arrayToDataTable([
      	indices, valores
      	]);
      var dataPizza = google.visualization.arrayToDataTable(
      	p.map(t => {
      		return t
      	})
      	);
        //opçoes para o gráfico barras
        var options = {
        	title: 'Barra',
          vAxis: {title: 'Valor',  titleTextStyle: {color: 'green'}},//legenda vertical
          
      };
        //instanciando e desenhando o gráfico barras
        var coluna = new google.visualization.ColumnChart(document.getElementById('coluna'));
        coluna.draw(data, options);
        //opções para o gráfico linhas
        var pizza = new google.visualization.PieChart(document.getElementById('pizza'));
        pizza.draw(dataPizza, {
        	title: 'Pizza',
        	is3D: true,
        });


    });