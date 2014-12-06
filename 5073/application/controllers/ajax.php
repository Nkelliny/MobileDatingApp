<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller 
{
	public function index()
	{
		// holder function
	}
	
	function updatesettings()
	{
		$id = $_POST['id'];
		$val = $_POST['val'];
		$query = $this->db->query('UPDATE `settings` SET `value` = "'.$val.'" WHERE `id` = "'.$id.'"');
		$tmp = "ok";
		echo $tmp;
		exit;
	}
	
	function updatesubprice()
	{
		$id = $_POST['id'];
		$price = $_POST['price'];
		$query = $this->db->query('UPDATE `subscriptions` SET `price` = "'.$price.'" WHERE `id` = "'.$id.'"');
		$tmp = "ok";
		echo $tmp;
		exit;
	}
	
	function getadcities()
	{
		$code = $_POST['code'];
		$query = $this->db->query('SELECT `id`,`city` FROM `geocity` WHERE `country` = "'.$code.'" AND `city` != "" GROUP BY `city` ORDER BY `city`');
		$html = '';
		if($query->num_rows() > 0)
		{
			$tmp = $query->result();
			foreach($tmp as $c)
			{
				$html .= '<div style="float:left; width:25%; height:25px;">';
				$html .= '<label><input type="checkbox" id="city[]" name="city[]" value="'.$c->id.'" />'.$c->city.'</label>';
				$html .= '</div>';
			}
		}
		else
		{
			$html .= "na";
		}
		echo $html;
		exit;
	}
	
	function sendsms()
	{
		$type = $_POST['type'];
		$amt = $_POST['amt'];
		$msg = $_POST['msg'];
		$when = $_POST['when'];
		// get numbers
		if($type == "thai")
		{
			$query = $this->db->query('SELECT `id`,`name`,`number` FROM `contact_numbers` WHERE `status` = "1" AND `sent` != "1" LIMIT '.$amt.'');
		}
		$contacts = $query->result();
		include("/home/www/xxxxxx.com/htdocs/js/sms/sms.class.php");
		$result_message = 'SMS RESULTS<br />';
		//print_r($contacts);
		foreach($contacts as $c)
		{
			
			$username = 'xxxxxx';
			$password = '528051';
			//$username = 'thaibulksms';
			//$password = 'thisispassword';
			$ph = str_replace('+66','0',$c->number);
			$msisdn = $ph;
			$message = str_replace('**NAME**',($c->name == "" ? '' : $c->name),$msg);
			$message = str_replace('**Name**',($c->name == "" ? '' : $c->name),$message);
			$message = str_replace('**name**',($c->name == "" ? '' : $c->name),$message);
			$sender = 'xxxxxx';
			//$sender = 'THAIBULKSMS';
			$ScheduledDelivery = $when;
			$force = 'standard';
			$result = sms::send_sms($username,$password,$msisdn,$message,$sender,$ScheduledDelivery,$force);
			$result_message .= 'Id: ' .$c->id. ' name: ' . $c->name . ' number: ' . $ph . ' result:' . $result.'<br /><br />';
			// update number
			$query = $this->db->query('UPDATE `contact_numbers` SET `sent` = 1 WHERE `id` = "'.$c->id.'"');
			
		}
		echo $result_message;
		exit;
	}
	
	function updatecontactkey()
	{
		$query = $this->db->query('UPDATE `contact_keys` SET `type` = "'.$_POST['value'].'" WHERE `id` = "'.$_POST['id'].'"');
		if($this->db->affected_rows() > 0)
		{
			$tmp = "ok";
		}
		else
		{
			$tmp = "notok";
		}
		echo $tmp;
		exit;
	}
	
	function setkey()
	{
		$str = $_POST['val'];
		$nstr = $this->my_stringmanager->cleanForUrl($str);
		echo $nstr;
		exit;
	}
	
	function getapnsusers()
	{
		$type = $_POST['users'];
		if($type == "female free")
		{
			$gender = "17";
			$ispro = "0";
		}
		else if($type == "male free")
		{
			$gender = "18";
			$ispro = "0";
		}
		else if($type == "ladyboy free")
		{
			$gender = "19";
			$ispro = "0";
		}
		if($type == "female pro")
		{
			$gender = "17";
			$ispro = "1";
		}
		else if($type == "male pro")
		{
			$gender = "18";
			$ispro = "1";
		}
		else if($type == "ladyboy pro")
		{
			$gender = "19";
			$ispro = "1";
		}
		
		$query = $this->db->query('SELECT `id`,`nickname`,`picpath`,`haspic` FROM `users` WHERE `device_token` != "" AND `gender` = "'.$gender.'" AND `ispro` = "'.$ispro.'" ORDER BY `id` DESC');
		// testing sql
		//$query = $this->db->query('SELECT `id`,`nickname`,`haspic`,`picpath` FROM `users` WHERE `utype` = "a" AND `gender` = "'.$gender.'" ORDER BY `id` DESC');
		$users = '';
		if($query->num_rows() > 0)
		{
			$ulist = $query->result();
			foreach($ulist as $u)
			{
				$img = $this->my_usersmanager->getProfilePicFromId($u->id);
				if($u->haspic == "1")
				{
					$pic = '<img src="http://www.xxxxxx.com/image.php?src='.$u->picpath.'&w=75&h=75&zc=1" width="75" height="75" />';
				}
				else
				{
					$pic = '<img src="http://www.xxxxxx.com/images/user_icon.png" width="75" height="75" />';
				}
				$users .= '<div style="float:left; width:90px; height:90px;"><label><input name="selusers[]" type="checkbox" value="'.$u->id.'" />'.$pic.'</label></div>';
			}
		}
		else
		{
			$users = "There are currently no users of this gender with a stored device token!";
		}
		echo $users;
		exit;
	}
	
	function setmainpicfix()
	{
		$uid = $_POST['uid'];
		$img = $_POST['img'];
		// default ismain to 2
		$query = $this->db->query('UPDATE `images` SET `ismain` = "2" WHERE `uid` = "'.$uid.'"');
		// set ismain for correct image
		$query = $this->db->query('UPDATE `images` SET `ismain` = "1", `status` = "1" WHERE `id` = "'.$img.'" AND `uid` = "'.$uid.'"');
		// update user to show haspic
		$query = $this->db->query('UPDATE `users` SET `haspic` = "1" WHERE `id` = "'.$uid.'"');
		echo 'ok';
		exit;
	}
	
	function getlivestats()
	{
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `mobile_online` = "1"');
		$live_girls = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `mobile_online` = "1"');
		$live_guys = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `mobile_online` = "1"');
		$live_ladyboys = $query->num_rows();
		
		// pro 
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `mobile_online` = "1"');
		$live_girls_pro = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `mobile_online` = "1"');
		$live_guys_pro = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `mobile_online` = "1"');
		$live_ladyboys_pro = $query->num_rows();
		
		$query = $this->db->query('SELECT `value` FROM `settings` WHERE `id` = "3"');
		$row = $query->row();
		$cur_active = $row->value;
		$total_free_hour = $live_guys + $live_girls + $live_ladyboys;
		if($total_free_hour > $cur_active)
		{
			$query = $this->db->query('UPDATE `settings` SET `value` = "'.$total_free_hour.'" WHERE `id` = "3"');
		}
		
		echo $live_guys.'::'.$live_girls.'::'.$live_ladyboys.'::'.$live_guys_pro.'::'.$live_girls_pro.'::'.$live_ladyboys_pro;
		exit;
	}
	
	function usersearch()
	{
		$type = $_POST['stype'];
		$val = $this->security->xss_clean($_POST['val']);
		if($type == "id")
		{
			$query = $this->db->query('SELECT users.*, profile_data.* FROM users JOIN profile_data ON users.id = profile_data.uid WHERE users.id = "'.$val.'" ORDER BY users.id DESC');
		}
		else if($type == "nickname")
		{
			$query = $this->db->query('SELECT users.*, profile_data.* FROM users JOIN profile_data ON users.id = profile_data.uid WHERE users.nickname LIKE "%'.$val.'%" ORDER BY users.id DESC');
		}
		else if($type == "email")
		{
			$query = $this->db->query('SELECT users.*, profile_data.* FROM users JOIN profile_data ON users.id = profile_data.uid WHERE users.email = "'.$val.'" ORDER BY users.id DESC');
		}
		if($query->num_rows() > 0)
		{
			$users = $query->result();
			$data['users'] = $users;
			$this->load->view('users_search_res',$data);
		}
		else
		{
			echo "na";
			exit;
		}
	}
	
	function imgRotate()
	{
		$id = $_POST['id'];
		$query = $this->db->query('SELECT * FROM `images` WHERE `id` = "'.$id.'"');
		$row = $query->row();
		$raw_path = $row->path;
		$mystring = $raw_path;
		$findme   = 'facebook.com';
		$pos = strpos($mystring, $findme);
		if ($pos === false) 
		{
			//$config['image_library'] = 'netpbm';
			//$config['library_path'] = '/usr/bin/';
			$config['source_image'] = $_SERVER['DOCUMENT_ROOT'].''.$row->path;
			$config['rotation_angle'] = '270';
			$this->load->library('image_lib', $config); 
			$this->image_lib->initialize($config);
			if ( ! $this->image_lib->rotate())
			{
    			echo $this->image_lib->display_errors();
			}
			else
			{
				$tmp = '<img src="/image.php?src='.$raw_path.'&w=150&h=150&zc=1&cb='.time().'" width="150" height="150" alt="" title="" border="0" style="border:#000000 1px solid;" /><br />';
				$tmp .= '<a href="javascript:void(0);" onclick="imgRotate('.$id.');">Rotate Image</a><br />';
				$tmp .= '<label><input name="site_'.$id.'" id="site_'.$id.'" type="checkbox" value="1" '.($row->home == "1" ? 'checked="checked"' : '').' onchange="setHomeImage('.$id.');" />ON SITE</label><br />';
				$tmp .= '<label><input name="mailer_'.$id.'" id="mailer_'.$id.'" type="checkbox" value="1" '.($row->mailer == "1" ? 'checked="checked"' : '').' onchange="setMailerImage('.$id.');" />ON MAILER</label><br />';
				$tmp .= '<a href="javascript:void(0);" onclick="deleteImage('.$id.');">Delete</a><br />';
			}
		}
		else
		{
			$tmp = "Facebook Image, can not be rotated.";
		}
		echo $tmp;
		exit;
	}
	
	function setUserType()
	{
		$id = $_POST['id'];
		$type = $_POST['type'];
		$query = $this->db->query('UPDATE `users` SET `type` = "'.$type.'" WHERE `id` = "'.$id.'"');
		$tmp = "ok";
		echo $tmp;
		exit;
	}
	
	function updateGender()
	{
		$id = $_POST['id'];
		$gender = $_POST['gender'];
		$query = $this->db->query('UPDATE `users` SET `gender` = "'.$gender.'" WHERE `id` = "'.$id.'"');
		if($this->db->affected_rows() > 0)
		{
			$tmp = "ok";
		}
		else
		{
			$tmp = 'notok';
		}
		echo $tmp;
		exit;
	}
	
	function updateMessage()
	{
		$id = $_POST['id'];
		$msg = $_POST['msg'];
		$query = $this->db->query('UPDATE `messages` SET `msg` = "'.mysql_real_escape_string($msg).'" WHERE `id` = "'.$id.'"');
		$tmp = "ok";
		echo $tmp;
		exit;
	}
	
	function deleteMessage()
	{
		$id = $_POST['id'];
		$query = $this->db->query('UPDATE `messages` SET `status` = "3" WHERE `id` = "'.$id.'"');
		$tmp = "ok";
		echo $tmp;
		exit;
	}
	
	function deleteImage()
	{
		$id = $_POST['id'];
		$query = $this->db->query('SELECT `uid` FROM `images` WHERE `id` = "'.$id.'"');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$query = $this->db->query('UPDATE `users` SET `haspic` = "2" WHERE `id` = "'.$row->uid.'"');
			$query = $this->db->query('UPDATE `images` SET `status` = "3" WHERE `id` = "'.$id.'"');
			$tmp = "ok";
		}
		else
		{
			$tmp = "notok";
		}
		echo $tmp;
		exit;
	}
	
	function setHomeImage()
	{
		$id = $_POST['id'];
		$val = $_POST['val'];
		$query = $this->db->query('UPDATE `images` SET `home` = "'.$val.'" WHERE `id` = "'.$id.'"');
		$tmp = "ok";
		echo $tmp;
		exit;
	}
	
	function setMailerImage()
	{
		$id = $_POST['id'];
		$val = $_POST['val'];
		$query = $this->db->query('UPDATE `images` SET `mailer` = "'.$val.'" WHERE `id` = "'.$id.'"');
		$tmp = "ok";
		echo $tmp;
		exit;
	}
	
	function setImageStatus()
	{
		$id = $_POST['img'];
		$status = $_POST['status'];
		if($status == "1")
		{
			$query = $this->db->query('UPDATE `images` SET `status` = "'.$status.'", `home` = "1", `mailer` = "1",`ismain` = "1" WHERE `id` = "'.$id.'"');
		}
		else
		{
			$query = $this->db->query('UPDATE `images` SET `status` = "'.$status.'", `home` = "2", `mailer` = "2",`ismain` = "2" WHERE `id` = "'.$id.'"');
		}
		$tmp = "ok";
		echo $tmp;
		exit;
	}
	
	function setAdStatus()
	{
		$id = $_POST['id'];
		$status = $_POST['status'];
		$query = $this->db->query('UPDATE `advert` SET `status` = "'.$status.'" WHERE `id` = "'.$id.'"');
		$tmp = "ok";
		echo $tmp;
		exit;
	}
	
	function doCommentStatus()
	{
		$id = $_POST['id'];
		$status = $_POST['status'];
		$query = $this->db->query('UPDATE `comments` SET `status` = "'.$status.'" WHERE `id` = "'.$id.'"');
		$tmp = "ok";
		echo $tmp;
		exit;
	}
	
	function setUserProfileStatus()
	{
		$val = $_POST['val'];
		$uid = $_POST['uid'];
		$query = $this->db->query('UPDATE `profile_data` SET `pstatus` = "'.$val.'" WHERE `uid` = "'.$uid.'"');
		$tmp = "ok";
		echo $tmp;
		exit;
	}
	
	function setUserStatus()
	{
		$val = $_POST['val'];
		$uid = $_POST['uid'];
		$query = $this->db->query('UPDATE `users` SET `status` = "'.$val.'" WHERE `id` = "'.$uid.'"');
		$tmp = "ok";
		echo $tmp;
		exit;
	}
	
	function isMod()
	{
		$val = $_POST['val'];
		$uid = $_POST['uid'];
		$query = $this->db->query('UPDATE `users` SET `ismod` = "'.$val.'" WHERE `id` = "'.$uid.'"');
		$tmp = "ok";
		echo $tmp;
		exit;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
