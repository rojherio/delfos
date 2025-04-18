<?php
$db = Conexao::getInstance();
$id = strip_tags(@$_POST['id']);
$nome = strip_tags(@$_POST['nome_tipo']);
$status = strip_tags(@$_POST['status_tipo']) == "on" ? 1 : 0;
$error = false;
$msg = array();
$mensagem = "";
try {
  $db->beginTransaction();
  //VERIFICA SE O NOME DO PROJETO JÁ FOI INFORMADO
  // $id_nome = pesquisar("id", "bsc_unidade_organizacional_tipo", "nome", "LIKE", $nome, "");
  $stmt = $db->prepare('SELECT id FROM bsc_unidade_organizacional_tipo WHERE nome LIKE ?;');
  $stmt->bindValue(1, $nome);
  $stmt->execute();
  $rs = $stmt->fetch(PDO::FETCH_ASSOC);
  $id_pesquisa = $rs['id'];
  if (!is_null($id_pesquisa) && $id_pesquisa != $id) {
    $error = true;
    $mensagem .= "O nome do tipo de unidade organizacional informado já existe no sistema.";
    $msg['tipo'] = "nome";
  }
  if ($error == false) {
    if ($id != "" && $id != 0) {
      $stmt = $db->prepare('UPDATE bsc_unidade_organizacional_tipo set nome = ?, status = ?, dt_cadastro = NOW(), seg_usuario_id = ? WHERE id = ?;');
      $stmt->bindValue(1, $nome);
      $stmt->bindValue(2, $status);
      $stmt->bindValue(3, $_SESSION['zatu_id']); //ID DO USUÁRIO LOGADO NO SISTEMA
      $stmt->bindValue(4, $id);
      $stmt->execute();
      $db->commit();
      //MENSAGEM DE SUCESSO
      $msg['id'] = $id;
      $msg['msg'] = 'success';
      $msg['retorno'] = 'Tipo de unidade organizacional atualizado com sucesso.';
      echo json_encode($msg);
      exit();
    } else {
      $stmt = $db->prepare('INSERT INTO bsc_unidade_organizacional_tipo (nome, status, dt_cadastro, seg_usuario_id) VALUES (?, ?, NOW(), ?);');
      $stmt->bindValue(1, $nome);
      $stmt->bindValue(2, $status);
      $stmt->bindValue(3, $_SESSION['zatu_id']); //ID DO USUÁRIO LOGADO NO SISTEMA
      $stmt->execute();
      $unidade_organizacional_tipo_id = $db->lastInsertId();
      $db->commit();
      //MENSAGEM DE SUCESSO
      $msg['id'] = $unidade_organizacional_tipo_id;
      $msg['msg'] = 'success';
      $msg['retorno'] = 'Tipo de unidade organizacional cadastrado com sucesso.';
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
  $msg['retorno'] = "Erro ao tentar salvar os dados do tipo de unidade organizacional: " . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>