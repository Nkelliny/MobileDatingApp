<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Faq extends CI_Controller 
{
	public function index()
	{
		$query = $this->db->query('SELECT * FROM `faq` WHERE `status` = "1" ORDER BY `category`,`question` ASC');
		$faqs = $query->result();
		$data['faqs'] = $faqs;
		
		$this->load->view('default_faq',$data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */