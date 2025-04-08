<?php
$db = Conexao::getInstance();
$id = strip_tags(@$_POST['id']);
$error = false;
$msg = array();
$mensagem = "";
try {
  $db->beginTransaction();
  //VERIFICA SE O NOME DO PROJETO JÁ FOI INFORMADO
  // $id_nome = pesquisar("id", "bsc_unidade_organizacional_tipo", "nome", "LIKE", $nome, "");
  $stmt = $db->prepare('SELECT c.id, sc.id  
                        FROM eo_cargo AS c 
                        LEFT JOIN rh_servidor_contrato AS sc ON c.id = sc.eo_cargo_id
                        WHERE sc.eo_cargo_id = ?;');
  $stmt->bindValue(1, $id);
  $stmt->execute();
  $rs = $stmt->fetch(PDO::FETCH_ASSOC);
  $id_pesquisa = $rs['id'];
  if (!is_null($id_pesquisa)) {
    $error = true;
    $mensagem .= "Este registro não pode ser exlcuido pois está vinculado a um contrato de servidor!";
    $msg['tipo'] = "nome";
  }
  if ($error == false) {
    if ($id != "" && $id != 0) {
      $stmt = $db->prepare('DELETE FROM eo_cargo WHERE id = ?;');
      $stmt->bindValue(1, $id);
      $stmt->execute();
      $db->commit();
      //MENSAGEM DE SUCESSO
      $msg['id'] = $id;
      $msg['msg'] = 'success';
      $msg['retorno'] = 'Cargo excluido com sucesso.';
      echo json_encode($msg);
      exit();
    } 
  } else {
    $db->rollback();
    $msg['msg'] = 'error';
    $msg['retorno'] = $mensagem;
    echo json_encode($msg);
    exit();
  }
} catch (PDOException $e) {
  $db->rollback();
  $msg['msg'] = 'error';
  $msg['retorno'] = "Erro ao tentar excluir o Cargo: " . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>