<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comments extends CI_Controller 
{
	public function index()
	{	
		$query = $this->db->query('SELECT `id` FROM `comments` WHERE `status` != "3"');
		$total_comments = $query->num_rows();
		$pg = $this->uri->segment(2,'na');
		if($pg != "na")
		{
			$limit = 'LIMIT '.$pg.',100';
		}
		else
		{
			$limit = 'LIMIT 0,100';
		}
		$this->load->library('pagination');
		$config['base_url'] = 'http://www.xxxxxx.com/5073/index.php/comments/';
		$config['total_rows'] = $total_comments;
		$config['per_page'] = 100;
		$config['uri_segment'] = 2;
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['num_links'] = 10;
		$this->pagination->initialize($config);
		$data['plinks'] = $this->pagination->create_links();
		$query = $this->db->query('SELECT * FROM `comments` WHERE `status` != "3" ORDER BY `id` DESC '.$limit);
		$data['comments'] = $query->result();
		$this->load->view('comments_default',$data);
	}
	
	function pending()
	{
		$query = $this->db->query('SELECT `id` FROM `comments` WHERE `status` = "2"');
		$total_comments = $query->num_rows();
		$pg = $this->uri->segment(3,'na');
		if($pg != "na")
		{
			$limit = 'LIMIT '.$pg.',100';
		}
		else
		{
			$limit = 'LIMIT 0,100';
		}
		$this->load->library('pagination');
		$config['base_url'] = 'http://www.xxxxxx.com/5073/index.php/comments/';
		$config['total_rows'] = $total_comments;
		$config['per_page'] = 100;
		$config['uri_segment'] = 3;
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['num_links'] = 10;
		$this->pagination->initialize($config);
		$data['plinks'] = $this->pagination->create_links();
		$query = $this->db->query('SELECT * FROM `comments` WHERE `status` = "2" ORDER BY `id` DESC '.$limit);
		$data['comments'] = $query->result();
		$this->load->view('comments_default',$data);
	}
	
	function mass()
	{
		$type = $_POST['doMass'];
		if($type == "app")
		{
			foreach($_POST['id'] as $i)
			{
				$query = $this->db->query('UPDATE `comments` SET `status` = "1" WHERE `id` = "'.$i.'"');
			}
		}
		else if($type == "del")
		{
			foreach($_POST['id'] as $i)
			{
				$query = $this->db->query('DELETE FROM `comments` WHERE `id` = "'.$i.'"');
			}
		}
		redirect('/comments','refresh');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */