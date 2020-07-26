

var TAMANHOPIZZASELECIONADO = null;
var SABORESESCOLHIDOS = [];
var MAXIMOSABORES = 0;
var ADICIONAISESCOLHIDOS = [];
var TODOSSABORES = [];
var maiorValorPizza = 0;
var DIVISAO_VALOR_PIZZA = 0;
var VALOR_PIZZA= 0;

$(function () {

  DIVISAO_VALOR_PIZZA = $('#DIVISAO_VALOR_PIZZA').val();

  verificaUnidadeCompra();

  // console.log($('#composto').val())
  if($('#composto').val() == 'true'){
    getProdutosComposto(function(data){
      $('input.autocomplete-produto').autocomplete({
        data: data,
        limit: 20, 
        onAutocomplete: function(val) {

        },
        minLength: 1,
      });
    });
  }else{
    getProdutos(function(data){
      $('#tamanhos-pizza').css('display', 'none');
      $('input.autocomplete-produto').autocomplete({
        data: data,
        limit: 20, 
        onAutocomplete: function(val) {
          let v = val.split('-')
          getProduto(v[0], (data) => {

            console.log(data)
            if(data.delivery && data.delivery.pizza.length > 0){
              setaTamanhosPizza(data.delivery)
            }else{
              $('#valor').val(data.valor_venda)
            }

            Materialize.updateTextFields();

          })
        },
        minLength: 1,
      });
    });
  }

  verificaCategoria()

  let produto = $('input.autocomplete-produto').val()
  if(produto){
    let v = produto.split('-')
    getProduto(v[0], (data) => {

      $('#valor').val(data.valor_venda)
      console.log(data)
      if(data.delivery && data.delivery.pizza.length > 0){
        setaTamanhosPizza(data.delivery)
      }

      Materialize.updateTextFields();

    })
  }

  getAdicionais();

});
$('input.autocomplete-produto').on('keyup', () => {
  $('#tamanhos-pizza').css('display', 'none');
  $('#sabores-pizza').css('display', 'none');

})

$('#sabores').on('keyup', () => {
  if(TAMANHOPIZZASELECIONADO == null){
    Materialize.toast('Informe o tamanho da pizza!', 2000)

  }
})

function getProdutos(data){
  $.ajax
  ({
    type: 'GET',
    url: path + 'produtos/all',
    dataType: 'json',
    success: function(e){
       // console.log(e);
       data(e)

     }, error: function(e){
      console.log(e)
    }

  });
}

function getAdicionais(data){
  $.ajax
  ({
    type: 'GET',
    url: path + 'deliveryComplemento/all',
    dataType: 'json',
    success: function(e){
       // console.log(e);
       data(e)

     }, error: function(e){
      console.log(e)
    }

  });
}

function getProduto(id, data){
  $.ajax
  ({
    type: 'GET',
    url: path + 'produtos/getProduto/'+id,
    dataType: 'json',
    success: function(e){
       // console.log(e);
       data(e)

     }, error: function(e){
      console.log(e)
    }

  });
}

function getProdutosComposto(data){
  $.ajax
  ({
    type: 'GET',
    url: path + 'produtos/composto',
    dataType: 'json',
    success: function(e){
      console.log(e);
      data(e)

    }, error: function(e){
      console.log(e)
    }

  });
}

$('#unidade_compra').change(() => {
  verificaUnidadeCompra();
})
$('#unidade_venda').change(() => {
  verificaUnidadeCompra();
})

function verificaUnidadeCompra(){
  let unidadeCompra = $('#unidade_compra').val();
  let unidadeVenda = $('#unidade_venda').val();
  if(unidadeCompra != unidadeVenda){
    $('#conversao').css('display', 'block');
  }else{
    $('#conversao').css('display', 'none');
  }
}

function alterarDestaque(id){
  $.ajax
  ({
    type: 'GET',
    url: path + 'deliveryProduto/alterarDestaque/'+id,
    dataType: 'json',
    success: function(e){
       // console.log(e);
       console.log(e)

     }, error: function(e){
      console.log(e)
    }

  });
}

function alterarStatus(id){
  $.ajax
  ({
    type: 'GET',
    url: path + 'deliveryProduto/alterarStatus/'+id,
    dataType: 'json',
    success: function(e){
       // console.log(e);
       console.log(e)

     }, error: function(e){
      console.log(e)
    }

  });
}

function verificaCategoria(){
  let cat = $('#categoria-select option:selected').html();
  if(cat && cat.includes('izza')){
    $('#produto-pizza').css('display', 'block');
    $('#produto-comum').css('display', 'none');

  }else{
    $('#produto-comum').css('display', 'block');
    $('#produto-pizza').css('display', 'none');

  }
}

$('#categoria-select').change(() => {
  verificaCategoria()
})

//chips

function getSaboresPizza(){
  console.log(TAMANHOPIZZASELECIONADO)
  console.log(maiorValorPizza)
  $.get(path+'/pizza/pizzas', {tamanho: TAMANHOPIZZASELECIONADO})
  .done((data) => {

    let js = JSON.parse(data);
    console.log(js)
    let tags = [];
    TODOSSABORES = js;
    js.map((v) => {
      console.log(v)
      if(v.produto.delivery && v.produto.delivery.galeria.length > 0)
        tags[v.produto.nome] = path+'imagens_produtos/'+v.produto.delivery.galeria[0].path
      else
        tags[v.produto.nome] = null
    })

    $('#sabores').material_chip({
      autocompleteOptions: {
        data: tags,
        limit: Infinity,
        minLength: 1
      }
    });
  })
  .fail((err) => {
    console.log(err)
  })
}


function getAdicionais(){

  $.get(path+'deliveryComplemento/allPedidoLocal')
  .done((data) => {
    let js = JSON.parse(data);

    let tags = [];
    js.map((v) => {

      tags[v.nome + " - R$ " + v.valor] = null
    })

    $('#adicionais').material_chip({
      autocompleteOptions: {
        data: tags,
        limit: 2,
        minLength: 1
      }
    });
  })
  .fail((err) => {
    console.log(err)
  })
  
}


// data: {
//   'Apple': ,
//   'Microsoft': null,
//   'Google': null
// },

function setaTamanhosPizza(data){
  let tags = [];
  
  data.pizza.map((v) => {
    tags.push({tag: v.tamanho.nome + ' - R$ ' + v.valor, item: v})
  });
  $('#tamanhos').material_chip({
    data: tags,
  });

  $('#tamanhos-pizza').css('display', 'block');
  $('#sabores-pizza').css('display', 'block');

}


$('#sabores').on('chip.add', function(e, chip){

  SABORESESCOLHIDOS.push(chip.tag)
  $('#sabores_escolhidos').val(SABORESESCOLHIDOS)
  if(SABORESESCOLHIDOS.length >= MAXIMOSABORES-1){
    $('#sabores input').css('display', 'none')
  }
  let soma = 0;
  TODOSSABORES.map((v) => {
    console.log(v)
    console.log(SABORESESCOLHIDOS)
    if(SABORESESCOLHIDOS.includes(v.produto.nome)){
      if(DIVISAO_VALOR_PIZZA == 0){
        if(v.tamanhoValor > maiorValorPizza) maiorValorPizza = v.tamanhoValor;
        $('#valor').val(maiorValorPizza)
      }else{

        soma += parseFloat(v.tamanhoValor);
      }
    }
  })
  let calc = (soma + parseFloat(VALOR_PIZZA) )/(SABORESESCOLHIDOS.length + 1);
  $('#valor').val(calc.toFixed(2));


});


$('#sabores').on('chip.delete', function(e, chip){
  let temp = [];
  SABORESESCOLHIDOS.map((t) => {
    if(t != chip.tag) temp.push(t)
  })
  SABORESESCOLHIDOS = temp;
  let soma = 0;
  TODOSSABORES.map((v) => {
    console.log(v)
    console.log(SABORESESCOLHIDOS)
    if(SABORESESCOLHIDOS.includes(v.produto.nome)){
      if(DIVISAO_VALOR_PIZZA == 0){
        if(v.tamanhoValor > maiorValorPizza) maiorValorPizza = v.tamanhoValor;
        $('#valor').val(maiorValorPizza)
      }else{

        soma += parseFloat(v.tamanhoValor);
      }
    }
  })
  let calc = (soma + parseFloat(VALOR_PIZZA) )/(SABORESESCOLHIDOS.length + 1);
  $('#valor').val(calc.toFixed(2));
  
  $('#sabores_escolhidos').val(SABORESESCOLHIDOS)
  $('#sabores input').css('display', 'block')
});

$('#tamanhos').on('chip.select', function(e, chip){
  console.log(chip.item)
  maiorValorPizza = chip.item.valor;
  VALOR_PIZZA = chip.item.valor;
  $('#valor').val(maiorValorPizza)
  TAMANHOPIZZASELECIONADO = chip.item.tamanho_id;
  MAXIMOSABORES = chip.item.tamanho.maximo_sabores;
  console.log(TAMANHOPIZZASELECIONADO)
  $('#tamanho_pizza_id').val(TAMANHOPIZZASELECIONADO);
  console.log(MAXIMOSABORES)
  if(MAXIMOSABORES == 1){
    $('#sabores-pizza').css('display', 'none')
    SABORESESCOLHIDOS = [];
  }else{
    if(SABORESESCOLHIDOS.length >= MAXIMOSABORES){
      location.reload();
    }else{
      $('#sabores-pizza').css('display', 'block')
    }
  }
  getSaboresPizza();
  console.log(maiorValorPizza)
});


$('#adicionais').on('chip.add', function(e, chip){
  ADICIONAISESCOLHIDOS.push(chip.tag)
  $('#adicioanis_escolhidos').val(ADICIONAISESCOLHIDOS)
});

$('#adicionais').on('chip.delete', function(e, chip){
  let temp = [];
  ADICIONAISESCOLHIDOS.map((t) => {
    if(t != chip.tag) temp.push(t)
  })
  SABORESESCOLHIDOS = temp;
  $('#adicioanis_escolhidos').val(ADICIONAISESCOLHIDOS)
});

function sendSms(){
  $('#preloader1').css('display', 'block');
  let celular = $('#numero_sms').val()
  let msg = $('#msg_sms').val()

  let celularEnvia = '55'+celular.replace(' ', '');
  celularEnvia = celularEnvia.replace('-', '');
  let js = {
    numero: celularEnvia,
    msg: msg
  }
  console.log(js)
  $.post(path+'pedidos/sms', {data: js, _token: $('#_token').val()})
  .done(function(v){
    console.log(v)
    $('#preloader1').css('display', 'none');
    Materialize.toast('SMS enviado!', 4000);
    $('#modal1').modal('close');

  })
  .fail(function(err){
    console.log(err)
    Materialize.toast('Erro ao enviar SMS!', 4000);
    $('#preloader1').css('display', 'none');

  })
}

function enviarWhatsApp(){
  let celular = $('#numero_whats').val();
  let texto = $('#msg_whats').val();

  let mensagem = texto.split(" ").join("%20");

  let celularEnvia = '55'+celular.replace(' ', '');
  celularEnvia = celularEnvia.replace('-', '');
  let api = 'https://api.whatsapp.com/send?phone='+celularEnvia
  +'&text='+mensagem;
  window.open(api)
}



$('#bairro').change(() => {
  let bairro = $('#bairro').val();
  if(bairro != '0'){
    let js = {
      bairro_id: bairro,
      pedido_id: $('#pedido_id').val()
    }
    console.log(js)
    $.get(path + '/pedidos/setarBairro', js)
    .done((success) => {
      console.log(success)
      location.reload()
    })
    .fail((err) => {
      console.log(err)
    })
  }
})


function imprimirItens(){
  let ids = "";
  $('#body tr').each(function(){
    if($(this).find('#checkbox input').is(':checked')){
      id = $(this).find('#item_id').html();
      ids += id + ",";
    }
  })

  window.open(path + 'pedidos/imprimirItens?ids='+ids);
  location.href = window.location.href;



  // $.get(path + 'pedidos/imprimirItens', {ids: ids})
  // .done((res) => {
  //   console.log(res)
  // })
  // .fail((err) => {
  //   console.log(err)
  // })

}








