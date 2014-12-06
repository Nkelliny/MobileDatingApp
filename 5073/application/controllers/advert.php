<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Advert extends CI_Controller 
{
	public function index()
	{
		$query = $this->db->query('SELECT * FROM `advert` ORDER BY `name`');
		$data['ads'] = $query->result();
		//echo $_SERVER['DOCUMENT_ROOT'];
		$this->load->view('advert_default',$data);
	}
	
	function add()
	{
		$data['size'] = $this->my_stringmanager->get_enum_values('advert','size');
		$data['poss'] = $this->my_stringmanager->get_enum_values('advert','poss');
		$data['type'] = $this->my_stringmanager->get_enum_values('advert','type');
		$data['adfor'] = $this->my_stringmanager->get_enum_values('advert','adfor');
		$query = $this->db->query('SELECT `code`,`name` FROM `geocountry` ORDER BY `name`');
		$res = $query->result();
		$data['countries'] = $res;
		
		$this->load->view('advert_add',$data);
	}
	
	function doadd()
	{
		$name = $_POST['name']; //=> Mobile Image Test Ad
		$owner = $_POST['owner']; //=> xxxxxx
		$adfor = $_POST['adfor']; //=> Mobile
		$type = $_POST['type']; //=> image
		$poss = $_POST['poss']; //=> footer
		$size = $_POST['size']; //=> 320x50
		$path = $_POST['path']; //=> https://www.xxxxxx.com/at/mobile_image_test_ad
		$outlink = $_POST['outlink']; //=> https://www.xxxxxx.com
		$gender = implode(',',$_POST['gender']);
		$adlimit = $_POST['adlimit']; //=> -1
		$adcode = $_POST['adcode']; //=> Testing of the admin stuff
		$country = $_POST['country']; //=> TH
    	$city = implode(',',$_POST['city']);
		if($_FILES['adfile']['size'] > 0)
		{
			$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . '/images/adverts/';
			$config['allowed_types'] = '*';
			$config['overwrite'] = false;
			$new_file_name = time().'.'.end(explode('.',$_FILES['adfile']['name']));
			$config['file_name'] = $new_file_name;
			$config['max_size']	= '0';
			$config['max_width']  = '0';
			$config['max_height']  = '0';
			$this->load->library('upload', $config);
			$field_name = "adfile";
			if ( ! $this->upload->do_upload($field_name))
			{
				$error = array('error' => $this->upload->display_errors());
				$msg = 'upload_file: ' . $_FILES['adfile']['name'] . ' new name: '.$new_file_name.' :: file size : '.$_FILES['adfile']['size'] . ' :: ';
				foreach($error as $key=>$value)
				{
					$msg .= $key . '=>' .$value.'-----'; 
				}
				$adfl = '';
				mail('xxxxxx@gmail.com','imgtest',$msg);
			}
			else
			{
				$img = array('upload_data' => $this->upload->data());
				$adfl = 'https://www.xxxxxx.com/images/adverts/'.$img['upload_data']['file_name'];
			}
		}
		else
		{
			$adfl = '';
		}
		$query = $this->db->query('INSERT INTO `advert` 
		(`id`,`owner`,`name`,`path`,`outlink`,`size`,`poss`,`type`,`adfor`,`adcode`,`country`,`gender`,`implimit`,`status`,`adfl`) 
		VALUES 
		(NULL,"'.$owner.'","'.$name.'","'.$path.'","'.$outlink.'","'.$size.'","'.$poss.'","'.$type.'","'.$adfor.'","'.$adcode.'","'.$country.'|'.$city.'","'.$gender.'","'.$adlimit.'","1","'.$adfl.'")');
		redirect('/advert','refresh');
	}
	
	function edit()
	{
		$id = $this->uri->segment(3);
		echo $id;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
