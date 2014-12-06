<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Affiliates extends CI_Controller 
{
	public function index()
	{
		$query = $this->db->query('SELECT * FROM `aff_users` WHERE `type` != "Member" && `type` != "Site Member" ORDER BY `joindate` DESC');
		$data['users'] = $query->result();
		$this->load->view('aff_users_default',$data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */