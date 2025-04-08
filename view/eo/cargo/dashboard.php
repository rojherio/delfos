<?php
include_once ('template/topo.php');
include_once ('template/header.php');
include_once ('template/sidebar.php');
$db = Conexao::getInstance();
$stmt = $db->prepare("
  SELECT 
  c.id AS id, 
  c.nome AS nome, 
  c.status AS status, 
  sc.eo_cargo_id AS sc_id_cargo 
  FROM eo_cargo AS c 
  LEFT JOIN rh_servidor_contrato AS sc ON c.id = sc.eo_cargo_id 
  GROUP BY c.id 
  ORDER BY c.nome ASC");
$stmt->execute();
$rsCargo = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <div class="container-full">
    <div class="content-header">
      <div class="d-inline-block align-items-center">
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="<?= PORTAL_URL; ?>dashboard"><i class="fal fa-desktop"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">Cargos/Funções</li>
          </ol>
        </nav>
      </div>
    </div>
    <!-- Main content -->
    <section class="content">
      <div class="box box-solid bg-info">
        <div class="box-header">
          <h4 class="box-title font-weight-bold">
            <div class="d-flex align-items-center justify-content-between">
              <div class="icon rounded-circle font-size-30"><i class="fal fa-id-card mr-10"></i></div>
              <span id="titulo_form"> NOVO CARGO/FUNÇÃO</span>
            </div>
          </h4>
          <!-- <a href="#" class="waves-effect waves-light btn btn-rounded btn-success mb-5 pull-right d-md-flex d-none">NOVO USUÁRIO</a> -->
        </div>
        <div class="box-body">
          <form class="" id="form_cargo" name="form_cargo" method="post" action="">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="nome_cargo">Nome<span class="text-danger">*</span>: </label>
                  <div class="input-group mb-3 controls">
                    <input type="text" class="form-control" minlength="3" id="nome_cargo" name="nome_cargo" placeholder="Nome do Cargo" value="" required/>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <br>
                  <div class="checkbox checkbox-success">
                    <input type="checkbox" class="filled-in chk-col-primary" id="status_cargo" name="status_cargo" checked="true">
                    <label for="status_cargo">Ativo</label>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer text-center">
              <button type="reset" id="btn_cancelar" class="btn btn-rounded btn-danger mr-1">
                <i class="ti-trash"></i> Cancelar
              </button>
              <button type="submit" class="btn btn-rounded btn-success">
                <i class="ti-save-alt"></i><span id="btn_submit"> Cadastrar</span>
              </button>
            </div>
            <input type="hidden" id="id" name="id" value="">
          </form>
        </div>
      </div>
      <div class="box box-solid bg-info">
        <div class="box-header">
          <h4 class="box-title font-weight-bold">
            <div class="d-flex align-items-center justify-content-between">
              <div class="icon rounded-circle font-size-30"><i class="fal fa-id-card mr-10"></i></div>
              <span id="titulo_form"> LISTA DE CARGOS/FUNÇÕES</span>
              <input type="hidden" id="titulo_relatorio" value="Relatório de cargos/funções cadastrados no sistema">
            </div>
          </h4>
          <!-- <a href="#" class="waves-effect waves-light btn btn-rounded btn-success mb-5 pull-right d-md-flex d-none">NOVO USUÁRIO</a> -->
        </div>
        <div class="box-body">
          <h6 class="box-subtitle ml-2">Copiar, Exportar (CVS, EXCEL, PDF) ou Imprimir a tabela.</h6>
          <div class="table-responsive">
            <table id="table_modelo_01" class="table table-hover">
              <thead class="bg-inverse">
                <tr>
                  <th>#</th>
                  <th>Nome</th>
                  <th>Status</th>
                  <th class="no-print" width="160px !important"></th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($rsCargo as $kObj => $vObj) {
                  $excluirDisable = !is_null($vObj['sc_id_cargo']) ? 'negado="true" data-toggle="tooltip" title="Este registro não pode ser exlcuido pois está vinculado a um contrato de servidor!" onclick="return null;"' : '';
                  ?>
                  <tr>
                    <input type="hidden" id="td_id" value="<?= $vObj['id']; ?>">
                    <td id="td_count"><?= $kObj+1; ?></td>
                    <td id="td_nome"><?= $vObj['nome']; ?></td>
                    <td id="td_status" value="<?= $vObj['status'];?>"><?= $vObj['status'] == 1 ? 'Ativo' : 'Inativo'; ?></td>
                    <td>
                      <button type="button" id="btn_editar_registro" class="btn_editar_registro waves-effect waves-light btn btn-warning btn-rounded" onclick="btnEditar(this);">
                        <i class="fa fa-edit"></i> Editar
                      </button>
                      <button type="button" id="btn_excluir_registro" class="btn_excluir_registro waves-effect waves-light btn btn-danger btn-rounded" <?= $excluirDisable; ?> onclick="btnExcluir(this);">
                        <i class="fa fa-trash"></i> Excluir
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
<script type="text/javascript" src="<?= PORTAL_URL; ?>control/eo/cargo/dashboard.js"></script>