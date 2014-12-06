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
	foreach($images as $i)
	{
		$raw_path = ($i->thumb != "" ? $i->thumb : $i->path);
		$mystring = $raw_path;
		$findme   = 'facebook.com';
		$pos = strpos($mystring, $findme);
		if ($pos === false) 
		{
            $ppic = '<img src="/image.php?src='.$raw_path.'&w=150&h=150&zc=1&cb='.(time() + $x).'" width="150" height="150" alt="" title="" border="0" style="border:#000000 1px solid;" />';
			$full = 'https://www.xxxxxx.com'.$raw_path;
		}
		else
		{
			$ppic = '<img src="'.$raw_path.'" width="150" height="150" alt="" title="" border="0" style="border:#000000 1px solid;" />';
			$full = $raw_path;
		}
		?>
            <div id="img_<?php echo $i->id; ?>" style="float:left; width:160px; height:280px; border:#000 1px solid; text-align:center;">
            <a href="<?php echo $full; ?>" target="_blank"><?php echo $ppic; ?></a><br />
            <a href="/5073/index.php/users/info/<?php echo $i->uid; ?>" target="_blank">Edit Profile</a><br />
            <a href="/5073/js/crop/crop_image.php?id=<?php echo $i->id; ?>" onclick="javascript:void window.open('/5073/js/crop/crop_image.php?id=<?php echo $i->id; ?>','<?php echo time(); ?>','width=800,height=800,toolbar=1,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0');return false;">Crop Image</a><br />
            <a href="javascript:void(0);" onclick="imgRotate(<?php echo $i->id; ?>);">Rotate Image</a><br />
            <label><input name="site_<?php echo $i->id; ?>" id="site_<?php echo $i->id; ?>" type="checkbox" value="1" <?php echo ($i->home == "1" ? 'checked="checked"' : ''); ?> onchange="setHomeImage(<?php echo $i->id; ?>);" />ON SITE</label><br />
            <label><input name="mailer_<?php echo $i->id; ?>" id="mailer_<?php echo $i->id; ?>" type="checkbox" value="1" <?php echo ($i->mailer == "1" ? 'checked="checked"' : ''); ?> onchange="setMailerImage(<?php echo $i->id; ?>);" />ON MAILER</label><br />
            <a href="javascript:void(0);" onclick="deleteImage(<?php echo $i->id; ?>);">Delete</a><br />
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