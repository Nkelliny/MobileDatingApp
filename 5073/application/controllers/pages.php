<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages extends CI_Controller 
{
	public function index()
	{
		$query = $this->db->query('SELECT * FROM `pages` ORDER BY `title`');
		if($query->num_rows() > 0)
		{
			$pages = $query->result();
		}
		else
		{
			$pages = "na";
		}
		$data['pages'] = $pages;
		$this->load->view('pages_default',$data);
	}
	
	function edit()
	{
		$id = $this->uri->segment(3);
		$query = $this->db->query('SELECT * FROM `pages` WHERE `id` = "'.$id.'"');
		$data['pg'] = $query->row();
		$this->load->view('pages_edit',$data);
	}
	
	function doedit()
	{
		$mtitle = $_POST['mtitle']; // => Thai Dating Tips
    	$mdesc  = $_POST['mdesc']; // => Thai Dating Tips
    	$mkey   = $_POST['mkey']; // => thai dating,thailand,love,bangkok,single thai
    	$title  = $_POST['title']; // => Thai Dating Tips
    	$url    = $_POST['url']; // => thai-dating-tips
    	$h1     = $_POST['h1']; // => Thai Dating Tips
    	$h2     = $_POST['h2']; // => 
    	$h3     = $_POST['h3']; // => 
    	$body   = $_POST['body'];
		$pg_id  = $_POST['pg_id'];
		$query = $this->db->query('UPDATE `pages` SET
		`url` = "'.mysql_real_escape_string($url).'",
		`mtitle` = "'.mysql_real_escape_string($mtitle).'",
		`mdescription` = "'.mysql_real_escape_string($mdesc).'",
		`mkey` = "'.mysql_real_escape_string($mkey).'",
		`title` = "'.mysql_real_escape_string($title).'",
		`h1` = "'.mysql_real_escape_string($h1).'",
		`h2` = "'.mysql_real_escape_string($h2).'",
		`h3` = "'.mysql_real_escape_string($h3).'",
		`body` = "'.mysql_real_escape_string($body).'" WHERE `id` = "'.$pg_id.'"');
		redirect('/pages','refresh');
	}
	
	function add()
	{
		$this->load->view('pages_add');
	}
	
	function doadd()
	{
		$mtitle = $_POST['mtitle'];
		$mdesc  = $_POST['mdesc'];
		$mkey   = $_POST['mkey'];
		$title  = $_POST['title'];
		$url    = $_POST['url'];
		$h1     = $_POST['h1'];
		$h2     = $_POST['h2']; 
		$h3     = $_POST['h3'];
		$body   = $_POST['body'];
		$query = $this->db->query('INSERT INTO `pages` 
		(`id`,`url`,`mtitle`,`mdescription`,`mkey`,`title`,`h1`,`h2`,`h3`,`body`,`status`,`views`) 
		VALUES 
		(NULL,"'.$url.'","'.mysql_real_escape_string($mtitle).'","'.mysql_real_escape_string($mdesc).'","'.mysql_real_escape_string($mkey).'","'.mysql_real_escape_string($title).'","'.mysql_real_escape_string($h1).'","'.mysql_real_escape_string($h2).'","'.mysql_real_escape_string($h3).'","'.mysql_real_escape_string($body).'","1","0")');
		redirect('/pages','refresh'); 
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */