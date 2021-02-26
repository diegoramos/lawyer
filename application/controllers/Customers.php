<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once "Secure_area.php";
class Customers extends Secure_area {

	public function __construct() {
		parent::__construct('customers');
		//Load Dependencies
		$this->load->model('Customer_model');
		$this->load->model('People_model');
		$this->load->model('Casos_model');
		$this->load->model('Tramite_model');
		$this->load->model('Proceso_model');
		$this->load->model('Payment_model');
		$this->load->model('File_model');
		$this->load->model('Calendar_model');
		$this->load->library('ciqrcode');
	}

	// List all your items
	public function index($offset = 0) {
		$data['title'] = 'Clientes';
		$code = $this->Customer_model->get_last_id();
		if ($code) {
			$id = $code[0]->person_id;
			$data['code'] = generate_code('COD', $id);
		} else {
			$data['code'] = 'COD1';
		}
		if ($this->verificar_permiso_accion('add_update')) {
			$data['add'] = true;
		} else {
			$data['add'] = false;
		}
		$this->load->view("include/header", $data);
		$this->load->view('include/menu');
		$this->load->view("customers_view", $data);
		$this->load->view("include/footer");
	}
	//Update one item
	public function view($id = -1) {

		$data['title'] = 'Cliente';
		$data['customer'] = $this->Customer_model->get_by_id_detail($id);
		$data['payment'] = $this->Payment_model->get_payment($data['customer']->customer_id);
		$data['lawyer'] = $this->Calendar_model->get_lawyer();

		$data['is_consulta'] = $this->verificar_permiso_accion('consulta');
		$data['is_tramite'] = $this->verificar_permiso_accion('tramite');
		$data['is_proceso'] = $this->verificar_permiso_accion('proceso');
		$data['is_archivo'] = $this->verificar_permiso_accion('images');
		$data['is_actividad'] = $this->verificar_permiso_accion('actividad');
		$this->load->view("include/header", $data);
		$this->load->view('include/menu');
		$this->load->view("customer_detalle_view", $data);
		$this->load->view("include/footer");
	}

	public function ajax_list() {
		$list = $this->Customer_model->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $customer) {
			$no++;
			$row = array();
			$row[] = $customer->code;
			$row[] = '<a href="' . base_url() . 'customers/view/' . $customer->person_id . '">'.$customer->first_name.'</a>';
			$row[] = $customer->last_name;
			//$row[] = $customer->gender;
			if ($customer->gender == 0) {
				$row[] = 'Femenino';
			} else {
				$row[] = 'Masculino';
			}

			//add html for action

			$actions = '';
			if ($this->verificar_permiso_accion('attend_patient')) {
				$actions .= '<a class="btn btn-sm btn-success" href="' . base_url() . 'customers/view/' . $customer->person_id . '"><i class="glyphicon glyphicon-eye-open"></i> Ver</a>';
			}
			if ($this->verificar_permiso_accion('add_update')) {
				$actions .= '<a class="btn btn-sm btn-primary" href="javascript:void(0)"  title="Edit" onclick="edit_customer(' . $customer->person_id . ')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
			}
			if ($this->verificar_permiso_accion('delete')) {
				$actions .= '<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_customer(' . $customer->person_id . ')"><i class="glyphicon glyphicon-trash"></i> Del</a>';
			}
			if ($actions == '') {
				$actions .= '<a class="btn btn-sm btn-default" href="javascript:void(0)" title="No cuenta con permisos" ">Solo Vista</a>';
			}
			$row[] = $actions;

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->Customer_model->count_all(),
			"recordsFiltered" => $this->Customer_model->count_filtered(),
			"data" => $data,
		);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id) {
		$data = $this->Customer_model->get_by_id($id);
		$data->birth_date = ($data->birth_date == '0000-00-00') ? '' : $data->birth_date; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_add() {
		$this->_validate_customer();
		$data_people = array(
			'first_name' => $this->input->post('first_name'),
			'last_name' => $this->input->post('last_name'),
			'birth_date' => $this->input->post('birth_date'),
			'gender' => $this->input->post('gender'),
			'email' => $this->input->post('email'),
			'phone' => $this->input->post('phone'),
			'cell_phone' => $this->input->post('cell_phone'),
			'address' => $this->input->post('address'),
			'dni' => $this->input->post('dni'),
			'date_reg' => $this->input->post('date_reg'),
		);

		if (!empty($_FILES['photo']['name'])) {
			$upload = $this->_do_upload();
			$data_people['photo'] = $upload;
		}

		$data_patient = array(
			'code' => $this->input->post('code'),
			'comment' => $this->input->post('comment'),
		);

		$insert = $this->People_model->save_people($data_people);
		if ($insert > 0) {
			$data_patient['person_id'] = $insert;
			$this->Customer_model->save_patient($data_patient);
		}
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update() {
		$this->_validate_customer();
		$data_people = array(
			'first_name' => $this->input->post('first_name'),
			'last_name' => $this->input->post('last_name'),
			'birth_date' => $this->input->post('birth_date'),
			'gender' => $this->input->post('gender'),
			'email' => $this->input->post('email'),
			'phone' => $this->input->post('phone'),
			'cell_phone' => $this->input->post('cell_phone'),
			'address' => $this->input->post('address'),
			'dni' => $this->input->post('dni'),
			'date_reg' => $this->input->post('date_reg'),
		);

		if ($this->input->post('remove_photo')) // if remove photo checked
		{
			if (file_exists('upload/' . $this->input->post('remove_photo')) && $this->input->post('remove_photo')) {
				unlink('upload/' . $this->input->post('remove_photo'));
			}

			$data_people['photo'] = '';
		}

		if (!empty($_FILES['photo']['name'])) {
			$upload = $this->_do_upload();

			//delete file
			$person = $this->People_model->get_by_id($this->input->post('person_id'));
			if (file_exists('upload/' . $person->photo) && $person->photo) {
				unlink('upload/' . $person->photo);
			}

			$data_people['photo'] = $upload;
		}
		$data_patient = array(
			'code' => $this->input->post('code'),
			'comment' => $this->input->post('comment'),
		);

		$data = $this->Customer_model->get_id($this->input->post('person_id'));

		$this->People_model->update_people(array('peoples.person_id' => $data->person_id), $data_people);
		$this->Customer_model->update_patient(array('customers.person_id' => $this->input->post('person_id')), $data_patient);
		echo json_encode(array("status" => TRUE));
	}

	private function _do_upload()
	{
		$config['upload_path']          = FCPATH .'/upload/';
        $config['allowed_types']        = 'gif|jpg|jpeg|png|JPG';
        $config['max_size']             = 2000; //set max size allowed in Kilobyte
        $config['max_width']            = 2000; // set max width image allowed
        $config['max_height']           = 2200; // set max height allowed
        $config['file_name']            = round(microtime(true) * 1000); //just milisecond timestamp fot unique name
        $this->load->library('upload', $config);

        if(!$this->upload->do_upload('photo')) //upload and validate
        {
            $data['inputerror'][] = 'photo';
			$data['error_string'][] = 'Upload error: '.$this->upload->display_errors('',''); //show ajax error
			$data['status'] = FALSE;
			echo json_encode($data);
			exit();
		}
		return $this->upload->data('file_name');
	}

	private function _validate_customer() {
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if ($this->input->post('first_name') == '') {
			$data['inputerror'][] = 'first_name';
			$data['error_string'][] = 'Este campo es requerido';
			$data['status'] = FALSE;
		}

		if ($this->input->post('last_name') == '') {
			$data['inputerror'][] = 'last_name';
			$data['error_string'][] = 'Este campo es requerido';
			$data['status'] = FALSE;
		}

		if ($data['status'] === FALSE) {
			echo json_encode($data);
			exit();
		}
	}

	public function ajax_delete($id) {
		//delete file
		$person = $this->People_model->get_by_id_cu($id);
		if (file_exists('upload/' . $person->photo) && $person->photo) {
			unlink('upload/' . $person->photo);
		}

		$this->People_model->delete_by_id($id);
		$this->Customer_model->delete_by_id($person->person_id);

		echo json_encode(array("status" => TRUE));
	}

	//Parte de consulta
	public function lista_consulta($customer_id)
	{
		$list = $this->Casos_model->get_datatables($customer_id);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $consultas) {
			$no++;
			$row = array();
			$row[] = $consultas->fecha;
			$row[] = $consultas->first_name.' '.$consultas->last_name;
			$row[] = $consultas->tipo_consulta;
			$row[] = $consultas->a_cuenta;
			$row[] = $consultas->saldo;
			$row[] = $consultas->total;
			$row[] = $consultas->estado;
            if($consultas->saldo == 0)
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar" onclick="edit_consulta('.$consultas->consulta_id.')"><i class="glyphicon glyphicon-pencil"></i></a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Borrar" onclick="delete_consulta('.$consultas->consulta_id.')"><i class="glyphicon glyphicon-trash"></i></a>
				  <a class="btn btn-sm btn-info" href="javascript:void(0)" title="Imprimir" onclick="imprimir_consulta('.$consultas->consulta_id.')"><i class="glyphicon glyphicon-print"></i></a>';
            else
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar" onclick="edit_consulta('.$consultas->consulta_id.')"><i class="glyphicon glyphicon-pencil"></i></a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Borrar" onclick="delete_consulta('.$consultas->consulta_id.')"><i class="glyphicon glyphicon-trash"></i></a>
				  <a class="btn btn-sm btn-success" href="javascript:void(0)" title="Pagar" onclick="pagar_consulta('.$consultas->consulta_id.')"><i class="glyphicon glyphicon-usd"></i></a>
				  <a class="btn btn-sm btn-info" href="javascript:void(0)" title="Imprimir" onclick="imprimir_consulta('.$consultas->consulta_id.')"><i class="glyphicon glyphicon-print"></i></a>';
			//add html for action
			$data[] = $row;
		}

		$output = array(
					"draw" => $_POST['draw'],
					"recordsTotal" => $this->Casos_model->count_all($customer_id),
					"recordsFiltered" => $this->Casos_model->count_filtered($customer_id),
					"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function edit_consulta($id)
	{
		$data = $this->Casos_model->get_by_id($id);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function save_consulta($value='')
	{
		$this->_validate_consulta();
		$data = array(
			'customer_id' => $this->input->post('customer_id'),
			'fecha' => $this->input->post('fecha'),
			'hora' => $this->input->post('hora'),
			'tipo_consulta' => $this->input->post('tipo_consulta'),
			'a_cuenta' => $this->input->post('a_cuenta'),
			'saldo' => $this->input->post('saldo'),
			'total' => $this->input->post('total'),
			'lawyer_id' => $this->input->post('lawyer_id'),
			'estado' => $this->input->post('estado'),
		);

		$insert = $this->Casos_model->save_consulta($data);
		echo json_encode(array("status" => TRUE));
	}

	public function update_consulta($value='')
	{
		$this->_validate_consulta();
		$data = array(
			'customer_id' => $this->input->post('customer_id'),
			'fecha' => $this->input->post('fecha'),
			'hora' => $this->input->post('hora'),
			'tipo_consulta' => $this->input->post('tipo_consulta'),
			'a_cuenta' => $this->input->post('a_cuenta'),
			'saldo' => $this->input->post('saldo'),
			'total' => $this->input->post('total'),
			'lawyer_id' => $this->input->post('lawyer_id'),
			'estado' => $this->input->post('estado'),
		);

		$this->Casos_model->update_consulta(array('consulta_id' => $this->input->post('consulta_id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function pagar_consulta($value='')
	{
		$this->_validate_pago();
		$data = array(
			'fecha' => $this->input->post('fecha_pag'),
			'hora' => $this->input->post('hora_pag'),
			'tipo_consulta' => $this->input->post('tipo_consulta_pag'),
			'a_cuenta' => $this->input->post('a_cuenta1'),
			'saldo' => $this->input->post('saldo1'),
			'total' => $this->input->post('total1'),
			'lawyer_id' => $this->input->post('lawyer_pag_id'),
			'estado' => $this->input->post('estado_pag'),
		);

		$this->Casos_model->update_consulta(array('consulta_id' => $this->input->post('consulta_pag_id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function delete_consulta($id)
	{
		$this->Casos_model->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function imprimir_boleta($id)
	{
		$data['info'] = $this->Casos_model->get_by_id($id);
		$params['data'] = "'".$data['info']->consulta_id."|".$data['info']->total."|".$data['info']->fecha."'";
		$params['level'] = 'H';
		$params['size'] = 3;
		$params['savename'] = FCPATH.'upload/'.$data['info']->consulta_id.'.png';
		$data['qr'] = $this->ciqrcode->generate($params);
		$this->load->view('imprimir_ticket', $data, FALSE);
	}


	public function print_documento($id)
	{
	    echo '<div class="button-group">
	          <a href="#" class="btn btn-success" onclick="printJS(\''.base_url().'customers/imprimir_boleta/'.$id.'\')">
	            IMPRIMIR
	          </a>
	          <a class="btn btn-primary" target="_blank" href="'.base_url().'customers/imprimir_boleta/'.$id.'">Abrir en navegador</a>
	          <a class="btn btn-secondary" data-dismiss="modal">Cerrar</a>
	        </div>
	        <br>
	        <iframe src="'.base_url().'customers/imprimir_boleta/'.$id.'" width="100%" height="400" frameborder="none">
	        </iframe>';
	}

	private function _validate_consulta() {
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if ($this->input->post('tipo_consulta') == '') {
			$data['inputerror'][] = 'tipo_consulta';
			$data['error_string'][] = 'Este campo es requerido';
			$data['status'] = FALSE;
		}

		if ($data['status'] === FALSE) {
			echo json_encode($data);
			exit();
		}
	}

	private function _validate_pago() {
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if ($this->input->post('tipo_consulta_pag') == '') {
			$data['inputerror'][] = 'tipo_consulta_pag';
			$data['error_string'][] = 'Este campo es requerido';
			$data['status'] = FALSE;
		}

		if ($data['status'] === FALSE) {
			echo json_encode($data);
			exit();
		}
	}

//Tramite
	public function lista_tramite($customer_id)
	{
		$list = $this->Tramite_model->get_datatables($customer_id);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $tramites) {
			$no++;
			$row = array();
			$row[] = $tramites->fecha_tram;
			$row[] = $tramites->first_name.' '.$tramites->last_name;
			$row[] = $tramites->tipo_tramite;
			$row[] = $tramites->a_cuenta_tram;
			$row[] = $tramites->saldo_tram;
			$row[] = $tramites->total_tram;
			$row[] = $tramites->estado_tram;
            if($tramites->saldo_tram == 0)
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar" onclick="edit_tramite('.$tramites->tramite_id.')"><i class="glyphicon glyphicon-pencil"></i></a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Borrar" onclick="delete_tramite('.$tramites->tramite_id.')"><i class="glyphicon glyphicon-trash"></i></a>
				  <a class="btn btn-sm btn-info" href="javascript:void(0)" title="Imprimir" onclick="imprimir_tramite('.$tramites->tramite_id.')"><i class="glyphicon glyphicon-print"></i></a>';
            else
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar" onclick="edit_tramite('.$tramites->tramite_id.')"><i class="glyphicon glyphicon-pencil"></i></a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Borrar" onclick="delete_tramite('.$tramites->tramite_id.')"><i class="glyphicon glyphicon-trash"></i></a>
				  <a class="btn btn-sm btn-success" href="javascript:void(0)" title="Pagar" onclick="pagar_tramite('.$tramites->tramite_id.')"><i class="glyphicon glyphicon-usd"></i></a>
				  <a class="btn btn-sm btn-info" href="javascript:void(0)" title="Imprimir" onclick="imprimir_tramite('.$tramites->tramite_id.')"><i class="glyphicon glyphicon-print"></i></a>';
			//add html for action
			$data[] = $row;
		}

		$output = array(
					"draw" => $_POST['draw'],
					"recordsTotal" => $this->Tramite_model->count_all($customer_id),
					"recordsFiltered" => $this->Tramite_model->count_filtered($customer_id),
					"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function edit_tramite($id)
	{
		$data = $this->Tramite_model->get_by_id($id);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function save_tramite($value='')
	{
		$this->_validate_tramite();
		$data = array(
			'customer_id' => $this->input->post('customer_tram_id'),
			'fecha_tram' => $this->input->post('fecha_tram'),
			'hora_tram' => $this->input->post('hora_tram'),
			'tipo_tramite' => $this->input->post('tipo_tramite'),
			'a_cuenta_tram' => $this->input->post('a_cuenta_tram'),
			'saldo_tram' => $this->input->post('saldo_tram'),
			'total_tram' => $this->input->post('total_tram'),
			'lawyer_id' => $this->input->post('lawyer_tram_id'),
			'estado_tram' => $this->input->post('estado_tram'),
		);

		$insert = $this->Tramite_model->save_tramite($data);
		echo json_encode(array("status" => TRUE));
	}

	public function update_tramite($value='')
	{
		$this->_validate_tramite();
		$data = array(
			'customer_id' => $this->input->post('customer_tram_id'),
			'fecha_tram' => $this->input->post('fecha_tram'),
			'hora_tram' => $this->input->post('hora_tram'),
			'tipo_tramite' => $this->input->post('tipo_tramite'),
			'a_cuenta_tram' => $this->input->post('a_cuenta_tram'),
			'saldo_tram' => $this->input->post('saldo_tram'),
			'total_tram' => $this->input->post('total_tram'),
			'lawyer_id' => $this->input->post('lawyer_tram_id'),
			'estado_tram' => $this->input->post('estado_tram'),
		);

		$this->Tramite_model->update_tramite(array('tramite_id' => $this->input->post('tramite_id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function pagar_tramite($value='')
	{
		$this->_validate_pagar_tramite();
		$data = array(
			'fecha_tram' => $this->input->post('fecha_pag_tram'),
			'hora_tram' => $this->input->post('hora_pag_tram'),
			'tipo_tramite' => $this->input->post('tipo_pag_tramite'),
			'a_cuenta_tram' => $this->input->post('a_cuenta_tram1'),
			'saldo_tram' => $this->input->post('saldo_tram1'),
			'total_tram' => $this->input->post('total_tram1'),
			'lawyer_id' => $this->input->post('lawyer_pag_tram_id'),
			'estado_tram' => $this->input->post('estado_pag_tram'),
		);

		$this->Tramite_model->update_tramite(array('tramite_id' => $this->input->post('tramite_pag_id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function delete_tramite($id)
	{
		$this->Tramite_model->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function imprimir_boleta_tramite($id)
	{
		$data['info'] = $this->Tramite_model->get_by_id($id);
		$params['data'] = "'".$data['info']->tramite_id."|".$data['info']->total_tram."|".$data['info']->fecha_tram."'";
		$params['level'] = 'H';
		$params['size'] = 3;
		$params['savename'] = FCPATH.'upload/'.$data['info']->tramite_id.'.png';
		$data['qr'] = $this->ciqrcode->generate($params);
		$this->load->view('imprimir_tramite', $data, FALSE);
	}

	public function print_documento_tramite($id)
	{
	    echo '<div class="button-group">
	          <a href="#" class="btn btn-success" onclick="printJS(\''.base_url().'customers/imprimir_boleta_tramite/'.$id.'\')">
	            IMPRIMIR
	          </a>
	          <a class="btn btn-primary" target="_blank" href="'.base_url().'customers/imprimir_boleta_tramite/'.$id.'">Abrir en navegador</a>
	          <a class="btn btn-secondary" data-dismiss="modal">Cerrar</a>
	        </div>
	        <br>
	        <iframe src="'.base_url().'customers/imprimir_boleta_tramite/'.$id.'" width="100%" height="400" frameborder="none">
	        </iframe>';
	}

	private function _validate_tramite() {
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if ($this->input->post('tipo_tramite') == '') {
			$data['inputerror'][] = 'tipo_tramite';
			$data['error_string'][] = 'Este campo es requerido';
			$data['status'] = FALSE;
		}

		if ($data['status'] === FALSE) {
			echo json_encode($data);
			exit();
		}
	}

	private function _validate_pagar_tramite() {
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if ($this->input->post('tipo_pag_tramite') == '') {
			$data['inputerror'][] = 'tipo_pag_tramite';
			$data['error_string'][] = 'Este campo es requerido';
			$data['status'] = FALSE;
		}

		if ($data['status'] === FALSE) {
			echo json_encode($data);
			exit();
		}
	}

//Seccion de procesos

	public function lista_proceso($customer_id)
	{
		$list = $this->Proceso_model->get_datatables($customer_id);

		$data = array();
		$no = $_POST['start'];
		foreach ($list as $procesos) {
			$no++;
			$row = array();
			$row[] = $procesos->fecha_proc;
			$row[] = $procesos->first_name.' '.$procesos->last_name;
			$row[] = $procesos->tipo_proceso;
			$row[] = $procesos->expediente_proc;
			$row[] = $procesos->total_proc;
			$row[] = $procesos->estado_proc;

			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar" onclick="edit_proceso('.$procesos->proceso_id.')"><i class="glyphicon glyphicon-pencil"></i></a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Borrar" onclick="delete_proceso('.$procesos->proceso_id.')"><i class="glyphicon glyphicon-trash"></i></a>
				  <a class="btn btn-sm btn-success" href="javascript:void(0)" title="Imprimir Contrato" onclick="contrato_proceso('.$procesos->proceso_id.')"><i class="glyphicon glyphicon-list-alt"></i></a>
				  <a class="btn btn-sm btn-info" href="javascript:void(0)" title="Imprimir Cronograma" onclick="imprimir_cronograma('.$procesos->proceso_id.','.$procesos->customer_proc_id.')"><i class="glyphicon glyphicon-print"></i></a>
				  <a class="btn btn-sm btn-warning" href="javascript:void(0)" title="Agregar Cronograma" onclick="add_cronograma_pagos('.$procesos->proceso_id.','.$procesos->customer_proc_id.')"><i class="glyphicon glyphicon-calendar"></i></a>';
			//add html for action
			$data[] = $row;
		}

		$output = array(
					"draw" => $_POST['draw'],
					"recordsTotal" => $this->Proceso_model->count_all($customer_id),
					"recordsFiltered" => $this->Proceso_model->count_filtered($customer_id),
					"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}


	public function ajax_edit_proceso($id)
	{
		$data = $this->Proceso_model->get_by_id($id);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_add_proceso()
	{
		$this->_validate_proceso();
		$data = array(
				'lawyer_proc_id' => $this->input->post('lawyer_proc_id'),
				'customer_proc_id' => $this->input->post('customer_proc_id'),
				'fecha_proc' => $this->input->post('fecha_proc'),
				'hora_proc' => $this->input->post('hora_proc'),
				'tipo_proceso' => $this->input->post('tipo_proceso'),
				'expediente_proc' => $this->input->post('expediente_proc'),
				'total_proc' => $this->input->post('total_proc'),
				'estado_proc' => $this->input->post('estado_proc'),
			);

		$insert = $this->Proceso_model->save_proceso($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update_proceso()
	{
		$this->_validate_proceso();
		$data = array(
				'lawyer_proc_id' => $this->input->post('lawyer_proc_id'),
				'customer_proc_id' => $this->input->post('customer_proc_id'),
				'fecha_proc' => $this->input->post('fecha_proc'),
				'hora_proc' => $this->input->post('hora_proc'),
				'tipo_proceso' => $this->input->post('tipo_proceso'),
				'expediente_proc' => $this->input->post('expediente_proc'),
				'total_proc' => $this->input->post('total_proc'),
				'estado_proc' => $this->input->post('estado_proc'),
			);
		$this->Proceso_model->update_proceso(array('proceso_id' => $this->input->post('proceso_id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete_proceso($id)
	{
		$this->Proceso_model->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}


	private function _validate_proceso()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('tipo_proceso') == '')
		{
			$data['inputerror'][] = 'tipo_proceso';
			$data['error_string'][] = 'Este campo es requerido';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}


/* Modulo pagos */

	public function add_payment($value='')
	{
		$data_payment = array(
			'fecha' => $this->input->post('fecha'),
			'pagar' => $this->input->post('pagar'),
			'acuenta' => $this->input->post('acuenta'),
			'saldo' => $this->input->post('total'),
			'total' => $this->input->post('total'),
			'customer_id' => $this->input->post('customer_id'),
			'proceso_id' => $this->input->post('proceso_id'),
		);

		$insert = $this->Payment_model->save_payment($data_payment);
		$data = $this->Payment_model->return_last_payment($insert);
		$output = '';
		$output .= '
		<tr class="table-row" id="table-row-'.$data['payment_id'].'">
			<td contenteditable="false" onBlur="saveToDatabase(this,\'payment_id\',\''.$data['payment_id'].'\')" onClick="editRow(this);">'.$data['payment_id'].'</td>
			<td contenteditable="true" class="fecha_date" onBlur="saveToDatabase(this,\'fecha\',\''.$data['payment_id'].'\')" onClick="editRow(this);">'.$data['fecha'].'</td>
			<td contenteditable="true" onBlur="saveToDatabase(this,\'pagar\',\''.$data['payment_id'].'\')" onClick="editRow(this);">'.$data['pagar'].'</td>
			<td contenteditable="false" onBlur="saveToDatabase(this,\'acuenta\',\''.$data['payment_id'].'\')" onClick="editRow(this);">'.$data['acuenta'].'</td>
			<td contenteditable="false" onBlur="saveToDatabase(this,\'saldo\',\''.$data['payment_id'].'\')" onClick="editRow(this);">'.$data['saldo'].'</td>
			<td contenteditable="false" onBlur="saveToDatabase(this,\'total\',\''.$data['payment_id'].'\')" onClick="editRow(this);">'.$data['total'].'</td>
			<td><a class="btn btn-danger btn-xs" href="javascript:void(0)" title="Borrar" onclick="deleteRecord('.$data['payment_id'].')">Borrar</a></td>
		</tr>
		';
		echo $output;
	}

	public function update_payment($value='')
	{
		$data_payment = array(
			'fecha' => $this->input->post('fecha'),
			'pagar' => $this->input->post('pagar'),
			'acuenta' => $this->input->post('acuenta'),
			'saldo' => $this->input->post('saldo'),
			'total' => $this->input->post('total'),
		);

		$this->Payment_model->update_payment(array('payment_id' => $this->input->post('id')), $data_payment);
		echo json_encode(array("status" => TRUE));
	}

	public function delete_payment($id)
	{
		$this->Payment_model->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function get_id_proceso()
	{
		$id_proceso = $this->input->post('id');
		$customer_id = $this->input->post('customer_id');
		$result = $this->Payment_model->get_payment_proc($customer_id,$id_proceso);
		echo json_encode($result);
	}

	public function imprimir_boleta_proceso($id)
	{
		$data['info'] = $this->Payment_model->get_by_id($id);

		$params['data'] = "'".$data['info']->payment_id."|".$data['info']->total."|".$data['info']->fecha."'";
		$params['level'] = 'H';
		$params['size'] = 3;
		$params['savename'] = FCPATH.'upload/'.$data['info']->payment_id.'.png';
		$data['qr'] = $this->ciqrcode->generate($params);
		$this->load->view('imprimir_proceso', $data, FALSE);
	}

	public function print_documento_proceso($id)
	{
	    echo '<div class="button-group">
	          <a href="#" class="btn btn-success" onclick="printJS(\''.base_url().'customers/imprimir_boleta_proceso/'.$id.'\')">
	            IMPRIMIR
	          </a>
	          <a class="btn btn-primary" target="_blank" href="'.base_url().'customers/imprimir_boleta_proceso/'.$id.'">Abrir en navegador</a>
	          <a class="btn btn-secondary" data-dismiss="modal">Cerrar</a>
	        </div>
	        <br>
	        <iframe src="'.base_url().'customers/imprimir_boleta_proceso/'.$id.'" width="100%" height="400" frameborder="none">
	        </iframe>';
	}


	public function imprimir_contrato_proceso($id)
	{
		$data['info'] = $this->Proceso_model->get_by_id_proc($id);
		$this->load->view('contrato_proceso', $data, FALSE);
	}

	public function print_contrato_proceso($id)
	{
	    echo '<div class="button-group">
	          <a href="#" class="btn btn-success" onclick="printJS(\''.base_url().'customers/imprimir_contrato_proceso/'.$id.'\')">
	            IMPRIMIR
	          </a>
	          <a class="btn btn-primary" target="_blank" href="'.base_url().'customers/imprimir_contrato_proceso/'.$id.'">Abrir en navegador</a>
	          <a class="btn btn-secondary" data-dismiss="modal">Cerrar</a>
	        </div>
	        <br>
	        <iframe src="'.base_url().'customers/imprimir_contrato_proceso/'.$id.'" width="100%" height="400" frameborder="none">
	        </iframe>';
	}
//00000000000000000000000000000000000000000000
	public function get_id_proceso_cronograma($customer_id,$id_proceso)
	{
		$data['info'] = $this->Payment_model->get_payment_proc($customer_id,$id_proceso);
		$this->load->view('imprimir_cronograma', $data, FALSE);
	}

	public function print_cronograma_proceso()
	{
		$id_proceso = $this->input->post('id');
		$customer_id = $this->input->post('customer_id');
	    echo '<div class="button-group">
	          <a href="#" class="btn btn-success" onclick="printJS(\''.base_url().'customers/get_id_proceso_cronograma/'.$customer_id.'/'.$id_proceso.'\')">
	            IMPRIMIR
	          </a>
	          <a class="btn btn-primary" target="_blank" href="'.base_url().'customers/get_id_proceso_cronograma/'.$customer_id.'/'.$id_proceso.'">Abrir en navegador</a>
	          <a class="btn btn-secondary" data-dismiss="modal">Cerrar</a>
	        </div>
	        <br>
	        <iframe src="'.base_url().'customers/get_id_proceso_cronograma/'.$customer_id.'/'.$id_proceso.'" width="100%" height="400" frameborder="none">
	        </iframe>';
	}
//0000000000000000000000000000000000000000000
//Files
	public function ajax_list_files($customer_id)
	{

		$list = $this->File_model->get_datatables($customer_id);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $files) {
			$no++;
			$row = array();
			$info = new SplFileInfo($files->file_name);
			$info->getExtension();
			$row[] = $files->date_in;
			$row[] = $files->resolucion;
			$row[] = $files->sumilla;
			if($info->getExtension() == 'jpg' || $info->getExtension() == 'png')
			$row[] = '<a href="'.base_url().'upload/'.$files->file_name.'" data-toggle="lightbox" data-title="'.$files->resolucion.'" data-footer="'.$files->file_name.'">
                        <img src="'.base_url().'upload/'.$files->file_name.'" height="300" width="100%" class="img-fluid">
                      </a>';

			if($info->getExtension() == 'doc' || $info->getExtension() == 'docx')
			$row[] = '<a href="'.base_url().'upload/'.$files->file_name.'" download title="Archivo tipo Word">
                      		<button type="submit"><i class="fa fa-file-word-o" aria-hidden="true"></i> Descargar</button>
                      </a>';

			if($info->getExtension() == 'xls' || $info->getExtension() == 'xlsx')
			$row[] = '<a href="'.base_url().'upload/'.$files->file_name.'" download title="Archivo tipo Excel">
                      		<button type="submit"><i class="fa fa-file-excel-o" aria-hidden="true"></i> Descargar</button>
                      </a>';

			if($info->getExtension() == 'ppt' || $info->getExtension() == 'pptx')
			$row[] = '<a href="'.base_url().'upload/'.$files->file_name.'" download title="Archivo tipo Power Point">
                      		<button type="submit"><i class="fa fa-file-powerpoint-o" aria-hidden="true"></i> Descargar</button>
                      </a>';

			if($info->getExtension() == 'pdf')
			$row[] = '<a href="'.base_url().'upload/'.$files->file_name.'" data-toggle="lightbox" data-title="'.$files->resolucion.'" data-footer="'.$files->file_name.'">
                        <embed src="'.base_url().'upload/'.$files->file_name.'" type="application/pdf" width="100%" height="300px" class="img-fluid">
                      </a>';
			//add html for action
			$actions = '';
			if ($this->verificar_permiso_accion('attend_patient')) {
				$actions .= '<div class="btn-group">
                  <button type="button" class="btn btn-success">Opciones</button>
                  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                  	<li class="divider"></li>
                    <li><a href="javascript:void(0)" title="Editar" onclick="edit_files('.$files->file_id.')">Editar</a></li>
                    <li class="divider"></li>
                    <li><a href="javascript:void(0)" title="Eliminar" onclick="delete_files('.$files->file_id.')">Eliminar</a></li>
                    <li class="divider"></li>
                    <li><a href="javascript:void(0)" title="Ver" onclick="ver_files('.$files->file_id.')">Ver</a></li>
                  </ul>
                </div>';
			}

			if ($actions == '') {
				$actions .= '<a class="btn btn-sm btn-default" href="javascript:void(0)" title="No cuenta con permisos" ">Solo Vista</a>';
			}
			$row[] = $actions;
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->File_model->count_all($customer_id),
						"recordsFiltered" => $this->File_model->count_filtered($customer_id),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit_files($id)
	{
		$data = $this->File_model->get_by_id($id);
		$data->date_in = ($data->date_in == '0000-00-00') ? '' : $data->date_in; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_add_files()
	{
		$this->_validate_files();
		$data = array(
				'customer_id' => $this->input->post('customer_id_file'),
				'date_in' => $this->input->post('date_in'),
				'resolucion' => $this->input->post('resolucion'),
				'acto' => $this->input->post('acto'),
				'folio' => $this->input->post('folio'),
				'sumilla' => $this->input->post('sumilla'),
			);

		if(!empty($_FILES['photo']['name']))
		{
			$upload = $this->_do_upload_files();
			$data['file_name'] = $upload;
		}

		$insert = $this->File_model->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update_files()
	{
		$this->_validate_files();
		$data = array(
				'customer_id' => $this->input->post('customer_id_file'),
				'date_in' => $this->input->post('date_in'),
				'resolucion' => $this->input->post('resolucion'),
				'acto' => $this->input->post('acto'),
				'folio' => $this->input->post('folio'),
				'sumilla' => $this->input->post('sumilla'),
			);

		if($this->input->post('remove_photo')) // if remove photo checked
		{
			if(file_exists('upload/'.$this->input->post('remove_photo')) && $this->input->post('remove_photo'))
				unlink('upload/'.$this->input->post('remove_photo'));
			$data['file_name'] = '';
		}

		if(!empty($_FILES['photo']['name']))
		{
			$upload = $this->_do_upload();
			//delete file
			$person = $this->File_model->get_by_id($this->input->post('file_id'));
			if(file_exists('upload/'.$person->file_name) && $person->file_name)
				unlink('upload/'.$person->file_name);

			$data['file_name'] = $upload;
		}

		$this->File_model->update(array('file_id' => $this->input->post('file_id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function get_files($id)
	{
        $data = $this->File_model->get_files($id);
        echo json_encode($data);
	}

	public function ajax_delete_files($id)
	{
		//delete file
		$person = $this->File_model->get_by_id($id);
		if(file_exists('upload/'.$person->file_name) && $person->file_name)
		unlink('upload/'.$person->file_name);
		
		$this->File_model->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	private function _do_upload_files()
	{
		$config['upload_path']          = FCPATH .'/upload/';
        $config['allowed_types']        = 'gif|jpg|jpeg|png|JPG|pdf|doc|docx|xls|xlsx|ppt|pptx';
        $config['max_size']             = 2000; //set max size allowed in Kilobyte
        $config['max_width']            = 2000; // set max width image allowed
        $config['max_height']           = 2200; // set max height allowed
        $config['file_name']            = round(microtime(true) * 1000); //just milisecond timestamp fot unique name

        $this->load->library('upload', $config);

        if(!$this->upload->do_upload('photo')) //upload and validate
        {
            $data['inputerror'][] = 'photo';
			$data['error_string'][] = 'Upload error: '.$this->upload->display_errors('',''); //show ajax error
			$data['status'] = FALSE;
			echo json_encode($data);
			exit();
		}
		return $this->upload->data('file_name');
	}

	private function _validate_files()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('date_in') == '')
		{
			$data['inputerror'][] = 'date_in';
			$data['error_string'][] = 'Fecha es requerido';
			$data['status'] = FALSE;
		}

		if($this->input->post('resolucion') == '')
		{
			$data['inputerror'][] = 'resolucion';
			$data['error_string'][] = 'Resolución es requerido';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}


	//Actividad de la agenda
	public function ajax_list_appointment($patient_id=-1){

		$list = $this->Calendar_model->get_datatables_appointment($patient_id);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $appointment) {
			$no++;
			$row = array();
			$row[] = $appointment->event_id;
			$row[] = $appointment->name_customer;
			$row[] = $appointment->start_date;
			$row[] = $appointment->start_time;
			$row[] = $appointment->end_time;
			$row[] = $appointment->name_lawyer;
			$row[] = $appointment->status;
			$action='';
			if ($this->verificar_permiso_accion('update_event')){
				$action .='<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Hapus" onclick="edit_event('.$appointment->event_id.')"><i class="glyphicon glyphicon-edit"></i> Editar</a>';
			}
			if ($this->verificar_permiso_accion('delete')){
				$action .='<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_event('.$appointment->event_id.')"><i class="glyphicon glyphicon-trash"></i> Del</a>';
			}

			$row[] = $action;
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->Calendar_model->count_filtered_appointment($patient_id),
						"recordsFiltered" => $this->Calendar_model->count_filtered_appointment($patient_id),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function get_event_by_id($id=-1)
	{
		$result = $this->Calendar_model->get_event_by_id($id);
		echo json_encode($result);
	}
	public function ajax_update_event() {
		$result = $this->Calendar_model->updateEventCustomer();
		echo $result;
	}

	public function event_delete($event_id) {
		$this->Calendar_model->event_delete($event_id);
		echo json_encode(array("status" => TRUE));
	}

}

/* End of file Customers.php */
/* Location: ./application/controllers/Customers.php */
