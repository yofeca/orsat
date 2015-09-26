<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
class user_permissions extends CI_Controller {
	var $table;
	var $controller;
	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->table = "user_permissions";
		$this->controller = "user_permissions";
	}
	public function index(){
		$this->user_validation->validate($this->router->class, $this->router->method);
		$table = $this->table;
		$controller = $this->controller;
		$start = $_GET['start'];
		$start += 0;
		$limit = 50;
				
		$sql = "select * from `".$table."` where 1 order by id desc limit $start, $limit";
		$export_sql = md5($sql);
		$_SESSION['export_sqls'][$export_sql] = $sql;
		$q = $this->db->query($sql);
		$records = $q->result_array();		
		
		//$sql = "select count(`id`) as `cnt` from `land` where `user_id` is NULL order by `folder` desc" ;
		$sql = "select count(`id`) as `cnt` from `".$table."` where 1" ;
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
		$this->user_validation->validate($this->router->class, $this->router->method);
		$table = $this->table;
		$controller = $this->controller;
		$start = $_GET['start'];
		$filter = $_GET['filter'];
		$start += 0;
		$limit = 50;
		$search = strtolower(trim($_GET['search']));
		$searchx = trim($_GET['search']);
		
		$sql = "select * from `".$table."`  where 1 ";
		if($search != ''){
			$sql .= "and LOWER(`".$filter."`) like '%".db_escape($search)."%'";
		}
		$sql .= " order by id desc limit $start, $limit" ;

		$export_sql = md5($sql);
		$_SESSION['export_sqls'][$export_sql] = $sql;
		$q = $this->db->query($sql);
		$records = $q->result_array();
				
		$sql = "select count(id) as `cnt`  from `".$table."` where 1 ";
		if($search != ''){
			$sql .= "and LOWER(`".$filter."`) like '%".db_escape($search)."%'";
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
	function ajax_edit(){
		$this->user_validation->validate($this->router->class, $this->router->method);
		$table = $this->table;
		$controller = $this->controller;
		$error = false;		
		
		/*start validation*/
		/*
		if ($_POST['user_group'] == ''){
			?>alertX("Please input User Group!");<?php
			$error = true;
		}
		*/
		if ($_POST['class_name'] == ''){
			?>alertX("Please input Controller Class Name!");<?php
			$error = true;
		}
		else if ($_POST['function'] == ''){
			?>alertX("Please input Function!");<?php
			$error = true;
		}
		/*end validation*/
		
		if(!$error){
			// check if there are other lands that are connected to the same land detail
			$id = $_POST['id'];			
			
			$sql = " update `".$table."` set ";
			//fields
			//$sql .= " `name` = '".db_escape($_POST['name'])."'" ;									
			//$sql .= "   `user_group` = '".mysql_real_escape_string($_POST['user_group'])."'";
			$sql .= "`class_name` = '".mysql_real_escape_string($_POST['class_name'])."'";
			$sql .= " , `function` = '".mysql_real_escape_string($_POST['function'])."'";

			
			$sql .= " where `id` = '$id' limit 1";	
			$this->db->query($sql);										
			?>
			alertX("Successfully Updated Record.");
			self.location = "<?php echo site_url($controller."/edit/".$_POST['id']); ?>";
			<?php
		}
		?>jQuery("#record_form *").attr("disabled", false);<?php
	}	
	function ajax_add(){
		$this->user_validation->validate($this->router->class, $this->router->method);
		$table = $this->table;
		$controller = $this->controller;
		$error = false;		
				
		/*start validation*/
		if ($_POST['user_group'] == ''){
			?>alertX("Please input User Group!");<?php
			$error = true;
		}
		else if ($_POST['class_name'] == ''){
			?>alertX("Please input Controller Class Name!");<?php
			$error = true;
		}
		else if ($_POST['function'] == ''){
			?>alertX("Please input Function!");<?php
			$error = true;
		}
		/*end validation*/
		
		if(!$error){								
			$sql = "insert into `".$table."` set ";
			/*fields*/
			//$sql .= " `name` = '".db_escape($_POST['name'])."'" ;							
			$sql .= "   `user_group` = '".mysql_real_escape_string($_POST['user_group'])."'";
$sql .= " , `class_name` = '".mysql_real_escape_string($_POST['class_name'])."'";
$sql .= " , `function` = '".mysql_real_escape_string($_POST['function'])."'";

			$this->db->query($sql);										
			?>
			alertX("Successfully Inserted Record.");
			self.location = "<?php echo site_url($controller); ?>";
			<?php
		}
		?>jQuery("#record_form *").attr("disabled", false);<?php
	}
	
	public function edit($id){
		$this->user_validation->validate($this->router->class, $this->router->method);
		$table = $this->table;
		$controller = $this->controller;
		if(!trim($id)){
			redirect(site_url($controller));
		}
		$sql = "select * from `".$table."` where `id` = '".db_escape($id)."' limit 1";
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
		$this->user_validation->validate($this->router->class, $this->router->method);
		$controller = $this->controller;
		$data['controller'] = $controller;
		$data['content'] = $this->load->view($controller.'/add', $data, true);
		$this->load->view('layout/main', $data);
	}
	public function ajax_delete($id=""){
		$this->user_validation->validate($this->router->class, $this->router->method);
		$table = $this->table;
		if(!$id){
			$id = $_POST['id'];
		}
		$sql = "select * from `".$table."` where `id` = '".db_escape($id)."' limit 1";
		$q = $this->db->query($sql);
		$record = $q->result_array();
		$record = $record[0];
		
		//count the user group 
		$sql = "select count(`id`) as `cnt` from `".$table."` where `user_group`='".db_escape($record['user_group'])."'";
		$q = $this->db->query($sql);
		$count = $q->result_array();
		$count = $count[0]['cnt'];
		if($count<=1){
			$sql = "delete from `user_user_groups` where `user_group`='".db_escape($record['user_group'])."'";
			$q = $this->db->query($sql);
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