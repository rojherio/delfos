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
  u.bsc_municipio_id AS municipio_id, 
  u.tel_fixo, 
  u.tel_celular, 
  u.email_pessoal, 
  u.email_institucional,  
  u.nivel_acesso, 
  u.status, 
  u.online, 
  u.dt_cadastro, 
  m.bsc_estado_id AS estado_id
  FROM seg_usuario AS u 
  LEFT JOIN bsc_municipio AS m ON m.id = u.bsc_municipio_id 
  LEFT JOIN bsc_estado AS e ON e.id = m.bsc_estado_id
  WHERE u.id = ?
  ORDER BY u.nome ASC;");
$stmt->bindValue(1, $id);
$stmt->execute();
$rsUsuario = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt = $db->prepare("
  SELECT 
  ua.id, 
  ua.permissao_seg_usuario_id, 
  ua.seg_acao_id, 
  ua.status 
  FROM seg_usuario_acao AS ua 
  WHERE ua.permissao_seg_usuario_id = ? 
  ORDER BY ua.seg_acao_id ASC;");
$stmt->bindValue(1, $id);
$stmt->execute();
$rsUsuarioAcoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt = $db->prepare("
  SELECT 
  id, 
  nome, 
  sigla 
  FROM bsc_estado 
  ORDER BY nome ASC;");
$stmt->execute();
$rsEstados = $stmt->fetchAll(PDO::FETCH_ASSOC);
if ($id != 0) {
  $stmt = $db->prepare("
    SELECT 
    id, 
    nome 
    FROM bsc_municipio 
    WHERE bsc_estado_id = ?
    ORDER BY nome ASC;");
  $stmt->bindValue(1, $rsUsuario['estado_id']);
  $stmt->execute();
  $rsMunicipios = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
$stmt = $db->prepare("
  SELECT 
  id, 
  nome, 
  status  
  FROM seg_acao
  ORDER BY id ASC;");
$stmt->execute();
$rsAcoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            <li class="breadcrumb-item active" aria-current="page">Novo Usuário</li>
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
              <span id="titulo_form"> <?= $id == 0 ? ' NOVO' : ' EDIÇÃO DE'; ?> USUÁRIO</span>
            </div>
          </h4>
          <!-- <a href="#" class="waves-effect waves-light btn btn-rounded btn-success mb-5 pull-right d-md-flex d-none">NOVO USUÁRIO</a> -->
        </div>
        <div class="box-body">
          <form class="form" id="form_usuario" name="form_usuario" method="post" action="">
            <div class="box box-outline-primary mt-3">
              <div class="box-header">
                <strong>INFOMAÇÕES PESSOAIS</strong>
              </div>
              <div class="box-body">
                <div class="row">
                  <div class="col-md-8">
                    <div class="form-group">
                      <label for="nome_u">Nome<span class="text-danger">*</span>: </label>
                      <div class="input-group mb-3 controls">
                        <input type="text" class="form-control" minlength="3" id="nome_u" name="nome_u" placeholder="Nome do usuário" value="<?= $rsUsuario['nome']; ?>" required/>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="login_u">Login<span class="text-danger">*</span>: </label>
                      <div class="input-group mb-3 controls">
                        <input type="text" class="form-control" minlength="3" id="login_u" name="login_u" placeholder="Login do usuário" value="<?= $rsUsuario['login']; ?>" required/>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="dt_nasc_u">Data de nascimento<span class="text-danger">*</span>: </label>
                      <div class="input-group mb-3 controls">
                        <input type="text" class="form-control date_format" minlength="10" id="dt_nasc_u" name="dt_nasc_u" placeholder="Data de nascimento do usuário" title="exemplo: 31/12/2000" value="<?= data_volta($rsUsuario['dt_nascimento']); ?>" required/>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="cpf_u">CPF<span class="text-danger">*</span>: </label>
                      <div class="input-group mb-3 controls">
                        <input type="text" class="form-control cpf_format" minlength="14" id="cpf_u" name="cpf_u" placeholder="CPF do usuário" title="exemplo: 999.999.999-99" value="<?= $rsUsuario['cpf']; ?>" required/>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="sexo_u">Sexo<span class="text-danger">*</span>: </label>
                      <div class="form-group ichack-input mt-10">
                        <label>
                          <input type="radio" id="sexo_u_F" name="sexo_u" class="square-purple" <?= $rsUsuario['sexo'] != 'M' ? 'checked="checked"' : ''; ?> value="F" required> Feminino
                        </label>
                        <label>
                          <input type="radio" id="sexo_u_M" name="sexo_u" class="square-purple" <?= $rsUsuario['sexo'] == 'M' ? 'checked="checked"' : ''; ?> value="M"> Masculino
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="box box-outline-primary mt-3">
              <div class="box-header">
                <strong>INFOMAÇÕES DE CONTATO</strong>
              </div>
              <div class="box-body">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="email_inst_u">E-mail institucional<span class="text-danger">*</span>: </label>
                      <div class="input-group mb-3 controls">
                        <input type="text" class="form-control" minlength="5" id="email_inst_u" name="email_inst_u" placeholder="E-mail institucional do usuário" value="<?= $rsUsuario['email_institucional']; ?>" required/>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="email_pessoal_u">E-mail pessoal<span class="text-danger">*</span>: </label>
                      <div class="input-group mb-3 controls">
                        <input type="text" class="form-control" minlength="5" id="email_pessoal_u" name="email_pessoal_u" placeholder="E-mail pessoal do usuário" value="<?= $rsUsuario['email_pessoal']; ?>" required/>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="tel_fixo_u">Telefone</span>: </label>
                      <div class="input-group mb-3 controls">
                        <input type="text" class="form-control tel_format" minlength="13" id="tel_fixo_u" name="tel_fixo_u" placeholder="Telefone do usuário" title="exemplo: (68)3222-2222" value="<?= $rsUsuario['tel_fixo']; ?>"/>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="tel_cel_u">Celular<span class="text-danger">*</span>: </label>
                      <div class="input-group mb-3 controls">
                        <input type="text" class="form-control cel_format" minlength="13" id="tel_cel_u" name="tel_cel_u" placeholder="Celular do usuário" title="exemplo: (68)9.9999-9999" value="<?= $rsUsuario['tel_celular']; ?>" required/>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="box box-outline-primary mt-3">
              <div class="box-header">
                <strong>INFOMAÇÕES DE RESIDÊNCIA</strong>
              </div>
              <div class="box-body">
                <div class="row">
                  <div class="col-md-10">
                    <div class="form-group">
                      <label for="end_log_u">Endereço<span class="text-danger">*</span>: </label>
                      <div class="input-group mb-3 controls">
                        <input type="text" class="form-control" minlength="5" id="end_log_u" name="end_log_u" placeholder="Endereço do usuário" value="<?= $rsUsuario['end_logradouro']; ?>" required/>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="form-group">
                      <label for="end_num_u">Número<span class="text-danger">*</span>: </label>
                      <div class="input-group mb-3 controls">
                        <input type="text" class="form-control" minlength="1" id="end_num_u" name="end_num_u" placeholder="Número do endereço do usuário" value="<?= $rsUsuario['end_numero']; ?>" required/>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-8">
                    <div class="form-group">
                      <label for="end_comp_u">Complemento: </label>
                      <div class="input-group mb-3 controls">
                        <input type="text" class="form-control" id="end_comp_u" name="end_comp_u" placeholder="Complemento do endereço do usuário" value="<?= $rsUsuario['end_complemento']; ?>"/>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="end_bairro_u">Bairro<span class="text-danger">*</span>: </label>
                      <div class="input-group mb-3 controls">
                        <input type="text" class="form-control" minlength="2" id="end_bairro_u" name="end_bairro_u" placeholder="Bairro do endereço do usuário" value="<?= $rsUsuario['end_bairro']; ?>" required/>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="end_cep_u">CEP: </label>
                      <div class="input-group mb-3 controls">
                        <input type="text" class="form-control cep_format" id="end_cep_u" name="end_cep_u" placeholder="CEP do endereço do usuário" title="exemplo: 69.900-000" value="<?= $rsUsuario['end_cep']; ?>"/>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="estado_u">Estado<span class="text-danger">*</span>: </label>
                      <div class="input-group mb-3 controls">
                        <select id="estado_u" name="estado_u" class="form-control select2" style="width: 100%;" placeholder="selecione o Estado do usuário" required>
                          <option></option>
                          <?php
                          foreach ($rsEstados as $kObj => $vObj) {
                            ?>
                            <option value="<?= $vObj['id']; ?>" <?= $vObj['id'] == $rsUsuario['estado_id'] ? 'selected="selected"' : ''; ?>><?= ($vObj['nome'].' - '.$vObj['sigla']); ?></option>
                            <?php
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="municipio_u">Município<span class="text-danger">*</span>: </label>
                      <div class="input-group mb-3 controls">
                        <select id="municipio_u" name="municipio_u" class="form-control select2" style="width: 100%;" placeholder="selecione o Município do usuário" required>
                          <?php
                          if ($id != 0) {
                            foreach ($rsMunicipios as $kObj => $vObj) {
                              ?>
                              <option value="<?= $vObj['id']; ?>" <?= $vObj['id'] == $rsUsuario['municipio_id'] ? 'selected="selected"' : ''; ?>><?= $vObj['nome']; ?></option>
                              <?php
                            }
                          } else {
                            ?>
                            <option value="0">Selecione primeiro o Estado do usuário</option>
                            <?php
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="box box-outline-primary mt-3">
              <div class="box-header">
                <strong>INFOMAÇÕES FUNCIONAIS</strong>
              </div>
              <div class="box-body">
                <div class="row">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label for="status_u">Status do Usuário: </label>
                      <br/>
                      <div class="checkbox checkbox-success mt-20">
                        <input type="checkbox" class="filled-in chk-col-primary" id="status_u" name="status_u" checked="true"/>
                        <label for="status_u">Ativo</label>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-8">
                    <label for="">PERMISSÕES DE ACESSO: </label>
                    <div class="form-group">
                      <div class="checkbox mt-20">
                        <?php
                        foreach ($rsAcoes as $kAcao => $vAcao) {
                          $checked = in_array($vAcao['id'], array_column($rsUsuarioAcoes, 'seg_acao_id')) ? 'checked="true"' : ''; 
                          ?>
                          <input type="checkbox" class="filled-in chk-col-success" id="acao_<?= $vAcao['id'] ;?>_u" name="acao_<?= $vAcao['id'] ;?>_u" <?= $checked ;?> value="<?= $vAcao['id'] ;?>"/>
                          <label for="acao_<?= $vAcao['id'] ;?>_u"><?= $vAcao['nome'] ;?></label> 
                          <br/>
                          <?
                        }
                        ?>
                      </div>
                    </div>
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
                <i class="ti-save-alt"></i><span id="btn_submit"> <?= $id == 0 ? 'Cadastrar' : 'Atualizar'; ?></span>
              </button>
            </div>
            <input type="hidden" id="id" name="id" value="<?= $id; ?>">
          </form>
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
<script type="text/javascript" src="<?= PORTAL_URL; ?>control/seg/usuario/cadastrar.js"></script>
<script type="text/javascript" src="<?= JS_FOLDER;  ?>validator_rmrosas.js"></script>