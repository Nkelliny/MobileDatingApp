<?php $this->load->view('default_header'); ?>
<form id="expmailer" name="expmailer" action="<?php echo site_url('export/mailer'); ?>" method="post" enctype="application/x-www-form-urlencoded" target="_blank">
<table width="800" border="0" align="center" cellpadding="3" cellspacing="3">
  <tr>
    <td width="11%"><div align="right">Gender:</div></td>
    <td width="24%"><select name="gender" id="gender">
      <option value="18" selected="selected">Males</option>
      <option value="17">Females</option>
      <option value="19">Ladyboys</option>
      <option value="0">All</option>
    </select></td>
    <td width="8%"><div align="right">Date:</div></td>
    <td width="57%"><input name="year" type="text" id="year" value="2013" size="10" />
    <input name="month" type="text" id="month" size="10" />
    <input name="day" type="text" id="day" size="10" />
    YYYY - MM - DD</td>
  </tr>
  <tr>
    <td colspan="4"><div align="center">
      <input type="submit" name="subBtn" id="subBtn" value="Export List" />
    </div></td>
  </tr>
</table>
</form>
<?php $this->load->view('default_footer'); ?>