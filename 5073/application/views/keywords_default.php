<?php $this->load->view('default_header'); ?>
<?php
if($this->session->userdata('adauth') == "yes")
{
	?>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="3">
    <form id="keyFrm" name="keyFrm" action="<?php echo site_url('keywords/doadd'); ?>" method="post" enctype="application/x-www-form-urlencoded">
    <table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td><div align="center">Word:
      <input name="word" type="text" id="word" size="30" />
    </div></td>
    <td><div align="center">Link:
      <input name="link" type="text" id="link" size="30" />
    </div></td>
    <td><div align="center">
      <input type="submit" name="btn" id="btn" value="Add Keyword" />
    </div></td>
  </tr>
  <tr>
    <td colspan="3">For links in the site ie... Browse girls use /girls you must have the first / for links in the site to work correctly! To link outside of the site you must use http://</td>
    </tr>
</table>
</form>
    </td>
  </tr>
  <tr>
    <td>Word</td>
    <td>Link</td>
    <td>Delete</td>
  </tr>
  <?php
  if($words != "na")
  {
  	foreach($words as $w)
  	{
  		?>
		<tr>
    	<td><?php echo $w->word; ?></td>
    	<td><?php echo $w->link; ?></td>
    	<td><a href="<?php echo site_url('keywords/delete/'.$w->id); ?>">Delete</a></td>
        </tr>
        <?php
	}
  }
  ?>
</table>
	<?php
}
else
{
	$this->load->view('login_default');
}
?>
<?php $this->load->view('default_footer'); ?>
