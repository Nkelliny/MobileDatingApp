<?php $this->load->view('default_header'); ?>
<?php
if($this->session->userdata('adauth') == "yes")
{
	?>
<script src="/5073/ckeditor/ckeditor.js"></script>

<p>
<h1>Add New FAQ</h1>
</p>
<form id="editFaq" name="editFaq" action="<?php echo site_url('faq/doedit'); ?>" method="post" enctype="application/x-www-form-urlencoded">
  <p>
    <label>Category:</label>
    <br />
    <select id="category" name="category">
      <option value="General FAQ" <?php echo ($faq->category == "General FAQ" ? 'selected="selected"' : ''); ?>>General FAQ</option>
      <option value="Android FAQ" <?php echo ($faq->category == "Android FAQ" ? 'selected="selected"' : ''); ?>>Android FAQ</option>
      <option value="IOS FAQ" <?php echo ($faq->category == "IOS FAQ" ? 'selected="selected"' : ''); ?>>IOS FAQ</option>
    </select>
  </p>
  <p>
    <label>FAQ Question:</label>
    <br />
    <input name="question" type="text" id="question" class="text" size="100" value="<?php echo $faq->question; ?>" />
  </p>
  <p>
    <label>Answer:</label>
    <br />
    <textarea class="ckeditor" name="answer" id="answer" cols="45" rows="5"><?php echo $faq->answer; ?></textarea>
    <!-- <input name="answer" type="text" id="answer" size="100" class="text" value="<?php echo $faq->answer; ?>" /> --> 
  </p>
  <p>
    <input type="hidden" id="id" name="id" value="<?php echo $faq->id; ?>" />
    <input class="submit tiny" type="submit" name="addPg" id="addPg" value="Update FAQ" />
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
