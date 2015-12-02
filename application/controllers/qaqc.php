<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
class qaqc extends CI_Controller {
	var $table;
	var $controller;

	var $site_id;

	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->table = "qaqc";
		$this->controller = "qaqc";
	}

	public function index(){
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

	public function search(){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		$controller = $this->controller;
		$start = $_GET['start'];
		$filter = $_GET['filter'];
		$start += 0;
		$limit = 100;
		$search = strtolower(trim($_GET['search']));
		$searchx = trim($_GET['search']);
		
		$sql = "SELECT * FROM `".$table."`  WHERE 1 ";
		if($search != ''){
			$sql .= "AND LOWER(`".$filter."`) LIKE '%".db_escape($search)."%'";
		}
		$sql .= " ORDER BY id DESC LIMIT $start, $limit" ;

		$export_sql = md5($sql);
		$_SESSION['export_sqls'][$export_sql] = $sql;
		$q = $this->db->query($sql);
		$records = $q->result_array();
				
		$sql = "SELECT COUNT(id) AS `cnt`  FROM `".$table."` WHERE 1 ";
		if($search != ''){
			$sql .= "AND LOWER(`".$filter."`) LIKE '%".db_escape($search)."%'";
		}
		
		$q = $this->db->query($sql);
		$cnt = $q->result_array();
		$pages = ceil($cnt[0]['cnt']/$limit);
		
		$data = array();
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

	public function edit($id){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		$controller = $this->controller;

		if(!trim($id)){
			redirect(site_url($controller));
		}
		
		$sql = "SELECT * FROM `".$table."` WHERE `id` = '".db_escape($id)."' LIMIT 1";
		$q = $this->db->query($sql);
		$record = $q->result_array();
		$record = $record[0];
		
		if(!trim($record['id'])){
			redirect(site_url($controller));
		}

		$data = array();
		$data['record'] = $record;
		$data['controller'] = $controller;
		$data['content'] = $this->load->view($controller.'/add', $data, true);		
		$this->load->view('layout/main', $data);;
	}
		
	public function add(){	
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$controller = $this->controller;

		$id = $_GET['sid'];

		if(!id) return;

		$data = array();
		$data['sites'] = $this->site_data->fetch_sites($id);
		$data['controller'] = $controller;
		$data['content'] = $this->load->view($controller.'/add', $data, true);
		$this->load->view('layout/main', $data);;
	}

	public function ajax_edit(){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		$controller = $this->controller;
		$error = false;		
	
		if(!$error){
			// check if there are other lands that are connected to the same land detail
			$id = $_POST['id'];			

			$sql = " UPDATE `".$table."` SET ";

			//fields							
			//$sql .= "   `site_id` = '".mysql_real_escape_string($_POST['site_id'])."'";
			//$sql .= " , `site_name` = '".mysql_real_escape_string($_POST['site_name'])."'";
			$sql .= " `validator` = '".mysql_real_escape_string($_POST['validator'])."'";
			$sql .= " , `operator` = '".mysql_real_escape_string($_POST['operator'])."'";
			$sql .= " , `data_validated_thru` = '".mysql_real_escape_string($_POST['data_validated_thru'])."'";
			$sql .= " , `channel_a_rf` = '".mysql_real_escape_string($_POST['channel_a_rf'])."'";
			$sql .= " , `channel_b_rf` = '".mysql_real_escape_string($_POST['channel_b_rf'])."'";
			$sql .= " , `last_calibration_date` = '".mysql_real_escape_string($_POST['last_calibration_date'])."'";
			$sql .= " , `last_calibration_by` = '".mysql_real_escape_string($_POST['last_calibration_by'])."'";
			
			$sql .= " WHERE `id` = '$id' LIMIT 1";	
			$this->db->query($sql);										
			?>
			alertX("Successfully Updated Record.");
			self.location = "<?php echo site_url("site_info?sid=".$_POST['site_id']); ?>";
			<?php
		}
		?>jQuery("#record_form *").attr("disabled", false);<?php
	}

	public function ajax_add(){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		$controller = $this->controller;
		$error = false;		
		
		$site = $this->site_data->fetch_info($_POST['site_id']);

		if(!$error){								
			$sql = "INSERT INTO `".$table."` SET ";
			
			//fields		
			$sql .= "   `site_id` = '".mysql_real_escape_string($_POST['site_id'])."'";
			$sql .= " , `site_name` = '".mysql_real_escape_string($site['instrument_name'])."'";
			$sql .= " , `validator` = '".mysql_real_escape_string($_POST['validator'])."'";
			$sql .= " , `operator` = '".mysql_real_escape_string($_POST['operator'])."'";
			$sql .= " , `data_validated_thru` = '".mysql_real_escape_string($_POST['data_validated_thru'])."'";
			$sql .= " , `channel_a_rf` = '".mysql_real_escape_string($_POST['channel_a_rf'])."'";
			$sql .= " , `channel_b_rf` = '".mysql_real_escape_string($_POST['channel_b_rf'])."'";
			$sql .= " , `last_calibration_date` = '".mysql_real_escape_string($_POST['last_calibration_date'])."'";
			$sql .= " , `last_calibration_by` = '".mysql_real_escape_string($_POST['last_calibration_by'])."'";
			
			$this->db->query($sql);										
			
			?>
			alertX("Successfully Inserted Record.");
			self.location = "<?php echo site_url("site_info?sid=".$_POST['site_id']); ?>";
			<?php
		}
		?>jQuery("#record_form *").attr("disabled", false);<?php
	}
	
	public function ajax_delete($id=""){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		
		if(!$id){
			$id = $_POST['id'];
		}
		
		$id = db_escape($id);
		$sql = "delete from `".$table."` where id = '".$id."' limit 1";
		$q = $this->db->query($sql);
		
		?>
		alertX("Successfully deleted.");
		<?php		
		exit();
	}
}
?>