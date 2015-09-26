<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
class users extends CI_Controller {
	var $table;
	var $controller;
	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->table = "users";
	}
	public function index(){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		$controller = $table;
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
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		$controller = $table;
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
		//users should always be able to edit themselves
		if($_POST['id']==$_SESSION['user']['id']){
		}
		else{
			$this->user_validation->validate(__CLASS__, __FUNCTION__);
		}
		$table = $this->table;
		$controller = $table;
		$error = false;	

		
		foreach($_POST as $key=>$value){
			if(!is_array($value)){
				$_POST[$key] = trim($value);
			}
		}
		
		/*start validation*/

		$id = $_POST['id'];
		$sql = "select * from `".$table."` where `id` = '".db_escape($id)."' limit 1";
		$q = $this->db->query($sql);
		$record = $q->result_array();
		$record = $record[0];
		if($record['email']=='admin'&&$_SESSION['user']['email']!="admin"){
			?>alertX("Error! Cannot Edit Admin");<?php
			$error = true;
		}		

		/*end validation*/
		
		if(!$error){
			// check if there are other lands that are connected to the same land detail
			$id = $_POST['id'];			
			
			$sql = " update `".$table."` set ";
			//fields
			$sql .= " `name` = '".db_escape($_POST['name'])."'" ;
			
			if(trim($_POST['password'])){
				$sql .= " , `password` = '".db_escape(md5(trim($_POST['password'])))."'";
			}
			
			$sql .= " where `id` = '$id' limit 1";	
			$this->db->query($sql);
			
			//set user groups
			$this->setUserGroups($record['email'], $_POST['user_groups']);
			
			?>
			alertX("Successfully Updated Record.");
			self.location = "<?php echo site_url($controller."/edit/".$_POST['id']); ?>";
			<?php
		}
		?>jQuery("#record_form *").attr("disabled", false);<?php
	}	
	function ajax_add(){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		if(!$_SESSION['user']){
			return false;
		}
		$table = $this->table;
		$controller = $table;
		$error = false;		
		
		foreach($_POST as $key=>$value){
			if(!is_array($value)){
				$_POST[$key] = trim($value);
			}
		}
		
		/*start validation*/
		if (trim($_POST['email']) == ''){
			?>alertX("Please input Login Name!");<?php
			$error = true;
		}
		else if (strlen(trim($_POST['email'])) < 5){
			?>alertX("Login Name must be at least 5 characters!");<?php
			$error = true;
		}
		else if(!preg_match("/^[A-Za-z0-9_]+$/", trim($_POST['email']))&&0){
			?>alertX("Login Name must be combination of alphanumeric characters and underscore only!");<?php
			$error = true;
		}
		else if (trim($_POST['password']) == ''){
			?>alertX("Please input Password!");<?php
			$error = true;
		}
		else{
			$sql = "select * from `users` where `email`='".db_escape(trim($_POST['email']))."'";
			$q = $this->db->query($sql);
			$records = $q->result_array();
			if(count($records)){
				?>alertX("Login Name already exists in the database. Please input a new Login Name!");<?php
				$error = true;
			}
		}
		/*end validation*/
		
		if(!$error){								
			$sql = "insert into `".$table."` set ";
			/*fields*/
			$sql .= " `name` = '".db_escape($_POST['name'])."', " ;							
			$sql .= "  `email` = '".db_escape($_POST['email'])."', ";
			$sql .= " `password` = '".db_escape(md5(trim($_POST['password'])))."', ";
			$sql .= "   `dateadded` = NOW() ";
			$this->db->query($sql);			

			$this->setUserGroups($_POST['email'], $_POST['user_groups']);
			
			?>
			alertX("Successfully Inserted Record.");
			self.location = "<?php echo site_url($controller); ?>";
			<?php
		}
		?>jQuery("#record_form *").attr("disabled", false);<?php
	}
	
	public function edit($id){
		//users should always be able to edit themselves
		if($id==$_SESSION['user']['id']){
		}
		else{
			$this->user_validation->validate(__CLASS__, __FUNCTION__);
		}
		if(!$_SESSION['user']){
			return false;
		}
		$table = $this->table;
		$controller = $table;
		if(!trim($id)){
			redirect(site_url($controller));
		}
		$sql = "select * from `".$table."` where `id` = '".db_escape($id)."' limit 1";
		$q = $this->db->query($sql);
		$record = $q->result_array();
		$record = $record[0];
		if($record['email']=='admin'&&$_SESSION['user']['email']!="admin"){
			die("Cannot Edit Admin");
		}
		//get user groups
		$sql = "select `user_group` from `user_permissions` group by `user_group`";
		$q = $this->db->query($sql);
		$user_groups = $q->result_array();
		
		$user_user_groups = array();
		$sql = "select * from `user_user_groups` where `user_email` = '".db_escape($record['email'])."'";
		$q = $this->db->query($sql);
		$uusergroups = $q->result_array();
		foreach($uusergroups as $value){
			$user_user_groups[] = $value['user_group'];
		}
		
		if(!trim($record['id'])){
			redirect(site_url($controller));
		}
		$data['record'] = $record;
		$data['user_user_groups'] = $user_user_groups;
		$data['user_groups'] = $user_groups;
		$data['controller'] = $controller;
		$data['content'] = $this->load->view($controller.'/add', $data, true);		
		$this->load->view('layout/main', $data);;
	}
		
	public function add(){	
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$controller = $this->table;
		$data['controller'] = $controller;
		//get user groups
		$sql = "select `user_group` from `user_permissions` group by `user_group`";
		$q = $this->db->query($sql);
		$user_groups = $q->result_array();
		$user_user_groups = array();
		$data['user_user_groups'] = $user_user_groups;
		$data['user_groups'] = $user_groups;
		$data['content'] = $this->load->view($controller.'/add', $data, true);
		$this->load->view('layout/main', $data);;
	}
	public function ajax_delete($id=""){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		if(!$_SESSION['user']){
			return false;
		}
		
		if($_SESSION['user']['id']==trim($id)){ //cannot delete self and cannot delete admin
			die("Cannot Delete Self");
			return false;
		}
		
		$id = db_escape($id);
		//get user details
		$sql = "select * from `users` where `id`='".$id."'";
		$q = $this->db->query($sql);
		$record = $q->result_array();
		$record = $record[0];
		

		if($record['email']=="admin"){
			die("Cannot Delete Admin");
			return false;
		}
		
		$table = $this->table;
		if(!$id){
			$id = $_POST['id'];
		}
		
		//delete user user groups
		$sql = "delete from `user_user_groups` where `user_email` = '".db_escape(trim($record['email']))."'";
		$q = $this->db->query($sql);
		
		//delete users
		$sql = "delete from `".$table."` where id = '".$id."'";
		$q = $this->db->query($sql);
		?>
		alertX("Successfully deleted.");
		<?php		
		exit();
	}
	private function setUserGroups($email, $user_groups){
		if($this->user_validation->validate(__CLASS__, __FUNCTION__, false)){
			//delete user group
			$sql = "delete from `user_user_groups` where `user_email` = '".db_escape($email)."'";
			$this->db->query($sql);
			if(is_array($user_groups)){
				foreach($user_groups as $value){
					$sql = "insert into `user_user_groups` set `user_email` = '".db_escape($email)."', `user_group`='".db_escape($value)."'";
					$this->db->query($sql);
				}
			}
		}
		
	}
}
?>