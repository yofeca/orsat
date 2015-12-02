<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
class site_info extends CI_Controller {
	var $table;
	var $controller;

	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->table = "sites";
		$this->controller = "site_info";
	}

	public function index(){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		$controller = $this->controller;

		$id = isset($_GET['sid']) ? trim($_GET['sid']) : 0;
		$start_date = isset($_GET['sd']) ? trim($_GET['sd']) : '';
		$end_date = isset($_GET['ed']) ? trim($_GET['ed']) : '';
		//$latest_txo = $this->fetch_latest_txo($id);
		//$tc = $this->fetch_target_compounds($id);
		//$lcs = $this->fetch_lcs($id);
		//$cvs = $this->fetch_cvs($id);
		$site_info = $this->site_data->fetch_info($id);

		$data = array();
		$data['site_info'] = $site_info;
		$data['target_components_a'] = $this->component_data->fetch_network_target_components('A',$site_info['network_name']);
		$data['target_components_b'] = $this->component_data->fetch_network_target_components('B',$site_info['network_name']);
		$data['lcs'] = $this->site_data->fetch_site_standards($id,'LCS');
		$data['cvs'] = $this->site_data->fetch_site_standards($id,'CVS');
		$data['rts'] = $this->site_data->fetch_site_standards($id,'RTS');
		$data['qaqc'] = $qaqc = $this->site_data->fetch_qaqc($id);
		$data['site_txo_data'] = $this->load->view($controller.'/latest-txo', array('txo'=>$this->txo_data->fetch_monthly_txo($id,$start_date, $end_date)), true);
		$data['controller'] = $controller;
		$data['content'] = $this->load->view($controller.'/main', $data, true);
		$this->load->view('layout/main', $data);
	}
}
?>