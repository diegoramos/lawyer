<?php
class Module extends CI_Model 
{
    function __construct()
    {
        parent::__construct();
    }
	
	function get_module_name($module_id)
	{
		$query = $this->db->get_where('modules', array('module_id' => $module_id), 1);
		
		if ($query->num_rows() ==1)
		{
			$row = $query->row();
			return lang($row->name_lang_key);
		}
		
		$this->lang->load('error');
		return lang('error_unknown');
	}
	
	function get_module_desc($module_id)
	{
		$query = $this->db->get_where('modules', array('module_id' => $module_id), 1);
		if ($query->num_rows() ==1)
		{
			$row = $query->row();
			return lang($row->desc_lang_key);
		}
		$this->lang->load('error');
		return lang('error_unknown');	
	}
	
	function get_all_modules()
	{
		$this->db->from('modules');
		$this->db->order_by("sort", "asc");
		return $this->db->get();		
	}
	
	function get_allowed_modules($person_id)
	{
		$this->db->from('modules');
		$this->db->join('permissions','permissions.module_id=modules.module_id');
		$this->db->where("permissions.person_id",$person_id);
		$this->db->order_by("sort", "asc");
		return $this->db->get();		
	}

	/*
	Determins whether the employee specified employee has access the specific module.
	*/
	function has_module_permission($module_id,$person_id)
	{
		//if no module_id is null, allow access
		if($module_id==null)
		{
			return true;
		}
		
		static $cache;
		
		if (isset($cache[$module_id.'|'.$person_id]))
		{
			return $cache[$module_id.'|'.$person_id];
		}
		$query = $this->db->get_where('permissions', array('person_id' => $person_id,'module_id'=>$module_id), 1);
		$cache[$module_id.'|'.$person_id] = $query->num_rows() == 1;
		return $cache[$module_id.'|'.$person_id];
	}
	
	function has_module_action_permission($module_id, $action_id, $person_id)
	{
		//if no module_id is null, allow access
		if($module_id==null)
		{
			return true;
		}
		
		static $cache;
		
		if (isset($cache[$module_id.'|'.$action_id.'|'.$person_id]))
		{
			return $cache[$module_id.'|'.$action_id.'|'.$person_id];
		}
		
		$query = $this->db->get_where('permissions_actions', array('person_id' => $person_id,'module_id'=>$module_id,'action_id'=>$action_id), 1);
		$cache[$module_id.'|'.$action_id.'|'.$person_id] =  $query->num_rows() == 1;
		return $cache[$module_id.'|'.$action_id.'|'.$person_id];
	}
}
?>
