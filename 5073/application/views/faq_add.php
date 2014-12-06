<?php $this->load->view('default_header'); ?>
<?php
if($this->session->userdata('adauth') == "yes")
{
	?>
    <script src="/5073/ckeditor/ckeditor.js"></script>
    <p><h1>Add New FAQ</h1></p>
    <form id="newPage" name="newPage" action="<?php echo site_url('faq/doadd'); ?>" method="post" enctype="application/x-www-form-urlencoded">
    <p>
    <label>Category:</label><br />
    <select id="category" name="category">
    <option value="General FAQ" selected="selected">General FAQ</option>
    <option value="Android FAQ">Android FAQ</option>
    <option value="IOS FAQ">IOS FAQ</option>
    </select>
    </p>
    <p>
    <label>FAQ Question:</label>
    <br />
	<input name="question" type="text" id="question" class="text" size="100" /></p>
    <p>
    <label>Answer:</label><br />
	<input name="answer" type="text" id="answer" size="100" class="text" /></p>
    </p>
  <p>
      <input class="submit tiny" type="submit" name="addPg" id="addPg" value="Add FAQ" />
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
