
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
      <!-- Main content -->
      <section class="content">
          <div class="row">
              <div class="col-md-4">
                  <!-- Widget: user widget style 1 -->
                  <div class="box box-widget widget-user-2">
                      <!-- Add the bg color to the header using any of the bg-* classes -->
                      <div class="widget-user-header bg-blue">
                          <div class="widget-user-image">
                              <?php if (!empty($customer->photo)) {?>
                              <img class="img-circle"
                                  src="<?php echo base_url(); ?>upload/<?php echo $customer->photo; ?>"
                                  alt="User Avatar">
                              <?php } else {?>
                              <img class="img-circle" src="<?php echo base_url(); ?>assets/dist/img/people.png"
                                  alt="User Avatar">
                              <?php } ?>
                          </div>
                          <!-- ID DEL PACIENTE NO DE LA PERSONA -->
                          <input type="hidden" name="customer_id" id="customer_id" value="<?php echo $customer->customer_id; ?>">
                          <input type="hidden" name="cita_id" id="cita_id"
                              value="<?php echo (isset($events['cita_id']))?$events['cita_id']:''; ?>">
                          <!-- /.widget-user-image -->
                          <h3 class="widget-user-username">
                              <?php echo $customer->first_name . ' ' . $customer->last_name; ?></h3>
                          <h5 class="widget-user-desc"><?php echo $customer->email; ?></h5>
                      </div>
                      <div class="box-footer no-padding">
                          <ul class="nav nav-stacked">
                              <li><a href="#"><i class="fa fa-mobile margin-r-5"></i> Celular:
                                      <strong><?php echo $customer->cell_phone; ?></strong></a></li>
                              <li><a href="#"><i class="fa fa-phone margin-r-5"></i> Teléfono:
                                      <strong><?php echo $customer->phone; ?></strong></a></li>
                          </ul>
                      </div>
                  </div>
                  <!-- /.widget-user -->
              </div>

              <div class="col-md-4">
                  <!-- Widget: user widget style 1 -->
                  <div class="box box-widget widget-user-2">
                      <!-- Add the bg color to the header using any of the bg-* classes -->
                      <?php if (empty($customer->comment)) {?>
                      <div class="alert alert-success alert-dismissible">
                          <h4><i class="icon fa fa-check"></i>Comentario</h4>
                          Ningun comentario...
                      </div>
                      <?php } else {?>
                      <div class="alert alert-danger alert-dismissible">
                          <h4><i class="icon fa fa-ban"></i> Información</h4>
                          <?php echo $customer->comment; ?>
                      </div>
                      <?php }?>
                      <div class="box-footer no-padding">
                          <ul class="nav nav-stacked">
                              <li><a href="#"><i class="fa fa-barcode margin-r-5"></i> Código:
                                <strong><?php echo $customer->code; ?></strong></a></li>
                              <?php
                                $cumpleanos = new DateTime($customer->birth_date);
                                $hoy = new DateTime();
                                $annos = $hoy->diff($cumpleanos);
                                $edad = $annos->y;
                              ?>
                              <li><a href="#"><i class="fa fa-hourglass-start margin-r-5"></i> Edad:
                                <strong><?php if ($edad == 0) {
                                  echo "Edad no definida";
                                } else {
                                  echo $edad;
                                }
                                 ?> </strong></a></li>
                          </ul>
                      </div>
                  </div>
                  <!-- /.widget-user -->
              </div>

              <div class="col-md-4">
                  <!-- Widget: user widget style 1 -->
                  <div class="box box-widget widget-user-2">
                      <!-- Add the bg color to the header using any of the bg-* classes -->
                      <div class="box-footer no-padding">
                          <ul class="nav nav-stacked">
                            <button class="pull-right btn btn-warning" onclick="add_contraria()"><i class="glyphicon glyphicon-plus"></i>Parte Contraria</button>
                          </ul>
                      </div>
                  </div>
                  <!-- /.widget-user -->
              </div>

          </div>
          <div class="row">
              <div class="col-md-12">
                  <div class="nav-tabs-custom ">
                      <ul class="nav nav-tabs nav-tabs-custom ">
                          <?php if ($is_consulta): ?>
                          <li class=""><a href="#consultas" data-toggle="tab" style="color:#23223D;">
                                  <h4>Consultas</h4>
                              </a></li>
                          <?php endif ?>
                          <?php if ($is_tramite): ?>
                          <li><a href="#tramites" data-toggle="tab" style="color:#23223D;">
                                  <h4>Tramites</h4>
                              </a></li>
                          <?php endif ?>
                          <?php if ($is_proceso): ?>
                          <li><a href="#procesos" data-toggle="tab" id="pagotab" style="color:#23223D;">
                                  <h4>Procesos</h4>
                              </a></li>
                          <?php endif ?>
                          <?php if ($is_archivo): ?>
                          <li><a href="#archivos" data-toggle="tab" style="color:#23223D;">
                                  <h4>Archivos</h4>
                              </a></li>
                          <?php endif ?>
                      </ul>
                      <div class="tab-content " style="background-color: #F3EEED;">
                          <div class="tab-pane" id="consultas">
                              <div class="box box-primary">
                                  <div class="box-header bg-primary">
                                      <h3 class="box-title" style="color: white;">Lista de Consultas</h3>
                                      <button class="pull-right btn btn-warning" onclick="add_consulta()"><i class="glyphicon glyphicon-plus"></i>Nuevo Consulta</button>
                                  </div>
                                  <div class="box-body">
                                      <table id="table_consulta" class="table table-bordered table-hover">
                                          <thead>
                                              <tr>
                                                <th>Fecha</th>
                                                <th>Profesional</th>
                                                <th>Tipo de consulta</th>
                                                <th>A cuenta</th>
                                                <th>Saldo</th>
                                                <th>Total</th>
                                                <th>Estado</th>
                                                <th style="width:180px;">Opción</th>
                                              </tr>
                                          </thead>
                                          <tbody>
                                          </tbody>
                                      </table>
                                  </div>
                              </div>
                          </div>
                          <!-- /.tab-pane -->
                          <div class="tab-pane" id="tramites">
                              <div class="box box-primary">
                                  <div class="box-header bg-primary">
                                      <h3 class="box-title" style="color: white;">Lista de Trámites</h3>
                                      <button class="pull-right btn btn-warning" onclick="add_tramite()"><i class="glyphicon glyphicon-plus"></i>Nuevo Trámite</button>
                                  </div>
                                  <div class="box-body">
                                      <table id="table_tramite" class="table table-bordered table-hover">
                                          <thead>
                                              <tr>
                                                <th>Fecha</th>
                                                <th>Profesional</th>
                                                <th>Tipo de tramite</th>
                                                <th>A cuenta</th>
                                                <th>Saldo</th>
                                                <th>Total</th>
                                                <th>Estado</th>
                                                <th style="width:180px;">Opción</th>
                                              </tr>
                                          </thead>
                                          <tbody>
                                          </tbody>
                                      </table>
                                  </div>
                              </div>
                          </div>
                          <!-- /.tab-pane -->
                          <div class="tab-pane" id="procesos">
                              <div class="box box-primary">
                                  <div class="box-header bg-primary">
                                      <h3 class="box-title" style="color: white;">Lista de Procesos</h3>
                                      <button class="pull-right btn btn-warning" onclick="add_proceso()"><i class="glyphicon glyphicon-plus"></i>Nuevo Proceso</button>
                                  </div>
                                  <div class="box-body">
                                      <table id="table_proceso" class="table table-bordered table-hover">
                                          <thead>
                                              <tr>
                                                  <th>Fecha</th>
                                                  <th>Profesional</th>
                                                  <th>Tipo de Proceso</th>
                                                  <th>Expediente</th>
                                                  <th>Total</th>
                                                  <th>Estado</th>
                                                  <th style="width:180px;">Opción</th>
                                              </tr>
                                          </thead>
                                          <tbody>
                                          </tbody>
                                      </table>
                                  </div>
                              </div>
                          </div>
                          <!-- /.tab-pane -->
                          <div class="tab-pane" id="archivos">
                              <div class="box box-primary">
                                  <div class="box-header bg-primary">
                                      <h3 class="box-title" style="color: white;">Lista de Archivos</h3>
                                      <button class="pull-right btn btn-warning" onclick="add_files()"><i class="glyphicon glyphicon-plus"></i>Nuevo Achivo</button>
                                  </div>
                                  <div class="box-body">
                                      <table id="table_files" class="table table-bordered table-hover">
                                          <thead>
                                              <tr>
                                                  <th>Fecha</th>
                                                  <th>Expediente</th>
                                                  <th>Sumila</th>
                                                  <th style="width:500px;">Archivo</th>
                                                  <th style="width:90px;">Opción</th>
                                              </tr>
                                          </thead>
                                          <tbody>
                                          </tbody>
                                      </table>
                                  </div>
                              </div>
                          </div>
                          <!-- /.tab-pane -->
                      </div>
                      <!--s /.tab-content -->
                  </div>
                  <!-- /.nav-tabs-custom -->
              </div>
              <!-- /.col -->
          </div>
          <!-- /.row -->
      </section>
      <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!--  TODOS LOS MODALS QUE EXISTEN -->

  <!-- Bootstrap modal Consulta-->
  <div class="modal fade" id="modal_consulta" role="dialog" data-backdrop="static">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h3 class="modal-title"></h3>
              </div>
              <div class="modal-body form">
                  <div class="modal-body">
                      <div class="error"></div>
                      <form action="#" class="form-horizontal" id="crud_consulta">
                          <input type="hidden" name="consulta_id" id="consulta_id">
                          <div class="form-group">
                              <input type="hidden" name="customer_id" id="customer_id" value="<?php echo $customer->customer_id; ?>">
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 control-label">Fecha</label>
                              <div class="col-sm-4">
                                  <div class="input-group date" id='date_start'>
                                      <input type="text" id="fecha" name="fecha" value="<?php echo date('Y-m-d'); ?>"
                                          class="form-control">
                                      <span class="input-group-addon">
                                          <span class="glyphicon glyphicon-calendar"></span>
                                      </span>
                                  </div>
                              </div>
                              <label class="col-sm-2 control-label">Hora</label>
                              <div class="col-sm-3">
                                  <div class="input-group date" id="time_start">
                                      <input type="text" id="hora" name="hora" value="<?php echo date('h:i:s'); ?>"
                                          class="form-control">
                                      <span class="input-group-addon">
                                          <span class="glyphicon glyphicon-time"></span>
                                      </span>
                                  </div>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 control-label">Tipo de consulta</label>
                              <div class="col-sm-9">
                                  <textarea class="form-control" id="tipo_consulta" name="tipo_consulta"></textarea>
                                  <span class="help-block"></span>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 control-label">A cuenta:</label>
                              <div class="col-sm-2">
                                  <div class="input-group date">
                                      <input type="text" id="a_cuenta" name="a_cuenta" class="form-control">
                                      <span class="help-block"></span>
                                  </div>
                              </div>
                              <!-- /.col-lg-6 -->
                              <label class="col-sm-1 control-label">Saldo:</label>
                              <div class="col-sm-2">
                                  <div class="input-group date">
                                      <input type="text" id="saldo" name="saldo" class="form-control">
                                      <span class="help-block"></span>
                                  </div>
                              </div>
                              <!-- /.col-lg-6 -->
                              <label class="col-sm-2 control-label">Total:</label>
                              <div class="col-sm-2">
                                  <div class="input-group date">
                                      <input type="text" id="total" name="total" class="form-control">
                                      <span class="help-block"></span>
                                  </div>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 control-label">Abogado</label>
                              <div class="col-sm-9">
                                  <select name="lawyer_id" id="lawyer_id" class="form-control">
                                      <option value="">Seleccione Abogado</option>
                                      <?php foreach ($lawyer as $value) {?>
                                      <option value="<?php echo $value->lawyer_id; ?>">
                                          <?php echo $value->first_name . ' ' . $value->last_name; ?></option>
                                      <?php }?>
                                  </select>
                                  <span class="help-block"></span>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 control-label">Estado</label>
                              <div class="col-sm-9">
                                  <select class="form-control" name="estado" id="estado">
                                      <option value="Pendiente">Pendiente</option>
                                      <option value="Cancelada">Cancelada</option>
                                      <option value="Archivado">Archivado</option>
                                  </select>
                                  <span class="help-block"></span>
                              </div>
                          </div>
                      </form>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" id="btnconsulta" onclick="save_consulta()" class="btn btn-success"
                      data-dismiss="modal">Guardar</button>
                  <button type="button" class="btn btn-warning" data-dismiss="modal">Cerrar</button>
              </div>
          </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <!-- Bootstrap modal pago consulta -->
  <div class="modal fade" id="modal_pagar_consulta" role="dialog" data-backdrop="static">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h3 class="modal-title"></h3>
              </div>
              <div class="modal-body form">
                  <div class="modal-body">
                      <div class="error"></div>
                      <form action="#" class="form-horizontal" id="pagar_consulta">
                          <input type="hidden" name="consulta_pag_id" id="consulta_pag_id">
                          <div class="form-group">
                              <label class="col-sm-2 control-label">Fecha</label>
                              <div class="col-sm-4">
                                  <div class="input-group date" id='date_start'>
                                      <input type="text" id="fecha_pag" name="fecha_pag" value="<?php echo date('Y-m-d'); ?>"
                                          class="form-control">
                                      <span class="input-group-addon">
                                          <span class="glyphicon glyphicon-calendar"></span>
                                      </span>
                                  </div>
                              </div>
                              <label class="col-sm-2 control-label">Hora</label>
                              <div class="col-sm-3">
                                  <div class="input-group date" id="time_start">
                                      <input type="text" id="hora_pag" name="hora_pag" value="<?php echo date('h:i:s'); ?>"
                                          class="form-control">
                                      <span class="input-group-addon">
                                          <span class="glyphicon glyphicon-time"></span>
                                      </span>
                                  </div>
                              </div>
                          </div>
                          <div class="form-group" style="display: none;">
                              <label class="col-sm-2 control-label">Tipo de consulta</label>
                              <div class="col-sm-9">
                                  <textarea class="form-control" id="tipo_consulta_pag" name="tipo_consulta_pag"></textarea>
                                  <span class="help-block"></span>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 control-label">A cuenta:</label>
                              <div class="col-sm-2">
                                  <div class="input-group">
                                      <input type="text" id="a_cuenta1" name="a_cuenta1" class="form-control">
                                      <span class="help-block"></span>
                                  </div>
                              </div>
                              <!-- /.col-lg-6 -->
                              <label class="col-sm-1 control-label">Saldo:</label>
                              <div class="col-sm-2">
                                  <div class="input-group">
                                      <input type="text" id="saldo1" name="saldo1" class="form-control">
                                      <span class="help-block"></span>
                                  </div>
                              </div>
                              <!-- /.col-lg-6 -->
                              <label class="col-sm-2 control-label">Total:</label>
                              <div class="col-sm-2">
                                  <div class="input-group">
                                      <input type="text" id="total1" name="total1" class="form-control">
                                      <span class="help-block"></span>
                                  </div>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 control-label">Pagar</label>
                              <div class="col-sm-9">
                                  <input type="text" id="pagar" name="pagar" class="form-control">
                                  <span class="help-block"></span>
                              </div>
                          </div>
                          <div class="form-group" style="display: none;">
                              <label class="col-sm-2 control-label">Abogado</label>
                              <div class="col-sm-9">
                                  <select name="lawyer_pag_id" id="lawyer_pag_id" class="form-control">
                                      <option value="">Seleccione Abogado</option>
                                      <?php foreach ($lawyer as $value) {?>
                                      <option value="<?php echo $value->lawyer_id; ?>">
                                          <?php echo $value->first_name . ' ' . $value->last_name; ?></option>
                                      <?php }?>
                                  </select>
                                  <span class="help-block"></span>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 control-label">Estado</label>
                              <div class="col-sm-9">
                                  <select class="form-control" name="estado_pag" id="estado_pag">
                                      <option value="Pendiente">Pendiente</option>
                                      <option value="Cancelada">Cancelada</option>
                                      <option value="Archivado">Archivado</option>
                                  </select>
                                  <span class="help-block"></span>
                              </div>
                          </div>
                      </form>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" id="btnconsulta" onclick="save_consulta()" class="btn btn-success"
                      data-dismiss="modal">Guardar</button>
                  <button type="button" class="btn btn-warning" data-dismiss="modal">Cerrar</button>
              </div>
          </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <!-- Bootstrap modal pago consulta -->
  <div class="modal fade" id="modal_print" tabindex="-1" role="dialog"  aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Descargar</h4>
           </div>
         <div class="modal-body" id="ipdf">

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
        </div><!-- /.modal-content -->
     </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->


  <!-- Bootstrap modal Tramite-->
  <div class="modal fade" id="modal_tramite" role="dialog" data-backdrop="static">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h3 class="modal-title"></h3>
              </div>
              <div class="modal-body form">
                  <div class="modal-body">
                      <div class="error"></div>
                      <form action="#" class="form-horizontal" id="crud_tramite">
                          <input type="hidden" name="tramite_id" id="tramite_id">
                          <div class="form-group">
                              <input type="hidden" name="customer_tram_id" id="customer_tram_id" value="<?php echo $customer->customer_id; ?>">
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 control-label">Fecha</label>
                              <div class="col-sm-4">
                                  <div class="input-group date" id='date_start'>
                                      <input type="text" id="fecha_tram" name="fecha_tram" value="<?php echo date('Y-m-d'); ?>"
                                          class="form-control">
                                      <span class="input-group-addon">
                                          <span class="glyphicon glyphicon-calendar"></span>
                                      </span>
                                  </div>
                              </div>
                              <label class="col-sm-2 control-label">Hora</label>
                              <div class="col-sm-3">
                                  <div class="input-group date" id="time_start">
                                      <input type="text" id="hora_tram" name="hora_tram" value="<?php echo date('h:i:s'); ?>"
                                          class="form-control">
                                      <span class="input-group-addon">
                                          <span class="glyphicon glyphicon-time"></span>
                                      </span>
                                  </div>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 control-label">Tipo de consulta</label>
                              <div class="col-sm-9">
                                  <textarea class="form-control" id="tipo_tramite" name="tipo_tramite"></textarea>
                                  <span class="help-block"></span>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 control-label">A cuenta:</label>
                              <div class="col-sm-2">
                                  <div class="input-group date">
                                      <input type="text" id="a_cuenta_tram" name="a_cuenta_tram" class="form-control">
                                      <span class="help-block"></span>
                                  </div>
                              </div>
                              <!-- /.col-lg-6 -->
                              <label class="col-sm-1 control-label">Saldo:</label>
                              <div class="col-sm-2">
                                  <div class="input-group date">
                                      <input type="text" id="saldo_tram" name="saldo_tram" class="form-control">
                                      <span class="help-block"></span>
                                  </div>
                              </div>
                              <!-- /.col-lg-6 -->
                              <label class="col-sm-2 control-label">Total:</label>
                              <div class="col-sm-2">
                                  <div class="input-group date">
                                      <input type="text" id="total_tram" name="total_tram" class="form-control">
                                      <span class="help-block"></span>
                                  </div>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 control-label">Abogado</label>
                              <div class="col-sm-9">
                                  <select name="lawyer_tram_id" id="lawyer_tram_id" class="form-control">
                                      <option value="">Seleccione Abogado</option>
                                      <?php foreach ($lawyer as $value) {?>
                                      <option value="<?php echo $value->lawyer_id; ?>">
                                          <?php echo $value->first_name . ' ' . $value->last_name; ?></option>
                                      <?php }?>
                                  </select>
                                  <span class="help-block"></span>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 control-label">Estado</label>
                              <div class="col-sm-9">
                                  <select class="form-control" name="estado_tram" id="estado_tram">
                                      <option value="Pendiente">Pendiente</option>
                                      <option value="Cancelada">Cancelada</option>
                                      <option value="Archivado">Archivado</option>
                                  </select>
                                  <span class="help-block"></span>
                              </div>
                          </div>
                      </form>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" id="btntramite" onclick="save_tramite()" class="btn btn-success"
                      data-dismiss="modal">Guardar</button>
                  <button type="button" class="btn btn-warning" data-dismiss="modal">Cerrar</button>
              </div>
          </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <!-- Bootstrap modal pago tramite -->
  <div class="modal fade" id="modal_pagar_tramite" role="dialog" data-backdrop="static">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h3 class="modal-title"></h3>
              </div>
              <div class="modal-body form">
                  <div class="modal-body">
                      <div class="error"></div>
                      <form action="#" class="form-horizontal" id="pagar_tramite">
                          <input type="hidden" name="tramite_pag_id" id="tramite_pag_id">
                          <div class="form-group">
                              <label class="col-sm-2 control-label">Fecha</label>
                              <div class="col-sm-4">
                                  <div class="input-group date" id='date_start'>
                                      <input type="text" id="fecha_pag_tram" name="fecha_pag_tram" value="<?php echo date('Y-m-d'); ?>"
                                          class="form-control">
                                      <span class="input-group-addon">
                                          <span class="glyphicon glyphicon-calendar"></span>
                                      </span>
                                  </div>
                              </div>
                              <label class="col-sm-2 control-label">Hora</label>
                              <div class="col-sm-3">
                                  <div class="input-group date" id="time_start">
                                      <input type="text" id="hora_pag_tram" name="hora_pag_tram" value="<?php echo date('h:i:s'); ?>"
                                          class="form-control">
                                      <span class="input-group-addon">
                                          <span class="glyphicon glyphicon-time"></span>
                                      </span>
                                  </div>
                              </div>
                          </div>
                          <div class="form-group" style="display: none;">
                              <label class="col-sm-2 control-label">Tipo de consulta</label>
                              <div class="col-sm-9">
                                  <textarea class="form-control" id="tipo_pag_tramite" name="tipo_pag_tramite"></textarea>
                                  <span class="help-block"></span>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 control-label">A cuenta:</label>
                              <div class="col-sm-2">
                                  <div class="input-group">
                                      <input type="text" id="a_cuenta_tram1" name="a_cuenta_tram1" class="form-control">
                                      <span class="help-block"></span>
                                  </div>
                              </div>
                              <!-- /.col-lg-6 -->
                              <label class="col-sm-1 control-label">Saldo:</label>
                              <div class="col-sm-2">
                                  <div class="input-group">
                                      <input type="text" id="saldo_tram1" name="saldo_tram1" class="form-control">
                                      <span class="help-block"></span>
                                  </div>
                              </div>
                              <!-- /.col-lg-6 -->
                              <label class="col-sm-2 control-label">Total:</label>
                              <div class="col-sm-2">
                                  <div class="input-group">
                                      <input type="text" id="total_tram1" name="total_tram1" class="form-control">
                                      <span class="help-block"></span>
                                  </div>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 control-label">Pagar</label>
                              <div class="col-sm-9">
                                  <input type="text" id="pagar_tram" name="pagar_tram" class="form-control">
                                  <span class="help-block"></span>
                              </div>
                          </div>
                          <div class="form-group" style="display: none;">
                              <label class="col-sm-2 control-label">Abogado</label>
                              <div class="col-sm-9">
                                  <select name="lawyer_pag_tram_id" id="lawyer_pag_tram_id" class="form-control">
                                      <option value="">Seleccione Abogado</option>
                                      <?php foreach ($lawyer as $value) {?>
                                      <option value="<?php echo $value->lawyer_id; ?>">
                                          <?php echo $value->first_name . ' ' . $value->last_name; ?></option>
                                      <?php }?>
                                  </select>
                                  <span class="help-block"></span>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 control-label">Estado</label>
                              <div class="col-sm-9">
                                  <select class="form-control" name="estado_pag_tram" id="estado_pag_tram">
                                      <option value="Pendiente">Pendiente</option>
                                      <option value="Cancelada">Cancelada</option>
                                      <option value="Archivado">Archivado</option>
                                  </select>
                                  <span class="help-block"></span>
                              </div>
                          </div>
                      </form>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" id="btntramite" onclick="save_tramite()" class="btn btn-success"
                      data-dismiss="modal">Guardar</button>
                  <button type="button" class="btn btn-warning" data-dismiss="modal">Cerrar</button>
              </div>
          </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <!-- Bootstrap modal pago consulta -->
  <div class="modal fade" id="modal_print_tramite" tabindex="-1" role="dialog"  aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Descargar</h4>
           </div>
         <div class="modal-body" id="ipdf_tramite">

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
        </div><!-- /.modal-content -->
     </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

  <!-- Bootstrap modal proceso -->
  <div class="modal fade" id="modal_proceso" role="dialog" data-backdrop="static">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h3 class="modal-title"></h3>
              </div>
              <div class="modal-body form">
                  <div class="modal-body">
                      <div class="error"></div>
                      <form action="#" class="form-horizontal" id="crud_proceso">
                          <input type="hidden" name="proceso_id" id="proceso_id">
                          <div class="form-group">
                              <input type="hidden" name="customer_proc_id" id="customer_proc_id" value="<?php echo $customer->customer_id; ?>">
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 control-label">Fecha</label>
                              <div class="col-sm-4">
                                  <div class="input-group date" id='date_start'>
                                      <input type="text" id="fecha_proc" name="fecha_proc" value="<?php echo date('Y-m-d'); ?>"
                                          class="form-control">
                                      <span class="input-group-addon">
                                          <span class="glyphicon glyphicon-calendar"></span>
                                      </span>
                                  </div>
                              </div>
                              <label class="col-sm-2 control-label">Hora</label>
                              <div class="col-sm-3">
                                  <div class="input-group date" id="time_start">
                                      <input type="text" id="hora_proc" name="hora_proc" value="<?php echo date('h:i:s'); ?>"
                                          class="form-control">
                                      <span class="input-group-addon">
                                          <span class="glyphicon glyphicon-time"></span>
                                      </span>
                                  </div>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 control-label">Tipo de proceso</label>
                              <div class="col-sm-9">
                                  <textarea class="form-control" id="tipo_proceso" name="tipo_proceso"></textarea>
                                  <span class="help-block"></span>
                              </div>
                          </div>
                          <div class="form-group">
                              <!-- /.col-lg-6 -->
                              <label class="col-sm-2 control-label">Expediente:</label>
                              <div class="col-sm-4">
                                  <div class="input-group date">
                                      <input type="text" id="expediente_proc" name="expediente_proc" class="form-control">
                                      <span class="help-block"></span>
                                  </div>
                              </div>
                              <!-- /.col-lg-6 -->
                              <label class="col-sm-2 control-label">Total:</label>
                              <div class="col-sm-3">
                                  <div class="input-group date">
                                      <input type="text" id="total_proc" name="total_proc" class="form-control">
                                      <span class="help-block"></span>
                                  </div>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 control-label">Abogado</label>
                              <div class="col-sm-9">
                                  <select name="lawyer_proc_id" id="lawyer_proc_id" class="form-control">
                                      <option value="">Seleccione Abogado</option>
                                      <?php foreach ($lawyer as $value) {?>
                                      <option value="<?php echo $value->lawyer_id; ?>">
                                          <?php echo $value->first_name . ' ' . $value->last_name; ?></option>
                                      <?php }?>
                                  </select>
                                  <span class="help-block"></span>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-sm-2 control-label">Estado</label>
                              <div class="col-sm-9">
                                  <select class="form-control" name="estado_proc" id="estado_proc">
                                      <option value="Pendiente">Pendiente</option>
                                      <option value="Cancelada">Cancelada</option>
                                      <option value="Archivado">Archivado</option>
                                  </select>
                                  <span class="help-block"></span>
                              </div>
                          </div>
                      </form>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" id="btnproceso" onclick="save_proceso()" class="btn btn-success"
                      data-dismiss="modal">Guardar</button>
                  <button type="button" class="btn btn-warning" data-dismiss="modal">Cerrar</button>
              </div>
          </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <!-- Bootstrap modal cronograma opcional -->
  <div class="modal fade" id="modal_cronograma" role="dialog">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h3 class="modal-title">Person Form</h3>
              </div>
              <div class="modal-body form">
                  <form action="#" id="crud_cronograma" class="form-horizontal">
                      <input type="hidden" value="" name="id"/> 
                      <div class="form-body">
                        <div class="content-process">
                            <div class="col-sm-12">
                                <table class="table table-hover" id="table">
                                    <thead>
                                    <tr>
                                        <th>Nro</th>
                                        <th>Fecha</th>
                                        <th>Cantidad</th>
                                        <th>A cuenta</th>
                                        <th>Sub Total</th>
                                        <th width="50"></th>
                                        <th width="50"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td width="50%"><input type="text" id="articulo0" name="articulo0" class="form-control" autocomplete="on"></td>
                                            <td><input type="text" id="cantidad0" onkeyup="change_qty(0)" value="1" name="cantidad0" class="form-control"></td>
                                            <td><input type="text" id="precio0" onkeyup="change_price(0)" value="0" name="precio0" class="form-control"></td>
                                            <td><input type="text" id="total0" readonly="" name="total0" value="0" class="form-control"></td>
                                            <td data-tabullet-type="save"><button type="button" id="add_item" class="btn btn-success">Agregar</button></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td></td>
                                            <td class="text-right"></td>
                                            <td>Sub Total:<input type="text" id="sub_total" name="sub_total" value="0.00"></td>
                                            <td>IGV:<input type="text" id="igv" name="igv" value="0.00"></td>
                                            <td>Total General:<input type="text" id="precio_total" name="precio_total" value="0.00"></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                      </div>
                  </form>
              </div>
              <div class="modal-footer">
                  <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
              </div>
          </div>
      </div>
  </div>
  <!-- End Bootstrap modal -->

  <!-- Bootstrap modal cronograma de pagos-->
  <div class="modal fade" id="modal_cronograma_pagos" role="dialog">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h3 class="modal-title">Cronograma de pagos</h3>
              </div>
              <div class="modal-body form">
                <form action="#" id="crud_cronograma_pagos" class="form-horizontal">
                  <input type="hidden" value="<?php echo $customer->customer_id; ?>" id="patient_payment_id" name="patient_payment_id"/> 
                  <input type="hidden" value="" id="procesoID" name="procesoID"/> 
                  <div class="form-body">
                    <div class="content-process">
                      <div class="col-sm-12">
                        <table class="tbl-qa table table-bordered" id="cronograma_pago">
                          <thead>
                          <tr>
                            <th class="table-header">#</th>
                            <th class="table-header">Fecha</th>
                            <th class="table-header">Pagar</th>
                            <th class="table-header">Acuenta</th>
                            <th class="table-header">Saldo</th>
                            <th class="table-header">Total</th>
                            <th class="table-header">Acciones</th>
                          </tr>
                          </thead>
                          <tbody id="table-body" >
                            
                          </tbody>
                        </table>
                        <button type="button" id="add-more" onclick="createNew();" class="btn btn-success">Agregar</button>
                        <button type="button" id="add-more" onclick="printJS({ printable: 'cronograma_pago', type: 'html', header: 'Cronograma de Pagos' });" class="btn btn-info">Imprimir Cronograma</button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              </div>
          </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
  </div>
  <!-- End Bootstrap modal -->

  <!-- Bootstrap modal imprmir proceso cronograma -->
  <div class="modal fade" id="modal_print_proceso" tabindex="10" role="dialog"  aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Descargar</h4>
           </div>
         <div class="modal-body" id="ipdf_proceso">

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
        </div><!-- /.modal-content -->
     </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->


  <!-- Bootstrap modal imprmir contrato proceso -->
  <div class="modal fade" id="modal_contrato_print" tabindex="10" role="dialog"  aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Descargar</h4>
           </div>
         <div class="modal-body" id="ipdf_contraro_proceso">

         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
        </div><!-- /.modal-content -->
     </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

  <!--  modal edit -->
  <div id="modal_edit_cita" class="modal fade">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                          class="sr-only">Close</span></button>
                  <h4 class="modal-title"></h4>
              </div>
              <div class="modal-body">
                  <div class="error"></div>
                  <form class="form-horizontal" id="crud-form-event">
                      <input type="hidden" name="event_id" id="event_id">
                      <div class="form-group">
                          <label class="col-sm-2 control-label">Estado</label>
                          <div class="col-sm-9">
                              <select class="form-control" name="status" id="status">
                                  <option value="Pendiente">Pendiente</option>
                                  <option value="Cancelada">Cancelada</option>
                                  <option value="Atendida">Atendida</option>
                                  <option value="No asistió">No asistió</option>
                                  <option value="Reagendada">Reagendada</option>
                                  <option value="Confirmada">Confirmada</option>
                              </select>
                          </div>
                      </div>
                      <div class="form-group">
                          <label class="col-sm-2 control-label">Cliente</label>
                          <div class="col-sm-9">
                              <div class="">
                                  <input type="text" readonly="" class="form-control" id="search_customer"
                                      name="search_customer" placeholder="Buscar">
                              </div>
                              <span class="help-block"></span>
                          </div>
                      </div>
                      <div class="form-group">
                          <label class="col-sm-2 control-label">Descripción</label>
                          <div class="col-sm-9">
                              <textarea class="form-control" id="description" name="description"></textarea>
                          </div>
                      </div>
                      <div class="form-group">
                          <label class="col-sm-2 control-label">Fecha</label>
                          <div class="col-sm-4">
                              <div class="input-group date" id='date_start'>
                                  <input type="text" id="start" name="start" class="form-control">
                                  <span class="input-group-addon">
                                      <span class="glyphicon glyphicon-calendar"></span>
                                  </span>
                              </div>
                          </div>
                      </div>
                      <div class="form-group">
                          <!-- /.col-lg-6 -->
                          <label class="col-sm-2 control-label">Hora</label>
                          <div class="col-sm-3">
                              <div class="input-group date" id="time_start">
                                  <input type="text" id="start_time" name="start_time" class="form-control">
                                  <span class="input-group-addon">
                                      <span class="glyphicon glyphicon-time"></span>
                                  </span>
                              </div>
                          </div>
                          <!-- /.col-lg-6 -->
                          <label class="col-sm-2 control-label">Hora Fin</label>
                          <div class="col-sm-3">
                              <div class="input-group date" id="time_end">
                                  <input type="text" id="end_time" name="end_time" class="form-control">
                                  <span class="input-group-addon">
                                      <span class="glyphicon glyphicon-time"></span>
                                  </span>
                              </div>
                          </div>

                      </div>
                      <div class="form-group">
                          <label class="col-sm-2 control-label">Abogado</label>
                          <div class="col-sm-9">
                              <select name="doctor_id" id="doctor_id" class="form-control">
                                  <option value="">Seleccione Abogado</option>
                                  <?php foreach ($odontologo as $value) {?>
                                  <option value="<?php echo $value->doctor_id; ?>">
                                      <?php echo $value->first_name . ' ' . $value->last_name; ?></option>
                                  <?php }?>
                              </select>
                              <span class="help-block"></span>
                          </div>
                      </div>

                  </form>
              </div>
              <div class="modal-footer cita">
                  <button type="button" class="btn btn-primary" id="btnUpdate"
                      onclick="updateEvent()">Actualizar</button>
                  <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
              </div>
          </div>
      </div>
  </div>

<!-- Bootstrap modal file -->
<div class="modal fade" id="modal_file" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Person Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_file" class="form-horizontal">
                    <input type="hidden" value="" name="file_id"/> 
                    <input type="hidden" name="customer_id_file" id="customer_id_file" value="<?php echo $customer->customer_id; ?>">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Fecha</label>
                            <div class="col-md-8">
                                <input name="date_in" placeholder="yyyy-mm-dd" class="form-control datepicker" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Resolución</label>
                            <div class="col-md-8">
                                <input name="resolucion" placeholder="Resolución" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Acto</label>
                            <div class="col-md-8">
                                <input name="acto" placeholder="Acto" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Folio</label>
                            <div class="col-md-8">
                                <input name="folio" placeholder="Folio" class="form-control" type="text">
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Sumilla</label>
                            <div class="col-md-8">
                                <textarea name="sumilla" placeholder="Sumilla" class="form-control"></textarea>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group" id="photo-preview">
                            <label class="control-label col-md-3">Archivo</label>
                            <div class="col-md-8">
                                (No hay archivo)
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3" id="label-photo">Cargar Archivo </label>
                            <div class="col-md-8">
                                <input name="photo" type="file">
                                <span class="help-block"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSaveFile" onclick="save_files()" class="btn btn-primary">Guardar</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- End Bootstrap modal -->

<!-- Bootstrap modal file -->
<div class="modal fade" id="modal_file_view" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title"></h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_view_file" class="form-horizontal">
                    <div class="form-body">
                        <table class="table table-bordered">

                        </table>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Cerrar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- End Bootstrap modal -->

  <!--  modal add_contraria -->
  <div id="modal_add_contraria" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div class="error"></div>

                <form class="form-horizontal" id="add_contraria">
                  <input type="hidden" name="person_id" id="person_id">

                  <div class="form-group">
                      <label class="col-sm-2 control-label">Cliente</label>
                      <div class="col-sm-9">
                        <div class="input-group ui-widget date">
                            <input type="text" class="form-control" id="search_patient" placeholder="Buscar">
                            <span class="input-group-addon" id='new_contraria'>
                                <span class="glyphicon glyphicon-plus"></span>
                            </span>
                        </div>
                        <span class="help-block"></span>
                      </div>
                      <input type="hidden" name="patient_id" id="patient_id">
                  </div>

                </form>
            </div>
            <div class="modal-footer cita">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
  </div>

  <!--  modal add_new_contraria -->
  <div id="modal_new_contraria" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
              <div class="error2"></div>

                <form class="form-horizontal" id="form_new_contraria">
                  <div class="form-group">
                      <label class="control-label col-md-2">Nombres</label>
                      <div class="col-md-9">
                          <input name="first_name" id="first_name" placeholder="Nombres" class="form-control" type="text">
                          <span class="help-block"></span>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="control-label col-md-2">Apellidos</label>
                      <div class="col-md-9">
                          <input name="last_name" id="last_name" placeholder="Apellidos" class="form-control" type="text">
                          <span class="help-block"></span>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="control-label col-md-2">E-mail</label>
                      <div class="col-md-9">
                          <input name="email" id="email" placeholder="E-mail" class="form-control" type="text">
                          <span class="help-block email"></span>
                      </div>
                  </div>
                  <div class="form-group">
                      <label class="control-label col-md-2">Genero</label>
                      <div class="col-md-9">
                          <select name="gender" id="gender" class="form-control">
                              <option value="">--Selecione Genero--</option>
                              <option value="1">Masculino</option>
                              <option value="0">Femenino</option>
                          </select>
                          <span class="help-block"></span>
                      </div>
                  </div>
                  <input type="hidden" name="cust_type" id="cust_type" value="0">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="add-contraria" class="btn btn-success">Agregar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
  </div>
