<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pfields extends CI_Controller 
{
	public function index()
	{
		$query = $this->db->query('SELECT * FROM `pfields` ORDER BY `name`');
		$fields = $query->result();
		$data['flds'] = $fields;
		$this->load->view('pfields_default',$data);
	}
	
	function addfld()
	{
		$name = $_POST['fld_name'];    	
		$text = $_POST['fld_txt']; // => Relationship
		$query = $this->db->query('INSERT INTO `pfields` (`id`,`name`,`txt`,`status`) VALUES (NULL,"'.mysql_real_escape_string($name).'","'.mysql_real_escape_string($text).'","1")');
		redirect('/pfields','refresh');
	}
	
	function edit()
	{
		$id = $this->uri->segment(3);
		$query = $this->db->query('SELECT * FROM `pfields` WHERE `id` = "'.$id.'"');
		$field = $query->row();
		$query = $this->db->query('SELECT * FROM `pfields_values` WHERE `fid` = "'.$id.'"');
		if($query->num_rows() > 0)
		{
			$values = $query->result();
		}
		else
		{
			$values = "na";
		}
		$data['field'] = $field;
		$data['values'] = $values;
		$this->load->view('pfields_edit',$data);
	}
	
	function doedit()
	{
		$field_id = $_POST['id']; // => 1
    	$field_name = $_POST['fld_name']; // => Relationship
    	$field_txt = $_POST['fld_txt']; // => Relationship
    	$query = $this->db->query('UPDATE `pfields` SET `name` = "'.mysql_real_escape_string($field_name).'", `txt` = "'.mysql_real_escape_string($field_txt).'" WHERE `id` = "'.$field_id.'"');
		if(@is_array($_POST['cval']))
		{
			foreach($_POST['cval'] as $key=>$value)
			{
				$query = $this->db->query('UPDATE `pfields_values` SET `name` = "'.mysql_real_escape_string($value).'" WHERE `id` = "'.$key.'"');
			}
		}
		if(@is_array($_POST['val']))
		{
			foreach($_POST['val'] as $v)
			{
				if($v != "")
				{
					$query = $this->db->query('INSERT INTO `pfields_values` (`id`,`fid`,`name`) VALUES (NULL,"'.$field_id.'","'.mysql_real_escape_string($v).'")');
				}
			}
		}
		redirect('/pfields','refresh');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */