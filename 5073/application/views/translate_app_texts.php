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
<form id="transTextFrm" name="transTextFrm" action="<?php echo site_url('translate/updatetext'); ?>" method="post" enctype="application/x-www-form-urlencoded">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th>Name</th>
    <th>Key</th>
    <?php
	$clm_cnt = 2;
	foreach($langs as $l)
	{
		?>
        <th><?php echo ucwords($l); ?> Value</th>
        <?php
		$clm_cnt++;
	}
	?>
  </tr>
  <?php
  if($texts == "na")
  {
	  ?>
      <tr>
      <td colspan="<?php echo $clm_cnt + 1; ?>" align="center">There is currently no text in the database.</td>
      </tr>
      <?php
  }
  else
  {
	  foreach($texts as $t)
	  {
	  	?>
      	<tr>
        <td><?php echo $t->name; ?></td>
        <td><?php echo $t->key; ?></td>
        <?php
		foreach($langs as $l)
		{
			?>
            <td>
			<?php 
			$key = ($l == 'en' ? 'value' : $l.'_value');
			if(strlen($t->$key) > 50)
			{
				?>
                <textarea class="text" id="<?php echo $key; ?>_<?php echo $t->id; ?>" name="<?php echo $key; ?>_<?php echo $t->id; ?>" rows="4" cols="50"><?php echo $t->$key; ?></textarea>
                <?php
			}
			else
			{
				?>
                <input type="text" class="text" id="<?php echo $key; ?>_<?php echo $t->id; ?>" name="<?php echo $key; ?>_<?php echo $t->id; ?>" value="<?php echo $t->$key; ?>" size="50" />
                <?php
			}
			//echo ($l == "en" ? 'value' : $l.'_value'); 
			?>
            <input type="hidden" id="key_<?php echo $key; ?>_<?php echo $t->id; ?>" name="key_<?php echo $key; ?>_<?php echo $t->id; ?>" value="<?php echo $key; ?>" />
            </td>
            <?php
		}
		?>
        </tr>
      	<?php
	  }
  }
  ?>
</table>
<p>
<input class="submit tiny" type="submit" id="addLang" name="addLang" value="Update Existing Text"/>
</p>
</form>
	<?php
}
else
{
	$this->load->view('login_default');
}
?>
<?php $this->load->view('default_footer'); ?>
