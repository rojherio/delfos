<?php
$db = Conexao::getInstance();
$nome = strip_tags(@$_POST['nome']);
$msg = array();
$mensagem = "";
try {
  $msg['itens'] = array();
  // if ($id != '' && $id != null) {
  $stmt = $db->prepare('SELECT m.id, m.nome, e.sigla AS estado_sigla 
    FROM bsc_municipio AS m 
    LEFT JOIN bsc_estado AS e ON e.id = m.bsc_estado_id 
    LEFT JOIN bsc_pais AS p ON p.id = e.bsc_pais_id 
    WHERE CONCAT(m.nome, " - ", e.sigla) LIKE ?
    ORDER BY e.sigla, m.nome;');
  $stmt->bindValue(1, ($nome.'%'));
  $stmt->execute();
  $rsMunicipios = $stmt->fetchAll(PDO::FETCH_ASSOC);
  if (count($rsMunicipios) > 0 ) {
    foreach ($rsMunicipios as $kObj => $vObj) {
      array_push($msg['itens'], array('id'=> $vObj['id'], 'text'=> ($vObj['nome'].' - '.$vObj['estado_sigla'])));
    }
  } else {
    array_push($msg['itens'], array('id'=> 0, 'text'=> 'Nenhum Município encontrado para a busca efetuada'));
  }
  $msg['msg'] = 'success';
  echo json_encode($msg);
  exit();
} catch (PDOException $e) {
  $msg['msg'] = 'error';
  $msg['retorno'] = "Erro ao tentar buscar os Municípios: " . $e->getMessage();
  echo json_encode($msg);
  exit();
}
?>