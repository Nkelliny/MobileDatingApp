<?php $this->load->view('default_header'); ?>
<?php
if($this->session->userdata('adauth') == "yes")
{
	?>
    <div style="float:left; width:100%;">
    <h3>Generate Photos for mailer</h3>
    </div>
    <div style="float:left; width:100%;">
    <form id="mailPhotos" name="mailPhotos" action="<?php echo site_url('photos/domailer'); ?>" method="post" enctype="application/x-www-form-urlencoded">
    <table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td valign="top"><div align="center">Gender:
      <select name="gender" id="gender">
        <option value="18" <?php echo ($pvars['gender'] == "18" ? 'selected="selected"' : ''); ?>>Male</option>
        <option value="17" <?php echo ($pvars['gender'] == "17" ? 'selected="selected"' : ''); ?>>Female</option>
        <option value="19" <?php echo ($pvars['gender'] == "19" ? 'selected="selected"' : ''); ?>>Ladyboy</option>
      </select>
    </div></td>
    <td valign="top"><div align="center">Start Date:
      <input name="year" type="text" id="year" value="2013" size="6" value="<?php echo $pvars['year']; ?>" />
      <input name="month" type="text" id="month" size="6" value="<?php echo $pvars['month']; ?>" />
      <input name="day" type="text" id="day" size="6" value="<?php echo $pvars['day']; ?>" />
      <br />
      Enter YYYY MM DD</div></td>
    <td valign="top"><div align="center">Colums:
      <input name="cols" type="text" id="cols" size="5" value="<?php echo $pvars['cols']; ?>" />
    </div></td>
    <td valign="top"><div align="center">Rows:
      <input name="rows" type="text" id="rows" size="5" value="<?php echo $pvars['rows']; ?>" />
    </div></td>
    <td valign="top"><div align="center">Img Width:
      <input name="width" type="text" id="width" size="6" value="<?php echo $pvars['width']; ?>" />
    </div></td>
    <td valign="top"><div align="center">Img Height:
      <input name="height" type="text" id="height" size="6" value="<?php echo $pvars['height']; ?>" />
    </div></td>
  </tr>
  <tr>
    <td colspan="6"><div align="center">
      <input type="submit" name="getImages" id="getImages" value="Re-Generate Code" />
    </div></td>
    </tr>
</table>
    </form>
    <?php
	if($users != "na")
	{
		$tbl_width = (($pvars['width'] + 5) * $pvars['cols']);
		$div_width = ($pvars['width'] + 5);
		$div_height = ($pvars['height'] + 5);
		$img_code = '<table width="'.$tbl_width.'" border="0" cellspacing="0" cellpadding="0" align="center">';
		$img_code .= '<tr>';
		$img_code .= '<td>';
		$img_code .= '<div style="float:left; width:100%;">';
		foreach($users as $u)
		{
			$img_code .= '<div style="float:left; width:'.$div_width.'px; height:'.$div_height.'px;">';
			$img_code .= '<div style="float:left; width:100%; text-align:center;">';
			$img_code .= '<a href="http://www.xxxxxx.com/profile/'.$u->url.'">';
			$raw_path = $this->my_usersmanager->getProfilePicFromId($u->id);
			$mystring = $raw_path;
			$findme   = 'facebook.com';
			$pos = strpos($mystring, $findme);
			if ($pos === false) 
			{
        	    $ppic = '<img src="http://www.xxxxxx.com/image.php?src='.$raw_path.'&w='.$pvars['width'].'&h='.$pvars['height'].'&zc=1" width="'.$pvars['width'].'" height="'.$pvars['height'].'" alt="" title="" border="0" style="border:#000000 1px solid;" />';
			}
			else
			{
				$ppic = '<img src="'.$raw_path.'" width="'.$pvars['width'].'" height="'.$pvars['height'].'" alt="" title="" border="0" style="border:#000000 1px solid;" />';
			}
			$img_code .= $ppic . '</a>';
			
			$img_code .= '</div>';
			$img_code .= '</div>';
		}
		$img_code .= '</div>';
		$img_code .= '</td>';
		$img_code .= '</tr>';
		$img_code .= '</table>';
	}
	else
	{
		$img_code = "Try again, your search returned no results!";
	}
	?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div align="center">
      <textarea cols="100" rows="20"><?php echo $img_code; ?></textarea>
    </div></td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><?php echo $img_code; ?></td>
  </tr>
</table>
    </div>
<?php
}
else
{
	$this->load->view('login_default');
}
?>
<?php $this->load->view('default_footer'); ?>