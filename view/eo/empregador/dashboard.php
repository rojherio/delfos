<?php
include_once ('template/topo.php');
include_once ('template/header.php');
include_once ('template/sidebar.php');
$db = Conexao::getInstance();
$stmt = $db->prepare("
  SELECT 
  e.id, 
  e.nome_razao_social, 
  e.nome_fantasia, 
  e.cnpj, 
  e.ie, 
  e.end_cep, 
  e.end_logradouro, 
  e.end_numero, 
  e.end_complemento, 
  e.end_bairro, 
  e.end_bsc_municipio_id, 
  e.tel_residencial, 
  e.tel_celular, 
  e.tel_recado, 
  e.tel_recado_nome, 
  s.eo_empregador_id AS s_id_empregador, 
  sa.eo_empregador_id AS sa_id_empregador, 
  m.nome AS end_municipio_nome, 
  est.nome AS end_estado_nome, 
  e.status 
  FROM eo_empregador AS e 
  LEFT JOIN bsc_municipio AS m ON m.id = e.end_bsc_municipio_id 
  LEFT JOIN bsc_estado AS est ON est.id = m.bsc_estado_id 
  LEFT JOIN rh_servidor AS s ON e.id = s.eo_empregador_id 
  LEFT JOIN sacad_servidor_atualizacao AS sa ON e.id = sa.eo_empregador_id 
  GROUP BY e.id 
  ORDER BY e.nome_razao_social ASC");
$stmt->execute();
$rsEmpregadores = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <div class="container-full">
    <div class="content-header">
      <div class="d-inline-block align-items-center">
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= PORTAL_URL; ?>dashboard"><i class="fal fa-desktop"></i></a></li>
            <li class="breadcrumb-item active" aria-current="page">EMPREGADOR</li>
            <input type="hidden" id="titulo_relatorio" value="Relatório de empregadores cadastrados no sistema">
          </ol>
        </nav>
      </div>
    </div>
    <!-- Main content -->
    <section class="content">
      <form class="" id="form_empregador" name="form_empregador" method="post" action="">
        <div class="box box-solid bg-info">
          <div class="box-header">
            <h4 class="box-title font-weight-bold">
              <div class="d-flex align-items-center justify-content-between">
                <div class="icon rounded-circle font-size-30"><i class="fal fa-institution mr-10"></i></div>
                <span id="titulo_form"> NOVO EMPREGADOR</span>
              </div>
            </h4>
            <!-- <a href="#" class="waves-effect waves-light btn btn-rounded btn-success mb-5 pull-right d-md-flex d-none">NOVO USUÁRIO</a> -->
          </div>
          <div class="box box-inline-info" style="margin-bottom: 0px;">
            <div class="box-header">
              <strong>DADOS INSTITUCIONAIS</strong>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="nome_razao_e">Nome/Razão Social<span class="text-danger">*</span>: </label>
                    <div class="input-group mb-3 controls">
                      <input type="text" class="form-control" minlength="3" id="nome_razao_e" name="nome_razao_e" placeholder="Nome/Razão Social do empregador" value="" required/>
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="nome_fantasia_e">Nome fantasía<span class="text-danger"></span>: </label>
                    <div class="input-group mb-3 controls">
                      <input type="text" class="form-control" minlength="3" id="nome_fantasia_e" name="nome_fantasia_e" placeholder="Nome fantasía do empregador" value=""/>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="cnpj_e">CNPJ<span class="text-danger"></span>: </label>
                    <div class="input-group mb-3 controls">
                      <input type="text" class="form-control cnpj_format" minlength="14" id="cnpj_e" name="cnpj_e" placeholder="CNPJ do empregador" title="exemplo: 99.999.999/9999-99" value=""/>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="ie_e">IE<span class="text-danger"></span>: </label>
                    <div class="input-group mb-3 controls">
                      <input type="text" class="form-control ie_format" minlength="14" id="ie_e" name="ie_e" placeholder="IE do empregador" title="exemplo: 99.999.999/999-99" value=""/>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="box-header">
              <strong>CONTATO</strong>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="tel_res_e">Telefone fixo<span class="text-danger"></span>: </label>
                    <div class="input-group mb-3 controls">
                      <input type="text" class="form-control tel_format" minlength="13" id="tel_res_e" name="tel_res_e" placeholder="Telefone fixo do empregador" title="exemplo: (68)3222-2222" value=""/>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="tel_cel_e">Telefone celular<span class="text-danger"></span>: </label>
                    <div class="input-group mb-3 controls">
                      <input type="text" class="form-control cel_format" minlength="13" id="tel_cel_e" name="tel_cel_e" placeholder="Telefone celular do empregador" title="exemplo: (68)9.9999-9999" value=""/>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="tel_recado_e">Telefone para recado<span class="text-danger"></span>: </label>
                    <div class="input-group mb-3 controls">
                      <input type="text" class="form-control cel_format" minlength="13" id="tel_recado_e" name="tel_recado_e" placeholder="Telefone de recado do empregador" title="exemplo: (68)9.9999-9999" value=""/>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="tel_recado_nome_e">Nome do contato de recado para o servidor<span class="text-danger"></span>: </label>
                    <div class="input-group mb-3 controls">
                      <input type="text" class="form-control" minlength="5" id="tel_recado_nome_e" name="tel_recado_nome_e" placeholder="Nome do contato de recado para o empregador" value=""/>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="box-header">
              <strong>ENDEREÇO</strong>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-10">
                  <div class="form-group">
                    <label for="end_log_e">Logradouro<span class="text-danger"></span>: </label>
                    <div class="input-group mb-3 controls">
                      <input type="text" class="form-control" minlength="5" id="end_log_e" name="end_log_e" placeholder="Logradouro do endereço do empregador" value=""/>
                    </div>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="form-group">
                    <label for="end_num_e">Número<span class="text-danger"></span>: </label>
                    <div class="input-group mb-3 controls">
                      <input type="text" class="form-control" minlength="1" id="end_num_e" name="end_num_e" placeholder="Número do endereço do empregador" value=""/>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mt-3">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="end_comp_e">Complemento<span class="text-danger"></span>: </label>
                    <div class="input-group mb-3 controls">
                      <input type="text" class="form-control" id="end_comp_e" name="end_comp_e" placeholder="Complemento do endereço do empregador" value=""/>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="end_bairro_e">Bairro<span class="text-danger"></span>: </label>
                    <div class="input-group mb-3 controls">
                      <input type="text" class="form-control" minlength="2" id="end_bairro_e" name="end_bairro_e" placeholder="Bairro do endereço do empregador" value=""/>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="end_cep_e">CEP<span class="text-danger"></span>: </label>
                    <div class="input-group mb-3 controls">
                      <input type="text" class="form-control cep_format" id="end_cep_e" name="end_cep_e" placeholder="CEP do endereço do empregador" title="exemplo: 69.900-000" value=""/>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="cidade_e">CIDADE<span class="text-danger"></span>: </label>
                    <div class="input-group mb-3 controls">
                      <select id="cidade_e" name="cidade_e" class="form-control select2_cidade_e" style="width: 100%;" placeholder="selecione a cidade empregadore">
                        <option></option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="box-header">
              <strong>SITUAÇÃO DO EMPREGADOR</strong>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <br>
                    <div class="checkbox checkbox-success">
                      <input type="checkbox" class="filled-in chk-col-primary" id="status_e" name="status_e" checked="true">
                      <label for="status_e">Ativo</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="box-footer text-center">
                <button type="reset" id="btn_cancelar" class="btn btn-rounded btn-danger mr-1">
                  <i class="ti-trash"></i> Cancelar
                </button>
                <button type="submit" class="btn btn-rounded btn-success">
                  <i class="ti-save-alt"></i><span id="btn_submit"> Cadastrar</span>
                </button>
              </div>
            </div>
          </div>
          <!-- /.box-body -->
          <input type="hidden" id="id" name="id" value="">
        </div>
      </form>
      <div class="box box-solid bg-info">
        <div class="box-header">
          <h4 class="box-title font-weight-bold">
            <div class="d-flex align-items-center justify-content-between">
              <div class="icon rounded-circle font-size-30"><i class="fal fa-id-card mr-10"></i></div>
              <h4 id="titulo_pagina" class="box-title font-size-16"><strong>EMPREGADORES</strong></h4>
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
                  <th>Nome/Razão Social</th>
                  <th>Nome fantasia</th>
                  <th>CNPJ</th>
                  <th>IE</th>
                  <th>Tel. FIxo</th>
                  <th>Celular</th>
                  <th>Número Recado</th>
                  <th>Nome Recado</th>
                  <th>Logradouro</th>
                  <th>Número</th>
                  <th>Complemento</th>
                  <th>Bairro</th>
                  <th>CEP</th>
                  <th>Município</th>
                  <th>Status</th>
                  <th class="no-print" width="160px !important"></th>
                </tr>
              </thead>
              <tbody>
                <?php
                foreach ($rsEmpregadores as $kObj => $vObj) {
                  $excluirDisable = !is_null($vObj['s_id_empregador']) || !is_null($vObj['sa_id_empregador']) ? 'negado="true" data-toggle="tooltip" title="Este registro não pode ser exlcuido pois está vinculado a um contrato de servidor!" onclick="return null;"' : '';
                  ?>
                  <tr>
                    <input type="hidden" id="td_id" value="<?= $vObj['id']; ?>">
                    <td id="td_count"><?= $kObj+1; ?></td>
                    <td id="td_nome_razao_e"><?= $vObj['nome_razao_social']; ?></td>
                    <td id="td_nome_fantasia_e"><?= $vObj['nome_fantasia']; ?></td>
                    <td id="td_cnpj_e"><?= $vObj['cnpj']; ?></td>
                    <td id="td_ie_e"><?= $vObj['ie']; ?></td>
                    <td id="td_tel_res_e"><?= $vObj['tel_residencial']; ?></td>
                    <td id="td_tel_cel_e"><?= $vObj['tel_celular']; ?></td>
                    <td id="td_tel_recado_e"><?= $vObj['tel_recado']; ?></td>
                    <td id="td_tel_recado_nome_e"><?= $vObj['tel_recado_nome']; ?></td>
                    <td id="td_end_log_e"><?= $vObj['end_logradouro']; ?></td>
                    <td id="td_end_num_e"><?= $vObj['end_numero']; ?></td>
                    <td id="td_end_comp_e"><?= $vObj['end_complemento']; ?></td>
                    <td id="td_end_bairro_e"><?= $vObj['end_bairro']; ?></td>
                    <td id="td_end_cep_e"><?= $vObj['end_cep']; ?></td>
                    <td id="td_cidade_e" value="<?= $vObj['end_bsc_municipio_id'] ;?>"><?= $vObj['end_municipio_nome'] . ' - ' . $vObj['end_estado_nome'] ; ?></td>
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
<script type="text/javascript" src="<?= PORTAL_URL; ?>control/eo/empregador/dashboard.js"></script>