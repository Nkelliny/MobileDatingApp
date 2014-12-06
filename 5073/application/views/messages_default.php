<?php $this->load->view('default_header'); ?>
<script type="text/javascript">
function selectAll(x) 
{
	for(var i=0,l=x.form.length; i<l; i++)
	if(x.form[i].type == 'checkbox' && x.form[i].name != 'sAll')
	x.form[i].checked=x.form[i].checked?false:true
}
</script>
<?php
if($this->session->userdata('adauth') == "yes")
{
	?>
    <p>
    <div class="table_pagination right"><?php echo $plinks; ?></div>
	</p>
    <form id="comMass" name="comMass" action="<?php echo site_url('messages/mass'); ?>" method="post" enctype="application/x-www-form-urlencoded">
    <table width="100%" border="0" cellspacing="3" cellpadding="3">
      <tr>
        <th><div align="center"><strong>Sent To</strong></div></th>
        <th><div align="center"><strong>Sent From</strong></div></th>
        <th><div align="center"><strong>Sent</strong></div></th>
        <th><div align="center"><strong>Message</strong></div></th>
        <th><div align="center"><strong>Update</strong></div></th>
        <th><div align="center"><strong>Delete</strong></div></th>
      </tr>
      <tbody>
      <?php
	  $x=0;
	  foreach($messages as $c)
	  {
		  if($c->from > 0 && $c->to > 0)
		  {
		  ?>
      		<tr id="msg_row_<?php echo $c->id; ?>">
            <td><a href="http://www.xxxxxx.com/5073/index.php/users/info/<?php echo $c->to; ?>"><?php echo $this->my_usersmanager->getNickname($c->to); ?></a></td>
        	<td><a href="http://www.xxxxxx.com/5073/index.php/users/info/<?php echo $c->from; ?>"><?php echo $this->my_usersmanager->getNickname($c->from); ?></a></td>
        	<td align="center"><?php echo $c->sent; ?></td>
        	<td><textarea cols="50" rows="5" id="msg_<?php echo $c->id; ?>"><?php echo $c->msg; ?></textarea></td>
        	<td align="center"><a href="javascript:void(0);" onclick="updateMessage(<?php echo $c->id; ?>);">Update</a></td>
            <td align="center"><a href="javascript:void(0);" onclick="deleteMessage(<?php echo $c->id; ?>);">Delete</a></td>
      		</tr>
            <?php
			$x++;
		  }
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
