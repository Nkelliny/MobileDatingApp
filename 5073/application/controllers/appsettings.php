<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Appsettings extends CI_Controller 
{
	public function index()
	{
		$query = $this->db->query('SELECT * FROM `settings` WHERE `id` > 4 ORDER BY `id`');
		$settings = $query->result();
		$data['settings'] = $settings;
		$this->load->view('app_settings',$data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */