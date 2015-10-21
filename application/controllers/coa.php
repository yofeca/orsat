<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
class coa extends CI_Controller {
	var $table;
	var $controller;

	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->table = "coa";
		$this->controller = "coa";
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

		$data['values'] = $this->component_data->fetch_standard_components('',$id);
		$data['record'] = $record;
		$data['controller'] = $controller;
		$data['content'] = $this->load->view($controller.'/add', $data, true);		
		$this->load->view('layout/main', $data);;
	}
		
	public function add($standard=""){	
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$controller = $this->controller;

		$data['standard'] = (isset($standard)) ? $standard : 'lcs_cvs';
		$data['lcs_cvs'] = $this->component_data->fetch_standard_components('LCS_CVS','');
		$data['rts'] = $this->component_data->fetch_standard_components('RTS','');
		$data['controller'] = $controller;
		$data['content'] = $this->load->view($controller.'/add', $data, true);
		$this->load->view('layout/main', $data);;
	}

	public function ajax_edit($id){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		$controller = $this->controller;
		$error = false;		
		
		if(!$id) return;

		parse_str($_POST['cyl'], $cylinder);
		parse_str($_POST['val'], $values);

		if(!$error){

			$sql = "UPDATE `".$table."` SET ";

			//fields		
			$sql .= " `cylinder` = '".mysql_real_escape_string($cylinder['cylinder'])."' WHERE id=$id";
			$this->db->query($sql);										
			$insert_id = $this->db->insert_id();

			//standard values
			foreach($values as $k => $v){
				$aid = str_replace('values-','',$k);
				$sql = "UPDATE `coa_components` SET value='".$v."' WHERE coa_id='".$id."' AND airs_list_id='".$aid."'";
				$this->db->query($sql);
			}
			?>
			alertX("Successfully Inserted Record.");
			self.location = "<?php echo site_url($controller); ?>";
			<?php
		}
		?>jQuery("#record_form *").attr("disabled", false);<?php
	}

	public function ajax_add(){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		$controller = $this->controller;
		$error = false;		
		
		parse_str($_POST['cyl'], $cylinder);
		parse_str($_POST['val'], $values);

		if(!$error){

			$sql = "INSERT INTO `".$table."` SET ";

			//fields		
			$sql .= " `cylinder` = '".mysql_real_escape_string($cylinder['cylinder'])."'";
			$sql .= ", `type` = '".mysql_real_escape_string(strtoupper($cylinder['standard_type']))."'";
			$this->db->query($sql);										
			$insert_id = $this->db->insert_id();

			//standard values
			foreach($values as $k => $v){
				$id = str_replace('values-','',$k);
				$sql = "INSERT INTO `coa_components` SET airs_list_id='".$id."', coa_id='".$insert_id."', value='".$v."', type='".strtoupper($cylinder['standard_type'])."'";
				$this->db->query($sql);
			}
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

		$sql = "delete from `coa_components` where coa_id = '".$id."'";
		$q = $this->db->query($sql);

		$sql = "delete from `".$table."` where id = '".$id."' limit 1";
		$q = $this->db->query($sql);
		
		?>
		alertX("Successfully deleted.");
		<?php		
		exit();
	}

	public function ajax_fetch_standards(){

		$id = explode('-',$_GET['cylinder']);
		$channel = strtoupper($_GET['ch']);

		if(!$id) return;
		$sql = "SELECT al.component_name, cc.value FROM `airs_list` al 
				RIGHT JOIN `tceq` t ON al.id=t.airs_list_id 
				RIGHT JOIN `coa_components` cc 
				ON cc.airs_list_id=t.airs_list_id 
				WHERE cc.type='".$id[0]."' AND cc.coa_id='".$id[1]."' AND t.channel='$channel' ORDER BY t.sort ASC";

		$q = $this->db->query($sql);
		$records = $q->result_array();

		echo json_encode($records);
	}
}
?>