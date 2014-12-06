<?php $this->load->view('default_header'); ?>
<script>
function pushWindow(url, title, w, h) 
{
	var left = (screen.width/2)-(w/2);
	var top = (screen.height/2)-(h/2);
	return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
} 
</script>
<?php
if($this->session->userdata('adauth') == "yes")
{
	?>

<form id="usrFrm" name="usrFrm" action="<?php echo site_url('users/doupdate'); ?>" method="post" enctype="application/x-www-form-urlencoded">
  <input type="hidden" id="id" name="id" value="<?php echo $uinfo->id; ?>" />
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td rowspan="2" align="center">
      <?php
		if($uinfo->haspic == 1)
		{
			$path = 'https://www.xxxxxx.com'.$uinfo->picpath;
		}
		else if($uinfo->haspic == 2)
		{
			$path = 'https://www.xxxxxx.com/images/no_profile_photo_user.png';
		}
		else if($uinfo->haspic == 3)
		{
			$path = 'https://www.xxxxxx.com/images/pending_profile_user.png';
		}
		else if($uinfo->haspic == 4)
		{
			$path = 'https://www.xxxxxx.com/images/not_approved_user.png';
		}
	  ?>
      <img src="/image.php?src=<?php echo $path; ?>&w=150&h=150&zc=1;" width="150" height="150" /><br />
        <label>
          <input type="checkbox" id="delphoto" name="delphoto" value="1" />
          Delete Photo</label><br />
Approved By:<br />
<?php echo ($uinfo->appby != "0" ? $this->my_usersmanager->getAdminInfo($uinfo->appby) : 'Older Pic'); ?></td>
      <td>Nickname:<br />
        <input type="text" id="nickname" name="nickname" value="<?php echo $uinfo->nickname; ?>" /></td>
      <td>Gender:<br />
        <select id="gender" name="gender">
          <option value="17" <?php echo ($uinfo->gender == "17" ? 'selected="selected"' : ''); ?>>Female</option>
          <option value="18" <?php echo ($uinfo->gender == "18" ? 'selected="selected"' : ''); ?>>Male</option>
          <option value="19" <?php echo ($uinfo->gender == "19" ? 'selected="selected"' : ''); ?>>Ladyboy</option>
        </select></td>
      <td>Seeking:<br />
        <?php 
	  $seeking = explode(',',$uinfo->seeking); 
	  if(in_array('17',$seeking))
	  {
		  echo 'Females ';
	  }
	  if(in_array('18',$seeking))
	  {
		  echo ' Males ';
	  }
	  if(in_array('19',$seeking))
	  {
		  echo ' Ladyboys ';
	  }
	  ?></td>
      <td>Show Only:<br />
        <?php
      $sonly = explode(',',$uinfo->showonly); 
	  if(in_array('17',$sonly))
	  {
		  echo 'Females ';
	  }
	  if(in_array('18',$sonly))
	  {
		  echo ' Males ';
	  }
	  if(in_array('19',$sonly))
	  {
		  echo ' Ladyboys ';
	  }
	  ?></td>
      <td>DOB:<br />
        <?php 
	  // y m d
	  $dob = explode('-',$uinfo->dob); 
	  ?>
        <select id="year" name="year">
          <?php 
	  for($a=1900; $a<2099; $a++)
	  {
		  ?>
          <option value="<?php echo $a; ?>" <?php echo ($a == @$dob[0] ? 'selected="selected"' : ''); ?>><?php echo $a; ?></option>
          <?php
	  }
	  ?>
        </select>
        <select id="month" name="month">
          <?php
	  for($a=1;$a<13;$a++)
	  {
		  ?>
          <option value="<?php echo $a; ?>" <?php echo ($a == @$dob[1] ? 'selected="selected"' : ''); ?>><?php echo $a; ?></option>
          <?php
	  }
	  ?>
        </select>
        <select id="day" name="day">
          <?php
	  for($a=1;$a<32;$a++)
	  {
		  ?>
          <option value="<?php echo $a; ?>" <?php echo($a == @$dob[2] ? 'selected="selected"' : ''); ?>><?php echo $a; ?></option>
          <?php
	  }
	  ?>
        </select></td>
    </tr>
    <tr>
      <td>Join Date:<br />
        <?php echo $uinfo->joindate; ?><br />
        Last Active:<br />
<?php echo date('Y-M-D h:i:s',$uinfo->lastactivity); ?></td>
      <td>Views:<br />
        <?php echo $uinfo->views; ?></td>
      <td>Join Type:<br />
        <?php echo $uinfo->jtype; ?></td>
      <td>Sync Code:<br />
        <?php echo $uinfo->sync_code; ?></td>
      <td>Pro Version:<br />
        <?php echo ($uinfo->ispro == "1" ? 'Yes' : 'No'); ?>
        <?php
        if($uinfo->ispro == "1")
		{
			$exp = date('Y-m-d',($uinfo->expiration_date / 1000)); 
			$exp_date = explode('-',$exp);
			?>
           <br />Expires: <select id="exp_y" name="exp_y">
           <?php
		   $cy = 2014;
		   for($a=$cy;$a<$cy+100;$a++)
		   {
			   ?>
               <option value="<?php echo $a; ?>" <?php echo ($a == $exp_date[0] ? 'selected="selected"' : ''); ?>><?php echo $a; ?></option>
               <?php
		   }
		   ?>
           </select> 
           <select id="exp_m" name="exp_m">
           <?php
		   for($a=1;$a<13;$a++)
		   {
			   $m = ($a < 10 ? '0'.$a : $a);
			   ?>
               <option value="<?php echo $m; ?>" <?php echo ($m == $exp_date[1] ? 'selected="selected"' : ''); ?>><?php echo $m; ?></option>
               <?php
		   }
		   ?>
           </select> 
           <select id="exp_d" name="exp_d">
           <?php
		   for($a=1;$a<32;$a++)
		   {
			   $d = ($a < 10 ? '0'.$a : $a);
			   ?>
               <option value="<?php echo $d; ?>" <?php echo ($d == $exp_date[2] ? 'selected="selected"' : ''); ?>><?php echo $d; ?></option>
               <?php
		   }
		   ?>
           </select>
            <?php
		}
		?></td>
    </tr>
    <tr>
      <td colspan="2">Headline:<br />
        <input type="text" id="headline" name="headline" value="<?php echo $uinfo->headline; ?>" /></td>
      <td>Promo Code:<br />
        <input type="text" id="promo_code" name="promo_code" value="<?php echo $uinfo->promo_code; ?>" /></td>
      <td>Language:<br />
        <?php echo $uinfo->lang; ?></td>
      <td>Is Deleted:<br />
        <?php echo ($uinfo->isdel == 1 ? 'Yes' : 'No'); ?></td>
      <td>Deleted Reason:<br />
        <?php echo $uinfo->delreason; ?></td>
    </tr>
    <tr>
      <td colspan="2">BIO:<br />
        <input type="text" id="bio" name="bio" value="<?php echo $pinfo->bio; ?>" /></td>
      <td><p>User Status:<br />
          <select id="type" name="type">
            <option value="mobile" <?php echo ($uinfo->type == 'mobile' ? 'selected="selected"' : ''); ?>>Mobile</option>
            <option value="normal" <?php echo ($uinfo->type == 'normal' ? 'selected="selected"' : ''); ?>>Normal</option>
            <option value="vip" <?php echo ($uinfo->type == 'vip' ? 'selected="selected"' : ''); ?>>Vip</option>
            <option value="banned" <?php echo ($uinfo->type == 'banned' ? 'selected="selected"' : ''); ?>>Banned</option>
            <option value="suspended" <?php echo ($uinfo->type == 'suspended' ? 'selected="selected"' : ''); ?>>Suspended</option>
            <option value="suspicious" <?php echo ($uinfo->type == 'suspicious' ? 'selected="selected"' : ''); ?>>Suspicious</option>
            <option value="synced" <?php echo ($uinfo->type == 'synced' ? 'selected="selected"' : ''); ?>>Synced</option>
          </select>
        <br />
        Notes:</p>
        <p>
          <label for="usernotes"></label>
          <textarea name="usernotes" id="usernotes" cols="45" rows="5"><?php echo $uinfo->usernotes; ?></textarea>
        </p></td>
      <td>User Blocked: <?php echo $blocks; ?> times.</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <?php
	if($uinfo->isadmin == "1")
	{
		?>
    	<tr>
    	  <td colspan="6">
          <div style="float:left; width:100%;">
          <?php
		  //print_r($sections);
		  $cpgs = explode(',',$uinfo->apgs);
		  foreach($sections as $s)
		  {
			  ?>
              <div style="float:left; width:100%;">
              <span style="font-size:18px; font-weight:bold;"><?php echo $s->section; ?></span><br />
              <?php
			  foreach($pages as $p)
			  {
				 if($p->section == $s->section)
				 {
				  ?>
                  <div style="float:left; width:25%; height:25px;"><label><input type="checkbox" id="pgs[]" name="pgs[]" <?php echo (in_array($p->id,$cpgs) ? 'checked="checked"' : ''); ?> value="<?php echo $p->id; ?>" /> <?php echo $p->name; ?></label></div>
                  <?php
				 }
			  }
			  ?>
              </div>
              <div style="float:left; width:100%; height:15px;">&nbsp;</div>
              <?php
		  }
		  //print_r($pages);
		  ?>
          </div>
          </td>
    	</tr>
    <?php
	}
	?>
    <tr>
      <td colspan="6"><div align="center"><a href="javascript:void(0);" onclick="document.getElementById('usrFrm').submit();">Update User</a> || <a href="javascript:void(0);" onclick="pushWindow('<?php echo site_url('users/pushshow/'.$uinfo->id); ?>','Push',600,600);">Send Push</a></div></td>
    </tr>
  </table>
</form>
<?php
}
else
{
	$this->load->view('login_default');
}
?>
<?php $this->load->view('default_footer'); ?>
