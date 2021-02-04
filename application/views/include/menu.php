    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
          <div class="pull-left image">
            <img src="<?php echo base_url(); ?>assets/dist/img/people.png" class="img-circle" alt="User Image">
          </div>
          <div class="pull-left info">
            <p><?php echo $this->session->userdata('last_name').' '.$this->session->userdata('first_name');?></p>
            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
          </div>
        </div>
        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
          <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                  <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                  </button>
                </span>
          </div>
        </form>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
          <li class="header">MENU PRINCIPAL</li>
          <li class="treeview <?php echo is_menu('dashboard');?>">
            <a href="<?php echo base_url();?>dashboard">
              <i class="fa fa-dashboard"></i> <span>Dashboard</span>
            </a>
          </li>

          <?php foreach($allowed_modules->result() as $module) { ?>

            <li class="treeview <?php echo is_menu($module->module_id);?>">
            <a href="<?php echo base_url().$module->module_id; ?>">
              <i class="<?php if($module->icon!=''){ echo $module->icon;}else{ echo 'fa fa-medkit';}; ?>"></i> <span><?php echo traducir($module->module_id); ?></span>
            </a>
          </li>
          <?php } ?>

            <!--
          <li class="treeview <?php echo is_menu('user');?>">
            <a href="<?php echo base_url(); ?>user">
              <i class="fa fa-users"></i> <span>Usuarios</span>
            </a>
          </li>
          <li class="treeview <?php echo is_menu('doctor');?>">
            <a href="<?php echo base_url(); ?>doctor">
              <i class="fa fa-user-md"></i> <span>Doctores</span>
            </a>
          </li>
          <li class="treeview <?php echo is_menu('patient');?>">
            <a href="<?php echo base_url(); ?>patient">
              <i class="fa fa-users"></i> <span>Pacientes</span>
            </a>
          </li>
          <li class="treeview <?php echo is_menu('calendar');?>">
            <a href="<?php echo base_url(); ?>calendar">
              <i class="fa fa-calendar"></i> <span>Agenda</span>
              <span class="pull-right-container">
                <small class="label pull-right bg-blue"><?php if(isset($events)){ echo $events; }?></small>
              </span>
            </a>
          </li> -->
<!--
          <li class="header">CONFIGURACIÓN</li>

          <li class="treeview <?php echo is_menu('treatment');?>">
            <a href="<?php echo base_url(); ?>treatment">
              <i class="fa fa-medkit"></i> <span>Tratamientos</span>
              <span class="pull-right-container">
                <small class="label pull-right bg-blue"><?php if(isset($treatments)){echo $treatments;}?></small>
              </span>
            </a>
          </li>
          <li class="treeview <?php echo is_menu('anamnesis');?>">
            <a href="<?php echo base_url(); ?>anamnesis">
              <i class="fa fa-heartbeat"></i> <span>Anamnesia</span>
              <span class="pull-right-container">
                <small class="label pull-right bg-blue">20</small>
              </span>
            </a>
          </li>
          <li class="treeview <?php echo is_menu('clinic');?>">
            <a href="<?php echo base_url(); ?>clinic">
              <i class="fa fa-building"></i> <span>Clínica</span>
            </a>
          </li> -->
          
        </ul>
      </section> 
      <!-- /.sidebar -->
    </aside>
