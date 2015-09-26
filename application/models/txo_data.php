<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

@session_start();

class txo_data extends CI_Model {
    public function __construct(){
		parent::__construct();
		$this->load->database();
    }

    public function fetch_header($id){

    	$sql = "SELECT * FROM `txo_dumps` WHERE `id` = '".db_escape($id)."' LIMIT 1";
		$q = $this->db->query($sql);
		$record = $q->result_array();
		
		return $record[0];
    }

    public function fetch_components($id){
    	$sql = "SELECT * FROM `component_values` WHERE `txo_dump_id` = '".db_escape($id)."'";

		$q = $this->db->query($sql);
		$records = $q->result_array();
		
		return $records;
    }

    public function fetch_total_components($id){

    	$sql = "SELECT * FROM `txo_total_components` WHERE `txo_dump_id` = '".db_escape($id)."'";
		$q = $this->db->query($sql);
		$records = $q->result_array();
		
		return $records[0];
    }

    public function fetch_monthly_txo($site_id){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);

		if(!$site_id) return;

		$sql = "SELECT `site_id`, DATE_FORMAT(`data_acquisition_time`, '%Y-%m-%d') AS dd FROM `txo_dumps` WHERE `site_id`='$site_id' GROUP BY dd LIMIT 31 ";
		$q = $this->db->query($sql);
		$monthly_txo = $q->result_array();

		$txo = array();
		$mt = count($monthly_txo);
		for($i=0; $i<$mt; $i++){
			$monthly_txo[$i]['list']= $this->fetch_daily_txo($monthly_txo[$i]['dd'],$site_id);
		}

		return $monthly_txo;
	}

	public function fetch_daily_txo($date,$site_id){

		if(!$date) return;

		$sql = "SELECT td.id, td.filename, td.data_acquisition_time, td.channel, ttc.pp_carbon, ttc.area, ttc.method_rt
		FROM `txo_dumps` td
		LEFT JOIN `txo_total_components` ttc ON td.id=ttc.txo_dump_id
		WHERE DATE_FORMAT(td.data_acquisition_time, '%Y-%m-%d') = DATE_FORMAT('$date','%Y-%m-%d') AND td.site_id = '$site_id'
		ORDER BY td.data_acquisition_time";
		$q = $this->db->query($sql);
		$txo = $q->result_array();

		return $txo;
	}

}