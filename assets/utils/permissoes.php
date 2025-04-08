<?php
$paginasId = array(
  '//' => 0,
  'rh/atestacao/dashboard' => 0,
  'rh/servidor_atualizacao/dashboard' => 0,
  'rh/servidor_atualizacao/atestacao' => 0,
  'rh/servidor_atualizacao/conferencia' => 0,
  'seg/usuario/foto' => 0,
  'rh/servidor_atualizacao/visualizar' => 3,
  'rh/servidor/cadastrar' => 1, //'Servidores - Cadastrar',
  'rh/servidor/dashboard' => 2, //'Servidores - Listar',
  'rh/servidor/visualizar' => 3, //'Servidores - Visualizar',
  'rh/servidor/acesso' => 16, //'Servidores - Servidor - Reseta senha/e-mail',
  //4 = 'Servidores - Excluir',
  //5 = 'Estrutura Organizacional - Cadastrar',
  'bsc/unidade_organizacional/dashboard' => 6, //'Estrutura Organizacional - Listar',
  'bsc/unidade_organizacional_tipo/dashboard' => 6, //'Estrutura Organizacional - Listar',
  'eo/cargo/dashboard' => 6, //'Estrutura Organizacional - Listar',
  'eo/empregador/dashboard' => 6, //'Estrutura Organizacional - Listar',
  'eo/setor/dashboard' => 6, //'Estrutura Organizacional - Listar',
  //7 = 'Estrutura Organizacional - Excluir',
  //8 = 'Recursos Humanos - Cadastrar',
  'rh/atestador/dashboard' => 9, //'Recursos Humanos - Listar',
  'rh/conferidor/dashboard' => 9, //'Recursos Humanos - Listar',
  'rh/servidor_tipo/dashboard' => 9, //'Recursos Humanos - Listar',
  //10 = 'Recursos Humanos - Excluir',
  'seg/usuario/cadastrar' => 11, //'Usuários - Cadastrar',
  'seg/usuario/dashboard' => 12, //'Usuários - Listar',
  'seg/usuario/visualizar' => 12, //'Usuários - Visualizar',
  //13 = 'Usuários - Ativa/Inativa',
  'relatorio/rh/atestacoes' => 14, //'Relatórios'
  'relatorio/rh/atestador' => 14, //'Relatórios'
  'relatorio/rh/servidor' => 14, //'Relatórios'
  'relatorio/rh/servidor_sem_atualizacao' => 14, //'Relatórios'
  'relatorio/rh/validador' => 14, //'Relatórios'
  'relatorio/sacad/servidor_atualizacao' => 14, //'Relatórios'
  'relatorio/seg/usuario' => 14 //'Relatórios'
  //15 = 'Supervisor',
  //16 = 'Servidor - Reseta E-mail/Senha',
);
//FUNÇÃO PARA VERIFICAR A PERMISSÃO NO MÓDULO
function permissaoPagina($paginaId) {
  @session_start();
  if ($paginaId > 0) {
    $db = Conexao::getInstance();
    $stmt = $db->prepare("
      SELECT 
      ua.id 
      FROM seg_usuario_acao AS ua 
      WHERE ua.permissao_seg_usuario_id = ? 
      AND (
        ua.seg_acao_id = ?
        OR ua.seg_acao_id = ?
      );"
    );
    $stmt->bindValue(1, $_SESSION['zatu_id']);
    $stmt->bindValue(2, $paginaId);
    $stmt->bindValue(3, 15);
    $stmt->execute();
    $rsUsuarioAcoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(sizeof($rsUsuarioAcoes) > 0 || (in_array($paginaId, [11, 12]) && $_SESSION['zatu_id'] == 1)) {
      return true;
    } else {
      echo "
      <script 'text/javascript'>
        alert('O seu usuário não tem permissão para acessar esta página!');
        //swal.fire('Atenção', 'O seu usuário não tem permissão para acessar esta página!', 'warning');
        window.location = '" . PORTAL_URL . "dashboard';
      </script>
      ";
    }
  } else {
    return true;
  }
}
?>