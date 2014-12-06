<?php $this->load->view('default_header'); ?>
<?php
if($this->session->userdata('adauth') == "yes")
{
	?>

<h2>Admin Users</h2>
<form action="<?php echo site_url('users/mass'); ?>" method="post" enctype="application/x-www-form-urlencoded" id="massFrm" name="massFrm">
  <table cellpadding="0" cellspacing="0" width="100%" class="sortable">
    <thead>
      <tr>
        <th width="10"><input type="checkbox" class="check_all" /></th>
        <th align="center">Nickname</th>
        <th align="center">Gender</th>
        <th align="center">Age</th>
        <th align="center">Type</th>
        <th align="center">Join Type</th>
        <th align="center">Approve</th>
        <th align="center">Delete</th>
      </tr>
    </thead>
    <tbody>
      <?php
  if($users != "na")
  {
  	$a=0;
  	foreach($users as $u)
  	{
		?>
      <tr height="35">
        <td width="10"><input type="checkbox" name="usr[]" id="usr[]" value="<?php echo $u->id; ?>" /></td>
        <td align="center"><a href="/5073/index.php/users/info/<?php echo $u->id; ?>">
          <?php
		$img = $this->my_usersmanager->getProfilePicFromId($u->id);
		if($u->haspic == "1")
		{
			?>
          <img src="http://www.xxxxxx.com/image.php?src=<?php echo $u->picpath; ?>&w=75&h=75&zc=1" width="75" height="75" /><br />
          <?php
		}
		else
		{
			?>
          <img src="http://www.xxxxxx.com/images/user_icon.png" width="75" height="75" /><br />
          <?php
		}
		?>
          </a> <a href="/5073/index.php/users/info/<?php echo $u->id; ?>"><?php echo ($u->nickname != "" ? $u->nickname : 'NO NICKNAME!'); ?></a>
          <?php
		if($u->device_id != "" && $u->device_id != "na")
		{
			?>
          <br />
          <em style="font-size:10px;">(APP USER)</em>
          <?php
		}
		?></td>
        <td align="center"><select id="gender_<?php echo $u->id; ?>" name="gender_<?php echo $u->id; ?>" onchange="switchGender(this.value,<?php echo $u->id; ?>);">
            <option value="17" <?php echo ($u->gender == 17 ? 'selected="selected"' : ''); ?>>Female</option>
            <option value="18" <?php echo ($u->gender == 18 ? 'selected="selected"' : ''); ?>>Male</option>
            <option value="19" <?php echo ($u->gender == 19 ? 'selected="selected"' : ''); ?>>Ladyboy</option>
          </select></td>
        <td align="center"><?php echo $u->age; ?></td>
        <td align="center"><select id="type_<?php echo $u->id; ?>" name="type_<?php echo $u->id; ?>" onchange="setUserType(this.value,<?php echo $u->id; ?>);">
            <option value="normal" <?php echo ($u->type == "normal" ? 'selected="selected"' : ''); ?>>Normal</option>
            <option value="vip" <?php echo ($u->type == "vip" ? 'selected="selected"' : ''); ?>>VIP</option>
            <option value="mobile" <?php echo ($u->type == "mobile" ? 'selected="selected"' : ''); ?>>Mobile</option>
            <option value="banned" <?php echo ($u->type == "banned" ? 'selected="selected"' : ''); ?>>Banned</option>
            <option value="suspended" <?php echo ($u->type == "suspended" ? 'selected="selected"' : ''); ?>>Suspended</option>
            <option value="suspicious" <?php echo ($u->type == "suspicious" ? 'selected="selected"' : ''); ?>>Suspicious</option>
          </select></td>
        <td align="center"><?php echo $u->jtype; ?></td>
        <td align="center"><a href="/5073/index.php/users/approve/<?php echo $u->id; ?>"><img src="../../images/msg_success.png" width="32" height="32" /></a></td>
        <td align="center"><a href="/5073/index.php/users/del/<?php echo $u->id; ?>"><img src="../../images/msg_error.png" width="32" height="32" /></a></td>
      </tr>
      <?php
		$a++;
  	}
  }
  else
  {
	  ?>
      <tr>
        <td colspan="15">There are no users to show for this section</td>
      </tr>
      <?php
  }
  ?>
    </tbody>
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
