<?php $this->load->view('div_header'); ?>
<div id="slide_content">
<div class="bcontent">
  <div id="faq_display">
    <div style="float:left; width:100%;">
    <div class="faq_section">
      <div class="faq_header">IOS FAQ</div>
      <div class="faq_content" style="float:left; width:98%;">
        <ul>
		<?php
        $iso_answer = array();
        $android_answer = array();
        $general_answer = array();
        $x=0;
        foreach($faqs as $f)
        {
        	if($f->category == "IOS FAQ")
        	{
        		?>
        		<li><a class="answers" href="#ios_<?php echo $x; ?>" style="text-decoration:none;"><span style="color:#000; font-size:14px;"><?php echo $f->question; ?></span></a></li>
        		<?php
        		$iso_answer[$x] = $f->answer; 
        		$x++;
        	}
        }
        foreach($iso_answer as $key=>$value)
        {
        	?>
        	<div id="ios_<?php echo $key; ?>" style="display:none;"><?php echo $value; ?></div>
        	<?php
        }
        ?>
        </ul>
      </div>
    </div>
    <div class="faq_section">
      <div class="faq_header">Android FAQ</div>
      <div class="faq_content" style="float:left; width:98%;">
        <ul>
		<?php
        $x=0;
        foreach($faqs as $f)
        {
        	if($f->category == "Android FAQ")
        	{
        		?>
        		<li><a class="answers" href="#an_<?php echo $x; ?>" style="text-decoration:none;"><span style="color:#000; font-size:14px;"><?php echo $f->question; ?></span></a></li>
        		<?php
        		$android_answer[$x] = $f->answer;
        	}
        	$x++;
        }
        foreach($android_answer as $key=>$value)
        {
        	?>
        	<div id="an_<?php echo $key; ?>" style="display:none;"><?php echo $value; ?></div>
        	<?php
        }
        ?>
        </ul>
      </div>
    </div>
    <div class="faq_section">
      <div class="faq_header">General FAQ</div>
      <div class="faq_content" style="float:left; width:98%;">
        <ul>
		<?php
        $x=0;
        foreach($faqs as $f)
        {
        	if($f->category == "General FAQ")
        	{
        		?>
        		<li><a class="answers" href="#gen_<?php echo $x; ?>" style="text-decoration:none;"><span style="color:#000; font-size:14px;"><?php echo $f->question; ?></span></a></li>
        		<?php
        		$general_answer[$x] = $f->answer;
        	}
        	$x++;
        }
        foreach($general_answer as $key=>$value)
        {
        	?>
        	<div id="gen_<?php echo $key; ?>" style="display:none;"><?php echo $value; ?></div>
        	<?php
        }
        ?>
        </ul>
      </div>
    </div>
  </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$(".answers").fancybox({
		maxWidth	: 400,
		maxHeight	: 400,
		fitToView	: false,
		width		: '400',
		height		: '400',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'elastic',
		closeEffect	: 'elastic'
	});
});
</script>
<?php $this->load->view('div_footer'); ?>
