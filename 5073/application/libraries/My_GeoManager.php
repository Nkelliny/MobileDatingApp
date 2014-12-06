<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_GeoManager
{	
	var $CI;
	function My_GeoManager()
	{
		$CI =& get_instance();
		$CI->load->database();
	}
	
	function getCountries()
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `Country_str_code`,`Country_str_name` FROM `skadate_location_country` ORDER BY `Country_str_name`');
		$countries = $query->result();
		return $countries;
	}
	
	function getStates($country)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `Admin1_str_code`,`Admin1_str_name` FROM `skadate_location_state` WHERE `Country_str_code` = "'.$country.'" ORDER BY `Admin1_str_name`');
		$states = $query->result();
		return $states;
	}
	
	function getCitiesFromCountry($country)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `Feature_int_id`,`Feature_str_name` FROM `skadate_location_city` WHERE `Country_str_code` = "'.$country.'" ORDER BY `Feature_str_name`');
		$cities = $query->result();
		return $cities;
	}
	
	function getCities($country,$state)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `Feature_int_id`,`Feature_str_name` FROM `skadate_location_city` WHERE `Country_str_code` = "'.$country.'" AND `Admin1_str_code` = "'.$state.'" ORDER BY `Feature_str_name`');
		$cities = $query->result();
		return $cities;
	}
	
	function getGeoName($type,$value)
	{
		$this->CI =& get_instance();
		if($type == 'country')
		{
			$query = $this->CI->db->query('SELECT `Country_str_name` FROM `skadate_location_country` WHERE `Country_str_code` = "'.$value.'"');
			if($query->num_rows() > 0)
			{
				$row = $query->row();
				$name = $row->Country_str_name;
			}
			else
			{
				$name = "na";
			}
		}
		else if($type == 'state')
		{
			$query = $this->CI->db->query('SELECT `Admin1_str_name` FROM `skadate_location_state` WHERE `Admin1_str_code` = "'.$value.'"');
			if($query->num_rows() > 0)
			{
				$row = $query->row();
				$name = $row->Admin1_str_name;
			}
			else
			{
				$name = "na";
			}
		}
		else if($type == 'city')
		{
			$query = $this->CI->db->query('SELECT `Feature_str_name` FROM `skadate_location_city` WHERE `Feature_int_id` = "'.$value.'"');
			if($query->num_rows() > 0)
			{
				$row = $query->row();
				$name = $row->Feature_str_name;
			}
			else
			{
				$name = "na";
			}
		}
		return $name;
	}
	
	function getCitiesFromGeo($geoInfo)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `Feature_str_name`,`Feature_int_id` FROM `skadate_location_city` WHERE `Country_str_code` = "'.$geoInfo['cc'].'" ORDER BY `Feature_str_name`');
		if($query->num_rows() > 0)
		{
			$cities = $query->result();
		}
		else
		{
			$cities = "na";
		}
		return $cities;
	}
	
	function getUserGeoData($ip)
	{
		include($_SERVER['DOCUMENT_ROOT']."/application/libraries/geoip.inc");
		
		$gi = geoip_open($_SERVER['DOCUMENT_ROOT']."/application/libraries/GeoIP.dat",GEOIP_STANDARD);
		
		$tmp['cc'] = geoip_country_code_by_addr($gi, $ip);
		$tmp['cn'] = geoip_country_name_by_addr($gi, $ip);
		geoip_close($gi);
		return $tmp;
	}
}

?>