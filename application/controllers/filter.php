<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
class filter extends CI_Controller {
	var $table;
	var $controller;
	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->table = "txo_dumps";
		$this->controller = "filter";
	}
	public function index(){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		$controller = $this->controller;
		$start = $_GET['start'];
		$filter = $_GET['filter'];
		$start += 0;
		$limit = 50;
		$search = strtolower(trim($_GET['search']));
		$searchx = trim($_GET['search']);
		extract($_POST);
				
		$sql = "select id from `".$table."` where 1";
		
		if($sitename !=""){
			$sql .= " AND LOWER(`Instrument Name`) LIKE LOWER('%". $sitename ."%')";
		}
		
		if($year !=0 ){
			$sql .= " AND `Date` LIKE '%". $year ."%'";
		}
		
		if($month !=0 ){
			$sql .= " AND `Sample Number` LIKE '%". $month ."/" ."%'";
		}
		
		if($day !=0 ){
			$sql .= " AND `Sample Number` LIKE '%/" . $day ."%'";
		}
		
		if($hour !=0 ){
			$sql .= " AND `Sample Number` LIKE '%" . $hour .":00%'";
		}
		
		$sql .= " order by id desc limit $start, $limit";
			
		$_SESSION['export_sqls'][$export_sql] = $sql;
		$q = $this->db->query($sql);
		$records = $q->result_array();
		
		$count = count($records);
		$txrecords = array();

		for($i=0; $i<$count; $i++){		
			$sql = "SELECT t.filename, t.Date, t.Channel, c.Component, c.Amount FROM `txo_dumps` t INNER JOIN `column_values` c ON t.id=c.txo_id WHERE t.id=" . $records[$i]['id'];
			$q = $this->db->query($sql);
			$txrecords[$i] = $q->result_array();
			//$txrecords[$i][0] = $r;			
		}
		/*
		for($i=0; $i<$count; $i++){	
			$sql = "SELECT * FROM `column_values` WHERE txo_id=" . $records[$i]['id'];
			$q = $this->db->query($sql);
			$r = $q->result_array();
			
			$txrecords[$i][1] = $r;
		}*/
		$sql = "select count(id) as `cnt`  from `".$table."` where 1 ";
		
		if($sitename !=""){
			$sql .= "AND LOWER(`Instrument Name`) LIKE LOWER('%". $sitename ."%')";
		}
		
		if($year !=0 ){
			$sql .= " AND `Date` LIKE '%". $year ."%'";
		}
		
		if($month !=0 ){
			$sql .= " AND `Sample Number` LIKE '%". $month ."/" ."%'";
		}
		
		if($day !=0 ){
			$sql .= " AND `Sample Number` LIKE '%/" . $day ."%'";
		}
		
		if($hour !=0 ){
			$sql .= " AND `Sample Number` LIKE '%" . $hour .":00%'";
		}

		$q = $this->db->query($sql);
		$cnt = $q->result_array();
		$pages = ceil($cnt[0]['cnt']/$limit);
		
		//echo "<pre>";
		//print_r($tx_records);
		//echo "</pre>";
		//return;
		$data = array();
		$data['txrecords'] = $txrecords;
		$data['records'] = $records;		
		$data['export_sql'] = $export_sql;
		$data['pages'] = $pages;
		$data['start'] = $start;
		$data['limit'] = $limit;
		$data['search'] = $searchx;
		$data['filter'] = $filter;
		$data['cnt'] = $cnt[0]['cnt'];
		$data['controller'] = $controller;
		$data['content'] = $this->load->view($controller.'/main', $data, true);
		$this->load->view('layout/main', $data);		
	}
}
?>