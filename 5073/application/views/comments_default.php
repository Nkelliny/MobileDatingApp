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
	/*
	[id] => 3
	[owner] => 12
	[sender] => 47
	[added] => 2013-01-07 23:22:43
	[comment] => Your so lovely and I think I love you!
	[status] => 1
	*/
	?>
    <p>
    <div class="table_pagination right"><?php echo $plinks; ?></div>
	</p>
    <form id="comMass" name="comMass" action="<?php echo site_url('comments/mass'); ?>" method="post" enctype="application/x-www-form-urlencoded">
    <table width="100%" border="0" cellspacing="3" cellpadding="3">
      <tr>
        <th><div align="center"><label><input type="checkbox" name="sAll" onclick="selectAll(this)" /> Check All</label></div></th>
        <th><div align="center"><strong>Sent To</strong></div></th>
        <th><div align="center"><strong>Sent From</strong></div></th>
        <th><div align="center"><strong>Sent</strong></div></th>
        <th><div align="center"><strong>Comment</strong></div></th>
        <th><div align="center"><strong>Status</strong></div></th>
      </tr>
      <tbody>
      <?php
	  $x=0;
	  foreach($comments as $c)
	  {
		  if($c->sender > 0)
		  {
		  ?>
      		<tr <?php echo ($c->status == "2" ? 'bgcolor="#FF0000"' : ($x%2==0 ? '' : 'bgcolor="#CCC"')); ?>>
        	<td><div align="center"><input type="checkbox" name="id[]" id="id_<?php echo $c->id; ?>" value="<?php echo $c->id; ?>" /></td>
            <td><a href="http://www.xxxxxx.com/profile/<?php echo $this->my_usersmanager->getProfileUrl($c->owner); ?>" target="_blank"><?php echo $this->my_usersmanager->getNickname($c->owner); ?></a></td>
        	<td><a href="http://www.xxxxxx.com/profile/<?php echo $this->my_usersmanager->getProfileUrl($c->sender); ?>" target="_blank"><?php echo $this->my_usersmanager->getNickname($c->sender); ?></a></td>
        	<td align="center"><?php echo $c->added; ?></td>
        	<td><?php echo $c->comment; ?></td>
        	<td align="center"><select id="com_<?php echo $c->id; ?>" name="com_<?php echo $c->id; ?>" onchange="doCommentStatus(<?php echo $c->id; ?>,this.value);">
            <option value="1" <?php echo ($c->status == "1" ? 'selected="selected"' : ''); ?>>Approved</option>
            <option value="2" <?php echo ($c->status == "2" ? 'selected="selected"' : ''); ?>>Needs Approved</option>
            <option value="3" <?php echo ($c->status == "3" ? 'selected="selected"' : ''); ?>>Deleted</option>
            </select>
            </td>
      		</tr>
            <?php
			$x++;
		  }
	  }
	  ?>
      <tr>
      <td colspan="5" align="right">
      <select id="doMass" name="doMass" onchange="document.getElementById('comMass').submit();">
      <option value="na">Select Action</option>
      <option value="app">Approve Selected</option>
      <option value="del">Delete Selected</option>
      </select>
      </td>
      </tr>
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
