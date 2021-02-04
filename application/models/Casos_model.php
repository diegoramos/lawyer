<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Casos_model extends CI_Model {
	var $table = 'consultas';
	var $column_order = array('fecha','lawyer_id','tipo_consulta','a_cuenta','saldo','total',null); //set column field database for datatable orderable
	var $column_search = array('fecha','tipo_consulta'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('consulta_id' => 'desc'); // default order

	private function _get_datatables_query($customer_id)
	{
		$this->db->select('*');
		$this->db->from('consultas con');
		$this->db->join('lawyer law', 'law.lawyer_id = con.lawyer_id', 'left');
		$this->db->join('peoples peo', 'peo.person_id = law.person_id', 'left');
		$this->db->where('customer_id', $customer_id);
	
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

	function get_datatables($customer_id)
	{
		$this->_get_datatables_query($customer_id);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered($customer_id)
	{
		$this->_get_datatables_query($customer_id);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($customer_id)
	{
		$this->db->where('customer_id', $customer_id);
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		// $this->db->from($this->table);
		// $this->db->where('consulta_id',$id);
		// $query = $this->db->get();
		// return $query->row();
		return $this->db->select('*,peo.first_name cust_first,peo.last_name cust_last,peo.dni cust_dni')
			->from('consultas con')
			->join('lawyer law', 'law.lawyer_id = con.lawyer_id', 'left')
			->join('customers cus', 'cus.customer_id = con.customer_id', 'left')
			->join('peoples peo', 'peo.person_id = cus.person_id', 'left')
			->join('peoples pe', 'pe.person_id = law.person_id', 'left')
			->where('consulta_id', $id)
			->get()->row();
	}

	public function save_consulta($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update_consulta($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$this->db->where('consulta_id', $id);
		$this->db->delete($this->table);
	}
}

/* End of file Casos_model.php */
/* Location: ./application/models/Casos_model.php */