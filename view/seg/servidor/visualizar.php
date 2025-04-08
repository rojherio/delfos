<?php
include_once ('template/topo.php');
include_once ('template/header.php');
include_once ('template/sidebar.php');
$id = !(isset($_POST['id'])) ? 0 : $_POST['id'];
$db = Conexao::getInstance();
$stmt = $db->prepare("
  SELECT 
  u.id, 
  u.nome, 
  u.dt_nascimento, 
  u.sexo, 
  u.login, 
  u.cpf, 
  u.foto, 
  u.end_cep, 
  u.end_logradouro, 
  u.end_numero, 
  u.end_complemento, 
  u.end_bairro, 
  u.bsc_municipio_id, 
  m.nome AS end_municipio_nome, 
  e.sigla AS end_estado_sigla, 
  u.tel_fixo, 
  u.tel_celular, 
  u.email_pessoal, 
  u.email_institucional, 
  u.nivel_acesso, 
  u.status, 
  u.online, 
  u.dt_cadastro 
  FROM seg_usuario AS u 
  LEFT JOIN bsc_municipio AS m ON m.id = u.bsc_municipio_id 
  LEFT JOIN bsc_estado AS e ON e.id = m.bsc_estado_id 
  WHERE u.id = ?;");
$stmt->bindValue(1, $id);
$stmt->execute();
$rsUsuario = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt = $db->prepare("
  SELECT 
  ua.id, 
  ua.permissao_seg_usuario_id, 
  ua.seg_acao_id, 
  a.nome AS acao_nome 
  FROM seg_usuario_acao AS ua
  LEFT JOIN seg_acao AS a ON a.id = ua.seg_acao_id 
  WHERE ua.permissao_seg_usuario_id = ?
  ORDER BY ua.seg_acao_id;");
$stmt->bindValue(1, $rsUsuario['id']);
$stmt->execute();
$rsUsuarioAcoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt = $db->prepare("
  SELECT 
  us.id, 
  us.responsavel_seg_usuario_id, 
  us.eo_setor_id, 
  s.nome AS setor_nome 
  FROM seg_usuario_setor AS us 
  LEFT JOIN eo_setor AS s ON s.id = us.eo_setor_id 
  WHERE us.eo_setor_id
  ORDER BY s.nome ASC;");
$stmt->bindValue(1, $rsUsuario['id']);
$stmt->execute();
$rsUsuarioSetores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <div class="container-full">
    <div class="content-header">
      <div class="d-inline-block align-items-center">
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= PORTAL_URL; ?>dashboard"><i class="fal fa-desktop"></i></a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="<?= PORTAL_URL; ?>view/seg/usuario/dashboard">Usuários</a></li>
            <li class="breadcrumb-item active" aria-current="page">Visualização</li>
          </ol>
        </nav>
      </div>
    </div>
      <div class="box box-solid bg-info">
        <div class="box-header">
          <h4 class="box-title font-weight-bold">
            <div class="d-flex align-items-center justify-content-between">
              <div class="icon rounded-circle font-size-30"><i class="fal fa-user mr-10"></i></div>
              <h4 id="titulo_pagina" class="box-title font-size-16"><strong>USUÁRIOS</strong></h4>
              <input type="hidden" id="titulo_relatorio" value="Relatório de dados do servidor cadastrado no sistema">
            </div>
          </h4>
          <a href="<?= PORTAL_URL; ?>view/seg/usuario/cadastrar" class="waves-effect waves-light btn btn-rounded btn-success mb-5 pull-right d-md-flex d-none mt-3">NOVO USUÁRIO</a>
        </div>
        <div class="box-body">
          <h6 class="box-subtitle ml-2">Copiar, Exportar (CVS, EXCEL, PDF) ou Imprimir a tabela.</h6>
          <div class="table-responsive">
            <table id="tableDashboard" class="table table-hover">
              <thead class="bg-inverse">
                <tr>
                  <th>#</th>
                  <th width="40%">CAMPO</th>
                  <th>CONTEÚDO</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <?php 
                $countRows = 0;
                ?>
                <tr>
                  <td><?= ++$countRows ;?></td>
                  <td>PESSOAL - Nome</td>
                  <td><?= $rsUsuario['nome'] ;?></td>
                  <td></td>
                </tr>
                <tr>
                  <td><?= ++$countRows ;?></td>
                  <td>PESSOAL - Login</td>
                  <td><?= $rsUsuario['nome'] ;?></td>
                  <td></td>
                </tr>
                <tr>
                  <td><?= ++$countRows ;?></td>
                  <td>PESSOAL - Data de nascimento</td>
                  <td><?= data_volta($rsUsuario['dt_nascimento']) ;?></td>
                  <td></td>
                </tr>
                <tr>
                  <td><?= ++$countRows ;?></td>
                  <td>PESSOAL - Sexo</td>
                  <td><?= $rsUsuario['sexo'] == 'M' ? 'Masculino' : 'Feminino'; ?></td>
                  <td></td>
                </tr>
                <tr>
                  <td><?= ++$countRows ;?></td>
                  <td>PESSOAL - CPF</td>
                  <td><?= $rsUsuario['cpf'] ;?></td>
                  <td></td>
                </tr>
                <tr>
                  <td><?= ++$countRows ;?></td>
                  <td>ENDEREÇO - Logradouro</td>
                  <td><?= $rsUsuario['end_logradouro'] ;?></td>
                  <td></td>
                </tr>
                <tr>
                  <td><?= ++$countRows ;?></td>
                  <td>ENDEREÇO - Número</td>
                  <td><?= $rsUsuario['end_numero'] ;?></td>
                  <td></td>
                </tr>
                <tr>
                  <td><?= ++$countRows ;?></td>
                  <td>ENDEREÇO - Complemento</td>
                  <td><?= $rsUsuario['end_complemento'] ;?></td>
                  <td></td>
                </tr>
                <tr>
                  <td><?= ++$countRows ;?></td>
                  <td>ENDEREÇO - Bairro</td>
                  <td><?= $rsUsuario['end_bairro'] ;?></td>
                  <td></td>
                </tr>
                <tr>
                  <td><?= ++$countRows ;?></td>
                  <td>ENDEREÇO - CEP</td>
                  <td><?= $rsUsuario['end_cep'] ;?></td>
                  <td></td>
                </tr>
                <tr>
                  <td><?= ++$countRows ;?></td>
                  <td>ENDEREÇO - Cidade</td>
                  <td><?= $rsUsuario['end_municipio_nome'] . ' - ' . $rsUsuario['end_estado_sigla'] ;?></td>
                  <td></td>
                </tr>
                <tr>
                  <td><?= ++$countRows ;?></td>
                  <td>CONTATO - Telefone residencial</td>
                  <td><?= $rsUsuario['tel_fixo'] ;?></td>
                  <td></td>
                </tr>
                <tr>
                  <td><?= ++$countRows ;?></td>
                  <td>CONTATO - Telefone celular</td>
                  <td><?= $rsUsuario['tel_celular'] ;?></td>
                  <td></td>
                </tr>
                <tr>
                  <td><?= ++$countRows ;?></td>
                  <td>CONTATO - E-mail institucional</td>
                  <td><?= $rsUsuario['email_institucional'] ;?></td>
                  <td></td>
                </tr>
                <tr>
                  <td><?= ++$countRows ;?></td>
                  <td>CONTATO - E-mail pessoal</td>
                  <td><?= $rsUsuario['email_pessoal'] ;?></td>
                  <td></td>
                </tr>
                <tr>
                  <td><?= ++$countRows ;?></td>
                  <td>STATUS</td>
                  <td><?= $rsUsuario['email_pessoal'] == '1' ? 'Ativo' : 'Inativo' ;?></td>
                  <td></td>
                </tr>
                <?php
                  $acoes = '| ';
                  if (is_array($rsUsuarioAcoes)) {
                     foreach ($rsUsuarioAcoes as $kObj => $vObj) {
                       $acoes .= $vObj['acao_nome'] . ' | ';
                     }
                   } 
                ?>
                <tr>
                  <td><?= ++$countRows ;?></td>
                  <td>PERMISSÕES</td>
                  <td><?= $acoes ;?></td>
                  <td></td>
                </tr>
                <?php
                  $setores = '| ';
                  if (is_array($rsUsuarioSetores)) {
                     foreach ($rsUsuarioSetores as $kObj => $vObj) {
                       $setores .= $vObj['setor_nome'] . ' | ';
                     }
                   } 
                ?>
                <tr>
                  <td><?= ++$countRows ;?></td>
                  <td>RESPONSÁVEL PELO SETORES</td>
                  <td><?= $setores ;?></td>
                  <td></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>
    <!-- /.content -->
  </div>
</div>
<?php
include_once ('template/footer.php');
//include_once ('template/control_sidebar.php');
include_once ('template/rodape.php');
?>