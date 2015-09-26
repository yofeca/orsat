<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
class tceq extends CI_Controller {
	var $table;
	var $controller;

	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->table = "tceq";
		$this->controller = "tceq";
	}

	public function index(){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		$controller = $this->controller;	
		
		$data = array();
		$data['tceq_a'] = $this->component_data->fetch_tceq('A');
		$data['tceq_b'] = $this->component_data->fetch_tceq('B');
		$data['airsfile_a'] = $this->component_data->fetch_airs_file('A');
		$data['airsfile_b'] = $this->component_data->fetch_airs_file('B');
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
	public function ajax_add_tceq_components(){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		$controller = $this->controller;
		$error = false;		
		
		$data = $_POST['data'];
		$t = count($data);

		if(!$error){	
			for($i=0; $i<$t; $i++){
				$d = explode('_',$data[$i]);
				$sql = "INSERT INTO `".$table."` SET ";					
				$sql .= "  `airs_list_id` = '".mysql_real_escape_string($d[0])."'";
				$sql .= " , `channel` = '".$d[2]."'";
				$this->db->query($sql);
			}
		}
	}

	public function ajax_delete_tceq_component(){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		
		if(!$id){
			$id = $_POST['id'];
		}
		
		$id = db_escape($id);
		$sql = "delete from `".$table."` where id = '".$id."' limit 1";
		$q = $this->db->query($sql);
		
		return true;
	}
	public function ajax_delete_all_tceq_component($id){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		
		if(!$id) return;

		$id = explode('_',$id);
		$data = $_POST['chVal'];

		$t = count($data);

		for($i=0; $i<$t; $i++){
			$sql = "DELETE FROM `$table` WHERE id = '".$data[$i]."' AND channel='".strtoupper($id[1])."' LIMIT 1";
			$q = $this->db->query($sql);
		}	
		return true;
	}
}
?>