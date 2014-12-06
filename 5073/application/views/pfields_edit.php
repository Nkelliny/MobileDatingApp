<?php $this->load->view('default_header'); ?>
<script type="text/javascript">
function addNewProfileValue()
{
	var newdiv = document.createElement('div');
    newdiv.innerHTML = '<br /><br />Value: <input type="text" class="text" id="val[]" name="val[]" />';
	document.getElementById('nvals').appendChild(newdiv);
}
</script>
<?php
if($this->session->userdata('adauth') == "yes")
{
	?>
    <h2>Update Profile Field</h2>
    <form id="fldUpdate" name="fldUpdate" action="<?php echo site_url('pfields/doedit'); ?>" method="post" enctype="application/x-www-form-urlencoded">
    <input type="hidden" id="id" name="id" value="<?php echo $field->id; ?>" />
	<p><label>Field Information</label><br />
    Name: <input type="text" class="text" id="fld_name" name="fld_name" value="<?php echo $field->name; ?>" /> Site Text: <input type="text" class="text" id="fld_txt" name="fld_txt" value="<?php echo $field->txt; ?>" /><br />
    </p>
    <p>
    <label>Values</label>
    <br />
	<?php
	if($values != "na")
	{
		foreach($values as $v)
		{
			?>
        	(<?php echo $v->id; ?>)<input type="text" class="text" id="cval[<?php echo $v->id; ?>]" name="cval[<?php echo $v->id; ?>]" value="<?php echo $v->name; ?>" />
			<?php
		}
	}
	?><br /><br />
    <span id="nvals">
    Value: <input type="text" class="text" id="val[]" name="val[]" />
    </span><br /><br />
    <a href="javascript:void(0);" onclick="addNewProfileValue();">Add new Value</a>
    </p>
    <p>
    <input type="submit" id="edtBtn" name="edtBtn" value="Update Data" />
    </p>
    <input type="hidden" id="cur_val" name="cur_val" value="0" />
    </form>
	<?php
}
else
{
	$this->load->view('login_default');
}
?>
<?php $this->load->view('default_footer'); ?>
