<?php
$db = Conexao::getInstance();
$id = strip_tags(@$_POST['id']);
$msg = array();
$mensagem = "";
try {
  //VERIFICA SE O NOME DO PROJETO JÁ FOI INFORMADO
  if ($id != '' && $id != null) {
    $stmt = $db->prepare('SELECT suo.id, s.numero, s.nome 
                          FROM eo_setor_unidade_organizacional AS suo
                          LEFT JOIN eo_setor AS s ON s.id = suo.eo_setor_id
                          WHERE suo.bsc_unidade_organizacional_id = ? 
                          GROUP BY suo.id 
                          ORDER BY s.nome ASC;');
    $stmt->bindValue(1, $id);
    $stmt->execute();
    $rsSetores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($rsSetores) > 0 ) {
      $msg['retorno'] = '<option></option>';
      foreach ($rsSetores as $kObj => $vObj) {
        $msg['retorno'] .= '<option value="'.$vObj['id'].'">'.$vObj['nome'].'</option>';
      }
    } else {
      $msg['retorno'] = '<option>Nenhum Setor encontrado para a Unidade Organizacional selecionada</option>';
    }
    $msg['msg'] = 'success';
    echo json_encode($msg);
    exit();
  } else {
    $msg['msg'] = 'success';
    $msg['retorno'] = '<option>Selecione primeiro a Unidade Organizacional</option>';
    echo json_encode($msg);
  } 
} catch (PDOException $e) {
  $msg['msg'] = 'error';
  $msg['retorno'] = "Erro ao tentar buscar os Setores da Unidade Organizacional selecionado: " . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>