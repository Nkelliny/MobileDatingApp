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
	
	function getPfieldOptions($name)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `id` FROM `pfields` WHERE `name` = "'.$name.'"');
		$row = $query->row();
		$query = $this->CI->db->query('SELECT `id`,`name` FROM `pfields_values` WHERE `fid` = "'.$row->id.'"');
		$options = $query->result();
		return $options;
	}
	
	function getGender($id)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `gender` FROM `users` WHERE `id` = "'.$id.'"');
		$row = $query->row();
		if($row->gender == "17")
		{
			$tmp['txt'] = "Female";
			$tmp['id'] = $row->gender;
		}
		else if($row->gender == "18")
		{
			$tmp['txt'] = "Male";
			$tmp['id'] = $row->gender;
		}
		else if($row->gender = "19")
		{
			$tmp['txt'] = "Ladyboy";
			$tmp['id'] = $row->gender;
		}
		else
		{
			$tmp['txt'] = "Not Set";
			$tmp['id'] = "0";
		}
		return $tmp;
	}
	
	function getAdminInfo($id)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `nickname`,`email` FROM `users` WHERE `id` = "'.$id.'"');
		$row = $query->row();
		$tmp = $row->nickname . '<br />' . $row->email;
		return $tmp;
	}
	
	function sendPhotoPushIosMulti($values)
	{
		$pro = array();
		$free = array();
		foreach($values as $v)
		{
			if($v['ispro'] == "1")
			{
				$pro[] = $v;
			}
			else
			{
				$free[] = $v;
			}
		}
		//$pushurl = 'ssl://gateway.sandbox.push.apple.com:2195';
		$pushurl = 'ssl://gateway.push.apple.com:2195';
		$passphrase = '';
		// process pro
		if(count($pro) > 0)
		{
			$filename = $_SERVER['DOCUMENT_ROOT'].'/js/applecerts/pro/ProductionCertificates.pem';
			// connect
			$ctx = stream_context_create();
			stream_context_set_option($ctx, 'ssl', 'local_cert', $filename);
			stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
			// Open a connection to the APNS server
			$res = '';
			$fp = stream_socket_client($pushurl, $err,$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
			if (!$fp)
			{
				$res = "Failed to connect: $err $errstr" . PHP_EOL.'<br />';
				//echo $res . '<br />CERT: ' .$filename .'<br />URL: ' .$pushurl;
				//mail('xxxxxx@gmail.com','tjadmin apns test','Failed to connect: $err $errstr' . PHP_EOL.'<br />Used file: ' . $filename . ' push url: ' . $pushurl);
				//exit;
				return false;
			}
			else
			{
				$res .= 'Connected to APNS' . PHP_EOL.'<br />';
				foreach($pro as $p)
				{
					if($p['type'] == "approve")
					{
						$apn_msg = '{"aps":{"alert":"Your profile photo has been approved!"},"type":2,"is_approved":1}';
					}
					else
					{
						$apn_msg = '{"aps":{"alert":"Your profile photo has been removed!"},"type":2,"is_approved":2}';
					}
					$payload = $apn_msg;
					// Build the binary notification
					$msg = chr(0) . pack('n', 32) . pack('H*', $p['dt']) . pack('n', strlen($payload)) . $payload;
					// Send it to the server
					$result = fwrite($fp, $msg, strlen($msg));
					if (!$result)
					{
						$res .= 'Message not delivered' . PHP_EOL.'<br />';
					}
					else
					{
						$res .= 'Message successfully delivered<br />';
					}
				}
				//mail('xxxxxx@gmail.com','APNS Photos',$res);
			}
			// disconnect
			fclose($fp);
		}
		// process free
		if(count($free) > 0)
		{
			$filename = $_SERVER['DOCUMENT_ROOT'].'/js/applecerts/pro/ProductionCertificates.pem';
			// connect
			$ctx = stream_context_create();
			stream_context_set_option($ctx, 'ssl', 'local_cert', $filename);
			stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
			// Open a connection to the APNS server
			$res = '';
			$fp = stream_socket_client($pushurl, $err,$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
			if (!$fp)
			{
				$res = "Failed to connect: $err $errstr" . PHP_EOL.'<br />';
				//echo $res . '<br />CERT: ' .$filename .'<br />URL: ' .$pushurl;
				//mail('xxxxxx@gmail.com','tjadmin apns test','Failed to connect: $err $errstr' . PHP_EOL.'<br />Used file: ' . $filename . ' push url: ' . $pushurl);
				//exit;
				return false;
			}
			else
			{
				$res .= 'Connected to APNS' . PHP_EOL.'<br />';
				foreach($free as $p)
				{
					if($p['type'] == "approve")
					{
						$apn_msg = '{"aps":{"alert":"Your profile photo has been approved!"},"type":2,"is_approved":1}';
					}
					else
					{
						$apn_msg = '{"aps":{"alert":"Your profile photo has been removed!"},"type":2,"is_approved":2}';
					}
					$payload = $apn_msg;
					// Build the binary notification
					$msg = chr(0) . pack('n', 32) . pack('H*', $p['dt']) . pack('n', strlen($payload)) . $payload;
					// Send it to the server
					$result = fwrite($fp, $msg, strlen($msg));
					if (!$result)
					{
						$res .= 'Message not delivered' . PHP_EOL.'<br />';
					}
					else
					{
						$res .= 'Message successfully delivered<br />';
					}
				}
			}
			// disconnect
			fclose($fp);
		}
	}
	
	function sendPhotoPushIos($token,$img_msg,$isdev,$ispro,$app)
	{
		$apn_msg = '{"aps":{"alert":"'.$img_msg.'"},"type":2,"is_approved":'.$app.'}'; // data 0/1
			
		// send to apple
		$deviceToken = $token;			
		$passphrase = '';
		$message = $apn_msg;
		if($isdev == "1")
		{
			if($ispro == "1")
			{
				$filename = $_SERVER['DOCUMENT_ROOT'].'/js/applecerts/dev/DevCertificates.pem';
			}
			else
			{
				$filename = $_SERVER['DOCUMENT_ROOT'].'/js/applecerts/dev/FreeDevCert.pem';
			}
			$pushurl = 'ssl://gateway.sandbox.push.apple.com:2195';
		}
		else
		{
			// check for free or pro version
			if($ispro == "1")
			{
				$filename = $_SERVER['DOCUMENT_ROOT'].'/js/applecerts/pro/ProductionCertificates.pem';
			}
			else
			{
				$filename = $_SERVER['DOCUMENT_ROOT'].'/js/applecerts/pro/FreeProductionCert.pem';
			}
			$pushurl = 'ssl://gateway.push.apple.com:2195';
		}
		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', $filename);
		stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
		// Open a connection to the APNS server
		$res = '';
		$fp = stream_socket_client($pushurl, $err,$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
		if (!$fp)
		{
			$res = "Failed to connect: $err $errstr" . PHP_EOL.'<br />';
			//echo $res . '<br />CERT: ' .$filename .'<br />URL: ' .$pushurl;
			//mail('xxxxxx@gmail.com','tjadmin apns test','Failed to connect: $err $errstr' . PHP_EOL.'<br />Used file: ' . $filename . ' push url: ' . $pushurl);
			//exit;
			return false;
		}
		else
		{
			$res .= 'Connected to APNS' . PHP_EOL.'<br />';
			// Create the payload body
			//$body['aps'] = array('alert' => $message,'sound' => 'default');
			// Encode the payload as JSON
			//$payload = json_encode($body);
			$payload = $message;
			// Build the binary notification
			$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
			// Send it to the server
			$result = fwrite($fp, $msg, strlen($msg));
			if (!$result)
			{
				$res .= 'Message not delivered' . PHP_EOL.'<br />';
			}
			else
			{
				$res .= 'Message successfully delivered<br />';
			}
			//mail('xxxxxx@gmail.com','tjadmin apns test','res: ' . $res.' used file: ' . $filename. ' push url: ' . $pushurl);
			// Close the connection to the server
			fclose($fp);
			//echo $res . '<br />CERT: ' .$filename .'<br />URL: ' .$pushurl;
			return true;
		}
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
	
	function getUserComments($id)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT * FROM `comments` WHERE `owner` = "'.$id.'" AND `status` = "1" ORDER BY `added` DESC');
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
		$query = $this->CI->db->query('SELECT `id`,`nickname`,`gender`,`seeking`,`status`,`isaff` FROM `users` WHERE `id` = "'.$val.'"');
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
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$url = $row->url;
		}
		else
		{
			$url = '';
		}
		return $url;
	}
	
	function getNickname($id)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `nickname` FROM `users` WHERE `id` = "'.$id.'"');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$nick = $row->nickname;
		}
		else
		{
			$nick = 'USER UNKNOWN!';
		}
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
				if($ugen == "2")
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
					if($ugen == "2")
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
			if($ugen == "2")
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
		$query = $this->CI->db->query('SELECT `id`,`url`,`gender`,`nickname`,`country_now`,`dob` FROM `users` WHERE `gender` = "'.$gender.'" ORDER BY RAND()');
		if($query->num_rows() > 0)
		{
			$members = $query->result();
			$profiles = array();
			$a=0;
			foreach($members as $m)
			{
				if($a == $limit)
				{
					break;
				}
				$query = $this->CI->db->query('SELECT `path` FROM `images` WHERE `status` = "1" AND `path` != "" AND `ismain` = "1" AND `uid` = "'.$m->id.'"');
				if($query->num_rows() > 0)
				{
					$profiles[$a] = $m;
					$a++;
				}
			}
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
			$query = $this->CI->db->query('SELECT `id`,`url`,`gender`,`nickname`,`country_now`,`dob` FROM `users` WHERE `gender` = "'.$gender.'" ORDER BY RAND()');
		}
		else
		{
			$query = $this->CI->db->query('SELECT `id`,`url`,`gender`,`nickname`,`country_now`,`dob` FROM `users` ORDER BY RAND()');
		}
		if($query->num_rows() > 0)
		{
			$members = $query->result();
			$profiles = array();
			$a = 0;
			foreach($members as $m)
			{
				if($a == $limit)
				{
					break;
				}
				$query = $this->CI->db->query('SELECT `path` FROM `images` WHERE `status` = "1" AND `path` != "" AND `ismain` = "1" AND `uid` = "'.$m->id.'"');
				if($query->num_rows() > 0)
				{
					$profiles[] = $m;
					$a++;
				}
			}
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
		$query = $this->CI->db->query('SELECT `path` FROM `images` WHERE `uid` = "'.$uid.'" AND `ismain` = "1"');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$img = $row->path;
			$img = str_replace('/images/uploads/','',$img);
		}
		else
		{
			$img = "na";
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
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$gender = $row->gender;
		}
		else
		{
			$gender = "na";
		}
		return $gender;
	}
	
	function getUserGenderTxt($id)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `gender` FROM `users` WHERE `id` = "'.$id.'"');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$gender = $row->gender;
		}
		else
		{
			$gender = "na";
		}
		if($gender == 17)
		{
			$gt = "Female";
		}
		else if($gender == 18)
		{
			$gt = "Male";
		}
		else if($gender == 19)
		{
			$gt = "Ladyboy";
		}
		else
		{
			$gt = "na";
		}
		return $gt;
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