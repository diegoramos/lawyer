<style type="text/css">
	.datepicker {
  border-radius: 4px;
  direction: ltr;
  -webkit-user-select: none;
  -webkit-touch-callout: none;
  }
  /* basicos */
  .datepicker .day{
    border-radius: 4px;
  }
  .datepicker-dropdown {
    top: 0;
    left: 0;
    padding: 5px;
  }
  .datepicker-dropdown:before {
    content: '';
    display: inline-block;
    border-left: 7px solid transparent;
    border-right: 7px solid transparent;
    border-bottom: 7px solid red;
    border-top: 0;
    border-bottom-color: red;
    position: absolute;
  }
  .datepicker-dropdown:after {
    content: '';
    display: inline-block;
    border-left: 6px solid transparent;
    border-right: 6px solid transparent;
    border-bottom: 6px solid #fff;
    border-top: 0;
    position: absolute;
  }
  .datepicker-dropdown.datepicker-orient-left:before {
    left: 6px;
  }
  .datepicker-dropdown.datepicker-orient-left:after {
    left: 7px;
  }
  .datepicker-dropdown.datepicker-orient-right:before {
    right: 6px;
  }
  .datepicker-dropdown.datepicker-orient-right:after {
    right: 7px;
  }
  .datepicker-dropdown.datepicker-orient-bottom:before {
    top: -7px;
  }
  .datepicker-dropdown.datepicker-orient-bottom:after {
    top: -6px;
  }
  .datepicker-dropdown.datepicker-orient-top:before {
    bottom: -7px;
    border-bottom: 0;
    border-top: 7px solid red;
  }
  .datepicker-dropdown.datepicker-orient-top:after {
    bottom: -6px;
    border-bottom: 0;
    border-top: 6px solid red;
  }
  .datepicker table {
    margin: 0;
    user-select: none;
  }
  .datepicker td,
  .datepicker th {
    text-align: center;
    width: 30px;
    height: 30px;
    border: none;
  }
  .datepicker .datepicker-switch,
  .datepicker .prev,
  .datepicker .next,
  .datepicker tfoot tr th {
    cursor: pointer;
  }
  /*.datepicker .datepicker-switch:hover,*/
  /*.datepicker .prev:hover,*/
  /*.datepicker .next:hover,*/
  /*.datepicker tfoot tr th:hover {*/
    /*background: red;*/
    /*border-radius: 4px;*/
  /*}*/
  .datepicker .prev .disabled,
  .datepicker .next .disabled {
    visibility: hidden;
  }
  .datepicker .range-start{
    background: #337ab7 url("../images/range-bg-1.png") top right no-repeat;
    color: #fff;
  }
  .datepicker .range-end{
    background: #337ab7 url("../images/range-bg-2.png") top left no-repeat;
    color: #fff;
  }
  .datepicker  .range-start.range-end{
    background-image: none;
  }
  .datepicker .range{
    background: #d5e9f7;
  }
  /*.datepicker .disabled.day{*/
    /*color:#999;*/
  /*}*/
  /* Hover para dia mes y año*/
  .datepicker .day:hover,
  .datepicker .month:hover,
  .datepicker .year:hover,
  .datepicker .datepicker-switch:hover,
  .datepicker .next:hover,
  .datepicker .prev:hover {
    background-color: #ff8000;
    color: white;
    border-radius: 4px;
  }
  .hover {
    background-color: #ff8000;
    color: white;

  }
  .datepicker .today {
    font-weight:bold;
    color: #1ed443;

  }
  /* Estilos para meses y años */
  .datepicker-months, .datepicker-years{
    width: 213px;

  }
  .datepicker-months td, .datepicker-years td {
    width: auto;
    height: auto;

  }
  .datepicker-months .month, .datepicker-years .year{
    color: #fff;
    background-color: #337ab7;
    border-color: #2e6da4;
    float: left;
    display: block;
    width: 23%;
    height: 46px;
    line-height: 46px;
    margin: 1%;
    cursor: pointer;
    border-radius: 4px;
  }
  .day.active, .start-date-active{
    color: #fff;
    background-color: #337ab7;
    border-color: #2e6da4;
  }
  /* Desactivados */
  .day.disabled, .month.disabled, .year.disabled, .start-date-active.disabled{
    cursor: not-allowed;
    filter: alpha(opacity=65);
    -webkit-box-shadow: none;
    box-shadow: none;
    opacity: .65;
  }
  a:active,
  a:hover {
    outline: 0;
  }
</style>
<div class="content-wrapper">
	<section class="content">
		<div class="row hidden-print">
	        <div class="col-xs-12">
	          <div class="box">
	          	<div class="box-header report-options">
	          		<h3 class="box-title"><?php echo $input_report_title; ?></h3>
					       <?php //if (isset($output_data) && $output_data) { ?>
								<div class="table_buttons pull-right">
									<button type="button" class="btn btn-more expand-collapse" data-toggle="dropdown" aria-expanded="false"><i id="expand-collapse-icon" class="ion-chevron-up"></i></button>
								</div>
					       <?php //} ?>
	          	</div>
	          	<!--  -->
	          	<div id="options" class="box-body" style="display:none;" <?php echo (isset($output_data) && $output_data) ? 'style="display:none;"' : ''; ?>>
				
      					<form class="form-horizontal form-horizontal-mobiles" id="report_input_form" method="get" action="<?php echo site_url('reports/generate/'.$report); ?>">
      						<input type="hidden" name="report_type" id="report_type" value="simple">
      						<input type="hidden" name="with_time" value="1">
      						<input type="hidden" name="end_date_end_of_day" value="0">
      						<div class="form-group">
      								<label for="date_range" class="col-sm-3 col-md-3 col-lg-2 control-label   ">Rango de fecha:</label>
      								<div class="col-sm-3 col-md-3 col-lg-2">
      									<select name="report_date_range_simple" id="report_date_range_simple" class="form-control">
      										<option value="TODAY" <?php echo ($report_date_range_simple=='TODAY')? 'selected="selected"':''; ?>>Hoy</option>
      										<option value="YESTERDAY" <?php echo ($report_date_range_simple=='YESTERDAY')? 'selected="selected"':''; ?>>Ayer</option>
      										<option value="LAST_7" <?php echo ($report_date_range_simple=='LAST_7')? 'selected="selected"':''; ?>>Últimos 7 días</option>
      										<option value="LAST_30" <?php echo ($report_date_range_simple=='LAST_30')? 'selected="selected"':''; ?>>Últimos 30 días</option>
      										<option value="THIS_WEEK" <?php echo ($report_date_range_simple=='THIS_WEEK')? 'selected="selected"':''; ?>>Esta semana</option>
      										<option value="LAST_WEEK" <?php echo ($report_date_range_simple=='LAST_WEEK')? 'selected="selected"':''; ?>>La semana pasada</option>
      										<option value="THIS_MONTH" <?php echo ($report_date_range_simple=='THIS_MONTH')? 'selected="selected"':''; ?>>Este mes</option>
      										<option value="LAST_MONTH" <?php echo ($report_date_range_simple=='LAST_MONTH')? 'selected="selected"':''; ?>>Mes pasado</option>
      										<option value="THIS_QUARTER" <?php echo ($report_date_range_simple=='THIS_QUARTER')? 'selected="selected"':''; ?>>Este trimestre</option>
      										<option value="LAST_QUARTER" <?php echo ($report_date_range_simple=='LAST_QUARTER')? 'selected="selected"':''; ?>>Último trimestre</option>
      										<option value="THIS_YEAR" <?php echo ($report_date_range_simple=='THIS_YEAR')? 'selected="selected"':''; ?>>Este año</option>
      										<option value="LAST_YEAR" <?php echo ($report_date_range_simple=='LAST_YEAR')? 'selected="selected"':''; ?>>Año pasado</option>
      										<option value="ALL_TIME" <?php echo ($report_date_range_simple=='ALL_TIME')? 'selected="selected"':''; ?>>Todos</option>
      										<option value="CUSTOM" <?php echo ($report_date_range_simple=='CUSTOM')? 'selected="selected"':''; ?>>Intervalo de fechas personalizado</option>
      									</select>
      								</div>
      								
      								<div id="report_date_range_complex" class="col-sm-6 col-md-6 col-lg-8 hidden">
      									<div class="row">
      										<div class="col-md-6">
      											<div class="input-group input-daterange" id="reportrange">
      												<span class="input-group-addon bg date-picker">Desde</span>
      									             <input type="text" class="form-control start-date" name="start_date_formatted" id="start_date_formatted" value="">
      									             <input type="hidden" id="start_date" name="start_date" value="">
      									        </div>
      										</div>
      										<div class="col-md-6">
      											<div class="input-group input-daterange" id="reportrange1">
      									        	<span class="input-group-addon bg date-picker">Hasta</span>
      									       		<input type="text" class="form-control end-date" name="end_date_formatted" id="end_date_formatted" value="">
      									       		<input type="hidden" id="end_date" name="end_date" value="">
      									      	</div>	
      										</div>
      									</div>
      								</div>
      						</div>

      						<div class="form-group">
      							<label for="specific_doctor" class="col-sm-3 col-md-3 col-lg-2 control-label ">Elegir Doctor :</label> 
      							<div class="col-sm-9 col-md-2 col-lg-2">
      								<select name="specific_doctor" id="specific_doctor" class="form-control">
      									<?php foreach ($doctors as $key => $value): ?>
      										<option <?php echo ($doctors_id==$value->doctor_id)?'selected="selected"':'' ?> value="<?php echo $value->doctor_id; ?>"><?php echo $value->nombres; ?></option>
      									<?php endforeach ?>
      								</select>
      							</div>
      						</div>
      						<div class="form-actions pull-right">
      							<button type="submit" id="generate_report" class="btn btn-primary submit_button btn-large">Aceptar</button>
      						</div>
      					</form>
				      </div>
	          </div>
	        </div>
	  	</div>
	  	<div class="row">
	        <div class="col-md-3 col-sm-6 col-xs-12">
	          <div class="info-box">
	            <span class="info-box-icon bg-green"><i class="ion-ios-flame"></i></span>

	            <div class="info-box-content">
	              <span class="info-box-text">TRATAMIENTOS</span>
	              <span class="info-box-number">S/. <?php echo $trat; ?></span>
	            </div>
	            <!-- /.info-box-content -->
	          </div>
	          <!-- /.info-box -->
	        </div>
	        <!-- /.col -->
	        <div class="col-md-3 col-sm-6 col-xs-12">
	          <div class="info-box">
	            <span class="info-box-icon bg-green"><i class="ion-ios-flame"></i></span>

	            <div class="info-box-content">
	              <span class="info-box-text">ODONTOGRAMAS</span>
	              <span class="info-box-number">S/. <?php echo $odont; ?></span>
	            </div>
	            <!-- /.info-box-content -->
	          </div>
	          <!-- /.info-box -->
	        </div>
	        <!-- fix for small devices only -->
	        <div class="clearfix visible-sm-block"></div>

	        <div class="col-md-3 col-sm-6 col-xs-12">
	          <div class="info-box">
	            <span class="info-box-icon bg-green"><i class="ion-ios-flame"></i></span>

	            <div class="info-box-content">
	              <span class="info-box-text">PAGOS</span>
	              <span class="info-box-number">S/. <?php echo $pag; ?></span>
	            </div>
	            <!-- /.info-box-content -->
	          </div>
	          <!-- /.info-box -->
	        </div>
	        <!-- /.col -->
	        <div class="col-md-3 col-sm-6 col-xs-12">
	          <div class="info-box">
	            <span class="info-box-icon bg-green"><i class="ion ion-ios-flame"></i></span>

	            <div class="info-box-content">
	              <span class="info-box-text">RESTANTES</span>
	              <span class="info-box-number">S/. <?php echo $rest; ?></span>
	            </div>
	            <!-- /.info-box-content -->
	          </div>
	          <!-- /.info-box -->
	        </div>
	        <!-- /.col -->
	    </div>
	    <div class="row">
	    	<div class="col-xs-12">
	    		<div class="box">
	    			<!--<div class="box-header">
	    				<div class="box-title"></div>
	    			</div>-->
	    			<div class="box-body">
	    				<table class="table table-bordered">
	    					<thead>
	    						<th>Doctor</th>
	    						<th>Número total de citas</th>
	    						<th>Total Tratamientos</th>
	    						<th>Total Odontogramas</th>
	    						<th>Total Pagos</th>
	    						<th>Restante</th>
	    					</thead>
	    					<tbody>
	    						<?php foreach ($events as $key => $value): ?>
		    						<tr>
		    							<td><?php echo $value->nombres; ?></td>
		    							<td><?php echo $value->cantidad; ?></td>
		    							<td><?php echo $value->total_tratamientos; ?></td>
		    							<td><?php echo $value->total_odontograma; ?></td>
		    							<td><?php echo $value->total_pagado; ?></td>
		    							<td><?php echo $value->por_pagar; ?></td>
		    						</tr>
	    						<?php endforeach ?>
	    					</tbody>
	    				</table>
	    			</div>
	    		</div>
	    	</div>
	    </div>
	</section>
</div>