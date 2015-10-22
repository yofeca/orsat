<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

@session_start();

class component_data extends CI_Model {
    public function __construct(){
		parent::__construct();
		$this->load->database();
    }

	/***
	 * @param string $ch
	 * @return mixed
	 */
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
	
	/**
	 * [fetch_standard_components description]
	 * @param  string $type  [lcs,cvs,rts]
	 * @param  int $id    	 [canister/certificate analysis id]
	 * @return Array()       [description]
	 */
	public function fetch_standard_components($type,$id){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		if($id){
			$sql = "SELECT al.*, al.id as airs_list_id, cc.value, t.channel, t.sort FROM `airs_list` al 
			RIGHT JOIN `tceq` t ON al.id=t.airs_list_id RIGHT JOIN `coa_components` cc ON cc.airs_list_id=t.airs_list_id WHERE cc.coa_id='$id'";
			if($type){
				$sql .= " AND cc.type='$type'";
			}
			
		}else{
			$sql = "SELECT sc.id, t.id as tceq_id, al.id AS airs_list_id, al.cas, al.component_name, al.alias, al.carbon_no, t.channel, t.sort FROM `airs_list` al 
			RIGHT JOIN `tceq` t ON al.id=t.airs_list_id RIGHT JOIN `standard_components` sc ON sc.airs_list_id=t.airs_list_id WHERE sc.type='$type'";	
		}
		$sql .= " ORDER BY t.channel, t.sort ASC";
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

	public function fetch_rts_summary($date,$site_id){
		/*$sql = "SELECT td.id, td.filename, td.data_acquisition_time, td.channel, ttc.pp_carbon, ttc.area, ttc.method_rt
		FROM `txo_dumps` td
		LEFT JOIN `txo_total_components` ttc ON td.id=ttc.txo_dump_id
		WHERE DATE_FORMAT(td.data_acquisition_time, '%Y-%m-%d') = DATE_FORMAT('$date','%Y-%m-%d') AND td.site_id = '$site_id'
		ORDER BY td.data_acquisition_time";*/
		
		$rts_summary = array();

		$sql = "SELECT `component_name`, `channel`, AVG(`time`) as value
		FROM `component_values` 
		WHERE DATE_FORMAT(`data_acquisition_time`,'%Y-%m-%d')=DATE_FORMAT('$date','%Y-%m-%d') 
		AND `site_id`='$site_id' GROUP BY `component_name` ";
		
		$q = $this->db->query($sql);
		$avg = $q->result_array();
		$rts_summary['average'] = $avg;


		$sql = "SELECT `component_name`, `channel`, STDDEV(`time`) as value
		FROM `component_values` 
		WHERE DATE_FORMAT(`data_acquisition_time`,'%Y-%m-%d')=DATE_FORMAT('$date','%Y-%m-%d') 
		AND `site_id`='$site_id' GROUP BY `component_name` ORDER BY `channel` ASC";
		
		$q = $this->db->query($sql);
		$avg = $q->result_array();
		$rts_summary['stdev'] = $avg;


		$sql = "SELECT `component_name`, `channel`, MIN(`time`) as value
		FROM `component_values` 
		WHERE DATE_FORMAT(`data_acquisition_time`,'%Y-%m-%d')=DATE_FORMAT('$date','%Y-%m-%d') 
		AND `site_id`='$site_id' GROUP BY `component_name` ORDER BY `channel` ASC";
		
		$q = $this->db->query($sql);
		$avg = $q->result_array();
		$rts_summary['min'] = $avg;

		$sql = "SELECT `component_name`, `channel`, MAX(`time`) as value
		FROM `component_values` 
		WHERE DATE_FORMAT(`data_acquisition_time`,'%Y-%m-%d')=DATE_FORMAT('$date','%Y-%m-%d') 
		AND `site_id`='$site_id' GROUP BY `component_name` ORDER BY `channel` ASC";
		
		$q = $this->db->query($sql);
		$avg = $q->result_array();
		$rts_summary['max'] = $avg;

		//fetch_mode
		return $rts_summary;
	}

	public function fetch_mode($date,$site_id,$channel,$header){
		$extra_char = array(" ","-",",");
		$th = count($header);
		$mode = array();

		for($i=0; $i<$th; $i++){
			$new_header = str_replace($extra_char, "", $header[$i]['component_name']);
			$sql = "SELECT component_name, time, channel,occurs
					FROM
						(
							SELECT time,channel,component_name,occurs FROM 
								(
									SELECT component_name, channel, time, count(*) as occurs
									FROM `component_values`
									WHERE DATE_FORMAT(`data_acquisition_time`,'%Y-%m-%d') = DATE_FORMAT('$date','%Y-%m-%d') 
									AND `channel`= '$channel' AND `site_id`='$site_id' 
									AND REPLACE(REPLACE(REPLACE(`component_name`,' ',''),'-',''),',','') ='$new_header'
									GROUP BY time
								)T1,
								(
									SELECT count(*) as maxoccurs
									FROM `component_values`
									WHERE DATE_FORMAT(`data_acquisition_time`,'%Y-%m-%d') = DATE_FORMAT('$date','%Y-%m-%d') 
									AND `channel`= '$channel' AND `site_id`='$site_id' 
									AND REPLACE(REPLACE(REPLACE(`component_name`,' ',''),'-',''),',','') ='$new_header'
									GROUP BY time
									ORDER BY count(*) DESC
									LIMIT 1
								)T3
							WHERE T1.occurs = T3.maxoccurs
						)T4
					GROUP BY occurs";

			$q = $this->db->query($sql);
			$result = $q->result_array();
			$mode[$i] = $result[0];
		}
		//$this->txo_data->printr($mode);
		return $mode;
	}
}