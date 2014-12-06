<?php $this->load->view('default_header'); ?>
<script type="text/javascript">
function cpimg(id)
{
	
}
</script>
<?php
if($this->session->userdata('adauth') == "yes")
{
	?>
    <div style="float:left; width:100%;">
    	<h3>Select Photos</h3>
    </div>
    <div class="table_pagination right"><?php echo $plinks; ?></div>
    <div style="float:left; width:100%;">
    <?php //print_r($images); 
	$x=1;
	$cur_mem = 0;
	foreach($images as $i)
	{
		if($cur_mem != $i->uid)
		{
			?>
            <div style="float:left; width:100%; text-align:center; height:20px;">*************************************************************************************</div>
            <?php
			$cur_mem = $i->uid;
		}
		$raw_path = ($i->thumb != "" ? $i->thumb : $i->path);
		$mystring = $raw_path;
		$findme   = 'facebook.com';
		$pos = strpos($mystring, $findme);
		if ($pos === false) 
		{
            $ppic = '<img src="/image.php?src='.$raw_path.'&w=150&h=150&zc=1&cb='.(time() + $x).'" width="150" height="150" alt="" title="" border="0" style="border:#000000 1px solid;" />';
		}
		else
		{
			$ppic = '<img src="'.$raw_path.'" width="150" height="150" alt="" title="" border="0" style="border:#000000 1px solid;" />';
		}
		?>
            <div id="img_<?php echo $i->id; ?>" style="float:left; width:160px; height:280px; border:#000 1px solid; text-align:center;">
            <?php echo $ppic; ?><br />
            <a href="/5073/index.php/users/info/<?php echo $i->uid; ?>" target="_blank">Edit Profile</a><br />
            <a href="/5073/js/crop/crop_image.php?id=<?php echo $i->id; ?>" onclick="javascript:void window.open('/5073/js/crop/crop_image.php?id=<?php echo $i->id; ?>','<?php echo time(); ?>','width=800,height=800,toolbar=1,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0');return false;">Crop Image</a><br />
            <a href="javascript:;" onclick="setMainPicFix(<?php echo $i->uid; ?>,<?php echo $i->id; ?>);">Set as Main</a><br />
            USER: <?php echo $i->uid; ?><br />
            ISMAIN: <?php echo $i->ismain; ?><br />
            STATUS: <?php echo $i->status; ?>
            </div>
            <?php
			$x++;
	}
	?>
    </div>
    <div class="table_pagination right"><?php echo $plinks; ?></div>
<?php
}
else
{
	$this->load->view('login_default');
}
?>
<?php $this->load->view('default_footer'); ?>