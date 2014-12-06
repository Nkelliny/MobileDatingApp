<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Siteads extends CI_Controller 
{
	public function index()
	{
		$query = $this->db->query('SELECT * FROM `advert` WHERE `status` != "3" ORDER BY `id`');
		$ads = $query->result();
		$data['ads'] = $ads;
		$data['countries'] = $this->my_geomanager->getCountries();
		$this->load->view('siteads_default',$data);
	}
	
	function add()
	{
		$type = $_POST['type'];
    	$size = $_POST['size'];
    	$poss = $_POST['poss'];
    	$code = $_POST['adcode']; 
		$country = $_POST['country'];
		$gender = $_POST['country'];
    	$outlink = $_POST['outlink'];
		if($type == "image")
		{
			if($_FILES['image']['name'] != "")
			{
				$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'].'/images/a_pics/';
				//echo $_SERVER['DOCUMENT_ROOT'].'/images/profile_pics/';
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size']	= '5120';
				$config['max_width']  = '0';
				$config['max_height']  = '0';
				$this->load->library('upload', $config);
				if ( ! $this->upload->do_upload('image'))
				{
					$error = array('error' => $this->upload->display_errors());
					print_r($error);
					exit;
				}
				else
				{
					$img = array('upload_data' => $this->upload->data());
					$path = '/images/a_pics/'.$img['upload_data']['file_name'];				
				}
			}
		}
		else
		{
			$path = $code;
		}
		$query = $this->db->query('INSERT INTO `advert` 
		(`id`,`owner`,`path`,`imp`,`clicks`,`outlink`,`size`,`poss`,`type`,`country`,`gender`,`status`) 
		VALUES 
		(NULL,"1","'.mysql_real_escape_string($path).'","0","0","'.mysql_real_escape_string($outlink).'","'.$size.'","'.$poss.'","'.$type.'","'.$country.'","'.$gender.'","1")');
		redirect('siteads','refresh');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */