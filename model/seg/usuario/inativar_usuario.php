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
  if ($id != "" && $id != 0) {
    $stmt = $db->prepare('UPDATE seg_usuario set status = 0, dt_cadastro = NOW(), seg_usuario_id = ? WHERE id = ?;');
    $stmt->bindValue(1, $_SESSION['zatu_id']); //ID DO USUÁRIO LOGADO NO SISTEMA
    $stmt->bindValue(2, $id);
    $stmt->execute();
    $db->commit();
    //MENSAGEM DE SUCESSO
    $msg['id'] = $id;
    $msg['msg'] = 'success';
    $msg['retorno'] = 'Usuário inativado com sucesso.';
    echo json_encode($msg);
    exit();
  } else {
    $db->rollback();
    //MENSAGEM DE SUCESSO
    $msg['msg'] = 'error';
    $msg['retorno'] = 'Erro ao tetnar inativar o usuário.';
    echo json_encode($msg);
    exit();
  }
} catch (PDOException $e) {
  $db->rollback();
  $msg['msg'] = 'error';
  $msg['retorno'] = "Erro ao tentar inativar o usuário: " . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>