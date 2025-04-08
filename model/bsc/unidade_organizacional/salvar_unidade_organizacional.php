<?php
$db             = Conexao::getInstance();
$id             = strip_tags(@$_POST['id']);
$numero         = strip_tags(@$_POST['numero_uo']);
$nome           = strip_tags(@$_POST['nome_uo']);
$tipoUo         = strip_tags(@$_POST['tipo_uo']);
$status         = strip_tags(@$_POST['status_uo']) == "on" ? 1 : 0;
$error          = false;
$msg            = array();
$mensagem       = "";
$setoresPost    = array();

$stmt = $db->prepare("
  SELECT 
  s.id  
  FROM eo_setor AS s 
  ORDER BY s.nome ASC ;");
$stmt->execute();
$rsSetores = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($rsSetores as $kObj => $vObj) {
  $setor = @$_POST['setor_'.$vObj['id'].'_uo'];
  if (isset($setor)){
    array_push($setoresPost, $vObj['id']);
  }
}
try {
  $db->beginTransaction();
  $stmt = $db->prepare('
    SELECT 
    uo.id 
    FROM bsc_unidade_organizacional AS uo
    WHERE 
    uo.nome LIKE ? 
    OR uo.numero LIKE ?;');
  $stmt->bindValue(1, $nome);
  $stmt->bindValue(2, $numero);
  $stmt->execute();
  $rs = $stmt->fetch(PDO::FETCH_ASSOC);
  $id_pesquisa = $rs['id'];
  if (!is_null($id_pesquisa) && $id_pesquisa != $id) {
    $error = true;
    $mensagem .= "O número ou nome da unidade organizacional informado já existe no sistema.";
    $msg['tipo'] = "nome";
  }
  if ($error == false) {
    if ($id != "" && $id != 0) {
      $stmt = $db->prepare('
        SELECT 
        id, 
        eo_setor_id 
        FROM eo_setor_unidade_organizacional 
        WHERE bsc_unidade_organizacional_id = ? 
        ORDER BY eo_setor_id;');
      $stmt->bindValue(1, $id);
      $stmt->execute();
      $rsSetoresOld = $stmt->fetchAll(PDO::FETCH_ASSOC);
      foreach ($rsSetoresOld as $kObjOld => $vObjOld) {
        if (!in_array($vObjOld['eo_setor_id'], $setoresPost)) {
          $stmt = $db->prepare('
            DELETE  
            FROM eo_setor_unidade_organizacional 
            WHERE id = ?');
          $stmt->bindValue(1, $vObjOld['id']);
          $stmt->execute();
        } else {
          unset($setoresPost[array_search($vObjOld['eo_setor_id'], $setoresPost)]);
        }
      }
      $stmt = $db->prepare('
        UPDATE bsc_unidade_organizacional 
        SET 
        numero = ?, 
        nome = ?, 
        status = ?, 
        bsc_unidade_organizacional_tipo_id = ?, 
        dt_cadastro = NOW(), 
        seg_usuario_id = ? 
        WHERE id = ?;');
      $stmt->bindValue(1, $numero);
      $stmt->bindValue(2, $nome);
      $stmt->bindValue(3, $status);
      $stmt->bindValue(4, $tipoUo);
      $stmt->bindValue(5, $_SESSION['zatu_id']); //ID DO USUÁRIO LOGADO NO SISTEMA
      $stmt->bindValue(6, $id);
      $stmt->execute();
    } else {
      $stmt = $db->prepare('
        INSERT INTO 
        bsc_unidade_organizacional (
          numero, 
          nome, 
          status, 
          bsc_unidade_organizacional_tipo_id , 
          dt_cadastro, 
          seg_usuario_id) 
        VALUES (?, ?, ?, ?, NOW(), ?);');
      $stmt->bindValue(1, $numero);
      $stmt->bindValue(2, $nome);
      $stmt->bindValue(3, $status);
      $stmt->bindValue(4, $tipoUo);
      $stmt->bindValue(5, $_SESSION['zatu_id']); //ID DO USUÁRIO LOGADO NO SISTEMA
      $stmt->execute();
      $uoIdNew = $db->lastInsertId();
      $id = $uoIdNew;
    }
    if (sizeof($setoresPost) > 0) {
      foreach ($setoresPost as $kObj => $vObj) {
        $stmt = $db->prepare('
          INSERT INTO eo_setor_unidade_organizacional
          (bsc_unidade_organizacional_id, 
            eo_setor_id, 
            status, 
            dt_cadastro, 
            seg_usuario_id)
          VALUES (?, ?, ?, NOW(), ?);');
        $stmt->bindValue(1, $id);
        $stmt->bindValue(2, $vObj);
        $stmt->bindValue(3, 1);
        $stmt->bindValue(4, $_SESSION['zatu_id']);
        $stmt->execute();
      }
    }
    $db->commit();
    $msg['id'] = $id;
    $msg['msg'] = 'success';
    $msg['retorno'] = 'Unidade organizacional cadastrado com sucesso.';
    echo json_encode($msg);
    exit();
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
  $msg['retorno'] = "Erro ao tentar salvar os dados da unidade organizacional: " . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>