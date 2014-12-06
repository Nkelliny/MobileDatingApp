<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Privacy extends CI_Controller 
{
	public function index()
	{
		$query = $this->db->query('SELECT * FROM `pages` WHERE `url` = "privacy-policy"');
		$data['pg'] = $query->row();
		$this->load->view('default_privacy',$data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */