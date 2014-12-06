<?php $this->load->view('default_header'); ?>
<?php
if($this->session->userdata('adauth') == "yes")
{
	?>
    <div style="float:left; width:100%;">
    <h3>Generate Photos for mailer</h3>
    </div>
    <div style="float:left; width:100%;">
    <form id="mailPhotos" name="mailPhotos" action="<?php echo site_url('photos/domailer'); ?>" method="post" enctype="application/x-www-form-urlencoded">
    <table width="100%" border="0" cellspacing="3" cellpadding="3">
  <tr>
    <td valign="top"><div align="center">Gender:
      <select name="gender" id="gender">
        <option value="18">Male</option>
        <option value="17">Female</option>
        <option value="19">Ladyboy</option>
      </select>
    </div></td>
    <td valign="top"><div align="center">Start Date:
      <input name="year" type="text" id="year" value="2013" size="6" />
      <input name="month" type="text" id="month" size="6" />
      <input name="day" type="text" id="day" size="6" />
      <br />
      Enter YYYY MM DD</div></td>
    <td valign="top"><div align="center">Colums:
      <input name="cols" type="text" id="cols" size="5" />
    </div></td>
    <td valign="top"><div align="center">Rows:
      <input name="rows" type="text" id="rows" size="5" />
    </div></td>
    <td valign="top"><div align="center">Img Width:
      <input name="width" type="text" id="width" size="6" />
    </div></td>
    <td valign="top"><div align="center">Img Height:
      <input name="height" type="text" id="height" size="6" />
    </div></td>
  </tr>
  <tr>
    <td colspan="6"><div align="center">
      <input type="submit" name="getImages" id="getImages" value="Get Code" />
    </div></td>
    </tr>
</table>
    </form>
    </div>
<?php
}
else
{
	$this->load->view('login_default');
}
?>
<?php $this->load->view('default_footer'); ?>