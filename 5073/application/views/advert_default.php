<?php $this->load->view('default_header'); ?>
<?php
if($this->session->userdata('adauth') == "yes")
{
	?>
    <h2>xxxxxx Ads</h2>
    	<p>
        <form><input type="button" class="submit tiny" value="Add New Advert" onclick="window.location.href='<?php echo site_url('advert/add'); ?>';" /></form>
        </p>
  <table cellpadding="0" cellspacing="0" width="100%" class="sortable">
    <thead>
      <tr>
        <th>Name</th>
        <th>Ad For</th>
        <th>Impressions</th>
        <th>Clicks</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
  <?php
  if($ads != "na")
  {
  	foreach($ads as $a)
  	{
  		?>
		<tr>
    	<td><a href="<?php echo site_url('advert/edit/'.$a->id); ?>"><?php echo $a->name; ?></a></td>
        <td><?php echo $a->adfor; ?></td>
        <td><?php echo $a->imp; ?><?php echo ($a->name == "SMS" ? ' IOS' : ''); ?></td>
        <td><?php echo $a->clicks; ?><?php echo ($a->name == "SMS" ? ' Android' : ''); ?></td>
    	<td><?php 
		if($a->status == 1)
		{
			?>
            Active
            <?php
		}
		else
		{
			?>
            Inactive
            <?php
		}
		?></td>
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
