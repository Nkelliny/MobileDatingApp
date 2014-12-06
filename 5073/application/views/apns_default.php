<?php $this->load->view('default_header'); ?>
<script type="text/javascript">
function loadApnsUsers()
{
	var users = document.getElementById('selectUsers').value;
	if(users == "all female free" || users == "all male free" || users == "all ladyboy free" || users == "all free" || users == "all female pro" || users == "all male pro" || users == "all ladyboy pro" || users == "all pro")
	{
		document.getElementById('usersList').innerHTML = "You have selected " + users + " users.";
	}
	else
	{
		document.getElementById('usersList').innerHTML = "Loading users, please wait...";
		var params = "users="+users;
		var url = "/5073/index.php/ajax/getapnsusers";
		var http = new XMLHttpRequest();
		http.open('POST',url,true);
		http.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		http.onreadystatechange = function()
		{
			if(http.readyState == 4 && http.status == 200)
			{
				var html = http.responseText;
				document.getElementById('usersList').innerHTML = html;
			}
		}
		http.send(params);
	}
}
</script>
<?php
if($this->session->userdata('adauth') == "yes")
{
	?>
    <form id="apnsFrm" method="post" action="<?php echo site_url('apns/sendApplePushB'); ?>" enctype="application/x-www-form-urlencoded">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <?php
  if($success != "na")
  {
	  ?>
      <tr>
      <td colspan="2">
      <strong>######################################### SUCCESSFUL PUSH #########################################</strong><br />
      <?php echo $success; ?><br /><br />
      <strong>######################################### FAILED PUSH #########################################</strong><br />
      <?php echo $failed; ?><br /><br />
      <?php echo $res; ?>
      </td>
      </tr>
      <?php
  }
  ?>
  <tr>
    <td width="38%"><div align="center">Options</div></td>
    <td width="62%"><div align="center"><em>Members(IOS Members only)</em></div></td>
  </tr>
  <tr>
    <td valign="top"><p>
    <label>Select Users:</label><br />
	<select id="selectUsers" name="selectUsers" onchange="loadApnsUsers();">
    <optgroup label="FREE USERS">
    <option value="na" selected="selected">Select Users</option>
    <option value="all female free">All Females</option>
    <option value="all male free">All Males</option>
    <option value="all ladyboy free">All Ladyboys</option>
    <option value="female free">Select Females</option>
    <option value="male free">Select Males</option>
    <option value="ladyboy free">Select Ladyboys</option>
    <option value="all free">All Users</option>
    </optgroup>
    <optgroup label="PRO USERS">
    <option value="all female pro">All Females</option>
    <option value="all male pro">All Males</option>
    <option value="all ladyboy pro">All Ladyboys</option>
    <option value="female pro">Select Females</option>
    <option value="male pro">Select Males</option>
    <option value="ladyboy pro">Select Ladyboys</option>
    <option value="all pro">All Users</option>
    </optgroup>
    </select>
    </p>
    <p><label>Message</label><br />
	<textarea name="msg" cols="50" rows="5" class="text" id="msg"></textarea>
    </p>
    <p>
    <input type="submit" id="sub" name="sub" value="Send Notification" />
    </p></td>
    <td valign="top">
    <div id="usersList" style="float:left; width:100%; height:500px; overflow-x:hidden; overflow-y:auto;">
    Please choose the users you want to send a push notification to on the left.
    </div>
    </td>
  </tr>
</table>
</form>
	<?php
}
else
{
	$this->load->view('login_default');
}
?>
<?php $this->load->view('default_footer'); ?>
