//MENSAGEM PERGUNTANDO SE O USUÁRIO DESEJA MESMO SAIR DO FORMULÁRIO
window.onbeforeunload = function (e) {
  if ($('#nome_cargo').val() == '') {
    window.onbeforeunload = null;
  } else {
    return true;
  }
};
$(document).ready(function () {
  //SALVANDO DADOS DO FORMULÁRIO DE PROJETO
  $('#form_cargo').submit(function () {
    window.onbeforeunload = null;
    projetouniversal.util.getjson({
      url: PORTAL_URL + "model/eo/cargo/salvar_cargo",
      type: "POST",
      data: $('#form_cargo').serialize(),
      enctype: 'multipart/form-data',
      success: onSuccessSend,
      error: onError
    });
    return false;
  });

  function onSuccessSend(obj) {
    if (obj.msg == 'success') {
      postToURL(PORTAL_URL + 'view/eo/cargo/dashboard', {mensagem: obj.retorno});
    } else if (obj.msg == 'error') {
      if (obj.tipo == 'nome') {
        swal.fire('Erro', obj.retorno, 'error');
      } else {
        swal.fire('Erro inesperado', "Houve um erro no sistema ao tentar realizar esta ação! Por favor, tente novamente ou informe esse erro ao suporte.", 'error');
        console.log('Error: ' + obj.retorno);
      }
      return false;
    }
  }
  $("#btn_cancelar").click(function(){
    $('#id').val('');
    $('span#titulo_form').text(' NOVO CARGO');
    $('span#btn_submit').text(' Cadastrar');
  });

});

// ERRO AO ENVIAR AJAX
function onError(obj) {
  if (obj.responseText == "logout") {
    swal.fire({title: 'Limite de tempo, sem ação, ultrapassado', text: "Você passou mais de 30 minutos sem ação no sistema e por isso deverá efetuar login novamente.", icon: 'error', confirmButtonText: 'Ok'})
    .then((result) => {
      postToURL(PORTAL_URL + (obj.responseText));
    });
  } else {
    swal.fire('Erro inesperado', "Houve um erro no sistema ao tentar realizar esta ação! Por favor, informe esse erro ao suporte.", 'error');
    console.log('onError: ' + JSON.stringify(obj));
  }
  return false;
}

function onSuccessSendExcluir(obj) {
  if (obj.msg == 'success') {
    postToURL(PORTAL_URL + 'view/eo/cargo/dashboard', {mensagem: obj.retorno});
  } else if (obj.msg == 'error') {
    if (obj.tipo == 'nome') {
      swal.fire('Erro', obj.retorno, 'error');
    } else {
      swal.fire('Erro inesperado', "Houve um erro no sistema ao tentar realizar esta ação! Por favor, tente novamente ou informe esse erro ao suporte.", 'error');
      console.log('Error: ' + obj.retorno);
    }
    return false;
  }
}

function btnExcluir(elem) {
  window.onbeforeunload = null;
  if ($(elem).attr('negado')) {
    swal.fire('Atenção', 'Este registro não pode ser exlcuido pois está vinculado a um contrato de servidor!', 'warning');
  } else {
    Swal.fire({
      title: 'Tens certeza de excluir este registro?',
      text: "Este processo não poderá ser desfeito!",
      icon: 'question',
      showCancelButton: true,
    // confirmButtonColor: '#3085d6',
    // cancelButtonColor: '#d33',
      confirmButtonText: 'Sim, excluir!',
      cancelButtonText: 'Cancelar!'
    }).then((result) => {
      if (result.isConfirmed) {
        var id = $(elem).parents('tr').children('input#td_id').val();
        projetouniversal.util.getjson({
          url: PPORTAL_URL + "model/eo/cargo/excluir_cargo",
          type: "POST",
          data: {id: id},
          enctype: 'multipart/form-data',
          success: function(data){
            onSuccessSendExcluir(data)
          },
          error: onError
        });
      }
    })
  }
  return false;
};

function btnEditar(elem){
  editarRegistro(elem);
};
function editarRegistro(obj){
  $('span#titulo_form').text(' EDIÇÃO DE CARGO');
  $('span#btn_submit').text(' Atualizar');
  $("input#id").val($(obj).parents('tr').children('input#td_id').val());
  $("input#nome_cargo").val($(obj).parents('tr').children('#td_nome').text());
  var status = $(obj).parents('tr').children('#td_status').attr('value') == 1 ? true : false;
  $("input#status_cargo").prop('checked', status);
  $('html, body').animate({scrollTop:86}, 'medium'); //slow, medium, fast
  return false;
}
