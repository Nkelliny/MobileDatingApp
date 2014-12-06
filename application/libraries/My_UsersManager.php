<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_UsersManager 
{	
	var $CI;
	function My_UsersManager()
	{
		$CI =& get_instance();
		$CI->load->database();
		$CI->load->library('session');
		$CI->load->library('email');
	}
	
	function userSnoop($uid,$lat,$lon)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('INSERT INTO `userSnoop` (`id`,`uid`,`lat`,`lon`) VALUES (NULL,"'.$uid.'","'.$lat.'","'.$lon.'")');
		return true;
	}
	
	function getBlocksList($uid,$type=0)
	{
		$this->CI =& get_instance();
		$blocks = array();
		$query = $this->CI->db->query('SELECT `blocked` FROM `blocks` WHERE `blockedby` = "'.$uid.'"');
		if($query->num_rows() > 0)
		{
			$res = $query->result();
			foreach($res as $r)
			{
				$blocks[] = $r->blocked;
			}
		}
		
		$query = $this->CI->db->query('SELECT `blockedby` FROM `blocks` WHERE `blocked` = "'.$uid.'"');
		if($query->num_rows() > 0)
		{
			$res = $query->result();
			foreach($res as $r)
			{
				$blocks[] = $r->blockedby;
			}
		}
		if(count($blocks) > 1)
		{
			if($type == "r")
			{
				$tmp = ' AND `viewed` NOT IN ('.implode(',',$blocks).')';	
			}
			else if($type == "f")
			{
				$tmp = ' AND `owner` NOT IN ('.implode(',',$blocks).')';
			}
			else
			{
				$tmp = ' AND `id` NOT IN ('.implode(',',$blocks).')';
			}
		}
		else
		{
			$tmp = 'na';
		}
		return $tmp;
	}
	
	function getAppSettings($id)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `value` FROM `settings` WHERE `id` = "'.$id.'"');
		$row = $query->row();
		$val = $row->value;
		return $value;
	}
	
	function addSubscription($product,$userid)
	{
		// get price from product id
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT * FROM `subscriptions` WHERE `productid` = "'.$product.'"');
		$sub = $query->row();
		// update subscriptions
		$query = $this->CI->db->query('INSERT INTO `userSubs` 
		(`id`,`uid`,`sid`,`price`) VALUES 
		(NULL,"'.$userid.'","'.$sub->id.'","'.$sub->price.'")');
		mail('xxxxxx@gmail.com','Subscription added!','info-'.$sub->id . ' ' . $sub->name . ' ' . $sub->price);
	}
	
	function sendPhotoApproveMail($path,$uid,$pid,$mpid)
	{
		$to = 'xxxxxx@gmail.com,xxxx@xxxx.com';
		$subject = 'New Photo Has Been Uploaded!'.$mpid;
		$msg = '<html>';
		$msg .= '<head>';
		$msg .= '<body>';
		$msg .= '<table width="320" border="0" cellspacing="2" cellpadding="2">';
		$msg .= '<tr>';
		$msg .= '<td colspan="2" align="center" valign="middle">';
		$msg .= 'MyPID: '.$mpid.'<br />';
		$msg .= '<img src="http://www.xxxxxx.com'.$path.'" width="300" height="300" /></td>';
		$msg .= '</tr>';
		$msg .= '<tr>';
		$msg .= '<td align="center" valign="middle"><a href="https://www.xxxxxx.com/5073/index.php/photos/autoapprove/'.$uid.'/'.$pid.'">APPROVE</a></td>';
		$msg .= '<td align="center" valign="middle"><a href="https://www.xxxxxx.com/5073/index.php/photos/autodelete/'.$uid.'/'.$pid.'">DELETE</a></td>';
		$msg .= '</tr>';
		$msg .= '</table>';
		$msg .= '</body>';
		$msg .= '</html>';
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		// Additional headers
		$headers .= 'To: xxxx <xxxxxx@gmail.com>, Div Cunt <xxxx@xxxx.com>' . "\r\n";
		//$headers .= 'From: Birthday Reminder <birthday@example.com>' . "\r\n";
		//$headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
		//$headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";
		mail($to,$subject,$msg,$headers);
	}
	
	function getAgeLmt($uid)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `match_age` FROM `profile_data` WHERE `uid` = "'.$uid.'"');
		$row = $query->row();
		if($row->match_age != "")
		{
			$agelmt = explode('-',$row->match_age);
			//$cur_year = date('Y',time());
			$min = $agelmt[0];
			$max = $agelmt[1];
			//mail('xxxxxx@gmail.com','test',$min.'-'.$max);
		}
		else
		{
			$min = "18";
			$max = "99";
		}
		$tmp = array("min"=>$min,"max"=>$max);
		return $tmp;
	}
	
	function getDistance($latitude1, $longitude1, $latitude2, $longitude2, $unit = 'Km') 
	{ 
		$theta = $longitude1 - $longitude2; 
		$distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + 
		(cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * 
		cos(deg2rad($theta))); 
		$distance = acos($distance); 
		$distance = rad2deg($distance); 
		$distance = $distance * 60 * 1.1515; 
		switch($unit) 
		{ 
			case 'Mi': 
			break; 
			case 'Km' : 
			$distance = $distance * 1.609344; 
		} 
		return (round($distance,2)).$unit; 
	}
	
	function getUserAppPic($id)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `haspic`,`picpath` FROM `users` WHERE `id` = "'.$id.'"');
		$row = $query->row();
		$haspic = $row->haspic;
		if($haspic == 1)
		{
			$path = 'https://www.xxxxxx.com'.$row->picpath;
		}
		else if($haspic == 2)
		{
			$path = 'https://www.xxxxxx.com/images/no_profile_photo_user.png';
		}
		else if($haspic == 3)
		{
			$path = 'https://www.xxxxxx.com/images/pending_profile_user.png';
		}
		else if($haspic == 4)
		{
			$path = 'https://www.xxxxxx.com/images/not_approved_user.png';
		}
		return $path;
	}
	
	function getAppPic($id)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `haspic`,`picpath` FROM `users` WHERE `id` = "'.$id.'" AND `haspic` = "1"');
		$row = $query->row();
		if(@$row->haspic == 1)
		{
			$pic = 'http://www.xxxxxx.com'.$row->picpath;
		}
		else
		{
			$pic = 'https://www.xxxxxx.com/images/no_profile_photo.png';
		}
		
		return $pic;
	}
	
	function getUserDataAppDistance($userid,$id,$lat,$lon)
	{
		$this->CI =& get_instance();
		
		// get diset from userid
		$query = $this->CI->db->query('SELECT `diset` FROM `users` WHERE `id` = "'.$userid.'"');
		$user = $query->row();
		// get profile data from id
		$query = $this->CI->db->query('SELECT * FROM `users` WHERE `id` = "'.$id.'"');
		if($query->num_rows() > 0)
		{
			$udata = $query->row();
			$query = $this->CI->db->query('SELECT * FROM `profile_data` WHERE `uid` = "'.$id.'"');
			$pdata = $query->row();
			// format user info
			// data to send
			$tmp['userid']       = $udata->id;
			$tmp['nickname']     = $udata->nickname;
			$tmp['relationship'] = self::getPfieldValue($pdata->user_relationship,$userid);
			$tmp['seeking']      = self::getGenderValues($udata->seeking,$userid);
			$tmp['looking']      = self::getPfieldValue($pdata->match_relationship,$userid);
			$tmp['gender']       = self::getPfieldValue($udata->gender,$userid);
			$tmp['dob']          = $udata->dob;
			$tmp['age']          = (self::birthday($udata->dob) > 99 ? 18 : self::birthday($udata->dob));
			$tmp['height']       = self::getPfieldValue($pdata->user_height,$userid);
			$tmp['weight']       = self::getPfieldValue($pdata->user_weight,$userid);
			$tmp['facebook']     = $pdata->facebook;
			$tmp['twitter']      = $pdata->twitter;
			$tmp['linkedin']     = $pdata->linkedin;
			$tmp['bio']          = ($pdata->bio != "" ? $pdata->bio : '');
			$tmp['msgcnt']       = 0; //rand(0,5);
			$tmp['distance']     = self::getDistance($lat,$lon,$udata->cur_lat,$udata->cur_lon,$user->diset);
			$tmp['favstatus']    = self::checkFav($userid,$udata->id);
			$tmp['onlinestatus'] = self::getOnlineStatus($udata->id);
			$tmp['lastactivity'] = self::getLastAct($udata->lastactivity);
			$tmp['thumb']        = 'http://www.xxxxxx.com/image.php?src='.self::getAppPic($udata->id).'&w=150&h=150&zc=1';
			$tmp['pic']          = self::getAppPic($udata->id);
			$tmp['online']       = self::getOnlineStatus($udata->id);
			$tmp['dislim']       = $udata->dislim;
			$tmp['showonly']     = self::getGenderValues($udata->showonly,$userid);
			$tmp['diset']        = $udata->diset;
		}
		else
		{
			$tmp = 'na';
		}
		return $tmp;
	}
	
	function getUserDataApp($userid,$id)
	{
		$this->CI =& get_instance();
		
		// get lat / lon from userid
		$query = $this->CI->db->query('SELECT `cur_lat`,`cur_lon`,`seeking`,`diset` FROM `users` WHERE `id` = "'.$userid.'"');
		$user = $query->row();
		// get profile data from id
		$query = $this->CI->db->query('SELECT * FROM `users` WHERE `id` = "'.$id.'"');
		if($query->num_rows() > 0)
		{
			$udata = $query->row();
			$query = $this->CI->db->query('SELECT * FROM `profile_data` WHERE `uid` = "'.$id.'"');
			$pdata = $query->row();
			// format user info
			// data to send
			$tmp['userid']       = $udata->id;
			$tmp['nickname']     = $udata->nickname;
			$tmp['relationship'] = self::getPfieldValue($pdata->user_relationship,$userid);
			$tmp['seeking']      = self::getGenderValues($udata->seeking,$userid);
			$tmp['looking']      = self::getPfieldValue($pdata->match_relationship,$userid);
			$tmp['gender']       = self::getPfieldValue($udata->gender,$userid);
			$tmp['dob']          = $udata->dob;
			$tmp['age']          = (self::birthday($udata->dob) > 99 ? 18 : self::birthday($udata->dob));
			$tmp['height']       = self::getPfieldValue($pdata->user_height,$userid);
			$tmp['weight']       = self::getPfieldValue($pdata->user_weight,$userid);
			$tmp['facebook']     = $pdata->facebook;
			$tmp['twitter']      = $pdata->twitter;
			$tmp['linkedin']     = $pdata->linkedin;
			$tmp['bio']          = ($pdata->bio != "" ? $pdata->bio : '');
			$tmp['msgcnt']       = 0; //rand(0,5);
			$tmp['distance']     = self::getDistance($user->cur_lat,$user->cur_lon,$udata->cur_lat,$udata->cur_lon,$user->diset);
			$tmp['favstatus']    = self::checkFav($userid,$udata->id);
			$tmp['onlinestatus'] = self::getOnlineStatus($udata->id);
			$tmp['lastactivity'] = self::getLastAct($udata->lastactivity);
			$tmp['thumb']        = 'https://www.xxxxxx.com/image.php?src='.self::getAppPic($udata->id).'&w=150&h=150&zc=1';
			$tmp['pic']          = self::getAppPic($udata->id);
			$tmp['online']       = self::getOnlineStatus($udata->id);
			$tmp['dislim']       = @$udata->dislim;
			$tmp['showonly']     = self::getGenderValues(@$udata->showonly,$userid);
			$tmp['diset']        = @$udata->diset;
		}
		else
		{
			$tmp = 'na';
		}
		return $tmp;
	}
	
	function getAppLangs()
	{
		$this->CI =& get_instance();
		$fields = $this->CI->db->list_fields('app_lang');
		$langs = array();
		foreach($fields as $field)
		{
			if($field != "id" && $field != "name" && $field != "key")
			{
				if($field == "value")
				{
					$langs[] = "en";
				}
				else
				{
					$langs[] = str_replace('_value','',$field); 
				}
			}
		}
		return $langs;
	}
	
	function getGenderValues($vals,$uid)
	{
		// get laung
		$langs = self::getAppLangs();
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `lang` FROM `users` WHERE `id` = "'.$uid.'"');
		$row = $query->row();
		$tlang = strtolower($row->lang);
		if(in_array($tlang,$langs))
		{
			$lang = $tlang;
		}
		else
		{
			$lang = "en";
		}
		
		$fld = ($lang == "en" ? 'name' : $lang.'_name');
		// get gender value fld = 6
		$query = $this->CI->db->query('SELECT `'.$fld.'` FROM `pfields_values` WHERE `fid` = "6" AND `id` = "'.$vals.'"');
		$row = $query->row();
		$gender = $row->$fld;
		//$gender = str_replace('17','Female',$vals);
		//$gender = str_replace('18','Male',$gender);
		//$gender = str_replace('19','Ladyboy',$gender);
		return $gender;
	}
	
	function getPfieldValue($val,$uid)
	{
		$langs = self::getAppLangs();
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `lang` FROM `users` WHERE `id` = "'.$uid.'"');
		$row = $query->row();
		if(in_array(strtolower($row->lang),$langs))
		{
			$lang = strtolower($row->lang);
		}
		else
		{
			$lang = "en";
		}
		$fld = ($lang == "en" ? 'name' : $lang.'_name');
		$query = $this->CI->db->query('SELECT `'.$fld.'` FROM `pfields_values` WHERE `id` = "'.$val.'"');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$value = $row->$fld;
		}
		else
		{
			$value = "na";
		}
		return $value;
	}
	
	function getLastAct($ts)
	{
		$how_log_ago = '';
		$seconds = time() - $ts; 
		$minutes = (int)($seconds / 60);
		$hours = (int)($minutes / 60);
		$days = (int)($hours / 24);
		if ($days >= 1) 
		{
			$how_log_ago .= $days . ' Day' . ($days != 1 ? 's' : '');
		} 
		else if ($hours >= 1) 
		{
			$how_log_ago .= $hours . ' Hour' . ($hours != 1 ? 's' : '');
		} 
		else if ($minutes >= 1) 
		{
			$how_log_ago .= $minutes . ' Minute' . ($minutes != 1 ? 's' : '');
		} 
		else 
		{
			$how_log_ago .= $seconds . ' Second' . ($seconds != 1 ? 's' : '');
		}
		return $how_log_ago;
	}
	
	function ckProfile($id)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `pck` FROM `users` WHERE `id` = "'.$id.'"');
		$row = $query->row();
		$pck = $row->pck;
		return $pck;
	}
	
	function checkFav($owner,$fav)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `id` FROM `favs` WHERE `owner` = "'.$owner.'" AND `fav` = "'.$fav.'"');
		if($query->num_rows() > 0)
		{
			$tmp = '1';
		}
		else
		{
			$tmp = '2';
		}
		return $tmp;
	}
	
	function getProfileFieldsSite()
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT * FROM `pfields` WHERE `site` = "y" AND `status` = "1"');
		$fields = $query->result();
		$field_values = array();
		foreach($fields as $f)
		{
			$query = $this->CI->db->query('SELECT * FROM `pfields_values` WHERE `fid` = "'.$f->id.'"');
			$values = $query->result();
			$varray = array();
			foreach($values as $v)
			{
				$varray[] = array('value'=>$v->id,'txt'=>$v->name);
			}
			$field_values[strtolower($f->name)] = $varray;
		}
		return $field_values;
	}
	
	function getOnlineStatus($uid)
	{
		$this->CI =& get_instance();
		// get settings
		$query = $this->CI->db->query('SELECT `value` FROM `settings` WHERE `id` = "8"');
		$row = $query->row();
		$otime = explode('|',$row->value);
		
		$query = $this->CI->db->query('SELECT `value` FROM `settings` WHERE `id` = "9"');
		$row = $query->row();
		$itime = explode('|',$row->value);
		//mail('xxxx@fwends.com','timetest',"".$itime[0]." days ".$itime[1]." hours ".$itime[2]." mins");
		$query = $this->CI->db->query('SELECT `online`,`lastactivity`,`mobile_online` FROM `users` WHERE `id` = "'.$uid.'"');
		$row = $query->row();
		$cur_time = time(); 
		$la = $row->lastactivity;
		// online now to 5 h 32 mins
		if($la > strtotime("".$otime[0]." hours ".$otime[1]." mins",$cur_time))
		{
			$online = "1";
		} 
		else if($la < strtotime("".$otime[0]." hours ".$otime[1]." mins",$cur_time) && $la > strtotime("".$itime[0]." days ".$itime[1]." hours ".$itime[2]." mins",$cur_time))
		{
			$online = "3";
		}
		else
		{
			$online = "2";
		}
		return $online;
	}
	
	function getPfieldOptions($name)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `id` FROM `pfields` WHERE `name` = "'.$name.'"');
		$row = $query->row();
		$query = $this->CI->db->query('SELECT `id`,`name` FROM `pfields_values` WHERE `fid` = "'.$row->id.'"');
		$options = $query->result();
		return $options;
	}
	
	function getPfieldValueOwner($val)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `name` FROM `pfields_values` WHERE `id` = "'.$val.'"');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$value = $row->name;
		}
		else
		{
			$value = "Ask Me";
		}
		return $value;
	}
	
	/* old function
	function getPfieldValue($val,$uid)
	{
		$langs = self::getAppLangs();
		$this->CI =& get_instance();
		
		$query = $this->CI->db->query('SELECT `name` FROM `pfields_values` WHERE `id` = "'.$val.'"');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$value = $row->name;
		}
		else
		{
			$value = "na";
		}
		return $value;
	}
	*/
	
	function getMetaDataProfile($uid)
	{
		$title = 'Thai Dating - Meet ';
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT * FROM `users` WHERE `id` = "'.$uid.'"');
		$mdata = $query->row();
		if($mdata->gender == "1")
		{
			$sex = "Lady";
			$sexb = "She";
		}
		else if($mdata->gender == "2")
		{
			$sex = "Man";
			$sexb = "He";
		}
		else
		{
			$sex = "Ladyboy";
			$sexb = "They";
		}
		$query = $this->CI->db->query('SELECT * FROM `profile_data` WHERE `uid` = "'.$uid.'"');
		$pdata = $query->row();
		$title .= $mdata->nickname . ' Today! '.$sexb.' would love to meet you today!';
		$tmp['title'] = $title;
		$keywords = $mdata->nickname.',thai dating,bangkok dating,dating,thailand,dating in thailand,singles in thailand,thai,girls,date,friends,bangkok,เพือน,แฟน,หาพื่อนฝรั่ง,หาแฟนฝรั่ง,หาแฟนต่างชาติ,หาเพื่อนต่างชาติ';
		$description = ($mdata->headline != "" ? $mdata->headline : ($pdata->bio != "" ? $pdata->bio : 'xxxxxx is Thai dating with Thai ladies from all over Thailand. Free messaging with 1000\'s of new Thai girls joining weekly at the biggest free Thai dating site!'));
		$tmp['keywords'] = $keywords;
		$tmp['description'] = $description;
		return $tmp;
	}
	
	function getOtherPhotos($uid,$imgid)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT * FROM `images` WHERE `uid` = "'.$uid.'" AND `id` != "'.$imgid.'"');
		if($query->num_rows() > 0)
		{
			$images = $query->result();
		}
		else
		{
			$images = "na";
		}
		return $images;
	}
	
	function getMemberDataPicId($id)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `uid` FROM `images` WHERE `id` = "'.$id.'"');
		$row = $query->row();
		$query = $this->CI->db->query('SELECT * FROM `users` WHERE `id` = "'.$row->uid.'"');
		$row = $query->row();
		return $row;
	}
	
	function getPicPath($id)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `path` FROM `images` WHERE `id` = "'.$id.'"');
		$row = $query->row();
		$path = $row->path;
		return $path;
	}
	
	function getHighestRatedPics($gender,$type)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT * FROM `top_rated` WHERE '.($gender != 0 ? '`gender` = "'.$gender.'" AND' : '').' `type` = "pic" ORDER BY `rating` DESC LIMIT '.($type == "mobile" ? 21 : 20).'');
		$pics = $query->result();
		return $pics;
	}
	
	function getNewestPics($gender,$type)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT images.* FROM images JOIN users ON images.uid = users.id 
		WHERE users.status != "3" AND users.type != "banned" AND users.type != "suspended" '.($gender != 0 ? 'AND users.gender = "'.$gender.'"' : '').' AND users.haspic = "1" AND images.status = 1 ORDER BY images.id DESC LIMIT '.($type == "mobile" ? 21 : 20).'');
		$pics = $query->result();
		return $pics;
	}
	
	function getMemberData($id)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT * FROM `users` WHERE `id` = "'.$id.'"');
		$row = $query->row();
		return $row;
	}
	
	function getHighestRatedProfiles($gender,$type)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `itemid` FROM `top_rated` WHERE '.($gender != 0 ? '`gender` = "'.$gender.'" AND' : '').'  `type` = "profile" GROUP BY `itemid`  ORDER BY `rating` DESC LIMIT '.($type == "mobile" ? 21 : 20).'');
		$members = $query->result();
		return $members;
	}
	
	function getNewestMembers($gender,$type)
	{
		$this->CI =& get_instance();
		//$query = $this->CI->db->query('SELECT * FROM `users` WHERE `gender` = "'.$gender.'" AND `haspic` = "1" ORDER BY `id` DESC LIMIT '.($type == "mobile" ? 21 : 20).'');
		if($gender == 0)
		{
			$query = $this->CI->db->query('SELECT u.*
			FROM users AS u
			INNER JOIN images AS i ON u.id = i.uid
			WHERE u.haspic = 1 AND 
			i.path NOT LIKE "%toon%" AND 
			i.ismain = 1 AND 
			i.status = 1 AND 
			(u.type = "normal" || u.type = "vip") ORDER BY u.id DESC LIMIT '.($type == "mobile" ? 21 : 20).'');
		}
		else
		{
			$query = $this->CI->db->query('SELECT u.*
			FROM users AS u
			INNER JOIN images AS i ON u.id = i.uid
			WHERE u.gender = '.$gender.' AND 
			u.haspic = 1 AND 
			i.path NOT LIKE "%toon%" AND 
			i.ismain = 1 AND 
			i.status = 1 AND 
			(u.type = "normal" || u.type = "vip") ORDER BY u.id DESC LIMIT '.($type == "mobile" ? 21 : 20).'');
		}
		$members = $query->result();
		return $members;
	}
	
	function getBlockList($blockedby)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT * FROM `blocks` WHERE `blockedby` = "'.$blockedby.'"');
		if($query->num_rows() > 0)
		{
			$blocks = $query->result();
		}
		else
		{
			$blocks = "na";
		}
		return $blocks;
	}
	
	function checkBlocked($vid,$oid)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `id` FROM `blocks` WHERE 
		(`blocked` = "'.$vid.'" && `blockedby` = "'.$oid.'") || (`blocked` = "'.$oid.'" && `blockedby` = "'.$vid.'")');
		if($query->num_rows() > 0)
		{
			// get viewers upr
			$query = $this->CI->db->query('SELECT `url` FROM `users` WHERE `id` = "'.$vid.'"');
			$row = $query->row();
			$tmp = $row->url;
		}
		else
		{
			$tmp = "n";
		}
		return $tmp;
	}
	
	function getSearchData($sid)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `values` FROM `searches` WHERE `id` = "'.$sid.'"');
		$row = $query->row();
		$str = $row->values;
		$pts = explode('||',$str);
		$values = array();
		foreach($pts as $p)
		{
			$data = explode('<%%>',$p);
			$values[$data[0]] = $data[1];
		}
		return $values;
	}
	
	function logSearch($vals,$uid)
	{
		$str = '';
		$cnt = count($vals);
		$x=1;
		foreach($vals as $key=>$value)
		{
			$str .= $key.'<%%>'.$value;
			if($x != $cnt)
			{
				$str .= '||';
			}
			$x++;
		}
		$this->CI =& get_instance();
		$query = $this->CI->db->query('INSERT INTO `searches` (`id`,`uid`,`values`) 
		VALUES 
		(NULL,"'.$uid.'","'.$str.'")');
		$sid = $this->CI->db->insert_id();
		return $sid;
	}
	
	function getPhotoComments($id)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT * FROM `comments` WHERE `img_id` = "'.$id.'" GROUP BY `comment` ORDER BY `added` DESC');
		if($query->num_rows() > 0)
		{
			$tmp = $query->result();
		}
		else
		{
			$tmp = "na";
		}
		return $tmp;
	}
	
	function getUserComments($id)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT * FROM `comments` WHERE `owner` = "'.$id.'" GROUP BY `comment` ORDER BY `added` DESC');
		if($query->num_rows() > 0)
		{
			$tmp = $query->result();
		}
		else
		{
			$tmp = "na";
		}
		return $tmp;
	}
	
	function updateLastActivity($id)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('UPDATE `users` SET `lastactivity` = "'.time().'" WHERE `id` = "'.$id.'"');
		return TRUE;
	}
	
	function getVerySmallPic($id)
	{
		$img = self::getProfilePicFromId($id);
		$path = '/image.php?src='.$img.'&h=30&w=30&zc=1';
		return $path;
	}
	
	function imgValid($path)
	{
		$tmp = "ok";
		$handle = curl_init($path);
		curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
		/* Get the HTML or whatever is linked in $url. */
		$response = curl_exec($handle);
		/* Check for 404 (file not found). */
		$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
		if($httpCode == 404) 
		{
    		/* Handle 404 here. */
			$tmp = "na";
		}	
		curl_close($handle);
		return $tmp;
	}
	
	function getCookVals($val)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `id`,`nickname`,`gender`,`seeking`,`status`,`isaff`,`verified` FROM `users` WHERE `id` = "'.$val.'"');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$newdata = array
			(
				'uid'     => $row->id,
				'authd'   => "yes",
				'gender'  => $row->gender,
				'seeking' => $row->seeking,
				'status'  => $row->status,
				'verify'  => $row->verified,
				'isaff'   => $row->isaff
			);
			return $newdata;
		}
		else
		{
			return FALSE;
		}
	}
	
	function checkProfileMatch($oid,$vid)
	{
		$matches = array();
		// get owner data
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `gender`,`dob`,`seeking`,`lookingfor` FROM `users` WHERE `id` = "'.$oid.'"');
		$ovals = $query->row();
		$query = $this->CI->db->query('SELECT * FROM `profile_data` WHERE `uid` = "'.$oid.'"');
		$ovalsb = $query->row();
		// get viewer values
		$query = $this->CI->db->query('SELECT `gender`,`dob`,`seeking`,`lookingfor` FROM `users` WHERE `id` = "'.$vid.'"');
		$vvals = $query->row();
		$query = $this->CI->db->query('SELECT * FROM `profile_data` WHERE `uid` = "'.$vid.'"');
		$vvalsb = $query->row();
		$matches = array();
		$flds = self::getProfileFields();
		foreach($ovals as $key=>$value)
		{
			$tmp['fld']    = $key;
			if($key != "dob" && $key != "id" && $key != "uid" && $key != "match_age" && $key != "match_occ" && $key != "bio" && $key != "msn" && $key != "yahoo" && $key != "facebook" && $key != "google_plus" && $key != "skype" && $key != "share_phone" && $key != "share_email" && $key != "blackberry_pin" && $key != "status")
			{
				$tmp['owner']  = ($value != "" ? @$flds[$key][$value] : 'No Answer');
				$tmp['viewer'] = ($vvals->$key != "" ? @$flds[$key][$vvals->$key] : 'Any');
			}
			else
			{
				$tmp['owner'] = ($value != "" ? $value : 'No Answer');
				$tmp['viewer'] = ($vvals->$key != "" ? $vvals->$key : 'Any');
			}
			$tmp['match'] = "n";
			if($key == "match_age")
			{
				$oages = explode('-',$value);
				$vages = explode('-',$vvals->$key);
				$oage = self::birthday($ovals->dob);
				$vage = self::birthday($vvals->dob);
				if($oage <= $vages[1] && $oage >= $vages[0])
				{
					$tmp['match'] = "y";
				}
				if($vvals->$key == "0-0")
				{
					$tmp['match'] = "y";
				}
				if($vvals->$key == "")
				{
					$tmp['match'] = "y";
				}
			}
			else if(($value == $vvals->$key) || ($vvals->$key == "" || $vvals->$key == "0" || $value == "0" || $value == ""))
			{
				$tmp['match'] = "y";
			}
			else
			{
				$tmp['match'] = "n";
			}
			$matches[$key] = $tmp;
		}
		foreach($ovalsb as $key=>$value)
		{
			$tmp['fld'] = $key;
			if($key != "dob" && $key != "id" && $key != "uid" && $key != "match_age" && $key != "match_occ" && $key != "bio" && $key != "msn" && $key != "yahoo" && $key != "facebook" && $key != "google_plus" && $key != "skype" && $key != "share_phone" && $key != "share_email" && $key != "blackberry_pin" && $key != "pstatus" && $key != "user_occ")
			{
				$tmp['owner'] = ($value != "" ? @$flds[$key][$value] : 'No Answer');
				$tmp['viewer'] = ($vvalsb->$key != "" ? @$flds[$key][$vvalsb->$key] : 'Any');
			}
			else
			{
				$tmp['owner'] = ($value != "" ? $value : 'No Answer');
				$tmp['viewer'] = ($vvalsb->$key != "" ? $vvalsb->$key : 'Any');
			}
			$tmp['match'] = "n";
			if(($value == $vvalsb->$key) || ($vvalsb->$key == "" || $vvalsb->$key == "0" || $value == "0" || $value == ""))
			{
				$tmp['match'] = "y";
			}
			
			$matches[$key] = $tmp;
			unset($tmp);
		}
		return $matches;
	}
	
	function getMemberCnt()
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `id` FROM `users`');
		$cnt = $query->num_rows();
		return $cnt;
	}
	
	function getProfileStates($con_id)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `sta_id`,`name` FROM `geo_states` WHERE `con_id` = "'.$con_id.'" ORDER BY `name`');
		$states = $query->result();
		return $states;
	}
	
	function getProfileCities($con_id,$sta_id)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `cty_id`,`name` FROM `geo_cities` WHERE `con_id` = "'.$con_id.'" AND `sta_id` = "'.$sta_id.'" ORDER BY `name`');
		$cities = $query->result();
		return $cities;
	}
	
	function getCountries()
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `con_id`,`name` FROM `geo_countries` ORDER BY `name`');
		$countries = $query->result();
		return $countries;
	}
	
	function getProfileUrl($id)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `url` FROM `users` WHERE `id` = "'.$id.'"');
		$row = $query->row();
		$url = $row->url;
		return $url;
	}
	
	function getMemberEmail($id)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `email` FROM `users` WHERE `id` = "'.$id.'"');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$tmp = $row->email;
		}
		else
		{
			$tmp = '';
		}
		return $tmp;
	}
	
	function getNickname($id)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `nickname` FROM `users` WHERE `id` = "'.$id.'"');
		$row = $query->row();
		$nick = $row->nickname;
		return $nick;
	}
	
	function getAlbumId($uid,$name)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `id` FROM `images_albums` WHERE `uid` = "'.$uid.'" AND `name` = "'.$name.'"');
		$row = $query->row();
		$id = $row->id;
		return $id;
	}
	
	function getProfilePic($id,$gender)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `path` FROM `images` WHERE `uid` = "'.$id.'" AND `ismain` = "1" AND `status` = "1"');
		if($query->num_rows > 0)
		{
			$row = $query->row();
			$img = $row->path;
			if($img == "")
			{
				$ugen = $gender;
				if($ugen == "18")
				{
					$num = rand(1,25);
					$img = '/images/toons/m/'.$num.'.jpg';
				}
				else
				{
					$num = rand(1,25);
					$img = '/images/toons/f/'.$num.'.jpg';
				}
			}
			else
			{
				// check for 404
				$imgck = self::imgValid($img);
				if($imgck == "na")
				{
					$ugen = $gender;
					if($ugen == "18")
					{
						$num = rand(1,25);
						$img = '/images/toons/m/'.$num.'.jpg';
					}
					else
					{
						$num = rand(1,25);
						$img = '/images/toons/f/'.$num.'.jpg';
					}
				}
			}
		}
		else
		{
			$ugen = $gender;
			if($ugen == "18")
			{
				$num = rand(1,25);
				$img = '/images/toons/m/'.$num.'.jpg';
			}
			else
			{
				$num = rand(1,25);
				$img = '/images/toons/f/'.$num.'.jpg';
			}
		}
		return $img;
	}
	
	function getAllUserData($id)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT * FROM `users` WHERE `id` = "'.$id.'"');
		$row = $query->row();
		foreach($row as $key=>$value)
		{
			$tmp[$key] = $value;
		}
		$query = $this->CI->db->query('SELECT * FROM `profile_data` WHERE `uid` = "'.$id.'"');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
		}
		else
		{
			// no profile data make row
			$query = $this->CI->db->query('INSERT INTO `profile_data` (`id`,`uid`,`pstatus`) VALUES (NULL,"'.$id.'","1")');
			$query = $this->CI->db->query('SELECT * FROM `profile_data` WHERE `uid` = "'.$id.'"');
			$row = $query->row();
		}
		foreach($row as $key=>$value)
		{
			//if($key == "id")
			//{
				//$tmp['pid'] = $value;
			//}
			//if($key != "uid")
			//{
				$tmp[$key] = $value;
			//}
		}
		return $tmp;
	}
	
	function birthday ($birthday)
	{
		list($year,$month,$day) = explode("-",$birthday);
		$year_diff  = date("Y") - $year;
		$month_diff = date("m") - $month;
		$day_diff   = date("d") - $day;
		if ($day_diff < 0 || $month_diff < 0)
		$year_diff--;
		return $year_diff;
	}
	/*
	function getGeoName($tbl,$id)
	{
		$this->CI =& get_instance();
		if($tbl == "country")
		{
			$query = $this->CI->db->query('SELECT `name` FROM `geo_countries` WHERE `con_id` = "'.$id.'"');
		}
		if($tbl == "state")
		{
			$query = $this->CI->db->query('SELECT `name` FROM `geo_states` WHERE `sta_id` = "'.$id.'"');
		}
		if($tbl == "city")
		{
			$query = $this->CI->db->query('SELECT `name` FROM `geo_cities` WHERE `cty_id` = "'.$id.'"');
		}
		$row = $query->row();
		$name = $row->name;
		return $name;
	}
	*/
	function getProfilesHome($viewerid,$limit)
	{
		$this->CI =& get_instance();
		if($viewerid == 0)
		{
			$query = $this->CI->db->query('SELECT `id`,`url`,`gender`,`nickname`,`country_now`,`dob` FROM `users` WHERE `gender` = "Lady" ORDER BY RAND() LIMIT 10');
			$profiles1 = $query->result();
			$query = $this->CI->db->query('SELECT `id`,`url`,`gender`,`nickname`,`country_now`,`dob` FROM `users` WHERE `gender` = "Man" ORDER BY RAND() LIMIT 10');
			$profiles2 = $query->result();
			$query = $this->CI->db->query('SELECT `id`,`url`,`gender`,`nickname`,`country_now`,`dob` FROM `users` WHERE `gender` = "Lady" ORDER BY RAND() LIMIT 10');
			$profiles3 = $query->result();
			$hpProfiles = array_merge($profiles1,$profiles2,$profiles3);
			return $hpProfiles;
		}
		else
		{
			$query = $this->CI->db->query('SELECT `seeking` FROM `users` WHERE `id` = "'.$viewerid.'"');
			$row = $query->row();
			$gender = $row->seeking;
			$query = $this->CI->db->query('SELECT `id`,`url`,`gender`,`nickname`,`country_now`,`dob` FROM `users` WHERE `gender` = "'.$gender.'" ORDER BY RAND() LIMIT '.$limit);
			if($query->num_rows() > 0)
			{
				$profiles = $query->result();
				return $profiles;
			}
		}
	}
	
	function getMembersJoin($gender,$limit)
	{
		$this->CI =& get_instance();
		//$query = $this->CI->db->query('SELECT `id`,`url`,`gender`,`nickname`,`country_now`,`dob` FROM `users` WHERE `gender` = "'.$gender.'" AND `haspic` = "1" ORDER BY RAND() LIMIT '.$limit.'');
		$query = $this->CI->db->query('SELECT u.id, u.url, u.gender, u.nickname, u.country_now, u.dob 
			FROM users AS u
			INNER JOIN images AS i ON u.id = i.uid
			WHERE u.haspic = 1 AND 
			i.path NOT LIKE "%toon%" AND 
			i.ismain = 1 AND 
			i.status = 1 AND 
			(u.type = "normal" || u.type = "vip") ORDER BY RAND() LIMIT '.$limit.' ');
		if($query->num_rows() > 0)
		{
			$profiles = $query->result();
		}
		else
		{
			$profiles = "na";
		}
		return $profiles;
	}
	
	function getMembers($gender,$limit)
	{
		$this->CI =& get_instance();
		if($gender == "r")
		{
			$query = $this->CI->db->query('SELECT `id`,`url`,`gender`,`nickname`,`country_now`,`dob` FROM `users` ORDER BY RAND() LIMIT '.$limit);
		}
		else
		{
			$query = $this->CI->db->query('SELECT `id`,`url`,`gender`,`nickname`,`country_now`,`dob` FROM `users` WHERE `gender` = "'.$gender.'" ORDER BY `id` DESC LIMIT '.$limit);
		}
		if($query->num_rows() > 0)
		{
			$profiles = $query->result();
		}
		else
		{
			$profiles = "na";
		}
		return $profiles;
	}
	
	function getMembersRnd($gender,$limit)
	{
		$this->CI =& get_instance();
		if($gender != "r")
		{
			$query = $this->CI->db->query('SELECT u.*
			FROM users AS u
			INNER JOIN images AS i ON u.id = i.uid
			WHERE u.haspic = 1 AND 
			u.gender = "'.$gender.'" AND
			i.path NOT LIKE "%toon%" AND 
			i.ismain = 1 AND 
			i.status = 1 AND 
			(u.type = "normal" || u.type = "vip")  ORDER BY RAND() LIMIT '.$limit.'');
		}
		else
		{
			$query = $this->CI->db->query('SELECT u.*
			FROM users AS u
			INNER JOIN images AS i ON u.id = i.uid
			WHERE u.haspic = 1 AND 
			i.path NOT LIKE "%toon%" AND 
			i.ismain = 1 AND 
			i.status = 1 AND 
			(u.type = "normal" || u.type = "vip")  ORDER BY RAND() LIMIT '.$limit.'');
		}
		if($query->num_rows() > 0)
		{
			$profiles = $query->result();
		}
		else
		{
			$profiles = "na";
		}
		return $profiles;
	}
	
	function getCurrentChips($id)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `cur_points` FROM `users` WHERE `id` = "'.$id.'"');
		$row = $query->row();
		$chips = $row->cur_points;
		return $chips;
	}
	
	function getProfilePicFromId($uid)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `path`,`thumb` FROM `images` WHERE `uid` = "'.$uid.'" AND `ismain` = "1" AND `status` = "1" AND `path` NOT LIKE "%toon%"');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$img = ($row->thumb != "" ? $row->thumb : $row->path);
			$img = str_replace('/images/uploads/','',$img);
		}
		else
		{
			/*
			// get users gender
			$query = $this->CI->db->query('SELECT `gender` FROM `users` WHERE `id` = "'.$uid.'"');
			$row = $query->row();
			$gender = $row->gender;
			if($gender == "18")
			{
				$num = rand(1,25);
				$img = '/images/toons/m/'.$num.'.jpg';
			}
			else
			{
				$num = rand(1,25);
				$img = '/images/toons/f/'.$num.'.jpg';
			}
			*/
			$img = '/images/user_icon.png';
		}
		return $img;
	}
	
	function getUsersPhotos($id)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT * FROM `images` WHERE `uid` = "'.$id.'" AND `status` != "3" ORDER BY `id`');
		if($query->num_rows() > 0)
		{
			$photos = $query->result();
		}
		else
		{
			$photos = "na";
		}
		return $photos;
	}
	
	function checkForNewMessage($id)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `id` FROM `messages` WHERE `to` = "'.$id.'" AND `status` = "1"');
		if($query->num_rows() > 0)
		{
			$tmp = $query->num_rows();
		}
		else
		{
			$tmp = "n";
		}
		return $tmp;
	}
	
	function userOnlineCheck($id)
	{
		$online = self::whosOnline();
		if(in_array($id,$online))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function whosOnline()
	{
		$data = array();
        $this->CI =& get_instance();
        $this->CI->db->select('*');
        $this->CI->db->from('ci_sessions');
        $this->CI->db->where('user_data !=', '');
        $this->CI->db->like('user_data', 'uid');
        
        $query = $this->CI->db->get();
        
        foreach($query -> result() as $row)
		{
           //$data[] = $row;
		   $pts = $this->CI->session->_unserialize($row->user_data);
		   $data[] = $pts['uid'];
        }
		return $data;
	}
	
	function onlineCheck($id)
	{
		$online_users = self::whosOnline();
		if(in_array($id,$online_users))
		{
			return true;
		}
		return false;
	}
	
	function getUserGender($id)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `gender` FROM `users` WHERE `id` = "'.$id.'"');
		$row = $query->row();
		$gender = $row->gender;
		return $gender;
	}
	
	function getMessageBody($id)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `msg` FROM `messages` WHERE `id` = "'.$id.'"');
		$row = $query->row();
		$msg = $row->msg; 
		return $msg;
	}
	
	function sendUserMail($to,$from,$action,$type,$vlink=0)
	{
		$this->CI =& get_instance();
		// get to info
		$query = $this->CI->db->query('SELECT `email`,`nickname` FROM `users` WHERE `id` = "'.$to.'"');
		$row = $query->row();
		$to_email = $row->email;
		$to_nick = $row->nickname;
		$to_gender = self::getUserGender($to);
		if($to_gender == 2)
		{
			$header_pic = 'http://www.xxxxxx.com/images/design/email_logo_male.jpg';
		}
		else
		{
			$header_pic = 'http://www.xxxxxx.com/images/design/email_logo_female.jpg';
		}
		if($from != "support")
		{
			// get from info
			$query = $this->CI->db->query('SELECT `nickname`,`gender` FROM `users` WHERE `id` = "'.$from.'"');
			$row = $query->row();
			$from_nick = $row->nickname;
			$from_gender = $row->gender;
		}
		else
		{
			$from_nick = "xxxxxx Support";
			$from_gender = "";
		}
		// get mail template
		$query = $this->CI->db->query('SELECT `body` FROM `mail_templates` WHERE `id` = "7"');
		$row = $query->row();
		$template = $row->body;
		$subject = "";
		$template = str_replace('{%%msgto%%}',$to_nick,$template);
		$template = str_replace('{%%msgfrom%%}',$from_nick,$template);
		$template = str_replace('{%%headerpic%%}',$header_pic,$template);
		$template = str_replace('{%%picfrom%%}',self::getProfilePic($from,$from_gender),$template);
		if($from != "support")
		{
			$template = str_replace('{%%urlfrom%%}',self::getProfileUrl($from),$template);
		}
		switch($action)
		{
			case "join":
				$subject = "Welcome to xxxxxx!";
				$msg = "Thank you for joining xxxxxx!\n\r";
				$msg .= "To verify your account please <a href=\"".$vlink."\">click here!</a>\n\r";
				$ret_link = '';
			break;
			case "pass":
				$subject = "Your password has been reset";
				$msg = "Here is your new password. Once you log in, please change your password.\n\r";
				$msg .= "Your new Password: " . $vlink;
				$ret_link = '';
			break;
			case "fav":
				$subject = "Somone has added you to their favoriates!";
				$msg = "Someone has added you to their favoriates!";
				$ret_link = '<a href="http://www.xxxxxx.com/favorites/"><img hspace="5" src="http://www.xxxxxx.com/images/design/email_reply.jpg" width="165" height="30" />';
			break;
			case "int":
				$subject = "Someone has shown interest in you!";
				$msg = $from_nick . ", Has shown interest in you.\n\r";
				$msg .= "<a href=\"http://www.xxxxxx.com/profile/".self::getProfileUrl($from)."\">View their profile and thank them by clicking here!</a>";
				$ret_link = '<a href="http://www.xxxxxx.com/interests/"><img hspace="5" src="http://www.xxxxxx.com/images/design/email_reply.jpg" width="165" height="30" />';
			break;
			case "msg";
				$subject = "Someone has sent you a message!";
				$msg = self::getMessageBody($vlink);
				$ret_link = '<a href="http://www.xxxxxx.com/messages/"><img hspace="5" src="http://www.xxxxxx.com/images/design/email_reply.jpg" width="165" height="30" />';
			break;
		}
		$template = str_replace('{%%message%%}',$msg,$template);
		$template = str_replace('{%%subject%%}',$subject,$template);
		$template = str_replace('{%%datesent%%}',date('Y-M-D h:i:s',time()),$template);
		$template = str_replace('{%%retlink%%}',$ret_link,$template);
		// send the mail
		$config['protocol'] = 'mail';
		$config['mailtype'] = 'html';
		$config['priority'] = '3';
		$this->CI->email->initialize($config);
		$this->CI->email->from('support@xxxxxx.com', 'Support');
		$this->CI->email->to($to_email);
		$this->CI->email->subject($subject);
		$this->CI->email->message($template);
		$this->CI->email->send();
		//echo $this->email->print_debugger();
	}
	
	function getGiftCount($id,$type)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `id` FROM `profile_actions` WHERE `to` = "'.$id.'" AND `type` = "'.$type.'"');
		if($query->num_rows() > 0)
		{
			$tmp = $query->num_rows();
		}
		else
		{
			$tmp = "0";
		}
		return $tmp;
	}
	
	function getUserPhotos($id,$limit)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT * FROM `images` WHERE `uid` = "'.$id.'" AND `status` = "1" ORDER BY `id` LIMIT '.$limit);
		if($query->num_rows() > 0)
		{
			$images = $query->result();
		}
		else
		{
			$images = "na";
		}
		return $images;
	}
	
	function checkInterests($viewer,$owner)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `id` FROM `interests` WHERE `to` = "'.$owner.'" AND `from` = "'.$viewer.'"');
		if($query->num_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function favCk($owner,$viewer)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `id` FROM `favs` WHERE `owner` = "'.$viewer.'" AND `fav` = "'.$owner.'"');
		if($query->num_rows() > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	function getCountryNow($id)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `country_now` FROM `users` WHERE `id` = "'.$id.'"');
		$row = $query->row();
		$country = $row->country_now;
		return $country;
	}
	
	function getUserDob($id)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `dob` FROM `users` WHERE `id` = "'.$id.'"');
		$row = $query->row();
		$dob = $row->dob;
		return $dob;
	}
	
	function checkIfCanRate($type,$item,$owner,$rater)
	{
		$this->CI =& get_instance();
		// 2012-06-03 18:03:18
		$currentTimestamp = time();
		$in24hourstime = strtotime("-24 hours", $currentTimestamp);
		$query = $this->CI->db->query('SELECT `id` FROM `ratings` WHERE `owner` = "'.$owner.'" AND `type` = "'.$type.'" AND `item` = "'.$item.'" AND `rated` > "'.$in24hourstime.'"');
		if($query->num_rows() > 0)
		{
			$query = $this->CI->db->query('SELECT `rating` FROM `ratings` WHERE `owner` = "'.$owner.'" AND `item` = "'.$item.'" AND `type` = "'.$type.'"');
			if($query->num_rows() > 0)
			{
				$ratings = $query->result();
				$total = 0;
				$rtotal = 0;
				foreach($ratings as $r)
				{
					$rtotal = $rtotal + $r->rating;
					$total++;
				}
				$avg_rating = round(($rtotal / $total),1);
				$tmp = $avg_rating . " Rating / " . $total . " votes!";
			}
			else
			{
				$tmp = "No Ratings Yet";
			}
		}
		else
		{
			$tmp = "ok";
		}
		return $tmp;
	}
	
	function getUserBlock($id)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `id`,`nickname`,`url`,`gender`,`seeking`,`dob`,`country_now`,`state_now`,`city_now` FROM `users` WHERE `id` = "'.$id.'"');
		$memdata = $query->row();
		return $memdata;	
	}
	
	function getRatingTotal($type,$item,$owner)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `rating` FROM `ratings` WHERE `owner` = "'.$owner.'" AND `item` = "'.$item.'" AND `type` = "'.$type.'"');
		if($query->num_rows() > 0)
		{
			$ratings = $query->result();
			$total = 0;
			$rtotal = 0;
			foreach($ratings as $r)
			{
				$rtotal = $rtotal + $r->rating;
				$total++;
			}
			$avg_rating = round(($rtotal / $total),1);
			$tmp = $avg_rating;
		}
		else
		{
			$tmp = 0;
		}
		return $tmp;
	}
	
	function getProfileFields()
	{
		$values = array();
		$gender[0] = "Any";
		$gender[1] = "Female";
		$gender[2] = "Male";
		$gender[8] = "Ladyboy";
		$values['gender'] = $gender;
		$values['match_gender'] = $gender;
		$values['seeking'] = $gender;
		
		$lookingfor[0] = "Any";
		$lookingfor[1] = "Friendship";
		$lookingfor[2] = "PenPal";
		$lookingfor[4] = "Marriage";
		$lookingfor[6] = "Interests Share";
		$lookingfor[16] = "One Night Stand";
		$lookingfor[32] = "Hang Out";
		$lookingfor[64] = "Love";
		$values['lookingfor'] = $lookingfor;
		$values['match_relationship'] = $lookingfor;
		
		$religion[0] = "Any";
		$religion[1] = "Adventist";
		$religion[2] = "Agnostic";
		$religion[4] = "Atheist";
		$religion[8] = "Baptist";
		$religion[16] = "Buddhist";
		$religion[32] = "Caodaism";
		$religion[64] = "Catholic";
		$religion[128] = "Christian";
		$religion[256] = "Hindu";
		$religion[512] = "Jain";
		$religion[1024] = "Jewish";
		$religion[2048] = "Methodist";
		$religion[4096] = "Mormon";
		$religion[8192] = "Moslem";
		$religion[16384] = "Orthodox";
		$religion[32768] = "Pentecostal";
		$religion[65536] = "Protestant";
		$religion[131072] = "Quaker";
		$religion[262144] = "Scientology";
		$religion[524288] = "Shinto";
		$religion[1048576] = "SintoÃ­smo";
		$religion[2097152] = "Spiritual";
		$religion[4194304] = "Taoism";
		$religion[8388608] = "Wiccan";
		$religion[16777216] = "Pagan";
		$religion[33554432] = "Other";
		$values['user_religion'] = $religion;
		$values['match_religion'] = $religion;
		
		$children[0] = "Any";
		$children[1] = "No";
		$children[2] = "1";
		$children[4] = "2";
		$children[8] = "3";
		$children[16] = "4";
		$children[32] = "5 or more";
		$values['match_children'] = $children;
		$values['user_children'] = $children;
		
		$language[0] = "Any";
		$language[1] = "English";
		$language[128] = "Thai";
		$language[4] = "Chinese";
		$language[8] = "Dutch";
		$language[16] = "Finnish";
		$language[32] = "French";
		$language[64] = "German";
		$language[4096] = "Japanese";
		$language[65536] = "Russian";
		$language[8388608] = "Other";
		$values['language'] = $language;
		
		$education[0] = "Any";
		$education[1] = "High School";
		$education[2] = "Some College";
		$education[4] = "Currently a Student";
		$education[8] = "College Degree";
		$education[16] = "Bachelors Degree";
		$education[32] = "Masters Degree";
		$education[64] = "PhD/Post Doctorate";
		$education[128] = "School of Life";
		$values['user_education'] = $education;
		$values['match_education'] = $education;
		
		$income[0] = "Any";
		$income[1] = "$10,000 or less";
		$income[2] = "$10,000-$30,000";
		$income[4] = "$30,000-$50,000";
		$income[8] = "$50,000-$70,000";
		$income[16] = "$70,000-$100,000";
		$income[32] = "$100,000-$150,000";
		$income[64] = "$150,000-$500,000";
		$income[128] = "Ok, I'm VERY wealthy";
		$values['user_income'] = $income;
		$values['match_income'] = $income;
		
		$smoke[0] = "Any";  
		$smoke[1] = "Never";
		$smoke[2] = "Rarely";
		$smoke[4] = "Socially";
		$smoke[8] = "Often";
		$smoke[16] = "Very Often";
		$smoke[32] = "Trying to Quit";
		$smoke[64] = "Quit";
		$values['user_smoke'] = $smoke;
		$values['match_smoke'] = $smoke;  
		
		$drink[0] = "Any";  
		$drink[1] = "Never";
		$drink[2] = "Rarely";
		$drink[4] = "Socially";
		$drink[8] = "Often";
		$drink[16] = "Very Often";
		$drink[32] = "Trying to Quit";
		$drink[64] = "Quit";
		$values['user_drink'] = $drink;
		$values['match_drink'] = $drink;
		
		$common_interests[0] = "Any";
		$common_interests[1] = "Business Networking";
		$common_interests[2] = "Books/Discussion";
		$common_interests[4] = "Camping";
		$common_interests[8] = "Cooking";
		$common_interests[16] = "Dining Out";
		$common_interests[32] = "Fishing/Hunting";
		$common_interests[64] = "Gardening/Landscaping";
		$common_interests[2048] = "Nightclubs/Dancing";
		$common_interests[4096] = "Performing Arts";
		$common_interests[16384] = "Playing Sports";
		$common_interests[32768] = "Politics";
		$values['common_interests'] = $common_interests;
		
		$ethnicity[0] = "Any";
		$ethnicity[1] = "African";
		$ethnicity[2] = "African American";
		$ethnicity[4] = "Asian";
		$ethnicity[8] = "White / Caucasian";
		$ethnicity[16] = "East Indian";
		$ethnicity[32] = "Hispanic";
		$ethnicity[64] = "Indian";
		$ethnicity[128] = "Latino";
		$ethnicity[256] = "Mediterranean";
		$ethnicity[512] = "Middle Eastern";
		$ethnicity[1024] = "Mixed";
		$values['user_ethn'] = $ethnicity;
		$values['match_ethn'] = $ethnicity;
		
		$height[0] = "Any";
		$height[32] = "4ft 7in (140cm) or below";
		$height[64] = "4ft 8in - 4ft 11in (141-150cm)";
		$height[128] = "5ft 0in - 5ft 3in (151-160cm)";
		$height[256] = "5ft 4in - 5ft 7in (161-170cm)";
		$height[512] = "5ft 8in - 5ft 11in (171-180cm)";
		$height[1024] = "6ft 0in - 6ft 3in (181-190cm)";
		$height[2048] = "6ft 4in (191cm) or above";
		$values['user_height'] = $height;
		$values['match_height'] = $height;
		
		$weight[0] = "Any";
		$weight[1] = "Slim";
		$weight[2] = "About Average";
		$weight[4] = "Athletic";
		$weight[8] = "Pumped Up";
		$weight[16] = "Have Some Extra Pounds";
		$weight[32] = "Big and Lovely";
		$values['user_weight'] = $weight;
		$values['match_weight'] = $weight;
		
		$eye_color[0] = "Any";
		$eye_color[1] = "Black";
		$eye_color[2] = "Blue";
		$eye_color[4] = "Hazel";
		$eye_color[8] = "Green";
		$eye_color[16] = "Grey";
		$eye_color[32] = "Brown";
		$values['user_eye'] = $eye_color;
		$values['match_eye'] = $eye_color;
		
		$hair_color[0] = "Any";
		$hair_color[1] = "Auburn";
		$hair_color[2] = "Bald";
		$hair_color[4] = "Black";
		$hair_color[8] = "Blonde";
		$hair_color[16] = "Brown";
		$hair_color[32] = "Light Brown";
		$hair_color[64] = "Red";
		$hair_color[128] = "White/Grey";
		$hair_color[256] = "Other";
		$values['user_hair'] = $hair_color;
		$values['match_hair'] = $hair_color;
		
		return $values;
	}
	
	/*************************************** AFFILIATE CODE **********************************************/
	
}

?>