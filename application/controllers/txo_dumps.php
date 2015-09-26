<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
class txo_dumps extends CI_Controller {
	var $table;
	var $controller;

	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->table = "txo_dumps";
		$this->controller = "txo_dumps";
	}

	public function index(){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		$controller = $this->controller;
		$start = $_GET['start'];
		$start += 0;
		$limit = 100;
				
		$sql = "SELECT * FROM `".$table."` WHERE 1 ORDER BY `data_acquisition_time` DESC LIMIT $start, $limit";
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
		$sql .= " ORDER BY `data_acquisition_time` DESC LIMIT $start, $limit" ;

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

		$data = array();
		$data['header'] = $this->txo_data->fetch_header($id);
		$data['components'] = $this->txo_data->fetch_components($id);
		$data['total_components'] = $this->txo_data->fetch_total_components($id);
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
			$sql .= "   `data` = '".mysql_real_escape_string($_POST['data'])."'";
			$sql .= " , `sample_type_id` = '".mysql_real_escape_string($_POST['sample_type_id'])."'";
			$sql .= " , `sample_name` = '".mysql_real_escape_string($_POST['sample_name'])."'";
			$sql .= " , `sample_number` = '".mysql_real_escape_string($_POST['sample_number'])."'";
			$sql .= " , `site_id` = '".mysql_real_escape_string($_POST['site_id'])."'";
			$sql .= " , `instrument_name` = '".mysql_real_escape_string($_POST['instrument_name'])."'";
			$sql .= " , `channel` = '".mysql_real_escape_string($_POST['channel'])."'";
			$sql .= " , `data_acquisition_time` = '".mysql_real_escape_string($_POST['data_acquisition_time'])."'";
			$sql .= " , `cycle` = '".mysql_real_escape_string($_POST['cycle'])."'";
			$sql .= " , `raw_data_file` = '".mysql_real_escape_string($_POST['raw_data_file'])."'";
			$sql .= " , `inst_method` = '".mysql_real_escape_string($_POST['inst_method'])."'";
			$sql .= " , `sequence_file` = '".mysql_real_escape_string($_POST['sequence_file'])."'";
			$sql .= " , `noise_threshold` = '".mysql_real_escape_string($_POST['noise_threshold'])."'";
			$sql .= " , `area_threshold` = '".mysql_real_escape_string($_POST['area_threshold'])."'";
			$sql .= " , `bunch_factor` = '".mysql_real_escape_string($_POST['bunch_factor'])."'";

			
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
			$sql .= "   `data` = '".mysql_real_escape_string($_POST['data'])."'";
			$sql .= " , `sample_type_id` = '".mysql_real_escape_string($_POST['sample_type_id'])."'";
			$sql .= " , `sample_name` = '".mysql_real_escape_string($_POST['sample_name'])."'";
			$sql .= " , `sample_number` = '".mysql_real_escape_string($_POST['sample_number'])."'";
			$sql .= " , `site_id` = '".mysql_real_escape_string($_POST['site_id'])."'";
			$sql .= " , `instrument_name` = '".mysql_real_escape_string($_POST['instrument_name'])."'";
			$sql .= " , `channel` = '".mysql_real_escape_string($_POST['channel'])."'";
			$sql .= " , `data_acquisition_time` = '".mysql_real_escape_string($_POST['data_acquisition_time'])."'";
			$sql .= " , `cycle` = '".mysql_real_escape_string($_POST['cycle'])."'";
			$sql .= " , `raw_data_file` = '".mysql_real_escape_string($_POST['raw_data_file'])."'";
			$sql .= " , `inst_method` = '".mysql_real_escape_string($_POST['inst_method'])."'";
			$sql .= " , `sequence_file` = '".mysql_real_escape_string($_POST['sequence_file'])."'";
			$sql .= " , `noise_threshold` = '".mysql_real_escape_string($_POST['noise_threshold'])."'";
			$sql .= " , `area_threshold` = '".mysql_real_escape_string($_POST['area_threshold'])."'";
			$sql .= " , `bunch_factor` = '".mysql_real_escape_string($_POST['bunch_factor'])."'";

			
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
	public function add_txo_files(){	
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$controller = $this->controller;
		$data['controller'] = $controller;
		$data['content'] = $this->load->view($controller.'/upload-txo', $data, true);
		$this->load->view('layout/main', $data);
	}

	public function upload_txo_files(){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);

		$files = json_decode($_POST['data']);
		
		$tf = count($files);

		for($i=0; $i<$tf; $i++){
			$fn = $files[$i]->name;

			$sql = "SELECT * FROM `files` WHERE `filename`='" . $fn ."'";
			$q = $this->db->query($sql);
			$id = $q->row()->id;

			if(!$id){
				$sql = "INSERT INTO `files` SET `filename`='" . $fn ."', `flag`='0'";
				$q = $this->db->query($sql);
			}
			//echo json_encode(array('id'=>$id));
		}
	}
}
?>