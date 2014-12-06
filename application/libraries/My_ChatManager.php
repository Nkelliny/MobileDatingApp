<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_ChatManager 
{	
	var $CI;
	function My_ChatManager()
	{
		$CI =& get_instance();
		$CI->load->database();
		$CI->load->library('session');
		$CI->load->library('email');
	}
	
	function fwendstest()
	{
		$editdb = 'fchat';
		$CI =& get_instance();
		$CI->$editdb = $CI->load->database($editdb, TRUE);
		$this->$editdb =& $CI->$editdb;
		$query = $this->$editdb->query('SELECT * FROM `ofUser` ORDER BY `username` LIMIT 100');
		$users = $query->result();
		print_r($users);
		exit;
	}
	
	function cleanName($val)
	{
		$new_string = ereg_replace("[^A-Za-z0-9]", "-", $val);
		$new_string = strtolower($new_string);
		$str_length = strlen($new_string);
		$new_string = preg_replace('{(-)\1+}','$1',$new_string);
		$ck_string_length = strlen($new_string);
		if($ck_string_length > 100)
		{
			$new_string = substr($new_string,0,100);
		}
		$new_string = trim($new_string,'-');
		return $new_string;
	}
	
	function updatePasswords()
	{
		$editdb = 'fchat';
		$CI =& get_instance();
		$CI->$editdb = $CI->load->database($editdb, TRUE);
		$this->$editdb =& $CI->$editdb;
		$pass = 'X;9T*WeG8yHH';
		//ba34d3ebd97a9ec45b554b1d57ed4128d40151b6d1a0d518df3de3634f299e68faecafe9b2a2d746
		$epass = 'ba34d3ebd97a9ec45b554b1d57ed4128d40151b6d1a0d518df3de3634f299e68faecafe9b2a2d746';
		$query = $this->$editdb->query('UPDATE `ofUser` SET `plainPassword` = "'.$pass.'", `encryptedPassword` = "'.$epass.'"');
	}
	
	function updateChatName($name,$id)
	{
		$editdb = 'fchat';
		$CI =& get_instance();
		$CI->$editdb = $CI->load->database($editdb, TRUE);
		$this->$editdb =& $CI->$editdb;
		$query = $this->$editdb->query('UPDATE `ofUser` SET `name` = "'.self::cleanName($name).'" WHERE `name` = "'.$id.'"');
		return true;
	}
	
	function chatGrab($owner)
	{
		$urltopost = "http://fwends.co.uk/chatconnect.php";
		$datatopost = array(
		"hs"=> 'xxxxxxxmpp',
		'owner'=>$owner
		);
		$ch = curl_init($urltopost);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $datatopost);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$returndata = curl_exec($ch);
	}
	
	function addToXmpp($id,$nickname,$email)
	{
		$urltopost = "http://fwends.co.uk/addchatuser.php";
		$datatopost = array (
		"hs"       => 'xxxxxxxmpp',
		"uid"       => $id,
		"nickname" => $nickname,
		"email"    => $email,
		);
		
		$ch = curl_init ($urltopost);
		curl_setopt ($ch, CURLOPT_POST, true);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, $datatopost);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		$returndata = curl_exec ($ch);
	}
	
	function addUser($u)
	{
		if($u->nickname != "")
		{
			// chat password X;9T*WeG8yHH
			$raw_pass = 'X;9T*WeG8yHH';
			$chat_pass = 'ba34d3ebd97a9ec45b554b1d57ed4128d40151b6d1a0d518df3de3634f299e68faecafe9b2a2d746';
			/*
			 [id] => 1 [nickname] => xxxx [email] => xxxx@xxxx.com 
			*/
			$editdb = 'fchat';
			$CI =& get_instance();
			$CI->$editdb = $CI->load->database($editdb, TRUE);
			$this->$editdb =& $CI->$editdb;
			// insert user ofUser
			$query = $this->$editdb->query('SELECT `name` FROM `ofUser` WHERE `username` = "tj-'.$u->id.'"');
			if($query->num_rows() > 0)
			{
				// user already added, skip them
				return 'DUPE - SKIPPED';
			}
			else
			{
				$query = $this->$editdb->query('INSERT INTO `ofUser` 
				(`username`,`plainPassword`,`encryptedPassword`,`name`,`email`,`creationDate`,`modificationDate`) 
				VALUES 
				("tj-'.$u->id.'",
				"'.$raw_pass.'",
				"'.$chat_pass.'",
				"'.$u->nickname.'",
				"'.$u->email.'",
				"'.time().'",
				"'.time().'")');
				if($this->$editdb->affected_rows() >= 1)
				{
					$tmp['user'] = 'added';
				}
				else
				{
					$tmp['user'] = 'failed';
				}
				// insert user group ofGroupUser
				$query = $this->$editdb->query('INSERT INTO `ofGroupUser` (`groupName`,`username`,`administrator`) VALUES ("xxxxxx","tj-'.$u->id.'","0")');
				if($this->$editdb->affected_rows() >= 1)
				{
					$tmp['group'] = 'added';
				}
				else
				{
					$tmp['group'] = 'failed';
				}
				//return $u->nickname;
				return $tmp;
			}
		}
	}
	
	function updatePushSent($ids)
	{
		$editdb = 'fchat';
		$CI =& get_instance();
		$CI->$editdb = $CI->load->database($editdb, TRUE);
		$this->$editdb =& $CI->$editdb;
		$x=1;
		$msg_sql = '(';
		foreach($ids as $i)
		{
			$msg_sql .= ' `messageId` = "'.$i.'"';
			if($x<count($ids))
			{
				$msg_sql .= ' || ';
			}
			$x++;
		}
		$msg_sql .= ')';
		//echo 'UPDATE `ofOffline` SET `push` = push+1 WHERE '.$msg_sql.'';
		$query = $this->$editdb->query('UPDATE `ofOffline` SET `push` = push+1 WHERE '.$msg_sql.'');
	}
	
	function updatePush($ids)
	{
		$editdb = 'fchat';
		$CI =& get_instance();
		$CI->$editdb = $CI->load->database($editdb, TRUE);
		$this->$editdb =& $CI->$editdb;
		if(is_array($ids))
		{
			foreach($ids as $i)
			{
				$query = $this->$editdb->query('UPDATE `ofOffline` SET `push` = push+1 WHERE `messageId` = "'.$i.'"');
			}
		}
		else
		{
			$query = $this->$editdb->query('UPDATE `ofOffline` SET `push` = push+1 WHERE `messageId` = "'.$ids.'"');
		}
	}
	
	function getOfflineMessages()
	{
		$editdb = 'fchat';
		$CI =& get_instance();
		$CI->$editdb = $CI->load->database($editdb, TRUE);
		$this->$editdb =& $CI->$editdb;
		$query = $this->$editdb->query('SELECT `username`,`messageID`,`stanza` FROM `ofOffline` WHERE `username` LIKE "tj-%" AND `push` = 0');
		if($query->num_rows() > 0)
		{
			$msgs = $query->result();
		}
		else
		{
			$msgs = "na";
		}
		return $msgs;
	}
}

?>