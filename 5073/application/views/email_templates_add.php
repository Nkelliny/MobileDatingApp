<?php $this->load->view('default_header'); ?>
<?php
if($this->session->userdata('adauth') == "yes")
{
	?>
    <script src="/5073/ckeditor/ckeditor.js"></script>
    <p><h1>Add New Template</h1></p>
    <form id="newPage" name="newPage" action="<?php echo site_url('etemps/doadd'); ?>" method="post" enctype="application/x-www-form-urlencoded">
    <p><label>Template Title:</label>
    <br />
    <input name="name" type="text" id="name" size="70" value="" />
    </p>
    <p><label>Subject:</label><br />
	<input name="subject" type="text" id="subject" size="70" value="" />
    </p>
    <p>
    <label>Legend</label><br />
    {%%subject%%} --> Subject Inside message<br />
	{%%msgfrom%%} --> Who the message is from<br />
	{%%datesent%%} --> Date Message Sent<br />
    {%%retlink%%} --> Return link, if used<br />
    <em>If these values are not used the message will not work correctly</em>
    </p>
    <p>
    <label>Body:</label><br />
	<textarea class="ckeditor" name="body" id="body" cols="45" rows="5"><?php echo $default_data; ?></textarea>
    </p>
  <p>
      <input class="submit tiny" type="submit" name="addPg" id="addPg" value="Add Template" />
    </p>
    </form>
	<?php
}
else
{
	$this->load->view('login_default');
}
?>
<?php $this->load->view('default_footer'); ?>
