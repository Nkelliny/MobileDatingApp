<?php $this->load->view('default_header'); ?>
<?php
if($this->session->userdata('adauth') == "yes")
{
	?>
    <script type="text/javascript">
	function deleteFaq(id)
	{
		var ok = confirm('Are you sure you want to delete this faq? This action can not be undone!');
		if(ok)
		{
			window.location.assign("https://www.xxxxxx.com/5073/index.php/faq/delete/"+id);
		}
	}
    </script>
    <h2>FAQ</h2>
    	<p>
        <form><input type="button" class="submit tiny" value="Add New FAQ" onclick="window.location.href='<?php echo site_url('faq/add'); ?>';" /></form>
        </p>
<form action="" method="post">
  <table cellpadding="0" cellspacing="0" width="100%">
    <thead>
      <tr>
        <th>Question</th>
        <th>Category</th>
        <th>Status</th>
        <th>Edit</th>
        <th>Delete</th>
      </tr>
    </thead>
    <tbody>
  <?php
  if($faqs != "na")
  {
  	foreach($faqs as $f)
  	{
  		?>
		<tr>
    	<td><a href="http://www.xxxxxx.com/faq/<?php echo $f->url; ?>" target="_blank"><?php echo $f->question; ?></a></td>
    	<td><?php echo $f->category; ?>
        <td><?php 
		if($f->status == 1)
		{
			?>
           <a href="javascript:void(0);" onclick="window.location.assign('https://www.xxxxxx.com/5073/index.php/faq/status/<?php echo $f->id; ?>/2');"><img src="/5073/images/active.png" width="80" height="26" /></a>
  			</p>
            <?php
		}
		else
		{
			?>
            <a href="javascript:void(0);" onclick="window.location.assign('https://www.xxxxxx.com/5073/index.php/faq/status/<?php echo $f->id; ?>/1');"><img src="/5073/images/inactive.png" width="80" height="26" /></a>
  			</p>
            <?php
		}
		?></td>
    	<td><a href="<?php echo site_url('faq/edit/'.$f->id); ?>">Edit</a></td>
    	<td><a href="javascript:void(0);" onclick="deleteFaq(<?php echo $f->id; ?>);"><img src="/5073/images/close.png" alt="Delete" title="Delete" /></a></td>
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
