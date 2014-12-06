<?php $this->load->view('default_header'); ?>
<script type="text/javascript">
var slideCnt = 4;
var cnt = 1;
var slideTimer = setInterval(function(){switchSlides()},10000); // 10 seconds

function fadeOut(element,cnt) 
{
	var op = 1;  // initial opacity
	var timer = setInterval(function () 
	{
		if (op <= 0.1)
		{
			clearInterval(timer);
			element.style.display = 'none';
			if(cnt == 6)
			{
				cnt = 1;
			}
			document.getElementById('slide_txt_'+cnt).style.display = "block";
			document.getElementById('slide_img_'+cnt).style.display = "block";
		}
		element.style.opacity = op;
		element.style.filter = 'alpha(opacity=' + op * 100 + ")";
		op -= op * 0.1;
	}, 50);
}

function switchSlides()
{
	// hide divs
	document.getElementById('slide_txt_'+cnt).style.display = "none";
	document.getElementById('slide_img_'+cnt).style.display = "none";
	//fadeOut(document.getElementById('slide_'+cnt),cnt);
	if(cnt == 4)
	{
		cnt = 1;
	}
	else
	{
		cnt++;
	}
	// show divs
	document.getElementById('slide_txt_'+cnt).style.display = "block";
	document.getElementById('slide_img_'+cnt).style.display = "block";
	//fadeIn(document.getElementById('slide_'+cnt));
}
</script>

<div id="slide_content">
  <div class="bcontent">
    <div id="slide_holder" style="float:left; width:1000px; height:400px;">
      <div id="slide_1">
        <div id="slide_txt_1" style="float:left; width:560px;">
          <div style="float:left; width:100%; height:100px;">&nbsp;</div>
          <div class="pink_24">Sync your profile!</div>
          <div style="float:left; width:100%; height:30px;">&nbsp;</div>
          <div class="black_18">The desktop user interface has been retired and we now only support mobile devices, you were sent your sync code via email, if you don’t have this contact us on the <a href="/contact">contact page</a>.</div>
          <div style="float:left; width:100%; height:30px;">&nbsp;</div>
          <div class="pink_24">Open the app and click the “menu” button on the top left.</div>
        </div>
        <div id="slide_img_1" style="float:right; width:434px; text-align:right;"><img src="/images/slides/sync_1.png" height="400" /></div>
      </div>
      <!-- end slide -->
      <div id="slide_2">
        <div id="slide_txt_2" style="float:left; width:560px; display:none;">
          <div style="float:left; width:100%; height:100px;">&nbsp;</div>
          <div class="pink_24">Click on the settings icon at the bottom of the app</div>
        </div>
        <div id="slide_img_2" style="float:right; width:434px; text-align:right; display:none;"><img src="/images/slides/sync_2.png" height="400" /></div>
      </div>
      <!-- end slide -->
      <div id="slide_3">
        <div id="slide_txt_3" style="float:left; width:560px; display:none;">
          <div style="float:left; width:100%; height:100px;">&nbsp;</div>
          <div class="pink_24">Click on the “Sync Profile” icon at the bottom of the app</div>
        </div>
        <div id="slide_img_3" style="float:right; width:434px; text-align:right; display:none;"><img src="/images/slides/sync_3.png" height="400" /></div>
      </div>
      <!-- end slide -->
      <div id="slide_4">
        <div id="slide_txt_4" style="float:left; width:560px; display:none;">
          <div style="float:left; width:100%; height:100px;">&nbsp;</div>
          <div class="pink_24">Enter your “Sync Code” which is case sensitive. This is now complete and your web profile is now synced to your device.</div>
        </div>
        <div id="slide_img_4" style="float:right; width:434px; text-align:right; display:none;"><img src="/images/slides/sync_4.png" height="400" /></div>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('default_footer'); ?>