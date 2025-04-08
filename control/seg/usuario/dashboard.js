//MENSAGEM PERGUNTANDO SE O USUÁRIO DESEJA MESMO SAIR DO FORMULÁRIO
$(document).ready(function () {

});

function btnVisualizar(elem){
  var id = $(elem).parents('tr').children('input#td_id').val();
  postToURL(PORTAL_URL + 'view/seg/usuario/visualizar', {id: id});
};

function btnEditar(elem){
  var id = $(elem).parents('tr').children('input#td_id').val();
  postToURL(PORTAL_URL + 'view/seg/usuario/cadastrar', {id: id});
};

function btnInativar(elem) {
  Swal.fire({
    title: 'Tens certeza de inativar este usuário?',
    text: "",
    icon: 'question',
    showCancelButton: true,
    // confirmButtonColor: '#3085d6',
    // cancelButtonColor: '#d33',
    confirmButtonText: 'Sim, inativar!',
    cancelButtonText: 'Cancelar!'
  }).then((result) => {
    if (result.isConfirmed) {
      var id = $(elem).parents('tr').children('input#td_id').val();
      var btn = $(elem);
      projetouniversal.util.getjson({
        url: PORTAL_URL + "model/seg/usuario/inativar_usuario",
        type: "POST",
        data: {id: id},
        enctype: 'multipart/form-data',
        success: function(data){
          onSuccessSendAtivaInativa(data, btn, 'Inativo')
        },
        error: onError
      });
    }
  })
  return false;
};

function btnAtivar(elem) {
  Swal.fire({
    title: 'Tens certeza de ativar este usuário?',
    text: "",
    icon: 'question',
    showCancelButton: true,
    // confirmButtonColor: '#3085d6',
    // cancelButtonColor: '#d33',
    confirmButtonText: 'Sim, ativar!',
    cancelButtonText: 'Cancelar!'
  }).then((result) => {
    if (result.isConfirmed) {
      var id = $(elem).parents('tr').children('input#td_id').val();
      var btn = $(elem);
      projetouniversal.util.getjson({
        url: PORTAL_URL + "model/seg/usuario/ativar_usuario",
        type: "POST",
        data: {id: id},
        enctype: 'multipart/form-data',
        success: function(data){
          onSuccessSendAtivaInativa(data, btn, 'Ativo')
        },
        error: onError
      });
    }
  })
  return false;
};

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

// SUCCESS AO ENIVAR AJAX
function onSuccessSendAtivaInativa(obj, btn, op) {
  if (obj.msg == 'success') {
    if (op == 'Inativo') {
      $(btn).parents('tr').children('#td_status').html(op);
      $(btn).parents('td').children('button.btn_inativar_registro').prop('style', 'display: none');
      $(btn).parents('td').children('button.btn_ativar_registro').prop('style', 'display: all');
    } else {
      $(btn).parents('tr').children('#td_status').html(op);
      $(btn).parents('td').children('button.btn_ativar_registro').prop('style', 'display: none');
      $(btn).parents('td').children('button.btn_inativar_registro').prop('style', 'display: all');
    }
    swal.fire('Sucesso', obj.retorno, 'success');
  } else if (obj.msg == 'error') {
    swal.fire('Erro inesperado', "Houve um erro no sistema ao tentar realizar esta ação! Por favor, tente novamente ou informe esse erro ao suporte.", 'error');
    console.log('Error: ' + obj.retorno);
  }
  return false;
}