<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class App extends CI_Controller 
{
	public function index()
	{
		header('HTTP/1.1 301 Moved Permanently');
		header('Location: https://www.xxxxxx.com/android');
		//$this->load->view('/mobile/landing');	
	}
	
	function test()
	{
		$this->load->view('app_android');
	}
}

/* End of file login.php */
/* Location: ./application/controllers/login.php */