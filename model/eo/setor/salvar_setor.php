<?php
$db = Conexao::getInstance();
$id = strip_tags(@$_POST['id']);
$numero = strip_tags(@$_POST['numero_setor']);
$nome = strip_tags(@$_POST['nome_setor']);
$status = strip_tags(@$_POST['status_setor']) == "on" ? 1 : 0;
$error = false;
$msg = array();
$mensagem = "";
try {
  $db->beginTransaction();
  //VERIFICA SE O NOME DO PROJETO JÁ FOI INFORMADO
  // $id_nome = pesquisar("id", "bsc_unidade_organizacional_tipo", "nome", "LIKE", $nome, "");
  $stmt = $db->prepare('SELECT id FROM eo_setor WHERE nome LIKE ? OR numero LIKE ?;');
  $stmt->bindValue(1, $nome);
  $stmt->bindValue(2, $numero);
  $stmt->execute();
  $rs = $stmt->fetch(PDO::FETCH_ASSOC);
  $id_pesquisa = $rs['id'];
  if (!is_null($id_pesquisa) && $id_pesquisa != $id) {
    $error = true;
    $mensagem .= "O número ou o nome do Setor informado já existe no sistema.";
    $msg['tipo'] = "nome";
  }
  if ($error == false) {
    if ($id != "" && $id != 0) {
      $stmt = $db->prepare('UPDATE eo_setor set numero = ?, nome = ?, status = ?, dt_cadastro = NOW(), seg_usuario_id = ? WHERE id = ?;');
      $stmt->bindValue(1, $numero);
      $stmt->bindValue(2, $nome);
      $stmt->bindValue(3, $status);
      $stmt->bindValue(4, $_SESSION['zatu_id']); //ID DO USUÁRIO LOGADO NO SISTEMA
      $stmt->bindValue(5, $id);
      $stmt->execute();
      $db->commit();
      //MENSAGEM DE SUCESSO
      $msg['id'] = $id;
      $msg['msg'] = 'success';
      $msg['retorno'] = 'Setor atualizado com sucesso.';
      echo json_encode($msg);
      exit();
    } else {
      $stmt = $db->prepare('INSERT INTO eo_setor (numero, nome, status, dt_cadastro, seg_usuario_id) VALUES (?, ?, ?, NOW(), ?);');
      $stmt->bindValue(1, $numero);
      $stmt->bindValue(2, $nome);
      $stmt->bindValue(3, $status);
      $stmt->bindValue(4, $_SESSION['zatu_id']); //ID DO USUÁRIO LOGADO NO SISTEMA
      $stmt->execute();
      $setor_id = $db->lastInsertId();
      $db->commit();
      //MENSAGEM DE SUCESSO
      $msg['id'] = $setor_id;
      $msg['msg'] = 'success';
      $msg['retorno'] = 'Setor cadastrado com sucesso.';
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
  $msg['retorno'] = "Erro ao tentar salvar os dados do Setor: " . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>