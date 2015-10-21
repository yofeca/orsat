<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
@session_start();
class user_validation extends CI_Model {
    public function __construct(){
		parent::__construct();
		$this->load->database();
    }
	public function validate($class, $function="%", $die=true){
		$class = trim($class);
		$function = trim($function);
		if(!$_SESSION['user']['id']){
			if($die){
				die("Access Denied");
			}
			return false;
		}
		//echo $class."-".$method;
		if($_SESSION['user']['email']=="super" || $_SESSION['user']['email']=="admin"){ //return true to all if admin
			return true;
		}
		else{
			//check if current user has permission to this class
			//echo "<pre>";
			//print_r($_SESSION);
			
			
			//if deny on specific function
			$sql = "select * from `user_permissions` where `class_name`='".db_escape($class)."' and `function`='-".db_escape($function)."'";
			
			if(!trim($_SESSION['user']['usg_sqlext'])){
				$usg = array();
				if(is_array($_SESSION['user']['user_groups'])){
					foreach($_SESSION['user']['user_groups'] as $value){
						$usg[] = " `user_group` = '".db_escape($value)."' ";
					}
				}
				$usgtxt = implode($usg, " or ");
				$_SESSION['user']['usg_sqlext'] = $usgtxt;
			}
			else{
				$usgtxt = $_SESSION['user']['usg_sqlext'];
			}
			if(trim($usgtxt)){
				$sql .= " and ( ".$usgtxt." ) ";
			}
			else{
				$sql .= " and 0 ";
			}

			$q = $this->db->query($sql);
			$records = $q->result_array();
			$t = count($records);
			if($t){
				if($die){
					die("Access Denied");
				}
				
				return false;
			}
			
			$sql = "select * from `user_permissions` where `class_name`='".db_escape($class)."' and `function`='%' ";
			if(trim($usgtxt)){
				$sql .= " and ( ".$usgtxt." ) ";
			}
			else{
				$sql .= " and 0 ";
			}

			
			$q = $this->db->query($sql);
			$records = $q->result_array();
			$t = count($records);
			if($t){
				return true;
			}
			
			$sql = "select * from `user_permissions` where `class_name`='".db_escape($class)."'";
			if(trim($usgtxt)){
				$sql .= " and ( ".$usgtxt." ) ";
			}
			else{
				$sql .= " and 0 ";
			}
			
			$q = $this->db->query($sql);
			$records = $q->result_array();
			$t = count($records);
			if($t){
				if($function=="%"){
					return true;
				}
				else{
					for($i=0; $i<$t; $i++){
						if($function==trim($records[$i]['function'])){
							return true;
						}
					}
				}
			}
			if($die){
				die("Access Denied");
			}
			return false;
		}
	}
}

/* End of file user_validation.php */