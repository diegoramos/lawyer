<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lawyer_model extends CI_Model {

   var $table = 'lawyer';

   var $column_order = array('first_name','last_name','code','speciality','username','email','status',null); //set column field database for datatable orderable
   var $column_search = array('first_name','last_name'); //set column field database for datatable searchable just firstname , lastname , address are searchable
   var $order = array('lawyer_id' => 'desc'); // default order 

   private function _get_datatables_query()
   {
    
      $this->db->select('*');
      $this->db->from('peoples p');
      $this->db->join('lawyer l', 'l.person_id = p.person_id');
      $this->db->where('l.deleted', 0);

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

   public function get_lawyer_login_id($person_id){
      $this->db->select('lawyer_id');
      $this->db->from('lawyer');
      if ($person_id>0) {
        $this->db->where('person_id', $person_id);
      }
      $this->db->where('deleted', '0');
      $this->db->limit(1);
      $result=$this->db->get()->row();
      return $result->lawyer_id;
   }

   function get_all($deleted = 0,$limit=10000, $offset=0)
  { 
    if (!$deleted)
    {
      $deleted = 0;
    }
    $this->db->select('d.lawyer_id,CONCAT(p.first_name," ",p.last_name) as nombres');
    $this->db->from('lawyer d');
    $this->db->join('peoples p', 'd.person_id = p.person_id');
    $this->db->where('deleted', $deleted);
    $this->db->limit($limit); 
    $result=$this->db->get();
    return $result;
  }

   public function get_by_id($id)
   {
    $this->db->select('*');
    $this->db->from('peoples p');
    $this->db->join('lawyer d', 'p.person_id = d.person_id');
    $this->db->where('p.person_id',$id);
    $query = $this->db->get();

    return $query->row();
   }
   public function get_other_by_id($id)
   {
    $this->db->select('*');
    $this->db->from('peoples p');
    $this->db->join('users d', 'p.person_id = d.person_id');
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

   public function save_lawyer($data)
   {
      $this->db->insert($this->table, $data);
      return $this->db->insert_id();
   }

   public function update_lawyer($where, $data)
   {
    $this->db->update($this->table, $data, $where);
    return $this->db->affected_rows();
   }

   public function get_lawyer($id)
   {
      $this->db->select('first_name,last_name,dni,birth_date,gender,email,phone,address,date_reg,username,color,status');
      $this->db->from('lawyer d');
      $this->db->join('peoples p', 'd.person_id = p.person_id');
      $this->db->where('d.person_id',$id);
      $this->db->where('d.deleted', 0);
      $query = $this->db->get();
      return $query->result();
   }

   public function delete_by_id($id)
   {
      $data = array('deleted' => '0' );
      $this->db->where('person_id', $id);
      $this->db->update($this->table,$data);
   }

   public function get_range_event_specific_lawyer($start='',$end='',$lawyer_id='',$limit='')
   {
     $where = ' e.start_date BETWEEN '.$this->db->escape($start).' and '.$this->db->escape($end).' and e.lawyer_id='.$lawyer_id.' ';

     $sql = 'SELECT e.event_id,e.start_date,e.status,CONCAT(pe.first_name," ",pe.last_name) as nombres,(SELECT SUM(trans_lab.total_price) FROM transaction_lab trans_lab  WHERE trans_lab.event_id=e.event_id) AS total_tratamientos,(SELECT SUM(odo_d.importe) FROM odonto_detalle odo_d INNER JOIN odontogram odo ON odo.odontogram_id=odo_d.odontogram_id WHERE odo.event_id=e.event_id) AS total_odontograma,(SELECT SUM(pay.total) FROM payments pay WHERE pay.event_id=e.event_id) AS total_pagado,(((SELECT SUM(trans_lab.total_price) FROM transaction_lab trans_lab WHERE trans_lab.event_id=e.event_id)+(SELECT SUM(odo_d.importe) FROM odonto_detalle odo_d INNER JOIN odontogram odo ON odo.odontogram_id=odo_d.odontogram_id WHERE odo.event_id=e.event_id))-(SELECT SUM(pay.total) FROM payments pay WHERE pay.event_id=e.event_id)) AS por_pagar FROM events e INNER JOIN patients p ON p.patient_id=e.patient_id INNER JOIN peoples pe ON pe.person_id=p.person_id WHERE '.$where.' ORDER BY e.event_id DESC '.$limit.'';

      $query=$this->db->query($sql);
      return $query->result();
   }

   public function count_all_events($start='',$end='',$lawyer_id=''){
      $where = ' e.start_date BETWEEN '.$this->db->escape($start).' and '.$this->db->escape($end).' and e.lawyer_id='.$lawyer_id.' ';
      $sql = 'SELECT count(e.event_id) as cant FROM events e WHERE '.$where.' ';
      $query=$this->db->query($sql);
      return $query->row();
   }

   public function get_range_event_group_lawyer($start='',$end=''){
      $where = ' e.start_date BETWEEN '.$this->db->escape($start).' and '.$this->db->escape($end).' ';
      $sql = 'SELECT  count(e.event_id) as cantidad,e.lawyer_id,CONCAT(pe.first_name," ",pe.last_name) as nombres,(SELECT SUM(trans_lab.total_price) FROM transaction_lab trans_lab INNER JOIN events ev ON ev.event_id=trans_lab.event_id  WHERE ev.lawyer_id=e.lawyer_id GROUP BY ev.lawyer_id) AS total_tratamientos,(SELECT SUM(odo_d.importe) FROM odonto_detalle odo_d INNER JOIN odontogram odo ON odo.odontogram_id=odo_d.odontogram_id INNER JOIN events ev ON ev.event_id=odo.event_id WHERE ev.lawyer_id=e.lawyer_id) AS total_odontograma,(SELECT SUM(pay.total) FROM payments pay INNER JOIN events ev ON ev.event_id=pay.event_id WHERE ev.lawyer_id=e.lawyer_id) AS total_pagado,(((SELECT SUM(trans_lab.total_price) FROM transaction_lab trans_lab INNER JOIN events ev ON ev.event_id=trans_lab.event_id  WHERE ev.lawyer_id=e.lawyer_id GROUP BY ev.lawyer_id)+(SELECT SUM(odo_d.importe) FROM odonto_detalle odo_d INNER JOIN odontogram odo ON odo.odontogram_id=odo_d.odontogram_id INNER JOIN events ev ON ev.event_id=odo.event_id WHERE ev.lawyer_id=e.lawyer_id))-(SELECT SUM(pay.total) FROM payments pay INNER JOIN events ev ON ev.event_id=pay.event_id WHERE ev.lawyer_id=e.lawyer_id)) AS por_pagar  FROM events e INNER JOIN lawyer law ON law.lawyer_id=e.lawyer_id INNER JOIN peoples pe ON pe.person_id=law.person_id WHERE '.$where.' GROUP BY  e.lawyer_id';
      $query=$this->db->query($sql);
      return $query->result();
   }

}

/* End of file Lawyer_model.php */
/* Location: ./application/models/Lawyer_model.php */