<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Faq extends CI_Controller 
{
	public function index()
	{
		$query = $this->db->query('SELECT * FROM `faq` ORDER BY `category`,`question` ASC');
		if($query->num_rows() > 0)
		{
			$faqs = $query->result();
		}
		else
		{
			$faqs = "na";
		}
		$data['faqs'] = $faqs;
		$this->load->view('faq_default',$data);
	}
	
	function status()
	{
		$id = $this->uri->segment(3);
		$status = $this->uri->segment(4);
		$query = $this->db->query('UPDATE `faq` SET `status` = "'.$status.'" WHERE `id` = "'.$id.'"');
		redirect('/faq','refresh');
	}
	
	function edit()
	{
		$id = $this->uri->segment(3);
		$query = $this->db->query('SELECT * FROM `faq` WHERE `id` = "'.$id.'"');
		$faq = $query->row();
		$data['faq'] = $faq;
		$this->load->view('faq_edit',$data);
	}
	
	function doedit()
	{
		$category = $_POST['category'];
		$question = $_POST['question'];
		$answer = $_POST['answer'];
		$id = $_POST['id'];
		
		$query = $this->db->query('UPDATE `faq` SET `category` = "'.$category.'", `question` = "'.$question.'", `answer` = "'.$answer.'" WHERE `id` = "'.$id.'"');
		redirect('/faq','refresh');
	}
	
	function add()
	{
		$this->load->view('faq_add');
	}
	
	function doadd()
	{
		$category = $_POST['category'];
    	$question = $_POST['question'];
    	$answer = $_POST['answer'];
		$query = $this->db->query('INSERT INTO `faq` (`id`,`question`,`answer`,`category`,`status`) 
		VALUES 
		(NULL,"'.mysql_real_escape_string($question).'","'.mysql_real_escape_string($answer).'","'.$category.'","1")');
		redirect('/faq','refresh');
	}
	
	function delete()
	{
		$id = $this->uri->segment(3);
		$query = $this->db->query('DELETE FROM `faq` WHERE `id` = "'.$id.'"');
		redirect('/faq','refresh');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */