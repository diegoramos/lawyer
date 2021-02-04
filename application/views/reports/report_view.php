<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<!-- Main content -->
	<section class="content">

		<div class="row report-listing">
			<div class="col-md-6  ">
				<div class="panel">
					<div class="panel-body">
						<div class="list-group parent-list">
							
							
							<?php
							if ($this->Module->has_module_action_permission('reports', 'view_doctores', $this->People_model->get_logged_in_employee_info()->person_id))
							{
							?>
								<a href="#" class="list-group-item" id="doctores"><i class="fa fa-user-md"></i>	<?php echo traducir('reports_doctores'); ?></a>
							<?php } ?>
							
							<?php
							//if ($this->Module->has_module_action_permission('reports', 'view_citas', $this->//People_model->get_logged_in_employee_info()->person_id))
							//{
							?>
								<!--<a href="#" class="list-group-item" id="citas"><i class="fa fa-file-text-o"></i>	<?php echo traducir('reports_citas'); ?></a> -->
							<?php //} ?>
							
							<?php
							//if ($this->Module->has_module_action_permission('reports', 'view_usuarios', $this->People_model->get_logged_in_employee_info()->person_id))
							//{
							?>
								<!--<a href="#" class="list-group-item" id="usuarios">
									<i class="fa fa-user"></i>	<?php echo traducir("reports_usuarios");//lang('reports_sales_generator'); ?>
								</a> -->
							<?php //} ?>
						</div>
					</div>
				</div> <!-- /panel -->
			</div>
			<div class="col-md-6" id="report_selection">
				<div class="panel">
					<div class="panel-body child-list">
					<h3 id="right_heading" class="page-header text-info"><i class="icon ti-angle-double-left"></i><?php echo "Has una elección";//lang('reports_make_a_selection')?></h3>
						
						<div class="list-group doctores hidden">
							<a class="list-group-item" href="<?php echo site_url('reports/generate/graphical_doctors');?>" ><i class="fa fa-area-chart"></i> <?php echo "Informes gráficos";//lang('reports_graphical_reports'); ?></a>
							<a class="list-group-item" href="<?php echo site_url('reports/generate/summary_doctor');?>" ><i class="fa fa-file-text"></i> <?php echo "Informes resumidos";//lang('reports_summary_reports'); ?></a>
							<a class="list-group-item" href="<?php echo site_url('reports/generate/specific_doctor');?>" ><i class="fa fa-list-alt"></i> <?php echo "Informes detallados";//lang('reports_detailed_reports'); ?></a>
							<!--
							<a class="list-group-item" href="<?php echo site_url('reports/generate/customers_series');?>" ><i class="fa fa-file-text"></i> <?php echo "Informe de la serie de clientes";//lang('reports_customer_series'); ?></a>
							<a class="list-group-item" href="<?php echo site_url('reports/generate/new_customers');?>" ><i class="fa fa-file-text"></i> <?php echo "Clientes nuevos";//lang('reports_new_customers'); ?></a>
							<a class="list-group-item" href="<?php echo site_url('reports/generate/summary_customers_zip');?>" ><i class="fa fa-file-text"></i> <?php echo "Informe de códigos postales";//lang('reports_zip_code_report'); ?></a>
							<a class="list-group-item" href="<?php echo site_url('reports/generate/graphical_customers_zip');?>" ><i class="fa fa-file-text"></i> <?php echo "Informe gráfico de códigos postales";//lang('reports_graphical_zip_code_report'); ?></a> -->
						</div>
						
						<div class="list-group citas hidden">
							<a class="list-group-item" href="<?php echo site_url('reports/generate/graphical_summary_employees');?>" ><i class="fa fa-area-chart"></i> <?php echo "Informes gráficos";//lang('reports_graphical_reports'); ?></a>
							<a class="list-group-item" href="<?php echo site_url('reports/generate/summary_employees');?>" ><i class="fa fa-file-text"></i> <?php echo "Informes resumidos";//lang('reports_summary_reports'); ?></a>
							<a class="list-group-item" href="<?php echo site_url('reports/generate/specific_employee');?>" ><i class="fa fa-list-alt"></i> <?php echo "Informes detallados";//lang('reports_detailed_reports'); ?></a>
						</div>
						<div class="list-group usuarios hidden">
							<a class="list-group-item" href="<?php echo site_url('reports/generate/graphical_summary_commissions');?>" ><i class="fa fa-area-chart"></i> <?php echo "Informes gráficos";//lang('reports_graphical_reports'); ?></a>
							<a class="list-group-item" href="<?php echo site_url('reports/generate/summary_commissions');?>" ><i class="fa fa-file-text"></i> <?php echo "Informes resumidos";//lang('reports_summary_reports'); ?></a>
							<a class="list-group-item" href="<?php echo site_url('reports/generate/detailed_commissions');?>" ><i class="fa fa-list-alt"></i> <?php echo "Informes detallados";//lang('reports_detailed_reports'); ?></a>
						</div>
						
						
					</div>
				</div> <!-- /panel -->
			</div>
		</div>
	</section>
</div>
