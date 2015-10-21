<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

@session_start();

class Standards extends CI_Model {
    public function __construct(){
		parent::__construct();
		$this->load->database();
    }

	/**
	 * [fetch_daily_standards description]
	 * @param  string $date  	[ date format (yyyy-mm-dd) ]
	 * @param  int $site_id  	[ site id ]
	 * @param  char $standard 	[ letter of standard based on sample types ]
	 * @param  char  $[name] [<description>]
	 * @return Array()          [ list of standards for the current date ]
	 */
	public function fetch_rts($date,$site_id,$standard,$channel){

		//fetch standard filenames
		$sql = "SELECT `filename`, `data_acquisition_time`, `channel` FROM `txo_dumps` 
				WHERE DATE_FORMAT(`data_acquisition_time`, '%Y-%m-%d')='$date' 
				AND `site_id`=$site_id AND SUBSTRING(`filename`,-9,1)='Q' AND `channel`='$channel' ORDER BY `data_acquisition_time` ASC";
		$q = $this->db->query($sql);
		$list_standards = $q->result_array();
	
		//fetch standard component values
		if(! empty($list_standards) ){
			
			$tls = count($list_standards);
			$components = array();

			for($i=0; $i<$tls; $i++){
				$sql = "SELECT al.component_name, al.alias, al.carbon_no, t.sort, t.channel, cc.value, cv.amount, cv.time, cv.area FROM `airs_list` al
						LEFT JOIN `tceq` t ON al.id=t.airs_list_id
						LEFT JOIN `coa_components` cc ON al.id=cc.airs_list_id
						RIGHT JOIN `component_values` cv ON REPLACE(REPLACE(al.component_name,'-',''),',','') = REPLACE(REPLACE(cv.component_name,'-',''),',','')
						WHERE cv.filename='".$list_standards[$i]['filename']."' AND cc.coa_id='".$coa['coa_id']."' AND t.channel='A' ORDER BY t.sort ASC";
				$q = $this->db->query($sql);
				$components['A'][$i] = $q->result_array();
			}
			$components['coa'] = $coa;
			return $components;
		}

		return false;
	}

	/**
	 * [fetch_daily_standards description]
	 * @param  string $date  	[ date format (yyyy-mm-dd) ]
	 * @param  int $site_id  	[ site id ]
	 * @param  char $standard 	[ RTS/LCS/CVS of standard based on sample types ]
	 * @param  date $date_off 	[ the current date in case previous search has no result ]
	 * @return Array()          [ cylinder information ]
	 */
	public function fetch_cylinder_info($date,$site_id,$standard){
		//fetch the current standard values
		$sql = "SELECT *, c.cylinder 
				FROM `coa` c 
				LEFT JOIN `standards` s 
				ON c.id=s.coa_id 
				WHERE s.site_id='$site_id' 
				AND DATE_FORMAT('$date','%Y-%m-%d') BETWEEN DATE_FORMAT(s.date_on,'%Y-%m-%d') AND DATE_FORMAT(s.date_off,'%Y-%m-%d') 
				AND s.type='$standard'
				ORDER BY s.date_on DESC LIMIT 1";

		$q = $this->db->query($sql);
		$coa = $q->result_array();

		if(empty($coa)){
			$sql = "SELECT *, c.cylinder 
				FROM `coa` c 
				LEFT JOIN `standards` s 
				ON c.id=s.coa_id 
				WHERE s.site_id='$site_id' 
				AND DATE_FORMAT('$date','%Y-%m-%d') BETWEEN DATE_FORMAT(s.date_on,'%Y-%m-%d') AND CURDATE() 
				AND s.type='$standard'
				ORDER BY s.date_on DESC LIMIT 1";

			$q = $this->db->query($sql);
			$coa = $q->result_array();
		}
		return $coa[0];
	}

} //end of Standards