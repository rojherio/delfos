<?php
include_once ('template/topo.php');
include_once ('template/header.php');
include_once ('template/sidebar.php');
$db = Conexao::getInstance();
$stmt = $db->prepare("
  SELECT 
  uo.id AS id, 
  uo.numero AS numero, 
  uo.nome AS nome, 
  uo.status AS status, 
  uo.bsc_unidade_organizacional_tipo_id AS id_tipo, 
  uot.nome AS nome_tipo, 
  sc.bsc_unidade_organizacional_id AS sc_id_uo 
  FROM bsc_unidade_organizacional AS uo 
  LEFT JOIN bsc_unidade_organizacional_tipo AS uot ON uot.id = uo.bsc_unidade_organizacional_tipo_id
  LEFT JOIN rh_servidor_contrato AS sc ON uo.id = sc.bsc_unidade_organizacional_id 
  GROUP BY uo.id 
  ORDER BY uo.nome ASC;");
$stmt->execute();
$rsUnidadeOrganizaional = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt = $db->prepare("SELECT id, nome, status
  FROM bsc_unidade_organizacional_tipo
  ORDER BY status DESC, nome ASC;");
$stmt->execute();
$rsUnidadeOrganizaionalTipo = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <div class="container-full">
    <div class="content-header">
      <div class="d-inline-block align-items-center">
          <nav>
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="<?= PORTAL_URL; ?>dashboard"><i class="fal fa-desktop"></i></a></li>
              <li class="breadcrumb-item active" aria-current="page">Atestador</li>
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
              <div class="icon rounded-circle font-size-30"><i class="fal fa-home-lg mr-10"></i></div>
              <span id="titulo_form"> NOVO ATESTADOR</span>
            </div>
          </h4>
          <!-- <a href="#" class="waves-effect waves-light btn btn-rounded btn-success mb-5 pull-right d-md-flex d-none">NOVO USUÁRIO</a> -->
        </div>
        <div class="box-body">
          <form class="" id="form_unidade_organizacional" name="form_unidade_organizacional" method="post" action="">
            <div class="row">
              <div class="col-md-2">
                <div class="form-group">
                  <label for="numero_uo">Número<span class="text-danger">*</span>: </label>
                  <div class="input-group mb-3 controls">
                    <input type="text" class="form-control" minlength="3" id="numero_uo" name="numero_uo" placeholder="Número da Unidade Organizacional" value="" required/>
                  </div>
                </div>
              </div>
              <div class="col-md-5">
                <div class="form-group">
                  <label for="nome_uo">Nome<span class="text-danger">*</span>: </label>
                  <div class="input-group mb-3 controls">
                    <input type="text" class="form-control" minlength="3" id="nome_uo" name="nome_uo" placeholder="Nome da Unidade Organizacional" value="" required/>
                  </div>
                </div>
              </div>
              <div class="col-md-5">
                <div class="form-group">
                  <label for="tipo_uo">Tipo de Entidade<span class="text-danger">*</span>: </label>
                  <div class="input-group mb-3 controls">
                    <select id="tipo_uo" class="form-control select2" style="width: 100%;" name="tipo_uo" placeholder="selecione un tipo de entidade" required>
                      <option></option>
                      <?php
                      foreach ($rsUnidadeOrganizaionalTipo as $kObj => $vObj) {
                        $vOdjInativo = $vObj['status'] == 0 ? 'disabled="disabled"' : '';
                        $vObjInativoDetail = $vObj['status'] == 0 ? '(INATIVO) - ' : '';
                        $hasInativo = 0;
                        ?>
                        <option <?= $vOdjInativo; ?> value="<?= $vObj['id']; ?>"><?= ($vObjInativoDetail.$vObj['nome']); ?></option>
                        <?php
                      }
                      ?>
                    </select>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <br>
                  <div class="checkbox checkbox-success">
                    <input type="checkbox" class="filled-in chk-col-primary" id="status_uo" name="status_uo" checked="true">
                    <label for="status_uo">Ativo</label>
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

      <br>

      <div class="box box-solid bg-facebook mt-3">
        <div class="box-header bg-primary">
          <h4 class="box-title font-weight-bold">
            <div class="d-flex align-items-center justify-content-between">
              <div class="icon bg-primary rounded-circle font-size-30"><i class="fal fa-home-lg mr-10"></i></div>
              <span id="titulo_form"> LISTA DE ATESTADORES</span>
              <input type="hidden" id="titulo_relatorio" value="Relatório de servidores que são chefes imediatos responsáveis por atestação de vínculo nos setores/secretarias">
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
                  <th>Número</th>
                  <th>Nome</th>
                  <th>Entidade</th>
                  <th>Status</th>
                  <th class="no-print" width="160px !important"></th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($rsUnidadeOrganizaional as $kObj => $vObj) {
                  $excluirDisable = !is_null($vObj['sc_id_uo'])  ? 'negado="true" data-toggle="tooltip" title="Este registro não pode ser exlcuido pois está vinculado a um contrato de servidor!" onclick="return null;"' : '';
                  ?>
                  <tr>
                    <input type="hidden" id="td_id" value="<?= $vObj['id']; ?>">
                    <td id="td_count"><?= $kObj+1; ?></td>
                    <td id="td_numero"><?= $vObj['numero']; ?></td>
                    <td id="td_nome"><?= $vObj['nome']; ?></td>
                    <td id="td_tipo_uo" value="<?= $vObj['id_tipo'];?>"><?= $vObj['nome_tipo']; ?></td>
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
<script type="text/javascript" src="<?= PORTAL_URL; ?>control/bsc/unidade_organizacional/dashboard.js"></script>