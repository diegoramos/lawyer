<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once "Secure_area.php";
class Calendar extends Secure_area {

	public function __construct() {
		parent::__construct('calendar');
		//Load Dependencies
		$this->load->model('Calendar_model');
		$this->load->model('Customer_model');

	}

	// List all your items
	public function index($offset = 0) {
		$data['title'] = 'Agenda de Citas';
		$data['abogado'] = $this->Calendar_model->get_lawyer();
		$event['events'] = $this->Calendar_model->count_all();

		$history_clinic = $this->Customer_model->get_last_id();
		if ($history_clinic) {
			$id = $history_clinic[0]->person_id;
			$data['history_clinic'] = generate_code('HCP0', $id);
		} else {
			$data['history_clinic'] = 'HCP01';
		}

		if ($this->verificar_permiso_accion('add_update')) {
			$data['add'] = true;
		} else {
			$data['add'] = false;
		}
		$this->load->view("include/header", $data);
		$this->load->view('include/menu', $event);
		$this->load->view("calendar_view", $data);
		$this->load->view("include/footer");
	}

	/*Get all Events */
	Public function getEvents() {
		// $result=$this->Calendar_model->getEvents();
		// echo json_encode($result);
		$data_calendar = $this->Calendar_model->getEventos();
		$calendar = array();
		foreach ($data_calendar as $key => $val) {
			$calendar[] = array(
				'id' => intval($val->id_evento),
				'title' => $val->id_paciente,
				'description' => trim($val->description),
				'start' => date_format(date_create($val->start_date), "Y-m-d") . ' ' . $val->start_time,
				'color' => $val->color,
			);
		}

		$data = array();
		//$data['get_data'] = json_encode($calendar);
		echo json_encode($calendar);
	}
	Public function getEventos() {
		// $result=$this->Calendar_model->getEventos();

		// echo json_encode($result);

		// echo '<br>';
		// exit();

		$data_calendar = $this->Calendar_model->getEventos();
		$calendar = array();
		foreach ($data_calendar as $key => $val) {
			$calendar[] = array(
				'id' => intval($val->event_id),
				'title' => $val->first_name_customer . ' ' . $val->last_name_customer,
				'description' =>$val->description,
				'start' => date_format(date_create($val->start_date), "Y-m-d") . ' ' . $val->start_time,
				'end' => date_format(date_create($val->start_date), "Y-m-d") . ' ' . $val->end_time,
				'lawyer_id' =>$val->lawyer_id,
				'customer_id' =>$val->customer_id,
				'person_id' =>$val->person_id,
				'color' => $val->color,
				'status' => $val->status,
			);
		}

		echo json_encode($calendar);
		//print_r($calendar);
	}

	function tester($value = '') {
		$data_calendar = $this->Calendar_model->getEventos();

		foreach ($data_calendar as $value) {

			echo $value->description;
		}
	}

	public function get_customer() {
		$buscar = $this->input->get('term');
		$result = $this->Calendar_model->get_customer($buscar);
		echo json_encode($result);
	}

	/*Add new event */
	Public function addEvent() {
		$result = $this->Calendar_model->addEvent();
		echo $result;
	}
	/*Update Event */
	Public function updateEvent() {
		$result = $this->Calendar_model->updateEvent();
		echo $result;
	}
	/*Delete Event*/
	Public function deleteEvent() {
		$result = $this->Calendar_model->deleteEvent();
		echo $result;
	}
	Public function dragUpdateEvent() {
		$result = $this->Calendar_model->dragUpdateEvent();
		echo $result;
	}
}

/* End of file Calendar.php */
/* Location: ./application/controllers/Calendar.php */
