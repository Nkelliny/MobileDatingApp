<?php $this->load->view('default_header'); ?>
<?php
if($this->session->userdata('adauth') == "yes")
{
	?>

<table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td align="center"><fieldset>
        <legend>Bad Words List</legend>
        <form id="badWordsFrm" name="badWordsFrm" action="<?php echo site_url('badwords/bwlist'); ?>" method="post" enctype="application/x-www-form-urlencoded">
          <table width="100%" border="0" cellspacing="3" cellpadding="3">
            <tr>
              <td align="center"><textarea name="badwords" cols="80" rows="10"><?php echo $badwords->bword_list; ?></textarea></td>
            </tr>
            <tr>
              <td align="center"><input type="submit" name="updateBtn" id="updateBtn" value="Update Badwords List" /></td>
            </tr>
          </table>
        </form>
      </fieldset></td>
  </tr>
  <tr>
    <td><fieldset>
        <legend>Bad Names List</legend>
        <form id="badNamesFrm" name="badNamesFrm" action="<?php echo site_url('badwords/bnlist'); ?>" method="post" enctype="application/x-www-form-urlencoded">
          <table width="100%" border="0" cellspacing="3" cellpadding="3">
            <tr>
              <td align="center"><textarea name="badwords" cols="80" rows="10"><?php echo $badnames->bword_list; ?></textarea></td>
            </tr>
            <tr>
              <td align="center"><input type="submit" name="updateBtn" id="updateBtn" value="Update Bad Names List" /></td>
            </tr>
          </table>
        </form>
      </fieldset></td>
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
