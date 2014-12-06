<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Export extends CI_Controller 
{
	public function index()
	{
		$this->load->view('export_mailer');
	}
	
	function mailer()
	{
		$gender = $_POST['gender']; // => 18
    	$year   = $_POST['year']; // => 2013
    	$month  = $_POST['month']; // => 03
    	$day    = $_POST['day']; // => 25
		if($gender == "0")
		{
			$gen_sql = '';
		}
		else
		{
			$gen_sql = ' AND `gender` = "'.$gender.'" ';
		}
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
		// get list
		$query = $this->db->query('SELECT `id`,`nickname`,`email`,`joindate`,`sync_code` FROM `users` WHERE `nomail` = "2" '.$gen_sql.' AND `joindate` >= "'.$dt.'" ORDER BY `id` DESC');
		$members = $query->result();
		$data = "nick name, email address, sex, sign up date, sync code\n";
		foreach($members as $m)
		{
			$data .= "{$m->nickname},{$m->email},".$this->my_usersmanager->getUserGenderTxt($m->id).",{$m->joindate},{$m->sync_code}\n";
		}
			header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
			header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
			header("Pragma: public");
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: public");
			header("Content-Type: application/octet-stream");
			header("Content-Disposition: attachment; filename=mailer.csv");
			header("Content-length: " . strlen($data));
			echo $data;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */