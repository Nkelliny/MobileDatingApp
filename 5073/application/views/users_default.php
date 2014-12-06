<?php $this->load->view('default_header'); ?>
<?php
if($this->session->userdata('adauth') == "yes")
{
	?>

<h2>Users</h2>
<p>
  <label><a href="javascript:void(0);" onclick="if(document.getElementById('user_search_block').style.display == 'none'){document.getElementById('user_search_block').style.display = 'block'}else{document.getElementById('user_search_block').style.display = 'none'}">Search Users</a></label>
  </a> </p>
<p>
<table cellpadding="0" cellspacing="0" width="100%" id="user_search_block" style="display:none;">
  <tr>
    <td><form id="usearch" name="usearch">
        <label>Search Type</label>
        <select id="stype" name="stype">
          <option value="id">ID</option>
          <option value="nickname">Nickname</option>
          <option value="email">Email</option>
        </select>
        <p>
          <label>Search Value</label>
          <input type="text" class="text" id="val" name="val" />
        </p>
        <p>
          <input type="button" class="submit tiny" value="Search" onclick="doUserSearch();" />
        </p>
      </form></td>
  </tr>
</table>
</p>
<p id="search_res" style="display:none;"></p>
<p>
<div class="table_pagination right"><?php echo $plinks; ?></div>
</p>
<form action="<?php echo site_url('users/mass'); ?>" method="post" enctype="application/x-www-form-urlencoded" id="massFrm" name="massFrm">
  <table cellpadding="0" cellspacing="0" width="100%" class="sortable">
    <thead>
      <tr>
        <th width="10"><input type="checkbox" class="check_all" /></th>
        <th align="center">Nickname</th>
        <!-- <th align="center">Email</th> -->
        <!-- <th align="center">Headline</th> -->
        <th align="center">Gender</th>
        <!-- <th align="center">Country</th> -->
        <th align="center">Age</th>
        <th align="center">Type</th>
        <th align="center">Join Type</th>
        <!-- <th align="center">Bio</th> -->
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
        <td width="10"><input type="checkbox" name="usr[]" id="usr[]" value="<?php echo $u->uid; ?>" /></td>
        <td align="center">
        <a href="/5073/index.php/users/info/<?php echo $u->uid; ?>">
		<?php
		$img = $this->my_usersmanager->getProfilePicFromId($u->uid);
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
        </a>
        <a href="/5073/index.php/users/info/<?php echo $u->uid; ?>"><?php echo ($u->nickname != "" ? $u->nickname : 'NO NICKNAME!'); ?></a>
        <?php
		if($u->device_id != "" && $u->device_id != "na")
		{
			?>
            <br /><em style="font-size:10px;">(APP USER)</em>
            <?php
		}
		?>
        </td>
        <!-- <td><?php //echo $u->email; ?></td> -->
        <!-- <td align="center"><img src="/5073/images/view.png" width="20" height="20" alt="<?php //echo $u->headline; ?>" title="<?php //echo $u->headline; ?>" border="0" /></td> -->
        <td align="center"><select id="gender_<?php echo $u->uid; ?>" name="gender_<?php echo $u->uid; ?>" onchange="switchGender(this.value,<?php echo $u->uid; ?>);">
            <option value="17" <?php echo ($u->gender == 17 ? 'selected="selected"' : ''); ?>>Female</option>
            <option value="18" <?php echo ($u->gender == 18 ? 'selected="selected"' : ''); ?>>Male</option>
            <option value="19" <?php echo ($u->gender == 19 ? 'selected="selected"' : ''); ?>>Ladyboy</option>
          </select></td>
        <!-- <td align="center"><?php //echo $u->country_now; ?></td> -->
        <td align="center"><?php echo $u->age; ?></td>
        <td align="center"><select id="type_<?php echo $u->uid; ?>" name="type_<?php echo $u->uid; ?>" onchange="setUserType(this.value,<?php echo $u->uid; ?>);">
            <option value="normal" <?php echo ($u->type == "normal" ? 'selected="selected"' : ''); ?>>Normal</option>
            <option value="vip" <?php echo ($u->type == "vip" ? 'selected="selected"' : ''); ?>>VIP</option>
            <option value="mobile" <?php echo ($u->type == "mobile" ? 'selected="selected"' : ''); ?>>Mobile</option>
            <option value="banned" <?php echo ($u->type == "banned" ? 'selected="selected"' : ''); ?>>Banned</option>
            <option value="suspended" <?php echo ($u->type == "suspended" ? 'selected="selected"' : ''); ?>>Suspended</option>
            <option value="suspicious" <?php echo ($u->type == "suspicious" ? 'selected="selected"' : ''); ?>>Suspicious</option>
          </select></td>
        <td align="center"><?php echo $u->jtype; ?></td>
        <!-- <td align="center"><img src="/5073/images/view.png" width="20" height="20" alt="<?php //echo $u->bio; ?>" title="<?php //echo $u->bio; ?>" border="0" /></td> -->
        <td align="center"><a href="/5073/index.php/users/approve/<?php echo $u->uid; ?>"><img src="../../images/msg_success.png" width="32" height="32" /></a></td>
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
  <div class="tableactions">
    <select id="massAct" name="massAct">
      <option value="na">Select Action</option>
      <option value="app">Approve Selected</option>
      <option value="del">Delete Selected</option>
    </select>
    <input type="submit" class="submit tiny" value="Apply to selected" />
  </div>
  <!-- .tableactions ends -->
  
  <div class="table_pagination right"><?php echo $plinks; ?></div>
  <!-- .pagination ends -->
  
</form>
<?php
}
else
{
	$this->load->view('login_default');
}
?>
<?php $this->load->view('default_footer'); ?>
