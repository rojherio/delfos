<?php
$db                     = Conexao::getInstance();
$nome                   = strip_tags(@$_POST['nome']);
$cpf                    = trim(strip_tags(@$_POST['cpf']));
$matricula              = trim(strip_tags(@$_POST['matricula']));
// $maeNome                = strip_tags(@$_POST['mae_mome']);
$email                  = trim(strip_tags(@$_POST['email']));
$senha                  = trim(strip_tags(@$_POST['senha']));
$error                  = false;
$msg                    = array();
$mensagem               = "";

$nome                   = str_replace(' ', '', $nome);
// $maeNome                = str_replace(' ', '', $maeNome);

try {
  $db->beginTransaction();
  //VERIFICA SE O NOME DO PROJETO JÁ FOI INFORMADO
  // $id_nome = pesquisar("id", "bsc_unidade_organizacional_tipo", "nome", "LIKE", $nome, "");
  $stmt = $db->prepare('
    SELECT id, REPLACE(UPPER(nome), " ", "") as nome, cpf, matricula, matricula_2, replace(UPPER(mae_nome), " ", "") as mae_nome, email, senha, status 
    FROM seg_servidor 
    WHERE REPLACE((UPPER(nome)), " ", "") LIKE ? 
    AND cpf LIKE ? 
    AND (matricula LIKE ? 
      OR matricula_2 LIKE ?);');
    // AND (matricula LIKE ? 
    //   OR matricula_2 LIKE ?) 
    // AND REPLACE((UPPER(mae_nome)), " ", "") LIKE ?;');
  $stmt->bindValue(1, strtoupper($nome));
  $stmt->bindValue(2, $cpf);
  $stmt->bindValue(3, $matricula);
  $stmt->bindValue(4, $matricula);
  // $stmt->bindValue(5, strtoupper($maeNome));
  $stmt->execute();
  $rsPesquisa = $stmt->fetch(PDO::FETCH_ASSOC);
  $stmt = $db->prepare('
    SELECT id, email, status 
    FROM seg_servidor 
    WHERE email LIKE ? ;');
  $stmt->bindValue(1, $email);
  $stmt->execute();
  $rsEmails = $stmt->fetchAll(PDO::FETCH_ASSOC);
  if (!is_null($rsPesquisa['id'])) {
    $mensagem = array();
    if(sizeof($rsEmails) > 1) {
      $error = true;
      $msg['tipo'] = "email";
      $mensagem['email'] = 'O e-mail informado não pode mais ser usado pois já está em uso no sistema.';
    } else if(sizeof($rsEmails) == 1) {
      if ($rsEmails[0]['id'] != $rsPesquisa['id']) {
        $error = true;
        $msg['tipo'] = "email";
        $mensagem['email'] = 'O e-mail informado não pode mais ser usado pois já está em uso no sistema.';
      }
    } else {
      if ($rsPesquisa['status'] > 0) {
        $error = true;
        $msg['tipo'] = "status";
        $mensagem['status'] = 'O seu registro de novo servidor já foi criado. Não é possível criar um novo registro. Efetue o acesso ao sistema com seu CPF e senha.';
      } else {
        $nomeUpper = str_replace(' ', '', strtoupper(retira_acentos($nome)));
        if (str_replace(' ', '', retira_acentos($rsPesquisa['nome'])) != $nomeUpper) {
          $error = true;
          $msg['tipo'] = "nome";
          $mensagem['nome'] = 'O nome do servidor informado não foi encontrado. ';
        }
        if ($rsPesquisa['cpf'] != $cpf) {
          $error = true;
          $msg['tipo'] = "nome";
          $mensagem['cpf'] = 'O cpf do servidor informado não foi encontrado. ';
        } 
        if ($rsPesquisa['matricula'] != $matricula && $rsPesquisa['matricula_2'] != $matricula) {
          $error = true;
          $msg['tipo'] = "nome";
          $mensagem['matricula'] = 'A matricula do servidor informado não foi encontrada. ';
        }
        // $nomeUpper = str_replace(' ', '', strtoupper(retira_acentos($maeNome)));
        // if (str_replace(' ', '', retira_acentos($rsPesquisa['mae_nome'])) != $nomeUpper) {
        //   $error = true;
        //   $msg['tipo'] = "nome";
        //   $mensagem['mae_nome'] = 'O nome da mãe do servidor informado não foi encontrado. ';
        // }
      }
    }
    if ($error == false) {
      $stmt = $db->prepare('
        UPDATE seg_servidor  
        SET
        email = ?, 
        senha = ?, 
        status = ?, 
        dt_cadastro = NOW() 
        WHERE id = ?;');
      $stmt->bindValue(1, $email);
      $stmt->bindValue(2, SHA1($senha));
      $stmt->bindValue(3, 1); //1 Ativo, 0 Inativo
      $stmt->bindValue(4, $rsPesquisa['id']); //1 Ativo, 0 Inativo
      $stmt->execute();
      $id = $rsPesquisa['id'];
      $msg['id'] = $id;
      $db->commit();
      $msg['msg'] = 'success';
      $msg['retorno'] = 'Dados do servidor salvos com sucesso.';
      echo json_encode($msg);
      exit();
    } else {
      $db->rollback();
      $msg['msg'] = 'error';
      $msg['retorno'] = $mensagem;
      echo json_encode($msg);
      exit();
    }
  } else {
    $db->rollback();
    $msg['tipo'] = 'registro';
    $msg['msg'] = 'error';
    $msg['retorno'] = 'Servidor não encontrado na base de dados';
    echo json_encode($msg);
    exit();
  }
} catch (PDOException $e) {
  $db->rollback();
  $msg['msg'] = 'error';
  $msg['retorno'] = "Erro ao tentar salvar os dados do servidor: " . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>