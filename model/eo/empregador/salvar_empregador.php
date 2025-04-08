<?php
$db                   = Conexao::getInstance();
$id                   = strip_tags(@$_POST['id']);
$nomeRazaoSocial      = strip_tags(@$_POST['nome_razao_e']);
$nomeFantasia         = strip_tags(@$_POST['nome_fantasia_e']);
$cnpj                 = strip_tags(@$_POST['cnpj_e']);
$ie                   = strip_tags(@$_POST['ie_e']);
$telResidencial       = strip_tags(@$_POST['tel_res_e']);
$telCelular           = strip_tags(@$_POST['tel_cel_e']);
$telRecadoNumero      = strip_tags(@$_POST['tel_recado_e']);
$relRecadoNome        = strip_tags(@$_POST['tel_recado_nome_e']);
$endLogradouro        = strip_tags(@$_POST['end_log_e']);
$endNumero            = strip_tags(@$_POST['end_num_e']);
$endComplemento       = strip_tags(@$_POST['end_comp_e']);
$endBairro            = strip_tags(@$_POST['end_bairro_e']);
$endCep               = strip_tags(@$_POST['end_cep_e']);
$endCidade            = strip_tags(@$_POST['cidade_e']);
$status               = strip_tags(@$_POST['status_e']) == "on" ? 1 : 0;
$error = false;
$msg = array();
$mensagem = "";
try {
  $db->beginTransaction();
  //VERIFICA SE O NOME DO PROJETO JÁ FOI INFORMADO
  // $id_nome = pesquisar("id", "bsc_unidade_organizacional_tipo", "nome", "LIKE", $nome, "");
  $stmt = $db->prepare('SELECT id FROM eo_empregador WHERE nome_razao_social LIKE ? OR cnpj LIKE ?;');
  $stmt->bindValue(1, $nomeRazaoSocial);
  $stmt->bindValue(2, $cnpj);
  $stmt->execute();
  $rs = $stmt->fetch(PDO::FETCH_ASSOC);
  $id_pesquisa = $rs['id'];
  if (!is_null($id_pesquisa) && $id_pesquisa != $id) {
    $error = true;
    $mensagem .= "O nome/razão social ou o cnpj do Empregador informado já existe no sistema.";
    $msg['tipo'] = "nome";
  }
  if ($error == false) {
    if ($id != "" && $id != 0) {
      $stmt = $db->prepare('
        UPDATE eo_empregador 
        SET 
        nome_razao_social = ?, 
        nome_fantasia = ?, 
        cnpj = ?, 
        ie = ?, 
        end_cep = ?, 
        end_logradouro = ?, 
        end_numero = ?, 
        end_complemento = ?, 
        end_bairro = ?, 
        end_bsc_municipio_id = ?, 
        tel_residencial = ?, 
        tel_celular = ?, 
        tel_recado = ?, 
        tel_recado_nome = ?, 
        status = ?, 
        dt_cadastro = NOW(), 
        seg_usuario_id = ? 
        WHERE id = ?;');
      $stmt->bindValue(1, $nomeRazaoSocial);
      $stmt->bindValue(2, $nomeFantasia);
      $stmt->bindValue(3, $cnpj);
      $stmt->bindValue(4, $ie);
      $stmt->bindValue(5, $endCep);
      $stmt->bindValue(6, $endLogradouro);
      $stmt->bindValue(7, $endNumero);
      $stmt->bindValue(8, $endComplemento);
      $stmt->bindValue(9, $endBairro);
      $stmt->bindValue(10, $endCidade);
      $stmt->bindValue(11, $telResidencial);
      $stmt->bindValue(12, $telCelular);
      $stmt->bindValue(13, $telRecadoNumero);
      $stmt->bindValue(14, $relRecadoNome);
      $stmt->bindValue(15, $status);
      $stmt->bindValue(16, $_SESSION['zatu_id']);
      $stmt->bindValue(17, $id);
      $stmt->execute();
      $db->commit();
      //MENSAGEM DE SUCESSO
      $msg['id'] = $id;
      $msg['msg'] = 'success';
      $msg['retorno'] = 'Empregador atualizado com sucesso.';
      echo json_encode($msg);
      exit();
    } else {
      $stmt = $db->prepare('
        INSERT INTO eo_empregador 
        (nome_razao_social, 
        nome_fantasia, 
        cnpj, 
        ie, 
        end_cep, 
        end_logradouro, 
        end_numero, 
        end_complemento, 
        end_bairro, 
        end_bsc_municipio_id, 
        tel_residencial, 
        tel_celular, 
        tel_recado, 
        tel_recado_nome,  
        status, 
        dt_cadastro, 
        seg_usuario_id) 
        VALUES 
        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?);');
      $stmt->bindValue(1, $nomeRazaoSocial);
      $stmt->bindValue(2, $nomeFantasia);
      $stmt->bindValue(3, $cnpj);
      $stmt->bindValue(4, $ie);
      $stmt->bindValue(5, $endCep);
      $stmt->bindValue(6, $endLogradouro);
      $stmt->bindValue(7, $endNumero);
      $stmt->bindValue(8, $endComplemento);
      $stmt->bindValue(9, $endBairro);
      $stmt->bindValue(10, $endCidade);
      $stmt->bindValue(11, $telResidencial);
      $stmt->bindValue(12, $telCelular);
      $stmt->bindValue(13, $telRecadoNumero);
      $stmt->bindValue(14, $relRecadoNome);
      $stmt->bindValue(15, $status);
      $stmt->bindValue(16, $_SESSION['zatu_id']);
      $stmt->execute();
      $empregadorId = $db->lastInsertId();
      $db->commit();
      //MENSAGEM DE SUCESSO
      $msg['id'] = $empregadorId;
      $msg['msg'] = 'success';
      $msg['retorno'] = 'Empregador cadastrado com sucesso.';
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
  $msg['retorno'] = "Erro ao tentar salvar os dados do Empregador: " . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>