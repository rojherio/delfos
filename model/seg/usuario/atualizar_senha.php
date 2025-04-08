<?php
$db = Conexao::getInstance();
$usuario_id = $_SESSION['zatu_id']; //Ainda não está pegando o id pela session
$senha = strip_tags(sha1(@$_POST['senha']));
$msg = array();
try {
  $db->beginTransaction();
  $stmt = $db->prepare("UPDATE seg_usuario 
                        SET senha = ? 
                        WHERE id = ?");
  $stmt->bindValue(1, $senha);
  $stmt->bindValue(2, $usuario_id);
  $stmt->execute();
  $db->commit();
  $_SESSION['redefinir_senha'] = 0;
  //MENSAGEM DE SUCESSO
  $msg['id'] = $usuario_id;
  $msg['msg'] = 'success';
  $msg['retorno'] = 'Senha atualizada com sucesso.';
  echo json_encode($msg);
  exit();
} catch(PDOException $e) {
  $db->rollback();
  $msg['msg'] = 'error';
  $msg['retorno'] = "Erro ao tentar salvar os dados da senha de acesso ao sistema: " . $e;
  echo json_encode($msg);
  exit();
}
?>

