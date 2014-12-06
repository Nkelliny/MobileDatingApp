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
        <th align="center">Nickname</th>
        <th align="center">Gender</th>
        <th align="center">Age</th>
        <th align="center">Reason</th>
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
        <td align="center">
        <a href="/5073/index.php/users/info/<?php echo $u->id; ?>">
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
        </a>
        <a href="/5073/index.php/users/info/<?php echo $u->id; ?>"><?php echo ($u->nickname != "" ? $u->nickname : 'NO NICKNAME!'); ?></a>
        <?php
		if($u->device_id != "" && $u->device_id != "na")
		{
			?>
            <br /><em style="font-size:10px;">(APP USER)</em>
            <?php
		}
		?>
        </td>
        <td align="center">
        <?php
		if($u->gender == "17")
		{
			echo 'Female';
		}
		else if($u->gender == "18")
		{
			echo 'Male';
		}
		else if($u->gender == "19")
		{
			echo 'Ladyboy';
		}
		?>
        </td>
        <td align="center"><?php echo $u->age; ?></td>
        <td align="center"><?php echo $u->delreason; ?></td>
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
