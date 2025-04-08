<?php 
//time duration session
function sessionOn(){
  //VERIFICAÇÃO DE SESSION COM TIMEOUT PARA EXPIRAR
  // 30 minutos em segundos
  $inactive_session = 1800;
  $urlanterior = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
  if( isset($_SESSION['zatu_id']) ){
    $_SESSION['timeout'] = $_SESSION['timeout'] != null || $_SESSION['timeout'] != '' ? $_SESSION['timeout'] : 0;
    $session_life = time() - $_SESSION['timeout'] ;
    if( $session_life > $inactive_session ){
      if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) ){
        echo "logout";
        exit();
      } else {
          ?>
        <script type="text/javascript"> window.location.href = '<?= PORTAL_URL ;?>logout';</script>
        <?php
      }
    } else {
      $_SESSION['timeout'] = time();
    }
  } else if( isset($_SESSION['servidor_zatu_id']) ){
    $_SESSION['timeout'] = $_SESSION['timeout'] != null || $_SESSION['timeout'] != '' ? $_SESSION['timeout'] : 0;
    $session_life = time() - $_SESSION['timeout'] ;
    if( $session_life > $inactive_session ){
      if( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ) ){
        echo "logout";
        exit();
      } else {
          ?>
        <script type="text/javascript"> window.location.href = '<?= PORTAL_URL ;?>servidor_logout';</script>
        <?php
      }
    } else {
      $_SESSION['timeout'] = time();
    }
  }
}//end function
function busca_usuario_session() {
  global $db;
  @session_start();
  if(isset($_SESSION['zatu_id'])) {
    verifica_existe_sessao(); // INSERI NA SESSÃO A DATA E HORA DA ÚLTIMA AÇÃO DO USUÁRIO
  } else if(Url::getURL(0) != 'login' && Url::getURL(0) != 'login.php' && Url::getURL(0) != 'autenticar') {
    ?>
    <script type="text/javascript"> window.location.href = '<?= PORTAL_URL ;?>logout';</script>
    <?php
    exit();
  }
}
function verifica_existe_sessao() {
  global $db;
  @session_start();
  if(Url::getURL(4) != 'css' && Url::getURL(3) != 'media' && Url::getURL(4) != 'js') {
    $rs = $db->prepare("SELECT seg_usuario_id 
      FROM seg_sessao 
      WHERE seg_usuario_id = ?");
    $rs->bindValue(1, $_SESSION['zatu_id']);
    $rs->execute();
    $usuario = $rs->fetch(PDO::FETCH_ASSOC);
    if(isset($usuario)) {
      atualiza_sessao();
    } else {
      cria_sessao();
    }
  }
}
function cria_sessao() {
  global $db;
  @session_start();
  if(Url::getURL(4) != 'css' && Url::getURL(3) != 'media' && Url::getURL(4) != 'js') {
    $rs = $db->prepare("INSERT INTO seg_sessao 
      (dt_login, dt_atualizacao, seg_usuario_pai_id, seg_usuario_id)
      VALUES (NOW(), NOW(), ?, ?)");
    $rs->bindValue(1, $_SESSION['zatu_id']);
    $rs->bindValue(2, $_SESSION['zatu_id']);
    $rs->execute();
  }
}
function atualiza_sessao() {
  global $db;
  @session_start();
  if(Url::getURL(4) != 'css' && Url::getURL(3) != 'media' && Url::getURL(4) != 'js') {
    $rs = $db->prepare("UPDATE seg_sessao 
      SET dt_atualizacao = NOW(), pagina = ? 
      WHERE seg_usuario_id = ?");
    $rs->bindValue(1, Url::getURL(3) . "/" . Url::getURL(4));
    $rs->bindValue(2, $_SESSION['zatu_id']);
    $rs->execute();
  }
}
function info_usuario($usuario_id) {
  $rs = $db->prepare("SELECT u.sexo_id, u.nome 
    FROM seg_usuario AS u
    WHERE u.id = ?");
  $rs->bindValue(1, $usuario_id);
  $rs->execute();
  $usuario = $rs->fetch(PDO::FETCH_ASSOC);
  return $usuario['sexo_id'] == "M" ? "O usuário " . $usuario['nome'] . " (" . $usuario['sigla'] . ")" : "A usuária " . $usuario['nome'];
}
function vf_usuario_pagina($pagina) {
  $vf = 0;
  @session_start();
  $rs = $db->prepare("SELECT seg_usuario_id 
    FROM seg_sessao 
    WHERE seg_usuario_id <> ? AND pagina = ? 
    ORDER BY dt_atualizacao DESC");
  $rs->bindValue(1, $_SESSION['zatu_id']);
  $rs->bindValue(2, $pagina);
  $rs->execute();
  while($sessao = $rs->fetch(PDO::FETCH_ASSOC)) {
    if(vf_usuario_on($sessao['seg_usuario_id'])) {
      $vf = $sessao['seg_usuario_id'];
    }
  }
  return $vf;
}
function vf_usuario_on($id) {
  $result = $db->prepare("SELECT id, online, status 
    FROM seg_usuario 
    WHERE id = ?");
  $result->bindValue(1, $id);
  $result->execute();
  while($usuario = $result->fetch(PDO::FETCH_ASSOC)) {
    if($usuario['online'] == 1 && vf_online($usuario['id']))
      return true;
    if($usuario['status'] == 0)
      return false;
  }
}
// SE O USUÁRIO NÃO REALIZAR NENHUMA AÇÃO EM 30 MINUTOS, ENTÃO ELE É CONSIDERADO COMO OFFLINE
function vf_online($id) {
  $rs = $db->prepare(" SELECT id
   FROM seg_sessao
   WHERE seg_usuario_id = ? AND DATE(dt_atualizacao) = DATE(NOW()) AND HOUR(dt_atualizacao) = HOUR(NOW())
                       AND MINUTE(dt_atualizacao) >= (MINUTE(NOW())-30)"); // 30 MINUTOS PASSADO COMO PARÂMETRO
  $rs->bindValue(1, $id);
  $rs->execute();
  if(is_numeric($rs->rowCount()) && $rs->rowCount() > 0) {
    return true;
  } else {
    return false;
  }
}
function busca_servidor_session() {
  global $db;
  if(isset($_SESSION['servidor_zatu_id']) && Url::getURL(1) != 'zatu') {
    verifica_existe_sessao_servidor(); // INSERI NA SESSÃO A DATA E HORA DA ÚLTIMA AÇÃO DO USUÁRIO
  } else if(Url::getURL(0) != 'servidor_login' && Url::getURL(0) != 'servidor_login.php' && Url::getURL(0) != 'servidor_autenticar' && Url::getURL(1) != 'zatu') {
    ?>
    <script type="text/javascript"> window.location.href = '<?= PORTAL_URL ;?>servidor_logout';</script>
    <?php
    exit();
  }
}
function verifica_existe_sessao_servidor() {
  global $db;
  @session_start();
  if(Url::getURL(4) != 'css' && Url::getURL(3) != 'media' && Url::getURL(4) != 'js') {
    $rs = $db->prepare("SELECT seg_servidor_id 
      FROM seg_servidor_sessao 
      WHERE seg_servidor_id = ?");
    $rs->bindValue(1, $_SESSION['servidor_zatu_id']);
    $rs->execute();
    $usuario = $rs->fetch(PDO::FETCH_ASSOC);
    if(isset($usuario)) {
      atualiza_sessao_servidor();
    } else {
      cria_sessao_servidor();
    }
  }
}
function cria_sessao_servidor() {
  global $db;
  @session_start();
  if(Url::getURL(4) != 'css' && Url::getURL(3) != 'media' && Url::getURL(4) != 'js') {
    $rs = $db->prepare("INSERT INTO seg_servidor_sessao 
      (dt_login, dt_atualizacao, seg_servidor_id)
      VALUES (NOW(), NOW(), ?)");
    $rs->bindValue(1, $_SESSION['servidor_zatu_id']);
    $rs->execute();
  }
}
function atualiza_sessao_servidor() {
  global $db;
  @session_start();
  if(Url::getURL(4) != 'css' && Url::getURL(3) != 'media' && Url::getURL(4) != 'js') {
    $rs = $db->prepare("UPDATE seg_servidor_sessao 
      SET dt_atualizacao = NOW(), pagina = ? 
      WHERE seg_servidor_id = ?");
    $rs->bindValue(1, Url::getURL(3) . "/" . Url::getURL(4));
    $rs->bindValue(2, $_SESSION['servidor_zatu_id']);
    $rs->execute();
  }
}
?>