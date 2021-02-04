<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Treatment_model extends CI_Model {

   var $table = 'treatments';

   var $column_order = array('code','name','sale_price','date','note',null); //set column field database for datatable orderable
   var $column_search = array('code','name'); //set column field database for datatable searchable just firstname , lastname , address are searchable
   var $order = array('treatment_id' => 'desc'); // default order
  ////////////////////////////////////////////////////////////////
   /////////////////////////SEGUIMIENTO//////////////////////////
  var $table2 = 'seguimiento_paciente';
  var $column_order2 = array('seguimiento_id','fecha','mensaje',null); //set column field database for datatable orderable
  var $column_search2 = array('fecha','mensaje'); //set column field database for datatable searchable just firstname , lastname , address are searchable
  var $order2 = array('seguimiento_id' => 'desc'); // default order 

   private function _get_datatables_query()
   {
      $this->db->from($this->table);
      $this->db->where('deleted', 0);

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

    function get_datatables()
    {
      $this->_get_datatables_query();
      if($_POST['length'] != -1)
      $this->db->limit($_POST['length'], $_POST['start']);
      $query = $this->db->get();
      return $query->result();
    }
    function get_treatment($search)
    {
      $this->db->select('name as value, treatment_id,sale_price');
      $this->db->from($this->table);
      $this->db->where("name LIKE '" . $this->db->escape_like_str($search) . "%'");
      $this->db->limit(5);
      $query = $this->db->get();
      return $query->result();
    }

    function count_filtered()
    {
      $this->_get_datatables_query();
      $query = $this->db->get();
      return $query->num_rows();
    }

   public function count_all()
   {
    $this->db->from($this->table);
    return $this->db->count_all_results();
   }

   public function get_by_id($id)
   {
      $this->db->from($this->table);
      $this->db->where('treatment_id',$id);
      $query = $this->db->get();
      return $query->row();
   }
    public function get_by_id_tre($id){
      $response = false;
      $query = $this->db->get_where('treatments',array('treatment_id' => $id));
      if($query && $query->num_rows()){
        $response = $query->result_array();
      }
      return $response;
    }

   public function save_treatment($data)
   {
      $this->db->insert($this->table, $data);
      return $this->db->insert_id();
   }

   public function update_treatment($where, $data)
   {
    $this->db->update($this->table, $data, $where);
    return $this->db->affected_rows();
   }

   public function delete_by_id($id)
   {
      $data = array('deleted' => '0' );
      $this->db->where('treatment_id', $id);
      $this->db->update($this->table,$data);
   }	

   ////////////////////////////////////////////////////////////////////////////
   //////////////////////PROCEDIMIENTOS Y VISTA////////////////////////////////
   ////////////////////////////////////////////////////////////////////////////
   public function getOdontoPrecio($id,$event_id=-1){
    if ($event_id>0) {
      $this->db->where('event_id', $event_id);
    }
    $this->db->where('patient_id', $id);
    
    $result=$this->db->get('v_saldo_por_odontograma');
    return $result->result();

   }
   public function getDetalleTotalMonto($id,$event_id=''){
    $event_id=($event_id=='')? 0 : $event_id;

    $result=$this->db->query("CALL sp_total_saldo_odontograma(".$id.",".$event_id.")");
    return $result->row();
   }

   public function getOdontoPresupuestoDetalle($id,$event_id=''){
    if ($event_id!='') {
        $this->db->where('event_id', $event_id);
    }
    $this->db->where('patient_id', $id);
    $result=$this->db->get('transaction_lab');
    return $result->result();
   }
   public function getDetalleTotalMontoPresupuesto($id,$event_id=''){
    $event_id=($event_id=='')? 0 : $event_id;

    $result=$this->db->query("CALL total_presupuesto(".$id.",".$event_id.")");
    return $result->row();
   }

  public function insert_cart_lab($data)
  {
    $this->db->insert('transaction_lab', $data);
    return $this->db->insert_id();
  }
  public function insert_detalle_lab($data)
  {
    $this->db->insert('transaction_detail', $data);
  }
  public function insertarPago($data){
    $this->db->insert('payments', $data);
  }

  //pagos por el paciente 
  public function getPagosPaciente($patient_id,$event_id=''){
    if ($event_id!='') {
      $this->db->where('event_id', $event_id);
    }
    $this->db->where('patient_id', $patient_id);
    $result=$this->db->get('v_payments');
    return $result->result();
  }
  public function getTotalPagoPaciente($id,$event_id=''){
    $event_id=($event_id=='')? 0 : $event_id;
    $result=$this->db->query("CALL sp_total_pago_paciente(".$id.",".$event_id.")");
    return $result->row();
  }

  public function totalFinalSaldos($id,$event_id=''){
    $event_id=($event_id=='')? 0 : $event_id;
    $result=$this->db->query("CALL sp_total_general_monto(".$id.",".$event_id.")");
    return $result->row();
  }
  //////////////////////TODO HISTORIA CLINICA/////////////////
  ///////////////////////////////////////////////////////////////////
  public function insertarSeguimiento($data){
    $this->db->insert('seguimiento_paciente', $data);
  }
   private function _get_datatables_query_seguimiento($id_patient)
  {
    
    $this->db->select("seguimiento_id,fecha,mensaje");
    $this->db->from('seguimiento_paciente s');
    $this->db->join('patients p', 'p.patient_id = s.patient_id');
    $this->db->where('s.patient_id', $id_patient);
    $this->db->where('s.deleted', '0');

    //$this->db->where('pat.deleted', 1);
    $i = 0;
  
    foreach ($this->column_search2 as $item) // loop column 
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

        if(count($this->column_search2) - 1 == $i) //last loop
          $this->db->group_end(); //close bracket
      }
      $i++;
    }
    
    if(isset($_POST['order'])) // here order processing
    {
      $this->db->order_by($this->column_order2[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
    } 
    else if(isset($this->order2))
    {
      $order = $this->order2;
      $this->db->order_by(key($order), $order[key($order)]);
    }
  }

  function get_datatables_seguimiento($id_patient)
  {
    $this->_get_datatables_query_seguimiento($id_patient);
    if($_POST['length'] != -1)
    $this->db->limit($_POST['length'], $_POST['start']);
    $query = $this->db->get();
    return $query->result();
  }

  function count_filtered_seguimiento($id_patient)
  {
    $this->_get_datatables_query_seguimiento($id_patient);
    $query = $this->db->get();
    return $query->num_rows();
  }

  public function count_all_seguimiento($id_patient)
  {
    $this->db->where('id_patient', $id_patient);
    $this->db->from($this->table2);

    return $this->db->count_all_results();
  }
  public function delete_seguimiento_by_id($id_seguimiento){
    $this->db->where('seguimiento_id', $id_seguimiento);
    $data = array('deleted' => '1' );
    $this->db->update('seguimiento_paciente', $data);
  }

  ////////////////////EXAMEN FISICO///////////////////
  public function get_examen_fisico_by_id($id){
    $this->db->select('ex.*');
    $this->db->from('examen_fisico_paciente ex');
    $this->db->join('patients pat', 'pat.patient_id = ex.patient_id');
    $this->db->where('pat.patient_id',$id);
    $query = $this->db->get();
    return $query->row();
  }
  public function insertarExamenFisico($data){
    $this->db->insert('examen_fisico_paciente', $data);
  }
  public function updateExamenFisico($patient_id,$data){
    $this->db->where('patient_id', $patient_id);
    $this->db->update('examen_fisico_paciente', $data);
  }
  ////////////////////EXAMEN CLINICO///////////////////
  public function get_examen_clinico_by_id($id){
    $this->db->select('ex.*');
    $this->db->from('examen_clinico_paciente ex');
    $this->db->join('patients pat', 'pat.patient_id = ex.patient_id');
    $this->db->where('pat.patient_id',$id);
    $query = $this->db->get();
    return $query->row();
  }
  public function insertarExamenClinico($data){
    $this->db->insert('examen_clinico_paciente', $data);
  }
  public function updateExamenClinico($patient_id,$data){
    $this->db->where('patient_id', $patient_id);
    $this->db->update('examen_clinico_paciente', $data);
  }
  public function get_examen_anamnesis_by_id($id_patient){
    $this->db->select('ex.*');
    $this->db->from('anamnesis_paciente ex');
    $this->db->join('patients pat', 'pat.patient_id = ex.patient_id');
    $this->db->where('pat.patient_id',$id_patient);
    $query = $this->db->get();
    return $query->result();
  }
  public function insertAnamnesis($data){
    $this->db->insert('anamnesis_paciente', $data);
    return $this->db->insert_id();
  }

  public function updateAnamnesis($data,$campo,$paciente_id){
    $this->db->where('campo', $campo);
    $this->db->where('patient_id', $paciente_id);
    $this->db->update('anamnesis_paciente', $data);
  }
  public function verificarCampoAnam($paciente_id,$campo){
    $this->db->where('patient_id', $paciente_id);
    $this->db->where('campo', $campo);
    $this->db->from('anamnesis_paciente');
    $result=$this->db->get();
    return $result->row();
  }

}

/* End of file Treatment_model.php */
/* Location: ./application/models/Treatment_model.php */