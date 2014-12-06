<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reported extends CI_Controller 
{
	public function index()
	{
		$query = $this->db->query('SELECT * FROM `reported` WHERE `reported` > 0 && `reporter` > 0 AND `status` != "3" ORDER BY `id` DESC');
		$reports = $query->result();
		$data['reports'] = $reports;
		$this->load->view('reported_default',$data);
	}
	
	function delete()
	{
		$id = $this->uri->segment(3);
		$query = $this->db->query('UPDATE `reported` SET `status` = "3" WHERE `id` = "'.$id.'"');
		redirect('/reported','refresh');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */