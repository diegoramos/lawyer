<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once ("Secure_area.php");
class Lawyers extends Secure_area {

	public function __construct()
	{
		parent::__construct('lawyers');
		//Load Dependencies
		if($this->session->userdata('user_id') <= 0 )
        {
            redirect('login');
        }
		$this->load->model('Lawyer_model');
		$this->load->model('People_model');
	}

	// List all your items
	public function index( $offset = 0 )
	{
		$employee_id=-1;
		$data['title'] = 'Abogados';
		$data['logged_in_employee_id']=($this->session->userdata('user_id')!='')?$this->session->userdata('user_id'):0;
		$data['all_modules'] = $this->Module->get_all_modules();
		$data['person_info']=$this->People_model->get_info_lawyer($employee_id);
		if ($this->verificar_permiso_accion('add_update')) {
			$data['add']=true;
		} else {
			$data['add']=false;
		}
		$this->load->view("include/header", $data);
		$this->load->view('include/menu');
	    $this->load->view("lawyer_view", $data);
	    $this->load->view("include/footer");
	}

	public function ajax_list()
	{
		$list = $this->Lawyer_model->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $lawyer) {
			$no++;
			$row = array();
			$row[] = $lawyer->first_name;
			$row[] = '<a href="javascript:void(0)" title="View" onclick="view_lawyer('.$lawyer->person_id.')">'.$lawyer->last_name.' <i class="glyphicon glyphicon-eye-open"></i></a>';
			$row[] = $lawyer->email;
			$row[] = $lawyer->speciality;
			$row[] = $lawyer->username;

            if($lawyer->status == 1)
                $row[] = 'Activo';
            else
                $row[] = 'Inactivo';
			//add html for action
            $actions='';
            if ($this->verificar_permiso_accion('add_update')) {
            	$actions .='<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_lawyer('.$lawyer->person_id.')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>';
            }
            if ($this->verificar_permiso_accion('delete')) {
            	$actions .='<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_lawyer('.$lawyer->person_id.')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
            }
            if ($actions=='') {
            	$actions .='<a class="btn btn-sm btn-default" href="javascript:void(0)" title="No cuenta con permisos" ">Solo Vista</a>';
            }
            $row[]=$actions;

			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->Lawyer_model->count_all(),
						"recordsFiltered" => $this->Lawyer_model->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);

	}

	public function ajax_edit($id)
	{
		$data = $this->Lawyer_model->get_by_id($id);
		$data->birth_date = ($data->birth_date == '0000-00-00') ? '' : $data->birth_date; // if 0000-00-00 set tu empty for datepicker compatibility $data->person_id
		$modules=$this->Module->get_all_modules();
		$data->permissions = array();
		foreach ($modules->result() as $key => $module) {
			if ($this->Module->has_module_permission($module->module_id,$id)) {
				$data->permissions[]= $val="permissions".$module->module_id;
				foreach($this->Module_action->get_module_actions($module->module_id)->result() as $module_action)
                    {
                    	if ($this->Module->has_module_action_permission($module->module_id,$module_action->action_id,$id)) {
                    		$data->permissions[]="permissions_actions".$module->module_id."|".$module_action->action_id;
                    	}
                    }
			}
		}
		echo json_encode($data);

	}

	public function ajax_add()
	{
		//$this->check_action_permission('add_update');
		$this->_validate();
		$this->_validate_pass();
		$permission_data = $this->input->post("permissions")!=false ? $this->input->post("permissions"):array();
		$permission_action_data = $this->input->post("permissions_actions")!=false ? $this->input->post("permissions_actions"):array();
		$data_people = array(
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'dni' => $this->input->post('dni'),
				'birth_date' => $this->input->post('birth_date'),
				'gender' => $this->input->post('gender'),
				'email' => $this->input->post('email'),
				'phone' => $this->input->post('phone'),
				'address' => $this->input->post('address'),
				'date_reg' => $this->input->post('date_reg')
			);
		$data_lawyer = array(
				'code' => $this->input->post('code'),
				'speciality' => $this->input->post('speciality'),
				'username' => $this->input->post('username'),
				'password' => md5($this->input->post('password')),
				'color' => $this->input->post('color'),
				'status' => $this->input->post('status')
			);

		$insert = $this->People_model->save_people($data_people);
        if ($insert>0) {
            $data_lawyer['person_id'] = $insert;
            $this->Lawyer_model->save_lawyer($data_lawyer);
            $this->People_model->update_permisos($permission_data,$permission_action_data,$insert);
        }
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data_people = array(
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'dni' => $this->input->post('dni'),
				'birth_date' => $this->input->post('birth_date'),
				'gender' => $this->input->post('gender'),
				'email' => $this->input->post('email'),
				'phone' => $this->input->post('phone'),
				'address' => $this->input->post('address'),
				'date_reg' => $this->input->post('date_reg')
			);
		$data_lawyer = array(
				'code' => $this->input->post('code'),
				'speciality' => $this->input->post('speciality'),
				'username' => $this->input->post('username'),
				//'password' => md5($this->input->post('password')),
				'color' => $this->input->post('color'),
				'status' => $this->input->post('status')
			);
			if ($this->input->post('password') != '') {
				$data_lawyer['password'] = md5($this->input->post('password'));
			}

        $data = $this->Lawyer_model->get_id($this->input->post('person_id'));

        $permission_data = $this->input->post("permissions")!=false ? $this->input->post("permissions"):array();
		$permission_action_data = $this->input->post("permissions_actions")!=false ? $this->input->post("permissions_actions"):array();

        $this->People_model->update_people(array('peoples.person_id' =>$data->person_id), $data_people);
        $this->Lawyer_model->update_lawyer(array('lawyer.person_id' => $this->input->post('person_id')), $data_lawyer);
        $this->People_model->update_permisos($permission_data,$permission_action_data,$data->person_id);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$people = $this->People_model->get_by_id_us($id);

        $this->People_model->delete_by_id($id);
        $this->Lawyer_model->delete_by_id($people->person_id);

		echo json_encode(array("status" => TRUE));
	}

	public function get_lawyer($id)
	{
        $data = $this->Lawyer_model->get_lawyer($id);
        echo json_encode($data);
	}
	
	private function _validate_pass()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;
		if($this->input->post('password') == '')
		{
			$data['inputerror'][] = 'password';
			$data['error_string'][] = 'Password es requerido';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('first_name') == '')
		{
			$data['inputerror'][] = 'first_name';
			$data['error_string'][] = 'Nombre es requerido';
			$data['status'] = FALSE;
		}

		if($this->input->post('last_name') == '')
		{
			$data['inputerror'][] = 'last_name';
			$data['error_string'][] = 'Apellidos es requerido';
			$data['status'] = FALSE;
		}

		if($this->input->post('username') == '')
		{
			$data['inputerror'][] = 'username';
			$data['error_string'][] = 'Usuario es requerido';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
}

/* End of file Lawyers.php */
/* Location: ./application/controllers/Lawyers.php */
