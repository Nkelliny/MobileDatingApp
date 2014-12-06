<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class At extends CI_Controller 
{
	public function index()
	{
		// add check
		$ad = $this->uri->segment(2);
		$query = $this->db->query('SELECT `id`,`name` FROM `advert` WHERE `name` = "'.$ad.'"');
		$advert = $query->row();
		$query = $this->db->query('UPDATE `advert` SET `clicks` = clicks+1 WHERE `id` = "'.$advert->id.'"');
		// ad tracks
		$query = $this->db->query('INSERT INTO `adTrack` (`id`,`ad`,`name`,`datetime`,`ip`) VALUES (NULL,"'.$advert->id.'","'.$advert->name.'","'.time().'","'.$_SERVER['REMOTE_ADDR'].'")');
		// redirect to home page
		redirect('/','refresh');
	}
	
	function show()
	{
		$ad = $this->uri->segment(3);
		$query = $this->db->query('SELECT * FROM `advert` WHERE `name` = "'.$ad.'"');
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			// update impressions
			$query = $this->db->query('UPDATE `advert` SET `imp` = imp+1 WHERE `id` = "'.$row->id.'"');
			$data['ad'] = $row;
			$this->load->view('advert_show',$data);
		}
	}
}

/* End of file login.php */
/* Location: ./application/controllers/login.php */