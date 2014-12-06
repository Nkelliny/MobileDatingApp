<?php $this->load->view('default_header'); ?>
<div id="slide_content">
  <div class="bcontent">
    <div style="float:left; width:100%; height:10px;">&nbsp;</div>
    <div style="float:left; width:100%; font-size:18px;">Thai Dating Tips</div>
    <div style="float:left; width:100%; height:10px;">&nbsp;</div>
    <div style="float:left; width:100%; height:350px; overflow-y:auto; overflow-x:hidden;">
    <div style="float:left; width:100%;">
    <ul>
	<?php
	foreach($pgs as $p)
	{
		?>
        <li style="height:30px;"><a href="/dating/tip/<?php echo $p->url; ?>" style="text-decoration:none;"><span style="color:#000; font-size:14px;"><?php echo $p->title; ?></span></li>
        <?php
	}
	?>
    </ul>
    </div>
    </div>
  </div>
</div>
<?php $this->load->view('default_footer'); ?>