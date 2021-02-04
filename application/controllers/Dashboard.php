<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once ("Secure_area.php");
class Dashboard extends Secure_area {

	public function __construct()
	{
		parent::__construct();

		if($this->session->userdata('user_id') <= 0 )
        {
            redirect('login');
        }
        $this->load->model('Customer_model');
		$this->load->model('Calendar_model');

	}

	public function index()
	{
		$data['title'] = 'Pagina Principal';
		$data['total_paciente'] = $this->Customer_model->count_all();
		$data['total_event'] = $this->Calendar_model->count_all();
		$this->load->view("include/header", $data);
		$this->load->view('include/menu');
	    $this->load->view("dashboard_view", $data);
	    $this->load->view("include/footer");		
	}

    public function logout()
    {
        $this->session->unset_userdata('user_id');
        $this->session->unset_userdata('first_name');
        $this->session->unset_userdata('last_name');
        $sdata['message']="Has salido satisfactoriamente del sistema";
        $this->session->set_userdata($sdata);
        redirect('login','refresh');
    }
}

/* End of file Dashboard.php */
/* Location: ./application/controllers/Dashboard.php */