<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Promos extends CI_Controller 
{
	public function index()
	{
		$query = $this->db->query('SELECT * FROM `promocodes` ORDER BY `type`');
		$promocodes = $query->result();
		$data['promos'] = $promocodes;
		$this->load->view('promo_codes_default',$data);
	}
	
	function addcodes()
	{
		$fname = $_POST['fname'];
    	$lname = $_POST['lname'];
    	$phone = $_POST['phone'];
    	$type = $_POST['type']; 
    	$ttl = $_POST['ttl'];
		for($a=0;$a<$ttl;$a++)
		{
			$code = self::genPromoCode();
			$query = $this->db->query('INSERT INTO `promocodes` 
			(`id`,`fname`,`lname`,`phone`,`code`,`type`,`used`,`status`) 
			VALUES 
			(NULL,"'.$fname.'","'.$lname.'","'.$phone.'","'.$code.'","'.$type.'","0","1")');
		}
		redirect('/promos','refresh');
	}
	
	function randomString()
	{
		$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVQXYZ';
		$randomString = '';
		for ($i = 0; $i < 6; $i++) 
		{
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
	
	function genPromoCode()
	{
		$ck = 'notok';
		while($ck == "notok")
		{
			$code = self::randomString();
			$query = $this->db->query('SELECT `id` FROM `promocodes` WHERE `code` = "'.$code.'"');
			if($query->num_rows() > 0)
			{
				$ck = "notok";
			}
			else
			{
				$ck = $code;
			}
		}
		return $code;
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */