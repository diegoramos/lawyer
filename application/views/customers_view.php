  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Lista de Clientes</h3>
              <?php if ($add) {?>
                <button class="pull-right btn btn-success" onclick="add_customer()"><i class="glyphicon glyphicon-plus"></i>Nuevo Cliente</button>
                 <?php }?>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table id="customers" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                      <th>Código</th>
                      <th>Nombres</th>
                      <th>Apellidos</th>
                      <th>Genero</th>
                      <th style="width:150px;">Opción</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Bootstrap modal -->
<div class="modal fade bs-example-modal-lg" id="modal_form" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title"></h3>
            </div>
            <div class="modal-body form">
              <form action="#" id="form" class="form-horizontal">
               <!-- <input type="hidden" name="id_patient" id="id_patient"> -->
                <input type="hidden" value="" name="person_id"/>
                  <div class="form-body">
                    <div class="row">
                      <div role="tabpanel">
                          <!-- Nav tabs -->
                          <ul class="nav nav-tabs" role="tablist">
                              <li role="presentation" class="active"><a href="#datos_personales" aria-controls="datos_personales" role="tab" data-toggle="tab">Datos Personales</a>
                              </li>
                              <li role="presentation"><a href="#datos_contacto" aria-controls="datos_contacto" role="tab" data-toggle="tab">Datos de Contacto</a>
                              </li>
                              <li role="presentation"><a href="#datos_adicionales" aria-controls="datos_adicionales" role="tab" data-toggle="tab">Datos Adicionales</a>
                              </li>
                          </ul>
                          <!-- Tab panes -->
                          <div class="tab-content">
                              <div role="tabpanel" class="tab-pane active" id="datos_personales">
                                <br>
                                <div class="form-group">
                                    <label class="control-label col-md-2">Código</label>
                                    <div class="col-md-9">
                                        <input name="code" value="<?php echo $code; ?>" class="form-control" type="text" readonly="readonly">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">Nombres</label>
                                    <div class="col-md-9">
                                        <input name="first_name" placeholder="Nombres" class="form-control" type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">Apellidos</label>
                                    <div class="col-md-9">
                                        <input name="last_name" placeholder="Apellidos" class="form-control" type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">Fecha Nacimiento</label>
                                    <div class="col-md-9">
                                        <input name="birth_date" placeholder="yyyy-mm-dd" class="form-control datepicker" type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">Genero</label>
                                    <div class="col-md-9">
                                        <select name="gender" class="form-control">
                                            <option value="">--Selecione Genero--</option>
                                            <option value="1">Masculino</option>
                                            <option value="0">Femenino</option>
                                        </select>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                              </div>

                              <div role="tabpanel" class="tab-pane" id="datos_contacto">
                                <br>
                                <div class="form-group">
                                    <label class="control-label col-md-2">E-mail</label>
                                    <div class="col-md-9">
                                        <input name="email" id="email" placeholder="E-mail" class="form-control" type="text">
                                        <span class="help-block email"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">Teléfono</label>
                                    <div class="col-md-9">
                                        <input name="phone" id="phone" placeholder="Teléfono" class="form-control" type="number">
                                        <span class="help-block phone"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">Celular</label>
                                    <div class="col-md-9">
                                        <input name="cell_phone" id="cell_phone" placeholder="Celular" class="form-control" type="number">
                                        <span class="help-block cell_phone"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">Dirección</label>
                                    <div class="col-md-9">
                                        <input name="address" placeholder="Dirección" class="form-control" type="text">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                              </div>

                              <div role="tabpanel" class="tab-pane" id="datos_adicionales">
                                <br>
                                <div class="form-group">
                                    <label class="control-label col-md-2">D.N.I.</label>
                                    <div class="col-md-9">
                                        <input name="dni" id="dni" placeholder="Nro. DNI" class="form-control" type="number">
                                        <span class="help-block dni"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">Fecha Registro</label>
                                    <div class="col-md-9">
                                        <input type="text" value="<?php echo date('Y-m-d H:i:s'); ?>" name="date_reg" id="date_reg" class="form-control" type="text" readonly="readonly"/>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2">Comentario</label>
                                    <div class="col-md-9">
                                        <textarea name="comment" placeholder="Comentario" class="form-control" rows="4"></textarea>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group" id="photo-preview">
                                    <label class="control-label col-md-2">Foto</label>
                                    <div class="col-md-9">
                                        (No hay foto)
                                    <span class="help-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2" id="label-photo">Subir Foto</label>
                                    <div class="col-md-5">
                                        <input name="photo" type="file">
                                        <span class="help-block"></span>
                                    </div>
                                    <!--<div class="col-md-4">
                                        <button type="button" id="botoncam" class="btn btn-warning" onclick="openModalCamera()">​<span class="glyphicon glyphicon-camera" ></span> Tomar Foto</button>
                                    </div> -->
                                </div>
                              </div>
                          </div>
                      </div>
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Guardar</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- End Bootstrap modal -->
<script type="text/javascript">
  var base_url = '<?php echo base_url(); ?>';
</script>