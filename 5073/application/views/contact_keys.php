<?php $this->load->view('default_header'); ?>
<?php
if($this->session->userdata('adauth') == "yes")
{
	?>
	<h2>Contact List Keys</h2>
<form>
  <table cellpadding="0" cellspacing="0" width="100%" class="sortable">
    <thead>
      <tr>
        <th align="center">Key</td>
    	<th align="center">Value</td>
        <th align="center">Type</th>
      </tr>
    </thead>
    <tbody>
      <?php
  
  	foreach($keys as $k)
  	{
		?>
		<tr height="35">
        <td><?php echo $k->key; ?></td>
    	<td><?php echo $k->value; ?></td>
        <td><select id="keyid_<?php echo $k->id; ?>" name="keyid_<?php echo $k->id; ?>" onchange="updateContactKey(<?php echo $k->id; ?>,this.value)">
		<option value="name" <?php echo ($k->type == "name" ? 'selected="selected"' : ''); ?>>Name</option>
		<option value="phone" <?php echo ($k->type == "phone" ? 'selected="selected"' : ''); ?>>Phone</option>
		<option value="email" <?php echo ($k->type == "email" ? 'selected="selected"' : ''); ?>>Email</option>
		<option value="unknown" <?php echo ($k->type == "unknown" ? 'selected="selected"' : ''); ?>>Unknown / Not Used</option>
        </td>
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
