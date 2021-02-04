<?php
class Secure_area extends CI_Controller 
{
	var $module_id;
	
	/*
	Controllers that are considered secure extend Secure_area, optionally a $module_id can
	be set to also check if a user can access a particular module in the system.
	*/
	function __construct($module_id=null)
	{
		parent::__construct();
		$this->module_id = $module_id;	
		$this->load->model('People_model');

		if(!$this->People_model->is_logged_in())
		{
			redirect('login?continue='.rawurlencode(uri_string().'?'.$_SERVER['QUERY_STRING']));
		}
		
		if(!$this->Module->has_module_permission($this->module_id,$this->People_model->get_logged_in_employee_info()->person_id))
		{
			redirect('no_access/'.$this->module_id);
		}
		//load up global data
		$logged_in_employee_info=$this->People_model->get_logged_in_employee_info();
		$data['allowed_modules']=$this->Module->get_allowed_modules($logged_in_employee_info->person_id);
		$data['user_info']=$logged_in_employee_info;
		$this->load->vars($data);
	}
	
	function check_action_permission($action_id)
	{
		if (!$this->Module->has_module_action_permission($this->module_id, $action_id, $this->People_model->get_logged_in_employee_info()->person_id))
		{
			redirect('no_access/'.$this->module_id);
		}
	}
	function verificar_permiso_accion($action_id)
	{
		if (!$this->Module->has_module_action_permission($this->module_id, $action_id, $this->People_model->get_logged_in_employee_info()->person_id))
		{
			return false;
		}
		return true;
	}	
}
?>