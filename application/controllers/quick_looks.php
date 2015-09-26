<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
class quick_looks extends CI_Controller {
	var $table;
	var $controller;

	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->table = "txo_dumps";
		$this->controller = "quick_looks";
	}

	public function index(){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		$controller = $this->controller;

		$date = $_GET['dd'];
		$site_id = $_GET['sid'];

		$txo = $this->txo_data->fetch_daily_txo($date,$site_id);

		$site = $this->site_data->fetch_info($site_id);
		$network_id = $site['network_id'];

		$tc = count($txo);
		for($i=0; $i<$tc; $i++){
			$txo[$i]['components'] = $this->txo_data->fetch_components($txo[$i]['id']);
		}
		
		$data = array();
		$data['headera'] = $this->component_data->fetch_network_target_components('A',$network_id);
		$data['headerb'] = $this->component_data->fetch_network_target_components('B',$network_id);
		$data['txo'] = $txo;
		$data['controller'] = $controller;
		$data['content'] = $this->load->view($controller.'/main', $data, true);
		$this->load->view('layout/main', $data);
	}
}
?>