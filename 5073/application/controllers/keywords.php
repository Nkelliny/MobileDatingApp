<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Keywords extends CI_Controller 
{
	public function index()
	{
		$query = $this->db->query('SELECT * FROM `linked_keywords` ORDER BY `word`');
		if($query->num_rows() > 0)
		{
			$data['words'] = $query->result();
		}
		else
		{
			$data['words'] = "na";
		}
		$this->load->view('keywords_default',$data);
	}
	
	function doadd()
	{
		$word = $_POST['word'];
    	$link = $_POST['link'];
		$query = $this->db->query('INSERT INTO `linked_keywords` 
		(`id`,`word`,`link`)
		VALUES 
		(NULL,"'.mysql_real_escape_string($word).'","'.mysql_real_escape_string($link).'")');
		redirect('/keywords','refresh');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */