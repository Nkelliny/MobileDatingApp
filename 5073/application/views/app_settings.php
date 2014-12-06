<?php $this->load->view('default_header'); ?>
<?php
if($this->session->userdata('adauth') == "yes")
{
	?>

<h2>App Settings</h2>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th>Setting</th>
    <th width="450">Description</th>
    <th>Value</th>
    <th>Update</th>
  </tr>
  <tr>
    <td><?php echo $settings[0]->name; ?></td>
    <td><?php echo $settings[0]->desc; ?></td>
    <td><select id="val_<?php echo $settings[0]->id; ?>" name="val_<?php echo $settings[0]->id; ?>">
	<option value="false" <?php echo ($settings[0]->value == "false" ? 'selected="selected"' : ''); ?>>False</option>
	<option value="true" <?php echo ($settings[0]->value == "true" ? 'selected="selected"' : ''); ?>>True</option></td>
    <td><a href="javascript:void(0);" onclick="updateSettings(<?php echo $settings[0]->id; ?>);">UPDATE</a></td>
  </tr>
  <tr>
    <td><?php echo $settings[1]->name; ?></td>
    <td><?php echo $settings[1]->desc; ?></td>
    <td><select id="val_<?php echo $settings[1]->id; ?>" name="val_<?php echo $settings[1]->id; ?>">
    <?php
	for($a=1;$a<50;$a++)
	{
		?>
        <option value="<?php echo $a; ?>" <?php echo ($a == $settings[1]->value ? 'selected="selected"' : ''); ?>><?php echo $a; ?></option>
        <?php
	}
	?>
    </select>
    </td>
    <td><a href="javascript:void(0);" onclick="updateSettings(<?php echo $settings[1]->id; ?>);">UPDATE</a></td>
  </tr>
  <tr>
    <td><?php echo $settings[2]->name; ?></td>
    <td><?php echo $settings[2]->desc; ?></td>
    <td>
    <select id="val_<?php echo $settings[2]->id; ?>" name="val_<?php echo $settings[2]->id; ?>">
    <?php
	for($a=1;$a<50;$a++)
	{
		?>
        <option value="<?php echo $a; ?>" <?php echo ($a == $settings[2]->value ? 'selected="selected"' : ''); ?>><?php echo $a; ?></option>
        <?php
	}
	?>
    </select>
    </td>
    <td><a href="javascript:void(0);" onclick="updateSettings(<?php echo $settings[2]->id; ?>);">UPDATE</a></td>
  </tr>
  <tr>
    <td><?php echo $settings[3]->name; ?></td>
    <td><?php echo $settings[3]->desc; ?></td>
    <td>
    <?php
	$values = explode('|',$settings[3]->value); 
	?>
    Hours:&nbsp;<select id="hrs_<?php echo $settings[3]->id; ?>" name="hrs_<?php echo $settings[3]->id; ?>">
    <?php
	for($a=1;$a<13;$a++)
	{
		?>
        <option value="-<?php echo $a; ?>" <?php echo ($values[0] == "-".$a."" ? 'selected="selected"' : ''); ?>>-<?php echo $a; ?> Hours</option>
        <?php
	}
	?>
    </select>&nbsp;Mins:&nbsp;<select id="mins_<?php echo $settings[3]->id; ?>" name="<?php echo $settings[3]->id; ?>">
    <?php
	for($a=1;$a<60;$a++)
	{
		?>
        <option value="<?php echo $a; ?>" <?php echo ($a == $values[1] ? 'selected="selected"' : ''); ?>><?php echo $a; ?> Mins</option>
        <?php
	}
	?>
    </select>
    </td>
    <td><a href="javascript:void(0);" onclick="updateSettings(<?php echo $settings[3]->id; ?>);">UPDATE</a></td>
  </tr>
  <tr>
    <td><?php echo $settings[4]->name; ?></td>
    <td><?php echo $settings[4]->desc; ?></td>
    <td>
    <?php
	$values = explode('|',$settings[4]->value);
	?>
    Days:&nbsp;<select id="days_<?php echo $settings[4]->id; ?>" name="days_<?php echo $settings[4]->id; ?>">
    <?php
	for($a=1;$a<10;$a++)
	{
		?>
        <option value="-<?php echo $a; ?>" <?php echo ($values[0] == "-".$a."" ? 'selected="selected"' : ''); ?>>-<?php echo $a; ?> Days</option>
        <?php
	}
	?>
    </select>&nbsp;Hours:&nbsp;<select id="hours_<?php echo $settings[4]->id; ?>" name="hours_<?php echo $settings[4]->id; ?>">
    <?php
	for($a=1;$a<13;$a++)
	{
		?>
        <option value="<?php echo $a; ?>" <?php echo ($a == $values[1] ? 'selected="selected"' : ''); ?>><?php echo $a; ?> Hours</option>
        <?php
	}
	?>
    </select>&nbsp;Mins:<select id="mins_<?php echo $settings[4]->id; ?>" name="mins_<?php echo $settings[4]->id; ?>">
    <?php
	for($a=1;$a<60;$a++)
	{
		?>
        <option value="<?php echo $a; ?>" <?php echo ($a == $values[2] ? 'selected="selected"' : ''); ?>><?php echo $a; ?> Mins</option>
        <?php
	}
	?>
    </select>
    </td>
    <td><a href="javascript:void(0);" onclick="updateSettings(<?php echo $settings[4]->id; ?>);">UPDATE</a></td>
  </tr>
  <tr>
    <td><?php echo $settings[5]->name; ?></td>
    <td><?php echo $settings[5]->desc; ?></td>
    <td><input type="text" id="val_<?php echo $settings[5]->id; ?>" name="val_<?php echo $settings[5]->id; ?>" value="<?php echo $settings[5]->value; ?>" /></td>
    <td><a href="javascript:void(0);" onclick="updateSettings(<?php echo $settings[5]->id; ?>);">UPDATE</a></td>
  </tr>
 <tr>
    <td><?php echo $settings[6]->name; ?></td>
    <td><?php echo $settings[6]->desc; ?></td>
    <td><input type="text" id="val_<?php echo $settings[6]->id; ?>" name="val_<?php echo $settings[6]->id; ?>" value="<?php echo $settings[6]->value; ?>" /></td>
    <td><a href="javascript:void(0);" onclick="updateSettings(<?php echo $settings[6]->id; ?>);">UPDATE</a></td>
  </tr>
</table>
<?php
//print_r($settings);
}
else
{
	$this->load->view('login_default');
}
?>
<?php $this->load->view('default_footer'); ?>
