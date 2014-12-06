<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller 
{
	public function index()
	{
		$query = $this->db->query('SELECT * FROM `users` WHERE `url` = "xxxxxx"');
		$owner = $query->row();
		$data['owner'] = $owner;
		$data['oinfo'] = $this->my_usersmanager->getAllUserData($owner->id);
		$data['vinfo'] = $this->my_usersmanager->getAllUserData($this->session->userdata('uid'));
		$data['uinfo']        = $this->my_usersmanager->getAllUserData($this->session->userdata('uid'));
		$data['gender']       = $this->my_usersmanager->getPfieldOptions('Gender');
		$data['relationship'] = $this->my_usersmanager->getPfieldOptions('Relationship');
		$data['height']       = $this->my_usersmanager->getPfieldOptions('Height');
		$data['weight']       = $this->my_usersmanager->getPfieldOptions('Weight');
		$data['education']    = $this->my_usersmanager->getPfieldOptions('Education');
		$data['income']       = $this->my_usersmanager->getPfieldOptions('Income');
		$data['religion']     = $this->my_usersmanager->getPfieldOptions('Religion');
		$data['hair']         = $this->my_usersmanager->getPfieldOptions('Hair Color');
		$data['eye']          = $this->my_usersmanager->getPfieldOptions('Eye Color');
		$data['smoke']        = $this->my_usersmanager->getPfieldOptions('Smoke');
		$data['children']     = $this->my_usersmanager->getPfieldOptions('Children');
		$data['ethnicity']    = $this->my_usersmanager->getPfieldOptions('Ethnicity');
		$data['drink']        = $this->my_usersmanager->getPfieldOptions('Drink');
		$this->load->view('default_profile_new',$data);
	}
	
	function geotest()
	{
		$this->load->view('geotest');
	}
	
	function fbtest()
	{
		$this->load->view('facebook_test');
	}
	
	function adtest()
	{
		$this->load->view('ad_test');
	}
	
	function addchat()
	{
		$id = '9999999';
		$nickname = 'xmpptest';
		$email = '999999@xxxxxx.com';
		$user = $this->my_chatmanager->addToXmpp($id,$nickname,$email);
		echo $user;
		echo 'complete';
	}
	
	function updatematch()
	{
		$age = $_POST['age_from'].'-'.$_POST['age_to'];
		$height = $_POST['height_to']; // => 0
		$weight = $_POST['match_weight']; // => 116
		$relationship = $_POST['relationship']; // => 0
		$hair = $_POST['match_hair']; // => 0
		$eye = $_POST['match_eye']; // => 0
		$smoke = $_POST['match_smoke']; // => 0
		$drink = $_POST['match_drink']; // => 0
		$education = $_POST['match_education']; // => 0
		$occ = $this->security->xss_clean($_POST['match_occ']); // => 0
		$ethn = $_POST['match_ethnicity']; // => 0
		$income = $_POST['match_income']; // => 0
		$religion = $_POST['match_religion']; // => 0
		$children = $_POST['match_children']; // => 0
		$uid = $_POST['uid']; // => 5852
		$query = $this->db->query('UPDATE `profile_data` SET 
		`match_age`          = "'.$age.'",
		`match_height`       = "'.$height.'",
		`match_weight`       = "'.$weight.'",
		`match_relationship` = "'.$relationship.'",
		`match_education`    = "'.$education.'",
		`match_income`       = "'.$income.'",
		`match_occ`          = "'.mysql_real_escape_string($occ).'",
		`match_religion`     = "'.$religion.'",
		`match_ethn`         = "'.$ethn.'",
		`match_children`     = "'.$children.'",
		`match_smoke`        = "'.$smoke.'",
		`match_drink`        = "'.$drink.'",
		`match_hair`         = "'.$hair.'",
		`match_eye` = "" WHERE `uid` = "'.$uid.'"');
		$query = $this->db->query('UPDATE `users` SET `pck` = "1" WHERE `id` = "'.$uid.'"');
		redirect('http://www.xxxxxx.com','refresh');
	}
	
	function updateinfo()
	{
		$headline = $_POST['headline']; // => Life is what you make it! So m
		$match_gender = $_POST['match_gender']; // => 17
		$lookingto = $_POST['lookingto']; // => 2
		$country_now = $_POST['country_now']; // => TH
		$state_now = $_POST['state_now']; // => TH60
		$city_now = $_POST['city_now']; // => 263742
		$height = $_POST['height']; // => 111
		$weight = $_POST['body_type']; // => 115
		$education = $_POST['education']; // => 67
		$occ = $this->security->xss_clean($_POST['occ']); // => Programmer
		$income = $_POST['income']; // => 79
		$ethn = $_POST['ethnicity']; // => 99
		$religion = $_POST['religion']; // => 50
		$children = $_POST['haschildren2']; // => 54
		$hair = $_POST['user_hair']; // => 130
		$eye = $_POST['user_eye']; // => 122
		$smoke = $_POST['user_smoke']; // => 85
		$drink = $_POST['user_drink']; // => 92
		$msn = $this->security->xss_clean($_POST['msn']); //=> 
		$yahoo = $this->security->xss_clean($_POST['yahoo']); // => 
		$facebook = $this->security->xss_clean($_POST['facebook']); // => 
		$google = $this->security->xss_clean($_POST['google']); // => 
		$skype = $this->security->xss_clean($_POST['skype']); // => xxxxxx
		$phone = $this->security->xss_clean($_POST['share_phone']); // => 
		$email = $this->security->xss_clean($_POST['share_email']); // => xxxxxx@gmail.com
		$blackberry = $this->security->xss_clean($_POST['blackberry']); // => 
		$uid = $_POST['uid']; // => 5852
		$query = $this->db->query('UPDATE `users` SET 
		`headline`    = "'.mysql_real_escape_string($headline).'",
		`seeking`     = "'.$match_gender.'",
		`lookingfor`  = "'.$lookingto.'",
		`country_now` = "'.$country_now.'",
		`state_now`   = "'.$state_now.'",
		`city_now`    = "'.$city_now.'",
		`pck` = "3" WHERE `id` = "'.$uid.'"');
		$query = $this->db->query('UPDATE `profile_data` SET 
		`match_gender`   = "'.$match_gender.'",
		`user_children`  = "'.$children.'",
		`user_height`    = "'.$height.'",
		`user_weight`    = "'.$weight.'",
		`user_education` = "'.$education.'",
		`user_income`    = "'.$income.'",
		`user_occ`       = "'.mysql_real_escape_string($occ).'",
		`user_religion`  = "'.$religion.'",
		`user_ethn`      = "'.$ethn.'",
		`user_hair`      = "'.$hair.'",
		`user_eye`       = "'.$eye.'",
		`user_smoke`     = "'.$smoke.'",
		`user_drink`     = "'.$drink.'",
		`msn`            = "'.mysql_real_escape_string($msn).'",
		`yahoo`          = "'.mysql_real_escape_string($yahoo).'",
		`facebook`       = "'.mysql_real_escape_string($facebook).'",
		`skype`          = "'.mysql_real_escape_string($skype).'",
		`share_phone`    = "'.mysql_real_escape_string($phone).'",
		`share_email`    = "'.mysql_real_escape_string($email).'",
		`blackberry_pin` = "'.mysql_real_escape_string($blackberry).'" WHERE `uid` = "'.$uid.'"');
		redirect('/test/pcheckb','refresh');
	}
	
	function pcheckb()
	{
		$uid = $this->session->userdata('uid');
		$query = $this->db->query('SELECT * FROM `users` WHERE `id` = "'.$uid.'"');
		$data['udata'] = $query->row();
		$query = $this->db->query('SELECT * FROM `profile_data` WHERE `uid` = "'.$uid.'"');
		$data['pdata'] = $query->row();
		$data['gender']       = $this->my_usersmanager->getPfieldOptions('Gender');
		$data['relationship'] = $this->my_usersmanager->getPfieldOptions('Relationship');
		$data['height']       = $this->my_usersmanager->getPfieldOptions('Height');
		$data['weight']       = $this->my_usersmanager->getPfieldOptions('Weight');
		$data['education']    = $this->my_usersmanager->getPfieldOptions('Education');
		$data['income']       = $this->my_usersmanager->getPfieldOptions('Income');
		$data['religion']     = $this->my_usersmanager->getPfieldOptions('Religion');
		$data['hair']         = $this->my_usersmanager->getPfieldOptions('Hair Color');
		$data['eye']          = $this->my_usersmanager->getPfieldOptions('Eye Color');
		$data['smoke']        = $this->my_usersmanager->getPfieldOptions('Smoke');
		$data['children']     = $this->my_usersmanager->getPfieldOptions('Children');
		$data['ethnicity']    = $this->my_usersmanager->getPfieldOptions('Ethnicity');
		$data['drink']        = $this->my_usersmanager->getPfieldOptions('Drink');
		$this->load->view('ckprofileb',$data);
	}
	
	function pcheck()
	{
		$uid = $this->session->userdata('uid');
		$query = $this->db->query('SELECT * FROM `users` WHERE `id` = "'.$uid.'"');
		$data['udata'] = $query->row();
		$query = $this->db->query('SELECT * FROM `profile_data` WHERE `uid` = "'.$uid.'"');
		$data['pdata'] = $query->row();
		$data['gender']       = $this->my_usersmanager->getPfieldOptions('Gender');
		$data['relationship'] = $this->my_usersmanager->getPfieldOptions('Relationship');
		$data['height']       = $this->my_usersmanager->getPfieldOptions('Height');
		$data['weight']       = $this->my_usersmanager->getPfieldOptions('Weight');
		$data['education']    = $this->my_usersmanager->getPfieldOptions('Education');
		$data['income']       = $this->my_usersmanager->getPfieldOptions('Income');
		$data['religion']     = $this->my_usersmanager->getPfieldOptions('Religion');
		$data['hair']         = $this->my_usersmanager->getPfieldOptions('Hair Color');
		$data['eye']          = $this->my_usersmanager->getPfieldOptions('Eye Color');
		$data['smoke']        = $this->my_usersmanager->getPfieldOptions('Smoke');
		$data['children']     = $this->my_usersmanager->getPfieldOptions('Children');
		$data['ethnicity']    = $this->my_usersmanager->getPfieldOptions('Ethnicity');
		$data['drink']        = $this->my_usersmanager->getPfieldOptions('Drink');
		$this->load->view('ckprofile',$data);
	}
	
	function tjoin()
	{
		print_r($_POST);
	}
	
	function dtfix()
	{
		/*
		$query = $this->db->query('SELECT `id` FROM `users` ORDER BY `id` DESC');
		$users = $query->result();
		$cnt = 10;
		$x = 0;
		$tcnt = 1;
		$cur_data = date('Y-m-d H:i:s',time());
		foreach($users as $u)
		{
			if($x <= 10)
			{
				$this_date = strtotime('-'.$tcnt.' day');
				$ndate = date('Y-m-d H:i:s',$this_date);
				$query = $this->db->query('UPDATE `users` SET `joindate` = "'.$ndate.'" WHERE `id` = "'.$u->id.'"');
				echo $u->id . ' joined: ' . $ndate.'<br />';
				$x++;
			}
			else
			{
				$this_date = strtotime('-'.$tcnt.' day');
				$ndate = date('Y-m-d H:i:s',$this_date);
				$query = $this->db->query('UPDATE `users` SET `joindate` = "'.$ndate.'" WHERE `id` = "'.$u->id.'"');
				echo $u->id . ' joined: ' . $ndate.'<br />';
				$x++;
				$x=0;
				$tcnt++;
			}
		}
		*/
	}
	
	function gensync()
	{
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `device_id` = "na" || `device_id` = ""');
		$users = $query->result();
		foreach($users as $u)
		{
			$sync_code = $this->my_stringmanager->getSyncCode($u->id.'-xxxxxx');
			$query = $this->db->query('UPDATE `users` SET `sync_code` = "'.$sync_code.'" WHERE `id` = "'.$u->id.'"');
			echo $u->id . ' now has a sync code<br />';
		}
		echo 'completed';
	}
	
	function dbdump()
	{
		$query = $this->db->query('SELECT * FROM `users` WHERE `gender` = "19" AND `joindate` LIKE "%2013-03%" ORDER BY `id` DESC');
		$members = $query->result();
		$data = "nick name, email address, sex, sign up date\n";
		foreach($members as $m)
		{
			$data .= "{$m->nickname},{$m->email},ladyboy,{$m->joindate}\n";
		}
		echo $data;
	}
	
	function testchat()
	{
		$data = $this->my_chatmanager->chatGrab('tj-6567@server.fwends.co.uk');
		echo ($data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */