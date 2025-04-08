$(document).ready(function () {
  $("#btn_cancelar").click(function(){
    postToURL(PORTAL_URL + 'dashboard');
  });
  $('#btn_foto_atualizar').click(function () {
    window.onbeforeunload = null;
    var form = document.getElementById('form_usuario_foto');
    const formData = new FormData(form);
    $.ajax(PORTAL_URL + "model/seg/usuario/crop", {
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      // enctype: 'multipart/form-data',
      success: onSuccessSend,
      error: onError
    });
    return false;
  });
  function onSuccessSend(obj) {
    swal.fire('Sucesso', 'Imagen alterada com sucesso!', 'success');
    $('img#igm_user').attr('src', obj.result);
    postToURL(PORTAL_URL + 'dashboard');
    return false;
  }
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