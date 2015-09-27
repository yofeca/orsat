<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
class network extends CI_Controller {
	var $table;
	var $controller;

	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->table = "network";
		$this->controller = "network";
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
		$data['network_id'] = $id;
		$data['target_a'] = $this->component_data->fetch_network_target_components('A',$id);
		$data['target_b'] = $this->component_data->fetch_network_target_components('B',$id);
		$data['tceq_a'] = $this->component_data->fetch_tceq_target_components('A',$id);
		$data['tceq_b'] = $this->component_data->fetch_tceq_target_components('B',$id);
		$data['record'] = $record;
		$data['controller'] = $controller;
		$data['content'] = $this->load->view($controller.'/add', $data, true);		
		$this->load->view('layout/main', $data);;
	}
		
	public function add(){	
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$controller = $this->controller;
		
		$data = array();
		$data['event'] = 'add';
		$data['target_a'] = $this->component_data->fetch_tceq('A');
		$data['target_b'] = $this->component_data->fetch_tceq('B');
		$data['tceq_a'] = $this->component_data->fetch_airs_file('A');
		$data['tceq_b'] = $this->component_data->fetch_airs_file('B');
		$data['controller'] = $controller;
		$data['content'] = $this->load->view($controller.'/add', $data, true);
		$this->load->view('layout/main', $data);;
	}

	public function ajax_edit(){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		$controller = $this->controller;
		$error = false;		
		
		parse_str($_POST['form'],$form);
		extract($form);
		if(!$error){
			// check if there are other lands that are connected to the same land detail		
			
			$sql = " UPDATE `".$table."` SET ";

			//fields							
			$sql .= " `name` = '".mysql_real_escape_string($name)."'";
			$sql .= " WHERE `id` = '$id' LIMIT 1";	
			$this->db->query($sql);

/*			$itemsa = $_POST['itemsa'];
			$itemsb = $_POST['itemsb'];

			$ta = count($itemsa);
			$tb = count($itemsb);
			print_r($itemsa);
			print_r($itemsb);

			for($i=0; $i<$ta; $i++){
				$sql = "UPDATE `network_target_components` SET ";					
				$sql .= " `sort` = '".mysql_real_escape_string($i+1)."'";
				$sql .= " WHERE `network_id` = '$id' AND `tceq_id` = '".mysql_real_escape_string($itemsa[$i])."'";
				$this->db->query($sql);
			}
			for($i=0; $i<$tb; $i++){
				$sql = "UPDATE `network_target_components` SET ";
				$sql .= " `sort` = '".mysql_real_escape_string($i+1)."'";
				$sql .= " WHERE `network_id` = '$id' AND `tceq_id` = '".mysql_real_escape_string($itemsb[$i])."'";
				$this->db->query($sql);
			}*/

			?>
			alertX("Successfully Updated Record.");
			//self.location = "<?php echo site_url($controller."/edit/".$_POST['id']); ?>";
			<?php
		}
		?>jQuery("#record_form *").attr("disabled", false);<?php
	}

	public function ajax_add(){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		$controller = $this->controller;
		$error = false;		
		
		parse_str($_POST['form'],$form);
		extract($form);

		if(!$error){								
			$sql = "INSERT INTO `".$table."` SET ";
			
			//fields		
			$sql .= "   `name` = '".mysql_real_escape_string($name)."'";

			
			$q = $this->db->query($sql);										
			$insert_id = $this->db->insert_id();

			$itemsa = $_POST['itemsa'];
			$itemsb = $_POST['itemsb'];

			$ta = count($itemsa);
			$tb = count($itemsb);

			for($i=0; $i<$ta; $i++){
				$sql = "INSERT INTO `network_target_components` SET ";					
				$sql .= " `airs_list_id` = '".mysql_real_escape_string($itemsa[$i])."',";
				$sql .= " `sort` = '".mysql_real_escape_string($i+1)."',";
				$sql .= " `network_id` = '$insert_id'";
				$this->db->query($sql);
			}
			for($i=0; $i<$tb; $i++){
				$sql = "INSERT INTO `network_target_components` SET ";					
				$sql .= " `airs_list_id` = '".mysql_real_escape_string($itemsb[$i])."',";
				$sql .= " `sort` = '".mysql_real_escape_string($i+1)."',";
				$sql .= " `network_id` = '$insert_id'";
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
		$sql = "DELETE FROM `network_target_components` WHERE `network_id`=$id";
		$q = $this->db->query($sql);

		$sql = "delete from `".$table."` where id = '".$id."' limit 1";
		$q = $this->db->query($sql);
		
		?>
		alertX("Successfully deleted.");
		<?php		
		exit();
	}

	public function ajax_add_target_components($id){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		$controller = $this->controller;
		$error = false;		
		
		if(!$id) return;

		$data = $_POST['data'];

		$t = count($data);

		if(!$error){	
			for($i=0; $i<$t; $i++){
				$d = explode('-',$data[$i]);
				$sql = "INSERT INTO `network_target_components` SET ";					
				$sql .= " `airs_list_id` = '".mysql_real_escape_string($d[0])."',";
				$sql .= " `sort` = '".mysql_real_escape_string($d[1])."',";
				$sql .= " `network_id` = '$id'";
				$this->db->query($sql);
			}
		}
	}

	public function ajax_remove_target_component($id){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		
		if(!$id) return;
		
		$id = db_escape($id);
		$sql = "DELETE FROM `network_target_components` where airs_list_id = '".$_POST['aid']."' AND network_id=$id";
		$q = $this->db->query($sql);
		echo $sql;
		return true;
	}

	public function ajax_remove_all_target_component($network_id){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		
		if(!$network_id) return;
		
		$t = count($_POST['chVal']);

		for($i=0; $i<$t; $i++){
			$sql = "DELETE FROM `network_target_components` where airs_list_id = '".$_POST['chVal'][$i]."' AND network_id=$network_id";
			$q = $this->db->query($sql);
		}
		return true;
	}

	public function ajax_target_component_sortable($network_id){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		
		$data = $_POST['data'];

		parse_str($data,$str);

		$item = $str['list-item'];

		foreach ($item as $k => $v){
			$sort_number = $k + 1;
			$sql = "UPDATE `network_target_components` SET sort=$sort_number WHERE airs_list_id=$v AND `network_id`=$network_id";
			$q = $this->db->query($sql);
		}
	}
}
?>