<?php $this->load->view('default_header'); ?>
<?php
if($this->session->userdata('adauth') == "yes")
{
	?>
  <h3>Photos That Need Approved</h3>
  <p>
  Last 5 Actions<br />
  <ul>
  <?php
  foreach($adminTrack as $a)
  {
	  ?>
      <li><?php echo $a->value; ?></li>
      <?php
  }
  ?>
  </ul>
  </p>
<p>
<form id="mass" name="mass" action="<?php echo site_url('photos/appmass'); ?>" method="post" enctype="application/x-www-form-urlencoded">
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr>
    <td>
  <p>
    <label>
      <input type="checkbox" onclick="checkAll(this);">
      Select All</label>
  </p>
  </td>
  </tr>
  <tr>
    <td>
  <p>
  <div style="float:left; width:100%;">
  <?php 
	if($photos != "na")
	{
		foreach($photos as $p)
		{
			$raw_path = $p->path;
			$ppic = '<img src="http://www.xxxxxx.com/image.php?src='.$raw_path.'&w=150&h=150&zc=1" width="150" height="150" alt="" title="" border="0" style="border:#000000 1px solid;" />';
			?>
			<div style="float:left; width:160px; height:235px; text-align:center; border:#000 1px solid; background-color:#CCC;" id="image_<?php echo $p->id; ?>">
            	<?php $ugen = $this->my_usersmanager->getGender($p->uid); ?>
                <select id="gender_<?php echo $p->uid; ?>" name="gender_<?php echo $p->uid; ?>" onchange="switchGender(this.value,<?php echo $p->uid; ?>);">
                <option value="17" <?php echo ($ugen['id'] == 17 ? 'selected="selected"' : ''); ?>>Female</option>
                <option value="18" <?php echo ($ugen['id'] == 18 ? 'selected="selected"' : ''); ?>>Male</option>
                <option value="19" <?php echo ($ugen['id'] == 19 ? 'selected="selected"' : ''); ?>>Ladyboy</option>
                </select>
                <br />
				<a href="<?php echo $p->path; ?>" target="_blank"><?php echo $ppic; ?></a><br />
				<a href="<?php echo site_url('users/info/'.$p->uid); ?>">Edit User</a><br />
				<input type="checkbox" id="usr[]" name="usr[]" value="<?php echo $p->id; ?>" />
				<!-- <select id="img_<?php //echo $p->id; ?>" name="<?php //echo $p->id; ?>" onchange="appImageStatus(<?php //echo $p->id; ?>,this.value);"> -->
				<!-- <option value="1">Approve</option> -->
				<!-- <option value="3">Disapprove</option> -->
				<!-- </select> --><br />
				TJ<?php echo $p->uid; ?>
			</div>
			<?php
		}
	}
	else
	{
		?>
  		<div style="float:left; width:100%; text-align:center;">There are currently no photos that need approved!</div>
  	<?php
	}
	?>
</div>
</p>
</td>
  </tr>
</table>
 <div class="tableactions">
    <select id="massAct" name="massAct">
      <option value="na">Select Action</option>
      <option value="app">Approve Selected</option>
      <option value="del">Delete Selected</option>
    </select>
    <input type="submit" class="submit tiny" value="Apply to selected" />
  </div>
  </form>
  </p>
<?php
}
else
{
	$this->load->view('login_default');
}
?>
<?php $this->load->view('default_footer'); ?>