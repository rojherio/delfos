//MENSAGEM PERGUNTANDO SE O USUÁRIO DESEJA MESMO SAIR DO FORMULÁRIO
window.onbeforeunload = function (e) {
  if ($('#nome_u').val() == '' && $('#login_u').val() == '' && $('#cpf_u').val() == '') {
    window.onbeforeunload = null;
  } else {
    return true;
  }
};
$(document).ready(function () {
  // Validation do template
  // $("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
  // Fim Validation do template
  //Date dd/mm/yyyy
  $('.date_format').mask('00/00/0000');
  //CPF 999.999.999-99
  $('.cpf_format').mask('000.000.000-00');
  //Telefone (68)3222-2222
  $('.tel_format').mask('(00)0000-0000');
  //Celular (68)9.9999-9999
  $('.cel_format').mask('(00)9.0000-0000');
  //CEP 69.900-000
  $('.cep_format').mask('00.000-000');
  //Flat red color scheme for iCheck
  $('.ichack-input input[type="checkbox"].minimal, .ichack-input input[type="radio"].square-purple').iCheck({
    radioClass: 'iradio_square-purple',
    increaseArea: '20%' // optional
  });
  // //iCheck for checkbox and radio inputs
  // $('.ichack-input input[type="checkbox"].minimal, .ichack-input input[type="radio"].minimal').iCheck({
  //   checkboxClass: 'icheckbox_minimal-blue',
  //   increaseArea: '20%' // optional
  // });
  // //iCheck for checkbox and radio inputs
  // $('.ichack-input input[type="checkbox"].minimal, .ichack-input input[type="radio"].minimal').iCheck({
  //   checkboxClass: 'icheckbox_minimal-blue',
  //   increaseArea: '20%' // optional
  // });
  //Initialize Select2 Elements
  $('.select2').select2({
    placeholder: 'Selecione uma opção'
  });
  //Carregando Select Municipio
  $('select#estado_u').change(function(){
    var id = $(this).val();
    projetouniversal.util.getjson({
      url: PORTAL_URL + "model/bsc/municipio/get_municipios",
      type: "POST",
      data: {id: id},
      enctype: 'multipart/form-data',
      success: onSuccessSendGetMunicipios,
      error: onError
    });
    return false;
  });
  function onSuccessSendGetMunicipios(obj) {
    if (obj.msg == 'success') {
      $('select#municipio_u').val(null).trigger('change');
      $('select#municipio_u').html(obj.retorno);
      $('select#municipio_u').select2({
        placeholder: 'Selecione uma opção'
      });
    } else if (obj.msg == 'error') {
      swal.fire('Erro inesperado', "Houve um erro no sistema ao tentar realizar esta ação! Por favor, tente novamente ou informe esse erro ao suporte.", 'error');
      console.log('Error: ' + obj.retorno);
    }
    return false;
  }
  $("#btn_cancelar").click(function(){
    $('imput#id').val(0);
    $('span#titulo_form').text('Cadastro');
    $('span#btn_submit').text(' Cadastrar');
    $('select#municipio_u').val(0).trigger('change');
    $('select#estado_u').val(0).trigger('change');
    $('select#cargo_u').val(0).trigger('change');
  });
  //SALVANDO DADOS DO FORMULÁRIO DE PROJETO
  $('#form_usuario').submit(function () {
    var formValido = formValidator();
    if (formValido) {
      window.onbeforeunload = null;
      projetouniversal.util.getjson({
        url: PORTAL_URL + "model/seg/usuario/salvar_usuario",
        type: "POST",
        data: $('#form_usuario').serialize(),
        enctype: 'multipart/form-data',
        success: onSuccessSend,
        error: onError
      });
    }
    return false;
  });
  function onSuccessSend(obj) {
    if (obj.msg == 'success') {
      Swal.fire({
        title: 'Deseja cadastrar um novo usuário?',
        text: "",
        icon: 'question',
        showCancelButton: true,
        // confirmButtonColor: '#3085d6',
        // cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, iniciar!',
        cancelButtonText: 'Não, obrigado!'
      }).then((result) => {
        if (result.isConfirmed) {
          postToURL(PORTAL_URL + 'view/seg/usuario/cadastrar');
        } else {
          postToURL(PORTAL_URL + 'view/seg/usuario/dashboard');
        }
      })
    } else if (obj.msg == 'error') {
      if (obj.tipo == 'nome') {
        var msg = obj.retorno.nome;
        msg += obj.retorno.cpf;
        msg += obj.retorno.login;
        msg += obj.retorno.emailInst;
        msg += obj.retorno.emailPessoal;
        swal.fire('Atenção', msg, 'warning');
      } else {
        swal.fire('Erro inesperado', "Houve um erro no sistema ao tentar realizar esta ação! Por favor, tente novamente ou informe esse erro ao suporte.", 'error');
        console.log('Error: ' + obj.retorno);
      }
      return false;
    }
  }
});

// ERRO AO ENVIAR AJAX
function onError(obj) {
  if (obj.responseText == "logout") {
    swal.fire({title: 'Limite de tempo, sem ação, ultrapassado', text: "Você passou mais de 30 minutos sem ação no sistema e por isso deverá efetuar login novamente.", icon: 'error', confirmButtonText: 'Ok'}).then((result) => {
      postToURL(PORTAL_URL + (obj.responseText));
    });
  } else {
    swal.fire('Erro inesperado', "Houve um erro no sistema ao tentar realizar esta ação! Por favor, informe esse erro ao suporte.", 'error');
    console.log('onError: ' + JSON.stringify(obj));
  }
  return false;
}

function formValidator(){
  var valido = true;
  if (!validaCPF($('input#cpf_u').val())) {
    swal.fire('Atenção', '!', 'warning');
    valido = false;
  }
  if (!validaDataNascimento($('input#dt_nasc_u').val())) {
    swal.fire('Atenção', 'A data de nascimento não pode ser superior a hoje!', 'warning');
    valido = false;
  }
  return valido;
}