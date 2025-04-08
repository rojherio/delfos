//MENSAGEM PERGUNTANDO SE O USUÁRIO DESEJA MESMO SAIR DO FORMULÁRIO
window.onbeforeunload = function (e) {
  if ($('#nome_empregador').val() == '') {
    window.onbeforeunload = null;
  } else {
    return true;
  }
};
$(document).ready(function () {

  //CNPJ 00.000.000/0000-00
  $('.cnpj_format').mask('00.000.000/0000-00');
  //IE 00.000.000/000-00
  $('.ie_format').mask('00.000.000/000-00');
  //Telefone (68)3222-2222
  $('.tel_format').mask('(00)0000-0000');
  //Celular (68)9.9999-9999
  $('.cel_format').mask('(00)9.0000-0000');
  //CEP 69.900-000
  $('.cep_format').mask('00.000-000');

  //SALVANDO DADOS DO FORMULÁRIO DE PROJETO
  $('#form_empregador').submit(function () {
    window.onbeforeunload = null;
    projetouniversal.util.getjson({
      url: PORTAL_URL + "model/eo/empregador/salvar_empregador",
      type: "POST",
      data: $('#form_empregador').serialize(),
      enctype: 'multipart/form-data',
      success: onSuccessSend,
      error: onError
    });
    return false;
  });

  function onSuccessSend(obj) {
    if (obj.msg == 'success') {
      postToURL(PORTAL_URL + 'view/eo/empregador/dashboard', {mensagem: obj.retorno});
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
    $('span#titulo_form').text(' NOVO EMPREGADOR');
    $('span#btn_submit').text(' Cadastrar');
  });

  $(".select2_cidade_e").select2({
    language: {
      inputTooShort: function(args) {
        // args.minimum is the minimum required length
        // args.input is the user-typed text
        return "Por favor, digite 3 ou mais caracteres";
      },
      errorLoading: function() {
        return "Erro ao carregar resultados";
      },
      loadingMore: function() {
        return "Carregando mais resultados";
      },
      noResults: function() {
        return "Nenhum resultado encontrado";
      },
      searching: function() {
        return "Carregando...";
      },
      maximumSelected: function(args) {
        // args.maximum is the maximum number of items the user may select
        return "Error loading results";
      }
    },
    ajax: {
      url: PORTAL_URL + "model/bsc/municipio/get_municipios_estados",
      dataType: 'json',
      type: "post",
      delay: 150,
      data: function(params) {
        return {
          nome: params.term // search term
        };
      },
      processResults: function(data, params) {
        return {
          results: data.itens
        };
      }
    },
    placeholder: 'Selecione uma opção',
    minimumInputLength: 3,
    cache: true
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

function createSelect2Model(obj){
  $(obj).select2({
    language: {
      inputTooShort: function(args) {
        // args.minimum is the minimum required length
        // args.input is the user-typed text
        return "Por favor, digite 3 ou mais caracteres";
      },
      errorLoading: function() {
        return "Erro ao carregar resultados";
      },
      loadingMore: function() {
        return "Carregando mais resultados";
      },
      noResults: function() {
        return "Nenhum resultado encontrado";
      },
      searching: function() {
        return "Carregando...";
      },
      maximumSelected: function(args) {
        // args.maximum is the maximum number of items the user may select
        return "Error loading results";
      }
    },
    placeholder: 'Selecione uma opção'
  });
}

function onSuccessSendExcluir(obj) {
  if (obj.msg == 'success') {
    postToURL(PORTAL_URL + 'view/eo/empregador/dashboard', {mensagem: obj.retorno});
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
          url: PPORTAL_URL + "model/eo/empregador/excluir_empregador",
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
}
function editarRegistro(obj){
  $('span#titulo_form').text(' EDIÇÃO DE EMPREGADOR');
  $('span#btn_submit').text(' Atualizar');
  $("input#id").val($(obj).parents('tr').children('input#td_id').val());
  $("input#nome_razao_e").val($(obj).parents('tr').children('#td_nome_razao_e').text());
  $("input#nome_fantasia_e").val($(obj).parents('tr').children('#td_nome_fantasia_e').text());
  $("input#cnpj_e").val($(obj).parents('tr').children('#td_cnpj_e').text());
  $("input#ie_e").val($(obj).parents('tr').children('#td_ie_e').text());
  $("input#tel_res_e").val($(obj).parents('tr').children('#td_tel_res_e').text());
  $("input#tel_cel_e").val($(obj).parents('tr').children('#td_tel_cel_e').text());
  $("input#tel_recado_e").val($(obj).parents('tr').children('#td_tel_recado_e').text());
  $("input#tel_recado_nome_e").val($(obj).parents('tr').children('#td_tel_recado_nome_e').text());
  $("input#end_log_e").val($(obj).parents('tr').children('#td_end_log_e').text());
  $("input#end_num_e").val($(obj).parents('tr').children('#td_end_num_e').text());
  $("input#end_comp_e").val($(obj).parents('tr').children('#td_end_comp_e').text());
  $("input#end_bairro_e").val($(obj).parents('tr').children('#td_end_bairro_e').text());
  $("input#end_cep_e").val($(obj).parents('tr').children('#td_end_cep_e').text());
  $("input#cidade_e").val($(obj).parents('tr').children('#td_cidade_e').text());
  var cidadeId = $(obj).parents('tr').children('#td_cidade_e').attr('value');
  var cidadeNome = $(obj).parents('tr').children('#td_cidade_e').text();
  var dataSelect = [{id: cidadeId, text: cidadeNome}]
  $(".select2_cidade_e").select2({
    data: dataSelect, 
    language: {
      inputTooShort: function(args) {
        // args.minimum is the minimum required length
        // args.input is the user-typed text
        return "Por favor, digite 3 ou mais caracteres";
      },
      errorLoading: function() {
        return "Erro ao carregar resultados";
      },
      loadingMore: function() {
        return "Carregando mais resultados";
      },
      noResults: function() {
        return "Nenhum resultado encontrado";
      },
      searching: function() {
        return "Carregando...";
      },
      maximumSelected: function(args) {
        // args.maximum is the maximum number of items the user may select
        return "Erro ao carregar resultados";
      }
    },
    ajax: {
      url: PORTAL_URL + "model/bsc/municipio/get_municipios_estados",
      dataType: 'json',
      type: "post",
      delay: 150,
      data: function(params) {
        return {
          nome: params.term // search term
        };
      },
      processResults: function(data, params) {
        return {
          results: data.itens
        };
      }
    },
    placeholder: 'Selecione uma opção',
    minimumInputLength: 3,
    cache: true
  });
  $('select#cidade_e').val(cidadeId).trigger('change');
  var status = $(obj).parents('tr').children('#td_status').attr('value') == 1 ? true : false;
  $("input#status_e").prop('checked', status);
  $('html, body').animate({scrollTop:86}, 'medium'); //slow, medium, fast
  return false;
}