<?php 
 
function traducir($palabra){
	//////////module actions
	$lang['module_action_delete']='Eliminar';
	$lang['module_action_add_update']='Agregar y Editar';
	$lang['module_action_attend_patient']='Atención al cliente';

	$lang['module_action_update_event']='Editar evento';
	$lang['module_action_delete_event']='Eliminar evento';

	$lang['reports_usuarios']='Usuarios';
	$lang['reports_doctores']='Abogados';
	$lang['reports_citas']='Citas';

	/////////
	$lang['module_action_consulta']='Consultas';
	$lang['module_action_tramite']='Tramites';
	$lang['module_action_proceso']='Procesos';
	$lang['module_action_procesos']='Procesos';

	$lang['module_action_odontograma']='Odontogramas';
	$lang['module_action_presupuesto']='Presupuesto';
	$lang['module_action_pagos']='Pagos';
	$lang['module_action_images']='Imagenes';
	$lang['module_action_recetas']='Recetas';
	$lang['module_action_historia_cli']='Hostiria Clinica';
	$lang['module_action_actividad']='Actividad';


	//////////modules
	$lang['user']='Usuarios';
	$lang['customers']='Clientes';
	$lang['lawyers']='Abogados';
	$lang['calendar']='Agendas';
	$lang['casos']='Casos';
	$lang['treatment']='Tratamientos';
	$lang['anamnesis']='Anamnesis';
	$lang['reports']='Reporte';

	$lang['reports_month_01'] = 'Enero';
	$lang['reports_month_02'] = 'Febrero';
	$lang['reports_month_03'] = 'Marzo';
	$lang['reports_month_04'] = 'Abril';
	$lang['reports_month_05'] = 'Mayo';
	$lang['reports_month_06'] = 'Junio';
	$lang['reports_month_07'] = 'Julio';
	$lang['reports_month_08'] = 'Agosto';
	$lang['reports_month_09'] = 'Septiembre';
	$lang['reports_month_10'] = 'Octubre';
	$lang['reports_month_11'] = 'Noviembre';
	$lang['reports_month_12'] = 'Diciembre';

	return $lang[$palabra];
}

?>