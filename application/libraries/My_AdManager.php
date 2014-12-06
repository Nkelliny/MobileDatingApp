<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My_AdManager 
{	
	var $CI;
	function My_AdManager()
	{
		$CI =& get_instance();
		$CI->load->database();
	}
	
	function show($size,$poss)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT * FROM `advert` WHERE `size` = "'.$size.'" AND `poss` = "'.$poss.'" AND `status` = "1" ORDER BY `imp` ASC');
		if($query->num_rows() > 0)
		{
			$info = $query->row();
			$query = $this->CI->db->query('UPDATE `advert` SET `imp` = imp + 1 WHERE `id` = "'.$info->id.'"');
			if($info->type == "image")
			{
				$pts = explode('x',$info->size);
				$ad = '<a href="/imgview/click/'.$info->id.'" target="_blank">
				<img src="'.$info->path.'" width="'.$pts[0].'" height="'.$pts[1].'" alt="" title="" border="0" />
				</a>';
			}
			else if($info->type == "code")
			{
				$ad = $info->path;
			}
		}
		else
		{
			$ad = "na";
		}
		return $ad;
	}
	
	function click($id)
	{
		$this->CI =& get_instance();
		$query = $this->CI->db->query('SELECT `outlink` FROM `advert` WHERE `id` = "'.$adid.'"');
		$row = $query->row();
		$outlink = $row->outlink;
		$query = $this->CI->db->query('UPDATE `advert` SET `clicks` = clicks + 1 WHERE `id` = "'.$adid.'"');
		redirect($outlink,'refresh');
	}
}
?>