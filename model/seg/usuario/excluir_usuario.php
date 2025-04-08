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
  $stmt = $db->prepare('
    SELECT 
    u.id, 
    uo.id  
    FROM seg_usuario AS u 
    LEFT JOIN bsc_unidade_organizacional AS uo ON u.id = uo.seg_usuario_id 
    WHERE uo.seg_usuario_id = ?;');
  $stmt->bindValue(1, $id);
  $stmt->execute();
  $rs = $stmt->fetch(PDO::FETCH_ASSOC);
  $id_pesquisa = $rs['id'];
  if (!is_null($id_pesquisa)) {
    $error = true;
    $mensagem .= "Este registro não pode ser exlcuido pois está vinculado a outro registro do sistema!";
    $msg['tipo'] = "nome";
  }
  if ($error == false) {
    if ($id != "" && $id != 0) {
      $stmt = $db->prepare('
        DELETE  
        FROM seg_usuario_acao 
        WHERE permissao_seg_usuario_id = ?');
      $stmt->bindValue(1, $id);
      $stmt->execute();
      $stmt = $db->prepare('
        DELETE  
        FROM seg_usuario_setor_uo  
        WHERE responsavel_seg_usuario_id = ?');
      $stmt->bindValue(1, $id);
      $stmt->execute();
      $stmt = $db->prepare('
        DELETE 
        FROM seg_usuario  
        WHERE id = ?;');
      $stmt->bindValue(1, $id);
      $stmt->execute();
      $db->commit();
      //MENSAGEM DE SUCESSO
      $msg['id'] = $id;
      $msg['msg'] = 'success';
      $msg['retorno'] = 'Usuário excluido com sucesso.';
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
  $msg['retorno'] = "Erro ao tentar excluir a unidade organizacional: " . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>