<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
class standards extends CI_Controller {
	var $table;
	var $controller;

	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->table = "tceq";
		$this->controller = "standards";
	}

	public function index(){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		$controller = $this->controller;	
		
		$data = array();
		$data['lcs_cvs'] = $this->component_data->fetch_standard_components('LCS_CVS','');
		$data['rt'] = $this->component_data->fetch_standard_components('RTS','');
		$data['controller'] = $controller;
		$data['content'] = $this->load->view($controller.'/main', $data, true);
		$this->load->view('layout/main', $data);
	}

	public function ajax_sortable(){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		
		$data = $_POST['data'];

		parse_str($data,$str);

		$item = $str['list-item'];

		foreach ($item as $k => $v){
			$sort_number = $k + 1;
			$sql = "UPDATE `".$table."` SET sort=$sort_number WHERE id=$v";
			$q = $this->db->query($sql);
		}
	}
	public function ajax_add_standard_components(){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		$controller = $this->controller;
		$error = false;		
		
		$data = $_POST['data'];
		$t = count($data);

		if(!$error){	
			for($i=0; $i<$t; $i++){
				$d = explode('-',$data[$i]);
				$sql = "INSERT INTO `standard_components` SET ";					
				$sql .= "  `airs_list_id` = '".mysql_real_escape_string($d[2])."'";
				$sql .= " , `type` = '".strtoupper($d[0])."'";
				$this->db->query($sql);
			}
		}
	}

	public function ajax_delete_tceq_component($id){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		
		if(!$id){
			$id = $_POST['id'];
		}

		$data = explode('-',$id);

		$sql = "DELETE FROM `standard_components` WHERE id = '".$data[1]."' AND type='".strtoupper($data[0])."' LIMIT 1";
		$q = $this->db->query($sql);
		
		return true;
	}

	public function ajax_delete_all_tceq_component($id){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		
		if(!$id) return;

		$id = explode('-',$id);
		$data = $_POST['chVal'];

		$t = count($data);

		for($i=0; $i<$t; $i++){
			$sql = "DELETE FROM `standard_components` WHERE id = '".$data[$i]."' AND type='".strtoupper($id[0])."' LIMIT 1";
			$q = $this->db->query($sql);
		}	
		return true;
	}

	public function ajax_fetch_tceq_components($id){

		if(!$id){
			$id = $_POST['id'];
		}

		$key = explode('-',$id);

		$tceq = $this->component_data->fetch_standard_tceq_components($key[0],strtoupper($key[1])); //(standard,channel)
	
		echo json_encode($tceq);
	}
}
?>