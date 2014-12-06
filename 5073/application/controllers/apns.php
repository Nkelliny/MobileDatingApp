<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Apns extends CI_Controller 
{
	public function index()
	{
		$data['success'] = 'na';
		$data['failed'] = 'na';
		//echo $_SERVER['DOCUMENT_ROOT'];
		$this->load->view('apns_default',$data);
	}
	
	function sendApplePushB()
	{
		$usersType = $_POST['selectUsers'];
		
		if($usersType == "male pro" || $usersType == "female pro" || $usersType == "ladyboy pro")
		{
			$users = $_POST['selusers'];
			$usersToSend = array();
			foreach($users as $u)
			{
				$query = $this->db->query('SELECT `device_token` FROM `users` WHERE `id` = "'.$u.'" AND `device_token` != ""');
				if($query->num_rows() > 0)
				{
					$row = $query->row();
					$tmp['id'] = $u;
					$tmp['dt'] = $row->device_token;
					$usersToSend[] = $tmp;
				}
			}
			$push_type = "pro";
		}
		else if($usersType == "male free" || $usersType == "female free" || $usersType == "ladyboy free")
		{
			$users = $_POST['selusers'];
			$usersToSend = array();
			foreach($users as $u)
			{
				$query = $this->db->query('SELECT `device_token` FROM `users` WHERE `id` = "'.$u.'" AND `device_token` != ""');
				if($query->num_rows() > 0)
				{
					$row = $query->row();
					$tmp['id'] = $u;
					$tmp['dt'] = $row->device_token;
					$usersToSend[] = $tmp;
				}
			}
			$push_type = "free";
		}
		else if($usersType == "all female free" || $usersType == "all male free" || $usersType == "all ladyboy free")
		{
			if($usersType == "all female free")
			{
				$gender = "17";
			}
			else if($usersType == "all male free")
			{
				$gender = "18";
			}
			else if($usersType == "all ladyboy free")
			{
				$gender = "19";
			}
			$query = $this->db->query('SELECT `id`,`device_token` FROM `users` WHERE `gender` = "'.$gender.'" AND `device_token` != "" AND `ispro` != "1"');
			$usersList = $query->result();
			$usersToSend = array();
			foreach($usersList as $u)
			{
				$tmp['id'] = $u->id;
				$tmp['dt'] = $u->device_token;
				$usersToSend[] = $tmp;
			}
			$push_type = "free";
		}
		else if($usersType == "all female pro" || $usersType == "all male pro" || $usersType == "all ladyboy pro")
		{
			if($usersType == "all female pro")
			{
				$gender = "17";
			}
			else if($usersType == "all male pro")
			{
				$gender = "18";
			}
			else if($usersType == "all ladyboy pro")
			{
				$gender = "19";
			}
			$query = $this->db->query('SELECT `id`,`device_token` FROM `users` WHERE `gender` = "'.$gender.'" AND `device_token` != "" AND `ispro` = "1"');
			$usersList = $query->result();
			$usersToSend = array();
			foreach($usersList as $u)
			{
				$tmp['id'] = $u->id;
				$tmp['dt'] = $u->device_token;
				$usersToSend[] = $tmp;
			}
			$push_type = "pro";
		}
		//print_r($usersToSend);
		//exit;
		$push = self::sendMultiPush($usersToSend,4,$_POST['msg'],$push_type);
	}
	
	function sendMultiPush($uids,$type,$text,$ver)
	{
		$msg = '{"aps":{"alert":"'.$text.'",},"type":"4" }';
		// send to apple
		//$deviceToken = $token;			
		$passphrase = '';
		$res = '';
		$message = $msg;
		if($ver == "pro")
		{
			$filename = $_SERVER['DOCUMENT_ROOT'].'/js/applecerts/pro/ProductionCertificates.pem';
		}
		else
		{
			$filename = $_SERVER['DOCUMENT_ROOT'].'/js/applecerts/pro/FreeProductionCert.pem';
		}
		$pushurl = 'ssl://gateway.push.apple.com:2195';
		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', $filename);
		stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
		// Open a connection to the APNS server
		$res = '';
		$fp = stream_socket_client($pushurl, $err,$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
		if (!$fp)
		{
			$res = "Failed to connect: $err $errstr" . PHP_EOL.'<br />';
			echo $res . '<br />CERT: ' .$filename .'<br />URL: ' .$pushurl;
			exit;
		}
		else
		{
			$res .= 'Connected to APNS' . PHP_EOL.'<br />';
			$payload = $message;
			$cnt = 0;
			foreach($uids as $u)
			{
				if($cnt == 100)
				{
					fclose($fp);
					usleep(400000);
					$fp = stream_socket_client($pushurl, $err,$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
					$cnt = 0;
				}
				// Build the binary notification
				$msg = chr(0) . pack('n', 32) . pack('H*', $u['dt']) . pack('n', strlen($payload)) . $payload;
				// Send it to the server
				$result = fwrite($fp, $msg, strlen($msg));
				if (!$result)
				{
					$res .= 'Message not delivered' . PHP_EOL.' uid: '.$u['id'].'<br />';
				}
				else
				{
					$res .= 'Message successfully delivered Uid: '.$u['id'].'<br />';
				}
				$cnt++;
				usleep(200000);
			}
		}
		// Close the connection to the server
		fclose($fp);
		echo $res . '<br />CERT: ' .$filename .'<br />URL: ' .$pushurl;
		exit;
	}
	
	function sendApplePush()
	{
		$selectedUsers = $_POST['selectUsers'];
		$msg = $_POST['msg'];
		if($selectedUsers == "female pro" || $selectedUsers == "male pro" || $selectedUsers == "ladyboy pro")
		{
			$users = array();
			$ulist = $_POST['selusers'];
			foreach($ulist as $key=>$value)
			{
				$users[]->id = $value;
			}
		}
		else if($selectedUsers == "all")
		{
			// get all users
			$query = $this->db->query('SELECT `id` FROM `users` WHERE `device_token` != "" ORDER BY `id` DESC');
			$users = $query->result();
		}
		else if($selectedUsers == "all female")
		{
			// get all female users 17
			$query = $this->db->query('SELECT `id` FROM `users` WHERE `gender` = "17" AND `device_token` != "" ORDER BY `id` DESC');
			$users = $query->result();
		}
		else if($selectedUsers == "all male")
		{
			// get all male users 18
			$query = $this->db->query('SELECT `id` FROM `users` WHERE `gender` = "18" AND `device_token` != "" ORDER BY `id` DESC');
			$users = $query->result();
		}
		else if($selectedUsers == "all ladyboy")
		{
			// get all ladyboy 19
			$query = $this->db->query('SELECT `id` FROM `users` WHERE `gender` = "19" AND `device_token` != "" ORDER BY `id` DESC');
			$users = $query->result();
		}
		// send push to users
		$pushSent = 0;
		$pushFailed = 0;
		$failedIds = array();
		$res = '';
		foreach($users as $u)
		{
			// get token
			$res .= "<br /><br />******************************************************************************************<br /><br />";
			$query = $this->db->query('SELECT `device_token`,`isdev`,`ispro` FROM `users` WHERE `id` = "'.$u->id.'"');
			if($query->num_rows() > 0)
			{
				$row = $query->row();
				$token = $row->device_token;
				$isdev = $row->isdev;
				$ispro = $row->ispro;
				// send to apple
				$res .= 'isDev= ' . $isdev . ' isPro= ' . $ispro.'<br />';
				$deviceToken = $token;			
				$passphrase = '';
				$message = '{"aps":{"alert":"'.$msg.'",},"type":"4" }';
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
				$fp = stream_socket_client($pushurl, $err,$errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
				if (!$fp)
				{
					$res .= "Failed to connect: $err $errstr" . PHP_EOL.'<br />';
					$res .= '<br />CERT: ' .$filename .'<br />URL: ' .$pushurl;
					$pushFailed++;
				}
				else
				{
					$res .= 'Connected to APNS' . PHP_EOL.'<br />';
					$payload = $message;
					// Build the binary notification
					$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
					// Send it to the server
					$result = fwrite($fp, $msg, strlen($msg));
					if (!$result)
					{
						$res .= 'Message not delivered' . PHP_EOL.' User: '.$u->id.'<br />';
						$pushFailed++;
					}
					else
					{
						$res .= 'Message successfully delivered to user: '.$u->id.'<br />';
						$pushSent++;
					}
					// Close the connection to the server
					fclose($fp);
					$res .= '<br />CERT: ' .$filename .'<br />URL: ' .$pushurl;
				}			
			}
			else
			{
				$failedIds[] = $u->id . ' has no device token!<br />';
				$pushFailed++;
			}
			unset($filename,$pushurl);
			// sleep a little
			usleep(200000);
		}
		$data['res'] = $res;
		$success = $pushSent . ' Notifications sent successfuly.<br />';
		$failed = $pushFailed . 'Notifications failed! the users are: ' . implode('<br />',$failedIds);
		$data['success'] = $success;
		$data['failed'] = $failed;
		$this->load->view('apns_default',$data);
	}
	
	function sendPush($id,$msg)
	{
		
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */