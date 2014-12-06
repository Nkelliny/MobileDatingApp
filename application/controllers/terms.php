<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Terms extends CI_Controller 
{
	public function index()
	{
		$query = $this->db->query('SELECT * FROM `pages` WHERE `url` = "terms-of-use"');
		$data['pg'] = $query->row();
		$this->load->view('default_terms',$data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */