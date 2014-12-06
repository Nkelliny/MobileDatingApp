<?php $this->load->view('default_header'); ?>
<?php
if($this->session->userdata('adauth') == "yes")
{
	?>
    <script src="/5073/ckeditor/ckeditor.js"></script>
    <p><h1>Add New Page</h1></p>
    <form id="newPage" name="newPage" action="<?php echo site_url('pages/doadd'); ?>" method="post" enctype="application/x-www-form-urlencoded">
    <p><label>Meta Title:</label>
    <br />
    <input name="mtitle" type="text" id="mtitle" size="70" value="" />
    </p>
    <p><label>Meta Description:</label><br />
	<textarea name="mdesc" id="mdesc" cols="45" rows="5"></textarea></p>
    <p>
    <label>Meta Keywords:</label>
    <br />
	<input name="mkey" type="text" id="mkey" size="70" />
    </p>
    <p>
    <label>Page Title:</label>
    <br />
	<input name="title" type="text" id="title" size="70" /></p>
    <p>
    <label>Url:</label>
    <br />
	<input name="url" type="text" id="url" size="70" />
    </p>
    <p>
  <label>H1:</label><br />
	<input name="h1" type="text" id="h1" size="70" />
    </p>
    <p>
    <label>H2:</label><br />
	<input name="h2" type="text" id="h2" size="70" />
    </p>
    <p>
    <label>H3:</label><br />
	<input name="h3" type="text" id="h3" size="70" /></p>
    <p>
    <label>Body:</label><br />
	<textarea class="ckeditor" name="body" id="body" cols="45" rows="5"></textarea>
    </p>
  <p>
      <input class="submit tiny" type="submit" name="addPg" id="addPg" value="Add Page" />
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
