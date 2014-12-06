<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Badwords extends CI_Controller 
{
	public function index()
	{
		$query = $this->db->query('SELECT * FROM `bad_words` WHERE `id` = "1"');
		$row = $query->row();
		$data['badwords'] = $row;
		$query = $this->db->query('SELECT * FROM `bad_words` WHERE `id` = "2"');
		$row = $query->row();
		$data['badnames'] = $row;
		
		$this->load->view('badwords_default',$data);
	}
	
	function bwlist()
	{
		$query = $this->db->query('UPDATE `bad_words` SET `bword_list` = "'.mysql_real_escape_string($_POST['badwords']).'" WHERE `id` = "1"');
		redirect(site_url('badwords'));
	}
	
	function bnlist()
	{
		$query = $this->db->query('UPDATE `bad_words` SET `bword_list` = "'.mysql_real_escape_string($_POST['badwords']).'" WHERE `id` = "2"');
		redirect(site_url('badwords'));
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */