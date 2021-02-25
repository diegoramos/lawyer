<style type="text/css">
  ul.ui-autocomplete {
    z-index: 1100;
  }
</style>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Agenda de Citas
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Calendar</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- /.col -->
        <div class="col-md-12">
          <div class="box box-primary">
            <div class="box-body no-padding">
              <!-- THE CALENDAR -->
              <div id="calendar">

              </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <div id="modal_new_cita" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div class="error"></div>
                <div class="form-inline " align="center">
                  <a  class="btn btn-lg " id="verPacienteH">Ver historial</a>
                </div>
                <form class="form-horizontal" id="crud-form">
                  <input type="hidden" name="person_id" id="person_id">
                  <div class="form-group" style="display: none;" id="divstatus">
                      <label class="col-sm-2 control-label">Estado</label>
                      <div class="col-sm-9">
                          <select class="form-control"  name="status" id="status">
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
                        <div class="input-group ui-widget date">
                            <input type="text" class="form-control" id="search_patient" placeholder="Buscar">
                            <span class="input-group-addon" id='new_patient'>
                                <span class="glyphicon glyphicon-plus"></span>
                            </span>
                        </div>
                        <span class="help-block"></span>
                      </div>
                      <input type="hidden" name="customer_id" id="customer_id">
                  </div>
                  <div class="form-group">
                      <label class="col-sm-2 control-label">Descripción</label>
                      <div class="col-sm-9">
                          <textarea class="form-control" id="description" name="description"></textarea>
                      </div>
                  </div>
                  
                  <div class="form-group">
                      <label class="col-sm-2 control-label">Abogado</label>
                      <div class="col-sm-9">
                          <select name="lawyer_id" id="lawyer_id" class="form-control">
                                <option value="">Seleccione Abogado</option>
                              <?php foreach ($abogado as $value) {?>
                                <option value="<?php echo $value->lawyer_id; ?>"><?php echo $value->first_name . ' ' . $value->last_name; ?></option>
                              <?php }?>
                          </select>
                          <span class="help-block"></span>
                      </div>
                  </div>
                  <div class="form-group">
                    <!-- /.col-lg-6 -->
                    <label class="col-sm-2 control-label">Hora</label>
                    <div class="col-sm-3">
                      <div class="input-group date" id="time_start">
                        <input type="text" id="start_time" class="form-control">
                          <span class="input-group-addon">
                              <span class="glyphicon glyphicon-time"></span>
                          </span>
                      </div>
                    </div>
                    <label class="col-sm-2 control-label">Hora Fin</label>
                    <div class="col-sm-3">
                      <div class="input-group date" id="time_end">
                        <input type="text" id="end_time" class="form-control">
                          <span class="input-group-addon">
                              <span class="glyphicon glyphicon-time"></span>
                          </span>
                      </div>
                    </div>
                    
                  </div>
                  <div class="form-group">
                    <!-- /.col-lg-6 -->
                    <label class="col-sm-2 control-label">Fecha</label>
                    <div class="col-sm-4">
                      <div class="input-group date" id='date_start'>
                        <input type="text" id="start" class="form-control" >
                          <span class="input-group-addon">
                              <span class="glyphicon glyphicon-calendar"></span>
                          </span>
                      </div>
                    </div>
                  </div>

                </form>
            </div>
            <div class="modal-footer cita">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
  </div>

  <div id="modal_new_patient" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
              <div class="error2"></div>

                <form class="form-horizontal" id="form_patient">
                  <input type="hidden" name="event_id" id="event_id">
                <div class="form-group">
                    <label class="control-label col-md-2">Código</label>
                    <div class="col-md-9">
                        <input name="hist_clinic" id="hist_clinic" value="<?php echo $history_clinic; ?>" class="form-control" type="text" readonly="readonly">
                        <span class="help-block"></span>
                    </div>
                </div>
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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="add-patient" class="btn btn-success">Agregar</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
  </div>
