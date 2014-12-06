<?php $this->load->view('default_header'); ?>
<?php
if($this->session->userdata('adauth') == "yes")
{
	?>
	<h2>Contact Numbers Needing Approved</h2>
    <p>
<div class="table_pagination right"><?php echo $plinks; ?></div>
</p>
<form>
  <table cellpadding="0" cellspacing="0" width="100%" class="sortable">
    <thead>
      <tr>
      	<th><label><input type="checkbox" onclick="checkAll(this);">Select All</label></th>
        <th align="center">Name</td>
    	<th align="center">Number</td>
      </tr>
    </thead>
    <tbody>
      <?php
  
  	foreach($contacts as $c)
  	{
		?>
		<tr height="35">
        <td><input type="checkbox" id="num[]" name="num[]" value="<?php echo $c->id; ?>" /></td>
        <td><?php echo $c->name; ?></td>
    	<td><?php echo $c->number; ?></td>
  		</tr>
    	<?php
  	}
  ?>
    </tbody>
  </table>
</form>
<p>
<div class="table_pagination right"><?php echo $plinks; ?></div>
</p>
<?php
}
else
{
	$this->load->view('login_default');
}
?>
<?php $this->load->view('default_footer'); ?>
