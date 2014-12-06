<?php $this->load->view('default_header'); ?>
<?php
if($this->session->userdata('adauth') == "yes")
{
	/*
	[id] => 8
	[name] => Children
	[txt] => Children
	[th_txt] => 
	[status] => 1
	[site] => y
	[mobile] => n
	*/
	?>
	
	<?php
}
else
{
	$this->load->view('login_default');
}
?>
<?php $this->load->view('default_footer'); ?>
