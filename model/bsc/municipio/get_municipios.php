<?php
$db = Conexao::getInstance();
$id = strip_tags(@$_POST['id']);
$msg = array();
$mensagem = "";
try {
  //VERIFICA SE O NOME DO PROJETO JÁ FOI INFORMADO
  if ($id != '' && $id != null) {
    $stmt = $db->prepare('SELECT id, nome FROM bsc_municipio WHERE bsc_estado_id = ?;');
    $stmt->bindValue(1, $id);
    $stmt->execute();
    $rsMunicipios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($rsMunicipios) > 0 ) {
      $msg['retorno'] = '<option></option>';
      foreach ($rsMunicipios as $kObj => $vObj) {
        $msg['retorno'] .= '<option value="'.$vObj['id'].'">'.$vObj['nome'].'</option>';
      }
    } else {
      $msg['retorno'] = '<option>Nenhum Município encontrado para o Estado selecionado</option>';
    }
    $msg['msg'] = 'success';
    echo json_encode($msg);
    exit();
  } else {
    $msg['msg'] = 'success';
    $msg['retorno'] = '<option>Selecione primeiro o Estado do usuário</option>';
    echo json_encode($msg);
  }
} catch (PDOException $e) {
  $msg['msg'] = 'error';
  $msg['retorno'] = "Erro ao tentar buscar os Municípios do Estado selecionado: " . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>