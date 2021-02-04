<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class File_model extends CI_Model {

	var $table = 'files';
	var $column_order = array('date_in','resolucion','sumilla','file_name',null); //set column field database for datatable orderable
	var $column_search = array('resolucion','sumilla','file_name'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('file_id' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query($customer_id)
	{
		
		$this->db->select('*');
		$this->db->from($this->table);
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
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where('file_id',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function save($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function get_files($id)
	{
	  $this->db->select('*');
	  $this->db->from('files');
	  $this->db->where('file_id',$id);
	  $this->db->where('deleted', 0);
	  $query = $this->db->get();
	  return $query->result();
	}

	public function delete_by_id($id)
	{
		$this->db->where('file_id', $id);
		$this->db->delete($this->table);
	}

}

/* End of file File_model.php */
/* Location: ./application/models/File_model.php */