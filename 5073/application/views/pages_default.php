<?php $this->load->view('default_header'); ?>
<?php
if($this->session->userdata('adauth') == "yes")
{
	?>
    <h2>Table listing</h2>
    	<p>
        <form><input type="button" class="submit tiny" value="Add New Page" onclick="window.location.href='<?php echo site_url('pages/add'); ?>';" /></form>
        </p>
<form action="" method="post">
  <table cellpadding="0" cellspacing="0" width="100%" class="sortable">
    <thead>
      <tr>
        <th width="10"><input type="checkbox" class="check_all" /></th>
        <th>Title</th>
        <th>Status</th>
        <th>Edit</th>
        <th>Delete</th>
        <th>Views</th>
      </tr>
    </thead>
    <tbody>
  <?php
  if($pages != "na")
  {
  	foreach($pages as $p)
  	{
  		?>
		<tr>
        <td><input type="checkbox" /></td>
    	<td><a href="http://www.xxxxxx.com/dating/<?php echo $p->url; ?>" target="_blank"><?php echo $p->title; ?></a></td>
    	<td><?php 
		if($p->status == 1)
		{
			?>
            <p class="onoffswitch">
    		<input type="checkbox" class="onoffbtn" checked="checked" />
  			</p>
            <?php
		}
		else
		{
			?>
            <p class="onoffswitch">
    		<input type="checkbox" class="onoffbtn" />
  			</p>
            <?php
		}
		?></td>
    	<td><a href="<?php echo site_url('pages/edit/'.$p->id); ?>">Edit</a></td>
    	<td class="delete"><a href="<?php echo site_url('pages/delete/'.$p->id); ?>"><img src="/5073/images/close.png" alt="Delete" title="Delete" /></a></td>
  		<td><?php echo $p->views; ?></td>
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
