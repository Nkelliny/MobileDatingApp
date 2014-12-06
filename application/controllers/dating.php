<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dating extends CI_Controller 
{
	public function index()
	{
		$query = $this->db->query('SELECT `url`,`title` FROM `pages` WHERE `url` != "terms-of-use" AND `url` != "privacy-policy" AND `status` = "1" ORDER BY `title`');
		$data['pgs'] = $query->result();
		$this->load->view('default_dating',$data);
	}
	
	function tip()
	{
		$url = $this->security->xss_clean($this->uri->segment(3));
		$query = $this->db->query('SELECT * FROM `pages` WHERE `url` = "'.$url.'"');
		$data['pg'] = $query->row();
		$this->load->view('dating_veiw',$data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */