$(document).ready(function () {

});
function validaCPF(cpfX) {
  var Soma;
  var Resto;
  var cpf = cpfX.replaceAll('.', '').replaceAll('-', '');
  Soma = 0;
  if (!cpf ||
    cpf.length != 11 ||
    cpf == "00000000000" ||
    cpf == "11111111111" ||
    cpf == "22222222222" ||
    cpf == "33333333333" ||
    cpf == "44444444444" ||
    cpf == "55555555555" ||
    cpf == "66666666666" ||
    cpf == "77777777777" ||
    cpf == "88888888888" ||
    cpf == "99999999999" 
    ) 
  {
    return false;
  }
  for (i=1; i<=9; i++) {
    Soma = Soma + parseInt(cpf.substring(i-1, i)) * (11 - i);
    Resto = (Soma * 10) % 11;
  }
  if ((Resto == 10) || (Resto == 11)) {
    Resto = 0;
  }
  if (Resto != parseInt(cpf.substring(9, 10)) ) {
    return false;
  }
  Soma = 0;
  for (i = 1; i <= 10; i++) {
    Soma = Soma + parseInt(cpf.substring(i-1, i)) * (12 - i);
    Resto = (Soma * 10) % 11;
  }
  if ((Resto == 10) || (Resto == 11)) {
    Resto = 0;
  }
  if (Resto != parseInt(cpf.substring(10, 11) ) ) {
    return false;
  }
  return true;
}

function validaDataNascimento(dtNascX){
  var strData = dtNascX;
  var partesData = strData.split("/");
  var data = new Date(partesData[2], partesData[1] - 1, partesData[0]);
  if(data > new Date()) {
   return false;
 }
 return true;
}

// FUNCAO PARA VALIDAR EMAIL
function IsEmail(email) {
  expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  if (!expr.test(email)) {
    return false;
  } else {
    return true;
  }
}

//FUNCAO PARA REMOVER OS ACENTOS
function removeAcento(s) {
  var r = s.toLowerCase();
  r = r.replace(new RegExp("\\s", 'g'), "");
  r = r.replace(new RegExp("[àáâãäå]", 'g'), "a");
  r = r.replace(new RegExp("æ", 'g'), "ae");
  r = r.replace(new RegExp("ç", 'g'), "c");
  r = r.replace(new RegExp("[èéêë]", 'g'), "e");
  r = r.replace(new RegExp("[ìíîï]", 'g'), "i");
  r = r.replace(new RegExp("ñ", 'g'), "n");
  r = r.replace(new RegExp("[òóôõö]", 'g'), "o");
  r = r.replace(new RegExp("œ", 'g'), "oe");
  r = r.replace(new RegExp("[ùúûü]", 'g'), "u");
  r = r.replace(new RegExp("[ýÿ]", 'g'), "y");
  r = r.replace(new RegExp("\\W", 'g'), "");
  return r;
}

function number_format(number, decimals, dec_point, thousands_sep) {
// * example 1: number_format(1234.56);
// * returns 1: '1,235'
// * example 2: number_format(1234.56, 2, ',', ' ');
// * returns 2: '1 234,56'
// * example 3: number_format(1234.5678, 2, '.', '');
// * returns 3: '1234.57'
// * example 4: number_format(67, 2, ',', '.');
// * returns 4: '67,00'
// * example 5: number_format(1000);
// * returns 5: '1,000'
// * example 6: number_format(67.311, 2);
// * returns 6: '67.31'
// * example 7: number_format(1000.55, 1);
// * returns 7: '1,000.6'
// * example 8: number_format(67000, 5, ',', '.');
// * returns 8: '67.000,00000'
// * example 9: number_format(0.9, 0);
// * returns 9: '1'
// * example 10: number_format('1.20', 2);
// * returns 10: '1.20'
// * example 11: number_format('1.20', 4);
// * returns 11: '1.2000'
// * example 12: number_format('1.2000', 3);
// * returns 12: '1.200'
// * example 13: number_format('1 000,50', 2, '.', ' ');
// * returns 13: '100 050.00'
// Strip all characters but numerical ones.
number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
var n = !isFinite(+number) ? 0 : +number,
prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
s = '',
toFixedFix = function (n, prec) {
  var k = Math.pow(10, prec);
  return '' + Math.round(n * k) / k;
};
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
      s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
      s[1] = s[1] || '';
      s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
  }

//funcao para converser valor monetário em float
function parseCurrency(valor) {
  valor = valor.replace('.', '');
  valor = valor.replace('.', '');
  valor = valor.replace('.', '');
  valor = valor.replace(',', '.');
  return valor;
}

//funcao para converter float em valor monetário
function formatarValorFloaTParaMonetario(valor) {
  valor = parseFloat(valor);
  valor = number_format(valor, 2, ',', '.');
  return valor;
}

function postToURL(path, params, method, target) {
    method = method || "post"; // Set method to post by default, if not specified.
    target = target || "_self"; // Set target to post by default, if not specified.

    // The rest of this code assumes you are not using a library.
    // It can be made less wordy if you use one.
    var form = document.createElement("form");
    form.setAttribute("method", method);
    form.setAttribute("action", path);
    form.setAttribute("target", target);

    var addField = function (key, value) {
      var hiddenField = document.createElement("input");
      hiddenField.setAttribute("type", "hidden");
      hiddenField.setAttribute("name", key);
      hiddenField.setAttribute("value", value);

      form.appendChild(hiddenField);
    };

    for (var key in params) {
      if (params.hasOwnProperty(key)) {
        if (params[key] instanceof Array) {
          for (var i = 0; i < params[key].length; i++) {
            addField(key, params[key][i])
          }
        }
        else {
          addField(key, params[key]);
        }
      }
    }

    document.body.appendChild(form);
    form.submit();
  }

//calculo de diferença de horas retorno em secundos
function diferencaDeHorario(hora1, hora2) {

  var today = new Date();
  var dd = today.getDate();
  var mm = today.getMonth() + 1;
  var yyyy = today.getFullYear();

  var data1 = new Date(mm + '/' + dd + '/' + yyyy + ' ' + hora1);
  var data2 = new Date(mm + '/' + dd + '/' + yyyy + ' ' + hora2);
  var sec = (data2.getTime() / 1000.0) - (data1.getTime() / 1000.0);

  return Math.round(sec);
}

//Função para remover o k-input do plugin kendo
function kendo_css_k_input() {
  $('form').find('span.k-input').each(function () {
    $(this).find('input').mask("99/99/9999");
    $(this).removeClass("k-input");
  });
}