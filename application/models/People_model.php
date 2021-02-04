<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class People_model extends CI_Model {

    var $table = 'peoples';

    public function __construct()
    {
        parent::__construct();

    }

    public function get_by_id($id)
    {
        $this->db->select('*, peo.person_id');
        $this->db->from('peoples peo');
        $this->db->join('customers cus', 'cus.person_id = peo.person_id');
        $this->db->where('peo.person_id',$id);
        $query = $this->db->get();

        return $query->row();
    }

    public function get_by_id_doc($id)
    {
        $this->db->select('*, peo.person_id');
        $this->db->from('peoples peo');
        $this->db->join('lawyer law', 'law.person_id = peo.person_id');
        $this->db->where('peo.person_id',$id);
        $query = $this->db->get();
        return  $query->row();
    }

    public function get_by_id_cu($id)
    {
        $this->db->select('*, peo.person_id');
        $this->db->from('peoples peo');
        $this->db->join('customers cus', 'cus.person_id = peo.person_id');
        $this->db->where('peo.person_id',$id);
        $query = $this->db->get();
        return  $query->row();
    }

    public function get_by_id_us($id)
    {
        $this->db->select('*, peo.person_id');
        $this->db->from('peoples peo');
        $this->db->join('users us', 'us.person_id = peo.person_id');
        $this->db->where('peo.person_id',$id);
        $query = $this->db->get();

        return $query->row();
    }

    public function get_id($id)
    {
        $this->db->select('person_id');
        $this->db->from($this->table);
        $this->db->where('person_id',$id);
        $this->db->where('deleted','1');
        $query = $this->db->get();

        return $query->row();
        
    }
    public function save_people($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
    public function save_usuario($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update_people($where, $data)
    {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }
    
    public function update_usuario($where, $data)
    {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }

    public function delete_by_id($id)
    {
        return TRUE;
        // $data = array('deleted' => '0' );
        // $this->db->where('person_id', $id);
        // $this->db->update($this->table,$data);
    }
    /*
    Gets information about a particular employee
    */
    function get_info_persona($person_id)
    {
        $query = $this->db->get_where('peoples', array('person_id' => $person_id), 1);
        
        if($query->num_rows()==1)
        {
            return $query->row();
        }
        else
        {
            //create object with empty properties.
            $fields = $this->db->list_fields('peoples');
            $person_obj = new stdClass;
            
            foreach ($fields as $field)
            {
                $person_obj->$field='';
            }
            
            return $person_obj;
        }
    }
    function get_info_lawyer($employee_id, $can_cache = TRUE)
    {
        if ($can_cache)
        {
            static $cache = array();
        
            if (isset($cache[$employee_id]))
            {
                return $cache[$employee_id];
            }
        }
        else
        {
            $cache = array();
        }
        $this->db->from('lawyer');   
        $this->db->join('peoples people', 'people.person_id = lawyer.person_id');
        $this->db->where('lawyer.person_id',$employee_id);
        $query = $this->db->get();
        
        if($query->num_rows()==1)
        {
            $cache[$employee_id] = $query->row();
            return $cache[$employee_id];
        }
        else
        {
            //Get empty base parent object, as $employee_id is NOT an employee
            $person_obj=$this->get_info_persona(-1);
            
            //Get all the fields from employee table
            $fields = $this->db->list_fields('lawyer');
            
            //append those fields to base parent object, we we have a complete empty object
            foreach ($fields as $field)
            {
                $person_obj->$field='';
            }
            
            return $person_obj;
        }
    }
    function get_info_user($employee_id, $can_cache = TRUE)
    {
        if ($can_cache)
        {
            static $cache = array();
        
            if (isset($cache[$employee_id]))
            {
                return $cache[$employee_id];
            }
        }
        else
        {
            $cache = array();
        }
        $this->db->from('users');   
        $this->db->join('peoples people', 'people.person_id = users.person_id');
        $this->db->where('users.person_id',$employee_id);
        $query = $this->db->get();
        
        if($query->num_rows()==1)
        {
            $cache[$employee_id] = $query->row();
            return $cache[$employee_id];
        }
        else
        {
            //Get empty base parent object, as $employee_id is NOT an employee
            $person_obj=$this->get_info_persona(-1);
            
            //Get all the fields from employee table
            $fields = $this->db->list_fields('users');
            
            //append those fields to base parent object, we we have a complete empty object
            foreach ($fields as $field)
            {
                $person_obj->$field='';
            }
            
            return $person_obj;
        }
    }

    public function update_permisos(&$permission_data,&$permission_action_data,&$employee_id){
    //First lets clear out any permissions the employee currently has.
        $success=$this->db->delete('permissions', array('person_id' => $employee_id));
        
        //Now insert the new permissions
        if($success)
        {
          foreach($permission_data as $allowed_module)
          {
            $success = $this->db->insert('permissions',
            array(
            'module_id'=>$allowed_module,
            'person_id'=>$employee_id));
          }
        }
        
        //First lets clear out any permissions actions the employee currently has.
        $success=$this->db->delete('permissions_actions', array('person_id' => $employee_id));
        
        //Now insert the new permissions actions
        if($success)
        {
          foreach($permission_action_data as $permission_action)
          {
            list($module, $action) = explode('|', $permission_action);
            $success = $this->db->insert('permissions_actions',
            array(
            'module_id'=>$module,
            'action_id'=>$action,
            'person_id'=>$employee_id));
          }
        }
   }
   function get_logged_in_employee_info()
    {
        if($this->is_logged_in())
        {
            if ($this->session->userdata('is_lawyer')) {
                $ret = $this->get_info_lawyer($this->session->userdata('user_id'));

            } else {
                $ret = $this->get_info_user($this->session->userdata('user_id'));
            }
            return $ret;
        } 
        
        return false;
    }
   function is_logged_in()
    {
        return $this->session->userdata('user_id')!=false;
    }
}

/* End of file Persona_model.php */
/* Location: ./application/models/Persona_model.php */