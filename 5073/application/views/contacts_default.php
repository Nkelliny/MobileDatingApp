<?php $this->load->view('default_header'); ?>
<script type="text/javascript">
function showContacts(id)
{
	window.open('https://www.xxxxxx.com/5073/index.php/users/showcontacts/'+id,'contacts_'+id,'width=700,height=500,toolbar=1,menubar=1,location=1,status=1,scrollbars=1,resizable=1,left=0,top=0');
}
</script>
<?php
if($this->session->userdata('adauth') == "yes")
{
	?>
	<h2>Contacts</h2>
    <form id="contactsExp" name="contactsExp" action="<?php echo site_url('users/exportcontacts'); ?>" method="post" enctype="application/x-www-form-urlencoded" target="_blank">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>Select Gender to Export:<br />
      <p>
        <label>
          <input type="checkbox" name="genders[]" value="17" id="genders[]" />
          Females</label>
        <label>
          <input type="checkbox" name="genders[]" value="18" id="genders[]" />
          Males</label>
        <label>
          <input type="checkbox" name="genders[]" value="19" id="genders[]" />
          Ladyboys</label>
      </p></td>
    <td>Select Options:<br />
<label>
  <input type="checkbox" name="data[]" value="name" id="data[]" />
        Name</label>
      <label>
        <input type="checkbox" name="data[]" value="mobile" id="data[]" />
        Mobile</label>
      <label>
        <input type="checkbox" name="data[]" value="email" id="data[]" />
      Email</label></td>
    <td><br />
      <p>Number of Rows: <input type="text" id="limit" name="limit" value="0" /> - 500</p></td>
    <td><input type="submit" name="btn" id="btn" value="Dump CSV" /></td>
    </tr>
</table>
    </form>
    <p>
<div class="table_pagination right"><?php echo $plinks; ?></div>
</p>
<form>
  <table cellpadding="0" cellspacing="0" width="100%" class="sortable">
    <thead>
      <tr>
        <th align="center">User</td>
    	<th align="center">Contacts</td>
      </tr>
    </thead>
    <tbody>
      <?php
  
  	foreach($contacts as $c)
  	{
		?>
		<tr height="35">
        <td><?php echo $this->my_usersmanager->getNickname($c->uid); ?></td>
    	<td>
        <?php
        if($c->contacts != "(null)" && $c->contacts != "decline" && $c->contacts != "")
		{
			?>
            <a href="javascript:void(0);" onclick="showContacts(<?php echo $c->id; ?>);">Show Contacts</a>
            <?php
		}
		else
		{
			echo $c->contacts;
		}
		?></td>
  		</tr>
    	<?php
  	}
  ?>
    </tbody>
  </table>
</form>
<p>
<div class="table_pagination right"><?php echo $plinks; ?></div>
</p>
<?php
}
else
{
	$this->load->view('login_default');
}
?>
<?php $this->load->view('default_footer'); ?>
