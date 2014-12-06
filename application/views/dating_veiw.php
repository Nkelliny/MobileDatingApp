<?php $this->load->view('default_header'); ?>
<div id="slide_content">
  <div class="bcontent">
    <div style="float:left; width:100%; height:10px;">&nbsp;</div>
    <div style="float:left; width:100%; font-size:18px;">Thai Dating Tips - <?php echo $pg->title; ?></div>
    <div style="float:left; width:100%;"><a href="/dating" style="text-decoration:none;"><span style="color:#F00;">Back</span></a>
    <div style="float:left; width:100%; height:10px;">&nbsp;</div>
    <div style="float:left; width:100%; height:350px; overflow-y:auto; overflow-x:hidden;">
    <div style="float:left; width:100%;">
    <?php echo $pg->body; ?>
    </div>
    </div>
  </div>
</div>
<?php $this->load->view('default_footer'); ?>