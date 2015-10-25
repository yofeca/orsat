<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
class site_quick_look extends CI_Controller {

	var $table;
	var $controller;

	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->load->model('standards');
		$this->table = "txo_dumps";
		$this->controller = "site_quick_look";
	}

	public function index(){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		$controller = $this->controller;

		$date = date('Y-m-d', strtotime($_GET['dd']));
		$site_id = $_GET['sid'];
		$view = $_GET['v'];

		$txo = $this->txo_data->fetch_daily_txo($date,$site_id);

		$site = $this->site_data->fetch_info($site_id);
		$network_id = $site['network_id'];

		$tc = count($txo);
		for($i=0; $i<$tc; $i++){
			$txo[$i]['components'] = $this->txo_data->fetch_components($txo[$i]['filename'],'');
		}
		
		$data = array();

		$data['site_info'] = $site;
		$headera = $this->component_data->fetch_network_target_components('A',$network_id);
		$headerb = $this->component_data->fetch_network_target_components('B',$network_id);

		$data['headera'] = $headera;
		$data['headerb'] = $headerb;
		$data['txo'] = $txo;
		$data['controller'] = $controller;

		if($view=='time'){
			$data['modea'] = $this->component_data->fetch_mode($date,$site_id,'A',$headera);
			$data['modeb'] = $this->component_data->fetch_mode($date,$site_id,'B',$headerb);
			$data['standards'] = $this->component_data->fetch_standard_components('RTS','');
			$data['rts_summary'] = $this->component_data->fetch_rts_summary($date,$site_id);
			$data['rts'] = $this->txo_data->fetch_daily_standards($date,$site_id,'Q','');
			$data['content'] = $this->load->view($controller.'/rts', $data, true);
		}else{
			$data['standards'] = $this->component_data->fetch_standard_components('LCS_CVS','');
			$data['lcs'] = $this->txo_data->fetch_daily_standards($date,$site_id,'E','');
			$data['cvs'] = $this->txo_data->fetch_daily_standards($date,$site_id,'C','');
			$data['content'] = $this->load->view($controller.'/main', $data, true);
		}
		
		$this->load->view('layout/main', $data);
	}

}
?>