<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller 
{
	public function index()
	{
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17"');
		$total_girls = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18"');
		$total_guys = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19"');
		$total_ladyboys = $query->num_rows();
		$data['tgirls'] = $total_girls;
		$data['tguys'] = $total_guys;
		$data['tladyboys'] = $total_ladyboys;
		// pro status
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17"');
		$total_girls = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18"');
		$total_guys = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19"');
		$total_ladyboys = $query->num_rows();
		$data['tgirls_pro'] = $total_girls;
		$data['tguys_pro'] = $total_guys;
		$data['tladyboys_pro'] = $total_ladyboys;
		
		// get active last hour
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `lastactivity` > "'.strtotime('-1 Hour').'"');
		$total_active_girls = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `lastactivity` > "'.strtotime('-1 Hour').'"');
		$total_active_guys = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `lastactivity` > "'.strtotime('-1 Hour').'"');
		$total_active_ladyboys = $query->num_rows();
		$data['tagirlshr'] = $total_active_girls;
		$data['taguyshr'] = $total_active_guys;
		$data['taladyboyshr'] = $total_active_ladyboys;
		// get highest ever free
		$query = $this->db->query('SELECT `value` FROM `settings` WHERE `id` = "3"');
		$row = $query->row();
		$data['highest_free_hour'] = $row->value;
		
		// get active last hour pro
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `lastactivity` > "'.strtotime('-1 Hour').'"');
		$total_active_girls = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `lastactivity` > "'.strtotime('-1 Hour').'"');
		$total_active_guys = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `lastactivity` > "'.strtotime('-1 Hour').'"');
		$total_active_ladyboys = $query->num_rows();
		$data['tagirlshr_pro'] = $total_active_girls;
		$data['taguyshr_pro'] = $total_active_guys;
		$data['taladyboyshr_pro'] = $total_active_ladyboys;
		
		// get highest ever pro
		$query = $this->db->query('SELECT `value` FROM `settings` WHERE `id` = "4"');
		$row = $query->row();
		$ttl = ($total_active_girls + $total_active_guys + $total_active_ladyboys);
		$data['highest_free_hour_pro'] = $row->value;
		
		// get active 24
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `lastactivity` > "'.strtotime('-24 Hours').'"');
		$total_active_girls = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `lastactivity` > "'.strtotime('-24 Hours').'"');
		$total_active_guys = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `lastactivity` > "'.strtotime('-24 Hours').'"');
		$total_active_ladyboys = $query->num_rows();
		$data['tagirls'] = $total_active_girls;
		$data['taguys'] = $total_active_guys;
		$data['taladyboys'] = $total_active_ladyboys;
		$ttl = $total_active_girls + $total_active_guys + $total_active_ladyboys;
		$query = $this->db->query('SELECT `value` FROM `settings` WHERE `id` = "1"');
		$row = $query->row();
		if($ttl > $row->value)
		{
			$data['highest_24'] = $ttl;
			$query = $this->db->query('UPDATE `settings` SET `value` = "'.$row->value.'" WHERE `id` = "1"');
		}
		else
		{
			$data['highest_24'] = $row->value;
		}
		
		// get active 24 pro
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `lastactivity` > "'.strtotime('-24 Hours').'"');
		$total_active_girls = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `lastactivity` > "'.strtotime('-24 Hours').'"');
		$total_active_guys = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `lastactivity` > "'.strtotime('-24 Hours').'"');
		$total_active_ladyboys = $query->num_rows();
		$data['tagirls_pro'] = $total_active_girls;
		$data['taguys_pro'] = $total_active_guys;
		$data['taladyboys_pro'] = $total_active_ladyboys;
		$ttl = $total_active_girls + $total_active_guys + $total_active_ladyboys;
		$query = $this->db->query('SELECT `value` FROM `settings` WHERE `id` = "2"');
		$row = $query->row();
		if($ttl > $row->value)
		{
			$data['highest_24_pro'] = $ttl;
			$query = $this->db->query('UPDATE `settings` SET `value` = "'.$ttl.'" WHERE `id` = "1"');
		}
		else
		{
			$data['highest_24_pro'] = $row->value;
		}
		
		// get seeking men
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `seeking` = "17"');
		$msw = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `seeking` = "18"');
		$msm = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND  `seeking` = "19"');
		$msl = $query->num_rows();
		$data['men_seeking_men'] = $msm;
		$data['men_seeking_women'] = $msw;
		$data['men_seeking_ladyboy'] = $msl;
		
		// get seeking men pro
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `seeking` = "17"');
		$msw = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `seeking` = "18"');
		$msm = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND  `seeking` = "19"');
		$msl = $query->num_rows();
		$data['men_seeking_men_pro'] = $msm;
		$data['men_seeking_women_pro'] = $msw;
		$data['men_seeking_ladyboy_pro'] = $msl;
		
		// get seeking women
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `seeking` = "17"');
		$wsw = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `seeking` = "18"');
		$wsm = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND  `seeking` = "19"');
		$wsl = $query->num_rows();
		$data['w_seeking_men'] = $wsm;
		$data['w_seeking_women'] = $wsw;
		$data['w_seeking_ladyboy'] = $wsl;
		
		// get seeking women pro
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `seeking` = "17"');
		$wsw = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `seeking` = "18"');
		$wsm = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND  `seeking` = "19"');
		$wsl = $query->num_rows();
		$data['w_seeking_men_pro'] = $wsm;
		$data['w_seeking_women_pro'] = $wsw;
		$data['w_seeking_ladyboy_pro'] = $wsl;
		
		// get seeking lady boy
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `seeking` = "17"');
		$lsw = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `seeking` = "18"');
		$lsm = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND  `seeking` = "19"');
		$lsl = $query->num_rows();
		$data['l_seeking_men'] = $lsm;
		$data['l_seeking_women'] = $lsw;
		$data['l_seeking_ladyboy'] = $lsl;
		
		// get seeking lady boy pro
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `seeking` = "17"');
		$lsw = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `seeking` = "18"');
		$lsm = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND  `seeking` = "19"');
		$lsl = $query->num_rows();
		$data['l_seeking_men_pro'] = $lsm;
		$data['l_seeking_women_pro'] = $lsw;
		$data['l_seeking_ladyboy_pro'] = $lsl;
		
		// join stats
		$today = date('Y-m-d',time());
		$month = date('Y-m-',time());
		$day_1 = strtotime($today.'0:00:01');
		$day_2 = strtotime($today.'0:00:01'.' -1 Day');
		$day_2_day = date('Y-m-d',$day_2);
		$day_3 = strtotime($today.'0:00:01'.' -2 Days');
		$day_3_day = date('Y-m-d',$day_3);
		$day_4 = strtotime($today.'0:00:01'.' -3 Days');
		$day_4_day = date('Y-m-d',$day_4);
		$day_5 = strtotime($today.'0:00:01'.' -4 Days');
		$day_5_day = date('Y-m-d',$day_5);
		$day_6 = strtotime($today.'0:00:01'.' -5 Days');
		$day_6_day = date('Y-m-d',$day_6);
		$day_7 = strtotime($today.'0:00:01'.' -6 Days');
		$day_7_day = date('Y-m-d',$day_7);
		
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `joindate` LIKE "'.$today.'%"'); 
		$day_1_f_j = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `joindate` LIKE "'.$today.'%"'); 
		$day_1_m_j = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `joindate` LIKE "'.$today.'%"'); 
		$day_1_l_j = $query->num_rows();
		$data['day_1'] = date('D (m-d)',$day_1);
		$data['day_1_f_j'] = $day_1_f_j;
		$data['day_1_m_j'] = $day_1_m_j;
		$data['day_1_l_j'] = $day_1_l_j;
		
		// day 1 pro
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `joindate` LIKE "'.$today.'%"'); 
		$day_1_f_j = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `joindate` LIKE "'.$today.'%"'); 
		$day_1_m_j = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `joindate` LIKE "'.$today.'%"'); 
		$day_1_l_j = $query->num_rows();
		$data['day_1'] = date('D (m-d)',$day_1);
		$data['day_1_f_j_pro'] = $day_1_f_j;
		$data['day_1_m_j_pro'] = $day_1_m_j;
		$data['day_1_l_j_pro'] = $day_1_l_j;
		
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `joindate` LIKE "'.$day_2_day.'%"'); 
		$day_2_f_j = $query->num_rows();
		$data['day_2_f_j'] = $day_2_f_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `joindate` LIKE "'.$day_2_day.'%"'); 
		$day_2_m_j = $query->num_rows();
		$data['day_2_m_j'] = $day_2_m_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `joindate` LIKE "'.$day_2_day.'%"'); 
		$day_2_l_j = $query->num_rows();
		$data['day_2_l_j'] = $day_2_l_j;
		
		// day 2 pro
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `joindate` LIKE "'.$day_2_day.'%"'); 
		$day_2_f_j = $query->num_rows();
		$data['day_2_f_j_pro'] = $day_2_f_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `joindate` LIKE "'.$day_2_day.'%"'); 
		$day_2_m_j = $query->num_rows();
		$data['day_2_m_j_pro'] = $day_2_m_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `joindate` LIKE "'.$day_2_day.'%"'); 
		$day_2_l_j = $query->num_rows();
		$data['day_2_l_j_pro'] = $day_2_l_j;
		
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `joindate` LIKE "'.$day_3_day.'%"'); 
		$day_3_f_j = $query->num_rows();
		$data['day_3_f_j'] = $day_3_f_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `joindate` LIKE "'.$day_3_day.'%"'); 
		$day_3_m_j = $query->num_rows();
		$data['day_3_m_j'] = $day_3_m_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `joindate` LIKE "'.$day_3_day.'%"'); 
		$day_3_l_j = $query->num_rows();
		$data['day_3_l_j'] = $day_3_l_j;
		
		// day three pro
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `joindate` LIKE "'.$day_3_day.'%"'); 
		$day_3_f_j = $query->num_rows();
		$data['day_3_f_j_pro'] = $day_3_f_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `joindate` LIKE "'.$day_3_day.'%"'); 
		$day_3_m_j = $query->num_rows();
		$data['day_3_m_j_pro'] = $day_3_m_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `joindate` LIKE "'.$day_3_day.'%"'); 
		$day_3_l_j = $query->num_rows();
		$data['day_3_l_j_pro'] = $day_3_l_j;
		
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `joindate` LIKE "'.$day_4_day.'%"'); 
		$day_4_f_j = $query->num_rows();
		$data['day_4_f_j'] = $day_4_f_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `joindate` LIKE "'.$day_4_day.'%"'); 
		$day_4_m_j = $query->num_rows();
		$data['day_4_m_j'] = $day_4_m_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `joindate` LIKE "'.$day_4_day.'%"'); 
		$day_4_l_j = $query->num_rows();
		$data['day_4_l_j'] = $day_4_l_j;
		
		// day 4 pro
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `joindate` LIKE "'.$day_4_day.'%"'); 
		$day_4_f_j = $query->num_rows();
		$data['day_4_f_j_pro'] = $day_4_f_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `joindate` LIKE "'.$day_4_day.'%"'); 
		$day_4_m_j = $query->num_rows();
		$data['day_4_m_j_pro'] = $day_4_m_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `joindate` LIKE "'.$day_4_day.'%"'); 
		$day_4_l_j = $query->num_rows();
		$data['day_4_l_j_pro'] = $day_4_l_j;
		
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `joindate` LIKE "'.$day_5_day.'%"'); 
		$day_5_f_j = $query->num_rows();
		$data['day_5_f_j'] = $day_5_f_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `joindate` LIKE "'.$day_5_day.'%"'); 
		$day_5_m_j = $query->num_rows();
		$data['day_5_m_j'] = $day_5_m_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `joindate` LIKE "'.$day_5_day.'%"'); 
		$day_5_l_j = $query->num_rows();
		$data['day_5_l_j'] = $day_5_l_j;
		
		// day five pro
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `joindate` LIKE "'.$day_5_day.'%"'); 
		$day_5_f_j = $query->num_rows();
		$data['day_5_f_j_pro'] = $day_5_f_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `joindate` LIKE "'.$day_5_day.'%"'); 
		$day_5_m_j = $query->num_rows();
		$data['day_5_m_j_pro'] = $day_5_m_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `joindate` LIKE "'.$day_5_day.'%"'); 
		$day_5_l_j = $query->num_rows();
		$data['day_5_l_j_pro'] = $day_5_l_j;
		
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `joindate` LIKE "'.$day_6_day.'%"'); 
		$day_6_f_j = $query->num_rows();
		$data['day_6_f_j'] = $day_6_f_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `joindate` LIKE "'.$day_6_day.'%"'); 
		$day_6_m_j = $query->num_rows();
		$data['day_6_m_j'] = $day_6_m_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `joindate` LIKE "'.$day_6_day.'%"'); 
		$day_6_l_j = $query->num_rows();
		$data['day_6_l_j'] = $day_6_l_j;
		
		// day six pro
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `joindate` LIKE "'.$day_6_day.'%"'); 
		$day_6_f_j = $query->num_rows();
		$data['day_6_f_j_pro'] = $day_6_f_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `joindate` LIKE "'.$day_6_day.'%"'); 
		$day_6_m_j = $query->num_rows();
		$data['day_6_m_j_pro'] = $day_6_m_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `joindate` LIKE "'.$day_6_day.'%"'); 
		$day_6_l_j = $query->num_rows();
		$data['day_6_l_j_pro'] = $day_6_l_j;
		
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `joindate` LIKE "'.$day_7_day.'%"'); 
		$day_7_f_j = $query->num_rows();
		$data['day_7_f_j'] = $day_7_f_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `joindate` LIKE "'.$day_7_day.'%"'); 
		$day_7_m_j = $query->num_rows();
		$data['day_7_m_j'] = $day_7_m_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `joindate` LIKE "'.$day_7_day.'%"'); 
		$day_7_l_j = $query->num_rows();
		$data['day_7_l_j'] = $day_7_l_j;
		
		// day seven pro
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `joindate` LIKE "'.$day_7_day.'%"'); 
		$day_7_f_j = $query->num_rows();
		$data['day_7_f_j_pro'] = $day_7_f_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `joindate` LIKE "'.$day_7_day.'%"'); 
		$day_7_m_j = $query->num_rows();
		$data['day_7_m_j_pro'] = $day_7_m_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `joindate` LIKE "'.$day_7_day.'%"'); 
		$day_7_l_j = $query->num_rows();
		$data['day_7_l_j_pro'] = $day_7_l_j;
		
		// monthly stats
		$today = date('Y-m-d',time());
		$month = date('Y-m-',time());
		$month_1 = strtotime($today.'0:00:01');
		$month_2 = strtotime($today.'0:00:01'.' -1 Month');
		$month_2_month = date('Y-m-',$month_2);
		$month_3 = strtotime($today.'0:00:01'.' -2 Months');
		$month_3_month = date('Y-m-',$month_3);
		$month_4 = strtotime($today.'0:00:01'.' -3 Months');
		$month_4_month = date('Y-m-',$month_4);
		$month_5 = strtotime($today.'0:00:01'.' -4 Months');
		$month_5_month = date('Y-m-',$month_5);
		$month_6 = strtotime($today.'0:00:01'.' -5 Months');
		$month_6_month = date('Y-m-',$month_6);
		$month_7 = strtotime($today.'0:00:01'.' -6 Months');
		$month_7_month = date('Y-m-',$month_7);
		
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `joindate` LIKE "'.$month.'%"'); 
		$m_1_f_j = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `joindate` LIKE "'.$month.'%"'); 
		$m_1_m_j = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `joindate` LIKE "'.$month.'%"'); 
		$m_1_l_j = $query->num_rows();
		//$data['m_1'] = date('D (m-d)',$month_1);
		$data['m_1_f_j'] = $m_1_f_j;
		$data['m_1_m_j'] = $m_1_m_j;
		$data['m_1_l_j'] = $m_1_l_j;
		
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `joindate` LIKE "'.$month_2_month.'%"'); 
		$m_2_f_j = $query->num_rows();
		$data['m_2_f_j'] = $m_2_f_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `joindate` LIKE "'.$month_2_month.'%"'); 
		$m_2_m_j = $query->num_rows();
		$data['m_2_m_j'] = $m_2_m_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `joindate` LIKE "'.$month_2_month.'%"'); 
		$m_2_l_j = $query->num_rows();
		$data['m_2_l_j'] = $m_2_l_j;
		
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `joindate` LIKE "'.$month_3_month.'%"'); 
		$m_3_f_j = $query->num_rows();
		$data['m_3_f_j'] = $m_3_f_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `joindate` LIKE "'.$month_3_month.'%"'); 
		$m_3_m_j = $query->num_rows();
		$data['m_3_m_j'] = $m_3_m_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `joindate` LIKE "'.$month_3_month.'%"'); 
		$m_3_l_j = $query->num_rows();
		$data['m_3_l_j'] = $m_3_l_j;
		
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `joindate` LIKE "'.$month_4_month.'%"'); 
		$m_4_f_j = $query->num_rows();
		$data['m_4_f_j'] = $m_4_f_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `joindate` LIKE "'.$month_4_month.'%"'); 
		$m_4_m_j = $query->num_rows();
		$data['m_4_m_j'] = $m_4_m_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `joindate` LIKE "'.$month_4_month.'%"'); 
		$m_4_l_j = $query->num_rows();
		$data['m_4_l_j'] = $m_4_l_j;
		
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `joindate` LIKE "'.$month_5_month.'%"'); 
		$m_5_f_j = $query->num_rows();
		$data['m_5_f_j'] = $m_5_f_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `joindate` LIKE "'.$month_5_month.'%"'); 
		$m_5_m_j = $query->num_rows();
		$data['m_5_m_j'] = $m_5_m_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `joindate` LIKE "'.$month_5_month.'%"'); 
		$m_5_l_j = $query->num_rows();
		$data['m_5_l_j'] = $m_5_l_j;
		
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `joindate` LIKE "'.$month_6_month.'%"'); 
		$m_6_f_j = $query->num_rows();
		$data['m_6_f_j'] = $m_6_f_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `joindate` LIKE "'.$month_6_month.'%"'); 
		$m_6_m_j = $query->num_rows();
		$data['m_6_m_j'] = $m_6_m_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `joindate` LIKE "'.$month_6_month.'%"'); 
		$m_6_l_j = $query->num_rows();
		$data['m_6_l_j'] = $m_6_l_j;
		
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `joindate` LIKE "'.$month_7_month.'%"'); 
		$m_7_f_j = $query->num_rows();
		$data['m_7_f_j'] = $m_7_f_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `joindate` LIKE "'.$month_7_month.'%"'); 
		$m_7_m_j = $query->num_rows();
		$data['m_7_m_j'] = $m_7_m_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` != "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `joindate` LIKE "'.$month_7_month.'%"'); 
		$m_7_l_j = $query->num_rows();
		$data['m_7_l_j'] = $m_7_l_j;
		
		// monthly pro
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `joindate` LIKE "'.$month.'%"'); 
		$m_1_f_j = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `joindate` LIKE "'.$month.'%"'); 
		$m_1_m_j = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `joindate` LIKE "'.$month.'%"'); 
		$m_1_l_j = $query->num_rows();
		//$data['m_1'] = date('D (m-d)',$month_1);
		$data['m_1_f_j_pro'] = $m_1_f_j;
		$data['m_1_m_j_pro'] = $m_1_m_j;
		$data['m_1_l_j_pro'] = $m_1_l_j;
		
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `joindate` LIKE "'.$month_2_month.'%"'); 
		$m_2_f_j = $query->num_rows();
		$data['m_2_f_j_pro'] = $m_2_f_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `joindate` LIKE "'.$month_2_month.'%"'); 
		$m_2_m_j = $query->num_rows();
		$data['m_2_m_j_pro'] = $m_2_m_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `joindate` LIKE "'.$month_2_month.'%"'); 
		$m_2_l_j = $query->num_rows();
		$data['m_2_l_j_pro'] = $m_2_l_j;
		
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `joindate` LIKE "'.$month_3_month.'%"'); 
		$m_3_f_j = $query->num_rows();
		$data['m_3_f_j_pro'] = $m_3_f_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `joindate` LIKE "'.$month_3_month.'%"'); 
		$m_3_m_j = $query->num_rows();
		$data['m_3_m_j_pro'] = $m_3_m_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `joindate` LIKE "'.$month_3_month.'%"'); 
		$m_3_l_j = $query->num_rows();
		$data['m_3_l_j_pro'] = $m_3_l_j;
		
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `joindate` LIKE "'.$month_4_month.'%"'); 
		$m_4_f_j = $query->num_rows();
		$data['m_4_f_j_pro'] = $m_4_f_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `joindate` LIKE "'.$month_4_month.'%"'); 
		$m_4_m_j = $query->num_rows();
		$data['m_4_m_j_pro'] = $m_4_m_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `joindate` LIKE "'.$month_4_month.'%"'); 
		$m_4_l_j = $query->num_rows();
		$data['m_4_l_j_pro'] = $m_4_l_j;
		
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `joindate` LIKE "'.$month_5_month.'%"'); 
		$m_5_f_j = $query->num_rows();
		$data['m_5_f_j_pro'] = $m_5_f_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `joindate` LIKE "'.$month_5_month.'%"'); 
		$m_5_m_j = $query->num_rows();
		$data['m_5_m_j_pro'] = $m_5_m_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `joindate` LIKE "'.$month_5_month.'%"'); 
		$m_5_l_j = $query->num_rows();
		$data['m_5_l_j_pro'] = $m_5_l_j;
		
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `joindate` LIKE "'.$month_6_month.'%"'); 
		$m_6_f_j = $query->num_rows();
		$data['m_6_f_j_pro'] = $m_6_f_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `joindate` LIKE "'.$month_6_month.'%"'); 
		$m_6_m_j = $query->num_rows();
		$data['m_6_m_j_pro'] = $m_6_m_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `joindate` LIKE "'.$month_6_month.'%"'); 
		$m_6_l_j = $query->num_rows();
		$data['m_6_l_j_pro'] = $m_6_l_j;
		
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "17" AND `joindate` LIKE "'.$month_7_month.'%"'); 
		$m_7_f_j = $query->num_rows();
		$data['m_7_f_j_pro'] = $m_7_f_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "18" AND `joindate` LIKE "'.$month_7_month.'%"'); 
		$m_7_m_j = $query->num_rows();
		$data['m_7_m_j_pro'] = $m_7_m_j;
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `ispro` = "1" AND `device_id` NOT LIKE "%sync%" AND `gender` = "19" AND `joindate` LIKE "'.$month_7_month.'%"'); 
		$m_7_l_j = $query->num_rows();
		$data['m_7_l_j_pro'] = $m_7_l_j;
		
		$data['day_2'] = date('D (m-d)',$day_2);
		$data['day_3'] = date('D (m-d)',$day_3);
		$data['day_4'] = date('D (m-d)',$day_4);
		$data['day_5'] = date('D (m-d)',$day_5);
		$data['day_6'] = date('D (m-d)',$day_6);
		$data['day_7'] = date('D (m-d)',$day_7);
		
		$data['m_1'] = date('M (y-m',time());
		$data['m_2'] = date('M (Y-m)',$month_2);
		$data['m_3'] = date('M (Y-m)',$month_3);
		$data['m_4'] = date('M (Y-m)',$month_4);
		$data['m_5'] = date('M (Y-m)',$month_5);
		$data['m_6'] = date('M (Y-m)',$month_6);
		$data['m_7'] = date('M (Y-m)',$month_7);
		
		// deleted free users
		$query = $this->db->query('SELECT `id`,`gender`,`lastactivity` FROM `users` WHERE `isdel` = "1" AND `utype` = "a" AND `ispro` != "1"');
		if($query->num_rows() > 0)
		{
			$del_users = $query->result();
			$del_users_m = 0;
			$del_users_f = 0;
			$del_users_l = 0;
			$del_today = 0;
			$cur_day = date('Y-m-d',time());
			foreach($del_users as $u)
			{
				if($u->gender == "17")
				{
					$del_users_f++;
				}
				if($u->gender == "18")
				{
					$del_users_m++;
				}
				if($u->gender == "19")
				{
					$del_users_l++;
				}
				$udate = date('Y-m-d',$u->lastactivity);
				if($udate == $del_today)
				{
					$del_today++;
				}
			}
		}
		$data['del_users_f'] = $del_users_f;
		$data['del_users_m'] = $del_users_m;
		$data['del_users_l'] = $del_users_l;
		$data['del_today'] = $del_today;
		// deleted pro users 
		$query = $this->db->query('SELECT `id`,`gender`,`lastactivity` FROM `users` WHERE `isdel` = "1" AND `utype` = "a" AND `ispro` = "1"');
		if($query->num_rows() > 0)
		{
			$del_users = $query->result();
			$del_users_m = 0;
			$del_users_f = 0;
			$del_users_l = 0;
			$del_today = 0;
			$cur_day = date('Y-m-d',time());
			foreach($del_users as $u)
			{
				if($u->gender == "17")
				{
					$del_users_f++;
				}
				if($u->gender == "18")
				{
					$del_users_m++;
				}
				if($u->gender == "19")
				{
					$del_users_l++;
				}
				$udate = date('Y-m-d',$u->lastactivity);
				if($udate == $del_today)
				{
					$del_today++;
				}
			}
		}
		$data['del_users_f_pro'] = $del_users_f;
		$data['del_users_m_pro'] = $del_users_m;
		$data['del_users_l_pro'] = $del_users_l;
		$data['del_today_pro'] = $del_today;
		
		// photo ratios 
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `gender` = "17" AND `utype` = "a" AND `device_id` NOT LIKE "%sync%"');
		$tf = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `gender` = "18" AND `utype` = "a" AND `device_id` NOT LIKE "%sync%"');
		$tm = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `gender` = "19" AND `utype` = "a" AND `device_id` NOT LIKE "%sync%"');
		$tl = $query->num_rows();
		
		// with photos
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `gender` = "17" AND `utype` = "a" AND `device_id` NOT LIKE "%sync%" AND `haspic` = "1"');
		$tfp = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `gender` = "18" AND `utype` = "a" AND `device_id` NOT LIKE "%sync%" AND `haspic` = "1"');
		$tmp = $query->num_rows();
		$query = $this->db->query('SELECT `id` FROM `users` WHERE `gender` = "19" AND `utype` = "a" AND `device_id` NOT LIKE "%sync%" AND `haspic` = "1"');
		$tlp = $query->num_rows();
		
		// ratios
		$data['f_pics_per'] = round(($tfp / $tf) * 100,2);
		$data['m_pics_per'] = round(($tmp / $tm) * 100,2);
		$data['l_pics_per'] = round(($tlp / $tl) * 100,2);
		$data['f_pics'] = $tfp;
		$data['m_pics'] = $tmp;
		$data['l_pics'] = $tlp;
		$data['ft'] = $tf;
		$data['mt'] = $tm;
		$data['lt'] = $tl;
		
		// languages
		$query = $this->db->query('SELECT `lang` FROM `users` WHERE `utype` = "a" AND `device_id` NOT LIKE "%sync%" GROUP BY `lang` ORDER BY `lang`');
		$langs = $query->result();
		$l_results = array();
		foreach($langs as $l)
		{
			$query = $this->db->query('SELECT `id` FROM `users` WHERE `utype` = "a" AND `device_id` NOT LIKE "%sync%" AND `lang` = "'.$l->lang.'"');
			$l_results[$l->lang] = $query->num_rows();
		}
		$data['langs'] = $l_results;
		
		// get subscriptions
		$query = $this->db->query('SELECT * FROM `subscriptions` WHERE `status` = "1" ORDER BY `id`');
		$subs = $query->result();
		$subscriptions = array();
		foreach($subs as $s)
		{
			$tmp = new StdClass;
			$tmp->name = $s->name;
			$tmp->price = $s->price;
			$query = $this->db->query('SELECT `id`,`price` FROM `userSubs` WHERE `sid` = "'.$s->id.'"');
			if($query->num_rows() > 0)
			{
				$subps = $query->result();
				$cur_val = 0;
				$cur_cnt = 0;
				foreach($subps as $p)
				{
					$cur_val = $cur_val + $p->price;
					$cur_cnt++;
				}
				$tmp->val = $cur_val;
				$tmp->cnt = $cur_cnt;
			}
			else
			{
				$tmp->val = 0;
				$tmp->cnt = 0;
			}
			$subscriptions[] = $tmp;
			unset($tmp);
		}
		$data['subscriptions'] = $subscriptions;
		
		$this->load->view('welcome_message',$data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */