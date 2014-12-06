<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Etemps extends CI_Controller 
{
	public function index()
	{
		$query = $this->db->query('SELECT `id`,`name` FROM `mail_templates` ORDER BY `name`');
		$data['templates'] = $query->result();
		$this->load->view('email_templates_default',$data);
	}
	
	function add()
	{
		$default_data = '<table align="center" border="0" cellpadding="0" cellspacing="0" height="275" width="610">
		<tbody>
		<tr>
		<td><img height="68" src="{%%headerpic%%}" width="211" /></td>
		</tr>
		<tr>
		<td><img height="8" src="http://dev2.xxxxxx.com/images/design/email_top_grey.jpg" width="610" /></td>
		</tr>
		<tr>
		<td><img align="left" height="75" hspace="5" src="http://dev2.xxxxxx.com{%%picfrom%%}" vspace="5" width="75" />Subject: {%%subject%%}<br />
		Sender: {%%msgfrom%%}<br />
		{%%datesent%%}</td>
		</tr>
		<tr>
		<td>&nbsp;
		<table align="center" border="1" cellpadding="2" cellspacing="2" width="90%">
		<tbody>
		<tr>
		<td>ADD MESSAGE HERE</td>
		</tr>
		</tbody>
		</table>
		</td>
		</tr>
		<tr>
		<td><br />
		{%%retlink%%}</td>
		</tr>
		</tbody>
		</table>';
		$data['default_data'] = $default_data;
		$this->load->view('email_templates_add',$data);
	}
	
	function doadd()
	{
		$name = $_POST['name'];
    	$subject = $_POST['subject'];
    	$body = $_POST['body'];
		$query = $this->db->query('INSERT INTO `mail_templates` 
		(`id`,`name`,`subject`,`body`) 
		VALUES 
		(NULL,"'.mysql_real_escape_string($name).'","'.mysql_real_escape_string($subject).'","'.mysql_real_escape_string($body).'")');
		redirect('etemps','refresh');
	}
	
	function edit()
	{
		$id = $this->uri->segment(3);
		$query = $this->db->query('SELECT * FROM `mail_templates` WHERE `id` = "'.$id.'"');
		$data['template'] = $query->row();
		$this->load->view('email_templates_edit',$data);
	}
	
	function doedit()
	{
		$query = $this->db->query('UPDATE `mail_templates` SET `name` = "'.mysql_real_escape_string($_POST['name']).'", `subject` = "'.mysql_real_escape_string($_POST['subject']).'", `body` = "'.mysql_real_escape_string($_POST['body']).'" WHERE `id` = "'.$_POST['id'].'"');
		redirect('etemps','refresh');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */