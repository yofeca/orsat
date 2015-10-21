<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
class sample_types extends CI_Controller {
	var $table;
	var $controller;

	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->table = "sample_types";
		$this->controller = "sample_types";
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

		$data['record'] = $record;
		$data['controller'] = $controller;
		$data['content'] = $this->load->view($controller.'/add', $data, true);		
		$this->load->view('layout/main', $data);;
	}
		
	public function add(){	
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$controller = $this->controller;
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
			$sql .= "   `name` = '".mysql_real_escape_string($_POST['name'])."'";
$sql .= " , `abbr` = '".mysql_real_escape_string($_POST['abbr'])."'";
$sql .= " , `file_designator` = '".mysql_real_escape_string($_POST['file_designator'])."'";
$sql .= " , `method` = '".mysql_real_escape_string($_POST['method'])."'";
$sql .= " , `description` = '".mysql_real_escape_string($_POST['description'])."'";

			
			$sql .= " WHERE `id` = '$id' LIMIT 1";	
			$this->db->query($sql);										
			?>
			alertX("Successfully Updated Record.");
			self.location = "<?php echo site_url($controller."/edit/".$_POST['id']); ?>";
			<?php
		}
		?>jQuery("#record_form *").attr("disabled", false);<?php
	}

	public function ajax_add(){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		$controller = $this->controller;
		$error = false;		
		
		if(!$error){								
			$sql = "INSERT INTO `".$table."` SET ";
			
			//fields		
			$sql .= "   `name` = '".mysql_real_escape_string($_POST['name'])."'";
$sql .= " , `abbr` = '".mysql_real_escape_string($_POST['abbr'])."'";
$sql .= " , `file_designator` = '".mysql_real_escape_string($_POST['file_designator'])."'";
$sql .= " , `method` = '".mysql_real_escape_string($_POST['method'])."'";
$sql .= " , `description` = '".mysql_real_escape_string($_POST['description'])."'";

			
			$this->db->query($sql);										
			
			?>
			alertX("Successfully Inserted Record.");
			self.location = "<?php echo site_url($controller); ?>";
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