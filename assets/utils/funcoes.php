<?php
function preparar_array_sql($array) {
  $resultado = "";
  $novo = $array;
  rsort($novo);
  $novo = array_unique($novo);
  foreach($novo as $obj => $val) {
    $resultado .= "'$val',";
  }
  return substr($resultado, 0, - 1);
}
// FUNÇÃO PARA VERIFICAR A CONEXÃO EXTERNA DE URL
function endereco_existe($url) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_HEADER, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  $content = curl_exec($ch);
  $info = curl_getinfo($ch);
  if($info['http_code'] == 200) {
    return true;
  } else {
    return false;
  }
}
function formato_arquivo($arquivo) {
  $tipo = ctexto(end(explode(".", $arquivo)), 'min');
  if($tipo == 'pdf') {
    return 'ic-pdf';
  } else if($tipo == 'excel' || $tipo == 'xls') {
    return 'ic-xls';
  } else if($tipo == 'doc' || $tipo == 'docx') {
    return 'ic-docx';
  } else if($tipo == 'pptx') {
    return 'ic-ppt';
  } else if($tipo == 'jpg' || $tipo == 'png' || $tipo == 'jpg' || $tipo == 'jpeg' || $tipo == 'gif') {
    return 'ic-jpg';
  } else if($tipo == 'mp3' || $tipo == 'wav') {
    return 'ic-mp3';
  } else if($tipo == 'avi' || $tipo == 'mp4' || $tipo == 'mkv') {
    return 'ic-avi';
  } else if($tipo == 'zip' || $tipo == 'rar') {
    return 'ic-zip';
  } else {
    return 'ic-outros';
  }
}
// LIMITA TEXTO E COLOCA "..."
function resume($var, $limite) {
  // Se o texto for maior que o limite, ele corta o texto e adiciona 3 pontinhos.
  if(strlen($var) > $limite) {
    $var = substr($var, 0, $limite);
    $var = trim($var) . "...";
  }
  return $var;
}
// FUNÇÃO QUE RECEBE UM ARRAY E ARRUMA TODOS OS DADOS PARA SEREM PEGOS
function retorna_campos($post) {
  $fields = explode("&", $post);
  foreach($fields as $field) {
    $field_key_value = explode("=", $field);
    $key = ($field_key_value[0]);
    $value = ($field_key_value[1]);
    if($value != '')
      $data[$key] = (urldecode($value));
  }
  return $data;
}
// MÉTOD PARA VALIDAR O CPF
function valida_cpf($cpfx) {
  $cpf = "";
  $guard = "";
  for($i = 0; ($i < 14); $i ++) {
    if($cpfx[$i] != '.' && $cpfx[$i] != '-') {
      $cpf += $cpfx[$i];
      $guard = "$guard$cpfx[$i]";
    }
  }
  $cpf = $guard;
  // Verifica se o cpf possui números
  if(! is_numeric($cpf)) {
    $status = false;
  } else {
    // VERIFICA
    if(($cpf == '11111111111') || ($cpf == '22222222222') || ($cpf == '33333333333') || ($cpf == '44444444444') || ($cpf == '55555555555') || ($cpf == '66666666666') || ($cpf == '77777777777') || ($cpf == '88888888888') || ($cpf == '99999999999') || ($cpf == '00000000000')) {
      $status = false;
    } else {
      // PEGA O DIGITO VERIFIACADOR
      $dv_informado = substr($cpf, 9, 2);
      for($i = 0; $i <= 8; $i ++) {
        $digito[$i] = substr($cpf, $i, 1);
      }
      // CALCULA O VALOR DO 10º DIGITO DE VERIFICAÇÂO
      $posicao = 10;
      $soma = 0;
      for($i = 0; $i <= 8; $i ++) {
        $soma = $soma + $digito[$i] * $posicao;
        $posicao = $posicao - 1;
      }
      $digito[9] = $soma % 11;
      if($digito[9] < 2) {
        $digito[9] = 0;
      } else {
        $digito[9] = 11 - $digito[9];
      }
      // CALCULA O VALOR DO 11º DIGITO DE VERIFICAÇÃO
      $posicao = 11;
      $soma = 0;
      for($i = 0; $i <= 9; $i ++) {
        $soma = $soma + $digito[$i] * $posicao;
        $posicao = $posicao - 1;
      }
      $digito[10] = $soma % 11;
      if($digito[10] < 2) {
        $digito[10] = 0;
      } else {
        $digito[10] = 11 - $digito[10];
      }
      // VERIFICA SE O DV CALCULADO É IGUAL AO INFORMADO
      $dv = $digito[9] * 10 + $digito[10];
      if($dv != $dv_informado) {
        $status = false;
      } else
      $status = true;
    } // FECHA ELSE
  } // FECHA ELSE(is_numeric)
  return $status;
}
// MÉTODO PARA REALIZAR UMA PESQUISA DE COMPARAÇÃO NO BANCO DE DADOS
function pesquisar($retorno, $tabela, $campo, $cond, $variavel, $add) {
  $stmt = $db->prepare("SELECT $retorno FROM $tabela WHERE $campo $cond ? $add");
  $stmt->bindValue(1, $variavel);
  $stmt->execute();
  $rs = $stmt->fetch(PDO::FETCH_ASSOC);
  return $rs[$retorno];
}
// FUNÇÃO PARA RETORNAR O STATUS
function status($codigo) {
  if($codigo == 1)
    return "Ativo";
  else
    return "Inativo";
}
// FUNÇÃO PARA RETORNAR O STATUS
function status_inverso($codigo) {
  if(strtoupper($codigo) == "ATIVO")
    return 1;
  else if(strtoupper($codigo) == "INATIVO")
    return 0;
  else
    return 2;
}
// FUNÇÃO PARA RETORNAR O ESTADO DE UM MUNICÍPIO
function estado_municipio($municipio) {
  if(is_numeric($municipio)) {
    $con = Conexao::getInstance();
    $rs = $con->prepare("SELECT estado_id FROM bsc_municipio WHERE id = ?");
    $rs->bindValue(1, $municipio);
    $rs->execute();
    $dados = $rs->fetch(PDO::FETCH_ASSOC);
    return $dados['estado_id'];
  } else {
    return "";
  }
}
// FUNÇÃO PARA RETORNAR O NOME DO ESTADO DE UM MUNICÍPIO
function nome_estado_municipio($municipio) {
  if(is_numeric($municipio)) {
    $con = Conexao::getInstance();
    $rs = $con->prepare("SELECT nome FROM bsc_estado WHERE id = ?");
    $rs->bindValue(1, $municipio);
    $rs->execute();
    $dados = $rs->fetch(PDO::FETCH_ASSOC);
    return $dados['nome'];
  } else {
    return "";
  }
}
// FUNÇÃO PARA VERIFICAR QUAIS OBJETOS E AÇÕES FORAM MARCADOS NO GRUPO
function grupo_modulo_objeto_acao($id, $grupo) {
  $con = Conexao::getInstance();
  $rs = $con->prepare("SELECT id FROM seg_grupo_modulo_objeto_acao WHERE modulo_objeto_acao_id = ? and grupo_id = ?");
  $rs->bindValue(1, $id);
  $rs->bindValue(2, $grupo);
  $rs->execute();
  $rowCount = $rs->rowCount();
  if($rowCount > 0)
    return true;
  else
    return false;
}
// FUNÇÃO PARA VERIFICAR QUAIS OBJETOS E AÇÕES FORAM MARCADOS NO FORMULÁRIO DE USUÁRIO
function usuario_modulo_objeto_acao($id, $usuario) {
  $con = Conexao::getInstance();
  $rs = $con->prepare("SELECT id FROM seg_usuario_modulo_objeto_acao WHERE modulo_objeto_acao_id = ? and usuario_id = ?");
  $rs->bindValue(1, $id);
  $rs->bindValue(2, $usuario);
  $rs->execute();
  $rowCount = $rs->rowCount();
  if($rowCount > 0)
    return true;
  else
    return false;
}
// FUNÇÃO PARA VERIFICAR QUAIS OBJETOS E AÇÕES FORAM MARCADOS
function check_objeto_acao($modulo, $objeto, $acao) {
  $con = Conexao::getInstance();
  $rs = $con->prepare("SELECT id FROM seg_modulo_objeto_acao WHERE objeto_id = ? AND acao_id = ? AND modulo_id = ?");
  $rs->bindValue(1, $objeto);
  $rs->bindValue(2, $acao);
  $rs->bindValue(3, $modulo);
  $rs->execute();
  $rowCount = $rs->rowCount();
  $dados = $rs->fetch(PDO::FETCH_ASSOC);
  if($rowCount > 0)
    return $dados['id'];
  else
    return 0;
}
// CALCULA A DIFERENÇA ENTRE MESES ENTRE DUAS DATAS
function diff_data_meses($inicio, $fim) {
  if($inicio != "00/00/0000 00:00:00" && $fim != "00/00/0000 00:00:00" && $inicio != "0000-00-00 00:00:00" && $fim != "0000-00-00 00:00:00") {
    // CONVERTE AS DATAS PARA O FORMATO AMERICANO
    $inicio = explode('/', $inicio);
    $inicio = "{$inicio[2]}-{$inicio[1]}-{$inicio[0]}";
    $fim = explode('/', $fim);
    $fim = "{$fim[2]}-{$fim[1]}-{$fim[0]}";
    // AGORA CONVERTEMOS A DATA PARA UM INTEIRO
    // QUE REPRESENTA A DATA E É PASSÍVEL DE OPERAÇÕES SIMPLES
    // COMO SUBITRAÇÃO E ADIÇÃO
    $inicio = strtotime($inicio);
    $fim = strtotime($fim);
    // CALCULA A DIFERENÇA ENTRE AS DATAS
    $intervalo = $fim - $inicio;
    $meses = floor(($intervalo / (30 * 60 * 60 * 24)));
    if($meses > 1) {
      return "$meses meses";
    } else if($meses == 1) {
      return "$meses mês";
    } else if($meses == 0) {
      return "Esse mês";
    } else if($meses < 0) {
      if($meses < - 1) {
        return "Atrasado " . abs($meses) . " meses";
      } else {
        return "Atrasado " . abs($meses) . " mês";
      }
    }
  } else {
    return "";
  }
}
// CALCULA A DIFERENÇA ENTRE DIAS ENTRE DUAS DATAS
function diff_data_dias($data_inicial, $data_final) {
  if($data_inicial != "00/00/0000 00:00:00" && $data_final != "00/00/0000 00:00:00" && $data_inicial != "0000-00-00 00:00:00" && $data_final != "0000-00-00 00:00:00") {
    $diferenca = strtotime($data_final) - strtotime($data_inicial);
    $dias = floor($diferenca / (60 * 60 * 24));
    if($dias > 1) {
      return "$dias Dias";
    } else if($dias == 1) {
      return "$dias Dia";
    } else if($dias == 0) {
      return "Hoje";
    } else if($dias < 0) {
      if($dias < - 1) {
        return "" . abs($dias) . " Dias";
      } else {
        return "" . abs($dias) . " Dia";
      }
    }
  } else {
    return "";
  }
}
function diff_data_dias2($data_inicial, $data_final) {
  if($data_inicial != "00/00/0000 00:00:00" && $data_final != "00/00/0000 00:00:00" && $data_inicial != "0000-00-00 00:00:00" && $data_final != "0000-00-00 00:00:00") {
    $diferenca = strtotime($data_final) - strtotime($data_inicial);
    $dias = floor($diferenca / (60 * 60 * 24));
    if($dias > 1) {
      return $dias;
    } else if($dias == 1) {
      return $dias;
    } else if($dias == 0) {
      return $dias;
    } else if($dias < 0) {
      return 0;
    }
  } else {
    return "";
  }
}
function formata_data($data) {
  if($data == '')
    return NULL;
  $d = explode('/', $data);
  return $d[2] . '-' . $d[1] . '-' . $d[0];
}
function data_volta($data) {
  if($data == '' || $data == '0000-00-00')
    return '';
  $d = explode('-', $data);
  return $d[2] . '/' . $d[1] . '/' . $d[0];
}
function hora($hora) { // Deixa a hora 20:00
  $h = explode(':', $hora);
  return $h[0] . ':' . $h[1];
}
function getSemana($dia, $completo = 0) {
  switch($dia){
    case 1 :
    $r = 'SEG';
    $comp = 'Segunda-feira';
    break;
    case 2 :
    $r = 'TER';
    $comp = 'Terça-feira';
    break;
    case 3 :
    $r = 'QUA';
    $comp = 'Quarta-feira';
    break;
    case 4 :
    $r = 'QUI';
    $comp = 'Quinta-feira';
    break;
    case 5 :
    $r = 'SEX';
    $comp = 'Sexta-feira';
    break;
    case 6 :
    $r = 'SAB';
    $comp = 'Sábado';
    break;
    case 7 :
    $r = 'DOM';
    $comp = 'Domingo';
    break;
  }
  if($completo == 1)
    return $comp;
  else
    return $r;
}
function getSemana2($dia, $completo = 0) {
  switch($dia){
    case 1 :
    $r = 'Seg';
    $comp = 'Segunda-feira';
    break;
    case 2 :
    $r = 'Ter';
    $comp = 'Terça-feira';
    break;
    case 3 :
    $r = 'Qua';
    $comp = 'Quarta-feira';
    break;
    case 4 :
    $r = 'Qui';
    $comp = 'Quinta-feira';
    break;
    case 5 :
    $r = 'Sex';
    $comp = 'Sexta-feira';
    break;
    case 6 :
    $r = 'Sab';
    $comp = 'Sábado';
    break;
    case 7 :
    $r = 'Dom';
    $comp = 'Domingo';
    break;
  }
  if($completo == 1)
    return $comp;
  else
    return $r;
}
function getDiaSemana($dia, $completo = 0) {
  switch($dia){
    case 1 :
    $r = 'Dom';
    $comp = 'Domingo';
    break;
    case 2 :
    $r = 'Seg';
    $comp = 'Segunda-feira';
    break;
    case 3 :
    $r = 'Ter';
    $comp = 'Terça-feira';
    break;
    case 4 :
    $r = 'Qua';
    $comp = 'Quarta-feira';
    break;
    case 5 :
    $r = 'Qui';
    $comp = 'Quinta-feira';
    break;
    case 6 :
    $r = 'Sex';
    $comp = 'Sexta-feira';
    break;
    case 7 :
    $r = 'Sab';
    $comp = 'Sábado';
    break;
  }
  if($completo == 1)
    return $comp;
  else
    return $r;
}
function hoje($data) {
  $dt = explode('/', $data);
  return getSemana(date("N", mktime(0, 0, 0, $dt[1], $dt[0], intval($dt[2]))), 1);
}
function timeDiff($firstTime, $lastTime) {
  $firstTime = strtotime($firstTime);
  $lastTime = strtotime($lastTime);
  $timeDiff = $lastTime - $firstTime;
  return $timeDiff;
}
function separa_hora($hora, $op) { // $op = minutos = 1; hora = 0
  $hr = explode(':', $hora);
  return $hr[$op];
}
function dataExtenso($dt) {
  $da = explode('/', $dt);
  return $da[0] . ' de ' . getMes($da[1]) . ' de ' . $da[2];
}
function dataExtensoTimeline($dt) {
  $da = explode('/', $dt);
  $diasemana = date("w", mktime(0, 0, 0, $da[1], $da[0], $da[2]));
  return getSemana2($diasemana, 0) . '  ' . getMes2($da[1]) . '  ' . $da[0] . ' ' . $da[2];
}
function getMes($m) {
  switch($m){
    case 1 :
    $mes = "Janeiro";
    break;
    case 2 :
    $mes = "Fevereiro";
    break;
    case 3 :
    $mes = "Março";
    break;
    case 4 :
    $mes = "Abril";
    break;
    case 5 :
    $mes = "Maio";
    break;
    case 6 :
    $mes = "Junho";
    break;
    case 7 :
    $mes = "Julho";
    break;
    case 8 :
    $mes = "Agosto";
    break;
    case 9 :
    $mes = "Setembro";
    break;
    case 10 :
    $mes = "Outubro";
    break;
    case 11 :
    $mes = "Novembro";
    break;
    case 12 :
    $mes = "Dezembro";
    break;
  }
  return $mes;
}
function getMes2($m) {
  switch($m){
    case 1 :
    $mes = "Jan";
    break;
    case 2 :
    $mes = "Fev";
    break;
    case 3 :
    $mes = "Mar";
    break;
    case 4 :
    $mes = "Abr";
    break;
    case 5 :
    $mes = "Mai";
    break;
    case 6 :
    $mes = "Jun";
    break;
    case 7 :
    $mes = "Jul";
    break;
    case 8 :
    $mes = "Ago";
    break;
    case 9 :
    $mes = "Set";
    break;
    case 10 :
    $mes = "Out";
    break;
    case 11 :
    $mes = "Nov";
    break;
    case 12 :
    $mes = "Dez";
    break;
  }
  return $mes;
}
function getMes3($m) {
  switch($m){
    case 1 :
    $mes = "janeiro";
    break;
    case 2 :
    $mes = "fevereiro";
    break;
    case 3 :
    $mes = "marco";
    break;
    case 4 :
    $mes = "abril";
    break;
    case 5 :
    $mes = "maio";
    break;
    case 6 :
    $mes = "junho";
    break;
    case 7 :
    $mes = "julho";
    break;
    case 8 :
    $mes = "agosto";
    break;
    case 9 :
    $mes = "setembro";
    break;
    case 10 :
    $mes = "outubro";
    break;
    case 11 :
    $mes = "novembro";
    break;
    case 12 :
    $mes = "dezembro";
    break;
  }
  return $mes;
}
function ctexto($texto, $frase = 'pal') {
  switch($frase){
    case 'fra' : // Apenas a a primeira letra em maiusculo
    $texto = ucfirst(mb_strtolower($texto));
    break;
    case 'min' :
    $texto = mb_strtolower($texto);
    break;
    case 'mai' :
    $texto = colocaAcentoMaiusculo((mb_strtoupper($texto)));
    break;
    case 'pal' : // Todas as palavras com a primeira em maiusculo
    $texto = ucwords(mb_strtolower($texto));
    break;
    case 'pri' : // Todos os primeiros caracteres de cada palavra em maiusuclo, menos as junções
    $texto = titleCase($texto);
    break;
  }
  return $texto;
}
function titleCase($string, $delimiters = array(
  " ",
  "-",
  ".",
  "'",
  "O'",
  "Mc"
), $exceptions = array(
  "de",
  "da",
  "dos",
  "das",
  "do",
  "I",
  "II",
  "III",
  "IV",
  "V",
  "VI"
)) {
  /*
   * Exceptions in lower case are words you don't want converted
   * Exceptions all in upper case are any words you don't want converted to title case
   * but should be converted to upper case, e.g.:
   * king henry viii or king henry Viii should be King Henry VIII
   */
  $string = mb_convert_case($string, MB_CASE_TITLE, "UTF-8");
  foreach($delimiters as $dlnr => $delimiter) {
    $words = explode($delimiter, $string);
    $newwords = array();
    foreach($words as $wordnr => $word) {
      if(in_array(mb_strtoupper($word, "UTF-8"), $exceptions)) {
        // check exceptions list for any words that should be in upper case
        $word = mb_strtoupper($word, "UTF-8");
      } elseif(in_array(mb_strtolower($word, "UTF-8"), $exceptions)) {
        // check exceptions list for any words that should be in upper case
        $word = mb_strtolower($word, "UTF-8");
      } elseif(! in_array($word, $exceptions)) {
        // convert to uppercase (non-utf8 only)
        $word = ucfirst($word);
      }
      array_push($newwords, $word);
    }
    $string = join($delimiter, $newwords);
  } // foreach
  return $string;
}
function colocaAcentoMaiusculo($texto) {
  $array1 = array(
    "á",
    "à",
    "â",
    "ã",
    "ä",
    "é",
    "è",
    "ê",
    "ë",
    "í",
    "ì",
    "î",
    "ï",
    "ó",
    "ò",
    "ô",
    "õ",
    "ö",
    "ú",
    "ù",
    "û",
    "ü",
    "ç"
  );
  $array2 = array(
    "Á",
    "À",
    "Â",
    "Ã",
    "Ä",
    "É",
    "È",
    "Ê",
    "Ë",
    "Í",
    "Ì",
    "Î",
    "Ï",
    "Ó",
    "Ò",
    "Ô",
    "Õ",
    "Ö",
    "Ú",
    "Ù",
    "Û",
    "Ü",
    "Ç"
  );
  return str_replace($array1, $array2, $texto);
}
function retira_acentos($texto) {
  $array1 = array(
    "á",
    "à",
    "â",
    "ã",
    "ä",
    "é",
    "è",
    "ê",
    "ë",
    "í",
    "ì",
    "î",
    "ï",
    "ó",
    "ò",
    "ô",
    "õ",
    "ö",
    "ú",
    "ù",
    "û",
    "ü",
    "ç",
    "Á",
    "À",
    "Â",
    "Ã",
    "Ä",
    "É",
    "È",
    "Ê",
    "Ë",
    "Í",
    "Ì",
    "Î",
    "Ï",
    "Ó",
    "Ò",
    "Ô",
    "Õ",
    "Ö",
    "Ú",
    "Ù",
    "Û",
    "Ü",
    "Ç"
  );
  $array2 = array(
    "a",
    "a",
    "a",
    "a",
    "a",
    "e",
    "e",
    "e",
    "e",
    "i",
    "i",
    "i",
    "i",
    "o",
    "o",
    "o",
    "o",
    "o",
    "u",
    "u",
    "u",
    "u",
    "c",
    "A",
    "A",
    "A",
    "A",
    "A",
    "E",
    "E",
    "E",
    "E",
    "I",
    "I",
    "I",
    "I",
    "O",
    "O",
    "O",
    "O",
    "O",
    "U",
    "U",
    "U",
    "U",
    "C"
  );
  return str_replace($array1, $array2, $texto);
}
// Cria uma função que retorna o timestamp de uma data no formato DD/MM/AAAA
function geraTimestamp($data) {
  $partes = explode('/', $data);
  return mktime(0, 0, 0, $partes[1], $partes[0], $partes[2]);
}
function obterDataBRTimestamp($data) {
  if($data != '') {
    $data = substr($data, 0, 10);
    $explodida = explode("-", $data);
    $dataIso = $explodida[2] . "/" . $explodida[1] . "/" . $explodida[0];
    return $dataIso;
  }
  return NULL;
}
function obterDataHoraBRTimestamp($data) {
  if($data != '') {
    $dataBr = substr($data, 0, 10);
    $horaBr = substr($data, 11, 5);
    $explodida = explode("-", $dataBr);
    $dataHoraIso = $explodida[2] . "/" . $explodida[1] . "/" . $explodida[0] . " " . $horaBr;
    return $dataHoraIso;
  }
  return NULL;
}
function convertDataBR2ISO($data) {
  if($data == '')
    return false;
  $explodida = explode("/", $data);
  $dataIso = $explodida[2] . "-" . $explodida[1] . "-" . $explodida[0];
  return $dataIso;
}
function obterHoraTimestamp($data) {
  return substr($data, 11, 5);
}
function obterDiaTimestamp($data) {
  return substr($data, 8, 2);
}
function obterMesTimestamp($data) {
  return substr($data, 5, 2);
}
function obterAnoTimestamp($data) {
  return substr($data, 0, 4);
}
function calculaDiferencaDatas($data_inicial, $data_final) {
  // Usa a função criada e pega o timestamp das duas datas:
  $time_inicial = geraTimestamp($data_inicial);
  $time_final = geraTimestamp($data_final);
  // Calcula a diferença de segundos entre as duas datas:
  $diferenca = $time_final - $time_inicial; // 19522800 segundos
  // Calcula a diferença de dias
  $dias = (int) floor($diferenca / (60 * 60 * 24)); // 225 dias
  // Exibe uma mensagem de resultado:
  // echo "A diferença entre as datas ".$data_inicial." e ".$data_final." é de <strong>".$dias."</strong> dias";
  return $dias;
}
function apelidometadatos($variavel) {
  /*
   * $a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ ,;:./';
   * $b = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr______';
   * //$string = ($string);
   * $string = strtr($string, ($a), $b); //substitui letras acentuadas por "normais"
   * $string = str_replace(" ","",$string); // retira espaco
   * $string = strtolower($string); // passa tudo para minusculo
   */
  $string = strtolower(ereg_replace("[^a-zA-Z0-9-]", "-", strtr((trim($variavel)), ("áàãâéêíóôõúüñçÁÀÃÂÉÊÍÓÔÕÚÜÑÇ"), "aaaaeeiooouuncAAAAEEIOOOUUNC-")));
  return ($string); // finaliza, gerando uma saída para a funcao
}
function getExtensaoArquivo($extensao) {
  switch($extensao){
    case 'image/jpeg' :
    $ext = ".jpeg";
    break;
    case 'image/jpg' :
    $ext = ".jpg";
    break;
    case 'image/pjpeg' :
    $ext = ".pjpg";
    break;
    case 'image/JPEG' :
    $ext = ".JPEG";
    break;
    case 'image/gif' :
    $ext = ".gif";
    break;
    case 'image/png' :
    $ext = ".png";
    break;
    case 'video/webm' :
    $ext = ".webm";
    break;
    case 'video/mp4' :
    $ext = ".mp4";
    break;
    case 'video/flv' :
    $ext = ".flv";
    break;
    case 'video/webm' :
    $ext = ".webm";
    break;
    case 'audio/mp4' :
    $ext = ".acc";
    break;
    case 'audio/mpeg' :
    $ext = ".mp3";
    break;
    case 'audio/ogg' :
    $ext = ".ogg";
    break;
  }
  return $ext;
}
function uploadArquivoPermitido($arquivo) {
  $tiposPermitidos = array(
    'image/gif',
    'image/jpeg',
    'image/jpg',
    'image/pjpeg',
    'image/png',
    'video/webm',
    'video/mp4',
    'video/ogv',
    'audio/mp3',
    'audio/mp4',
    'audio/mpeg',
    'audio/ogg'
  );
  if(array_search($arquivo, $tiposPermitidos) === false) {
    return false;
  } else {
    return true;
  } // end if
}
function converteValorMonetario($valor) {
  $valor = str_replace('.', '', $valor);
  $valor = str_replace('.', '', $valor);
  $valor = str_replace('.', '', $valor);
  $valor = str_replace(',', '.', $valor);
  return $valor;
}
function valorMonetario($valor) {
  $valor = number_format($valor, 2, ',', '.');
  return $valor;
}
function real2float($num) {
  $num = str_replace(".", "", $num);
  $num = str_replace(",", ".", $num);
  return $num;
}
function fdec($numero, $formato = NULL, $tmp = NULL) {
  switch($formato){
    case null :
    if($numero != 0)
      $numero = number_format($numero, 2, ',', '.');
    else
      $numero = '0,00';
    break;
    case '%' :
    if($numero > 0)
      $numero = number_format((($numero / $tmp) * 100), 2, ',', '.') . '%';
    else
      $numero = '0%';
    break;
    case '-' :
    $numero = "<font color='red'>" . fdec($numero) . "</font>";
    break;
  }
  return $numero;
}
function verificarloginduplicado($usuario, $idsessao, $query) {
  $retorno = true;
  $querysessao = $db->query($query);
  $qtdsessao = $querysessao->rowCount();
  if($qtdsessao == 0) {
    $retorno = false;
  }
  return $retorno;
}
function historicoacesso($pagina, $apelido, $operacao, $usuario, $ip) {
  $oConn = Conexao::getInstance();
  $retorno = true;
  if($pagina != '' && $operacao != '') {
    $rsUsuarioHist = $oConn->prepare("SELECT count(id) total FROM usuario_hist WHERE datacadastro = now() AND ip = ? AND apelido = ? AND operacao = ?");
    $rsUsuarioHist->bindValue(1, $ip);
    $rsUsuarioHist->bindValue(2, $apelido);
    $rsUsuarioHist->bindValue(3, $operacao);
    $rsUsuarioHist->execute();
    $countUsuarioHist = $rsUsuarioHist->fetch(PDO::FETCH_OBJ)->total;
    if($countUsuarioHist <= 0) {
      $usuarioHist = $oConn->prepare("INSERT INTO usuario_hist(pagina, apelido, operacao, datacadastro, idusuario, ip) VALUES(?, ?, ?, now(), ?, ?)");
      $usuarioHist->bindValue(1, $pagina);
      $usuarioHist->bindValue(2, $apelido);
      $usuarioHist->bindValue(3, $operacao);
      $usuarioHist->bindValue(4, $usuario);
      $usuarioHist->bindValue(5, $ip);
      // $usuarioHist->bindValue(6, $ip);
      if(! $usuarioHist->execute()) {
        $retorno = false;
      }
    }
  }
  return $retorno;
}
function envia_email($para, $assunto, $mensagem, $emaile, $emaileNome, $usuarioNome) {
  $usuarioNome = utf8_decode($usuarioNome);
  $mail = new PHPMailer();
  $mail->IsSMTP();
  // $mail->SMTPDebug = 1;
  // $mail->CharSet = 'utf-8';
  // $mail->Encoding = 'base64';
  $mail->Host     = "smtp.umbler.com";
  $mail->SMTPAuth = true;
  $mail->Port     = 587;
  $mail->Username = $emaile;
  $mail->Password = '!zatu@2022#';
  $mail->SetFrom($emaile, utf8_decode($emaileNome));
  $mail->AddAddress($para, $usuarioNome);
  // $mail->AddAddress($para);
  $mail->AddReplyTo($emaile, $emaileNome);
  $mail->AddBCC($emaile, $usuarioNome);
  $mail->IsHTML(true);
  $mail->Subject = utf8_decode($assunto);
  $mail->Body = utf8_decode($mensagem);
  $mail->AltBody = utf8_decode($mensagem);
  $enviado = $mail->Send();
  $mail->ClearAllRecipients();
  if ($enviado) {
    return 'sucesso';
  } else {
    echo "Motivo do erro: " . $mail->ErrorInfo;
    return false;
  }

  // $headers = "From: $emaile\r\n";
  // // $headers .= "Reply-To: $email\r\n";
  // //não esqueça de substituir este email pelo seu.
  // $status = mail($para, $assunto, $mensagem, $headers);
  // //enviando o email.
  // if ($status) {
  //   echo "<script> alert('Formulário enviado com sucesso!'); </script>";
  //   return true;
  //   //mensagem de form enviado com sucesso.
  // } else {
  //   echo "<script> alert('Falha ao enviar o Formulário.'); </script>";
  //   return false;
  //   //mensagem de erro no envio. 
  // }

  // Inicia a classe PHPMailer
  // $mail = new PHPMailer();
  // // Define os dados do servidor e tipo de conexão
  // // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
  // $mail->IsSMTP(); // Define que a mensagem será SMTP
  // $mail->Host         = "smtp.gmail.com"; // Endereço do servidor SMTP (caso queira utilizar a autenticação, utilize o host smtp.seudomínio.com.br)
  // $mail->SMTPAuth     = true; // Usar autenticação SMTP (obrigatório para smtp.seudomínio.com.br)
  // $mail->SMTPDebug    = 1;   // Debugar: 1 = erros e mensagens, 2 = mensagens apenas
  // $mail->Port         = 26;      // A porta ### deverá estar aberta em seu servidor - set the SMTP port for the GMAIL server
  // $mail->SMTPSecure   = 'ssl';  // SSL REQUERIDO pelo GMail
  // // $mail->Username     = 'admptk2017@gmail.com'; // Usuário do servidor SMTP (endereço de email)
  // $mail->Username     = 'rojheriorosas@gmail.com'; // Usuário do servidor SMTP (endereço de email)
  // $mail->Password     = '!Osempnmf252625'; // Senha do servidor SMTP (senha do email usado)
  // $mail->SetFrom($emaile, $nome_email);
  // $mail->AddAddress($para, 'teste'); // E-mail do destinatário
  // $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
  // $mail->Subject = $assunto; // Assunto da mensagem
  // $mail->MsgHTML($mensagem);
  // $mail->AltBody = $mensagem;
  // if(!$mail->Send()) {
  //   $error = 'Mail error: '.$mail->ErrorInfo; 
  //   echo $error;
  //   $mail->ClearAllRecipients();
  //   $mail->ClearAttachments();
  //   return false;
  // } else {
  //   $error = 'Mensagem enviada!';
  //   echo $error;
  //   $mail->ClearAllRecipients();
  //   $mail->ClearAttachments();
  //   return true;
  // }

  // $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
  // $mail->IsSMTP(); // telling the class to use SMTP

  // try {
  //   $mail->Host       = "smtp.umbler.com"; // SMTP server
  //   $mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
  //   $mail->SMTPAuth   = true;                  // enable SMTP authentication
  //   $mail->Host       = "smtp.umbler.com"; // sets the SMTP server
  //   $mail->Port       = 587;                    // set the SMTP port for the GMAIL server
  //   // $mail->SMTPSecure = 'tls';  // SSL REQUERIDO pelo GMail PORT 465 para GMAIL ||| tls para umbler
  //   $mail->Username   = "suporte@zatu.com.br"; // SMTP account username
  //   $mail->Password   = "!zatu@2022#";        // SMTP account password
  //   // $mail->CharSet    = 'utf-8';
  //   $mail->SetFrom($emaile, $emaileNome);
  //   $mail->AddAddress($para, $usuarioNome);
  //   // $mail->Username   = "admptk2017@zatu.com.br"; // SMTP account username
  //   // $mail->Password   = "!zatu@2022#";        // SMTP account password
  //   // $mail->AddAddress($para, 'Teste');
  //   // $mail->SetFrom('admptk2017@zatu.com.br', 'TESTE');
  //   // $mail->AddReplyTo($emaile, $emaileNome);
  //   $mail->Subject = $assunto;
  //   $mail->AltBody = $mensagem; // optional - MsgHTML will create an alternate automatically
  //   $mail->MsgHTML($mensagem);
  //   // $mail->AddAttachment('images/phpmailer.gif');      // attachment
  //   // $mail->AddAttachment('images/phpmailer_mini.gif'); // attachment
  //   $mail->Send();
  //   echo "Mensagem enviada com sucesso</p>\n";
  //   return true;
  // } catch (phpmailerException $e) {
  //   echo $mail->ErrorInfo;
  //   echo $e->errorMessage(); //Pretty error messages from PHPMailer
  //   return false;
  // } catch (Exception $e) {
  //   echo $e->getMessage(); //Boring error messages from anything else!
  //   return false;
  // }

  // try {
  //   $mail->SMTPOptions = array(
  //     'ssl' => array(
  //       'verify_peer' => false,
  //       'verify_peer_name' => false,
  //       'allow_self_signed' => true
  //     )
  //   );
  //   $mail->Host       = "smtp.gmail.com"; // SMTP server
  //   $mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
  //   $mail->SMTPAuth   = true;                  // enable SMTP authentication
  //   $mail->Host       = "smtp.gmail.com"; // sets the SMTP server
  //   $mail->Port       = 465;                    // set the SMTP port for the GMAIL server
  //   $mail->SMTPSecure = 'ssl';  // SSL REQUERIDO pelo GMail
  //   $mail->Username   = "rojheriorosas@gmail.com"; // SMTP account username
  //   $mail->Password   = "fraviekmnzzsdqul";        // SMTP account password
  //   $mail->SetFrom($emaile, $emaileNome);
  //   $mail->AddAddress($para, $usuarioNome);
  //   // $mail->Username   = "admptk2017@zatu.com.br"; // SMTP account username
  //   // $mail->Password   = "!zatu@2022#";        // SMTP account password
  //   // $mail->AddAddress($para, 'Teste');
  //   // $mail->SetFrom('admptk2017@zatu.com.br', 'TESTE');
  //   // $mail->AddReplyTo('name@yourdomain.com', 'First Last');
  //   $mail->Subject = $assunto;
  //   $mail->AltBody = $mensagem; // optional - MsgHTML will create an alternate automatically
  //   $mail->MsgHTML($mensagem);
  //   // $mail->AddAttachment('images/phpmailer.gif');      // attachment
  //   // $mail->AddAttachment('images/phpmailer_mini.gif'); // attachment
  //   $mail->Send();
  //   echo "Mensagem enviada com sucesso</p>\n";
  //   return true;
  // } catch (phpmailerException $e) {
  //   echo $mail->ErrorInfo;
  //   echo $e->errorMessage(); //Pretty error messages from PHPMailer
  //   return false;
  // } catch (Exception $e) {
  //   echo $e->getMessage(); //Boring error messages from anything else!
  //   return false;
  // }
}
function pegar_nome_campo_municipio($municipio_id) {
  $rs = "";
  if($municipio_id == 5565) {
    $rs = "acre";
  } else if($municipio_id == 79) {
    $rs = "acrelandia";
  } else if($municipio_id == 80) {
    $rs = "assis_brasil";
  } else if($municipio_id == 81) {
    $rs = "brasileia";
  } else if($municipio_id == 82) {
    $rs = "bujari";
  } else if($municipio_id == 83) {
    $rs = "capixaba";
  } else if($municipio_id == 84) {
    $rs = "cruzeiro_do_sul";
  } else if($municipio_id == 85) {
    $rs = "epitaciolandia";
  } else if($municipio_id == 86) {
    $rs = "feijo";
  } else if($municipio_id == 87) {
    $rs = "jordao";
  } else if($municipio_id == 88) {
    $rs = "mancio_lima";
  } else if($municipio_id == 89) {
    $rs = "manoel_urbano";
  } else if($municipio_id == 90) {
    $rs = "marechal_thaumaturgo";
  } else if($municipio_id == 91) {
    $rs = "placido_de_castro";
  } else if($municipio_id == 92) {
    $rs = "porto_acre";
  } else if($municipio_id == 93) {
    $rs = "porto_walter";
  } else if($municipio_id == 94) {
    $rs = "rio_branco";
  } else if($municipio_id == 95) {
    $rs = "rodrigues_alves";
  } else if($municipio_id == 96) {
    $rs = "santa_rosa";
  } else if($municipio_id == 97) {
    $rs = "sena_madureira";
  } else if($municipio_id == 98) {
    $rs = "senador_guiomard";
  } else if($municipio_id == 99) {
    $rs = "tarauaca";
  } else if($municipio_id == 100) {
    $rs = "xapuri";
  }
  return $rs;
}
function valor_global_pdi($meta_id, $municipio_id) {
  $soma = 0;
  $result = $db->prepare("SELECT id AS valor_id
    FROM pdi_valor
    WHERE meta_id = ?");
  $result->bindValue(1, $meta_id);
  $result->execute();
  while($pdi = $result->fetch(PDO::FETCH_ASSOC)) {
    $result2 = $db->prepare("SELECT * FROM pdi_fonte_executada WHERE valor_id = ?");
    $result2->bindValue(1, $pdi['valor_id']);
    $result2->execute();
    while($fonte = $result2->fetch(PDO::FETCH_ASSOC)) {
      $soma += $fonte["" . pegar_nome_campo_municipio($municipio_id) . ""];
    }
  }
  return $soma;
}
function valor_global_hist_pdi($meta_id, $municipio_id, $controle) {
  $soma = 0;
  $result = $db->prepare("SELECT id AS valor_id
    FROM hist_pdi_valor
    WHERE meta_id = ? AND controle = ?");
  $result->bindValue(1, $meta_id);
  $result->bindValue(2, $controle);
  $result->execute();
  while($pdi = $result->fetch(PDO::FETCH_ASSOC)) {
    $result2 = $db->prepare("SELECT * FROM hist_pdi_fonte_executada WHERE valor_id = ? AND controle = ?");
    $result2->bindValue(1, $pdi['valor_id']);
    $result2->bindValue(2, $controle);
    $result2->execute();
    while($fonte = $result2->fetch(PDO::FETCH_ASSOC)) {
      $soma += $fonte["" . pegar_nome_campo_municipio($municipio_id) . ""];
    }
  }
  return $soma;
}
// FUNÇÃO QUE CAPTURA O VALOR DE UM ARRAY E IGNORA OS VALORES VAZIOS
function pegar_valor_array($valor_array) {
  $rs = "";
  foreach($valor_array as $key => $value) {
    if($value != "" && $value != NULL && $value != "0" && $value != "undefined") {
      $rs = $value;
    }
  }
  return $rs;
}
function romano($N) {
  $N1 = $N;
  $Y = "";
  while($N / 1000 >= 1) {
    $Y .= "M";
    $N = $N - 1000;
  }
  if($N / 900 >= 1) {
    $Y .= "CM";
    $N = $N - 900;
  }
  if($N / 500 >= 1) {
    $Y .= "D";
    $N = $N - 500;
  }
  if($N / 400 >= 1) {
    $Y .= "CD";
    $N = $N - 400;
  }
  while($N / 100 >= 1) {
    $Y .= "C";
    $N = $N - 100;
  }
  if($N / 90 >= 1) {
    $Y .= "XC";
    $N = $N - 90;
  }
  if($N / 50 >= 1) {
    $Y .= "L";
    $N = $N - 50;
  }
  if($N / 40 >= 1) {
    $Y .= "XL";
    $N = $N - 40;
  }
  while($N / 10 >= 1) {
    $Y .= "X";
    $N = $N - 10;
  }
  if($N / 9 >= 1) {
    $Y .= "IX";
    $N = $N - 9;
  }
  if($N / 5 >= 1) {
    $Y .= "V";
    $N = $N - 5;
  }
  if($N / 4 >= 1) {
    $Y .= "IV";
    $N = $N - 4;
  }
  while($N >= 1) {
    $Y .= "I";
    $N = $N - 1;
  }
  return $Y;
}
function valorPorExtenso($valor = 0, $bolExibirMoeda = true, $bolPalavraFeminina = false) {
  $singular = null;
  $plural = null;
  if($bolExibirMoeda) {
    $singular = array(
      "centavo",
      "real",
      "mil",
      "milhão",
      "bilhão",
      "trilhão",
      "quatrilhão"
    );
    $plural = array(
      "centavos",
      "reais",
      "mil",
      "milhões",
      "bilhões",
      "trilhões",
      "quatrilhões"
    );
  } else {
    $singular = array(
      "",
      "",
      "mil",
      "milhão",
      "bilhão",
      "trilhão",
      "quatrilhão"
    );
    $plural = array(
      "",
      "",
      "mil",
      "milhões",
      "bilhões",
      "trilhões",
      "quatrilhões"
    );
  }
  $c = array(
    "",
    "cem",
    "duzentos",
    "trezentos",
    "quatrocentos",
    "quinhentos",
    "seiscentos",
    "setecentos",
    "oitocentos",
    "novecentos"
  );
  $d = array(
    "",
    "dez",
    "vinte",
    "trinta",
    "quarenta",
    "cinquenta",
    "sessenta",
    "setenta",
    "oitenta",
    "noventa"
  );
  $d10 = array(
    "dez",
    "onze",
    "doze",
    "treze",
    "quatorze",
    "quinze",
    "dezesseis",
    "dezesete",
    "dezoito",
    "dezenove"
  );
  $u = array(
    "",
    "um",
    "dois",
    "três",
    "quatro",
    "cinco",
    "seis",
    "sete",
    "oito",
    "nove"
  );
  if($bolPalavraFeminina) {
    if($valor == 1) {
      $u = array(
        "",
        "uma",
        "duas",
        "três",
        "quatro",
        "cinco",
        "seis",
        "sete",
        "oito",
        "nove"
      );
    } else {
      $u = array(
        "",
        "um",
        "duas",
        "três",
        "quatro",
        "cinco",
        "seis",
        "sete",
        "oito",
        "nove"
      );
    }
    $c = array(
      "",
      "cem",
      "duzentas",
      "trezentas",
      "quatrocentas",
      "quinhentas",
      "seiscentas",
      "setecentas",
      "oitocentas",
      "novecentas"
    );
  }
  $z = 0;
  $valor = number_format($valor, 2, ".", ".");
  $inteiro = explode(".", $valor);
  for($i = 0; $i < count($inteiro); $i ++) {
    for($ii = mb_strlen($inteiro[$i]); $ii < 3; $ii ++) {
      $inteiro[$i] = "0" . $inteiro[$i];
    }
  }
  // $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
  $rt = null;
  $fim = count($inteiro) - ($inteiro[count($inteiro) - 1] > 0 ? 1 : 2);
  for($i = 0; $i < count($inteiro); $i ++) {
    $valor = $inteiro[$i];
    $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
    $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
    $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";
    $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
    $t = count($inteiro) - 1 - $i;
    $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
    if($valor == "000")
      $z ++;
    elseif($z > 0)
      $z --;
    if(($t == 1) && ($z > 0) && ($inteiro[0] > 0))
      $r .= (($z > 1) ? " de " : "") . $plural[$t];
    if($r)
      $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? (($i < $fim) ? ", " : " e ") : " ") . $r;
  }
  $rt = mb_substr($rt, 1);
  return ($rt ? trim($rt) : "zero");
}
function mask($val, $mask) {
  $maskared = '';
  $k = 0;
  for($i = 0; $i <= strlen($mask) - 1; $i ++) {
    if($mask[$i] == '#') {
      if(isset($val[$k])) {
        $maskared .= $val[$k ++];
      }
    } else {
      if(isset($mask[$i])) {
        $maskared .= $mask[$i];
      }
    }
  }
  return $maskared;
}
// retorna diferença em horas
function retorna_dif_horas($data) {
  $hora_data_atual = date("Y-m-d H:i:s");
  $data = strtotime($data);
  $hora_data_atual = strtotime($hora_data_atual);
  $diferenca = $hora_data_atual - $data;
  $horas = floor($diferenca / 3600);
  return $horas;
}
// Remove acentos e caracteres especiais
function removeCaracteresEspeciais($texto){
  $caracteresSemAcento = array(
    'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj',''=>'Z', ''=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
    'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
    'Ï'=>'I', 'Ñ'=>'N', 'Ń'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
    'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
    'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
    'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ń'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
    'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f',
    'ă'=>'a', 'î'=>'i', 'â'=>'a', 'ș'=>'s', 'ț'=>'t', 'Ă'=>'A', 'Î'=>'I', 'Â'=>'A', 'Ș'=>'S', 'Ț'=>'T',
  );
  $novoTexto = preg_replace("/[^a-zA-Z0-9]/", "", strtr($texto, $caracteresSemAcento));
  return $novoTexto;
}
// Remove acentos
function removeAcentos($texto){
  $caracteresSemAcento = array(
    'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj',''=>'Z', ''=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
    'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
    'Ï'=>'I', 'Ñ'=>'N', 'Ń'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
    'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
    'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
    'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ń'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
    'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f',
    'ă'=>'a', 'î'=>'i', 'â'=>'a', 'ș'=>'s', 'ț'=>'t', 'Ă'=>'A', 'Î'=>'I', 'Â'=>'A', 'Ș'=>'S', 'Ț'=>'T',
  );
  $novoTexto = strtr($texto, $caracteresSemAcento);
  return $novoTexto;
}
function removeAcentosAscii($string, $slug = false) {
  $string = strtolower($string);
  // Código ASCII das vogais
  $ascii['a'] = range(224, 230);
  $ascii['e'] = range(232, 235);
  $ascii['i'] = range(236, 239);
  $ascii['o'] = array_merge(range(242, 246), array(
    240,
    248
  ));
  $ascii['u'] = range(249, 252);
  // Código ASCII dos outros caracteres
  $ascii['b'] = array(
    223
  );
  $ascii['c'] = array(
    231
  );
  $ascii['d'] = array(
    208
  );
  $ascii['n'] = array(
    241
  );
  $ascii['y'] = array(
    253,
    255
  );
  foreach($ascii as $key => $item) {
    $acentos = '';
    foreach($item as $codigo)
      $acentos .= chr($codigo);
    $troca[$key] = '/[' . $acentos . ']/i';
  }
  $string = preg_replace(array_values($troca), array_keys($troca), $string);
  // Slug?
  if($slug) {
    // Troca tudo que não for letra ou número por um caractere ($slug)
    $string = preg_replace('/[^a-z0-9]/i', $slug, $string);
    // Tira os caracteres ($slug) repetidos
    $string = preg_replace('/' . $slug . '{2,}/i', $slug, $string);
    $string = trim($string, $slug);
  }
  return $string;
}
?>