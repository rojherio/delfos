<?php
include_once ('template/topo.php');
include_once ('template/header.php');
include_once ('template/sidebar.php');
$id = !(isset($_POST['id'])) ? 0 : $_POST['id'];
$db = Conexao::getInstance();
$stmt = $db->prepare("SELECT u.id, u.nome, u.foto  
  FROM seg_usuario AS u 
  WHERE u.id = ?;");
$stmt->bindValue(1, $_SESSION['zatu_id']);
$stmt->execute();
$rsUsuario = $stmt->fetch(PDO::FETCH_ASSOC);
if ($rsUsuario['foto'] == "") {
  $rsUsuario['foto'] = AVATAR_FOLDER. 'picture.jpg';
} else {
  $rsUsuario['foto'] = AVATAR_FOLDER. $rsUsuario['foto'];
}
?>
<link href="<?= PLUGINS_FOLDER ;?>cropperjs/cropper.css" rel="stylesheet" />
<link href="<?= PLUGINS_FOLDER ;?>cropperjs/main.css" rel="stylesheet" />
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <div class="container-full">
    <div class="content-header">
      <div class="d-inline-block align-items-center">
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= PORTAL_URL; ?>dashboard"><i class="fal fa-desktop"></i></a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="<?= PORTAL_URL; ?>view/seg/usuario/dashboard">Usuários</a></li>
            <li class="breadcrumb-item active" aria-current="page">Foto do Usuário</li>
          </ol>
        </nav>
      </div>
    </div>
      <div class="box box-solid bg-info">
        <div class="box-header">
          <h4 class="box-title font-weight-bold">
            <div class="d-flex align-items-center justify-content-between">
              <div class="icon rounded-circle font-size-30"><i class="fal fa-user mr-10"></i></div>
              <span id="titulo_form">FOTO DO USUÁRIO</span>
            </div>
          </h4>
        </div>
        <form class="form" id="form_usuario_foto" name="form_usuario_foto" enctype="multipart/form-data" method="post" action="">
          <div class="box-body">
            <div class="box box-outline-primary mt-3">
              <div class="box-header">
                <strong>DEFINIÇÃO DE FOTO DO USUáRIO</strong>
              </div>
              <div class="box-body">
                <div class="container">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label class="btn btn-primary btn-upload" for="inputImage" title="Carregar arquivo de imagem">
                          <input type="hidden" class="avatar-src" id="avatarSrc" name="avatar_src" value="<?= $rsUsuario['foto'] ;?>">
                          <input type="hidden" class="avatar-data" id="avatarData" name="avatar_data" value="">
                          <input type="file" class="sr-only" id="inputImage" name="avatar_file" accept="image/*">
                          <span class="docs-tooltip" data-toggle="tooltip" title="Selecionar imagem">
                            <span class="fa fa-upload"></span>
                          </span>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-9">
                      <!-- <h3>Demo:</h3> -->
                      <div class="docs-demo">
                        <div class="img-container">
                          <img src="<?= $rsUsuario['foto'] ;?>" id="img-cropped" alt="Foto">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <!-- <h3>Preview:</h3> -->
                      <div class="docs-preview clearfix">
                        <div class="img-preview preview-lg"></div>
                        <div class="img-preview preview-md"></div>
                        <div class="img-preview preview-sm"></div>
                        <div class="img-preview preview-xs"></div>
                      </div>
                      <!-- <h3>Data:</h3> -->
                      <input type="hidden" class="form-control" id="dataX" placeholder="x">
                      <input type="hidden" class="form-control" id="dataY" placeholder="y">
                      <input type="hidden" class="form-control" id="dataWidth" placeholder="width">
                      <input type="hidden" class="form-control" id="dataHeight" placeholder="height">
                      <input type="hidden" class="form-control" id="dataRotate" placeholder="rotate">
                      <input type="hidden" class="form-control" id="dataScaleX" placeholder="scaleX">
                      <input type="hidden" class="form-control" id="dataScaleY" placeholder="scaleY">
                    </div>
                  </div>
                  <div class="row" id="actions">
                    <div class="col-md-9 docs-buttons">
                      <div class="btn-group">
                        <button type="button" class="btn btn-primary" data-method="zoom" data-option="0.1" title="Aumentar zoom">
                          <span class="docs-tooltip" data-toggle="tooltip" title="Aumentar zoom">
                            <span class="fa fa-search-plus"></span>
                          </span>
                        </button>
                        <button type="button" class="btn btn-primary" data-method="zoom" data-option="-0.1" title="Diminuir zoom">
                          <span class="docs-tooltip" data-toggle="tooltip" title="Diminuir zoom">
                            <span class="fa fa-search-minus"></span>
                          </span>
                        </button>
                      </div>
                      <div class="btn-group">
                        <button type="button" class="btn btn-primary" data-method="move" data-option="-10" data-second-option="0" title="Mover a esquerda">
                          <span class="docs-tooltip" data-toggle="tooltip" title="cropper.move(-10, 0)">
                            <span class="fa fa-arrow-left"></span>
                          </span>
                        </button>
                        <button type="button" class="btn btn-primary" data-method="move" data-option="10" data-second-option="0" title="Mover a direita">
                          <span class="docs-tooltip" data-toggle="tooltip" title="Mover a direita">
                            <span class="fa fa-arrow-right"></span>
                          </span>
                        </button>
                        <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="-10" title="Mover a cima">
                          <span class="docs-tooltip" data-toggle="tooltip" title="Mover a cima">
                            <span class="fa fa-arrow-up"></span>
                          </span>
                        </button>
                        <button type="button" class="btn btn-primary" data-method="move" data-option="0" data-second-option="10" title="Mover a baixo">
                          <span class="docs-tooltip" data-toggle="tooltip" title="Mover a baixo">
                            <span class="fa fa-arrow-down"></span>
                          </span>
                        </button>
                      </div>
                      <div class="btn-group">
                        <button type="button" class="btn btn-primary" data-method="rotate" data-option="-45" title="Girar a esquerda">
                          <span class="docs-tooltip" data-toggle="tooltip" title="Girar a esquerda">
                            <span class="fa fa-undo-alt"></span>
                          </span>
                        </button>
                        <button type="button" class="btn btn-primary" data-method="rotate" data-option="45" title="Girar a direita">
                          <span class="docs-tooltip" data-toggle="tooltip" title="Girar a direita">
                            <span class="fa fa-redo-alt"></span>
                          </span>
                        </button>
                      </div>
                      <div class="btn-group">
                        <button type="button" class="btn btn-primary" data-method="scaleX" data-option="-1" title="Inverter na horizontal">
                          <span class="docs-tooltip" data-toggle="tooltip" title="Inverter ma horizontal">
                            <span class="fa fa-arrows-alt-h"></span>
                          </span>
                        </button>
                        <button type="button" class="btn btn-primary" data-method="scaleY" data-option="-1" title="Inverter na vertical">
                          <span class="docs-tooltip" data-toggle="tooltip" title="Inverter na vertical">
                            <span class="fa fa-arrows-alt-v"></span>
                          </span>
                        </button>
                      </div>
                      <div class="btn-group">
                        <button type="button" class="btn btn-primary" data-method="reset" title="Reiniciar edição">
                          <span class="docs-tooltip" data-toggle="tooltip" title="Reiniciar edição">
                            <span class="fa fa-sync-alt"></span>
                          </span>
                        </button> 
                      </div>
                      <div class="btn-group btn-group-crop">
                        <button type="button" class="btn btn-success" data-method="getCroppedCanvas" data-option="{ &quot;maxWidth&quot;: 4096, &quot;maxHeight&quot;: 4096 }">
                          <span class="docs-tooltip" data-toggle="tooltip" title="Exibir o resultado da edição">
                            Visualizar resultado da edição
                          </span>
                        </button>
                      </div>
                      <!-- Show the cropped image in modal -->
                      <div class="modal fade docs-cropped" id="getCroppedCanvasModal" role="dialog" aria-hidden="true" aria-labelledby="getCroppedCanvasTitle" tabindex="-1">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title" id="getCroppedCanvasTitle">Resultado da edição</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>
                            <div class="modal-body"></div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                              <a class="btn btn-primary" id="download" href="javascript:void(0);" download="cropped.jpg">Download</a>
                            </div>
                          </div>
                        </div>
                      </div><!-- /.modal -->
                    </div><!-- /.docs-buttons -->
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
            <button type="submit" id="btn_foto_atualizar" class="btn btn-rounded btn-success">
              <i class="ti-save-alt"></i><span id="btn_submit"> Atualizar</span>
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
<script type="text/javascript" src="<?= PLUGINS_FOLDER ?>cropperjs/cropper.js"></script>
<script type="text/javascript" src="<?= PORTAL_URL ?>control/seg/usuario/cropper_main.js"></script>
<script type="text/javascript" src="<?= PORTAL_URL; ?>control/seg/usuario/foto.js"></script>