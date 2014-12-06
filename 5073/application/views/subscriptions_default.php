<?php $this->load->view('default_header'); ?>
<?php
if($this->session->userdata('adauth') == "yes")
{
	?>
    <h2>Subscriptions</h2>
<form action="" method="post">
  <table cellpadding="0" cellspacing="0" width="100%" class="sortable">
    <thead>
      <tr>
        <th>Name</th>
        <th>Description</th>
        <th>P ID</th>
        <th>Price</th>
        <th>Update</th>
      </tr>
    </thead>
    <tbody>
  <?php
  if($subs != "na")
  {
  	foreach($subs as $s)
  	{
  		?>
		<tr>
    	<td><a href="<?php echo site_url('subscriptions/edit/'.$s->id); ?>"><?php echo $s->name; ?></a></td>
    	<td><?php echo $s->desc; ?></td>
    	<td><?php echo $s->productid; ?></td>
    	<td><input id="price_<?php echo $s->id; ?>" name="price_<?php echo $s->id; ?>" type="text" class="text" value="<?php echo $s->price; ?>" /></td>
  		<td>
        <input class="submit tiny" type="button" name="update" id="update" value="Update" onclick="updateSubPrice(<?php echo $s->id; ?>);" />
        </td>
        </tr>
        <?php
	}
  }
  ?>
  </tbody>
</table>
	<?php
}
else
{
	$this->load->view('login_default');
}
?>
<?php $this->load->view('default_footer'); ?>
