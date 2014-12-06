<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//error_reporting(E_ALL);
class Mapps extends CI_Controller 
{
	public function index()
	{
		echo 'xxxxxx Mobile App Service Calls!';
		exit;
	}
	
	function ftest()
	{
		$test = $this->my_chatmanager->fwendstest();
	}
	
	function cleanData($service,$uri,$pvars='na')
	{
		//$pvars_txt = "Post values: \n";
		//if($pvars != "na")
		//{
			//foreach($pvars as $key=>$value)
			//{
				//$pvars_txt .= $key." => ". $value . "\n";
			//}
		//}
		//mail('xxxxxx@gmail.com','App - Service Call',$service . ' was called useing '. $uri. ' '.$pvars_txt);
	}
	
	function test()
	{
		$type = $this->uri->segment(4,'na');
		$data['type'] = $type;
		$this->load->view('mapps_test',$data);
	}
	
	function storecontacts()
	{
		$userid = $_POST['userid'];
		$contacts = $_POST['contacts'];
		$query = $this->db->query('SELECT `id` FROM `phone_contacts` WHERE `uid` = "'.$userid.'"');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$id = $row->id;
			$query = $this->db->query('UPDATE `phone_contacts` SET `contacts` = "'.mysql_real_escape_string($contacts).'" WHERE `uid` = "'.$userid.'"');
		}
		else
		{
			// new add
			$query = $this->db->query('INSERT INTO `phone_contacts` 
			(`id`,`uid`,`contacts`) 
			VALUES 
			(NULL,"'.$userid.'","'.mysql_real_escape_string($contacts).'")');
		}
		$data = array('res'=>1);
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	
	function getcontacts()
	{
		$userid = $_POST['userid'];
		$query = $this->db->query('SELECT * FROM `phone_contacts` WHERE `uid` = "'.$userid.'"');
		$row = $query->row();
		$contacts = $row->contacts;
		$data = array('contacts'=>$contacts);
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	
	function addpromo()
	{
		$userid = $_POST['userid'];
		$promo = $_POST['promocode'];
		// check for correct promo code
		//mail('xxxxxx@gmail.com','test','uid:'.$userid.' promo:'.$promo);
		$query = $this->db->query('SELECT `id` FROM `promocodes` WHERE `code` = "'.mysql_real_escape_string($promo).'"');
		if($query->num_rows() > 0)
		{
			// has code add to user
			$query = $this->db->query('UPDATE `users` SET `promo_code` = "'.mysql_real_escape_string($promo).'" WHERE `id` = "'.$userid.'"');
			//$msg = 'Your promo code has been added!';
			$msg = 1;
		}
		else
		{
			// non existing promo code
			// get cnt of attempt
			$query = $this->db->query('SELECT `promoct` FROM `users` WHERE `id` = "'.$userid.'"');
			$row = $query->row();
			$current_cnt = $row->promoct;
			if($current_cnt < 3)
			{
				// update cnt
				$query = $this->db->query('UPDATE `users` SET `promoct` = promoct + 1 WHERE `id` = "'.$userid.'"');
				//$msg = 'You have entered an incorrect promo code, please try again.';
				$msg = 2;
			}
			else
			{
				// can not add any more
				//$msg = 'You have reached your limit to add your promo code. Please contact support for help.';
				$msg = 3;
			}
		}
		$data = array('res'=>$msg);
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	
	function picapproved()
	{
		self::cleanData('picapproved',$_SERVER['REQUEST_URI']);
		//$uid = $this->security->xss_clean($this->uri->segment(3));
		$uid = $this->security->xss_clean($_POST['userid']);
		$query = $this->db->query('SELECT `picpath` FROM `users` WHERE `id` = "'.$uid.'" AND `haspic` = "1"');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			if($row->picpath != "")
			{
				$tmp = "true";
			}
			else
			{
				$tmp = "false";
			}
		}
		else
		{
			$tmp = "false";
		}
		self::cleanData('picapproved');
		$data = array('res'=>$tmp);
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	
	function syncp()
	{
		$userid    = $_POST['userid'];
		$sync_code = $_POST['sync_code'];
		$email     = $_POST['email'];
		$type      = $_POST['type'];
		//mail('xxxxxx@gmail.com','test','userid: '.$userid . ' sync code: '.$sync_code.' email: '.$email.' type: '.$type);
		if($type == 'rec')
		{
			if($userid == "na")
			{
				// get and send sync code
				$query = $this->db->query('SELECT `sync_code` FROM `users` WHERE `email` = "'.$email.'"');
			}
			else
			{
				$query = $this->db->query('SELECT `sync_code` FROM `users` WHERE `id` = "'.$userid.'"');
			}
			$row = $query->row();
			$code = $row->sync_code;
			//$msg = 'Here is your sync code. To sync this profile with any device. Enter this code. Website users were automatically emailed the sync code, if you need to retrieve your sync code please contact us.';
			$msg = 1;
		}
		else if($type == "msync")
		{
			$query = $this->db->query('SELECT `id`,`email`,`sync_code` FROM `users` WHERE `email` = "'.$email.'"');
			if($query->num_rows() > 0)
			{
				// get and email sync code.
				$row = $query->row();
				if($row->sync_code != "")
				{
					$syncEmail = $row->sync_code;
				}
				else
				{
					$sc = $this->my_stringmanager->getSyncCode('tj-'.$row->id);
					$query = $this->db->query('UPDATE `users` SET `sync_code` = "'.$sc.'" WHERE `id` = "'.$row->id.'"');
					$syncEmail = $sc;
				}
				$snd = self::sendUserMail($email,'sync_code',$syncEmail);
				//mail($email,'xxxxxx Sync Code','Here is your sync code: ' . $syncEmail);
				//$code = "na";
				$code = 1;
				//$msg = 'Your sync code has been emailed to the email you have entered.';
				$msg = 2;
			}
			else
			{
				//$code = "na";
				$code = 1;
				//$msg = "The email you entered is not in our system. Please try again.";
				$msg = 3;
			}
		}
		else if($type == "sen")
		{
			// get userid from sync_code
			if($email == "na" && $sync_code != "na")
			{
				$query = $this->db->query('SELECT `id` FROM `users` WHERE `sync_code` = "'.$sync_code.'"');
			}
			else if($email != "na" && $sync_code == "na")
			{
				$query = $this->db->query('SELECT `id` FROM `users` WHERE `email` = "'.$email.'"');
			}
			if($query->num_rows() > 0)
			{
				$row = $query->row();
				$new_id = $row->id;
				//mail('xxxxxx@gmail.com','test','test- new id = ' . $new_id);
				// get current device id.
				$query = $this->db->query('SELECT `device_id` FROM `users` WHERE `id` = "'.$userid.'"');
				$row = $query->row();
				$new_deviceid = $row->device_id;
				// update old account
				$query = $this->db->query('UPDATE `users` SET `type` = "synced", `device_id` = "SYNCED:'.$new_id.'" WHERE `id` = "'.$userid.'"');
				// update new account
				$query = $this->db->query('UPDATE `users` SET `device_id` = "'.$new_deviceid.'" WHERE `id` = "'.$new_id.'"');
				//$code = "na";
				$code = 1;
				//$msg = "Your accounts have now been synced!";
				$msg = 4;
			}
			else
			{
				//$code = "na";
				$code = 1;
				if($email != "na")
				{
					//$msg = "The email you entered is not in our system. Please try again.";
					$msg = 3;
				}
				else
				{
					//$msg = "The sync code you entered appears to be invalid. Please try again.";
					$msg = 5;
				}
			}
		}
		$data = array('code'=>$code,'msg'=>$msg);
		//self::cleanData('deleteprofile');
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	
	function reactivate()
	{
		$id = $_POST['userid'];
		$email = $_POST['email'];
		$code = $_POST['code'];
		//mail('xxxxxx@gmail.com','test',$id . ' : ' . $email . ' : ' . $code);
		if($email == "na" && $code != "na")
		{
			// check code and re-activate account
			$query = $this->db->query('SELECT `delcode` FROM `users` WHERE `id` = "'.$id.'"');
			if($query->num_rows() > 0)
			{
				$row = $query->row();
				$delcode = $row->delcode;
				if($delcode == $code)
				{
					$query = $this->db->query('UPDATE `users` SET `delcode` = "", `isdel` = "0" WHERE `id` = "'.$id.'"');
					//$tmp = 'Your account has been re-activated.';
					$tmp = 1;
				}
				else
				{
					//$tmp = 'The code you have entered does not match, please try again.';
					$tmp = 2;
				}
			}
			else
			{
				//$tmp = 'Please contact support, we do not have a delete code matching your user.';
				$tmp = 3;
			}
		}
		else
		{
			// add email and send code
			//$delcode = $this->my_stringmanager->genVerify(8);
			$query = $this->db->query('SELECT `email`,`delcode` FROM `users` WHERE `id` = "'.$id.'"');
			$row = $query->row();
			$delcode = $row->delcode;
			// check for xxxxxx email
			$mystring = $row->email;
			$findme   = 'xxxxxx.com';
			$pos = strpos($mystring, $findme);
			if ($pos === false) 
			{
				// check email if same email code,if not same let user know
				if($row->email == $email && $row->email != "")
				{
					// mail code
					//$query = $this->db->query('UPDATE `users` SET `email` = "'.$email.'" WHERE `id` = "'.$id.'"');
					mail($email,'xxxxxx Re-Activation Code','Here is your re-activation code. ' . $delcode);
					//$tmp = 'Your re-activation code has been emailed to you!';
					$tmp = 4;
				}
				else if($row->email == "")
				{
					// mail code
					$query = $this->db->query('UPDATE `users` SET `email` = "'.$email.'" WHERE `id` = "'.$id.'"');
					mail($email,'xxxxxx Re-Activation Code','Here is your re-activation code. ' . $delcode);
					//$tmp = 'Your re-activation code has been emailed to you!';
					$tmp = 4;
				}
				else
				{
					//$tmp = 'Please contact support, the email you entered does not match the email in our system';
					$tmp = 5;
				}
			}
			else
			{
				// update email and send code
				$query = $this->db->query('UPDATE `users` SET `email` = "'.$email.'" WHERE `id` = "'.$id.'"');
				mail($email,'xxxxxx Re-Activation Code','Here is your re-activation code. ' . $delcode);
				$tmp = 'Your re-activation code has been emailed to you!';
			}
		}
		$data = array('res'=>$tmp);
		//self::cleanData('deleteprofile');
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	
	function deleteprofile()
	{
		$uid = $this->security->xss_clean($_POST['userid']);
		// get del code
		$delcode = $this->my_stringmanager->genVerify(8);
		$query = $this->db->query('UPDATE `users` SET `isdel` = "1", `delcode` = "'.$delcode.'" WHERE `id` = "'.$uid.'"');
		$data = array('res'=>'true');
		//self::cleanData('deleteprofile');
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	
	function unblockeveryone()
	{
		self::cleanData('unblockeveryone',$_SERVER['REQUEST_URI']);
		//$uid = $this->security->xss_clean($this->uri->segment(3));
		$uid = $this->security->xss_clean($_POST['userid']);
		$query = $this->db->query('DELETE FROM `blocks` WHERE `blockedby` = "'.$uid.'"');
		$data = array('res'=>'true');
		//self::cleanData('unblockeveryone');
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	
	function updateViewCnt()
	{
		$uid = $this->security->xss_clean($_POST['userid']);
		$vid = $this->security->xss_clean($_POST['userid2']);
		//mail('xxxxxx@gmail.com','test','updateViewCnt:'.$uid.' : '.$vid);
		$la = self::updateLastActive($uid);
		if($uid != $vid)
		{
			//mail('xxxxxx@gmail.com','test','INSERT INTO `app_profile_views` (`id`,`userid`,`viewed`) VALUES (NULL,"'.$uid.'","'.$vid.'")');
			$query = $this->db->query('INSERT INTO `app_profile_views` (`id`,`userid`,`viewed`) VALUES (NULL,"'.$uid.'","'.$vid.'")');
			$query = $this->db->query('UPDATE `users` SET `views` = views + 1 WHERE `id` = "'.$uid.'"');
		}
		$data = array('res'=>'true');
		//self::cleanData('updateviewcnt');
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	
	function distance()
	{
		// default
		self::cleanData('distance',$_SERVER['REQUEST_URI']);
		$uid = $this->security->xss_clean($_POST['userid']);
		$vid = $this->security->xss_clean($_POST['userid2']);
		$unit = $this->security->xss_clean($_POST['unit']); // Mi or Km
		$query = $this->db->query('SELECT `cur_lat`,`cur_lon` FROM `users` WHERE `id` = "'.$uid.'"');
		$user = $query->row();
		$query = $this->db->query('SELECT `cur_lat`,`cur_lon` FROM `users` WHERE `id` = "'.$vid.'"');
		$viewer = $query->row();
		$distance = $this->my_geomanager->getDistance($user->cur_lat,$user->cur_lon,$viewer->cur_lat,$viewer->cur_lon);
		$data = array('res'=>$distance);
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	
	function onlinestatus()
	{
		self::cleanData('onlinestatus',$_SERVER['REQUEST_URI'],$_POST);
		$uid = $this->security->xss_clean($_POST['userid']);
		$status = $this->security->xss_clean($_POST['status']);
		$la = self::updateLastActive($uid);
		$query = $this->db->query('UPDATE `users` SET `mobile_online` = "'.$status.'" WHERE `id` = "'.$uid.'"');
		if($this->db->affected_rows() > 0)
		{
			$tmp =  "true";
		}
		else
		{
			$tmp = "false";
		}
		$data = array('res'=>$tmp);
		self::cleanData('onlinestatus');
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	
	function updateProfile()
	{
		$uid = $this->security->xss_clean($_POST['userid']);
		$fld = $this->security->xss_clean($_POST['fld']);
		$val = $this->security->xss_clean($_POST['val']);
		$la = self::updateLastActive($uid);
		if($uid == "5852")
		{
			//mail('xxxxxx@gmail.com','test looking for',$fld . ':' . $val . ' : ' . $uid);
		}
		if($fld == "nickname")
		{
			// check for existing
			$msg = "You Displayname has been updated!";
			$size = strlen($val);
			if($size >= 3 && $size < 15)
			{	
				//$query = $this->db->query('SELECT `id` FROM `users` WHERE `nickname` = "'.mysql_real_escape_string($val).'"');
				//if($query->num_rows() > 0)
				//{
					//$msg = 'This displayname is already in use. Please try again.';
				//}
				//else
				//{
					// check for badwords
					$ckname = $this->my_stringmanager->filterNick($val);
					if($ckname != 'ok')
					{
						$msg = 'Your displayname is not allowed. Please try again.';
					}
					else
					{
						$url = $this->my_stringmanager->cleanForUrl($val);
						$query = $this->db->query('UPDATE `users` SET `nickname` = "'.mysql_real_escape_string($val).'", `url` = "'.$url.'" WHERE `id` = "'.$uid.'"');
						$msg = 'Your displayname has been added!';
					}
				//}
			}
			else
			{
				$msg = 'Your displayname needs to be 3 - 15 characters long.';
			}
			$data = array('res'=>$msg);
			$this->output
			->set_content_type('application/json')
    		->set_output(json_encode($data));
		} // end nickname
		else if($fld == "dis")
		{
			$query = $this->db->query('UPDATE `users` SET `show_dis` = "'.$val.'" WHERE `id` = "'.$uid.'"');
			$msg = 'Your distance setting has been set.';
			$data = array('res'=>$msg);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
		}
		else if($fld == "headline")
		{
			$headline = $this->my_stringmanager->removeUnwanted($val);
			$query = $this->db->query('UPDATE `users` SET `headline` = "'.mysql_real_escape_string($headline).'", `status` = "2" WHERE `id` = "'.$uid.'"');
			$msg = 'Your headline has been updated.';
			$data = array('res'=>$msg);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
		} // end headline 
		else if($fld == "bio")
		{
			$bio = $this->my_stringmanager->removeUnwanted($val);
			$query = $this->db->query('UPDATE `profile_data` SET `bio` = "'.mysql_real_escape_string($bio).'", `pstatus` = "2" WHERE `uid` = "'.$uid.'"');
			$msg = 'Your profile has been updated!';
			$data = array('res'=>$msg);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
		} // end bio 
		else if($fld == "relationship")
		{
			//mail('xxxxxx@gmail.com','test looking for',$fld . ':' . $val . ' : ' . $id);
			$query = $this->db->query('UPDATE `profile_data` SET `user_relationship` = "'.$val.'" WHERE `id` = "'.$uid.'"');
			$msg = 'Your profile has been updated!';
			$data = array('res'=>$msg);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
		}// end relationship 
		else if($fld == "lookingfor")
		{
			$query = $this->db->query('UPDATE `profile_data` SET `match_relationship` = "'.$val.'" WHERE `uid` = "'.$uid.'"');
			$msg = 'Your profile has been updated!';
			$data = array('res'=>$msg);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
		} // end looking for 
		else if($fld == "ethnicity")
		{
			$query = $this->db->query('UPDATE `profile_data` SET `user_ethn` = "'.$val.'" WHERE `uid` = "'.$uid.'"');
			$msg = 'Your profile has been updated!';
			$data = array('res'=>$msg);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
		} // end ethn
		else if($fld == "age")
		{
			$age = $this->my_usersmanager->birthday($val);
			//mail('xxxxxx@gmail.com','test','test age '.$age);
			$query = $this->db->query('UPDATE `users` SET `dob` = "'.$val.'", `age` = "'.($age > 99 ? 18 : $age).'" WHERE `id` = "'.$uid.'"');
			$msg = 'Your profile has been updated!';
			$data = array('res'=>$msg);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
		} // end age
		 else if($fld == "height")
		{
			$query = $this->db->query('UPDATE `profile_data` SET `user_height` = "'.$val.'" WHERE `uid` = "'.$uid.'"');
			$msg = 'Your profile has been updated!';
			$data = array('res'=>$msg);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
		} // end height 
		else if($fld == "weight")
		{
			$query = $this->db->query('UPDATE `profile_data` SET `user_weight` = "'.$val.'" WHERE `uid` = "'.$uid.'"');
			$msg = 'Your profile has been updated!';
			$data = array('res'=>$msg);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
		} // end weight 
		else if($fld == "soc")
		{
			$pts = explode('||',$val);
			$typ = $pts[0];
			$url = $pts[1];
			if($typ == "fb")
			{
				$dfld = 'facebook';	
			}
			if($typ == "tw")
			{
				$dfld = 'twitter';
			}
			if($type == "li")
			{
				$dfld = 'linkedin';
			}
			$query = $this->db->query('UPDATE `profile_data` SET `'.$dfld.'` = "'.mysql_real_escape_string($url).'" WHERE `uid` = "'.$uid.'"');
			$msg = 'Your social data has been updated!';
			$data = array('res'=>$msg);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
		} // end soc 
		else if($fld == "agelmt")
		{
			$query = $this->db->query('UPDATE `profile_data` SET `match_age` = "'.$val.'" WHERE `uid` = "'.$uid.'"');
			$msg = 'Your profile has been updated!';
			$data = array('res'=>$msg);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
		}
		else if($fld == "seeking")
		{
			$query = $this->db->query('UPDATE `users` SET `seeking` = "'.$val.'" WHERE `id` = "'.$uid.'"');
			$query = $this->db->query('UPDATE `profile_data` SET `match_gender` = "'.$val.'" WHERE `uid` = "'.$uid.'"');
			$msg = "Your profile has been updated!";
			$data = array('res'=>$msg);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
		}
		else if($fld == "gender")
		{
			$query = $this->db->query('UPDATE `users` SET `gender` = "'.$val.'" WHERE `id` = "'.$uid.'"');
			$msg = "Your profile has been updated!";
			$data = array('res'=>$msg);
			$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
		}
	}
	
	function uploadPhoto()
	{
		$uid = (!$_POST['userid'] ? $_POST['userId'] : $_POST['userid']);
		$fldnm = $_FILES['pic'];
		$la = self::updateLastActive($id);
		// check for pending images
		$query = $this->db->query('SELECT `id`,`status` FROM `waiting_images` WHERE `uid` = "'.$uid.'"');
		if($query->num_rows() > 0)
		{
			// user has pic pending approval
			$msg = "You currently have a pic awaiting to be approved!";
			$pic = "https://www.xxxxxx.com/images/pending_profile_user.png";
		}
		else
		{
			// upload pic
			$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . '/images/p_pics/';
			$config['allowed_types'] = '*';
			$config['overwrite'] = false;
			$new_file_name = time().'.'.end(explode('.',$_FILES['pic']['name']));
			$config['file_name'] = $new_file_name;
			$config['max_size']	= '0';
			$config['max_width']  = '0';
			$config['max_height']  = '0';
			$this->load->library('upload', $config);
			$field_name = "pic";
			if ( ! $this->upload->do_upload($field_name))
			{
				$error = array('error' => $this->upload->display_errors());
				$msg = 'upload_file: ' . $_FILES['pic']['name'] . ' new name: '.$new_file_name.' :: file size : '.$_FILES['pic']['size'] . ' :: ';
				foreach($error as $key=>$value)
				{
					$msg .= $key . '=>' .$value.'-----'; 
				}
				mail('xxxxxx@gmail.com','imgtest',$msg);
			}
			else
			{
				$img = array('upload_data' => $this->upload->data());
				$profile_pic = '/images/p_pics/'.$img['upload_data']['file_name'];
				$query = $this->db->query('INSERT INTO `waiting_images` (`id`,`uid`,`status`,`path`) VALUES (NULL,"'.$uid.'","2","'.$profile_pic.'")');
				$query = $this->db->query('INSERT INTO `images` (`id`,`uid`,`ismain`,`status`,`path`) VALUES (NULL,"'.$uid.'","2","2","'.$profile_pic.'")');
				$query = $this->db->query('UPDATE `users` SET `haspic` = "3" WHERE `id` = "'.$uid.'"');
				$msg = "Your photo has been uploaded and is waiting to be approved!";
				$pic = "https://www.xxxxxx.com/images/pending_profile_user.png";
			}
		}
		$data = array('res'=>$msg,'path'=>$pic);
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	
	function uploadPhotoB()
	{
		$uid = (!$_POST['userid'] ? $_POST['userId'] : $_POST['userid']);
		$fldnm = $_FILES['pic'];
		$la = self::updateLastActive($id);
		// check for pending images
		$query = $this->db->query('SELECT `id`,`status` FROM `waiting_images` WHERE `uid` = "'.$uid.'"');
		if($query->num_rows() > 0)
		{
			// user has pic pending approval
			$msg = "You currently have a pic awaiting to be approved!";
		}
		else
		{
			// upload pic
			$config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . '/images/p_pics/';
			$config['allowed_types'] = '*';
			$config['overwrite'] = false;
			$new_file_name = time().'.'.end(explode('.',$_FILES['pic']['name']));
			$config['file_name'] = $new_file_name;
			$config['max_size']	= '0';
			$config['max_width']  = '0';
			$config['max_height']  = '0';
			$this->load->library('upload', $config);
			$field_name = "pic";
			if ( ! $this->upload->do_upload($field_name))
			{
				$error = array('error' => $this->upload->display_errors());
				$msg = 'upload_file: ' . $_FILES['pic']['name'] . ' new name: '.$new_file_name.' :: file size : '.$_FILES['pic']['size'] . ' :: ';
				foreach($error as $key=>$value)
				{
					$msg .= $key . '=>' .$value.'-----'; 
				}
				mail('xxxxxx@gmail.com','imgtest',$msg);
			}
			else
			{
				$img = array('upload_data' => $this->upload->data());
				$profile_pic = '/images/p_pics/'.$img['upload_data']['file_name'];
				$query = $this->db->query('INSERT INTO `waiting_images` (`id`,`uid`,`status`,`path`) VALUES (NULL,"'.$uid.'","2","'.$profile_pic.'")');
				$msg = "Your photo has been uploaded and is waiting to be approved!";
			}
		}
		$data = array('res'=>$msg);
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($data));
	}
	
	function getProfileValues()
	{
		self::cleanData('getprofilevalues',$_SERVER['REQUEST_URI']);
		$query = $this->db->query('SELECT * FROM `pfields` WHERE `mobile` = "y" AND `status` = "1"');
		$fields = $query->result();
		$field_values = array();
		//$la = self::updateLastActive($uid);
		//self::cleanData('getprofilevalues');
		foreach($fields as $f)
		{
			$query = $this->db->query('SELECT * FROM `pfields_values` WHERE `fid` = "'.$f->id.'"');
			$values = $query->result();
			$varray = array();
			foreach($values as $v)
			{
				$varray[] = array('value'=>$v->id,'txt'=>$v->name);
			}
			$field_values[strtolower(str_replace(' ','',$f->name))]['label'] = $f->name;
			$field_values[strtolower(str_replace(' ','',$f->name))]['values'] = $varray;
		}
		//print_r($field_values);
		//exit;
		$this->output
		->set_content_type('application/json')
    	->set_output(json_encode($field_values));
	}
	
	function getUsersList()
	{	
		$userid = $this->security->xss_clean($_POST['userid']);
		$ltype = $this->security->xss_clean($_POST['ltype']);
		$limit = (@$_POST['limit'] ? ' LIMIT '.$_POST['limit'].',50' : ' LIMIT 0,50');
		$la = self::updateLastActive($userid);
		//$msg = "getUsersList\n";
		//foreach($_POST as $key=>$value)
		//{
			//$msg .= $key.':'.$value."\n";
		//}
		$query = $this->db->query('SELECT `gender`,`seeking`,`cur_lat`,`cur_lon` FROM `users` WHERE `id` = "'.$userid.'"');
		$row = $query->row();
		$ulat = $row->cur_lat;
		$ulon = $row->cur_lon;
		$gender = $row->gender;
		$seeking = $row->seeking;
		$agelmt = $this->my_usersmanager->getAgeLmt($userid);
		$match_age_sql = '';
		$match_age_sql = ' (`age` <= "'.$agelmt['max'].'" AND `age` >= "'.$agelmt['min'].'") AND ';
		if($ltype == "r")
		{
			$query = $this->db->query('SELECT `viewed` FROM `app_profile_views` WHERE `userid` = "'.$userid.'" GROUP BY `viewed` ORDER BY `tsview` DESC');
			if($query->num_rows() > 0)
			{
				$viewed = $query->result();
				$users_data = array();
				//mail('xxxxxx@gmail.com','test','total: ' . count($viewed));
				foreach($viewed as $v)
				{
					$tmp = $this->my_usersmanager->getUserDataApp($userid,$v->viewed);
					if($tmp != "na")
					{
						$users_data[] = $tmp;
					}
				}
			}
		}
		else if($ltype == "e")
		{
			$query = $this->db->query('SELECT `id`,`nickname`,
			( 3959 * acos( cos( radians('.$ulat.') ) * cos( radians( `cur_lat` ) ) 
   			* cos( radians(`cur_lon`) - radians('.$ulon.')) + sin(radians('.$ulat.')) 
   			* sin( radians(`cur_lat`)))) AS distance 
			FROM `users` 
			WHERE  (`type` = "normal" || `type` = "vip" || `type` = "mobile") AND 
			`isdel` != "1" AND 
			`utype` = "a" AND
			'.($seeking > 0 ? '`gender` = "'.$seeking.'" AND' : '').'
			`lastactivity` != 0
			ORDER BY `utype` ASC, `joindate` DESC '.$limit.';');
			$users = $query->result();
			$users_data = array();
			foreach($users as $u)
			{
				$users_data[] = $this->my_usersmanager->getUserDataApp($userid,$u->id);
			}
		}
		else if($ltype == "n")
		{
			$query = $this->db->query('SELECT `id`,`nickname`,
			( 3959 * acos( cos( radians('.$ulat.') ) * cos( radians( `cur_lat` ) ) 
   			* cos( radians(`cur_lon`) - radians('.$ulon.')) + sin(radians('.$ulat.')) 
   			* sin( radians(`cur_lat`)))) AS distance 
			FROM `users`
			WHERE  (`type` = "normal" || `type` = "vip" || `type` = "mobile") AND 
			isdel != "1" AND 
			`utype` = "a" AND
			'.($seeking > 0 ? '`gender` = "'.$seeking.'" AND' : '').'
			`lastactivity` > 0
			ORDER BY `utype`,distance ASC '.$limit.';');
			$users = $query->result();
			$users_data = array();
			foreach($users as $u)
			{
				$users_data[] = $this->my_usersmanager->getUserDataApp($userid,$u->id);
			}
			//$msg = explode('***',$users_data);
			//mail('xxxxxx@gmail.com','test',$msg);
		}
		else
		{	
			/*
			$query = $this->db->query('SELECT `id`, `nickname`,
			( 3959 * acos( cos( radians('.$ulat.') ) * cos( radians( `cur_lat` ) ) 
   			* cos( radians(`cur_lon`) - radians('.$ulon.')) + sin(radians('.$ulat.')) 
   			* sin( radians(`cur_lat`)))) AS distance 
			FROM users 
			WHERE  (`type` = "normal" || `type` = "vip" || `type` = "mobile") AND
			`isdel` != "1" AND 
			`haspic` = "1" AND
			`utype` = "a" AND
			'.($seeking > 0 ? '`gender` = "'.$seeking.'" AND' : '').'
			'.$match_age_sql.'
			`lastactivity` != 0 
			ORDER BY `utype`,`mobile_online`,distance ASC '.$limit.';');
			*/
			$query = $this->db->query('SELECT id, nickname,
		( 3959 * acos( cos( radians('.($ulat != "" ? $ulat : '13.7500').') ) * cos( radians( cur_lat ) ) 
		* cos( radians(cur_lon) - radians('.($ulon != "" ? $ulon : '100.4667').')) + sin(radians('.($ulat != "" ? $ulat : '13.7500').')) 
		* sin( radians(cur_lat)))) AS distance 
		FROM users
		WHERE  (type = "normal" || type = "vip" || type = "mobile") AND
		haspic = "1" AND
		isdel != "1" AND 
		utype = "a" AND
		'.($seeking > 0 ? 'gender = "'.$seeking.'" AND' : '').'
		'.$match_age_sql.'
		`lastactivity` > 0
		ORDER BY mobile_online,`lastactivity` DESC '.$limit.';');
		
			$users = $query->result();
			$users_data = array();
			foreach($users as $u)
			{
				$users_data[] = $this->my_usersmanager->getUserDataApp($userid,$u->id);
			}
		}
		//self::cleanData('getuserslist');
		$data = array();
		$data['users'] = $users_data;
		$this->output
		->set_content_type('application/json')
    	->set_output(json_encode($data));
	}
	
	function lonlattest($lata,$lona,$latb,$lonb)
	{
		$distance = $this->my_geomanager->getDistance($lata,$lona,$latb,$lonb);
		echo $distance;
	}
	
	function supportcode()
	{
		$userid = $this->security->xss_clean($_POST['userid']);
		// generate random support ticket code
		$code = time().'-tj'.$userid;
		// add to db as new ticket
		$data['scode'] = $code;
		$this->output
		->set_content_type('application/json')
    	->set_output(json_encode($data));
	}
	
	function startnew()
	{
		$uid = $_POST['userid'];
		//mail('xxxxxx@gmail.com','test','UPDATE `users` SET `isdel` = "1" WHERE `id` = "'.$uid.'"');
		$this->db->query('UPDATE `users` SET `isdel` = "0" WHERE `id` = "'.$uid.'"');
		$data = array('res'=>'true');
		$this->output
		->set_content_type('application/json')
    	->set_output(json_encode($data));
	}
	
	function droidfl()
	{
		$uid     = $_POST['userid'];
		$lat     = $_POST['lat'];
		$lon     = $_POST['lon'];
		$gender  = $_POST['gender'];
		$seeking = $_POST['seeking'];
		// update user with info
		$query = $this->db->query('UPDATE `users` SET 
		`gender`   = "'.$gender.'",
		`seeking`  = "'.$seeking.'",
		`join_lat` = "'.$lat.'",
		`join_lon` = "'.$lon.'",
		`cur_lat`  = "'.$lat.'",
		`cur_lon`  = "'.$lon.'" WHERE `id` = "'.$uid.'"');
		// get terms 
		$query = $this->db->query('SELECT * FROM `app_text` WHERE `name` = "terms"');
		$item = $query->row();
		$data['res'] = $item->text;
		$this->output
		->set_content_type('application/json')
    	->set_output(json_encode($data));
	}
	
	function appstart()
	{
		$device_id = $this->security->xss_clean($_POST['devid']);
		$lat = $this->security->xss_clean($_POST['lat']);
		$lon = $this->security->xss_clean($_POST['lon']);
		$idDel = 0;
		// check for current device id
		$query = $this->db->query('SELECT `id`,`isdel`,`type` FROM `users` WHERE `device_id` = "'.$device_id.'"');
		if($query->num_rows() > 0)
		{
			// found
			$row = $query->row();
			$userid = $row->id;
			if($row->isdel == 1)
			{
				$isDel = 1;	
				$delid = $row->id;
			}
			if($row->type == 'banned')
			{
				$isBan = "y";
			}
			if($row->type == 'suspended')
			{
				$isSus = "y";
			}
		}
		else
		{
			// new user
			$user_info = "new";
			$sync_code = $this->my_stringmanager->getSyncCode($device_id);
			$query = $this->db->query('INSERT INTO `users` 
			(`id`,`status`,`type`,`verified`,`jtype`,`utype`,`device_id`,`sync_code`,`join_lat`,`join_lon`,`cur_lat`,`cur_lon`) 
			VALUES 
			(NULL,"1","normal","1","Mobile - APP","a","'.$device_id.'","'.$sync_code.'","'.$lat.'","'.$lon.'","'.$lat.'","'.$lon.'")');
			$userid = $this->db->insert_id();
			$query = $this->db->query('INSERT INTO `profile_data`(`id`,`uid`,`pstatus`) VALUES (NULL,"'.$userid.'","2")');
			// set users nickname
			$query = $this->db->query('UPDATE `users` SET `nickname` = "TJ'.$userid.'",`url` = "tj'.$userid.'" WHERE `id` = "'.$userid.'"');
			// add to chat server
			$user_chat->id = $userid;
			$user_chat->nickname = 'TJ-'.$userid;
			$user_chat->email = 'tj-'.$userid.'@xxxxxx.com';
			$added = $this->my_chatmanager->addUser($user_chat);
			if($added['user'] == 'failed' || $added['group'] == "failed")
			{
				mail('xxxxxx@gmail.com','xxxxxx XMPP FAIL!!!','User: '.$added['user'].' Group:'.$added['group']);
			}
			else
			{
				mail('xxxxxx@gmail.com','xxxxxx XMPP FAIL!!!','User: '.$added['user'].' Group:'.$added['group']);
			}
			//$added = $this->my_chatmanager->addToXmpp($userid,$user_chat->nickname,$user_chat->email);
			$to = 'xxxxxx@gmail.com,xxxx@xxxx.com';
			$subject = 'New Member Join Via xxxxxx App!';
			$msg = 'A new user has joined using the mobile app!';
			mail($to,$subject,$msg);
		}
		// update current location
		$query = $this->db->query('UPDATE `users` SET `cur_lat` = "'.$lat.'", `cur_lon` = "'.$lon.'" WHERE `id` = "'.$userid.'"');
		$query = $this->db->query('SELECT `appfrun` FROM `users` WHERE `id` = "'.$userid.'"');
		$row = $query->row();
		$frun = $row->appfrun;
		$data = array();
		$data['frun'] = ($frun == "" ? 'n' : 'y');
		if($isDel == 1)
		{
			$data['userid'] = 'Deleted';
			$data['delid'] = $userid;
		}
		else if($isBan == "y")
		{
			$data['userid'] = 'Banned - Support # TJ'.$userid;
			$data['delid'] = 'Your account has been banned, please contact xxxxxx Support.';
		}
		else if($isSus == "y")
		{
			$data['userid'] = 'Suspended - Support # TJ'.$userid;
			$data['delid'] = 'Your account has been suspended, please contact xxxxxx Support.';
		}
		else
		{
			$data['userid'] = $userid;
		}
		// server.fwends.co.uk
		// 204.93.185.46
		// server.fwends.co.uk
		$data['app_settings'] = array('active_chat'=>3,'inactive_chat'=>30,'xmpps' => 'server.fwends.co.uk','xmppass'=>'X;9T*WeG8yHH');
		$msg = "appstart\n";
		//foreach($_POST as $key=>$value)
		//{
			//$msg .= $key . ":" . $value . "\n";
		//}
		//$msg .= 'UserId: ' . $userid;
		//mail('xxxxxx@gmail.com','AppStart Test',$msg);
		$this->output
		->set_content_type('application/json')
    	->set_output(json_encode($data));
	}
	
	function updateLastActive($userid)
	{
		$query = $this->db->query('UPDATE `users` SET `lastactivity` = "'.time().'", `mobile_online` = "1" WHERE `id` = "'.$userid.'"');
	}
	
	function appload()
	{
		$userid = $this->security->xss_clean($_POST['userid']);
		$msg = "appload\n";
		$la = self::updateLastActive($userid);
		
		// get user info
		$test = @$_POST['test'];
		$la = self::updateLastActive($userid);
		$query = $this->db->query('SELECT * FROM `users` WHERE `id` = "'.$userid.'"');
		$user_data = $query->row();
		$query = $this->db->query('SELECT * FROM `profile_data` WHERE `uid` = "'.$userid.'"');
		$profile_data = $query->row();
		// data to send
		$tmp['userid']   = $user_data->id;
		$tmp['nickname'] = $user_data->nickname;
		$tmp['seeking']  = $this->my_usersmanager->getPfieldValue($user_data->seeking);
		$tmp['relationship'] = $this->my_usersmanager->getPfieldValue($profile_data->user_relationship);
		$tmp['looking']  = $this->my_usersmanager->getPfieldValue($profile_data->match_relationship);
		$tmp['gender']   = $this->my_usersmanager->getPfieldValue($user_data->gender);
		$tmp['dob']      = $user_data->dob;
		$tmp['age']      = ($this->my_usersmanager->birthday($user_data->dob) > 99 ? 18 : $this->my_usersmanager->birthday($user_data->dob));
		$tmp['height']   = $this->my_usersmanager->getPfieldValue($profile_data->user_height);
		$tmp['weight']   = $this->my_usersmanager->getPfieldValue($profile_data->user_weight);
		$tmp['facebook'] = $profile_data->facebook;
		$tmp['twitter']  = $profile_data->twitter;
		$tmp['linkedin'] = $profile_data->linkedin;
		$tmp['bio']      = $profile_data->bio;
		$tmp['msgcnt']   = rand(0,5);
		$tmp['distance'] = "0";
		$tmp['favstatus'] = '0';
		$tmp['headline'] = $user_data->headline;
		$tmp['ethnicity'] = $this->my_usersmanager->getPfieldValue($profile_data->user_ethn);
		$tmp['match_age'] = $profile_data->match_age;
		$tmp['thumb'] = 'https://www.xxxxxx.com/image.php?src='.$this->my_usersmanager->getUserAppPic($user_data->id).'&w=150&h=150&zc=1';
		$tmp['pic'] = $this->my_usersmanager->getUserAppPic($user_data->id);
		$tmp['online'] = $this->my_usersmanager->getOnlineStatus($user_data->id);
		$tmp['show_dis'] = $user_data->show_dis;
		$user_info = $tmp;
		// match age sql
		$match_age_sql = '';
		if($profile_data->match_age != "")
		{
			$pts = explode('-',$profile_data->match_age);
			$match_age_sql = ' (`age` <= "'.($pts[1] == 0 ? 99 : $pts[1]).'" AND `age` >= "'.($pts[0] == 0 ? 18 : $pts[0]).'") ';
		}
		else
		{
			$match_age_sql = ' (`age` <= "99" AND `age` >= "18") ';
		}
		$query = $this->db->query('SELECT `id`, `nickname`,
		( 3959 * acos( cos( radians('.($user_data->cur_lat != "" ? $user_data->cur_lat : '13.7500').') ) * cos( radians( `cur_lat` ) ) 
		* cos( radians(`cur_lon`) - radians('.($user_data->cur_lon != "" ? $user_data->cur_lon : '100.4667').')) + sin(radians('.($user_data->cur_lat != "" ? $user_data->cur_lat : '13.7500').')) 
		* sin( radians(`cur_lat`)))) AS distance
		FROM `users` 
		WHERE  (`type` = "normal" || `type` = "vip" || `type` = "mobile" || `type` = "synced") AND
		`haspic` = "1" AND
		`isdel` != "1" AND 
		`utype` = "a" AND 
		'.($user_data->seeking > 0 ? '`gender` = "'.$user_data->seeking.'" AND' : '').'
		'.$match_age_sql.' 
		ORDER BY `mobile_online`,`lastactivity` DESC LIMIT 50;');
		$users = $query->result();
		$users_data = array();
		foreach($users as $u)
		{
			$users_data[] = $this->my_usersmanager->getUserDataApp($userid,$u->id);
		}
		$data['user_info'] = $user_info;
		$data['near_friends'] = $users_data;
		if($test == "ok")
		{
			print_r($users_data);
		}
		else
		{
			$this->output
			->set_content_type('application/json')
    		->set_output(json_encode($data));
		}
	}
	
	function apploadDroid()
	{
		$userid = $this->security->xss_clean($_POST['userid']);
		$msg = "appload\n";
		$la = self::updateLastActive($userid);
		
		// get user info
		$test = @$_POST['test'];
		$la = self::updateLastActive($userid);
		$query = $this->db->query('SELECT * FROM `users` WHERE `id` = "'.$userid.'"');
		$user_data = $query->row();
		$query = $this->db->query('SELECT * FROM `profile_data` WHERE `uid` = "'.$userid.'"');
		$profile_data = $query->row();
		// data to send
		$tmp['userid']   = $user_data->id;
		$tmp['nickname'] = $user_data->nickname;
		$tmp['seeking']  = $this->my_usersmanager->getPfieldValue($user_data->seeking);
		$tmp['relationship'] = $this->my_usersmanager->getPfieldValue($profile_data->user_relationship);
		$tmp['looking']  = $this->my_usersmanager->getPfieldValue($profile_data->match_relationship);
		$tmp['gender']   = $this->my_usersmanager->getPfieldValue($user_data->gender);
		$tmp['dob']      = $user_data->dob;
		$tmp['age']      = ($this->my_usersmanager->birthday($user_data->dob) > 99 ? 18 : $this->my_usersmanager->birthday($user_data->dob));
		$tmp['height']   = $this->my_usersmanager->getPfieldValue($profile_data->user_height);
		$tmp['weight']   = $this->my_usersmanager->getPfieldValue($profile_data->user_weight);
		$tmp['facebook'] = $profile_data->facebook;
		$tmp['twitter']  = $profile_data->twitter;
		$tmp['linkedin'] = $profile_data->linkedin;
		$tmp['bio']      = $profile_data->bio;
		$tmp['msgcnt']   = rand(0,5);
		$tmp['distance'] = "0";
		$tmp['favstatus'] = '0';
		$tmp['headline'] = $user_data->headline;
		$tmp['ethnicity'] = $this->my_usersmanager->getPfieldValue($profile_data->user_ethn);
		$tmp['match_age'] = $profile_data->match_age;
		$tmp['pic'] = $this->my_usersmanager->getAppPic($user_data->id);
		$tmp['online'] = $this->my_usersmanager->getOnlineStatus($user_data->id);
		$user_info = $tmp;
		// match age sql
		$match_age_sql = '';
		if($profile_data->match_age != "")
		{
			$pts = explode('-',$profile_data->match_age);
			$match_age_sql = ' (`age` <= "'.($pts[1] == 0 ? 99 : $pts[1]).'" AND `age` >= "'.($pts[0] == 0 ? 18 : $pts[0]).'") ';
		}
		else
		{
			$match_age_sql = ' (`age` <= "99" AND `age` >= "18") ';
		}
		$query = $this->db->query('SELECT `id`, `nickname`,
		( 3959 * acos( cos( radians('.($user_data->cur_lat != "" ? $user_data->cur_lat : '13.7500').') ) * cos( radians( `cur_lat` ) ) 
		* cos( radians(`cur_lon`) - radians('.($user_data->cur_lon != "" ? $user_data->cur_lon : '100.4667').')) + sin(radians('.($user_data->cur_lat != "" ? $user_data->cur_lat : '13.7500').')) 
		* sin( radians(`cur_lat`)))) AS distance
		FROM `users` 
		WHERE  (`type` = "normal" || `type` = "vip" || `type` = "mobile" || `type` = "synced") AND
		`haspic` = "1" AND
		`isdel` != "1" AND 
		`utype` = "a" AND 
		'.($user_data->seeking > 0 ? '`gender` = "'.$user_data->seeking.'" AND' : '').'
		'.$match_age_sql.' 
		ORDER BY `mobile_online`,`lastactivity` DESC LIMIT 200;');
		$users = $query->result();
		$users_data = array();
		foreach($users as $u)
		{
			$users_data[] = $this->my_usersmanager->getUserDataApp($userid,$u->id);
		}
		$data['user_info'] = $user_info;
		$data['near_friends'] = $users_data;
		if($test == "ok")
		{
			print_r($users_data);
		}
		else
		{
			$this->output
			->set_content_type('application/json')
    		->set_output(json_encode($data));
		}
	}
	
	function getFavs()
	{
		self::cleanData('getfavs',$_SERVER['REQUEST_URI']);
		$userid = $this->security->xss_clean($_POST['userid']);
		$la = self::updateLastActive($userid);
		$query = $this->db->query('SELECT `cur_lat`,`cur_lon` FROM `users` WHERE `id` = "'.$userid.'"');
		$user = $query->row();
		$query = $this->db->query('SELECT `fav` FROM `favs` WHERE `owner` = "'.$userid.'"');
		if($query->num_rows() > 0)
		{
			$flist = $query->result();
			$favs = array();
			foreach($flist as $f)
			{
				$tmp = $this->my_usersmanager->getUserDataApp($userid,$f->fav);
				if($tmp != "na")
				{
					$favs[] = $tmp;
				}
			}
		}
		else
		{
			$favs['res'] = "na";
		}
		self::cleanData('getfavs');
		$this->output
		->set_content_type('application/json')
    	->set_output(json_encode($favs));
	}
	
	function updateLocation()
	{
		$id = $this->security->xss_clean($_POST['userid']);
		$lat = $this->security->xss_clean($_POST['lat']);
		$lon = $this->security->xss_clean($_POST['lon']);
		$query = $this->db->query('UPDATE `users` SET `cur_lat` = "'.$lat.'", `cur_lon` = "'.$lon.'" WHERE `id` = "'.$id.'"');
		if($this->db->affected_rows() > 0)
		{
			$data['res'] = 'true';
		}
		else
		{
			$data['res'] = 'false';
		}
		self::cleanData('updatelocation');
		$this->output
		->set_content_type('application/json')
    	->set_output(json_encode($data));
	}
	
	function getUserProfile()
	{
		self::cleanData('getuserprofile',$_SERVER['REQUEST_URI']);
		$id = $this->security->xss_clean($_POST['userid']);
		$uid = $this->security->xss_clean($_POST['userid2']);
		// get current users location
		$query = $this->db->query('SELECT `cur_lat`,`cur_lon` FROM `users` WHERE `id` = "'.$id.'"');
		$row = $query->row();
		$ulat = $row->cur_lat;
		$ulon = $row->cur_lon;
		$query = $this->db->query('SELECT `id`,`nickname`,`headline`,`gender`,`seeking`,`picpath`,`dob`,`cur_lat`,`cur_lon`,`mobile_online` FROM `users` WHERE `id` = "'.$uid.'"');
		$udata = $query->row();
		$query = $this->db->query('SELECT * FROM `profile_data` WHERE `uid` = "'.$uid.'"');
		$pdata = $query->row();
		$data['userid'] = $udata->id;
		if($udata->dob != "")
		{
			$data['age'] = $this->my_usersmanager->birthday($udata->dob).' yrs';
		}
		else
		{
			$data['age'] = "na";
		}
		$data['race']         = $this->my_usersmanager->getPfieldValue($pdata->user_ethn);
		$data['gender']       = $this->my_usersmanager->getPfieldValue($udata->gender);
		$data['seeking']      = $this->my_usersmanager->getPfieldValue($udata->seeking);
		$data['onlinestatus'] = $udata->mobile_online;
		$data['nickname']     = $udata->nickname;
		$data['pic']          = 'https://www.xxxxxx.com'.$udata->picpath;
		$data['distance'] = $this->my_geomanager->getDistance($udata->cur_lat,$udata->cur_lon,$ulat,$ulon);
		if($udata->headline != "")
		{
			$data['headline'] = $udata->headline;
		}
		else
		{
			$data['headline'] = "na";
		}
		
		$this->output
		->set_content_type('application/json')
    	->set_output(json_encode($data));
	}
	
	function makeFavorite()
	{
		self::cleanData('makefavorite',$_SERVER['REQUEST_URI']);
		$owner = $this->security->xss_clean($_POST['userid']);
		$fav = $this->security->xss_clean($_POST['userid2']);
		$la = self::updateLastActive($owner);
		$query = $this->db->query('SELECT `id` FROM `favs` WHERE `owner` = "'.$owner.'" AND `fav` = "'.$fav.'"');
		if($query->num_rows() > 0)
		{
			$msg = 'This user is already in your favorites!';
		}
		else
		{
			$query = $this->db->query('INSERT INTO `favs` (`id`,`owner`,`fav`) VALUES (NULL,"'.$owner.'","'.$fav.'")');
			$msg = 'This user has been added to your favorites!';
		}
		$data = array('msg'=>$msg);
		$this->output
		->set_content_type('application/json')
    	->set_output(json_encode($data));
	}
	
	function blockFriend()
	{
		$blocker = $this->security->xss_clean($_POST['userid']);
		$blocked = $this->security->xss_clean($_POST['userid2']);
		$query = $this->db->query('INSERT INTO `blocks` (`id`,`blocked`,`blockedby`) VALUES (NULL,"'.$blocked.'","'.$blocker.'")');
		$msg = "The user has now been blocked.";
		$data = array('msg'=>$msg);
		self::cleanData('blockfriend',$_SERVER['REQUEST_URI']);
		$this->output
		->set_content_type('application/json')
    	->set_output(json_encode($data));
	}
	
	function unfav()
	{
		$owner = $this->security->xss_clean($_POST['userid']);
		$fav = $this->security->xss_clean($_POST['userid2']);
		$query = $this->db->query('DELETE FROM `favs` WHERE `owner` = "'.$owner.'" AND `fav` = "'.$fav.'"');
		$msg = 'The user has been removed from your favorites.';
		self::cleanData('unfav',$_SERVER['REQUEST_URI']);
		$data = array('msg'=>$msg);
		$this->output
		->set_content_type('application/json')
    	->set_output(json_encode($data));
	}
	
	function loadtextDroid()
	{
		$name = $this->security->xss_clean($_POST['name']);
		$query = $this->db->query('SELECT * FROM `app_text` WHERE `name` = "'.$name.'"');
		$item = $query->row();
		$tmp = array
		(
			'subject'=>$item->subject,
			'text' =>strip_tags(str_replace("<br>","\n",$item->text))
		);
		
		$this->output
		->set_content_type('application/json')
    	->set_output(json_encode($tmp));
	}
	
	function setstartinfo()
	{
		$gender = $this->security->xss_clean($_POST['gender']);
		$seeking = $this->security->xss_clean($_POST['seeking']);
		$userid = $this->security->xss_clean($_POST['userid']);
		$query = $this->db->query('UPDATE `users` SET `gender` = "'.$gender.'", `seeking` = "'.$seeking.'", `appfrun` = "y" WHERE `id` = "'.$userid.'"');
		$tmp = array('res'=>'ok');
		$this->output
		->set_content_type('application/json')
		->set_output(json_encode($tmp));
	}
	
	function loadtext()
	{
		$name = $this->security->xss_clean($_POST['name']);
		$query = $this->db->query('SELECT * FROM `app_text` WHERE `name` = "'.$name.'"');
		$item = $query->row();
		$tmp = array
		(
			'subject'=>$item->subject,
			'text' =>$item->text
		);
		echo $tmp['subject'].'|'.$tmp['text'];
	}
	
	function report()
	{
		//self::cleanData('report',$_SERVER['REQUEST_URI']);
		$reporter = $this->security->xss_clean($_POST['userid']);
		$reported = $this->security->xss_clean($_POST['userid2']);
		$rep_msg = $this->security->xss_clean($_POST['message']);
		mail('xxxxxx@gmail.com','xxxxxx User-Reported!','Reproter: '.$reporter.' reported: '. $reported.' reason: '. $rep_msg);
		$query = $this->db->query('INSERT INTO `reported` (`id`,`reported`,`reporter`,`reason`,`status`) 
		VALUES 
		(NULL,"'.$reported.'","'.$reporter.'","'.mysql_real_escape_string($rep_msg).'","1")');
		if($this->db->insert_id() > 0)
		{
			$msg = 'The user has been reported.';
		}
		else
		{
			$msg = "The user has not been reported, please try again.";
		}
		$data = array('msg'=>$msg);
		//self::cleanData('report');
		$this->output
		->set_content_type('application/json')
    	->set_output(json_encode($data));
	}
	
	function mailtest()
	{
		$test = self::sendUserMail('xxxxxx@gmail.com','sync_code','23452342');
		print_r($test);
	}
	
	function sendUserMail($to,$type,$code=0)
	{
		$email_1 = 'no-reply@xxxxxx.com';
		$email_2 = 'no-reply@xxxxxx.com';
		$email_3 = 'no-reply@xxxxxx.com';
		$email_4 = 'no-reply@xxxxxx.com';
		$day_id = 0;
		// check current count
		$cdate = date('Y-m-d',time());
		$query = $this->db->query('SELECT `id`,`cnt` FROM `sync_mails` WHERE `day` = "'.$cdate.'"');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$day_id = $row->id;
			$cur_cnt = $row->cnt;
			if($cur_cnt >= 0 && $cur_cnt <= 450)
			{
				$from_email = $email_1;
			}
			else if($cur_cnt >= 451 && $cur_cnt <= 950)
			{
				$from_email = $email_2;
			}
			else if($cur_cnt >= 951 && $cur_cnt <= 1450)
			{
				$from_email = $email_3;
			}
			else
			{
				$from_email = $email_4;
			}
		}
		else
		{
			// new day add row
			$query = $this->db->query('INSERT INTO `sync_mails` (`id`,`day`,`cnt`) VALUES (NULL,"'.$cdate.'","0")');
			$day_id = $this->db->insert_id();
			$cur_cnt = 0;
			$from_email = $email_1;
		}
		$config = Array(
			'protocol'  => 'smtp',
			'smtp_host' => 'ssl://smtp.googlemail.com',
			'smtp_port' => 25,
			'smtp_user' => $from_email,
			'smtp_pass' => 'ilovexxxx',
			'mailtype'  => 'html', 
			'charset'   => 'iso-8859-1'
		);
		$this->load->library('email', $config);
		$this->email->set_mailtype("html");
		$this->email->set_newline("\r\n");
		// Set to, from, message, etc.
		$this->email->to($to); 
		if($type == 'test')
		{
			$this->email->from($from_email, 'xxxxxx test');
			$this->email->subject('Email Test');
			$msg = "<html>
			<head>
			<title>Test</title>
			</head>
			<body>
			<div align=\"center\">Test for email</div>
			</body>
			</html>";
		}
		else if($type == "sync_code")
		{
			$this->email->from($from_email, 'xxxxxx Sync Code');
			$this->email->subject('xxxxxx Sync Code: '.$code);
			$msg = '<html>
			<head>
			<title>xxxxxx</title>
			</head>
			<body>
			<table width="300" border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
			<td><img src="http://www.xxxxxx.com/images/mail_logo.png" width="114" height="114" alt="xxxxxx" title="xxxxxx" align="absmiddle" /><span style="font-size:36px; font-weight:bold; color:#CCC;">xxxxxx</span></td>
			</tr>
			<tr>
			<td><p>Copy and paste this sync code on the app!</p>
			<p align="center"><span style="font-size:24px; font-weight:bold; color:#000;">'.$code.'</span></p></td>
			</tr>
			<tr>
			<td><p>Thank you,<br>
			xxxxxx Support
			</p></td>
			</tr>
			</table>
			</body>
			</html>';
		}
		$this->email->message($msg);  
		$result = $this->email->send();
		if($result == "1")
		{
			// update cnt
			$query = $this->db->query('UPDATE `sync_mails` SET `cnt` = cnt + 1 WHERE `id` = "'.$day_id.'"');
		}
		return $result;
	}
	
	function agefix()
	{
		$query = $this->db->query('SELECT `id`,`dob`,`age` FROM `users` WHERE `age` = ""');
		$users = $query->result();
		foreach($users as $u)
		{
			$age = $age = $this->my_usersmanager->birthday($u->dob);
			$query = $this->db->query('UPDATE `users` SET `age` = "'.$age.'" WHERE `id` = "'.$u->id.'"');
			echo 'user: '.$u->id . ' has been added: '.$age.'<br />';
		}
	}
	
	/*
	function updateProfilePics()
	{
		$query = $this->db->query('SELECT `id`,`nickname`,`gender` FROM `users` WHERE `haspic` != 1 ORDER BY `id`');
		$users = $query->result();
		foreach($users as $u)
		{
			$num = rand(1,25);
			if($u->gender == "18")
			{
				$folder = "m";
			}
			else
			{
				$folder = "f";
			}
			$img = '/images/toons/'.$folder.'/'.$num.'.jpg';
			$query = $this->db->query('SELECT `id` FROM `images_albums` WHERE `uid` = "'.$u->id.'"');
			if($query->num_rows() > 0)
			{
				$row = $query->row();
				$album_id = $row->id;
			}
			else
			{
				$query = $this->db->query('INSERT INTO `images_albums` (`id`,`uid`,`name`,`url`,`status`) VALUES (NULL,"'.$u->id.'","Profile Pics","profile-pics","1")');
				$album_id = $this->db->insert_id();
			}
			$query = $this->db->query('INSERT INTO `images` (`id`,`uid`,`path`,`ismain`,`views`,`status`,`album_id`,`public`) 
			VALUES 
			(NULL,"'.$u->id.'","'.$img.'","1","0","2","'.$album_id.'","1")');
			$query = $this->db->query('UPDATE `users` SET `haspic` = "1" WHERE `id` = "'.$u->id.'"');
			echo $u->nickname . ' has been updated<br />';
		}
	}
	*/
	
	function picfix()
	{
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `haspic` = "1"');
		$users = $query->result();
		foreach($users as $u)
		{
			$query = $this->db->query('SELECT `id` FROM `images` WHERE `uid` = "'.$u->id.'" AND `ismain` = "1"');
			if($query->num_rows() > 0)
			{
				// has pic do nothing
			}
			else
			{
				$query = $this->db->query('UPDATE `users` SET `haspic` = "2" WHERE `id` = "'.$u->id.'"');
			}
		}
		echo 'Completed';
	}
	
	function gensitesync()
	{
		/*
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `device_id` = "" || `device_id` = "na"');
		$users = $query->result();
		foreach($users as $u)
		{
			$sync_code = $this->my_stringmanager->getSyncCode('tj-'.$u->id);
			$query = $this->db->query('UPDATE `users` SET `sync_code` = "'.$sync_code.'" WHERE `id` = "'.$u->id.'"');
			echo 'user updated - tj-'. $u->id.'<br />';
		}
		*/
	}
	
	function statuscron()
	{
		$lmt_time = strtotime('-10 minutes');
		$query = $this->db->query('UPDATE `users` SET `mobile_online` = "2" WHERE `lastactivity` < "'.$lmt_time.'"');
		//echo 'Completed: ' . $this->db->affected_rows() . ' users set offline';
		//mail('xxxxxx@gmail.com','test-cron','Status Cron Ran');
	}
}

/* End of file mapps.php */
/* Location: ./application/controllers/mapps.php */