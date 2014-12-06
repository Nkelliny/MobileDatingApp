<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller 
{
	public function index()
	{
		echo phpinfo();
		//$this->load->view('welcome_message');
	}
	
	function svdroid()
	{
		$em = $this->security->xss_clean($_POST['em']);
		$nm = $this->security->xss_clean($_POST['nm']);
		$query = $this->db->query('INSERT INTO `droid_req` (`id`,`nm`,`em`) VALUES (NULL,"'.$nm.'","'.$em.'")');
		echo 'ok';
		exit;
	}
	
	function getchatpic()
	{
		$str = $_POST['jid'];
		$nstr = str_replace('tj-','',$str);
		$uid = str_replace('@xmpp.xxxxxx.com','',$nstr);
		$img = $this->my_usersmanager->getProfilePicFromId($uid);
		$mystring = $img;
		$findme   = 'facebook.com';
		$pos = strpos($mystring, $findme);
		if ($pos === false) 
		{
            $ppic = '<img src="/image.php?src='.$img.'&w=30&h=30&zc=1" width="30" height="30" alt="" title="" border="0" />';
		}
		else
		{
			$ppic = '<img src="'.$img.'" width="30" height="30" alt="" title="" border="0" />';
		}
		echo $ppic;
		exit;
	}
	
	function getchatinfo()
	{
		$uid = $_POST['c_val'];
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `id` = "'.$uid.'"');
		$row = $query->row();
		$res = 'tj-'.$row->id.'@server.fwends.co.uk::X;9T*WeG8yHH';
		//$res = 'xxxxxx@server.fwends.co.uk::wyattme13';
		echo $res;
		exit;
	}
	
	function getPicRating($itemid)
	{
		$query = $this->db->query('SELECT * FROM `ratings` WHERE `item` = "'.$itemid.'" AND `type` = "image"');
		if($query->num_rows() > 0)
		{
			$values = $query->result();
			$cnt = $query->num_rows();
			$ttl = 0;
			foreach($values as $v)
			{
				$ttl = $ttl + $v->rating;
			}
			$rating = ($ttl / $cnt);
		}
		else
		{
			$rating = "0";
		}
		return $rating;
	}
	
	function ratingcron()
	{
		$sex = $this->uri->segment(3);
		if($sex == "1")
		{
			$sex = "17";
		}
		if($sex == "2")
		{
			$sex = "18";
		}
		if($sex == "8")
		{
			$sex = "19";
		}
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `gender` = "'.$sex.'" AND (`type` = "normal" || `type` = "vip")');
		$female_ids = $query->result();
		// get highest rated female
		$rated_females = array();
		foreach($female_ids as $f)
		{
			$query = $this->db->query('SELECT * FROM `ratings` WHERE `owner` = "'.$f->id.'" AND `type` = "profile"');
			if($query->num_rows() > 0)
			{
				$values = $query->result();
				$cnt = $query->num_rows();
				$ttl = 0;
				foreach($values as $v)
				{
					$ttl = $v->rating + $ttl;
				}
				$query = $this->db->query('DELETE FROM `top_rated` WHERE `gender` = "'.$sex.'" AND `type` = "profile" AND `itemid` = "'.$f->id.'"');
				$query = $this->db->query('INSERT INTO `top_rated` (`id`,`itemid`,`type`,`gender`,`rating`) 
			VALUES (NULL,"'.$f->id.'","profile","'.$sex.'","'.($ttl / $cnt).'")');
			//echo $f->id . 'rated<br />';
			}
		}
		$female_pics = array();
		foreach($female_ids as $f)
		{
			$query = $this->db->query('SELECT * FROM `ratings` WHERE `owner` = "'.$f->id.'" AND `type` = "image" GROUP BY `item`');
			if($query->num_rows() > 0)
			{
				$values = $query->result();
				foreach($values as $v)
				{
					$tmp['uid']    = $v->owner;
					$tmp['itemid'] = $v->item;
					$tmp['rating'] = self::getPicRating($v->item);
					$female_pics[] = $tmp;
				}
			}
			// clear the table
			$query = $this->db->query('DELETE FROM `top_rated` WHERE `gender` = "'.$sex.'" AND `type` = "pic"');
			foreach($female_pics as $p)
			{
				$query = $this->db->query('INSERT INTO `top_rated` (`id`,`itemid`,`type`,`gender`,`rating`) 
				VALUES (NULL,"'.$p['itemid'].'","pic","'.$sex.'","'.$p['rating'].'")');	
			}
		}
		mail('xxxxxx@gmail.com','xxxxxx Rating Cron','Rating Cron as run!');
	}
	
	function matchcron()
	{
		// set fucking cookie because this server sucks ass
		if($this->input->cookie('matchcron') && $this->input->cookie('matchcron') != 0)
		{
			exit;
		}
		else
		{
			$cookie = array(
				'name'   => 'matchcron',
				'value'  => 'on',
				'expire' => '2592000', // one month
				'domain' => 'xxxxxx.com',
				'path'   => '/'
			);
			$this->input->set_cookie($cookie); 
			$lastWeek = time() - (7 * 24 * 60 * 60);
			mail('xxxxxx@gmail.com','match cron started','match cron started');
			set_time_limit(0);
			$query = $this->db->query('SELECT `id` FROM `users` WHERE (`type` = "normal" || `type` = "vip") AND `lastactivity` > "'.$lastWeek.'"');
			$users = $query->result();
			$total = 12;
			foreach($users as $u)
			{
				$match_val = 0;
				$query = $this->db->query('DELETE FROM `matches` WHERE `user` = "'.$u->id.'"');
				$owner = $this->my_usersmanager->getAllUserData($u->id);
				$query = $this->db->query('SELECT `id` FROM `users` WHERE `gender` = "'.$owner['seeking'].'" AND (`type`="normal" || `type`="vip")'); 
				if($query->num_rows() > 0)
				{
					$match_users = $query->result();
					foreach($match_users as $m)
					{
						if($u->id != $m->id)
						{
							$match = $this->my_usersmanager->getAllUserData($m->id);
							$owner_age = $this->my_usersmanager->birthday($owner['dob']);
							$match_age = $this->my_usersmanager->birthday($match['dob']);
							$oage = explode('-',$owner['match_age']);
							$mage = explode('-',$match['match_age']);
							if($owner['seeking'] == $match['gender'] && $owner['seeking'] == $match['gender'])
							{
								$match_val++;
							}
							if($owner['lookingfor'] == $match['match_relationship'])
							{
								$match_val++;
							}
							if(($oage[0] >= $match_age && $oage[1] <= $match_age) && ($mage[0] >= $owner_age && $mage[1] <= $owner_age))
							{
								$match_val++;
							}
							if($owner['match_height'] == $match['user_height'] && $owner['user_height'] == $match['match_height'])
							{
								$match_val++;
							}
							if($owner['match_weight'] == $match['user_weight'] && $owner['user_weight'] == $match['match_weight'])
							{
								$match_val++;
							}
							if($owner['match_eductioan'] == $match['user_education'] && $owner['user_education'] == $match['match_education'])
							{
								$match_val++;
							}
							if($owner['match_ethn'] == $match['user_ethn'] && $owner['user_ethn'] == $match['match_ethn'])
							{
								$match_val++;
							}
							if($owner['match_childern'] == $match['user_children'] && $owner['user_children'] == $match['match_children'])
							{
								$match_val++;
							}
							if($owner['match_hair'] == $match['user_hair'] && $owner['user_hair'] == $match['match_hair'])
							{
								$match_val++;
							}
							if($owner['match_eye'] == $match['user_eye'] && $owner['user_eye'] == $match['match_eye'])
							{
								$match_val++;
							}
							if($owner['match_smoke'] == $match['user_smoke'] && $owner['user_smoke'] == $match['match_smoke'])
							{
								$match_val++;
							}
							if($owner['match_drink'] == $match['user_drink'] && $owner['user_drink'] == $match['match_drink'])
							{
								$match_val++;
							} 
							$match_percent = round(($match_val / $total)*100);
							if($match_percent >= 25)
							{
								$query = $this->db->query('INSERT INTO `matches` (`id`,`user`,`match`,`percent`) VALUES (NULL,"'.$u->id.'","'.$match['uid'].'","'.$match_percent.'")');
							}
							unset($match,$owner_age,$match_age,$oage,$mage,$match_val);
							$match_val = 0;
						}
					}
					unset($owner);
				}
			}
			$cookie = array(
				'name'   => 'matchcron',
				'value'  => '0',
				'expire' => '0',
				'domain' => 'xxxxxx.com',
				'path'   => '/'
			);
			$this->input->set_cookie($cookie); 
			mail('xxxxxx@gmail.com','match cron ended','match cron ended');
		}
	}
	
	function removeBlock()
	{
		$blocked = $_POST['blocked'];
		$blockedby = $_POST['blockedby'];
		$query = $this->db->query('DELETE FROM `blocks` WHERE `blocked` = "'.$blocked.'" AND `blockedby` = "'.$blockedby.'"');
		$query = $this->db->query('DELETE FROM `cometchat_block` WHERE `fromid` = "'.$blockedby.'" AND `toid` = "'.$blocked.'"');
		$tmp = "ok";
		echo $tmp;
		exit;
	}
	
	function doBlock()
	{
		$blocked = $_POST['blocked'];
		$blockedby = $_POST['blockedby'];
		$query = $this->db->query('INSERT INTO `blocks` (`id`,`blocked`,`blockedby`) VALUES (NULL,"'.$blocked.'","'.$blockedby.'")');
		$query = $this->db->query('INSERT INTO `cometchat_block` (`id`,`fromid`,`toid`) VALUES (NULL,"'.$blockedby.'","'.$blocked.'")');
		$tmp = "ok";
		echo $tmp;
		exit;
	}
	
	function recoverPass()
	{
		$email = $this->security->xss_clean($_POST['email']);
		$query = $this->db->query('SELECT * FROM `users` WHERE `email` = "'.$email.'"');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			// build new password
			$uid = $row->id;
			$npass = $this->my_stringmanager->genPass();
			$pass = sha1('4db689efccf22'.$npass);
			// add pass to db
			$query = $this->db->query('UPDATE `users` SET `pass` = "'.$pass.'" WHERE `id` = "'.$uid.'"');
			// email pass to user.
			mail($email,'New Password','Here is your new password: '. $npass);
			$smail = $this->my_usersmanager->sendUserMail($uid,'support','pass','pass',$npass);
			$tmp = '<div style="float:left; width:100%; text-align:center;">Your new password has been emailed to you!</div>';
		}
		else
		{
			$tmp = '<form id="forgotFrm" name="forgotFrm" action="/login/recover" method="post" enctype="application/x-www-form-urlencoded">
			<table width="500" border="0" cellspacing="2" cellpadding="2" align="center">
			<tr>
			<td colspan="2"><div align="center">The email you entered is invalid or not in our system. Please re-enter your email address.</div></td>
			</tr>
			<tr>
			<td width="217"><div align="right">Please Enter Your Email Address:</div></td>
			<td width="269"><input name="remail" type="text" id="remail" size="20" /></td>
			</tr>
			<tr>
			<td colspan="2"><div align="center">
			<input type="button" name="forgotPass" id="forgotPass" onclick="recoverPassword();" value="Recover Password" />
			</div></td>
			</tr>
			</table>
			</form>';
		}
		echo $tmp;
		exit;
	}
	
	function updateLastActivity()
	{
		$uck = $this->my_usersmanager->updateLastActivity($_POST['uid']);
		echo $uck;
		exit;
	}
	
	function getmatchstates()
	{
		$con_id = $_POST['con_id'];
		$query = $this->db->query('SELECT `sta_id`,`name` FROM `geo_states` WHERE `con_id` = "'.$con_id.'" ORDER BY `name`');
		$states = $query->result();
		$data['states'] = $states;
		$data['match_state'] = $_POST['mstate'];
		$data['match_city'] = $_POST['mcity'];
		$this->load->view(($this->session->userdata('device') == "mobile" ? '/mobile/' : '').'ajax_match_states',$data);
	}
	
	function getCitiesAff()
	{
		$con = $_POST['country'];
		$sta = $_POST['state'];
		$cities = $this->my_geomanager->getCities($con,$sta);
		$html = '<select id="city" name="city" style="width:150px;">';
		$html .= '<option value="na">Select</option>';
		foreach($cities as $c)
		{
			$html .= '<option value="'.$c->Feature_int_id.'">'.$c->Feature_str_name.'</option>';
		}
		$html .= '</select>';
		echo $html;
		exit;
	}
	
	function getStatesAff()
	{
		$con = $_POST['country'];
		$states = $this->my_geomanager->getStates($con);
		$html = '<select name="state" id="state" onchange="getCitiesAff(this.value);" style="width:150px;">';
		$html .= '<option value="na">Select</option>';
		foreach($states as $s)
		{
			$html .= '<option value="'.$s->Admin1_str_code.'">'.$s->Admin1_str_name.'</option>';
		}
		$html .= '</select>';
		echo $html;
		exit;
	}
	
	function getCitiesDT()
	{
		$con = $_POST['country'];
		$sta = $_POST['state'];
		$cities = $this->my_geomanager->getCities($con,$sta);
		$html = '<select id="city" name="city" style="width:150px;">';
		$html .= '<option value="na">City</option>';
		foreach($cities as $c)
		{
			$html .= '<option value="'.$c->Feature_int_id.'">'.$c->Feature_str_name.'</option>';
		}
		$html .= '</select>';
		echo $html;
		exit;
	}
	
	function getStatesDT()
	{
		$states = $this->my_geomanager->getStates($_POST['country']);
		$html = '<select name="state" id="state" style="width:150px;" onchange="getCitiesDT(this.value);">';
        $html .= '<option value="na">State / Province</option>';
		foreach($states as $s)
		{
			$html .= '<option value="'.$s->Admin1_str_code.'">'.$s->Admin1_str_name.'</option>';
		}
		$html .= '</select>';
		echo $html;
		exit;
	}
	
	function getStatesSearch()
	{
		$states = $this->my_geomanager->getStates($_POST['country']);
		$html = '<select name="state" id="state" style="width:100px;" onchange="getCitiesSearch(this.value)">';
        foreach($states as $s)
		{
			$html .= '<option value="'.$s->Admin1_str_code.'">'.$s->Admin1_str_name.'</option>';
		}
		$html .= '</select>';
		echo $html;
		exit;
	}
	
	function getStates()
	{
		$states = $this->my_geomanager->getStates($_POST['country']);
		$html = '<select name="city_now" id="city_now" style="width:240px;">';
        foreach($states as $s)
		{
			$html .= '<option value="'.$s->Admin1_str_code.'">'.$s->Admin1_str_name.'</option>';
		}
		$html .= '</select>';
		echo $html;
		exit;
	}
	
	function getCitiesSearch()
	{
		$cities = $this->my_geomanager->getCities($_POST['con_id'],$_POST['sta_id']);
		$html = '<select name="city" id="city" style="width:100px;">';
        foreach($cities as $s)
		{
			$html .= '<option value="'.$s->Feature_int_id.'">'.$s->Feature_str_name.'</option>';
		}
		$html .= '</select>';
		echo $html;
		exit;
	}
	
	
	function getmatchcities()
	{
		$con_id = $_POST['con_id'];
		$sta_id = $_POST['sta_id'];
		$query = $this->db->query('SELECT `cty_id`,`name` FROM `geo_cities` WHERE `con_id` = "'.$con_id.'" AND `sta_id` = "'.$sta_id.'" ORDER BY `name`');
		$cities = $query->result();
		$data['cities'] = $cities;
		$data['match_city'] = $_POST['mcity'];
		$this->load->view(($this->session->userdata('device') == "mobile" ? '/mobile/' : '').'ajax_match_cities',$data);
	}
	
	function getcities()
	{
		$con_id = $_POST['con_id'];
		$sta_id = $_POST['sta_id'];
		$query = $this->db->query('SELECT `cty_id`,`name` FROM `geo_cities` WHERE `con_id` = "'.$con_id.'" AND `sta_id` = "'.$sta_id.'" ORDER BY `name`');
		$cities = $query->result();
		$data['cities'] = $cities;
		$data['city_div'] = $_POST['cty'];
		$this->load->view(($this->session->userdata('device') == "mobile" ? '/mobile/' : '').'ajax_cities',$data);
	}
	
	function checkHeadline()
	{
		$str = $_POST['str'];
		$str = $this->security->xss_clean($str);
		$query = $this->db->query('SELECT * FROM `bad_words` WHERE `id` = "1"');
		$row = $query->row();
		$badwords = explode(',',$row->bword_list);
		$query = $this->db->query('SELECT * FROM `bad_words` WHERE `id` = "2"');
		$row = $query->row();
		$badnames = explode(',',$row->bword_list);
		$msg = "ok";
		foreach($badwords as $b)
		{
			$mystring = $str;
			$findme   = $b;
			$pos = strpos($mystring, $findme);
			if ($pos !== false) 
			{
				$msg = "err";
				break;
			}
		}
		/*
		foreach($badnames as $b)
		{
			$mystring = $str;
			$findme   = $b;
			$pos = strpos($mystring, $findme);
			if ($pos !== false) 
			{
				$msg = "err";
				break;
			}
		}
		*/
		echo $msg;
		exit;
	}
	
	function checknickname()
	{
		$size = strlen($_POST['nick']);
		$msg = "ok";
		if($size > 3 && $size < 100)
		{
			$nick = $this->security->xss_clean($_POST['nick']);
			$query = $this->db->query('SELECT `id` FROM `users` WHERE `nickname` = "'.$nick.'"');
			if($query->num_rows() > 0)
			{
				$msg = "Sorry, that name is already being used, please choose a different one.";
			}
			else
			{
				// check against unwanted names and badwords
				$query = $this->db->query('SELECT * FROM `bad_words` WHERE `id` = "1"');
				$row = $query->row();
				$badwords = explode(',',$row->bword_list);
				$query = $this->db->query('SELECT * FROM `bad_words` WHERE `id` = "2"');
				$row = $query->row();
				$badnames = explode(',',$row->bword_list);
				foreach($badwords as $b)
				{
					$mystring = $nick;
					$findme   = $b;
					$pos = strpos($mystring, $findme);
					if ($pos !== false) 
					{
						$msg = "Your nickname is not allowed, please try again.";
						
						break;
					}
				}
				foreach($badnames as $b)
				{
					$mystring = $nick;
					$findme   = $b;
					$pos = strpos($mystring, $findme);
					if ($pos !== false) 
					{
						$msg = "Your nickname is not allowed, please try again.";
						
						break;
					}
				}
			}
		}
		else
		{
			$msg = 'Please choose a name that is 3 - 15 letters long.';
		}
		echo $msg;
		exit;
	}
	
	function addimgdb()
	{
		$id    = $_POST['id'];
		$path  = $_POST['img'];
		$path  = str_replace('http://www.xxxxxx.com','',$path);
		$query = $this->db->query('INSERT INTO `images` 
		(`id`,`uid`,`path`,`status`) 
		VALUES 
		(NULL,"'.$id.'","'.$path.'","2")');
		$query = $this->db->query('UPDATE `users` SET `cur_points` = cur_points + 10 WHERE `id` = "'.$id.'"');
		echo $this->db->insert_id();
		exit;
	}
	
	function showInterest()
	{
		$query = $this->db->query('SELECT `id` FROM `interests` WHERE `to` = "'.$_POST['tid'].'" AND `from` = "'.$_POST['fid'].'"');
		if($query->num_rows() > 0)
		{
			$msg = "n";
		}
		else
		{
			$query = $this->db->query('INSERT INTO `interests` (`id`,`to`,`from`) VALUES (NULL,"'.$_POST['tid'].'","'.$_POST['fid'].'")');
			$query = $this->db->query('UPDATE `users` SET `cur_points` = cur_points + 1 , `total_points` = total_points + 1 WHERE `id` = "'.$_POST['fid'].'"');
			$query = $this->db->query('SELECT `cur_points` FROM `users` WHERE `id` = "'.$_POST['fid'].'"');
			$row = $query->row();
			$ht_cnt = $row->cur_points;
			$msg = "ok,".$ht_cnt;
			// do email opt
			$this->my_usersmanager->sendUserMail($_POST['tid'],$_POST['fid'],'int','na');
		}
		echo $msg;
		exit;
	}
	
	function sendgift()
	{
		$cur_hearts = $this->my_usersmanager->getCurrentChips($_POST['f']);
		if($cur_hearts < 2)
		{
			$msg = "nmmOops, you do not have enough hearts to do this!\nYou can upload a photo, show interest, add a few favoriates to get more hearts!";
		}
		else
		{
			$query = $this->db->query('INSERT INTO `profile_actions` (`id`,`type`,`to`,`from`,`gift`) VALUES (NULL,"'.$_POST['ty'].'","'.$_POST['t'].'","'.$_POST['f'].'","0")');
			$query = $this->db->query('UPDATE `users` SET `cur_points` = cur_points - 2 , `used_points` = used_points + 1 WHERE `id` = "'.$_POST['f'].'"');
			$ht_cnt = $this->my_usersmanager->getCurrentChips($_POST['f']);
			$msg = "ymmYour gift has been sent!mm".$ht_cnt;
			$this->my_usersmanager->sendUserMail($_POST['t'],$_POST['f'],'gift',$_POST['ty']);
		}
		echo $msg;
		exit;
	}
	
	function doPhotoComment()
	{
		$t = $_POST['to'];
		$f = $_POST['from'];
		$pid = $_POST['pid'];
		$msg = $this->security->xss_clean($_POST['msg']);
		$msg = strip_tags($msg);
		$new_text = $this->my_stringmanager->wordCleaner($msg);
		$query = $this->db->query('INSERT INTO `comments` 
		(`id`,`owner`,`sender`,`comment`,`status`,`type`,`img_id`) 
		VALUES 
		(NULL,"'.$t.'","'.$f.'","'.mysql_real_escape_string($msg).'","2","Photo","'.$pid.'")');
		mail('xxxxxx@gmail.com,support@xxxxxx.com','New Photo Comment Added - xxxxxx','A new comment has been added and needs approved. '.$msg);
		$comments = $this->my_usersmanager->getPhotoComments($pid);
		$cblock = '<div style="float:left; width:100%;">';
		foreach($comments as $c)
		{
			$cblock .= '<div style="float:left; width:100%;">';
			$cblock .= '<div style="float:left; width:120px; height:120px;"><a href="/profile/'.$this->my_usersmanager->getProfileUrl($c->sender).'"><img src="/image.php?src='.$this->my_usersmanager->getProfilePicFromId($c->sender).'&w=100&h=100&zc=1" width="100" height="100" border="0" /></a></div>';
			$cblock .= '<div style="float:left; width:300px;"><a href="/profile/'.$this->my_usersmanager->getProfileUrl($c->sender).'">'.$this->my_usersmanager->getNickname($c->sender).'</a> said on '.$c->added.'<br /><br />';
			if($c->sender == $f && $c->status == 2)
			{
				$cblock .= '<span style="color:#CCC;">';
				$cblock .= '(Pending Review)<br /><br />';
				$cblock .= '</span>';
				$cblock .= $c->comment;
			}
			else if($c->status == 1)
			{
				$cblock .= $this->my_stringmanager->wordCleaner($c->comment);
			}
			$cblock .= '</div>';
			$cblock .= '</div>';
			$cblock .= '<div style="float:left; width:100%;"><hr size="1" width="100%" /></div>';
		}
		$cblock .= '</div>';
		$smsg = "yjsplitYour message has been sent!jsplit".$cblock;
		echo $smsg;
		exit;
	}
	
	function dosendcomment()
	{
		$t = $_POST['t'];
		$f = $_POST['f'];
		$msg = $this->security->xss_clean($_POST['msg']);
		$msg = strip_tags($msg);
		$new_text = $this->my_stringmanager->wordCleaner($msg);
		$query = $this->db->query('INSERT INTO `comments` 
		(`id`,`owner`,`sender`,`comment`,`status`) 
		VALUES 
		(NULL,"'.$t.'","'.$f.'","'.mysql_real_escape_string($new_text).'","2")');
		mail('xxxxxx@gmail.com,support@xxxxxx.com','New Comment Added - xxxxxx','A new comment has been added and needs approved. '.$msg);
		// get comments
		$comments = $this->my_usersmanager->getUserComments($t);
		$cblock = '<div style="float:left; width:100%;">';
		foreach($comments as $c)
		{
			$cblock .= '<div style="float:left; width:100%;">';
			$cblock .= '<div style="float:left; width:120px; height:120px;"><a href="/profile/'.$this->my_usersmanager->getProfileUrl($c->sender).'"><img src="/image.php?src='.$this->my_usersmanager->getProfilePicFromId($c->sender).'&w=100&h=100&zc=1" width="100" height="100" border="0" /></a></div>';
			$cblock .= '<div style="float:left; width:300px;"><a href="/profile/'.$this->my_usersmanager->getProfileUrl($c->sender).'">'.$this->my_usersmanager->getNickname($c->sender).'</a> said on '.$c->added.'<br /><br />';
			if($c->sender == $f && $c->status == 2)
			{
				$cblock .= '<span style="color:#CCC;">';
				$cblock .= '(Pending Review)<br /><br />';
				$cblock .= '</span>';
				$cblock .= $c->comment;
			}
			else if($c->status == 1)
			{
				$cblock .= $this->my_stringmanager->wordCleaner($c->comment);
			}
			$cblock .= '</div>';
			$cblock .= '</div>';
			$cblock .= '<div style="float:left; width:100%;"><hr size="1" width="100%" /></div>';
		}
		$cblock .= '</div>';
		$smsg = "yjsplitYour message has been sent!jsplit".$cblock;
		echo $smsg;
		exit;
	}
	
	function dosendmsg()
	{
		$t = $_POST['t'];
		$f = $_POST['f'];
		//$cur_hearts = $this->my_usersmanager->getCurrentChips($f);
		//if($cur_hearts > 5)
		//{
			$msg = $this->security->xss_clean($_POST['msg']);
			//$msg = $this->my_stringmanager->cleaner($msg);
			$msg = strip_tags($msg);		
			$new_text = $this->my_stringmanager->wordCleaner($msg);
			$query = $this->db->query('INSERT INTO `messages` 
			(`id`,`parent`,`to`,`from`,`msg`,`status`) VALUES 
			(NULL,"0","'.$t.'","'.$f.'","'.mysql_real_escape_string($new_text).'","1")');
			$msg_id = $this->db->insert_id();
			
			//$query = $this->db->query('UPDATE `users` SET `cur_points` = cur_points - 5, `used_points` = used_points + 5 WHERE `id` = "'.$f.'"');
			
			//$cur_hearts = $cur_hearts = $this->my_usersmanager->getCurrentChips($f);
			$smsg = "ymmYour message has been sent!";
			
			if($f == "6283" || $f == "10052" || $f == "")
			{
				mail('xxxxxx@gmail','Spammer sent message',$msg);
			}
			else
			{
				$this->my_usersmanager->sendUserMail($_POST['t'],$_POST['f'],'msg','na',$msg_id);
			}
		//}
		//else
		//{
			//$smsg = "nmmYou do not have enough hearts to send this user a message!\nYou can upload a photo, show interest, add a few favoriates to get more hearts!";
		//}
		echo $smsg;
		exit;
	}
	
	function updateUserStatus()
	{
		$id = $_POST['id'];
		$status = $_POST['status'];
		$query = $this->db->query('UPDATE `users` SET `status` = "'.$status.'" WHERE `id` = "'.$id.'"');
		$tmp = "ok";
		echo $tmp;
		exit; 
	}
	function updateUserViews()
	{
		$id = $_POST['id'];
		$views = $_POST['views'];
		$query = $this->db->query('UPDATE `users` SET `views` = "'.$views.'" WHERE `id` = "'.$id.'"');
		$tmp = "ok";
		echo $tmp;
		exit; 
	}
	function updateUserHearts()
	{
		$id = $_POST['id'];
		$hearts = $_POST['hearts'];
		$query = $this->db->query('UPDATE `users` SET `cur_points` = "'.$hearts.'" WHERE `id` = "'.$id.'"');
		$tmp = "ok";
		echo $tmp;
		exit; 
	}
	
	function sendChatRequest()
	{
		$to = $_POST['t'];
		$from = $_POST['f'];
		$cid = $_POST['c'];
		$query = $this->db->query('INSERT INTO `chat_requests` (`id`,`t`,`f`,`cid`,`status`) VALUES (NULL,"'.$to.'","'.$from.'","'.$cid.'","1")');
		$tmp = "ok";
		echo $tmp;
		exit;
	}
	
	function checkChatRequest()
	{
		$to = $_POST['id'];
		$query = $this->db->query('SELECT * FROM `chat_requests` WHERE `t` = "'.$to.'" AND `status` = "1"');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$msg = "okmm".$this->my_usersmanager->getNickname($row->f)."mm".$row->t."mm".$row->f."mm".$row->cid;
		}
		else
		{
			$msg = "nammNo new chats";
		}
		echo $msg;
		exit;
	}
	
	function setChatStatus()
	{
		$cid = $_POST['id'];
		$status = $_POST['s'];
		$this->db->query('UPDATE `chat_requests` SET `status` = "'.$status.'" WHERE `cid` = "'.$cid.'"');
	}
	
	function checkEmail()
	{
		$addy = $_POST['em'];
		if (!preg_match("/^[a-zA-Z][\w\.-]*[a-zA-Z0-9]@[a-zA-Z0-9][\w\.-]*[a-zA-Z0-9]\.[a-zA-Z][a-zA-Z\.]*[a-zA-Z]$/i",$addy))
		{
			$tmp = "notok";
		}
		else
		{
			$query = $this->db->query('SELECT `id` FROM `users` WHERE `email` = "'.$addy.'"');
			if($query->num_rows() > 0)
			{
				$tmp = "notok";
			}
			else
			{
				$tmp = "ok";
			}
		}
		echo $tmp;
		exit;
	}
	
	function addFav()
	{
		$fav = $_POST['fav'];
		$owner = $_POST['owner'];
		$query = $this->db->query('INSERT INTO `favs` (`id`,`owner`,`fav`) VALUES (NULL,"'.$owner.'","'.$fav.'")');
		$tmp = "ok";
		echo $tmp;
		exit;
	}
	
	function doRating()
	{
		$type = $_POST['type']; 
		$owner = $_POST['o'];
		$rater = $_POST['v'];
		$rating = $_POST['r'];
		$item = $_POST['i'];
		$query = $this->db->query('INSERT INTO `ratings` 
		(`id`,`owner`,`item`,`type`,`rater_id`,`rating`,`rated`) 
		VALUES 
		(NULL,"'.$owner.'","'.$item.'","'.$type.'","'.$rater.'","'.$rating.'","'.time().'")');
		$query = $this->db->query('SELECT `rating` FROM `ratings` WHERE `owner` = "'.$owner.'" AND `item` = "'.$item.'" AND `type` = "'.$type.'"');
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
			$tmp = "";
		}
		// add points to rater
		//$query = $this->db->query('UPDATE `users` SET `cur_points` = cur_points + 2 WHERE `id` = "'.$rater.'"');
		echo $tmp;
		exit;
	}
	
	function deletePhoto()
	{
		$uid = $_POST['uid'];
		$img = $_POST['img'];
		$query = $this->db->query('UPDATE `images` SET `status` = "3" WHERE `uid` = "'.$uid.'" AND `id` = "'.$img.'"');
		$query = $this->db->query('UPDATE `users` SET `cur_points` = cur_points - 10 WHERE `id` = "'.$uid.'"');
		$tmp = "ok";
		echo $tmp;
		exit;
	}
	
	function approvePhoto()
	{
		$uid = $_POST['uid'];
		$img = $_POST['img'];
		$query = $this->db->query('UPDATE `images` SET `status` = "1" WHERE `uid` = "'.$uid.'" AND `id` = "'.$img.'"');
		$tmp = "ok";
		echo $tmp;
		exit;
	}
	
	function setMainPic()
	{
		$uid = $_POST['uid'];
		$img = $_POST['img'];
		$query = $this->db->query('UPDATE `images` SET `ismain` = "1" WHERE `uid` = "'.$uid.'" AND `id` = "'.$img.'"');
		$query = $this->db->query('UPDATE `images` SET `ismain` = "0" WHERE `uid` = "'.$uid.'" AND `id` != "'.$img.'"');
		$tmp = "ok";
		echo $tmp;
		exit;
	}
}

/* End of file ajax.php */
/* Location: ./application/controllers/ajax.php */