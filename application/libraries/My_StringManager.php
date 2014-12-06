<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_StringManager
{	
	var $CI;
	function My_StringManager()
	{
		$CI =& get_instance();
		$CI->load->database();
	}
	
	function genVerify($length = 14)
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) 
		{
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
	
	function getAppText($uid,$key)
	{
		$this->CI =& get_instance();
		// get current languages
		$langs = self::getlangs();
		$query = $this->CI->db->query('SELECT `lang` FROM `users` WHERE `id` = "'.$uid.'"');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			if(in_array(strtolower($row->lang),$langs))
			{
				$lang = strtolower($row->lang);
			}
			else
			{
				$lang = "en";
			}
			//if($lang != "en")
			//{
				//$lang = "en";
			//}
		}
		else
		{
			$lang = "en";
		}
		$fld = ($lang == "en" ? 'value' : $lang.'_value');
		$query = $this->CI->db->query('SELECT `'.$fld.'` FROM `app_lang` WHERE `key` = "'.$key.'"');
		$row = $query->row();
		$txt = $row->$fld;
		return $txt;
	}
	
	function getlangs()
	{
		$this->CI =& get_instance();
		$fields = $this->CI->db->list_fields('app_lang');
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
		return $langs;
	}
	
	function checkProfileChange($fld,$str,$uid)
	{
		$this->CI =& get_instance();
		if($fld == 'headline')
		{
			$query = $this->CI->db->query('SELECT `headline` FROM `users` WHERE `id` = "'.$uid.'"');
			$row = $query->row();
			if($str != $row->headline)
			{
				// user changed headline
				$tmp = "n";
			}
			else
			{
				$tmp = "y";
			}
		}
		else
		{
			$query = $this->CI->db->query('SELECT `'.$fld.'` FROM `profile_data` WHERE `uid` = "'.$uid.'"');
			$row = $query->row();
			if($str != $row->$fld)
			{
				$tmp = "n";
			}
			else
			{
				$tmp = "y";
			}
		}
		
		if($tmp == "n")
		{
			// mail admin
			$to  = 'xxxx@xxxx.com' . ', '; // note the comma
			$to .= ',support@xxxxxx.com';
			$subject = "PROFILE NEEDS APPROVED";
			$msg = "User has changed their ".$fld." \n\r";
			$msg .= "New Value is: \n\r";
			$msg .= $str . "\n\r";
			$msg .= "<a href='http://www.xxxxxx.com/5073/qupdate/autoapprove/".$fld."/".$uid."'>Click Here To Approve</a>\n\r";
			$msg .= "<a href='http://www.xxxxxx.com/5073/qupdate/dissaprove/".$fld."/".$uid."'>Click Here To Send User Email</a>";
			mail($to,$subject,$msg);
		}
		
		return $tmp;
	}
	
	function getSyncCode($udid)
	{
    	$s = strtolower(md5($udid)); 
    	$sync_code = substr($s,0,8); 
		return $sync_code;
	}
	
	function filterNick($name)
	{
		$msg = 'ok';
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT * FROM `bad_words` WHERE `id` = "1"');
		$row = $query->row();
		$badwords = explode(',',$row->bword_list);
		$query = $this->CI->db->query('SELECT * FROM `bad_words` WHERE `id` = "2"');
		$row = $query->row();
		$badnames = explode(',',$row->bword_list);
		foreach($badwords as $b)
		{
			$mystring = $name;
			$findme   = $b;
			$pos = strpos($mystring, $findme);
			if ($pos !== false) 
			{
				$msg = "Your nickname is not allowed, please try again.";
				break;
			}
		}
		foreach($badnames as $b)
		{
			$mystring = $name;
			$findme   = $b;
			$pos = strpos($mystring, $findme);
			if ($pos !== false) 
			{
				$msg = "Your nickname is not allowed, please try again.";
				break;
			}
		}
		return $msg;
	}
	
	function removeUnwanted($str)
	{
		// remove emails
		$pattern = "/[^@\s]*@[^@\s]*\.[^@\s]*/";
		$replacement = " **** ";
		$nstr = preg_replace($pattern, $replacement, $str);
		// remove urls
		$pattern = "/[a-zA-Z]*[:\/\/]*[A-Za-z0-9\-_]+\.+[A-Za-z0-9\.\/%&=\?\-_]+/i";
		$replacement = " **** ";
		$nstr = preg_replace($pattern, $replacement, $nstr);
		return $nstr;
	}
	
	function wordCleaner($strToFilter)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `bword_list` FROM `bad_words` WHERE `id` = "1"');
		$row = $query->row();
		$blist = explode(',',$row->bword_list);
		$nstr = $strToFilter;
		foreach($blist as $b)
		{
			$nstr = str_replace($b,'',$nstr);
		}
		$nstr = self::removeUnwanted($nstr);
		return $nstr;
	}
	
	function getBadWords()
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `bword_list` FROM `bad_words` WHERE `id` = "1"');
		$row = $query->row();
		$lst = $row->bword_list;
		return $lst;
	}
	
	function genPass($length = 6) 
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randomString = '';
		for ($i = 0; $i < $length; $i++) 
		{
			$randomString .= $characters[rand(0, strlen($characters) - 1)];
		}
		return $randomString;
	}
	
	function cleanForUrl($val)
	{
		$new_string = ereg_replace("[^A-Za-z0-9]", "-", $val);
		$new_string = strtolower($new_string);
		$str_length = strlen($new_string);
		$new_string = preg_replace('{(-)\1+}','$1',$new_string);
		$ck_string_length = strlen($new_string);
		if($ck_string_length > 100)
		{
			$new_string = substr($new_string,0,100);
		}
		$new_string = trim($new_string,'-');
		return $new_string;
	}
	
	function cm_ft($val) 
	{
    	$cms = str_replace('cm','',$val);
		$inches = $cms/2.54;
    	$feet = intval($inches/12);
    	$inches = $inches%12;
    	return sprintf('%d\' %d\'\'', $feet, $inches);
	}
	
	function kg_lb($val)
	{
		//70 kg  x  2.2 = 154 lbs
		$kg = str_replace('kg','',$val);
		$lbs = round($kg * 2.2);
		return $lbs."lbs";
	}
	
	function LinkKeywords($html,$killanchor=false,$default=false,$multiple=4)
	{	
		$this->CI =& get_instance();
		if ($killanchor)
		{
			$html = preg_replace("/<a([^>]+)>/i","",$html);
			$html = preg_replace("/<\/a>/i","",$html);
		}
		else
		{
			if (preg_match_all('/<a[^>]*href="http:\/\/([^"]+)"[^>]*>/Ui',$html,$matches, PREG_OFFSET_CAPTURE))
			{
				if (strpos($matches[1][0][0],$_SERVER['HTTP_HOST']) === false)
				{
					#look for target
					$anchor = $matches[0][0][0];
					$anchor = preg_replace('/target="[^"]+"/i','',$anchor);
					$anchor = str_replace('>',' target="_blank">',$anchor);
					$html = substr_replace($html,$anchor,$matches[0][0][1],strlen($matches[0][0][0]));
				}
			}
		}
	
		if (is_array($default))
		{
			foreach ($default as $keyword => $url)
			{
				$keyword = str_replace(" ","('s)?[\\s\\r\\n]+",$keyword);
				$keywords[] = str_replace("/","\/",$keyword);
				$urls[] = $url;
				$counts[] = $multiple;
			}
		}
		$editdb = 'default';
		$CI =& get_instance();
		$CI->$editdb = $CI->load->database($editdb, TRUE);
		$this->$editdb =& $CI->$editdb;
		$kwres = $this->$editdb->query("SELECT * FROM `linked_keywords` ORDER BY LENGTH(`word`) DESC");
		//$kwres = mysql_query("SELECT * FROM `rw_keywords` ORDER BY LENGTH(`keyword`) DESC");
		#Get keywords
		$line = $kwres->result();
		foreach($line as $l)
		{
			$l->word = str_replace(" ","('s)?[\\s\\r\\n]+",$l->word);
			$keywords[] = str_replace("/","\/",$l->word);
			$urls[] = $l->link;
			$counts[] = $multiple;
		}
	
		if (isset($keywords) && isset($urls))
		{
			while(true)
			{
				#find tag locations
				unset($start,$stop);
				$restart = false;
				preg_match_all("/(<a[^>]*>(.*)<\/a>)|(<h1[^>]*>(.*)<\/h1>)|(<strong[^>]*>(.*)<\/strong>)|(<[^>]+[\/]?>)/Ui",$html,$matches, PREG_OFFSET_CAPTURE);
				foreach ($matches[0] as $value)
				{
					$start[] = $value[1];
					$stop[] = strlen($value[0]) + $value[1];
				}
	
				#match keyword
				foreach ($keywords as $key => $keyword)
				{
					if($key)
					{	
						$keywords[$key-1] = '';
					}
					if ($keyword)
					{
						preg_match_all("/(\b{$keyword}('s)?\b)/i", $html, $matches, PREG_OFFSET_CAPTURE);
						foreach ($matches[1] as $value)
						{
							$pos = $value[1];
							$len = strlen($value[0]);
							$doreplace = true;
							if (isset($start) && isset($stop))
							{
								for ($i=0; $i<count($start); $i++)
								{
									if ($pos >= $start[$i] && $pos < $stop[$i])
									{
										$doreplace = false;
									}
								}
							}
							if ($doreplace)
							{
								$html = substr_replace($html,'<a href="' . $urls[$key] . '">' . $value[0] . '</a>',$pos,$len);
								$counts[$key]--;
								if ($counts[$key] == 0)
								{
									$keywords[$key] = ''; #Limits linked keyword
								}
								$restart = true;
								break 2;
							}
						}
					}
				}
				if (!$restart)
				{
					break;
				}
			}
		}
		//$lkck = self::linkfixer($html);
		return $html;
	}
}

?>