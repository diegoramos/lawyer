  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Lista de Abogados</h3>
              <?php if ($add) {  ?>
                <button class="pull-right btn btn-primary" onclick="add_lawyer()"><i class="glyphicon glyphicon-plus"></i>
                Nuevo Abogado
                </button>
                <?php } ?>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="lawyer" class="table table-bordered table-hover">
                <thead>
                <tr>
                  <th>Nombres</th>
                  <th>Apellidos</th>
                  <th>E-mail</th>
                  <th>Especialidad</th>
                  <th>Username</th>
                  <th>Estado</th>
                  <th>Opciones</th>
                </tr>
                </thead>
                <tbody>
     
                </tbody>
                <tfoot>

                </tfoot>
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
<?php $current_employee_editing_self = $this->session->userdata('user_id') == $person_info->person_id; ?>
<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title"></h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="person_id"/> 
                    <div class="form-body">
                        <div class="row">
                            <div role="tabpanel">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li role="presentation" class="active"><a href="#datos_personales" aria-controls="datos_personales" role="tab" data-toggle="tab">Datos Personales</a>
                                    </li>
                                    <li role="presentation"><a href="#datos_contacto" aria-controls="datos_contacto" role="tab" data-toggle="tab">Datos de Contacto</a>
                                    </li>
                                    <li role="presentation"><a href="#datos_adicionales" aria-controls="datos_adicionales" role="tab" data-toggle="tab">Datos Adicionales</a>
                                    </li>
                                    <li role="permisos"><a href="#asignarpermiso" aria-controls="asignarpermiso" role="tab" data-toggle="tab">Permisos</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane active" id="datos_personales">
                                        <br>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Nombres</label>
                                            <div class="col-md-8">
                                                <input name="first_name" placeholder="Nombres" class="form-control" type="text">
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Apellidos</label>
                                            <div class="col-md-8">
                                                <input name="last_name" placeholder="Apellidos" class="form-control" type="text">
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">DNI</label>
                                            <div class="col-md-8">
                                                <input name="dni" id="dni" placeholder="DNI" class="form-control" type="number">
                                                <span class="help-block dni"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Fecha Nacimiento</label>
                                            <div class="col-md-8">
                                                <input name="birth_date" id="birth_date" placeholder="YYYY-MM-DD" class="form-control datepicker" type="text">
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Género</label>
                                            <div class="col-md-8">
                                                <select name="gender" class="form-control">
                                                    <option value="">--Selecione Género--</option>
                                                    <option value="1">Masculino</option>
                                                    <option value="0">Femenino</option>
                                                </select>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3">Fecha de Registro</label>
                                            <div class="col-md-8">
                                                <input type="text" value="<?php echo date('Y-m-d H:i:s');?>" name="date_reg" id="date_reg" class="form-control" type="text" readonly="readonly"/>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="datos_contacto">
                                        <br>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">E-mail</label>
                                            <div class="col-md-8">
                                                <input name="email" id="email" placeholder="E-mail" class="form-control" type="text">
                                                <span class="help-block email"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Teléfono</label>
                                            <div class="col-md-8">
                                                <input name="phone" id="phone" placeholder="Teléfono" class="form-control" type="number">
                                                <span class="help-block phone"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Direccón</label>
                                            <div class="col-md-8">
                                                <input name="address" placeholder="Direccón" class="form-control" type="text">
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="datos_adicionales">
                                        <br>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Codigo</label>
                                            <div class="col-md-8">
                                                <input name="code" placeholder="Codigo" class="form-control" type="text">
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Especialidad</label>
                                            <div class="col-md-8">
                                                <input name="speciality" placeholder="Especialidad" class="form-control" type="text">
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Username</label>
                                            <div class="col-md-8">
                                                <input name="username" placeholder="Username" class="form-control" type="text">
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Password</label>
                                            <div class="col-md-8">
                                                <input type="password" name="password" placeholder="Password" class="form-control"></input>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Color</label>
                                            <div class="col-md-8">
                                                <div class="input-group my-colorpicker">
                                                    <input name="color" value="#ed1f68" class="form-control"></input>
                                                    <div class="input-group-addon">
                                                        <i></i>
                                                    </div>
                                                    <span class="help-block"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Estado</label>
                                            <div class="col-md-8">
                                                <select name="status" class="form-control">
                                                    <option value="">--Selecione Estado--</option>
                                                    <option value="1">Activo</option>
                                                    <option value="0">Inactivo</option>
                                                </select>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div role="tabpanel" class="tab-pane" id="asignarpermiso">
                                        <br>
                                        <?php
                                            foreach($all_modules->result() as $module)
                                            {
                                                $checkbox_options = array(
                                                'name' => 'permissions[]',
                                                'id' => 'permissions'.$module->module_id,
                                                'value' => $module->module_id,
                                                'class' => 'module_checkboxes '
                                                );
                                            ?>
                                            <div class="panel panel-piluku">
                                                    <div class="panel-heading  bg-success">
                                                        <?php echo form_checkbox($checkbox_options).'<label for="permissions'.$module->module_id.'"><span></span></label>'; ?>
                                                        <span class="text-success" style="font-weight: bold;"><?php echo traducir($module->module_id);?>:</span>
                                                        <span class="text-warning"><?php echo "Agregar, actualizar y eliminar ".traducir($module->module_id);?></span>
                                                    </div>
                                                    
                                                    <ul class="list-group">
                                                        <?php
                                                        foreach($this->Module_action->get_module_actions($module->module_id)->result() as $module_action)
                                                        {
                                                            $checkbox_options = array(
                                                            'name' => 'permissions_actions[]',
                                                            'data-module-checkbox-id' => 'permissions'.$module->module_id,
                                                            'class' => 'module_action_checkboxes',
                                                            'id' => 'permissions_actions'.$module_action->module_id."|".$module_action->action_id,
                                                            'value' => $module_action->module_id."|".$module_action->action_id,
                                                           
                                                            );
                                                            ?>
                                                        <li class="list-group-item permission-action-item">
                                                                <?php echo form_checkbox($checkbox_options).'<label for="permissions_actions'.$module_action->module_id."|".$module_action->action_id.'"><span></span></label>'; ?>
                                                                <span class="text-info">
                                                                <?php echo traducir($module_action->action_name_key); ?>
                                                                </span>
                                                        </li>
                                                            <?php } ?>
                                                    </ul>
                                                </div> 

                                            <?php } ?>
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
<!-- /.modal -->

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form_view" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title"></h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_view" class="form-horizontal">
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
<!-- /.modal -->

<script type="text/javascript">
  var base_url = '<?php echo base_url();?>';
</script>