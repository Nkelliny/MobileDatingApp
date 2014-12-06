<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_ContactsManager 
{	
	var $CI;
	function My_ContactsManager()
	{
		$CI =& get_instance();
		$CI->load->database();
		$CI->load->library('session');
		$CI->load->library('email');
	}
	
	function sortDataKeys()
	{
		// pull contacts list and store keys / values
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT * FROM `phone_contacts`');
		$contacts = $query->result();
		foreach($contacts as $c)
		{
			$clist = explode('||',$c->contacts);
			//print_r($clist);
			foreach($clist as $c)
			{
				$info = explode(',',$c);
				foreach($info as $key=>$value)
				{
					$pts = explode(':',$value);
					// check for existing key
					$query = $this->CI->db->query('SELECT `id` FROM `contact_keys` WHERE `key` = "'.mysql_real_escape_string($pts[0]).'"');
					if($query->num_rows > 0)
					{
						// already added do nothing
					}
					else
					{
						$query = $this->CI->db->query('INSERT INTO `contact_keys` (`id`,`key`,`value`,`type`) VALUES (NULL,"'.mysql_real_escape_string($pts[0]).'","'.mysql_real_escape_string(@$pts[1]).'","unknown")');
						echo "Key: " . $pts[0] . "added<br />";
					}
				}
			}
		}
		echo "Completed";
	}
	
	function getnumbers()
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `key` FROM `contact_keys` WHERE `type` = "phone"');
		$phone_key = $query->result();
		$phone_keys = array();
		foreach($phone_key as $p)
		{
			$phone_keys[] = $p->key;
		}
		$query = $this->CI->db->query('SELECT `key` FROM `contact_keys` WHERE `type` = "name"');
		$name_key = $query->result();
		$name_keys = array();
		foreach($name_key as $n)
		{
			$name_keys[] = $n->key;
		}
		// pull contact and grab numbers
		$query = $this->CI->db->query('SELECT `uid`,`contacts` FROM `phone_contacts` WHERE `contacts` != "(null)" AND `contacts` != "decline" AND `contacts` != ""');
		$contacts = $query->result();
		$a=0;
		foreach($contacts as $c)
		{
			// get contacts
			$clist = explode('||',$c->contacts);
			$vals = array();
			foreach($clist as $item)
			{
				$contact = explode(',',$item);
				//print_r($contact);
				$name = "";
				$number = array();
				// get name
				foreach($contact as $val)
				{
					$pts = explode(':',$val);
					if(in_array($pts[0],$name_keys) && $name == "")
					{
						$name = $pts[1];
					}
				}
				// get numbers
				foreach($contact as $val)
				{
					$pts = explode(':',$val);
					if(in_array($pts[0],$phone_keys))
					{
						$number[] = $pts[1];
					}
				}
				foreach($number as $n)
				{
					$str = str_replace('(','',$n);
					$str = str_replace(')','',$str);
					$str = str_replace('-','',$str);
					$str = str_replace(' ','',$str);
					$query = $this->CI->db->query('SELECT `id` FROM `contact_numbers` WHERE `number` = "'.mysql_real_escape_string($str).'"');
					if($query->num_rows() > 0)
					{
						// alread in db
					}
					else
					{
						$query = $this->CI->db->query('INSERT INTO `contact_numbers` (`id`,`owner`,`name`,`number`,`status`) VALUES (NULL,"'.$c->uid.'","'.mysql_real_escape_string($name).'","'.mysql_real_escape_string($str).'","3")');
						echo "Added: " . $name . ' Phone:'.$str.'<br />';
					}
				}
			}
			echo '<br />***********************************************************************************<br />';
		}
		echo 'Completed';
	}
	
}

?>