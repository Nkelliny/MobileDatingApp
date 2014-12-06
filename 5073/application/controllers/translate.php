<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Translate extends CI_Controller 
{
	public function index()
	{
		$this->load->view('translate_default');	
	}
	
	function addlang()
	{
		$lang = strtolower($_POST['lang']);
		/* tables to change 
		app_lang field value
		app_text fields subject,text
		pfileds field txt
		pfields_values field name
		*/
		$query = $this->db->query('ALTER TABLE `app_lang` ADD `'.$lang.'_value` VARCHAR( 255 ) after `value`');
		$query = $this->db->query('ALTER TABLE `app_text` ADD `'.$lang.'_subject` VARCHAR( 255 ) after `subject`');
		$query = $this->db->query('ALTER TABLE `app_text` ADD `'.$lang.'_text` VARCHAR( 255 ) after `text`');
		$query = $this->db->query('ALTER TABLE `pfields` ADD `'.$lang.'_txt` VARCHAR( 255 ) after `txt`');
		$query = $this->db->query('ALTER TABLE `pfields_values` ADD `'.$lang.'_name` VARCHAR( 255 ) after `name`');
		$this->session->set_flashdata('msg','A new language has been added to the database, you can now add the translations');
		redirect('/translate','refresh');
	}
	
	function updatetext()
	{
		$lang = $_POST['ls'];
		foreach($_POST['value'] as $key=>$value)
		{
			//echo $key . '=>' . $value.'<br />';
			// update value
			$fld = $lang.'_value';
			$query = $this->db->query('UPDATE `app_lang` SET `'.$fld.'` = "'.mysql_real_escape_string($value).'" WHERE `id` = "'.$key.'"');
		}
		redirect('translate/iap','refresh');
	}
	
	function pfields()
	{
		$fields = $this->db->list_fields('app_lang');
		$langs = array();
		foreach($fields as $field)
		{
			if($field != "id" && $field != "name" && $field != "key")
			{
				if($field == "value")
				{
					$langs[] = "en";
				}
				else
				{
					$langs[] = str_replace('_value','',$field);
				}
			}
		}
		$data['langs'] = $langs;
		$ls = '';
		if(isset($_POST['ls']))
		{
			$ls = $_POST['ls'];
		}
		$data['ls'] = $ls;
		if($ls != "")
		{
			$query = $this->db->query('SELECT * FROM `pfields` WHERE `status` = "1" AND `mobile` = "y" ORDER BY `name`');
			$lbls = $query->result();
			$labels = array();
			$fld = $ls.'_txt';
			foreach($lbls as $l)
			{
				$tmp->id = $l->id;
				$tmp->name = $l->name;
				$tmp->txt = $l->$fld;
				$labels[] = $tmp;
				unset($tmp);
			}
			$data['plabels'] = $labels;
			$values = array();
			foreach($lbls as $l)
			{
				$query = $this->db->query('SELECT * FROM `pfields_values` WHERE `status` = "1" AND `fid` = "'.$l->id.'" ORDER BY `name`');
				$vals = $query->result();
				$fld = $ls.'_name';
				foreach($vals as $v)
				{
					$tmp->id = $v->id;
					$tmp->name = $v->name;
					$tmp->value = $v->$fld;
					$values[] = $tmp;
					unset($tmp,$vals);
				}
			}
			$data['pfields'] = $values;
		}
		else
		{
			$data['plabels'] = "na";
			$data['pfields'] = "na";
		}
		$this->load->view('pfields_translate',$data);
 	}
	
	function iap()
	{
		$lang_select = '';
		if(isset($_POST['lang']))
		{
			$lang_select = $_POST['lang'];
		}
		$data['ls'] = $lang_select;
		$fields = $this->db->list_fields('app_lang');
		$langs = array();
		foreach ($fields as $field)
		{
	   		if($field != "id" && $field != "name" && $field != "key")
			{
				if($field == "value")
				{
					$langs[] = "en";
				}
				else
				{
					$langs[] = str_replace('_value','',$field);
				}
			}
		}
		$data['langs'] = $langs;
		if($lang_select != "")
		{
			$fld = $lang_select.'_value';
			$query = $this->db->query('SELECT * FROM `app_lang` ORDER BY `key`');
			$res = $query->result();
			$values = array();
			foreach($res as $r)
			{
				$tmp->id = $r->id;
				$tmp->name = $r->name;
				$tmp->key = $r->key;
				$tmp->value = $r->$fld;
				$values[] = $tmp;
				unset($tmp);
			}
			$data['texts'] = $values;
		}
		else
		{
			$data['texts'] = '0';
		}
		//$query = $this->db->query('SELECT * FROM `app_lang` ORDER BY `key`');
		//if($query->num_rows() > 0)
		//{
			//$texts = $query->result();
		//}
		//else
		//{
			//$texts = "na";
		//}
		//$data['texts'] = $texts;
		$this->load->view('translate_app_texts_show',$data);
	}
	
	function updatepfields()
	{
		$ls = $_POST['ls'];// => th
    	$vals = $_POST['val'];
		$fld = $ls.'_name';
		foreach($vals as $key=>$value)
		{
			$query = $this->db->query('UPDATE `pfields_values` SET `'.$fld.'` = "'.mysql_real_escape_string($value).'" WHERE `id` = "'.$key.'"');
		}
		redirect('translate/pfields','refresh');
	}
	
	function updateplables()
	{
		$ls = $_POST['ls']; // => th
    	$vals = $_POST['val'];
		$fld = $ls.'_txt';
		foreach($vals as $key=>$value)
		{
			$query = $this->db->query('UPDATE `pfields` SET `'.$fld.'` = "'.mysql_real_escape_string($value).'" WHERE `id` = "'.$key.'"');
		}
		redirect('translate/pfields','refresh');
	}
	
	function addtext()
	{
		//[name] => Test
		//[key] => test
		//[value] => Test English value
		//[th_value] => Test Th Value
		//[addLang] => Add New Text
		$fld_sql = '';
		$val_sql = '';
		$x=1;
		$flds = (count($_POST) - 3);
		foreach($_POST as $key => $value)
		{
			// set fields sql
			if($key != "name" && $key != "add_key" && $key != "addLang") 
			{
				$fld_sql .= '`'.$key.'`';
				$val_sql .= '"'.mysql_real_escape_string($value).'"';
				if($x != $flds)
				{
					$fld_sql .= ',';
					$val_sql .= ',';
					$x++;
				}
			}
		}
		// set values sql
		$sql = 'INSERT INTO `app_lang` (`id`,`name`,`key`,'.$fld_sql.') VALUES (NULL,"'.mysql_real_escape_string($_POST['name']).'","'.mysql_real_escape_string($_POST['add_key']).'",'.$val_sql.')';
		//echo $sql;
		$query = $this->db->query($sql);
		redirect('/translate/iap');
	}
	
	/*
	function pfields()
	{
		$query = $this->db->query('SELECT * FROM `pfields` ORDER BY `name`');
		$data['pfields'] = $query->result();
		$this->load->view('translate_pfields',$data);
	}
	*/
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */