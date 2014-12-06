<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Crop extends CI_Controller 
{
	public function index()
	{	
		// holder function
	}
	
	function docrop()
	{
		$img = $this->uri->segment(3);
		$query = $this->db->query('SELECT `path` FROM `images` WHERE `id` = "'.$img.'"');
		$row = $query->row();
		$data['img_id'] = $img;
		$data['path'] = $row->path;
		$this->load->view('crop_image',$data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */