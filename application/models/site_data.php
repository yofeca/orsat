<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

@session_start();

class site_data extends CI_Model {
    public function __construct(){
		parent::__construct();
		$this->load->database();
    }

    public function fetch_sites(){
    	$sql = "SELECT id, instrument_name FROM `sites`";
    	$q = $this->db->query($sql);
		$records = $q->result_array();

		return $records;
    }

    public function fetch_info($id=""){
    	if(!$id) return;

    	$sql = "SELECT * FROM `sites` WHERE id='$id' LIMIT 1";
    	$q = $this->db->query($sql);
		$records = $q->result_array();

		return $records[0];
    }

    public function fetch_qaqc($id=""){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);

		if(!$id) return;

		$sql = "SELECT * FROM `qaqc` WHERE `site_id`=" .$id. " LIMIT 1";
		$q = $this->db->query($sql);
		$record = $q->result_array();

		return $record[0];
	}

	public function fetch_latest_txo(){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		$controller = $this->controller;
		$start = $_GET['start'];
		$start += 0;
		$limit = 100;
				
		$sql = "SELECT * FROM `".$table."` WHERE 1 order by id DESC LIMIT $start, $limit";
		$export_sql = md5($sql);
		$_SESSION['export_sqls'][$export_sql] = $sql;
		$q = $this->db->query($sql);
		$records = $q->result_array();		
		
		$sql = "SELECT COUNT(`id`) AS `cnt` FROM `".$table."` WHERE 1" ;
		$q = $this->db->query($sql);
		$cnt = $q->result_array();
		$pages = ceil($cnt[0]['cnt']/$limit);
		
		$data = array();
		$data['records'] = $records;
		$data['export_sql'] = $export_sql;
		$data['pages'] = $pages;
		$data['start'] = $start;
		$data['limit'] = $limit;
		$data['cnt'] = $cnt[0]['cnt'];
		$data['controller'] = $controller;
		$data['content'] = $this->load->view($controller.'/main', $data, true);
		$this->load->view('layout/main', $data);
	}

	public function fetch_site_target_components($site_id=""){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);

		if(!trim($site_id)) return;
		
		$sql = "SELECT tc.*, cl.method_name, cl.alias  FROM `target_compound` tc INNER JOIN `compound_list` cl ON tc.compound_id=cl.id WHERE tc.`site_id` = '".db_escape($id)."' ORDER BY `compound_id` ASC";
		$q = $this->db->query($sql);
		$records = $q->result_array();
		return $records;
	}



	public function fetch_cvs($id=""){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);

		if(!trim($id)){
			redirect(site_url($controller));
		}

		$sql = "SELECT l.*, cl.method_name, cl.alias  FROM `cvs_compound` l INNER JOIN `compound_list` cl ON l.compound_id=cl.id WHERE l.`site_id` = '".db_escape($id)."' ORDER BY `compound_id` ASC";
		$q = $this->db->query($sql);
		$records = $q->result_array();
		return $records;
	}

	public function fetch_lcs($id=""){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);

		if(!trim($id)){
			redirect(site_url($controller));
		}

		$sql = "SELECT l.*, cl.method_name, cl.alias  FROM `lcs_compound` l INNER JOIN `compound_list` cl ON l.compound_id=cl.id WHERE l.`site_id` = '".db_escape($id)."' ORDER BY `compound_id` ASC";
		$q = $this->db->query($sql);
		$records = $q->result_array();
		return $records;
	}

	public function fetch_site_networks(){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);

		$sql = "SELECT * FROM `network` ORDER BY `name` ASC";
		$q = $this->db->query($sql);
		$records = $q->result_array();
		
		return $records;
	}

	public function fetch_site_standards($site_id,$type){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);

		$sql = "SELECT s.*, c.cylinder FROM `standards` s INNER JOIN `coa` c ON s.coa_id=c.id WHERE s.site_id=$site_id AND s.type='$type'";
		$q = $this->db->query($sql);
		$records = $q->result_array();
		
		return $records;
	}

	public function fetch_coa($type){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);

		$sql = "SELECT * FROM `coa` WHERE `type`='$type'";
		$q = $this->db->query($sql);
		$records = $q->result_array();
		
		return $records;
	}

	public function rmExtraChar($str){
		
		$extra_char = array(" ","-",",");
		$str = str_replace($extra_char, "", $str);

		return $str;
	}
}