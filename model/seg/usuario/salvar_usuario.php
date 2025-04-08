<?php
$db                     = Conexao::getInstance();
$id                     = strip_tags(@$_POST['id']);
$nome                   = strip_tags(@$_POST['nome_u']);
$login                  = strip_tags(@$_POST['login_u']);
$dtNasc                 = strip_tags(@$_POST['dt_nasc_u']);
$cpf                    = strip_tags(@$_POST['cpf_u']);
$sexo                   = strip_tags(@$_POST['sexo_u']);
$emailInst              = strip_tags(@$_POST['email_inst_u']);
$emailPessoal           = strip_tags(@$_POST['email_pessoal_u']);
$telFixo                = strip_tags(@$_POST['tel_fixo_u']);
$telCel                 = strip_tags(@$_POST['tel_cel_u']);
$endLograd              = strip_tags(@$_POST['end_log_u']);
$endNum                 = strip_tags(@$_POST['end_num_u']);
$endComp                = strip_tags(@$_POST['end_comp_u']);
$endBairro              = strip_tags(@$_POST['end_bairro_u']);
$endCep                 = strip_tags(@$_POST['end_cep_u']);
$municipio              = strip_tags(@$_POST['municipio_u']);
$status                 = strip_tags(@$_POST['status_u']) == "on" ? 1 : 0;
$permView               = strip_tags(@$_POST['perm_view_u']);
$permView               = strip_tags(@$_POST['perm_save_u']);
$permEdit               = strip_tags(@$_POST['perm_edit_u']);
$permRel                = strip_tags(@$_POST['perm_rel_u']);
$permSuper              = strip_tags(@$_POST['perm_super_u']);
$permDel                = strip_tags(@$_POST['perm_del_u']);
$error                  = false;
$msg                    = array();
$mensagem               = "";
$acoesPost              = array();
$msg['tipo']            = "";
$stmt = $db->prepare('
  SELECT id 
  FROM seg_acao
  ORDER BY id ASC;');
$stmt->execute();
$rsAcoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($rsAcoes as $kAcao => $vAcao) {
  $acao = @$_POST['acao_'.$vAcao['id'].'_u'];
  if (isset($acao)){
    array_push($acoesPost, $vAcao['id']);
  }
}
try {
  $db->beginTransaction();
  $stmt = $db->prepare('
    SELECT id, nome, cpf, login, email_institucional, email_pessoal 
    FROM seg_usuario 
    WHERE nome LIKE ? 
    OR cpf LIKE ?
    OR login LIKE ? 
    OR email_institucional LIKE ? 
    OR email_pessoal LIKE ?;');
  $stmt->bindValue(1, $nome);
  $stmt->bindValue(2, $cpf);
  $stmt->bindValue(3, $login);
  $stmt->bindValue(4, $emailInst);
  $stmt->bindValue(5, $emailPessoal);
  $stmt->execute();
  $rsPesquisa = $stmt->fetch(PDO::FETCH_ASSOC);
  if (!is_null($rsPesquisa['id']) && $rsPesquisa['id'] != $id) {
    $error = true;
    $mensagem = array();
    $msg['tipo'] = "nome";
    $mensagem['nome']         = "";
    $mensagem['cpf']          = "";
    $mensagem['login']        = "";
    $mensagem['emailInst']    = "";
    $mensagem['emailPessoal'] = "";
    if ($rsPesquisa['nome'] == $nome) {
      $mensagem['nome'] = 'O nome do usuário informado já existe no sistema. ';
    }
    if ($rsPesquisa['cpf'] == $cpf) {
      $mensagem['cpf'] = 'O cpf do usuário informado já existe no sistema. ';
    }
    if ($rsPesquisa['login'] == $login) {
      $mensagem['login'] = 'O login do usuário informado já existe no sistema. ';
    }
    if ($rsPesquisa['email_institucional'] == $emailInst) {
      $mensagem['emailInst'] = 'O email institucional do usuário informado já existe no sistema. ';
    }
    if ($rsPesquisa['email_pessoal'] == $emailPessoal) {
      $mensagem['emailPessoal'] = 'O email pessoal do usuário informado já existe no sistema.';
    }
    $db->rollback();
    $msg['msg'] = 'error';
    $msg['retorno'] = $mensagem;
    echo json_encode($msg);
    exit();
  }
  if ($error == false) {
    if (is_numeric($id) && $id != "" && $id != 0 ) {
      $stmt = $db->prepare('
        SELECT id, seg_acao_id 
        FROM seg_usuario_acao 
        WHERE permissao_seg_usuario_id = ? 
        ORDER BY seg_acao_id;');
      $stmt->bindValue(1, $id);
      $stmt->execute();
      $rsAcoesOld = $stmt->fetchAll(PDO::FETCH_ASSOC);
      foreach ($rsAcoesOld as $kAcaoOld => $vAcaoOld) {
        if (!in_array($vAcaoOld['seg_acao_id'], $acoesPost)) {
          $stmt = $db->prepare('
            DELETE  
            FROM seg_usuario_acao 
            WHERE id = ?');
          $stmt->bindValue(1, $vAcaoOld['id']);
          $stmt->execute();
        } else {
          unset($acoesPost[array_search($vAcaoOld['seg_acao_id'], $acoesPost)]);
        }
      }
      $msg['acao2'] = $acoesPost;
      $stmt = $db->prepare('UPDATE seg_usuario 
        SET 
        nome = ?, 
        dt_nascimento = ?, 
        sexo = ?, 
        login = ?, 
        cpf = ?, 
        end_cep = ?, 
        end_logradouro = ?, 
        end_numero = ?, 
        end_complemento = ?, 
        end_bairro = ?,
        tel_fixo = ?, 
        tel_celular = ?, 
        email_pessoal = ?, 
        email_institucional = ?, 
        status = ?, 
        dt_cadastro = NOW(), 
        bsc_municipio_id = ?, 
        seg_usuario_id = ?
        WHERE id = ?;');
      $stmt->bindValue(1, $nome);
      $stmt->bindValue(2, formata_data($dtNasc));
      $stmt->bindValue(3, $sexo);
      $stmt->bindValue(4, $login);
      $stmt->bindValue(5, $cpf);
      $stmt->bindValue(6, $endCep);
      $stmt->bindValue(7, $endLograd);
      $stmt->bindValue(8, $endNum);
      $stmt->bindValue(9, $endComp);
      $stmt->bindValue(10, $endBairro);
      $stmt->bindValue(11, $telFixo);
      $stmt->bindValue(12, $telCel);
      $stmt->bindValue(13, $emailPessoal);
      $stmt->bindValue(14, $emailInst);
      $stmt->bindValue(15, $status);
      $stmt->bindValue(16, $municipio);
      $stmt->bindValue(17, $_SESSION['zatu_id']);
      $stmt->bindValue(18, $id);
      $stmt->execute();
      //MENSAGEM DE SUCESSO
    } else {
      $stmt = $db->prepare('INSERT INTO seg_usuario 
        (
          nome, 
          dt_nascimento, 
          sexo, 
          login, 
          senha, 
          cpf, 
          end_cep, 
          end_logradouro, 
          end_numero, 
          end_complemento, 
          end_bairro, 
          tel_fixo, 
          tel_celular, 
          email_pessoal, 
          email_institucional, 
          status, 
          bsc_municipio_id, 
          seg_usuario_id, 
          dt_cadastro
          ) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW());');
      $stmt->bindValue(1, $nome);
      $stmt->bindValue(2, formata_data($dtNasc));
      $stmt->bindValue(3, $sexo);
      $stmt->bindValue(4, $login);
      $stmt->bindValue(5, SHA1("123456"));
      $stmt->bindValue(6, $cpf);
      $stmt->bindValue(7, $endCep);
      $stmt->bindValue(8, $endLograd);
      $stmt->bindValue(9, $endNum);
      $stmt->bindValue(10, $endComp);
      $stmt->bindValue(11, $endBairro);
      $stmt->bindValue(12, $telFixo);
      $stmt->bindValue(13, $telCel);
      $stmt->bindValue(14, $emailPessoal);
      $stmt->bindValue(15, $emailInst);
      $stmt->bindValue(16, $status);
      $stmt->bindValue(17, $municipio);
      $stmt->bindValue(18, $_SESSION['zatu_id']);
      $stmt->execute();
      $usuarioIdNew = $db->lastInsertId();
      $id = $usuarioIdNew;
      $msg['id'] = $usuarioIdNew;
    }
    if (sizeof($acoesPost) > 0) {
      foreach ($acoesPost as $kAcao => $vAcao) {
        $stmt = $db->prepare('
          INSERT INTO seg_usuario_acao
          (permissao_seg_usuario_id, seg_acao_id, status, dt_cadastro, seg_usuario_id)
          VALUES (?, ?, ?, NOW(), ?);');
        $stmt->bindValue(1, $id);
        $stmt->bindValue(2, $vAcao);
        $stmt->bindValue(3, 1);
        $stmt->bindValue(4, $_SESSION['zatu_id']);
        $stmt->execute();
      }
    }
    $db->commit();
    $msg['msg'] = 'success';
    $msg['retorno'] = 'Dados do Usuário salvo com sucesso.';
    echo json_encode($msg);
    exit();
  }
} catch (PDOException $e) {
  $db->rollback();
  $msg['msg'] = 'error';
  $msg['retorno'] = "Erro ao tentar salvar os dados do usuário: " . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>