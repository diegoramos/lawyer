<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_model extends CI_Model {

	var $table = 'payments';

	public function get_payment($id)
	{
		$this->db->from($this->table);
		$this->db->where('customer_id',$id);
		$data = $this->db->get();
		return $data->result_array();
	}

	public function save_payment($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function return_last_payment($id='')
	{
		$this->db->from($this->table);
		$this->db->where('payment_id',$id);
		$query = $this->db->get();
		return $query->row_array();
	}

	public function update_payment($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}
	
	public function delete_by_id($id)
	{
		$this->db->where('payment_id', $id);
		$this->db->delete($this->table);
	}

	public function get_payment_proc($id,$proc_id)
	{
		$this->db->from($this->table);
		$this->db->where('customer_id',$id);
		$this->db->where('proceso_id',$proc_id);
		$data = $this->db->get();
		return $data->result_array();
	}

	public function get_by_id($id)
	{
		$this->db->select('*');
		$this->db->from('payments pay');
		$this->db->join('customers cus', 'cus.customer_id = pay.customer_id', 'left');
		$this->db->join('procesos proc', 'proc.customer_proc_id = cus.customer_id', 'left');
		$this->db->join('peoples peo', 'peo.person_id = cus.person_id', 'left');
		$this->db->where('payment_id',$id);
		$query = $this->db->get();
		return $query->row();
	}
}

/* End of file Payment_model.php */
/* Location: ./application/models/Payment_model.php */