<?php
include_once ('template/topo.php');
include_once ('template/header.php');
include_once ('template/sidebar.php');
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
  u.tel_fixo, 
  u.tel_celular, 
  u.email_pessoal, 
  u.email_institucional, 
  u.nivel_acesso, 
  u.status, 
  u.online, 
  u.dt_cadastro 
  FROM seg_usuario AS u 
  WHERE u.id != 1 
  ORDER BY u.nome ASC;");
$stmt->execute();
$rsUsuario = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <div class="container-full">
    <div class="content-header">
      <div class="d-inline-block align-items-center">
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= PORTAL_URL; ?>dashboard"><i class="fal fa-desktop"></i></a></li>
            <li class="breadcrumb-item active" aria-current="page">Usuários</li>
          </ol>
        </nav>
      </div>
    </div>
    <section class="content">
      <div class="box box-solid bg-info">
        <div class="box-header">
          <h4 class="box-title font-weight-bold">
            <div class="d-flex align-items-center justify-content-between">
              <div class="icon rounded-circle font-size-30"><i class="fal fa-user mr-10"></i></div>
              <h4 id="titulo_pagina" class="box-title font-size-16"><strong>USUÁRIOS</strong></h4>
              <input type="hidden" id="titulo_relatorio" value="Relatório de usuários cadastrados no sistema">
            </div>
          </h4>
          <a href="<?= PORTAL_URL; ?>view/seg/usuario/cadastrar" class="waves-effect waves-light btn btn-rounded btn-success mb-5 pull-right d-md-flex d-none mt-3">NOVO USUÁRIO</a>
        </div>
        <div class="box-body">
          <h6 class="box-subtitle ml-2">Copiar, Exportar (CVS, EXCEL, PDF) ou Imprimir a tabela.</h6>
          <div class="table-responsive">
            <table id="table_modelo_01" class="table table-hover">
              <thead class="bg-inverse">
                <tr>
                  <th>#</th>
                  <th>Nome</th>
                  <th>Login</th>
                  <th>CPF</th>
                  <th>Nascimento</th>
                  <th>Contatos</th>
                  <th>E-mails</th>
                  <th>Status</th>
                  <th>Online</th>
                  <th class="no-print" width="200px !important"></th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($rsUsuario as $kObj => $vObj) {
                  $contatos = $vObj['tel_fixo'] != '' && $vObj['tel_celular'] != '' ? $vObj['tel_fixo'].'<br/>'.$vObj['tel_celular'] : ($vObj['tel_fixo'] != '' ? $vObj['tel_fixo'] : $vObj['tel_celular']);
                  $emails = $vObj['email_institucional'] != '' && $vObj['email_pessoal'] != '' ? $vObj['email_institucional'].'<br/>'.$vObj['email_pessoal'] : ($vObj['email_institucional'] != '' ? $vObj['email_institucional'] : $vObj['email_pessoal']);
                  $btnAtivar = $vObj['status'] == 1 ? 'style="display: none;"' : 'style="display: all;"';
                  $btnInativar = $vObj['status'] == 0 ? 'style="display: none;"' : 'style="display: all;"';
                  ?>
                  <tr>
                    <input type="hidden" id="td_id" value="<?= $vObj['id']; ?>">
                    <td><?= $kObj+1; ?></td>
                    <td><?= $vObj['nome']; ?></td>
                    <td><?= $vObj['login']; ?></td>
                    <td><?= $vObj['cpf']; ?></td>
                    <td><?= data_volta($vObj['dt_nascimento']); ?></td>
                    <td><?= $contatos; ?></td>
                    <td><?= $emails; ?></td>
                    <td id="td_status" value="<?= $vObj['status'];?>"><?= $vObj['status'] == 1 ? 'Ativo' : 'Inativo'; ?></td>
                    <td><?= $vObj['online'] == 1 ? 'Online' : 'Offline'; ?></td>
                    <td>
                      <button id="btn_visualizar_registro" class="btn_visualizar_registro waves-effect waves-light btn btn-info btn-rounded" onclick="btnVisualizar(this);">
                        <i class="fa fa-file-text"></i> Ver
                      </button>
                      <button id="btn_editar_registro" class="btn_editar_registro waves-effect waves-light btn btn-warning btn-rounded" onclick="btnEditar(this);">
                        <i class="fa fa-edit"></i> Editar
                      </button>
                      <button id="btn_inativar_registro" class="btn_inativar_registro waves-effect waves-light btn btn-danger btn-rounded" <?= $btnInativar; ?> onclick="btnInativar(this);">
                        <i class="fa fa-minus-square"></i> Inativar
                      </button>
                      <button id="btn_ativar_registro" class="btn_ativar_registro waves-effect waves-light btn btn-success btn-rounded" <?= $btnAtivar; ?> onclick="btnAtivar(this);">
                        <i class="fa fa-check-square"></i> Ativar
                      </button>
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
include_once ('template/footer.php');
//include_once ('template/control_sidebar.php');
include_once ('template/rodape.php');
?>
<script type="text/javascript" src="<?= PORTAL_URL; ?>control/seg/usuario/dashboard.js"></script>