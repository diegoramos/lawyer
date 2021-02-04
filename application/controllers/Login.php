<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		//Do your magic here
        if($this->session->userdata('user_id')>0)
        {
            redirect('dashboard', 'refresh');
        }
		$this->load->model('User_model');
	}

	public function index()
	{
		//$this->load->view('include/header');
		$this->load->view('login_view');
		//$this->load->view('include/footer');
	}

	public function check_login()
	{
        $response=array();
        if($this->form_validation->run('login')!==false){
            $result=$this->User_model->checkLogin();
            if($result){
                $response['status']='success';
                $sdata['user_id']=$result->person_id;
                $sdata['last_name']=$result->first_name." ".$result->last_name;
                $sdata['date_reg']=$result->date_reg;
                $sdata['is_doctor']=false;
                $this->session->set_userdata($sdata);
            }
            else{
                $result2=$this->User_model->checkLogin_lawyer();
                if($result2){
                    $response['status']='success';
                    $sdata['user_id']=$result2->person_id;
                    $sdata['last_name']=$result2->first_name." ".$result2->last_name;
                    $sdata['rol']=$result2->speciality;
                    $sdata['date_reg']=$result2->date_reg;
                    $sdata['is_doctor']=true;
                    $this->session->set_userdata($sdata);
                }else{
                    $response['status']='error';
                    $response['message']='Ops! Invalid username Or password'; 
                }
            }
        }
        else{
            $response['status']='error';
            $response['message']=validation_errors();
        }
        echo json_encode($response);		
	}

}

/* End of file Login.php */
/* Location: ./application/controllers/Login.php */