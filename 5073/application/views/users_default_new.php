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
<p>
<?php
if($this->input->cookie('tjadminusort'))
{
	$vals = explode('-',$this->input->cookie('tjadminusort'));
	$gs = $vals[0];
	$ph = $vals[1];
	$os = $vals[2];
	$ap = $vals[3];
	$ut = $vals[4];
}
else
{
	$gs = "all";
	$ph = "all";
	$os = "all";
	$ap = "all";
	$ut = "all";
}
?>
<form id="loadusers" name="loadusers" action="<?php echo site_url('users'); ?>" method="post" enctype="application/x-www-form-urlencoded">
  Show:&nbsp;
  <label>Gender&nbsp;
    <select id="gen_show" name="gen_show">
      <option value="all" <?php echo ($gs == "all" ? 'selected="selected"' : ''); ?>>All</option>
      <option value="17" <?php echo ($gs == "17" ? 'selected="selected"' : ''); ?>>Women</option>
      <option value="18" <?php echo ($gs == "18" ? 'selected="selected"' : ''); ?>>Men</option>
      <option value="19" <?php echo ($gs == "19" ? 'selected="selected"' : ''); ?>>Ladyboys</option>
    </select>
  </label>
  &nbsp;
  <label>Photos&nbsp;
    <select id="photos" name="photos">
      <option value="all" <?php echo ($ph == "all" ? 'selected="selected"' : ''); ?>>All</option>
      <option value="1" <?php echo ($ph == "1" ? 'selected="selected"' : ''); ?>>Photos</option>
      <option value="2" <?php echo ($ph == "2" ? 'selected="selected"' : ''); ?>>No Photo</option>
    </select>
  </label>
  &nbsp;
  <label>OS&nbsp;
    <select id="os" name="os">
      <option value="all" <?php echo ($os == "all" ? 'selected="selected"' : ''); ?>>All</option>
      <option value="ios" <?php echo ($os == "ios" ? 'selected="selected"' : ''); ?>>iOS</option>
      <option value="android" <?php echo ($os == "android" ? 'selected="selected"' : ''); ?>>Android</option>
    </select>
  </label>
  &nbsp;
  <label>App Type&nbsp;
    <select id="app" name="app">
      <option value="all" <?php echo ($ap == "all" ? 'selected="selected"' : ''); ?>>All</option>
      <option value="1" <?php echo ($ap == "0" ? 'selected="selected"' : ''); ?>>Pro</option>
      <option value="0" <?php echo ($ap == "0" ? 'selected="selected"' : ''); ?>>Free</option>
    </select>
  </label>
  &nbsp;
  <label>User Type&nbsp;
  <select id="utype" name="utype">
  <option value="all" <?php echo ($ut == "all" ? 'selected="selected"' : ''); ?>>All</option>
    <option value="normal" <?php echo ($ut == "normal" ? 'selected="selected"' : ''); ?>>Normal</option>
    <option value="banned" <?php echo ($ut == "banned" ? 'selected="selected"' : ''); ?>>Banned</option>
    <option value="suspended" <?php echo ($ut == "suspended" ? 'selected="selected"' : ''); ?>>Suspended</option>
    <option value="suspicious" <?php echo ($ut == "suspicious" ? 'selected="selected"' : ''); ?>>Suspicious</option>
  </select>
  </label>
  &nbsp;
  <input type="submit" class="submit tiny" value="Load Users" />
</form>
</p>
<form action="<?php echo site_url('users/mass'); ?>" method="post" enctype="application/x-www-form-urlencoded" id="massFrm" name="massFrm">
  <table cellpadding="0" cellspacing="0" width="100%" class="sortable">
    <thead>
      <tr>
        <th width="10"><input type="checkbox" class="check_all" /></th>
        <th align="center">Nickname</th>
        <th align="center">Gender</th>
        <th align="center">Age</th>
        <th align="center">Type</th>
        <th align="center">OS</th>
        <th align="center">App Type</th>
        <th align="center">Join Date</th>
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
          </td>
        <td align="center"><select id="gender_<?php echo $u->id; ?>" name="gender_<?php echo $u->uid; ?>" onchange="switchGender(this.value,<?php echo $u->id; ?>);">
            <option value="17" <?php echo ($u->gender == 17 ? 'selected="selected"' : ''); ?>>Female</option>
            <option value="18" <?php echo ($u->gender == 18 ? 'selected="selected"' : ''); ?>>Male</option>
            <option value="19" <?php echo ($u->gender == 19 ? 'selected="selected"' : ''); ?>>Ladyboy</option>
          </select></td>
        <td align="center"><?php echo $u->age; ?></td>
        <td align="center"><select id="type_<?php echo $u->id; ?>" name="type_<?php echo $u->id; ?>" onchange="setUserType(this.value,<?php echo $u->id; ?>);">
            <option value="normal" <?php echo ($u->type == "normal" ? 'selected="selected"' : ''); ?>>Normal</option>
            <option value="banned" <?php echo ($u->type == "banned" ? 'selected="selected"' : ''); ?>>Banned</option>
            <option value="suspended" <?php echo ($u->type == "suspended" ? 'selected="selected"' : ''); ?>>Suspended</option>
            <option value="suspicious" <?php echo ($u->type == "suspicious" ? 'selected="selected"' : ''); ?>>Suspicious</option>
          </select></td>
        <td align="center"><img src="/5073/images/<?php echo $u->os; ?>.png" width="25" height="25" /></td>
        <td align="center"><?php echo ($u->ispro == "1" ? 'Pro' : 'Free'); ?></td>
        <td align="center"><?php echo $u->joindate; ?></td>
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
