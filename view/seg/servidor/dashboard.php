<!-- <?php
include_once ('template/servidor_topo.php');
include_once ('template/servidor_header.php');
include_once ('template/servidor_sidebar.php');
$db = Conexao::getInstance();
$stmt = $db->prepare("
  SELECT 
  s.id, 
  s.nome, 
  s.dt_nascimento, 
  s.sexo, 
  s.login, 
  s.senha, 
  s.cpf, 
  s.matricula, 
  s.foto, 
  s.tel_celular, 
  s.email, 
  s.nivel_acesso, 
  s.status, 
  s.online, 
  s.dt_cadastro, 
  s.bsc_municipio_id, 
  s.seg_usuario_id, 
  s.eo_setor_unidade_organizacional_id, 
  st.id AS setor_id, 
  st.nome AS setor_nome, 
  uo.id AS uo_id, 
  uo.nome AS uo_nome 
  FROM seg_servidor s 
  LEFT JOIN eo_setor_unidade_organizacional AS suo ON suo.id = s.eo_setor_unidade_organizacional_id 
  LEFT JOIN eo_setor AS st ON st.id = suo.eo_setor_id 
  LEFT JOIN bsc_unidade_organizacional AS uo ON uo.id = suo.bsc_unidade_organizacional_id 
  WHERE s.id = ?
  ORDER BY s.nome ASC;");
$stmt->bindValue(1, $_SESSION['servidor_zatu_id']);
$stmt->execute();
$rsSegServidor = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt = $db->prepare("SELECT 
  ssa.id, 
  ssa.obs, 
  sa.nome AS situacao_atestacao_nome, 
  ssa.status, 
  ssa.dt_cadastro, 
  ssa.seg_usuario_id, 
  ssa.seg_servidor_id, 
  ssa.rh_situacao_atestacao_id 
  FROM seg_servidor_situacao_atestacao AS ssa 
  LEFT JOIN rh_situacao_atestacao AS sa ON sa.id = ssa.rh_situacao_atestacao_id 
  WHERE ssa.seg_servidor_id = ? 
  ORDER BY ssa.id ASC;");
$stmt->bindValue(1, $rsSegServidor['id']);
$stmt->execute();
$rsSegServidorSituacao = $stmt->fetchAll(PDO::FETCH_ASSOC);
$rsSegServidor['situacoes'] = $rsSegServidorSituacao;
$rsSegServidor['situacaoServidorPrimeiro'] = $rsSegServidorSituacao[0];
$rsSegServidor['situacaoServidorUltima'] = end($rsSegServidorSituacao);
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <div class="container-full">
    <div class="content-header">
      <div class="d-inline-block align-items-center">
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= PORTAL_URL; ?>dashboard"><i class="fal fa-desktop"></i></a></li>
            <li class="breadcrumb-item active" aria-current="page">Atestação</li>
          </ol>
        </nav>
      </div>
    </div>
    <!-- Main content -->
    <section class="content">
      <div class="">
      <div class="row mt-20">
        <div class="col-md-12">
          <div class="box box-outline-info">
            <div class="box-header">
              <h4 id="titulo_pagina" class="box-title font-size-16"><strong>ATESTAÇÃO</strong></h4>
              <input type="hidden" id="titulo_relatorio" value="Relatório de dados do servidor cadastrado no sistema">
            </div>
            <div class="box-body">
          <div class="table-responsive">
            <table id="tableDashboard" class="table table-hover" class="">
              <thead>
                <tr>
                  <th>#</th>
                  <th>SERVIDOR</th>
                  <th>STATUS</th>
                  <th>DATA</th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($rsSegServidorSituacao as $kObj => $vObj) {
                  ?>
                  <tr>
                    <input type="hidden" id="td_id" value="<?= $vObj['id']; ?>">
                    <td><?= $kObj+1; ?></td>
                    <td>
                      <strong><?= $rsSegServidor['nome']; ?></strong><br/>
                    </td>
                    <td>
                      <strong><?= $vObj['situacao_atestacao_nome']; ?></strong><br/>
                      Etapa: <span class="badge badge-success"><?= 'RH'; ?></span><br/>
                    </td>
                    <td>
                      Alteração: <strong><?= obterDataBRTimestamp($vObj['dt_cadastro']); ?></strong>
                    </td>
                  </tr>
                  <?php
                }
                ?>
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
include_once ('template/servidor_footer.php');
include_once ('template/servidor_control_sidebar.php');
include_once ('template/servidor_rodape.php');
?>
<script type="text/javascript" src="<?= PORTAL_URL; ?>control/seg/usuario/dashboard.js"></script> -->