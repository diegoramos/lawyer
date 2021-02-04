<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once ("Secure_area.php");
class Treatment extends Secure_area {

	public function __construct()
	{
		parent::__construct('treatment');
		//Load Dependencies
		if($this->session->userdata('user_id') <= 0 )
        {
            redirect('login');
        }
		$this->load->model('Treatment_model');
	}

	// List all your items
	public function index( $offset = 0 )
	{
		$data['title'] = 'Tratamientos';
		$data['total_paciente'] = 'ok';//$this->Patient_m->count_all();
		$treatment['treatments'] = $this->Treatment_model->count_all();
		if ($this->verificar_permiso_accion('add_update')) {
			$treatment['add']=true;
		} else {
			$treatment['add']=false;
		}
		$this->load->view("include/header", $data);
		$this->load->view('include/menu',$treatment);
	    $this->load->view("treatment_view", $data);
	    $this->load->view("include/footer");
	}

	public function ajax_list()
	{
		$list = $this->Treatment_model->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $treatment) {
			$no++;
			$row = array();
			$row[] = $treatment->code;
			$row[] = '<a href="javascript:void(0)" title="View" onclick="view_doctor('.$treatment->treatment_id.')">'.$treatment->name.' <i class="glyphicon glyphicon-eye-open"></i></a>';
			$row[] = $treatment->sale_price;
			$row[] = $treatment->date;
			$row[] = $treatment->note;

            if($treatment->status == 1)
                $row[] = 'Activo';
            else
                $row[] = 'Inactivo';
			//add html for action
            $actions='';
            if ($this->verificar_permiso_accion('add_update')) {
            	$actions .='<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_treatment('.$treatment->treatment_id.')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
            }
            if ($this->verificar_permiso_accion('delete')) {
            	$actions .='<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_treatment('.$treatment->treatment_id.')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            }
            if ($actions=='') {
            	$actions .='<a class="btn btn-sm btn-default" href="javascript:void(0)" title="No cuenta con permisos" ">Solo Vista</a>';
            }
            
            $row[]=$actions;

			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->Treatment_model->count_all(),
						"recordsFiltered" => $this->Treatment_model->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);

	}

	public function ajax_edit($id)
	{
		$data = $this->Treatment_model->get_by_id($id);
		$data->date = ($data->date == '0000-00-00') ? '' : $data->date; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();
		$data = array(
				'code' => $this->input->post('code'),
				'name' => $this->input->post('name'),
				'sale_price' => $this->input->post('sale_price'),
				'date' => $this->input->post('date'),
				'note' => $this->input->post('note'),
				'status' => $this->input->post('status')
			);

		$insert = $this->Treatment_model->save_treatment($data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
				'code' => $this->input->post('code'),
				'name' => $this->input->post('name'),
				'sale_price' => $this->input->post('sale_price'),
				'date' => $this->input->post('date'),
				'note' => $this->input->post('note'),
				'status' => $this->input->post('status')
			);

		$this->Treatment_model->update_treatment(array('treatment_id' => $this->input->post('treatment_id')), $data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{

        $this->Treatment_model->delete_by_id($id);

		echo json_encode(array("status" => TRUE));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('name') == '')
		{
			$data['inputerror'][] = 'name';
			$data['error_string'][] = 'Nombre es requerido';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
}

/* End of file Treatment.php */
/* Location: ./application/controllers/Treatment.php */
