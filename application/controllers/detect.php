<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Detect extends CI_Controller 
{
	public function index()
	{
		//Detect special conditions devices
		$iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
		$iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
		$iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
		$Android = stripos($_SERVER['HTTP_USER_AGENT'],"Android");
		$webOS   = stripos($_SERVER['HTTP_USER_AGENT'],"webOS");
		if($iPod || $iPhone || $iPad)
		{
			$query = $this->db->query('UPDATE `advert` SET `imp` = imp+1 WHERE `id` = "11"');
			if($this->db->affected_rows() > 0)
			{
				//redirect('https://itunes.apple.com/us/app/xxxxxx/id580010244?mt=8');
				$path = 'https://itunes.apple.com/us/app/xxxxxx/id580010244?mt=8';
			}
		}
		else if($Android)
		{
		    // log droid hit
			$query = $this->db->query('UPDATE `advert` SET `clicks` = clicks+1 WHERE `id` = "11"');
			if($this->db->affected_rows() > 0)
			{
				//redirect('/android');
				$path = '/android';
			}
		}
		else
		{
			$path = 'https://www.xxxxxx.com';
		}
		$data['path'] = $path;
		$this->load->view('default_detect',$data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */