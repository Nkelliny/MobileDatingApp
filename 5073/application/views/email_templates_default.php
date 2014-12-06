<?php $this->load->view('default_header'); ?>
<?php
if($this->session->userdata('adauth') == "yes")
{
?>
<h2>Email Templates</h2>
<p>
        <form><input type="button" class="submit tiny" value="Add New Template" onclick="window.location.href='<?php echo site_url('etemps/add'); ?>';" /></form>
        </p>
    <form method="post" id="main">
  <table cellpadding="0" cellspacing="0" width="100%" class="sortable">
    <thead>
      <tr>
        <th align="center">Template Name</td>
    	<th align="center">Edit</td>
      </tr>
    </thead>
    <tbody>
    <?php
	foreach($templates as $t)
	{
		?>
        <tr>
        <td><?php echo $t->name; ?></td>
        <td><a href="<?php echo site_url('etemps/edit/'.$t->id); ?>"><img src="/5073/images/b_edit.png" width="16" height="16" alt="Edit" title="Edit" border="0" /></a></td>
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
