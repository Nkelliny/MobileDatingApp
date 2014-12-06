<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subscriptions extends CI_Controller 
{
	public function index()
	{	
		$query = $this->db->query('SELECT * FROM `subscriptions` ORDER BY `id`');
		$data['subs'] = $query->result();
		$this->load->view('subscriptions_default',$data);
		
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */