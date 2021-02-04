<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once ("Secure_area.php");
class Reports extends Secure_area {

	public function __construct()
	{
		parent::__construct('reports');
		//Load Dependencies
		if($this->session->userdata('user_id') <= 0 )
        {
            redirect('login');
        }
		$this->load->model('Lawyer_model');
		$this->load->helper('report');
		//$this->load->model('People_model');
	}

	public function index()
	{
		$data = array();
		$data['title'] = 'Reportes';
		$this->load->view("include/header",$data);
		$this->load->view('include/menu');
	    $this->load->view("reports/report_view", $data);
	    $this->load->view("include/footer");
	}
	public function generate($report=''){
		if ($report=='specific_doctor') {
			$this->specific_doctor();
		}

		if ($report=='graphical_doctors') {
			$this->graphical_doctors();
		}
		if ($report=='summary_doctor') {
			$this->summary_doctor();
		}
	}

	private function formato_fecha($fecha='')
	{
		$start=explode('/', $fecha);
		return $start[2]."-".$start[1]."-".$start[0];
	}
	// VISTA DE LOS REPORTES

	private function specific_doctor(){
		$data = array();
		$data['title'] = 'Reportes';
		$data['report']='specific_doctor';
		$data['input_report_title']='Opciones de informe';
		$data['doctors']=$this->Lawyer_model->get_all()->result();

		$get=$this->input->get();
		$person_id=($this->session->userdata('is_doctor')==true)?$this->session->userdata('user_id'):0;
		if ($get) {
			$data['doctors_id']=$this->input->get('specific_doctor');
			if ($this->input->get('report_date_range_simple')=='CUSTOM') {
				$start=$this->formato_fecha($this->input->get('start_date_formatted'));
				$end=$this->formato_fecha($this->input->get('end_date_formatted'));
			}else{
				if ($this->input->get('report_type') == 'simple')
				{
					$dates = simple_date_range_to_date($this->input->get('report_date_range_simple'), (boolean)$this->input->get('with_time'),(boolean)$this->input->get('end_date_end_of_day')); 
					$start = date_format(date_create($dates['start_date']),"Y-m-d");
					$end = date_format(date_create($dates['end_date']),"Y-m-d");
				}else{
					$data['doctors_id']=$this->Lawyer_model->get_doctor_login_id($person_id);
					$start = date('Y-m-d');
					$end = date('Y-m-d'); 
				}
			}
				
		}else{
			
			$data['doctors_id']=$this->Lawyer_model->get_doctor_login_id($person_id);
			$start = date('Y-m-d');
			$end = date('Y-m-d'); 
		}
		if ($this->input->get('report_date_range_simple')!='') {
			$data['report_date_range_simple']=$this->input->get('report_date_range_simple');
		}else{
			$data['report_date_range_simple']='TODAY';
		}
		
		/*-- Este la paginación --*/
		if (isset($_GET['pageno'])) {
		   $data['pageno'] = $_GET['pageno'];
		} else {
		   $data['pageno'] = 1;
		}

		$numrows=$this->Lawyer_model->count_all_events($start,$end,$data['doctors_id'])->cant;

		$rows_per_page = 50;
		$data['lastpage'] = ceil($numrows/$rows_per_page);

		$data['pageno'] = (int)$data['pageno'];
		if ($data['pageno'] > $data['lastpage']) {
		   $data['pageno'] = $data['lastpage'];
		} // if
		if ($data['pageno'] < 1) {
		   $data['pageno'] = 1;
		}

		$limit = ' LIMIT ' .($data['pageno'] - 1) * $rows_per_page .',' .$rows_per_page;
	
		$data['events']=$this->Lawyer_model->get_range_event_specific_doctor($start,$end,$data['doctors_id'],$limit);

		$data['trat']	=0;
		$data['odont']	=0;
		$data['pag']	=0;
		$data['rest']	=0;
		foreach ($data['events'] as $key => $value):
			$data['trat']=$data['trat']+$value->total_tratamientos;
			$data['odont']=$data['odont']+$value->total_odontograma;
			$data['pag']=$data['pag']+$value->total_pagado;
			$data['rest']=$data['rest']+$value->por_pagar;
		endforeach;
		$this->load->view("include/header",$data);
		$this->load->view('include/menu');
		$this->load->view('reports/specific_doctor_view', $data, FALSE);
		$this->load->view("include/footer");
	}
	private function summary_doctor(){
		$data = array();
		$data['title'] = 'Reportes';
		$data['input_report_title']='Opciones de informe';
		$data['report']='summary_doctor';

		//$data['doctors']=$this->Lawyer_model->get_all()->result();

		$get=$this->input->get();
		if ($get) {
			//$data['doctors_id']=$this->input->get('specific_doctor');
			if ($this->input->get('report_date_range_simple')=='CUSTOM') {
				$start=$this->formato_fecha($this->input->get('start_date_formatted'));
				$end=$this->formato_fecha($this->input->get('end_date_formatted'));
			}else{
				if ($this->input->get('report_type') == 'simple')
				{
					$dates = simple_date_range_to_date($this->input->get('report_date_range_simple'), (boolean)$this->input->get('with_time'),(boolean)$this->input->get('end_date_end_of_day')); 
					$start = date_format(date_create($dates['start_date']),"Y-m-d");
					$end = date_format(date_create($dates['end_date']),"Y-m-d");
				}
			}
				
		}else{
			$person_id=($this->session->userdata('is_doctor')==true)?$this->session->userdata('user_id'):0;
			//$data['doctors_id']=$this->Lawyer_model->get_doctor_login_id($person_id);
			$start = date('Y-m-d');
			$end = date('Y-m-d'); 
		}
		if ($this->input->get('report_date_range_simple')!='') {
			$data['report_date_range_simple']=$this->input->get('report_date_range_simple');
		}else{
			$data['report_date_range_simple']='TODAY';
		}
		$data['events']=$this->Lawyer_model->get_range_event_group_doctor($start,$end);
		
		$data['trat']	=0;
		$data['odont']	=0;
		$data['pag']	=0;
		$data['rest']	=0;
		foreach ($data['events'] as $key => $value):
			$data['trat']=$data['trat']+$value->total_tratamientos;
			$data['odont']=$data['odont']+$value->total_odontograma;
			$data['pag']=$data['pag']+$value->total_pagado;
			$data['rest']=$data['rest']+$value->por_pagar;
		endforeach;
		/*echo "<pre>";
		print_r($data);
		echo "</pre>";
		exit();*/
		$this->load->view("include/header",$data);
		$this->load->view('include/menu');
		$this->load->view('reports/summary_doctor_view', $data, FALSE);
		$this->load->view("include/footer");
	}
	private function graphical_doctors(){
		$data = array();
		$data['title'] = 'Reportes';
		$data['report']='graphical_doctors';
		$this->load->view("include/header",$data);
		$this->load->view('include/menu');
		$this->load->view('reports/graphical_doctors_view', $data, FALSE);
		$this->load->view("include/footer");
	}

	

}

/* End of file Reports.php */
/* Location: ./application/controllers/Reports.php */
 ?>