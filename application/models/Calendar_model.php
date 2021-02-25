<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Calendar_model extends CI_Model {
	var $table = 'events';

	var $column_order = array('cu.first_name','end_date','start_time','end_time','name_lawyer','e.status',null); //set column field database for datatable orderable
	var $column_search = array('CONCAT(cu.first_name, " ", cu.last_name)'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('event_id' => 'desc'); // default order
	public function getEvents()
	{
		/*$this->db->select('*');
		$this->db->from('events');
		$this->db->where("events.start BETWEEN ".$_GET['start']." AND ".$_GET['end']);
		$this->db->order_by("start", "asc");
		return $this->db->get()->result();*/
		/*$sql = $this->db->get('events');
		return $sql->result();*/
	 $sql = "SELECT * FROM events WHERE events.start BETWEEN ? AND ? ORDER BY events.start ASC";
	 return $this->db->query($sql, array($_GET['start'], $_GET['end']))->result();

	}

	private function _get_datatables_query_appointment($customer_id)
	{
		
		$this->db->select('event_id, CONCAT(cu.first_name, " ", cu.last_name) AS name_customer, start_date, start_time, end_time, CONCAT(la.first_name, " ", la.last_name) AS name_lawyer,e.description, e.status,cu.person_id as person_pa_id');
		$this->db->from('events e');
		$this->db->join('customers cus', 'cus.customer_id = e.customer_id');
		$this->db->join('lawyer law', 'law.lawyer_id = e.lawyer_id');
		$this->db->join('peoples cu', 'cu.person_id = cus.person_id');
		$this->db->join('peoples la', 'la.person_id = law.person_id');
		$this->db->where('e.customer_id', $customer_id);

		//$this->db->where('pat.deleted', 1);
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

	function get_datatables_appointment($customer_id)
	{
		$this->_get_datatables_query_appointment($customer_id);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered_appointment($customer_id)
	{
		$this->_get_datatables_query_appointment($customer_id);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all_appointment($customer_id)
	{
		$this->db->where('customer_id', $customer_id);
		$this->db->from($this->table);

		return $this->db->count_all_results();
	}

	public function count_all() {
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}
	
	/* get evento por id */

	public function get_event_by_id($event_id='')
	{
		$this->db->select('event_id, CONCAT(cu.first_name, " ", cu.last_name) AS name_customer, end_date,start_date, CONCAT(start_date, " ", start_time) AS start_time,CONCAT(start_date, " ", end_time) AS end_time,law.lawyer_id, CONCAT(la.first_name, " ", la.last_name) AS name_lawyer,e.description, e.status');
		$this->db->from('events e');
		$this->db->join('customers cus', 'cus.customer_id = e.customer_id');
		$this->db->join('lawyer law', 'law.lawyer_id = e.lawyer_id');
		$this->db->join('peoples cu', 'cu.person_id = cus.person_id');
		$this->db->join('peoples la', 'la.person_id = law.person_id');
		$this->db->where('e.event_id', $event_id);
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->row();
	}


	Public function getEventos()
	{
		
		$this->db->select('event_id, pe.first_name as first_name_lawyer, pe.last_name as last_name_lawyer, peo.first_name as first_name_customer, peo.last_name as last_name_customer,law.lawyer_id,cus.customer_id, description, start_date, end_date, start_time, end_time, color,e.status,cus.person_id');
		$this->db->from('events e');
		$this->db->join('customers cus', 'cus.customer_id = e.customer_id');
		$this->db->join('lawyer law', 'law.lawyer_id = e.lawyer_id');
		$this->db->join('peoples pe', 'pe.person_id = law.person_id');
		$this->db->join('peoples peo', 'peo.person_id = cus.person_id');

		$sql = $this->db->get();
		return $sql->result();
	}

	public function get_lawyer()
	{
		$this->db->select('lawyer_id, first_name, last_name');
		$this->db->from('lawyer law');
		$this->db->join('peoples cus', 'cus.person_id = law.person_id');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_customer_test()
	{
		$this->db->select('customer_id, first_name, last_name');
		$this->db->from('customers cu');
		$this->db->join('peoples pe', 'pe.person_id = cu.person_id');
		$query = $this->db->get();
		return $query->result();
	}

	public function get_customer($search, $deleted = 0, $limit = 20, $offset = 0, $orderby = 'asc')
    {
        $this->db->select("CONCAT(first_name,' ',last_name) as value, customer_id");
		$this->db->from('customers cu');
		$this->db->join('peoples pe', 'pe.person_id = cu.person_id');
        $this->db->where("first_name LIKE '" . $this->db->escape_like_str($search) . "%' OR CONCAT(first_name,' ',last_name) LIKE '%" . $this->db->escape_like_str($search) . "%' ");
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result();
    }

	/*Create new events */
	Public function addEvent()
	{

	$sql = "INSERT INTO events (customer_id,description,start_date,start_time,end_time, lawyer_id) VALUES (?,?,?,?,?,?)";
	$this->db->query($sql, array($_POST['customer_id'], $_POST['description'], $_POST['start'], date_format(date_create($_POST['start_time']),'H:i'),date_format(date_create($_POST['end_time']),'H:i') , $_POST['lawyer_id']));
		return ($this->db->affected_rows()!=1)?false:true;
	}

	/*Update  event */

	Public function updateEvent()
	{

	$sql = "UPDATE events SET customer_id = ?, description = ?, start_date = ?, start_time = ?, end_time = ?, lawyer_id = ?,status=? WHERE event_id = ?";
	$this->db->query($sql, array($_POST['customer_id'],$_POST['description'],$_POST['start'],date_format(date_create($_POST['start_time']),'H:i'),date_format(date_create($_POST['end_time']),'H:i'),$_POST['lawyer_id'],$_POST['status'],$_POST['event_id']));
		return ($this->db->affected_rows()!=1)?false:true;
	}

	Public function updateEventCustomer()
	{

	$sql = "UPDATE events SET status = ?, description = ?, start_date = ?, start_time = ?, end_time = ?, lawyer_id = ? WHERE event_id = ?";
	$this->db->query($sql, array($_POST['status'],$_POST['description'],$_POST['start'],date_format(date_create($_POST['start_time']),'H:i'),date_format(date_create($_POST['end_time']),'H:i'),$_POST['lawyer_id'],$_POST['event_id']));
		return ($this->db->affected_rows()!=1)?false:true;
	}

	/*Delete event */

	Public function deleteEvent()
	{

	$sql = "DELETE FROM events WHERE event_id = ?";
	$this->db->query($sql, array($_GET['id']));
		return ($this->db->affected_rows()!=1)?false:true;
	}

	public function event_delete($event_id)
	{
		$this->db->where('event_id', $event_id);
		$this->db->delete($this->table);
	}

	/*Update  event */

	Public function dragUpdateEvent()
	{
	//$date=date('Y-m-d h:i:s',strtotime($_POST['date']));
	$sql = "UPDATE events SET  start_date = ?,start_time = ?,end_time = ?  WHERE event_id = ?";
	$this->db->query($sql, array($_POST['start'],date_format(date_create($_POST['start_time']),'H:i'),date_format(date_create($_POST['end_time']),'H:i'), $_POST['id']));
	return ($this->db->affected_rows()!=1)?false:true;
	}

}

/* End of file Calendar_model.php */
/* Location: ./application/models/Calendar_model.php */