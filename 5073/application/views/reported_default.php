<?php $this->load->view('default_header'); ?>
<?php
if($this->session->userdata('adauth') == "yes")
{
	//print_r($reports);
	?>
<h2>Reported Users</h2>
<table cellpadding="0" cellspacing="0" width="100%" class="sortable">
  <thead>
    <tr>
      <th align="center">Reported User </th>
      <th align="center">Reported By </th>
      <th align="center">Reason </th>
      <th align="center">Date Reported </th>
      <th align="center">Status</th>
      <th align="center">Delete</th>
    </tr>
  </thead>
  <tbody>
    <?php
      foreach($reports as $r)
	  {
		  ?>
    <tr>
      <td><a href="/5073/index.php/users/info/<?php echo $r->reported; ?>" target="_blank"><?php echo ($this->my_usersmanager->getNickname($r->reported) == "" ? 'TJ-'.$r->reported : $this->my_usersmanager->getNickname($r->reported)); ?></a></td>
      <td><a href="/5073/index.php/users/info/<?php echo $r->reporter; ?>" target="_blank"><?php echo ($this->my_usersmanager->getNickname($r->reporter) == "" ? 'TJ-'.$r->reporter : $this->my_usersmanager->getNickname($r->reporter)); ?></a></td>
      <td><textarea cols="50" rows="5" readonly="readonly"><?php echo $r->reason; ?></textarea></td>
      <td><?php echo $r->time_reported; ?></td>
      <td><?php echo $r->status; ?></td>
      <td><a href="<?php echo site_url('reported/delete/'.$r->id); ?>">DELETE</a></td>
    </tr>
    <?php
	  }
	  ?>
  </tbody>
</table>
<?php
}
else
{
	$this->load->view('login_default');
}
?>
<?php $this->load->view('default_footer'); ?>
