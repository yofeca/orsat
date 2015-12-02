<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

@session_start();

class txo_data extends CI_Model 
{
    public function __construct()
    {
		parent::__construct();
		$this->load->database();
    }

    public function fetch_header($key)
    {
    	if(! $key) return;

    	$sql = "SELECT * FROM `txo_dumps` WHERE `filename` = '$key' LIMIT 1";

		$q = $this->db->query($sql);
		$record = $q->result_array();
		
		return $record[0];
    }

    public function fetch_components($key)
    {
    	if(! $key) return;

    	$sql = "SELECT * FROM `component_values` WHERE `filename`='$key'";

		$q = $this->db->query($sql);
		$records = $q->result_array();
		
		return $records;
    }

    public function fetch_total_components($key)
    {

    	$sql = "SELECT * FROM `txo_total_components` WHERE `filename`='$key'";
		$q = $this->db->query($sql);
		$records = $q->result_array();
		
		return $records[0];
    }

    public function fetch_monthly_txo($site_id,$start_date,$end_date){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);

		if(!$site_id) return;

		$sql = "SELECT `site_id`, DATE_FORMAT(`data_acquisition_time`, '%Y-%m-%d') AS dd FROM `txo_dumps` WHERE `site_id`='$site_id'";

		if($start_date || $end_date){
			$sql .= " AND DATE_FORMAT(`data_acquisition_time`, '%Y-%m-%d') BETWEEN DATE_FORMAT('$start_date', '%Y-%m-%d') AND DATE_FORMAT('$end_date', '%Y-%m-%d')";
		}

		$sql .= " GROUP BY dd";

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

		$sql = "SELECT td.id, td.filename, td.data_acquisition_time, td.channel, td.sequence_file, td.noise_threshold, td.area_threshold, ttc.pp_carbon, ttc.area, ttc.method_rt
		FROM `txo_dumps` td
		LEFT JOIN `txo_total_components` ttc ON td.id=ttc.txo_dump_id
		WHERE DATE_FORMAT(td.data_acquisition_time, '%Y-%m-%d') = DATE_FORMAT('$date','%Y-%m-%d') AND td.site_id = '$site_id'
		ORDER BY td.data_acquisition_time";
		$q = $this->db->query($sql);
		$txo = $q->result_array();

		return $txo;

	}

	/**
	 * [fetch_daily_standards description]
	 * @param  string $date  	[ date format (yyyy-mm-dd) ]
	 * @param  int $site_id  	[ site id ]
	 * @param  char $standard 	[ letter of standard based on sample types ]
	 * @param  date $date_off 	[ the current date in case previous search has no result ]
	 * @return Array()          [ list of standards for the current date ]
	 */
	private function fetch_current_coa_value($date,$site_id,$standard,$date_off){
		//fetch the current standard values
		$sql = "SELECT *, c.cylinder FROM `coa` c LEFT JOIN `standards` s ON c.id=s.coa_id WHERE s.site_id='$site_id' AND DATE_FORMAT('$date','%Y-%m-%d') BETWEEN DATE_FORMAT(s.date_on,'%Y-%m-%d')";

		if(!$date_off){
			$sql .=" AND DATE_FORMAT(s.date_off,'%Y-%m-%d')";
		}else{
			$sql .=" AND DATE_FORMAT('$date_off','%Y-%m-%d')";
		}
		
		switch($standard){
			case 'E':
				$sql .= " AND s.type='LCS'";
				break;
			case 'C':
				$sql .= " AND s.type='CVS'";
				break;
			case 'Q':
				$sql .= " AND s.type='RTS'";
				break;
		}

		$sql .= " ORDER BY s.date_on DESC LIMIT 1";
		$q = $this->db->query($sql);
		$coa = $q->result_array();

		return $coa[0];

	}
	/**
	 * [fetch_daily_standards description]
	 * @param  string $date  	[ date format (yyyy-mm-dd) ]
	 * @param  int $site_id  	[ site id ]
	 * @param  char $standard 	[ letter of standard based on sample types ]
	 * @param  char  $[name] [<description>]
	 * @return Array()          [ list of standards for the current date ]
	 */
	public function fetch_daily_standards($date,$site_id,$standard,$ch){

		//fetch standard filenames
		$sql = "SELECT `filename`, `data_acquisition_time`, `channel` FROM `txo_dumps` 
				WHERE DATE_FORMAT(`data_acquisition_time`, '%Y-%m-%d')='$date' 
				AND `site_id`=$site_id AND SUBSTRING(`filename`,-9,1)='$standard' ORDER BY `data_acquisition_time` ASC";

		$q = $this->db->query($sql);
		$list_standards = $q->result_array();

		//fetch certificate of analysis values
		$coa = $this->fetch_current_coa_value($date,$site_id,$standard,'');
		if( empty($coa) ){
			$coa = $this->fetch_current_coa_value($date,$site_id,$standard,date('Y-m-d'));
		}
		
		//fetch standard component values
		if(! empty($list_standards) ){
			
			$tls = count($list_standards);
			$components = array();
			
			$ctr1 = $ctr2 = 0;

			for($i=0; $i<$tls; $i++){
				if($list_standards[$i]['channel']=='A'){
					$sql = "SELECT al.component_name, al.alias, al.carbon_no, t.sort, t.channel, cc.value, cv.amount, cv.time, cv.area FROM `airs_list` al
							LEFT JOIN `tceq` t ON al.id=t.airs_list_id
							LEFT JOIN `coa_components` cc ON al.id=cc.airs_list_id
							RIGHT JOIN `component_values` cv ON REPLACE(REPLACE(al.component_name,'-',''),',','') = REPLACE(REPLACE(cv.component_name,'-',''),',','')
							WHERE cv.filename='".$list_standards[$i]['filename']."' AND cc.coa_id='".$coa['coa_id']."' AND t.channel='A' ORDER BY t.sort ASC";
					$q = $this->db->query($sql);
					$components['A'][$ctr1] = $q->result_array();
					$ctr1++;
				}else if($list_standards[$i]['channel']=='B'){
					$sql = "SELECT al.component_name, al.alias, al.carbon_no, t.sort, t.channel, cc.value, cv.amount, cv.time, cv.area FROM `airs_list` al
							LEFT JOIN `tceq` t ON al.id=t.airs_list_id
							LEFT JOIN `coa_components` cc ON al.id=cc.airs_list_id
							RIGHT JOIN `component_values` cv ON REPLACE(REPLACE(al.component_name,'-',''),',','') = REPLACE(REPLACE(cv.component_name,'-',''),',','')
							WHERE cv.filename='".$list_standards[$i]['filename']."' AND cc.coa_id='".$coa['coa_id']."' AND t.channel='B' ORDER BY t.sort ASC";
					$q = $this->db->query($sql);
					$components['B'][$ctr2] = $q->result_array();
					$ctr2++;
				}
			}
			$components['coa'] = $coa;
			return $components;
		}

		return false;
	}

	public function fetch_rts_summary($data,$site_id,$channel){
		$summary = array();

		//fetch standard filenames
		$sql = "SELECT `filename`, `data_acquisition_time`, `channel` FROM `txo_dumps` 
				WHERE DATE_FORMAT(`data_acquisition_time`, '%Y-%m-%d')='$date' 
				AND `site_id`=$site_id AND SUBSTRING(`filename`,-9,1)='$standard' ORDER BY `data_acquisition_time` ASC";

		$q = $this->db->query($sql);
		$list_standards = $q->result_array();

	}

	public function printr($arr_data){
		echo '<pre>';
		print_r($arr_data);
		echo '</pre>';
	}
}