<?php $this->load->view('default_header'); ?>
<?php
print_r($users);
exit;
?>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th>Nickname</th>
    <th>Email</th>
    <th>Gender</th>
    <th>Country</th>
    <th>DOB</th>
    <th>Joined</th>
    <th>Status</th>
    <th>Aff-Join</th>
    <th>Mod</th>
    <th>Admin</th>
    <th>Type</th>
    <th>Last Active</th>
  </tr>
  <?php
  $a=0;
  foreach($users as $u)
  {
  	?>
	<tr <?php echo ($a % 2 == 0 ? ($u->status == 2 ? 'bgcolor="#FF0000"' : '') : ($u->status == 2 ? 'bgcolor="#FF0000"' : 'bgcolor="#CCCCCC"')); ?> height="50">
  	  <td align="center"><?php echo $u->nickname; ?><br />
      <a href="http://dev2.xxxxxx.com/profile/<?php echo $u->url; ?>" target="_blank">Profile</a>|<a href="/users/info/<?php echo $u->id; ?>">Stats</a></td>
  	  <td><?php echo $u->email; ?></td>
  	  <td align="center">
	  <?php
      if($u->gender == "1")
	  {
		  echo "Lady";
	  }
	  else if($u->gender == "2")
	  {
		  echo "Man";
	  }
	  else
	  {
		  echo "Ladyboy";
	  }
	  
	  ?></td>
  	  <td align="center"><?php echo $u->country_now; ?></td>
  	  <td align="center"><?php echo $u->dob; ?></td>
  	  <td align="center">
	  <?php 
	  $pts = explode(' ',$u->joindate);
	  echo $pts[0]; 
	  ?>
      </td>
  	  <td align="center">
      <select id="status_<?php echo $u->id; ?>" name="status_<?php echo $u->id; ?>" onchange="setStatus(this.value,<?php echo $u->id; ?>);">
	  <?php echo $u->status; ?>
      <option value="1" <?php echo ($u->status == "1" ? 'selected="selected"' : ''); ?>>Approved</option>
      <option value="2" <?php echo ($u->status == "2" ? 'selected="selected"' : ''); ?>>Pending</option>
      <option value="3" <?php echo ($u->status == "3" ? 'selected="selected"' : ''); ?>>Unapproved</option>
      </select>
      </td>
  	  <td align="center"><?php echo ($u->isaff != 0 ? '<a href="/affiliate/member/'.$u->isaff.'">'.$u->isaff.'</a>' : 'na'); ?></td>
  	  <td align="center"><input type="checkbox" id="ismod_<?php echo $u->id; ?>" name="ismod_<?php echo $u->id; ?>" value="1" <?php echo ($u->ismod != 0 ? 'checked="checked"' : ''); ?> onchange="isMod(<?php echo $u->id; ?>);" /></td>
  	  <td align="center"><input type="checkbox" id="isadmin_<?php echo $u->id; ?>" name="isadmin_<?php echo $u->id; ?>" value="1" <?php echo ($u->isadmin != 0 ? 'checked="checked"' : ''); ?> onchange="isAdmin(<?php echo $u->id; ?>);" /></td>
  	  <td align="center"><select id="type_<?php echo $u->id; ?>" name="type_<?php echo $u->id; ?>" onchange="chUtype(this.value,<?php echo $u->id; ?>);">
      <option value="normal" <?php echo ($u->type == 'normal' ? 'selected="selected"':''); ?>>Normal</option>
      <option value="vip" <?php echo ($u->type == 'vip' ? 'selected="selected"':''); ?>>VIP</option>
      <option value="banned" <?php echo ($u->type == 'banned' ? 'selected="selected"':''); ?>>Banned</option>
      <option value="suspended" <?php echo ($u->type == 'suspended' ? 'selected="selected"':''); ?>>Suspended</option>
      </select>
      </td>
  	  <td align="center"><?php echo date('Y-M-D h:i:s',$u->lastactivity); ?></td>
  </tr>
  <?php
  	$a++;
  }
  ?>
</table>
<?php $this->load->view('default_footer'); ?>