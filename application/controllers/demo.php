<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Demo extends CI_Controller 
{
	public function index()
	{
		$this->load->view('demo_home');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */