<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact extends CI_Controller 
{
	public function index()
	{
		$this->load->view('default_contact');
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
		
		redirect('/thanks','refresh');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */