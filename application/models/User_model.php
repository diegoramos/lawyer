<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

   var $table = 'users';

   var $column_order = array('first_name','last_name','username','email','status',null); //set column field database for datatable orderable
   var $column_search = array('first_name','last_name'); //set column field database for datatable searchable just firstname , lastname , address are searchable
   var $order = array('user_id' => 'desc'); // default order 

   public function checkLogin()
   {
        $username=$this->input->post('username',true);
        $password=$this->input->post('password',true);
        $this->db->select('*');
        $this->db->from('users');
        $this->db->join('peoples', 'peoples.person_id = users.person_id');
        $this->db->where('username', $username);
        $this->db->where('password', $password);

        return $this->db->get()->row();
   }
   public function checkLogin_lawyer()
   {
        $username=$this->input->post('username',true);
        $password=$this->input->post('password',true);
        $this->db->select('*');
        $this->db->from('lawyer');
        $this->db->join('peoples', 'peoples.person_id = lawyer.person_id');
        $this->db->where('username', $username);
        $this->db->where('password', $password);

        return $this->db->get()->row();
   }

   private function _get_datatables_query()
   {
    
      $this->db->select('*');
      $this->db->from('peoples p');
      $this->db->join('users u', 'u.person_id = p.person_id');
      $this->db->where('u.deleted', 0);

      //$this->db->from($this->table);

      $i = 0;

      foreach ($this->column_search as $item) // loop column 
      {
      if($_POST['search']['value']) // if datatable send POST for search
      {
        
        if($i===0) // first loop
        {
          $this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
          $this->db->like($item, $_POST['search']['value']);
        }
        else
        {
          $this->db->or_like($item, $_POST['search']['value']);
        }

        if(count($this->column_search) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
      }

      if(isset($_POST['order'])) // here order processing
      {
      $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
      } 
      else if(isset($this->order))
      {
      $order = $this->order;
      $this->db->order_by(key($order), $order[key($order)]);
      }
   }

   function get_datatables()
   {
    $this->_get_datatables_query();
    if($_POST['length'] != -1)
    $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result();
   }

   function count_filtered()
   {
    $this->_get_datatables_query();
    $query = $this->db->get();
    return $query->num_rows();
   }

   public function count_all()
   {
    $this->db->from($this->table);
    return $this->db->count_all_results();
   }

   public function get_by_id($id)
   {
    $this->db->select('*');
    $this->db->from('peoples p');
    $this->db->join('users u', 'p.person_id = u.person_id');
    $this->db->where('p.person_id',$id);
    $query = $this->db->get();

    return $query->row();
   }

   public function get_id($id)
   {
      $this->db->select('person_id');
      $this->db->from($this->table);
      $this->db->where('person_id',$id);
      $this->db->where('status','1');
      $query = $this->db->get();
      return $query->row();  
   }

   public function save_user($data)
   {
      $this->db->insert($this->table, $data);
      return $this->db->insert_id();
   }

   public function update_user($where, $data)
   {
    $this->db->update($this->table, $data, $where);
    return $this->db->affected_rows();
   }

   public function get_user($id)
   {
      $this->db->select('first_name,last_name,dni,birth_date,gender,email,phone,address,date_reg,username,status');
      $this->db->from('users u');
      $this->db->join('peoples p', 'u.person_id = p.person_id');
      $this->db->where('u.person_id',$id);
      $this->db->where('u.deleted', 1);
      $query = $this->db->get();
      return $query->result();
   }

   public function delete_by_id($id)
   {
      $data = array('deleted' => '1' );
      $this->db->where('person_id', $id);
      $this->db->update($this->table,$data);
   }

}

/* End of file User_model.php */
/* Location: ./application/models/User_model.php */