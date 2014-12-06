<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Div extends CI_Controller 
{
	public function index()
	{
		$this->load->view('div_default');
	}
	
	function tj()
	{
		$query = $this->db->query('SELECT `url`,`title` FROM `pages` WHERE `status` = "1" AND `url` != "privacy-policy" AND `url` != "terms-of-use" ORDER BY `title`');
		$pages = $query->result();
		$data['pages'] = $pages;
		$this->load->view('div_final',$data);
	}
	
	function xxxx()
	{
		$this->load->view('div_home');
	}
	
	function featurers()
	{
		$this->load->view('div_featurers');
	}
	
	function faq()
	{
		$query = $this->db->query('SELECT * FROM `faq` WHERE `status` = "1" ORDER BY `category`,`question` ASC');
		$faqs = $query->result();
		$data['faqs'] = $faqs;
		$this->load->view('div_faq',$data);
	}
	
	function sync()
	{
		$this->load->view('div_sync');	
	}
	
	function contact()
	{
		$this->load->view('div_contact');
	}
	
	function csub()
	{
		$firstname = $this->security->xss_clean($_POST['firstname']);
		$email     = $this->security->xss_clean($_POST['email']);
		$subject   = $this->security->xss_clean($_POST['subject']);
		$message   = $this->security->xss_clean($_POST['message']);
		// add to database
		$query = $this->db->query('INSERT INTO `tj_mail` 
		(`id`,`firstname`,`email`,`subject`,`message`) 
		VALUES 
		(NULL,"'.mysql_real_escape_string($firstname).'","'.mysql_real_escape_string($email).'","'.mysql_real_escape_string($subject).'","'.mysql_real_escape_string($message).'")');
		
		// send mail
		$msg = "Firstname: " . $firstname . "\n";
		$msg .= "Email: " . $email . "\n";
		$msg .= "Subject: " . $subject . "\n";
		$msg .= "Message: " . $message . "\n";
		// Additional headers
		$headers .= 'To: xxxxxx Support <support@xxxxxx.com>' . "\r\n";
		$headers .= 'From: '.$firstname.' <'.$email.'>' . "\r\n";
		$headers .= "Reply-To: ".$firstname." <".$email."> \r\n";
		mail('support@xxxxxx.com','xxxxxx Contact Submit',$msg,$headers);
		
		redirect('/div/thanks','refresh');
	}
	
	function thanks()
	{
		$this->load->view('div_thanks');
	}
	
	function android()
	{
		$this->load->view('div_android');
	}
	
	function img()
	{
		$this->load->view('div_img');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */