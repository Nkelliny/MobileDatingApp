<?php $this->load->view('default_header'); ?>
<?php
if($this->session->userdata('adauth') == "yes")
{
	?>
    <div style="float:left; width:100%;">
    	<h3>Recently Approved Photos (last 100)</h3>
    </div>
    <div style="float:left; width:100%;">
    <?php
    /*
	[id] => 31626
	[nickname] => TJ31626
	[gender] => 18
	[age] => 18
	[joindate] => 2014-05-15 23:16:51
	[picpath] => /images/p_pics/1400218523.jpg
	[appby] => 5852
	[appbytime] => 1400218791
	*/
	$x=1;
	foreach($photos as $p)
	{
		?>
        <div style="float:left; width:160px; height:280px; border:#000 1px solid; text-align:center;">
        <a href="https://www.xxxxxx.com<?php echo $p->picpath; ?>" target="_blank"><img src="/image.php?src=<?php echo $p->picpath; ?>&w=150&h=150&zc=1&cb=<?php echo (time() + $x); ?>" width="150" height="150" alt="" title="" border="0" style="border:#000000 1px solid;" /></a><br />
        Approved By:<br />
<?php echo ($p->appby != "" ? $this->my_usersmanager->getAdminInfo($p->appby) : ''); ?><br />
        Approved Date: <br />
<?php echo date('Y-m-d h:i:s',$p->appbytime); ?><br />
<a href="/5073/index.php/users/info/<?php echo $p->id; ?>">Edit User</a>
        </div>
        <?php
		$x++;
	}
	?>
    </div>
<?php
}
else
{
	$this->load->view('login_default');
}
?>
<?php $this->load->view('default_footer'); ?>