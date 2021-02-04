<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_model extends CI_Model {

	var $table = 'customers';
	var $column_order = array('hist_clinic', 'first_name', 'last_name', 'gender', null); //set column field database for datatable orderable
	var $column_search = array('first_name', 'last_name', 'concat(first_name,\' \',last_name)', 'dni'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('cust.person_id' => 'desc'); // default order

	public function __construct() {
		parent::__construct();
	}

	private function _get_datatables_query() {

		$this->db->select('*');
		$this->db->from('peoples peo');
		$this->db->join('customers cust', 'cust.person_id = peo.person_id');
		$this->db->where('cust.deleted', 1);

		$i = 0;

		foreach ($this->column_search as $item) // loop column
		{
			if ($_POST['search']['value']) // if datatable send POST for search
			{

				if ($i === 0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if (count($this->column_search) - 1 == $i) //last loop
				{
					$this->db->group_end();
				}
				//close bracket
			}
			$i++;
		}

		if (isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($this->order)) {
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables() {
		$this->_get_datatables_query();
		if ($_POST['length'] != -1) {
			$this->db->limit($_POST['length'], $_POST['start']);
		}

		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered() {
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all() {
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function get_id($id) {
		$this->db->select('person_id');
		$this->db->from($this->table);
		$this->db->where('person_id', $id);
		$this->db->where('deleted', '1');
		$query = $this->db->get();
		return $query->row();
	}

	public function get_by_id($id) {
		$this->db->select('*, cust.person_id');
		$this->db->from('peoples peo');
		$this->db->join('customers cust', 'cust.person_id = peo.person_id');
		$this->db->where('peo.person_id', $id);
		$query = $this->db->get();

		return $query->row();
	}

	public function get_by_id_detail($id) {
		$this->db->select('*');
		$this->db->from('peoples peo');
		$this->db->join('customers cust', 'cust.person_id = peo.person_id');
		$this->db->where('peo.person_id', $id);
		$query = $this->db->get();

		return $query->row();
	}
	public function getPatientById($id) {
		$this->db->select('person_id');
		$this->db->from('customers');
		$this->db->where('patient_id', $id);
		$query = $this->db->get();
		$this->db->limit(1);

		return $query->row()->person_id;
	}

	public function save_patient($data) {
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data) {
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function update_patient($where, $data) {
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id) {
		$data = array('deleted' => '0');
		$this->db->where('person_id', $id);
		$this->db->update($this->table, $data);
	}

	function get_last_id($value = '') {
		$this->db->order_by('person_id', 'DESC');
		$query = $this->db->get($this->table, 1, 0);

		return $query->result();
	}
	////////////////////ARCHIVOS E IMAGENES//////////
	//////////////////////////////////////////7
	function saveFilePerson($data) {
		$this->db->insert('archivos_paciente', $data);
	}
	function updateFilePerfil($data)
	{
		$datas = array('photo' => $data['photo']);
		$this->db->where('person_id', $data['person_id']);
		$this->db->update('peoples', $datas);
	}
	function getAllFile($person_id) {
		$this->db->select('name,filesize as size,nombre');
		$this->db->where('person_id', $person_id);
		$this->db->where('deleted', '0');
		$query = $this->db->get('archivos_paciente');
		return $query->result();
	}
	public function delete_file_by_name($name) {
		$data = array('deleted' => '1');
		$this->db->where('name', $name);
		$this->db->update('archivos_paciente', $data);
	}

	public function insert_receta($data)
	{
		$this->db->insert('recetados', $data);
	}
	public function get_all_medicamento_by_id($cita_id)
	{
		$query=$this->db->get_where('recetados', ['event_id'=>$cita_id]);
		return $query->result();
	}

}

/* End of file Patient_model.php */
/* Location: ./application/models/Patient_model.php */