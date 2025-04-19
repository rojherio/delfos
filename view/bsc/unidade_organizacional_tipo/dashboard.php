<?php
include_once ('template/topo.php');
include_once ('template/sidebar.php');
include_once ('template/header.php');
$db = Conexao::getInstance();
$stmt = $db->prepare("SELECT 
  uot.id AS id, 
  uot.nome AS nome, 
  uot.status AS status, 
  uo.bsc_unidade_organizacional_tipo_id AS id_tipo
  FROM bsc_unidade_organizacional_tipo AS uot 
  LEFT JOIN bsc_unidade_organizacional AS uo ON uot.id = uo.bsc_unidade_organizacional_tipo_id
  GROUP BY uot.id 
  ORDER BY uot.nome ASC;");
$stmt->execute();
$rsUnidadeOrganizaionalTipo = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- main section -->
<main>
  <div class="container-fluid">
    <!-- Breadcrumb start -->
    <div class="row m-1">
      <div class="col-12 ">
        <h4 class="main-title">Tipo de instituição</h4>
        <ul class="app-line-breadcrumbs mb-3">
          <li class="">
            <a href="<?= PORTAL_URL; ?>" class="f-s-14 f-w-500">
              <span>
                <i class="ph-duotone  ph-cardholder f-s-16"></i>  Estrutura Organizacional
              </span>
            </a>
          </li>
          <li class="active">
            <a href="<?= PORTAL_URL; ?>" class="f-s-14 f-w-500">Tipo de isntituição</a>
          </li>
        </ul>
      </div>
    </div>
    <!-- Breadcrumb end -->

    <!-- Floating Labels start -->
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h5>NOVO TIPO DE INSTITUIÇÃO</h5>
            <small>Explicação da página</small>
          </div>
          <div class="card-body">
            <form class="app-form" id="form_unidade_organizacional_tipo" name="form_unidade_organizacional_tipo" method="post" action="">
              <div class="row">
                <div class="col-12">
                  <div class="form-floating mb-3">
                    <input type="text" class="form-control" minlength="3" id="nome_tipo" name="nome_tipo" placeholder="Digite o nome da instituição" value="Alabama" required>
                    <label for="nome_tipo">Nome</label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <div class="form-floating mb-3">
                    <select class="select_modelo form-control select-example form-select select-basic" id="secretaria" name="secretaria" aria-label="Selecione uma secretaria">>
                      <option value="AL">Alabama</option>
                      <option value="AL">Wyoming</option>
                      <option value="WY">Coming</option>
                      <option value="WY">Hanry Die</option>
                      <option value="WY">John Doe</option>
                    </select>
                    <label for="secretaria" class="form-label">Secretaria</label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <div class="card-body main-switch main-switch-color">
                    <div class="switch-info swich-size">
                      <input type="checkbox" class="toggle" id="status_tipo" name="status_tipo" value="1">
                      <label for="status_tipo">Ativo</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="box-footer text-center">
                  <button type="reset" class="btn btn-danger waves-effect waves-light b-r-22" id="btn_cancelar">
                    <i class="ti ti-eraser"></i> Cancelar
                  </button>
                  <button type="submit" class="btn btn-success waves-effect waves-light b-r-22">
                    <i class="ti ti-writing"></i> Cadastrar
                  </button>
                </div>
                <input type="hidden" id="id" name="id" value="">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card ">
          <div class="card-header">
            <h5><span id="titulo_form" class="icon-name"> LISTA DE TIPOS DE SECRETARIAS</span></h5>
            <small>Explicação da página</small>
            <input type="hidden" id="titulo_relatorio" value="Relatório de tipos de secretarias cadastrados no sistema">
          </div>
          <div class="card-body p-0">
            <div class="app-datatable-default overflow-auto">
              <h6 class="card-header">Copiar, Exportar (CVS, EXCEL, PDF) ou Imprimir a tabela.</h6>
              <!-- <table id="table_modelo_01" class="display app-data-table default-data-table"> -->
              <table id="table_modelo_01" class="table table-striped display app-data-table">
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
                  foreach ($rsUnidadeOrganizaionalTipo as $kObj => $vObj) {
                    $excluirDisable = !is_null($vObj['id_tipo']) ? 'negado="true" data-toggle="tooltip" title="Este registro não pode ser exlcuido pois está vinculado a um tipo de secretaria!" onclick="return null;"' : '';
                    ?>
                    <tr>
                      <input type="hidden" id="td_id" value="<?= $vObj['id']; ?>">
                      <td id="td_count"><?= $kObj+1; ?></td>
                      <td id="td_nome"><?= $vObj['nome']; ?></td>
                      <td id="td_status" value="<?= $vObj['status'];?>"><span class="badge text-light-primary"><?= $vObj['status'] == 1 ? 'Ativo' : 'Inativo'; ?></span></td>
                      <td class="text-center">
                        <button type="button" id="btn_editar_registro" class="btn_editar_registro btn btn-light-warning icon-btn b-r-4" onclick="btnEditar(this);">
                          <i class="ti ti-edit"></i>
                        </button>
                        <button type="button" id="btn_excluir_registro" class="btn_excluir_registro btn btn-light-danger icon-btn b-r-4" <?= $excluirDisable; ?> onclick="btnExcluir(this);">
                          <i class="ti ti-trash"></i>
                        </button>
                      </td>
                    </tr>
                    <?php
                  }
                  ?>
                  <!-- <td><span class="badge text-light-primary">System Architect</span></td>
                  <td><span class="badge text-light-success">Accountant</span></td>
                  <td><span class="badge text-light-secondary">Junior Technical Author</span></td>
                  <td><span class="badge text-light-info">Senior Javascript Developer</span></td>
                  <td><span class="badge text-light-danger"> Integration Specialist</span></td>
                  <td><span class="badge text-light-dark">Sales Assistant</span></td>
                  <td><span class="badge text-light-light">Integration Specialist</span></td>
                  <td><span class="badge text-light-warning">Marketing Designer</span></td> -->
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<?php
include_once ('template/footer.php');
//include_once ('template/control_sidebar.php');
include_once ('template/rodape.php');
?>
<script type="text/javascript" src="<?= PORTAL_URL; ?>control/bsc/unidade_organizacional_tipo/dashboard.js"></script>