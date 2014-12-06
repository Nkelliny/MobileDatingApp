<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller 
{
	public function index()
	{
		$email  = $this->security->xss_clean($_POST['email']);
		$pass   = $this->security->xss_clean($_POST['pass']);
		$salt   = '4db689efccf22';
		$ckpass = sha1($salt.$pass);
		$query = $this->db->query('SELECT * FROM `users` WHERE `email` = "'.$email.'" AND `pass` = "'.$ckpass.'" AND `isadmin` = "1"');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$ndata = array
			(
				'uid'     => $row->id,
				'adauth'  => "yes"
			);
			$this->session->set_userdata($ndata);
			redirect(site_url(),'refresh');
		}
		else
		{
			echo 'invalid login';
		}
	}
	
	function logout()
	{
		$ndata = array
			(
				'uid'     => '0',
				'adauth'  => "no"
			);
			$this->session->set_userdata($ndata);
			redirect(site_url(),'refresh');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */