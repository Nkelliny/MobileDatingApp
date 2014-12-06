<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Controller 
{
	public function index()
	{
		$gen_sql = '';
		$photo_sql = '';
		$app_sql = '';
		$os_sql = '';
		$type_sql = '';
		if($this->input->cookie('tjadminusort') && !isset($_POST['gen_show']))
		{
			$vals = explode('-',$this->input->cookie('tjadminusort'));
			$_POST['gen_show'] = $vals[0];
			$_POST['photos'] = $vals[1];
			$_POST['os'] = $vals[2];
			$_POST['app'] = $vals[3];
			$_POST['utype'] = $vals[4];
		}
		if(isset($_POST['gen_show']))
		{
			if($_POST['gen_show'] != "all")
			{
				$gen_sql = ' AND `gender` = "'.$_POST['gen_show'].'" ';
			}
			if($_POST['photos'] != "all")
			{
				$photo_sql = ' AND `haspic` '.($_POST['photos'] == "1" ? '= "1" ' : '!= "1" ');
			}
			if($_POST['os'] != "all")
			{
				$os_sql = ' AND `os` = "'.$_POST['os'].'" ';
			}
			if($_POST['app'] != "all")
			{
				$app_sql = ' AND `ispro` '.($_POST['app'] == "1" ? '= "1"' : '!= "1"');
			}
			if($_POST['utype'] != "all")
			{
				$type_sql = ' AND `type` = "'.$_POST['utype'].'" ';
			}
			// set cookies
			$cookie = array
			(
				'name'   => 'usort',
				'value'  => $_POST['gen_show'].'-'.$_POST['photos'].'-'.$_POST['os'].'-'.$_POST['app'].'-'.$_POST['utype'],
				'expire' => '86500'
			);
			$this->input->set_cookie($cookie);
		}
		
		$pg = $this->uri->segment(2,'na');
		if($pg != "na")
		{
			$limit = ' LIMIT '.$pg.',50';
		}
		else
		{
			$limit = ' LIMIT 0,50';
		}
		$this->load->library('pagination');
		$config['base_url'] = 'http://www.xxxxxx.com/5073/index.php/users/';
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" '.$gen_sql.$photo_sql.$os_sql.$app_sql.$type_sql.' ORDER BY `id` DESC');
		$total_rows = $query->num_rows();
		$config['total_rows'] = $total_rows;
		$config['per_page'] = ($this->session->userdata('device') == "mobile" ? 51 : 50);
		$config['uri_segment'] = 2;
		$config['full_tag_open'] = '<div class="table_pagination right">';
		$config['full_tag_close'] = '</div>';
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['num_links'] = 10;
		$this->pagination->initialize($config);
		$data['plinks'] = $this->pagination->create_links();
		$query = $this->db->query('SELECT * FROM `users` WHERE `utype` = "a" '.$gen_sql.$photo_sql.$os_sql.$app_sql.$type_sql.' ORDER BY `id` DESC'. $limit);
		if($query->num_rows() > 0)
		{
			$data['users'] = $query->result();
		}
		else
		{
			$data['users'] = "na";
		}
		//$data['plinks'] = '';
		$this->load->view('users_default_new',$data);
	}
	
	function deleted()
	{
		$this->load->library('pagination');
		$config['base_url'] = 'http://www.xxxxxx.com/5073/index.php/users/deleted/';
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `isdel` = "1"');
		$total_rows = $query->num_rows();
		$config['total_rows'] = $total_rows;
		$config['per_page'] =  50;
		$config['uri_segment'] = 3;
		$config['full_tag_open'] = '<div class="table_pagination right">';
		$config['full_tag_close'] = '</div>';
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['num_links'] = 10;
		$this->pagination->initialize($config);
		$data['plinks'] = $this->pagination->create_links();
		$pg = $this->uri->segment(3,'na');
		if($pg != "na")
		{
			$limit = ' LIMIT '.$pg.',50';
		}
		else
		{
			$limit = ' LIMIT 0,50';
		}
		$query = $this->db->query('SELECT * FROM `users` WHERE `utype` = "a" AND `isdel` = "1" ORDER BY `id` DESC '.$limit);
		$users = $query->result();
		$data['users'] = $users;
		$this->load->view('users_deleted',$data);
	}
	
	function admin()
	{
		$query = $this->db->query('SELECT * FROM `users` WHERE `isadmin` = "1" ORDER BY `id`');
		$data['users'] = $query->result();
		$this->load->view('users_admin',$data);
	}
	
	function pushshow()
	{
		$uid = $this->uri->segment(3);
		$data['uid'] = $uid;
		$this->load->view('users_single_push',$data);
	}
	
	function sendpush()
	{
		$uid = $_POST['uid']; // => 30743
    	//$msg = $_POST['msg']; // => test message
		$msg = '{"aps":{"alert":"'.$_POST['msg'].'",},"type":"4" }';
		$query = $this->db->query('SELECT `ispro`,`device_token` FROM `users` WHERE `id` = "'.$uid.'"');
		$row = $query->row();
		// send to apple
		//$deviceToken = $token;			
		$passphrase = '';
		$res = '';
		$message = $msg;
		if($row->ispro == "1")
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
			// Build the binary notification
			$msg = chr(0) . pack('n', 32) . pack('H*', $row->device_token) . pack('n', strlen($payload)) . $payload;
			// Send it to the server
			$result = fwrite($fp, $msg, strlen($msg));
			if (!$result)
			{
				$res .= 'Message not delivered' . PHP_EOL.' uid: '.$u['id'].'<br />';
			}
			else
			{
				$res .= 'Message successfully delivered Uid: '.$uid.'<br />';
			}
		}
		// Close the connection to the server
		fclose($fp);
		echo $res . '<br />CERT: ' .$filename .'<br />URL: ' .$pushurl;
		exit;
	}
	
	function doupdate()
	{
		$id         = $_POST['id'];
		$delphoto   = @$_POST['delphoto'];
		$nickname   = $_POST['nickname'];
		$gender     = $_POST['gender'];
		$year       = $_POST['year'];
		$month      = $_POST['month'];
		$day        = $_POST['day'];
		$headline   = $_POST['headline']; 
		$promo_code = $_POST['promo_code'];
		$bio        = $_POST['bio']; 
		$type       = $_POST['type'];
		$usernotes  = $_POST['usernotes'];
		$exp_date = '0';
		$pgs = "na";
		if(isset($_POST['pgs']))
		{
			$pgs = implode(',',$_POST['pgs']);
		}
		if(isset($_POST['exp_y']))
		{
			$exp_y = $_POST['exp_y']; // => 2014
    		$exp_m = $_POST['exp_m']; // => 05
    		$exp_d = $_POST['exp_d']; // => 13
			$ndate = strtotime($exp_d.'-'.$exp_m.'-'.$exp_y);
			$exp_date = (1000 * $ndate);
		}
		// check to delete image
		if(@$delphoto == "1")
		{
			// delete photo
			$query = $this->db->query('UPDATE `users` SET `haspic` = "2",`picpath` = "" WHERE `id` = "'.$id.'"');
			$query = $this->db->query('SELECT `device_token`,`isdev`,`ispro` FROM `users` WHERE `id` = "'.$id.'" AND `device_token` != ""');
			if($query->num_rows() > 0)
			{
				$row = $query->row();
				$push = $this->my_usersmanager->sendPhotoPushIos($row->device_token,"Your profile photo has been removed!",$row->isdev,$row->ispro,"2");
			}
		}
		// update users table
		$query = $this->db->query('UPDATE `users` SET `nickname` = "'.mysql_real_escape_string($nickname).'", `gender` = "'.$gender.'", `dob` = "'.$year.'-'.$month.'-'.$day.'", `headline` = "'.mysql_real_escape_string($headline).'", `promo_code` = "'.$promo_code.'", `type` = "'.$type.'", `usernotes` = "'.mysql_real_escape_string($usernotes).'", `expiration_date` = "'.$exp_date.'" WHERE `id` = "'.$id.'"');
		// update profile data
		$query = $this->db->query('UPDATE `profile_data` SET `bio` = "'.mysql_real_escape_string($bio).'" WHERE `uid` = "'.$id.'"');
		
		if($pgs != "na")
		{
			$query = $this->db->query('UPDATE `users` SET `apgs` = "'.$pgs.'" WHERE `id` = "'.$id.'"');
		}
		
		// return to edit page
		redirect('/users/info/'.$id,'refresh');
	}
	
	function updateurl()
	{
		$query = $this->db->query('UPDATE `users` SET `nickname` = "'.$_POST['username'].'",`url` = "'.$_POST['url'].'" WHERE `id` = "'.$_POST['uid'].'"');
		redirect('/users/info/'.$_POST['uid'],'refresh');
	}
	
	function export()
	{
		$this->load->view('export_mailer');
	}
	
	function mass()
	{
		//print_r($_POST);
		//exit;
		$mtype = $_POST['massAct'];
		if(is_array($_POST['usr']))
		{
			foreach($_POST['usr'] as $u)
			{
				if($u != "")
				{
					if($mtype == "app")
					{
						$query = $this->db->query('UPDATE `users` SET `status` = "1" WHERE `id` = "'.$u.'"');
						$query = $this->db->query('UPDATE `profile_data` SET `pstatus` = "1" WHERE `uid` = "'.$u.'"');
					}
					else if($mtype == "del")
					{
						// delete user
						$query = $this->db->query('DELETE FROM `users` WHERE `id` = "'.$u.'"');
						// delete profile
						$query = $this->db->query('DELETE FROM `profile_data` WHERE `uid` = "'.$u.'"');
						// delete album
						$query = $this->db->query('DELETE FROM `images_albums` WHERE `uid` = "'.$u.'"');
						// delete images
						$query = $this->db->query('DELETE FROM `images` WHERE `uid` = "'.$u.'"');
					}
				}
			}
		}
		redirect('/users','refresh');
	}
	
	function all()
	{
		$query = $this->db->query('SELECT `id` FROM `users`');
		$total_users = $query->num_rows();
		$pg = $this->uri->segment(3,'na');
		if($pg != "na")
		{
			$limit = 'LIMIT '.$pg.',100';
		}
		else
		{
			$limit = 'LIMIT 0,100';
		}
		$this->load->library('pagination');
		$config['base_url'] = 'http://www.xxxxxx.com/5073/index.php/users/all/';
		$config['total_rows'] = $total_users;
		$config['per_page'] = 100;
		$config['uri_segment'] = 3;
		$config['full_tag_open'] = '<div class="table_pagination right">';
		$config['full_tag_close'] = '</div>';
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['num_links'] = 10;
		$this->pagination->initialize($config);
		$data['plinks'] = $this->pagination->create_links();
		$query = $this->db->query('SELECT users.*,profile_data.* FROM users JOIN profile_data ON users.id = profile_data.uid ORDER BY users.id DESC '.$limit);
		$users = $query->result();
		$data['users'] = $users;
		$this->load->view('users_default',$data);
	}
	
	function sendmsg()
	{
		//print_r($_POST);
		//exit;
		$subject = $_POST['subject']; // => Profile Error
    	$msg = $_POST['msg']; // => This is the message.
    	$to_id = $_POST['id']; // => 6372
    	$from_id = $_POST['frm']; // => 6372
		//sendUserMail($to,$from,$action,$type,$vlink=0)
		$this->CI =& get_instance();
		// get to info
		$query = $this->CI->db->query('SELECT `email`,`nickname`,`gender` FROM `users` WHERE `id` = "'.$to_id.'"');
		$row = $query->row();
		$to_email = $row->email;
		$to_nick = $row->nickname;
		$to_gender = $row->gender;
		if($to_gender == 18)
		{
			$header_pic = 'http://www.xxxxxx.com/images/design/email_logo_male.jpg';
		}
		else
		{
			$header_pic = 'http://www.xxxxxx.com/images/design/email_logo_female.jpg';
		}
		$query = $this->CI->db->query('SELECT `nickname`,`gender` FROM `users` WHERE `id` = "'.$from_id.'"');
		$row = $query->row();
		$from_nick = $row->nickname;
		$from_gender = $row->gender;
		// get mail template
		$query = $this->CI->db->query('SELECT `body` FROM `mail_templates` WHERE `id` = "7"');
		$row = $query->row();
		$template = $row->body;
		$template = str_replace('{%%msgto%%}',$to_nick,$template);
		$template = str_replace('{%%msgfrom%%}',$from_nick,$template);
		$template = str_replace('{%%headerpic%%}',$header_pic,$template);
		$template = str_replace('{%%picfrom%%}',$this->my_usersmanager->getProfilePic(6372,2),$template);
		switch($subject)
		{
			case "Profile Error":
				$subject = "Your profile needs attention!";
				$ret_link = '<a href="http://www.xxxxxx.com">Click here to update your profile.</a>';
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
		$this->session->set_flashdata('msg','The user has been emailed.');
		redirect('http://www.xxxxxx.com/5073/index.php/users/info/'.$to_id,'refresh');
	}
	
	function suspended()
	{
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `type` = "suspended"');
		$total_users = $query->num_rows();
		$pg = $this->uri->segment(3,'na');
		if($pg != "na")
		{
			$limit = 'LIMIT '.$pg.',100';
		}
		else
		{
			$limit = 'LIMIT 0,100';
		}
		$this->load->library('pagination');
		$config['base_url'] = 'http://www.xxxxxx.com/5073/index.php/users/all/';
		$config['total_rows'] = $total_users;
		$config['per_page'] = 100;
		$config['uri_segment'] = 3;
		$config['full_tag_open'] = '<div class="table_pagination right">';
		$config['full_tag_close'] = '</div>';
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['num_links'] = 10;
		$this->pagination->initialize($config);
		$data['plinks'] = $this->pagination->create_links();
		$query = $this->db->query('SELECT users.*,profile_data.* FROM users JOIN profile_data ON users.id = profile_data.uid
		WHERE users.type = "suspended" 
		ORDER BY users.id DESC '.$limit);
		$users = $query->result();
		$data['users'] = $users;
		$this->load->view('users_default',$data);
	}
	
	function fausers()
	{
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `device_id` != "" AND `device_id` != "na" AND `gender` = "17"');
		$total_users = $query->num_rows();
		$pg = $this->uri->segment(3,'na');
		if($pg != "na")
		{
			$limit = 'LIMIT '.$pg.',100';
		}
		else
		{
			$limit = 'LIMIT 0,100';
		}
		$this->load->library('pagination');
		$config['base_url'] = 'http://www.xxxxxx.com/5073/index.php/users/fausers/';
		$config['total_rows'] = $total_users;
		$config['per_page'] = 100;
		$config['uri_segment'] = 3;
		$config['full_tag_open'] = '<div class="table_pagination right">';
		$config['full_tag_close'] = '</div>';
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['num_links'] = 10;
		$this->pagination->initialize($config);
		$data['plinks'] = $this->pagination->create_links();
		$query = $this->db->query('SELECT users.*,profile_data.* FROM users JOIN profile_data ON users.id = profile_data.uid
		WHERE users.device_id != "" AND users.device_id != "na" 
		AND users.gender = "17"
		ORDER BY users.id DESC '.$limit);
		$users = $query->result();
		$data['users'] = $users;
		$this->load->view('users_default',$data);
	}
	
	function mobilem()
	{
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `device_id` != "" AND `device_id` != "na" AND `gender` = "18"');
		$total_users = $query->num_rows();
		$pg = $this->uri->segment(3,'na');
		if($pg != "na")
		{
			$limit = 'LIMIT '.$pg.',100';
		}
		else
		{
			$limit = 'LIMIT 0,100';
		}
		$this->load->library('pagination');
		$config['base_url'] = 'http://www.xxxxxx.com/5073/index.php/users/mobilem/';
		$config['total_rows'] = $total_users;
		$config['per_page'] = 100;
		$config['uri_segment'] = 3;
		$config['full_tag_open'] = '<div class="table_pagination right">';
		$config['full_tag_close'] = '</div>';
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['num_links'] = 10;
		$this->pagination->initialize($config);
		$data['plinks'] = $this->pagination->create_links();
		$query = $this->db->query('SELECT users.*,profile_data.* FROM users JOIN profile_data ON users.id = profile_data.uid
		WHERE users.device_id != "" AND users.device_id != "na" AND `gender` = "18"
		ORDER BY users.id DESC '.$limit);
		$users = $query->result();
		$data['users'] = $users;
		$this->load->view('users_default',$data);
	}
	
	function mobilef()
	{
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `device_id` != "" AND `device_id` != "na" AND `gender` = "17"');
		$total_users = $query->num_rows();
		$pg = $this->uri->segment(3,'na');
		if($pg != "na")
		{
			$limit = 'LIMIT '.$pg.',100';
		}
		else
		{
			$limit = 'LIMIT 0,100';
		}
		$this->load->library('pagination');
		$config['base_url'] = 'http://www.xxxxxx.com/5073/index.php/users/mobilef/';
		$config['total_rows'] = $total_users;
		$config['per_page'] = 100;
		$config['uri_segment'] = 3;
		$config['full_tag_open'] = '<div class="table_pagination right">';
		$config['full_tag_close'] = '</div>';
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['num_links'] = 10;
		$this->pagination->initialize($config);
		$data['plinks'] = $this->pagination->create_links();
		$query = $this->db->query('SELECT users.*,profile_data.* FROM users JOIN profile_data ON users.id = profile_data.uid
		WHERE users.device_id != "" AND users.device_id != "na" AND `gender` = "17"
		ORDER BY users.id DESC '.$limit);
		$users = $query->result();
		$data['users'] = $users;
		$this->load->view('users_default',$data);
	}
	
	function mobilel()
	{
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `device_id` != "" AND `device_id` != "na" AND `gender` = "19"');
		$total_users = $query->num_rows();
		$pg = $this->uri->segment(3,'na');
		if($pg != "na")
		{
			$limit = 'LIMIT '.$pg.',100';
		}
		else
		{
			$limit = 'LIMIT 0,100';
		}
		$this->load->library('pagination');
		$config['base_url'] = 'http://www.xxxxxx.com/5073/index.php/users/mobilel/';
		$config['total_rows'] = $total_users;
		$config['per_page'] = 100;
		$config['uri_segment'] = 3;
		$config['full_tag_open'] = '<div class="table_pagination right">';
		$config['full_tag_close'] = '</div>';
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['num_links'] = 10;
		$this->pagination->initialize($config);
		$data['plinks'] = $this->pagination->create_links();
		$query = $this->db->query('SELECT users.*,profile_data.* FROM users JOIN profile_data ON users.id = profile_data.uid
		WHERE users.device_id != "" AND users.device_id != "na" AND `gender` = "19"
		ORDER BY users.id DESC '.$limit);
		$users = $query->result();
		$data['users'] = $users;
		$this->load->view('users_default',$data);
	}
	
	function mobile()
	{
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `device_id` != "" AND `device_id` != "na"');
		$total_users = $query->num_rows();
		$pg = $this->uri->segment(3,'na');
		if($pg != "na")
		{
			$limit = 'LIMIT '.$pg.',100';
		}
		else
		{
			$limit = 'LIMIT 0,100';
		}
		$this->load->library('pagination');
		$config['base_url'] = 'http://www.xxxxxx.com/5073/index.php/users/mobile/';
		$config['total_rows'] = $total_users;
		$config['per_page'] = 100;
		$config['uri_segment'] = 3;
		$config['full_tag_open'] = '<div class="table_pagination right">';
		$config['full_tag_close'] = '</div>';
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['num_links'] = 10;
		$this->pagination->initialize($config);
		$data['plinks'] = $this->pagination->create_links();
		$query = $this->db->query('SELECT users.*,profile_data.* FROM users JOIN profile_data ON users.id = profile_data.uid
		WHERE users.device_id != "" AND users.device_id != "na" 
		ORDER BY users.id DESC '.$limit);
		$users = $query->result();
		$data['users'] = $users;
		$this->load->view('users_default',$data);
	}
	
	function vip()
	{
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `type` = "vip"');
		$total_users = $query->num_rows();
		$pg = $this->uri->segment(3,'na');
		if($pg != "na")
		{
			$limit = 'LIMIT '.$pg.',100';
		}
		else
		{
			$limit = 'LIMIT 0,100';
		}
		$this->load->library('pagination');
		$config['base_url'] = 'http://www.xxxxxx.com/5073/index.php/users/all/';
		$config['total_rows'] = $total_users;
		$config['per_page'] = 100;
		$config['uri_segment'] = 3;
		$config['full_tag_open'] = '<div class="table_pagination right">';
		$config['full_tag_close'] = '</div>';
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['num_links'] = 10;
		$this->pagination->initialize($config);
		$data['plinks'] = $this->pagination->create_links();
		$query = $this->db->query('SELECT users.*,profile_data.* FROM users JOIN profile_data ON users.id = profile_data.uid
		WHERE users.type = "vip" 
		ORDER BY users.id DESC '.$limit);
		$users = $query->result();
		$data['users'] = $users;
		$this->load->view('users_default',$data);
	}
	
	function suspicious()
	{
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `type` = "suspicious"');
		$total_users = $query->num_rows();
		$pg = $this->uri->segment(3,'na');
		if($pg != "na")
		{
			$limit = 'LIMIT '.$pg.',100';
		}
		else
		{
			$limit = 'LIMIT 0,100';
		}
		$this->load->library('pagination');
		$config['base_url'] = 'http://www.xxxxxx.com/5073/index.php/users/all/';
		$config['total_rows'] = $total_users;
		$config['per_page'] = 100;
		$config['uri_segment'] = 3;
		$config['full_tag_open'] = '<div class="table_pagination right">';
		$config['full_tag_close'] = '</div>';
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['num_links'] = 10;
		$this->pagination->initialize($config);
		$data['plinks'] = $this->pagination->create_links();
		$query = $this->db->query('SELECT users.*,profile_data.* FROM users JOIN profile_data ON users.id = profile_data.uid
		WHERE users.type = "suspicious" 
		ORDER BY users.id DESC '.$limit);
		$users = $query->result();
		$data['users'] = $users;
		$this->load->view('users_default',$data);
	}
	
	function banned()
	{
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `type` = "banned"');
		$total_users = $query->num_rows();
		$pg = $this->uri->segment(3,'na');
		if($pg != "na")
		{
			$limit = 'LIMIT '.$pg.',100';
		}
		else
		{
			$limit = 'LIMIT 0,100';
		}
		$this->load->library('pagination');
		$config['base_url'] = 'http://www.xxxxxx.com/5073/index.php/users/all/';
		$config['total_rows'] = $total_users;
		$config['per_page'] = 100;
		$config['uri_segment'] = 3;
		$config['full_tag_open'] = '<div class="table_pagination right">';
		$config['full_tag_close'] = '</div>';
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['num_links'] = 10;
		$this->pagination->initialize($config);
		$data['plinks'] = $this->pagination->create_links();
		$query = $this->db->query('SELECT users.*,profile_data.* FROM users JOIN profile_data ON users.id = profile_data.uid
		WHERE users.type = "banned" 
		ORDER BY users.id DESC '.$limit);
		$users = $query->result();
		$data['users'] = $users;
		$this->load->view('users_default',$data);
	}
	
	function del()
	{
		$id = $this->uri->segment(3);
		// delete user
		$query = $this->db->query('DELETE FROM `users` WHERE `id` = "'.$id.'"');
		// delete profile
		$query = $this->db->query('DELETE FROM `profile_data` WHERE `uid` = "'.$id.'"');
		// delete album
		$query = $this->db->query('DELETE FROM `images_albums` WHERE `uid` = "'.$id.'"');
		// delete images
		$query = $this->db->query('DELETE FROM `images` WHERE `uid` = "'.$id.'"');
		redirect('/users','refresh');
	}
	
	function approve()
	{
		$id = $this->uri->segment(3);
		$query = $this->db->query('UPDATE `users` SET `status` = "1" WHERE `id` = "'.$id.'"');
		$query = $this->db->query('UPDATE `profile_data` SET `pstatus` = "1" WHERE `uid` = "'.$id.'"');
		redirect('/users','refresh');
	}
	
	function updatebio()
	{
		$bio = $this->security->xss_clean($_POST['bio']);
    	$uid = $_POST['uid'];
		// check bio
		$isok = $this->my_stringmanager->checkProfileChange('bio',$bio,$uid);
		if($isok == "y")
		{
			$status = '';
		}
		else
		{
			$status = ' ,`pstatus` = "2" ';
		}
		$query = $this->db->query('UPDATE `profile_data` SET `bio` = "'.mysql_real_escape_string($bio).'" '.$status.' WHERE `uid` = "'.$uid.'"');
		$url = $this->my_usersmanager->getProfileUrl($uid);
		redirect('/users/info/'.$uid,'refresh');
	}
	
	function updateinfo()
	{
		$headline      = $this->security->xss_clean($_POST['headline']); // => This is my headline!
		$gender        = $_POST['gender'];
		$match_gender  = $_POST['match_gender']; // => 1
		$lookingto     = $_POST['lookingto']; // => 0
		$country_now   = ''; // => TH
		$state_now     = ''; // => TH60
		$city_now      = ''; // => 263742
		$user_height   = $_POST['height']; // => 512
		$user_weight   = $_POST['body_type']; // => 2
		$user_edu      = $_POST['education']; // => 1
		$user_occ      = $this->security->xss_clean($_POST['occ']); // => 
		$user_income   = $_POST['income']; // => 32
		$user_ethn     = $_POST['ethnicity']; // => 0
		$user_reli     = $_POST['religion']; // => 33554432
		$user_children = $_POST['haschildren2']; // => 8
		$user_hair     = $_POST['user_hair']; // => 16
		$user_eye      = $_POST['user_eye']; // => 4
		$user_smoke    = $_POST['user_smoke']; // => 8
		$user_drink    = $_POST['user_drink']; // => 4
		$user_msn      = $this->security->xss_clean($_POST['msn']); // => lasher13@hotmail.com
		$user_yahoo    = $this->security->xss_clean($_POST['yahoo']); // => lasher13420@yahoo.com
		$user_facebook = $this->security->xss_clean($_POST['facebook']); // => http://www.facebook.com
		$user_google   = $this->security->xss_clean($_POST['google']); // => http://www.google.com
		$user_skype    = $this->security->xss_clean($_POST['skype']); // => xxxxxx
		$user_phone    = $this->security->xss_clean($_POST['share_phone']); // => 087774448484
		$user_email    = $this->security->xss_clean($_POST['share_email']); // => xxxxxx@gmail.com
		$user_black    = $this->security->xss_clean($_POST['blackberry']); // => 2228882282
		$uid           = $_POST['uid'];
		$isok = $this->my_stringmanager->checkProfileChange('headline',$headline,$uid);
		if($isok == "y")
		{
			$status = '';
		}
		else
		{
			$status = ' ,`status` = "2" ';
		}
		$query = $this->db->query('UPDATE `users` SET 
		`gender`  = "'.$gender.'",
		`headline` = "'.mysql_real_escape_string($headline).'",
		`seeking` = "'.$match_gender.'",
		`lookingfor` = "'.$lookingto.'", 
		`country_now` = "'.$country_now.'", 
		`state_now` = "'.$state_now.'", 
		`city_now` = "'.$city_now.'" '.$status.'
		WHERE `id` = "'.$uid.'"');
		
		$query = $this->db->query('UPDATE `profile_data` SET 
		`match_gender`   = "'.$match_gender.'",
		`user_children`  = "'.$user_children.'",
		`user_height`    = "'.$user_height.'",
		`user_weight`    = "'.$user_weight.'",
		`user_education` = "'.$user_edu.'",
		`user_income`    = "'.$user_income.'",
		`user_occ`       = "'.$user_occ.'",
		`user_religion`  = "'.$user_reli.'",
		`user_ethn`      = "'.$user_ethn.'",
		`user_hair`      = "'.$user_hair.'",
		`user_eye`       = "'.$user_eye.'",
		`user_smoke`     = "'.$user_smoke.'",
		`user_drink`     = "'.$user_drink.'",
		`msn`            = "'.$user_msn.'",
		`yahoo`          = "'.$user_yahoo.'",
		`facebook`       = "'.$user_facebook.'",
		`google_plus`    = "'.$user_google.'",
		`skype`          = "'.$user_skype.'",
		`share_phone`    = "'.$user_phone.'",
		`share_email`    = "'.$user_email.'",
		`blackberry_pin` = "'.$user_black.'"
		WHERE `uid` = "'.$uid.'"');
		$url = $this->my_usersmanager->getProfileUrl($uid);
		redirect('/users/info/'.$uid,'refresh');
	}
	
	function passchange()
	{
		$passa = $this->security->xss_clean($_POST['passa']);
    	$passb = $this->security->xss_clean($_POST['passb']);
		$pass = sha1('4db689efccf22'.$passa);
    	$uid = $_POST['uid'];
		$query = $this->db->query('UPDATE `users` SET `pass` = "'.$pass.'" WHERE `id` = "'.$uid.'"');
		$this->session->set_flashdata('msg','Your password has been changed.');
		$url = $this->my_usersmanager->getProfileUrl($uid);
		redirect('/users/info/'.$uid,'refresh');
	}
	
	function info()
	{
		$id = $this->uri->segment(3);
		$query = $this->db->query('SELECT * FROM `users` WHERE `id` = "'.$id.'"');
		$data['uinfo'] = $query->row();
		$query = $this->db->query('SELECT * FROM `profile_data` WHERE `uid` = "'.$id.'"');
		$data['pinfo'] = $query->row();
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
		// get block count
		$query = $this->db->query('SELECT `id` FROM `blocks` WHERE `blocked` = "'.$id.'"');
		$data['blocks'] = $query->num_rows();
		// get pages sections
		$query = $this->db->query('SELECT `section` FROM `admin_pages` WHERE `status` = 1 GROUP BY `section` ORDER BY `section`');
		$data['sections'] = $query->result();
		// get admin pages
		$query = $this->db->query('SELECT * FROM `admin_pages` WHERE `status` = "1"');
		$data['pages'] = $query->result();
		
		$this->load->view('users_show',$data);
	}
	
	function update()
	{
		$type = $this->uri->segment(3);
		if($type == "info")
		{
			$url = $_POST['url']; // => happy555
			$nickname = $_POST['nickname']; // => happy555
			$headline = $_POST['headline_']; // => sawadeekup hi I am tim from Sydney 
			$id = $_POST['id']; // => 6335
			$query = $this->db->query('UPDATE `users` SET 
			`url` = "'.$url.'", 
			`nickname` = "'.mysql_real_escape_string($nickname).'", 
			`headline` = "'.mysql_real_escape_string($headline).'" WHERE `id` = "'.$id.'"');
			redirect('/users/info/'.$id,'refresh');
		}
		if($type == "profile")
		{
			$bio = $_POST['bio']; // => hi I am t
			$id = $_POST['id']; // => 6335
			$query = $this->db->query('UPDATE `profile_data` SET `bio` = "'.mysql_real_escape_string($bio).'" WHERE `uid` = "'.$id.'"');
			redirect('/users/info/'.$id,'refresh');
		}
	}
	
	function showcontacts()
	{
		$id = $this->uri->segment(3);
		$query = $this->db->query('SELECT * FROM `phone_contacts` WHERE `id` = "'.$id.'"');
		$contacts = $query->row();
		$data['contacts'] = $contacts;
		$this->load->view('contacts_list',$data);
	}
	
	function exportcontacts()
	{
		$genders = $_POST['genders'];
		$data = $_POST['data'];
		$limit = $_POST['limit'];
		$gender_sql = ' (';
		$a = 0;
		foreach($genders as $g)
		{
			$gender_sql .= '`gender` = "'.$g.'"';
			if($a < (count($genders) - 1))
			{
				$gender_sql .= ' || ';
			}
			$a++;
		}
		$gender_sql .= ')';
		$query = $this->db->query('SELECT `id` FROM `users` WHERE '.$gender_sql.' AND `utype` = "a" LIMIT '.$limit.',500');
		$users = $query->result();
		$csvData = implode(',',$data);
		$csvData .= "\n";
		foreach($users as $u)
		{
			// pull contacts for users
			$query = $this->db->query('SELECT `contacts` FROM `phone_contacts` WHERE `uid` = "'.$u->id.'" AND `contacts` != "(null)" AND `contacts` != "decline" AND `contacts` != ""');
			if($query->num_rows() > 0)
			{
				$res = $query->row();
				// |firstname:P,lastname:JOY,mobile:0894396318
				$contacts = explode('||',$res->contacts);
				foreach($contacts as $c)
				{
					$name = '';
					$mobile = '';
					$email = '';
					$items = explode(',',$c);
					foreach($items as $i)
					{
						$pts = explode(':',$i);
						// check for name
						if($pts[0] == 'firstname')
						{
							$name = $pts[1];
						}
						if($pts[0] == 'mobile')
						{
							$mobile = $pts[1];
						}
						// check for email
						if($pts[0] == "email")
						{
							$email = $pts[1];
						}
					}
					if(in_array('name',$data))
					{
						$csvData .= '"'.$name.'"';
					}
					if(in_array('name',$data) && in_array('mobile',$data))
					{
						$csvData .= ',';
					}
					if(in_array('mobile',$data))
					{
						$csvData .= '"'.$mobile.'"';
					}
					if(in_array('name',$data) || in_array('mobile',$data))
					{
						$csvData .= ',';
					}
					if(in_array('email',$data))
					{
						$csvData .= '"'.$email.'"';
					}
					$csvData .= "\n";
				}
			}
		}
		//$csvData = str_replace('"","",""','',$csvData);
		header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: public");
		header("Content-Type: application/octet-stream");
		header("Content-Disposition: attachment; filename=contacts-".implode('-',$data)."-".$limit."-500-".time().".csv");
		header("Content-length: " . strlen($csvData));
		echo $csvData;
	}
	
	function contacts()
	{
		$pg = $this->uri->segment(3,'0');
		$query = $this->db->query('SELECT `id` FROM `phone_contacts` ORDER BY `id`');
		$total_users = $query->num_rows();
		$this->load->library('pagination');
		$config['base_url'] = 'http://www.xxxxxx.com/5073/index.php/users/contacts/';
		$config['total_rows'] = $total_users;
		$config['per_page'] = 100;
		$config['uri_segment'] = 3;
		$config['full_tag_open'] = '<div class="table_pagination right">';
		$config['full_tag_close'] = '</div>';
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['num_links'] = 10;
		$this->pagination->initialize($config);
		$query = $this->db->query('SELECT * FROM `phone_contacts` ORDER BY `id` LIMIT '.$pg.',100');
		$clist = $query->result();
		$data['plinks'] = $this->pagination->create_links();
		$data['contacts'] = $clist;
		$data['trows'] = $total_users;
		$this->load->view('contacts_default',$data);
	}
	
	function contactnumbers()
	{
		
		$pg = $this->uri->segment(3,'0');
		$query = $this->db->query('SELECT `id` FROM `contact_numbers` WHERE `status` = "3" AND (`number` LIKE "+66%" || `number` LIKE "08%" || `number` LIKE "09%") ORDER BY `id`');
		$total_users = $query->num_rows();
		$this->load->library('pagination');
		$config['base_url'] = 'http://www.xxxxxx.com/5073/index.php/users/contactnumbers/';
		$config['total_rows'] = $total_users;
		$config['per_page'] = 100;
		$config['uri_segment'] = 3;
		$config['full_tag_open'] = '<div class="table_pagination right">';
		$config['full_tag_close'] = '</div>';
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['num_links'] = 10;
		$this->pagination->initialize($config);
		$query = $this->db->query('SELECT * FROM `contact_numbers` WHERE `status` = "3" AND (`number` LIKE "+66%" || `number` LIKE "08%" || `number` LIKE "09%") ORDER BY `id` LIMIT '.$pg.',100');
		$numbers = $query->result();
		$data['plinks'] = $this->pagination->create_links();
		$data['contacts'] = $numbers;
		$data['trows'] = $total_users;
		$this->load->view('contact_numbers',$data);
	}
	
	function contactkeys()
	{
		$query = $this->db->query('SELECT * FROM `contact_keys` ORDER BY `id`');
		$keys = $query->result();
		$data['keys'] = $keys;
		$this->load->view('contact_keys',$data);
	}
	
	function getcontactkeys()
	{
		$val = $this->my_contactsmanager->sortDataKeys();
		echo $val;
	}
	
	function getnumbers()
	{
		$val = $this->my_contactsmanager->getNumbers();
		echo $val;
	}
	
	function sendsms()
	{
		$this->load->view('send_sms_default');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */