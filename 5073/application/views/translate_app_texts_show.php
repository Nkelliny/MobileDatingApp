<?php $this->load->view('default_header'); ?>
<?php
if($this->session->userdata('adauth') == "yes")
{
	?>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <?php
  if($this->session->flashdata('msg'))
  {
	  ?>
      <tr><td><?php echo $this->session->flashdata('msg'); ?></td></tr>
      <?php
  }
  ?>
  <tr>
    <td align="center">There are currently <?php echo count($langs); ?> in the system.</td>
  </tr>
</table>
<h2><a href="javascript:void(0);" onclick="if(document.getElementById('addText').style.display == 'none'){document.getElementById('addText').style.display='block';}else{document.getElementById('addText').style.display='none';}">Add New Text String</a></h2>
<form id="addText" name="addText" action="<?php echo site_url('translate/addtext'); ?>" method="post" enctype="application/x-www-form-urlencoded" style="display:none;">
<p>
<label>Name</label>
<br />
<input type="text" class="text" id="name" name="name" size="50" onblur="setKey(this.value);" />
</p>
<p>
<label>Key</label>
<br />
<input type="text" class="text" id="add_key" name="add_key" size="50" readonly="readonly" />
</p>
<?php
foreach($langs as $l)
{
	?>
    <p>
    <label><?php echo ucwords($l); ?> Value</label>
    <br />
    <input type="text" class="text" id="<?php echo ($l == "en" ? 'value' : $l.'_value'); ?>" name="<?php echo ($l == "en" ? 'value' : $l.'_value'); ?>" size="50" />
    </p>
    <?php
}
?>
<p>
<input class="submit tiny" type="submit" id="addLang" name="addLang" value="Add New Text"/>
</p>
</form>
<form id="langsel" name="langsel" action="<?php echo site_url('translate/iap'); ?>" method="post" enctype="application/x-www-form-urlencoded">
<p><label>Select Language to Edit <select id="lang" name="lang">
<option value="0">Select Language</option>
<?php
foreach($langs as $l)
{
	?>
    <option value="<?php echo $l; ?>"><?php echo $l; ?></option>
    <?php
}
?>
</select>
</label>
</p>
<p>
<input class="submit tiny" type="submit" id="el" name="el" value="Edit Language"/>
</p>
</form>
<p>&nbsp;</p>
<?php
if($texts != "0")
{
	?>
    <form id="transTextFrm" name="transTextFrm" action="<?php echo site_url('translate/updatetext'); ?>" method="post" enctype="application/x-www-form-urlencoded">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th>Name</th>
    <th>Key</th>
    <th>Value</th>
  </tr>
  <?php
	foreach($texts as $t)
	{
	?>
		<tr>
		<td><?php echo $t->name; ?></td>
		<td><?php echo $t->key; ?></td>
        <td>
        <?php
		if(strlen($t->value) > 50)
		{
			?>
			<textarea class="text" id="value[<?php echo $t->id; ?>]" name="value[<?php echo $t->id; ?>]" rows="4" cols="50"><?php echo $t->value; ?></textarea>
			<?php
		}
		else
		{
		?>
			<input type="text" class="text" id="value[<?php echo $t->id; ?>]" name="value[<?php echo $t->id; ?>]" value="<?php echo $t->value; ?>" size="50" />
		<?php
		}
		?>
        </td>
        </tr>
      	<?php
	 }
  ?>
</table>
<p>
<input type="hidden" id="ls" name="ls" value="<?php echo $ls; ?>" />
<input class="submit tiny" type="submit" id="addLang" name="addLang" value="Update Existing Text"/>
</p>
</form>
    <?php
}
?>
	<?php
}
else
{
	$this->load->view('login_default');
}
?>
<?php $this->load->view('default_footer'); ?>
