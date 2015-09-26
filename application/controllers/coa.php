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

		$data['lcs_cvs'] = $this->component_data->fetch_standard_components('LCS','edit',$id);
		$data['record'] = $record;
		$data['controller'] = $controller;
		$data['content'] = $this->load->view($controller.'/add', $data, true);		
		$this->load->view('layout/main', $data);;
	}
		
	public function add(){	
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$controller = $this->controller;
		$data['lcs_cvs'] = $this->component_data->fetch_standard_components('LCS_CVS','add','');
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


			$c = explode('&',$_POST['cyl']);
			$i = explode('=',$c[0]);
			$c = explode('=',$c[1]);
			
			$cylinder = $c[1];
			$id = $i[1];
			

			//fields		
			$sql .= " `cylinder` = '".mysql_real_escape_string($cylinder)."' WHERE id='$id'";
			$this->db->query($sql);										

			//lcs
			$lcs = explode('&',$_POST['lcs']);
			$tlcs = count($lcs);
			for($i=0; $i<$tlcs; $i++){
				$type = explode('-',$lcs[$i]);
				$value = explode("=",$type[1]);
				$sql = "UPDATE `coa_components` SET value='".$value[1]."' WHERE tceq_id='".$value[0]."' AND type='".$type[0]."' AND coa_id='$id'";
				$this->db->query($sql);

			}

			//cvs
			$cvs = explode('&',$_POST['cvs']);
			$tcvs = count($cvs);
			for($i=0; $i<$tcvs; $i++){
				$type = explode('-',$cvs[$i]);
				$value = explode("=",$type[1]);
				$sql = "UPDATE `coa_components` SET value='".$value[1]."' WHERE tceq_id='".$value[0]."' AND type='".$type[0]."' AND coa_id='$id'";
				$this->db->query($sql);
			}									
			?>
			alertX("Successfully Updated Record.");
			self.location = "<?php echo site_url($controller."/edit/".$id); ?>";
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

			$c = explode('&',$_POST['cyl']);
			$c = explode('=',$c[1]);
			$cylinder = $c[1];

			//fields		
			$sql .= " `cylinder` = '".mysql_real_escape_string($cylinder)."'";
			$this->db->query($sql);										
			$insert_id = $this->db->insert_id();

			//lcs
			$lcs = explode('&',$_POST['lcs']);
			$tlcs = count($lcs);
			for($i=0; $i<$tlcs; $i++){
				$type = explode('-',$lcs[$i]);
				$value = explode("=",$type[1]);
				$sql = "INSERT INTO `coa_components` SET tceq_id='".$value[0]."', coa_id='".$insert_id."', value='".$value[1]."', type='".strtoupper($type[0])."'";
				$this->db->query($sql);
			}

			//cvs
			$cvs = explode('&',$_POST['cvs']);
			$tcvs = count($cvs);
			for($i=0; $i<$tcvs; $i++){
				$type = explode('-',$cvs[$i]);
				$value = explode("=",$type[1]);
				$sql = "INSERT INTO `coa_components` SET tceq_id='".$value[0]."', coa_id='".$insert_id."', value='".$value[1]."', type='".strtoupper($type[0])."'";
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
		$id = $_GET['cylinder'];
		$type = strtoupper($_GET['type']);

		if(!$id) return;

		$sql = "SELECT al.component_name, cc.value FROM `airs_list` al 
		RIGHT JOIN `tceq` t ON al.id=t.airs_list_id RIGHT JOIN `coa_components` cc ON cc.tceq_id=t.id WHERE cc.type='$type' AND cc.coa_id='$id' ORDER BY type DESC";
		$q = $this->db->query($sql);
		$records = $q->result_array();

		echo json_encode($records);
	}
}
?>