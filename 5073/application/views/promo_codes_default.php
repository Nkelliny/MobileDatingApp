<?php $this->load->view('default_header'); ?>
<?php
if($this->session->userdata('adauth') == "yes")
{
	?>
    <h2>Add Promo Code Block</h2>
        <form id="addCodes" name="addCodes" method="post" action="<?php echo site_url('promos/addcodes'); ?>" enctype="application/x-www-form-urlencoded">
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>
      <label>First Name:</label><br />
      <input type="text" name="fname" id="fname" class="text" size="30" /></td>
    <td><label>Last Name:</label><br />
      <input type="text" name="lname" id="lname" class="text" size="30" /></td>
    <td><label>Phone:</label><br />
      <input type="text" name="phone" id="phone" class="text" size="30" /></td>
    <td><label>Type:</label><br />
      <select name="type" id="type">
      <option value="">Select Code Type</option>
      <option value="Pro-Unlimited">Pro Unlimited</option>
      <option value="Pro-Month">Pro Month</option>
      <option value="Pro-Week">Pro Week</option>
      <option value="Tracking">Tracking</option>
      </select></td>
    <td><label>Block Size:</label><br />
      <input type="text" name="ttl" id="ttl" class="text" size="4" /></td>
  </tr>
  <tr>
    <td colspan="5"><div align="center">
      <input type="submit" name="button" id="button" value="Add Block" />
    </div></td>
    </tr>
</table>
        </form>
        <p>&nbsp;</p>
        <?php
		/*
		[id] => 1
		[fname] => xxxx
		[lname] => Stout
		[phone] => 0917099984
		[code] => 9LQNEU
		[type] => Pro-Unlimited
		[used] => 0
		[status] => 1
		*/
		?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
        <td>Name</td>
        <td>Type</td>
        <td>Code</td>
        <td>Used</td>
        <td>Status</td>
        <td>Delete</td>
        </tr>
        <?php
		foreach($promos as $p)
		{
			?>
        <tr>
        <td><?php echo $p->fname . ' ' . $p->lname; ?></td>
        <td><?php echo $p->type; ?></td>
        <td><?php echo $p->code; ?></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        </tr>
        <?php
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
