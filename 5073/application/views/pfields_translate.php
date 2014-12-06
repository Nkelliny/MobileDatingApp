<?php $this->load->view('default_header'); ?>
<?php
if($this->session->userdata('adauth') == "yes")
{
	?>
    <h2>Profile Fields</h2>
<p>
    <label>Existing Fields & Values</label>
    </p>
    <form id="pftrans" name="pftrans" action="<?php echo site_url('translate/pfields'); ?>" method="post" enctype="application/x-www-form-urlencoded">
    <p><label>Select Language
    <select id="ls" name="ls">
    <option value="0">Select Language</option>
    <?php
	foreach($langs as $l)
	{
		?>
        <option value="<?php echo $l; ?>" <?php echo ($ls == $l ? 'selected="selected"' : ''); ?>><?php echo $l; ?></option>
        <?php
	}
	?>
    </select>
    </label>
    <p>
<input class="submit tiny" type="submit" id="el" name="el" value="Select Language"/>
</p>
    </form>
    <?php
	if($plabels != "na")
	{
		?>
    <h2>Edit Text</h2>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th width="50%">Profile Fields</th>
    <th width="50%">Field Values</th>
  </tr>
  <tr>
    <td valign="top">
    <form id="lbsfrm" name="lbsfrm" action="<?php echo site_url('translate/updateplables'); ?>" method="post" enctype="application/x-www-form-urlencoded">
    <input type="hidden" id="ls" name="ls" value="<?php echo $ls; ?>" />
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>Name</td>
    <td><?php echo $ls; ?> Value</td>
  </tr>
  <?php
  foreach($plabels as $l)
  {
	?>
  	<tr>
    <td><?php echo $l->name; ?></td>
    <td><input type="text" class="text" id="val[<?php echo $l->id; ?>]" name="val[<?php echo $l->id; ?>]" value="<?php echo $l->txt; ?>" /></td>
  </tr>
  <?php
  }
  ?>
</table>
<p><input class="submit tiny" type="submit" id="up" name="up" value="Update Profile Fields"/></p>
</form>
    </td>
    <td valign="top">
    <form id="lbsfrm" name="lbsfrm" action="<?php echo site_url('translate/updatepfields'); ?>" method="post" enctype="application/x-www-form-urlencoded">
    <input type="hidden" id="ls" name="ls" value="<?php echo $ls; ?>" />
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>Name</td>
    <td><?php echo $ls; ?> Value</td>
  </tr>
  <?php
  foreach($pfields as $l)
  {
	?>
  	<tr>
    <td><?php echo $l->name; ?></td>
    <td><input type="text" class="text" id="val[<?php echo $l->id; ?>]" name="val[<?php echo $l->id; ?>]" value="<?php echo $l->value; ?>" /></td>
  </tr>
  <?php
  }
  ?>
</table>
<p><input class="submit tiny" type="submit" id="up" name="up" value="Update Profile Values"/></p>
</form>
    </td>
    </td>
  </tr>
</table>
	<?php
	}
}
else
{
	$this->load->view('login_default');
}
?>
<?php $this->load->view('default_footer'); ?>
