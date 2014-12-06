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
  <td>
  <strong>Add New Language</strong><br />
  <form id="addLangForm" name="addLangForm" action="<?php echo site_url('translate/addlang'); ?>" method="post" enctype="application/x-www-form-urlencoded">
  <input class="text" size="4" type="text" id="lang" name="lang" /> <em>Enter Lang Code (EN,TH)</em> <br /><br />
<input class="submit tiny" type="submit" id="addLang" name="addLang" value="Add New Lang"/>
  </form>
  </td>
  </tr>
  <tr>
    <td align="center"><a href="<?php echo site_url('translate/pfields'); ?>">Profile Fields</a> | <a href="<?php echo site_url('translate/iap'); ?>">In App Text</a> | Emails </td>
  </tr>
</table>
	<?php
}
else
{
	$this->load->view('login_default');
}
?>
<?php $this->load->view('default_footer'); ?>
