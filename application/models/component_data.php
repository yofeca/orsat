<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

@session_start();

class component_data extends CI_Model {
    public function __construct(){
		parent::__construct();
		$this->load->database();
    }

	public function fetch_airs_file($ch=""){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);

		if($ch=='A')
			$ch2 = 'B';
		else
			$ch2 = 'A';

		$sql = "SELECT * FROM `airs_list` WHERE id NOT IN (SELECT airs_list_id FROM `tceq` WHERE channel='".$ch."') AND id NOT IN (SELECT airs_list_id FROM `tceq` WHERE channel='$ch2') ORDER BY `component_name` ASC";
		$q = $this->db->query($sql);
		$records = $q->result_array();
		return $records;
	}

	public function fetch_tceq($ch=""){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);

		$sql = "SELECT al.*, t.id as tceq_id, t.channel FROM `airs_list` al RIGHT JOIN `tceq` t ON al.id=t.airs_list_id WHERE t.channel= '".$ch."' ORDER BY sort ASC";

		$q = $this->db->query($sql);
		$records = $q->result_array();
		return $records;
	}

	public function fetch_tceq_target_components($ch="",$nid=""){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);

		$sql = "SELECT al.*,t.id as tceq_id, t.sort 
		FROM `airs_list` al LEFT JOIN `tceq` t ON al.id=t.airs_list_id 
		WHERE al.id NOT IN (SELECT airs_list_id FROM `network_target_components` 
		WHERE `network_id`='$nid') AND t.channel='$ch' ORDER BY t.sort ASC";
		
		$q = $this->db->query($sql);
		$records = $q->result_array();
		return $records;
	}

	public function fetch_network_target_components($ch="",$nid=""){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);

		$sql = "SELECT al.*
		FROM `airs_list` al LEFT JOIN `tceq` t ON al.id=t.airs_list_id
		LEFT JOIN `network_target_components` ntc ON t.airs_list_id=ntc.airs_list_id WHERE t.channel='$ch' AND ntc.network_id='$nid' ORDER BY ntc.sort";

		//$sql = "SELECT ntc.*, al.component_name FROM `tceq` tc RIGHT JOIN `network_target_components` ntc ON al.id=tc.airs_list_id WHERE tc.channel= '".$ch."' ORDER BY sort ASC";
		$q = $this->db->query($sql);
		$records = $q->result_array();

		return $records;
	}

	public function fetch_standard_components($type,$event,$id){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);

		if($event=='edit'){
			$sql = "SELECT al.*, al.id as airs_list_id, cc.value, t.channel, t.sort FROM `airs_list` al 
			RIGHT JOIN `tceq` t ON al.id=t.airs_list_id RIGHT JOIN `coa_components` cc ON cc.airs_list_id=t.airs_list_id WHERE cc.coa_id='$id'";
			if($type){
				$sql .= " AND cc.type='$type'";
			}
			$sql .= " ORDER BY t.sort ASC";
		}else{
			$sql = "SELECT sc.id, t.id as tceq_id, al.id AS airs_list_id, al.cas, al.component_name, al.alias, al.carbon_no, t.channel, t.sort FROM `airs_list` al 
			RIGHT JOIN `tceq` t ON al.id=t.airs_list_id RIGHT JOIN `standard_components` sc ON sc.airs_list_id=t.airs_list_id WHERE sc.type='$type' ORDER BY t.sort ASC";	
		}

		$q = $this->db->query($sql);
		$records = $q->result_array();
		return $records;
	}
	public function fetch_standard_tceq_components($type,$channel){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);

		$sql = "SELECT t.id, al.id AS airs_list_id, al.cas, al.component_name, al.alias, al.carbon_no, t.channel, t.sort FROM `airs_list` al 
			RIGHT JOIN `tceq` t ON al.id=t.airs_list_id WHERE t.airs_list_id NOT IN (SELECT airs_list_id FROM `standard_components` sc WHERE sc.type='$type') AND t.channel='$channel' ORDER BY t.sort ASC";

		$q = $this->db->query($sql);
		$records = $q->result_array();
		return $records;
	}
}