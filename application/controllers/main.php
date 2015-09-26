<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class main extends CI_Controller {
	public function index(){
		//redirect(site_url("admin"), "refresh");
		$data['content'] = $this->load->view("layout/welcome", $data, true);		
		$this->load->view('layout/main', $data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */