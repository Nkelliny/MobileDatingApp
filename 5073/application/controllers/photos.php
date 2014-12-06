<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Photos extends CI_Controller 
{
	public function index()
	{
		$query = $this->db->query('SELECT * FROM `waiting_images` WHERE `status` != "3" ORDER BY `added` DESC');
		if($query->num_rows() > 0)
		{
			$data['photos'] = $query->result();
		}
		else
		{
			$data['photos'] = "na";
		}
		$query = $this->db->query('SELECT `value` FROM `adminTrack` WHERE `name` = "Photos Admin" ORDER BY `id` DESC LIMIT 5');
		$data['adminTrack'] = $query->result();
		$this->load->view('photos_waiting',$data);
	}
	
	function recent()
	{
		$query = $this->db->query('SELECT * FROM `users` WHERE `haspic` = "1" AND `utype` = "a" AND `appbytime` != "" ORDER BY `appbytime` DESC LIMIT 100');
		$data['photos'] = $query->result();
		$this->load->view('photos_recent',$data);
	}
	
	function autoapprove()
	{
		$uid = $this->uri->segment(3);
		$pid = $this->uri->segment(4);
		// cancel out all other profile pics
		$query = $this->db->query('UPDATE `images` SET `ismain` = "2" WHERE `uid` = "'.$uid.'"');
		// set selected pic as main
		$query = $this->db->query('UPDATE `images` SET `ismain` = "1", `status` = "1" WHERE `uid` = "'.$uid.'" AND `id` = "'.$pid.'"');
		// update user to show they have a profile pic
		$query = $this->db->query('UPDATE `users` SET `haspic` = "1" WHERE `id` = "'.$uid.'"');
		echo "PHOTO APPROVED!";
	}
	
	function autodelete()
	{
		$uid = $this->uri->segment(3);
		$pid = $this->uri->segment(4);
		// set selected pic as main
		$query = $this->db->query('UPDATE `images` SET `ismain` = "2", `status` = "3" WHERE `uid` = "'.$uid.'" AND `id` = "'.$pid.'"');
		echo "PHOTO DELETED";
	}
	
	function appmass()
	{
		$mtype = $_POST['massAct'];
		$app_cnt = 0;
		$del_cnt = 0;
		$push_values = array();
		if(is_array($_POST['usr']))
		{
			foreach($_POST['usr'] as $u)
			{
				$query = $this->db->query('SELECT * FROM `waiting_images` WHERE `id` = "'.$u.'"');
				$row = $query->row();
				if($mtype == "app")
				{
					// add to users table 
					$query = $this->db->query('UPDATE `users` SET `haspic` = "1", `picpath` = "'.$row->path.'", `appby` = "'.$this->session->userdata('uid').'",`appbytime` = "'.time().'" WHERE `id` = "'.$row->uid.'"');
					$app_cnt++;
					// remove from waiting_images
					$query = $this->db->query('DELETE FROM `waiting_images` WHERE `id` = "'.$u.'"');
					// get user device token
					$query = $this->db->query('SELECT `id`,`device_token`,`isdev`,`ispro` FROM `users` WHERE `id` = "'.$row->uid.'" AND `device_token` != ""');
					if($query->num_rows() > 0)
					{
						$row = $query->row();
						$tmp['id'] = $row->id;
						$tmp['dt'] = $row->device_token;
						$tmp['ispro'] = $row->ispro;
						$tmp['type'] = 'approve';
						$push_values[] = $tmp;
						//$push = $this->my_usersmanager->sendPhotoPushIos($row->device_token,"Your profile photo has been approved!",$row->isdev,$row->ispro,"1");
					}
				}
				else if($mtype == "del")
				{
					// set as 3 on waiting images
					$query = $this->db->query('DELETE FROM `waiting_images` WHERE `id` = "'.$u.'"');
					$query = $this->db->query('UPDATE `users` SET `haspic` = "4" WHERE `id` = "'.$row->uid.'"');
					$query = $this->db->query('SELECT `id`,`device_token`,`isdev`,`ispro` FROM `users` WHERE `id` = "'.$row->uid.'" AND `device_token` != ""');
					$del_cnt++;
					if($query->num_rows() > 0)
					{
						$row = $query->row();
						$tmp['id'] = $row->id;
						$tmp['dt'] = $row->device_token;
						$tmp['ispro'] = $row->ispro;
						$tmp['type'] = 'delete';
						$push_values[] = $tmp;
						//$push = $this->my_usersmanager->sendPhotoPushIos($row->device_token,"Your profile photo has been removed!",$row->isdev,$row->ispro,"2");
					}
				}	
			}
			$push = $this->my_usersmanager->sendPhotoPushIosMulti($push_values);
		}
		date_default_timezone_set('Asia/Bangkok');
		$msg = 'There were '. $app_cnt . ' photos approved, and '.$del_cnt . ' photos deleted at '.date('Y-M-d H:i:s',time()) . ' This action was preformed by '. self::getAdminUser($this->session->userdata('uid'));
		$query = $this->db->query('INSERT INTO `adminTrack` (`id`,`name`,`value`) VALUES (NULL,"Photos Admin","'.$msg.'")');
		redirect('/photos','refresh');
	}
	
	function getAdminUser($id)
	{
		$query = $this->db->query('SELECT `email`,`nickname` FROM `users` WHERE `id` = "'.$id.'"');
		$row = $query->row();
		$user = $row->nickname . ' ' . $row->email;
		return $user;
	}
	
	function mass()
	{
		$mtype = $_POST['massAct'];
		if(is_array($_POST['usr']))
		{
			foreach($_POST['usr'] as $u)
			{
				if($mtype == "app")
				{
					$query = $this->db->query('UPDATE `images` SET `status` = "1", `home` = "1", `mailer` = "1", `ismain` = "1" WHERE `id` = "'.$u.'"');
				}
				else if($mtype == "del")
				{
					$query = $this->db->query('UPDATE `images` SET `status` = "3", `ismain` = "2" WHERE `id` = "'.$u.'"');
				}
			}
		}
		redirect('/photos','refresh');
	}
	
	function mailer()
	{
		$this->load->view('photos_mailer');
	}
	
	function deleted()
	{
		$limit = $this->uri->segment(3,'na');
		$this->load->library('pagination');
		$config['base_url'] = 'http://www.xxxxxx.com/5073/index.php/photos/deleted/';
		$query = $this->db->query('SELECT i.id
		FROM images AS i
		INNER JOIN users AS u ON i.uid = u.id
		WHERE i.status = 3
		ORDER BY i.id DESC ');
		$total_rows = $query->num_rows();
		$config['total_rows'] = $total_rows;
		$config['per_page'] = 100;
		$config['uri_segment'] = 3;
		$config['full_tag_open'] = '<div class="table_pagination right">';
		$config['full_tag_close'] = '</div>';
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['num_links'] = 10;
		$this->pagination->initialize($config);
		$data['plinks'] = $this->pagination->create_links();
		$query = $this->db->query('SELECT i.id,i.path,i.thumb,i.home,i.uid,i.mailer
		FROM images AS i
		INNER JOIN users AS u ON i.uid = u.id
		WHERE i.status = 3
		ORDER BY i.id DESC 
		'.($limit != "na" ? 'LIMIT '.$limit.',100' : 'LIMIT 100')); 
		
		if($query->num_rows() > 0)
		{
			$images = $query->result();
		}
		else
		{
			$images = "na";
		}
		$data['images'] = $images;
		$this->load->view('photos_choose',$data);
	}
	
	function allappimages()
	{
		$gender = $this->uri->segment(3);
		$limit = $this->uri->segment(4,'na');
		$this->load->library('pagination');
		$config['base_url'] = 'http://www.xxxxxx.com/5073/index.php/photos/allappimages/'.$gender.'/';
		$query = $this->db->query('SELECT i.id
		FROM images AS i
		INNER JOIN users AS u ON i.uid = u.id
		WHERE u.gender = '.$gender.' AND 
		u.haspic = 1 AND 
		u.utype = "a"
		ORDER BY i.id DESC ');
		$total_rows = $query->num_rows();
		$config['total_rows'] = $total_rows;
		$config['per_page'] = 100;
		$config['uri_segment'] = 4;
		$config['full_tag_open'] = '<div class="table_pagination right">';
		$config['full_tag_close'] = '</div>';
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['num_links'] = 10;
		$this->pagination->initialize($config);
		$data['plinks'] = $this->pagination->create_links();
		$query = $this->db->query('SELECT i.id,i.path,i.thumb,i.home,i.uid,i.mailer,i.ismain,i.status
		FROM images AS i
		INNER JOIN users AS u ON i.uid = u.id
		WHERE u.gender = '.$gender.' AND 
		u.haspic = 1 AND 
		u.utype = "a"
		AND i.status != "3"
		ORDER BY i.uid DESC , i.status ASC 
		'.($limit != "na" ? 'LIMIT '.$limit.',100' : 'LIMIT 100')); 
		
		if($query->num_rows() > 0)
		{
			$images = $query->result();
		}
		else
		{
			$images = "na";
		}
		$data['images'] = $images;
		$data['gen'] = $gender;
		$this->load->view('all_app_images',$data);
	}
	
	function allimages()
	{
		$gender = $this->uri->segment(3);
		$limit = $this->uri->segment(4,'na');
		$this->load->library('pagination');
		$config['base_url'] = 'http://www.xxxxxx.com/5073/index.php/photos/allimages/'.$gender.'/';
		$query = $this->db->query('SELECT i.id
		FROM images AS i
		INNER JOIN users AS u ON i.uid = u.id
		WHERE u.gender = '.$gender.' AND 
		u.haspic = 1 AND 
		i.path NOT LIKE "%toon%" AND  
		i.status = 1
		ORDER BY i.id DESC ');
		$total_rows = $query->num_rows();
		$config['total_rows'] = $total_rows;
		$config['per_page'] = 100;
		$config['uri_segment'] = 4;
		$config['full_tag_open'] = '<div class="table_pagination right">';
		$config['full_tag_close'] = '</div>';
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['num_links'] = 10;
		$this->pagination->initialize($config);
		$data['plinks'] = $this->pagination->create_links();
		$query = $this->db->query('SELECT i.id,i.path,i.thumb,i.home,i.uid,i.mailer
		FROM images AS i
		INNER JOIN users AS u ON i.uid = u.id
		WHERE u.gender = '.$gender.' AND 
		u.haspic = 1 AND 
		i.path NOT LIKE "%toon%" AND  
		i.status = 1
		ORDER BY i.id DESC 
		'.($limit != "na" ? 'LIMIT '.$limit.',100' : 'LIMIT 100')); 
		
		if($query->num_rows() > 0)
		{
			$images = $query->result();
		}
		else
		{
			$images = "na";
		}
		$data['images'] = $images;
		$data['gen'] = $gender;
		$this->load->view('photos_choose',$data);
	}
	
	function chooseapp()
	{
		$gender = $this->uri->segment(3);
		$limit = $this->uri->segment(4,'na');
		$this->load->library('pagination');
		$config['base_url'] = 'http://www.xxxxxx.com/5073/index.php/photos/chooseapp/'.$gender.'/';
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `gender` = "'.$gender.'" AND `haspic` = "1" AND `picpath` != "" AND `utype` = "a"');
		$total_rows = $query->num_rows();
		$this->load->library('pagination');
		$config['total_rows'] = $total_rows;
		$config['per_page'] = 100;
		$config['uri_segment'] = 4;
		$config['full_tag_open'] = '<div class="table_pagination right">';
		$config['full_tag_close'] = '</div>';
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['num_links'] = 10;
		$this->pagination->initialize($config);
		$data['plinks'] = $this->pagination->create_links();
		$query = $this->db->query('SELECT `id`,`nickname`,`picpath` FROM `users` WHERE `gender` = "'.$gender.'" AND `haspic` = "1" AND `picpath` != "" AND `utype` = "a" ORDER BY `id` DESC '.($limit != "na" ? 'LIMIT '.$limit.',100' : 'LIMIT 100'));
		$images = $query->result();
		$data['images'] = $images;
		$data['gen'] = $gender;
		$this->load->view('photos_choose_app',$data);
	}
	
	function choose()
	{
		$gender = $this->uri->segment(3);
		$limit = $this->uri->segment(4,'na');
		$this->load->library('pagination');
		$config['base_url'] = 'http://www.xxxxxx.com/5073/index.php/photos/choose/'.$gender.'/';
		$query = $this->db->query('SELECT i.id
		FROM images AS i
		INNER JOIN users AS u ON i.uid = u.id
		WHERE u.gender = '.$gender.' AND 
		u.haspic = 1 AND 
		i.path NOT LIKE "%toon%" AND 
		i.ismain = 1 AND 
		i.status = 1
		ORDER BY i.id DESC ');
		$total_rows = $query->num_rows();
		$config['total_rows'] = $total_rows;
		$config['per_page'] = 100;
		$config['uri_segment'] = 4;
		$config['full_tag_open'] = '<div class="table_pagination right">';
		$config['full_tag_close'] = '</div>';
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['num_links'] = 10;
		$this->pagination->initialize($config);
		$data['plinks'] = $this->pagination->create_links();
		$query = $this->db->query('SELECT i.id,i.path,i.thumb,i.home,i.uid,i.mailer
		FROM images AS i
		INNER JOIN users AS u ON i.uid = u.id
		WHERE u.gender = '.$gender.' AND 
		u.haspic = 1 AND 
		i.path NOT LIKE "%toon%" AND 
		i.ismain = 1 AND 
		i.status = 1
		ORDER BY i.id DESC 
		'.($limit != "na" ? 'LIMIT '.$limit.',100' : 'LIMIT 100')); 
		
		if($query->num_rows() > 0)
		{
			$images = $query->result();
		}
		else
		{
			$images = "na";
		}
		$data['images'] = $images;
		$data['gen'] = $gender;
		$this->load->view('photos_choose',$data);
	}
	
	function domailer()
	{
		$gender = $_POST['gender']; // => 2
		$year = $_POST['year']; // => 2013
		$month = $_POST['month']; // => 03
		$day = $_POST['day']; // => 00
		$cols = $_POST['cols']; // => 4
		$rows = $_POST['rows']; // => 7
		$width = $_POST['width']; // => 120
		$height = $_POST['height']; // => 120
		// pull users
		$dt = $year;
		if($month == "")
		{
			$dt .= '-01';
		}
		else
		{
			$dt .= '-'.$month;
		}
		if($day == "")
		{
			$dt .= '-01';
		}
		else
		{
			$dt .= '-'.$day;
		}
		$dt .= ' 00:00:00';
		$lmt = $cols * $rows;
		$query = $this->db->query('SELECT i.id,i.path,i.home,i.mailer,u.id,u.url
		FROM images AS i
		INNER JOIN users AS u ON i.uid = u.id
		WHERE u.gender = '.$gender.' AND 
		u.haspic = 1 AND 
		u.joindate >= "'.$dt.'" AND
		i.path NOT LIKE "%toon%" AND 
		i.ismain = 1 AND 
		i.status = 1 AND 
		i.mailer = 1 AND
		(u.type = "normal" || u.type = "vip") ORDER BY RAND() LIMIT 0,'.$lmt);
		if($query->num_rows() > 0)
		{
			$users = $query->result();
		}
		else
		{
			$users = "na";
		}
		$data['users'] = $users;
		$data['pvars'] = $_POST;
		$this->load->view('photos_mailer_code',$data);
	}
	
	function pixfix()
	{
		$query = $this->db->query('SELECT `id` FROM `users`');
		$users = $query->result();
		foreach($users as $u)
		{
			// get current profile pic
			$query = $this->db->query('SELECT `path` FROM `images` WHERE `uid` = "'.$u->id.'" AND `ismain` = "1" AND `status` = "1" LIMIT 1');
			if($query->num_rows() > 0)
			{
				$row = $query->row();
				$query = $this->db->query('UPDATE `users` SET `haspic` = "1", `picpath` = "'.$row->path.'" WHERE `id` = "'.$u->id.'"');
				echo $u->id . ' has been moved <br />';
			}
		}
		
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */