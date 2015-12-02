<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
class sites extends CI_Controller {
	var $table;
	var $controller;

	public function __construct(){
		parent::__construct();
		$this->load->database();
		$this->table = "sites";
		$this->controller = "sites";
	}

	public function index(){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$table = $this->table;
		$controller = $this->controller;
		$start = $_GET['start'];
		$start += 0;
		$limit = 100;
				
		$sql = "SELECT s.* FROM `".$table."` s LEFT JOIN `network` n ON UPPER(s.network_name)=UPPER(n.name) ORDER BY s.`instrument_name` ASC LIMIT $start, $limit";
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
		$cylinder_lcs = $this->site_data->fetch_coa('LCS');
		$cylinder_cvs = $this->site_data->fetch_coa('CVS');
		$cylinder_rts = $this->site_data->fetch_coa('RTS');

		$lcs = $this->site_data->fetch_site_standards($id,'LCS');
		$cvs = $this->site_data->fetch_site_standards($id,'CVS');
		$rts = $this->site_data->fetch_site_standards($id,'RTS');

		$data['lcs_page'] = $this->load->view($controller.'/lcs', array('lcs' => $lcs, 'controller' => $controller, 'site_id'=> $id, 'cylinder' => $cylinder_lcs), true);
		$data['cvs_page'] = $this->load->view($controller.'/cvs', array('cvs' => $cvs, 'controller' => $controller, 'site_id'=> $id, 'cylinder' => $cylinder_cvs), true);
		$data['rts_page'] = $this->load->view($controller.'/rts', array('rts' => $rts, 'controller' => $controller, 'site_id'=> $id, 'cylinder' => $cylinder_rts), true);
		$data['networks'] = $this->site_data->fetch_site_networks();
		$data['record'] = $record;
		$data['controller'] = $controller;
		$data['content'] = $this->load->view($controller.'/add', $data, true);		
		$this->load->view('layout/main', $data);;
	}
		
	public function add(){	
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$controller = $this->controller;
		
		$data['networks'] = $this->site_data->fetch_site_networks();
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
			$sql .= "   `instrument_name` = '".mysql_real_escape_string($_POST['instrument_name'])."'";
			$sql .= " , `network_name` = '".mysql_real_escape_string($_POST['network'])."'";
			$sql .= " , `site_designator` = '".mysql_real_escape_string($_POST['site_designator'])."'";
			$sql .= " , `aqs_no` = '".mysql_real_escape_string($_POST['aqs_no'])."'";
			$sql .= " , `short_name` = '".mysql_real_escape_string($_POST['short_name'])."'";
			$sql .= " , `formal_name` = '".mysql_real_escape_string($_POST['formal_name'])."'";
			$sql .= " , `address` = '".mysql_real_escape_string($_POST['address'])."'";
			$sql .= " , `city` = '".mysql_real_escape_string($_POST['city'])."'";
			$sql .= " , `zip` = '".mysql_real_escape_string($_POST['zip'])."'";
			$sql .= " , `latitude` = '".mysql_real_escape_string($_POST['latitude'])."'";
			$sql .= " , `longitude` = '".mysql_real_escape_string($_POST['longitude'])."'";
			$sql .= " , `notes` = '".mysql_real_escape_string($_POST['notes'])."'";
			$sql .= " , `cams_code` = '".mysql_real_escape_string($_POST['cams_code'])."'";
			$sql .= " , `doc` = '".mysql_real_escape_string($_POST['doc'])."'";
			$sql .= " , `interval` = '".mysql_real_escape_string($_POST['interval'])."'";
			$sql .= " , `units_code` = '".mysql_real_escape_string($_POST['units_code'])."'";
			$sql .= " , `method_code` = '".mysql_real_escape_string($_POST['method_code'])."'";

			
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
			$sql .= "   `instrument_name` = '".mysql_real_escape_string($_POST['instrument_name'])."'";
			$sql .= " , `network_id` = '".mysql_real_escape_string($_POST['network_id'])."'";
			$sql .= " , `site_designator` = '".mysql_real_escape_string($_POST['site_designator'])."'";
			$sql .= " , `aqs_no` = '".mysql_real_escape_string($_POST['aqs_no'])."'";
			$sql .= " , `short_name` = '".mysql_real_escape_string($_POST['short_name'])."'";
			$sql .= " , `formal_name` = '".mysql_real_escape_string($_POST['formal_name'])."'";
			$sql .= " , `address` = '".mysql_real_escape_string($_POST['address'])."'";
			$sql .= " , `city` = '".mysql_real_escape_string($_POST['city'])."'";
			$sql .= " , `zip` = '".mysql_real_escape_string($_POST['zip'])."'";
			$sql .= " , `latitude` = '".mysql_real_escape_string($_POST['latitude'])."'";
			$sql .= " , `longitude` = '".mysql_real_escape_string($_POST['longitude'])."'";
			$sql .= " , `notes` = '".mysql_real_escape_string($_POST['notes'])."'";
			$sql .= " , `cams_code` = '".mysql_real_escape_string($_POST['cams_code'])."'";
			$sql .= " , `doc` = '".mysql_real_escape_string($_POST['doc'])."'";
			$sql .= " , `interval` = '".mysql_real_escape_string($_POST['interval'])."'";
			$sql .= " , `units_code` = '".mysql_real_escape_string($_POST['units_code'])."'";
			$sql .= " , `method_code` = '".mysql_real_escape_string($_POST['method_code'])."'";

			
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

	//LCS
	public function ajax_fetch_lcs_standard($id,$type){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);

		if(!$id)
			$id = $_POST['id'];

		if(!$type)
			$type = $_POST['type'];

		$sql = "SELECT s.*, c.cylinder FROM `standards` s LEFT JOIN `coa` c ON s.coa_id=c.id WHERE s.id=$id AND s.type='$type'";
		$q = $this->db->query($sql);
		$data = $q->result_array();

		echo json_encode($data[0]);
	}

	public function ajax_add_lcs_standard(){

		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$response = array();

		if(!isset($_POST['data'])) return;

		$data = array();
		parse_str($_POST['data'], $data);
		extract($data);

		$sql = "INSERT INTO `standards` SET";
		$sql .= " `coa_id` = '".mysql_real_escape_string($cylinder_id)."'";
		$sql .= " , `date_on` = '".date('Y-m-d H:i:s', strtotime($date_on))."'";
		if( ! empty($date_off) ){
			$sql .= " , `date_off` = '". date('Y-m-d H:i:s', strtotime($date_off)) ."'";
		}
		$sql .= " , `value` = '".mysql_real_escape_string($dilution_factor)."'";
		$sql .= " , `site_id` = '".mysql_real_escape_string($lcs_site_id)."'";
		$sql .= " , `type` = '".mysql_real_escape_string($standard_type)."'";
		$q = $this->db->query($sql);

		$response['id'] = $this->db->insert_id();

		echo json_encode( array( 'id' => $this->db->insert_id() ) );

	}

	function ajax_update_lcs_standard($id=''){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);

		if(!isset($_POST['data'])) return;

		$data = array();
		parse_str($_POST['data'], $data);
		extract($data);
		
		//$dateon = date('Y-m-d H:i:s', strtotime($date_on));
		//$dateoff = ($date_off=='') ? 'NULL' : date('Y-m-d H:i:s', strtotime($date_off));

		$sql = "UPDATE `standards` SET ";
		$sql .= " `coa_id` = '".mysql_real_escape_string($cylinder_id)."'";
		$sql .= " , `date_on` = '".date('Y-m-d H:i:s', strtotime($date_on))."'";
		if( ! empty($date_off) ){
			$sql .= " , `date_off` = '". date('Y-m-d H:i:s', strtotime($date_off)) ."'";
		}
		$sql .= " , `value` = '".mysql_real_escape_string($dilution_factor)."'";
		$sql .= " , `site_id` = '".mysql_real_escape_string($lcs_site_id)."'";
		$sql .= " , `type` = '".mysql_real_escape_string($standard_type)."'";
		$sql .= " WHERE `id` = $id limit 1";	
		$this->db->query($sql);										
	}

	public function ajax_delete_lcs_standard($id=""){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);

		if(!$id){
			$id = $_POST['id'];
		}

		$sql = "DELETE FROM `standards` WHERE id = $id limit 1";
		$q = $this->db->query($sql);
	}

	//CVS
	public function ajax_fetch_cvs_standard($id,$type){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);

		if(!$id)
			$id = $_POST['id'];

		if(!$type)
			$type = $_POST['type'];

		$sql = "SELECT s.*, c.cylinder FROM `standards` s LEFT JOIN `coa` c ON s.coa_id=c.id WHERE s.id=$id AND s.type='$type'";
		$q = $this->db->query($sql);
		$data = $q->result_array();

		echo json_encode($data[0]);
	}
	public function ajax_add_cvs_standard(){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$response = array();

		if(!isset($_POST['data'])) return;

		$data = array();
		parse_str($_POST['data'], $data);
		extract($data);

		$sql = "INSERT INTO `standards` SET";
		$sql .= " `coa_id` = '".mysql_real_escape_string($cylinder_id)."'";
		$sql .= " , `date_on` = '".date('Y-m-d H:i:s', strtotime($date_on))."'";
		$sql .= " , `date_off` = '".($date_off=='') ? NULL : date('Y-m-d H:i:s', strtotime($date_off))."'";
		$sql .= " , `value` = '".mysql_real_escape_string($dilution_factor)."'";
		$sql .= " , `site_id` = '".mysql_real_escape_string($cvs_site_id)."'";
		$sql .= " , `type` = '".mysql_real_escape_string($standard_type)."'";
		$q = $this->db->query($sql);

		$response['id'] = $this->db->insert_id();

		echo json_encode($response);
	}
	function ajax_update_cvs_standard($id=''){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);

		if(!isset($_POST['data'])) return;

		$data = array();
		parse_str($_POST['data'], $data);
		extract($data);
		
		$sql = "UPDATE `standards` SET ";
		$sql .= " `coa_id` = '".mysql_real_escape_string($cylinder_id)."'";
		$sql .= " , `date_on` = '".date('Y-m-d H:i:s', strtotime($date_on))."'";
		$sql .= " , `date_off` = '".($date_off=='') ? NULL : date('Y-m-d H:i:s', strtotime($date_off))."'";
		$sql .= " , `value` = '".mysql_real_escape_string($dilution_factor)."'";
		$sql .= " , `site_id` = '".mysql_real_escape_string($cvs_site_id)."'";
		$sql .= " , `type` = '".mysql_real_escape_string($standard_type)."'";
		$sql .= " WHERE `id` = $id limit 1";	
		$this->db->query($sql);										
	}
	public function ajax_delete_cvs_standard($id=""){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);

		if(!$id){
			$id = $_POST['id'];
		}

		$sql = "DELETE FROM `standards` WHERE id = $id limit 1";
		$q = $this->db->query($sql);
	}

	//rts
	public function ajax_fetch_rts_standard($id,$type){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);

		if(!$id)
			$id = $_POST['id'];

		if(!$type)
			$type = $_POST['type'];

		$sql = "SELECT s.*, c.cylinder FROM `standards` s LEFT JOIN `coa` c ON s.coa_id=c.id WHERE s.id=$id AND s.type='$type'";
		$q = $this->db->query($sql);
		$data = $q->result_array();

		echo json_encode($data[0]);
	}
	public function ajax_add_rts_standard(){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);
		$response = array();

		if(!isset($_POST['data'])) return;

		$data = array();
		parse_str($_POST['data'], $data);
		extract($data);

		$sql = "INSERT INTO `standards` SET";
		$sql .= " `coa_id` = '".mysql_real_escape_string($cylinder_id)."'";
		$sql .= " , `date_on` = '".date('Y-m-d H:i:s', strtotime($date_on))."'";
		$sql .= " , `date_off` = '".($date_off=='') ? NULL : date('Y-m-d H:i:s', strtotime($date_off))."'";
		$sql .= " , `value` = '".mysql_real_escape_string($dilution_factor)."'";
		$sql .= " , `site_id` = '".mysql_real_escape_string($rts_site_id)."'";
		$sql .= " , `type` = '".mysql_real_escape_string($standard_type)."'";
		$q = $this->db->query($sql);

		$response['id'] = $this->db->insert_id();

		echo json_encode($response);
	}
	function ajax_update_rts_standard($id=''){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);

		if(!isset($_POST['data'])) return;

		$data = array();
		parse_str($_POST['data'], $data);
		extract($data);
		
		$sql = "UPDATE `standards` SET ";
		$sql .= " `coa_id` = '".mysql_real_escape_string($cylinder_id)."'";
		$sql .= " , `date_on` = '".date('Y-m-d H:i:s', strtotime($date_on))."'";
		$sql .= " , `date_off` = '".($date_off=='') ? NULL : date('Y-m-d H:i:s', strtotime($date_off))."'";
		$sql .= " , `value` = '".mysql_real_escape_string($dilution_factor)."'";
		$sql .= " , `site_id` = '".mysql_real_escape_string($rts_site_id)."'";
		$sql .= " , `type` = '".mysql_real_escape_string($standard_type)."'";
		$sql .= " WHERE `id` = $id limit 1";	
		$this->db->query($sql);										
	}
	public function ajax_delete_rts_standard($id=""){
		$this->user_validation->validate(__CLASS__, __FUNCTION__);

		if(!$id){
			$id = $_POST['id'];
		}

		$sql = "DELETE FROM `standards` WHERE id = $id limit 1";
		$q = $this->db->query($sql);
	}
}
?>