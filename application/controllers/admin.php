<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start();
class admin extends CI_Controller {

	public function __construct(){

		parent::__construct();
		$this->load->database();

	}

	public function index(){

		if($_SESSION['user']){
			
			redirect(site_url('main'),'refresh');
			//$data['content'] = $this->load->view("layout/welcome", $data, true);	
			//$this->load->view('layout/main', $data);

		}else{

			$this->load->view('layout/main');

		}

	}//index

	public function createcms(){

		$this->user_validation->validate($this->router->class, $this->router->method);

		if($_POST){

			$table   = trim($_POST['table']);
			$folder  = trim($_POST['folder']);
			$display = trim($_POST['display']);

			if(!$folder){

				$folder = $table;

			}

			$contents    = file_get_contents(dirname(__FILE__)."/admin/controller.txt");
			$contents    = str_replace("[[table]]", $table, $contents);
			$contents    = str_replace("[[folder]]", $folder, $contents);
			$str         = "";
			$fields_temp = explode("\n", trim($_POST['edit_fields']));
			$more        = 0;

			foreach($fields_temp as $value){

				$values = explode("|", trim($value));
				$field  = trim($values[0]);
				$label  = trim($values[1]);

				if($more==0){

					$str  .= '$sql .= "   `'.$field.'` = \'".mysql_real_escape_string($_POST[\''.trim($values[0]).'\'])."\'"'.";\n";
					$more = 1;

				}else{

					$str  .= '$sql .= " , `'.$field.'` = \'".mysql_real_escape_string($_POST[\''.trim($values[0]).'\'])."\'"'.";\n";
				
				}

			}

			$contents = str_replace("[[update_fields]]", $str, $contents);
			file_put_contents(dirname(__FILE__)."/".$folder.".php", $contents);
			
			//create add
			mkdir(dirname(__FILE__)."/../views/".$folder, 0777);
			
			$contents    = file_get_contents(dirname(__FILE__)."/admin/add.txt");
			$str         = "";
			$fields_temp = explode("\n", trim($_POST['edit_fields']));

			foreach($fields_temp as $value){

				$values = explode("|", trim($value));
				$field  = trim($values[0]);
				$label  = trim($values[1]);
				$str    .= '<div class="form-group">
							<label for="'.$field.'" class="col-sm-4 control-label">'.$label.'</label>
							<div class="col-sm-8">
								<input type="text" name="'.$field.'" size="40" class="form-control" placeholder="Enter '.$label.'">
							</div>
							</div>';
			}

			$contents = str_replace("[[edit_fields]]", $str, $contents);
			file_put_contents(dirname(__FILE__)."/../views/".$folder."/add.php", $contents);
			
			//create main
			$contents    = file_get_contents(dirname(__FILE__)."/admin/main.txt");
			$str         = "";
			$fields_temp = explode("\n", trim($_POST['filter_fields']));
			
			foreach($fields_temp as $value){

				$values = explode("|", trim($value));
				$field  = trim($values[0]);
				$label  = trim($values[1]);
				$str    .= '<option value="'.$field.'">'.$label.'</option>	'."\n";
			
			}

			$contents = str_replace("[[filter_fields]]", $str, $contents);
			
			$str1        = "";
			$str2        = "";
			$fields_temp = explode("\n", trim($_POST['display_fields']));
			
			foreach($fields_temp as $value){

				$values = explode("|", trim($value));
				$field  = trim($values[0]);
				$label  = trim($values[1]);
				$str1   .= '<th>'.$label.'</th>'."\n";
				$str2   .= '<td><?php echo $records[$i][\''.$field.'\'];?></td>'."\n";

			}

			$contents = str_replace("[[display_heads]]", $str1, $contents);
			$contents = str_replace("[[display_values]]", $str2, $contents);
			file_put_contents(dirname(__FILE__)."/../views/".$folder."/main.php", $contents);
			
			//setup the menu
			$menu_contents = file_get_contents(dirname(__FILE__)."/admin/menu.txt");
			$menu_contents = str_replace("[[controller]]", $folder, $menu_contents);
			$menu_contents = str_replace("[[display]]", $display, $menu_contents);
			$contents      = file_get_contents(dirname(__FILE__)."/../views/layout/menus.php");
			
			if(strpos($menu_contents, $contents)===false){

				$contents = str_replace("/*[[MENU]]*/", $menu_contents."\n/*[[MENU]]*/", $contents);
				file_put_contents(dirname(__FILE__)."/../views/layout/menus.php", $contents);

			}
			
			redirect(site_url("admin/createcms/?message=Done!"), 'refresh');
		}

		$data['createcms'] = 1;
		$this->load->view('layout/main', $data);

	}

	public function logout(){

		unset($_SESSION['user']);
		redirect(site_url("admin"), 'refresh');

	}

	public function login(){

		$sql = "select * from `users` where `email`= ".$this->db->escape($_POST['login_email'])." and `password`= '".md5($_POST['password'])."'";
		$q   = $this->db->query($sql);
		$r   = $q->result_array();	

		if($r[0]){

			unset($_SESSION['user']);
			$_SESSION['user'] = $r[0];
			
			//get user groups
			$user_user_groups = array();
			$sql              = "select * from `user_user_groups` where `user_email` = '".db_escape($r[0]['email'])."'";
			$q                = $this->db->query($sql);
			$uusergroups      = $q->result_array();
			
			foreach($uusergroups as $value){

				$user_user_groups[] = $value['user_group'];
				
			}

			$_SESSION['user']['user_groups'] = $user_user_groups;
			
			redirect(site_url("admin"), 'refresh');

		}else{

			redirect(site_url("admin/?error=Invalid Login&login_email=".$_POST['login_email']), 'refresh');

		}
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */