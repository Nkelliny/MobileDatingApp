<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Messages extends CI_Controller 
{
	public function index()
	{	
		$query = $this->db->query('SELECT `id` FROM `messages` WHERE `status` != "3"');
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
		$config['base_url'] = 'http://www.xxxxxx.com/5073/index.php/messages/';
		$config['total_rows'] = $total_comments;
		$config['per_page'] = 100;
		$config['uri_segment'] = 2;
		$config['full_tag_open'] = '<div class="table_pagination right">';
		$config['full_tag_close'] = '</div>';
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['num_links'] = 10;
		$this->pagination->initialize($config);
		$data['plinks'] = $this->pagination->create_links();
		$query = $this->db->query('SELECT * FROM `messages` WHERE `status` != "3" ORDER BY `id` DESC '.$limit);
		$messages = $query->result();
		$data['messages'] = $messages;
		$this->load->view('messages_default',$data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */