<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class main extends CI_Controller {
	var $controller;

	public function __construct(){
		parent::__construct();
		//$this->load->database();
		$this->controller = "main";
	}

	public function index(){
		//$this->user_validation->validate($this->router->class, $this->router->method);
		$data['content'] = $this->load->view("layout/welcome", $data, true);		
		$this->load->view('layout/main', $data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */