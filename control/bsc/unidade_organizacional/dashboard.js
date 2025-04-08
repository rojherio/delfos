//MENSAGEM PERGUNTANDO SE O USUÁRIO DESEJA MESMO SAIR DO FORMULÁRIO
window.onbeforeunload = function (e) {
  if ($('#nome_uo').val() == '') {
    window.onbeforeunload = null;
  } else {
    return true;
  }
};
$(document).ready(function () {
  //Initialize Select2 Elements
  $('.select2').select2({
    placeholder: 'Selecione uma opção'
  });
  //SALVANDO DADOS DO FORMULÁRIO DE PROJETO
  $('#form_unidade_organizacional').submit(function () {
    window.onbeforeunload = null;
    projetouniversal.util.getjson({
      url: PORTAL_URL + "model/bsc/unidade_organizacional/salvar_unidade_organizacional",
      type: "POST",
      data: $('#form_unidade_organizacional').serialize(),
      enctype: 'multipart/form-data',
      success: onSuccessSend,
      error: onError
    });
    return false;
  });

  function onSuccessSend(obj) {
    if (obj.msg == 'success') {
      swal.fire('Sucesso', obj.retorno, 'success');
      postToURL(PORTAL_URL + 'view/bsc/unidade_organizacional/dashboard');
    }  else if (obj.msg == 'error') {
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
    postToURL(PORTAL_URL + 'view/bsc/unidade_organizacional/dashboard');
    // $('#id').val('');
    // $('span#titulo_form').text(' NOVA UNIDADE ORGANIZACIONAL');
    // $('span#btn_submit').text(' Cadastrar');
    // $('select#tipo_uo').val(0).trigger('change');
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
          url: PPORTAL_URL + "model/bsc/unidade_organizacional/excluir_unidade_organizacional",
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

function onSuccessSendExcluir(obj) {
  if (obj.msg == 'success') {
    swal.fire('Sucesso', obj.retorno, 'success');
    postToURL(PORTAL_URL + 'view/bsc/unidade_organizacional/dashboard');
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

function btnEditar(elem){
  editarRegistro(elem);
};

function editarRegistro(obj){
  var id = $(obj).parents('tr').children('input#td_id').val();
  postToURL(PORTAL_URL + 'view/bsc/unidade_organizacional/dashboard', {id: id});
  // $('span#titulo_form').text(' EDIÇÃO DE UNIDADE ORGANIZACIONAL');
  // $('span#btn_submit').text(' Atualizar');
  // $("input#id").val($(obj).parents('tr').children('input#td_id').val());
  // $("input#numero_uo").val($(obj).parents('tr').children('#td_numero').text());
  // $("input#nome_uo").val($(obj).parents('tr').children('#td_nome').text());
  // $('select#tipo_uo').val($(obj).parents('tr').children('#td_tipo_uo').attr('value')).trigger('change');console.log ($(obj).parents('tr').children('#td_tipo_uo').attr('value'));
  // var status = $(obj).parents('tr').children('#td_status').attr('value') == 1 ? true : false;
  // $("input#status_uo").prop('checked', status);
  // $('html, body').animate({scrollTop:86}, 'medium'); //slow, medium, fast
  // return false;
}
