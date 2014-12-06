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
          <div class="pink_24">xxxxxx.com Instant connect, no registration required!</div>
          <div style="float:left; width:100%; height:30px;">&nbsp;</div>
          <div class="black_18">There is no need to register with all your details to use our app, all you need to do is select your sex and the sex you looking for “Male, Female or Ladyboys” and that’s it, your instantly live and available to chat to people nearest to you!</div>
        </div>
        <div id="slide_img_1" style="float:right; width:434px; text-align:right;"><img src="/images/slides/home_1.png" height="400" /></div>
      </div>
      <!-- end slide -->
      <div id="slide_2">
        <div id="slide_txt_2" style="float:left; width:383px; display:none;">
          <div style="float:left; width:100%; height:100px;">&nbsp;</div>
          <div class="pink_24">xxxxxx.com Supporting multiple devices!</div>
          <div style="float:left; width:100%; height:30px;">&nbsp;</div>
          <div class="black_18">With support for the iphone and android already here and the ipad version is in tow and coming soon it makes perfect sense to use xxxxxx to connect to the Thai social world</div>
        </div>
        <div id="slide_img_2" style="float:right; width:617px; text-align:right; display:none;"><img src="/images/slides/home_2.png" height="400" /></div>
      </div>
      <!-- end slide -->
      <div id="slide_3">
        <div id="slide_txt_3" style="float:left; width:560px; display:none;">
          <div style="float:left; width:100%; height:100px;">&nbsp;</div>
          <div class="pink_24">xxxxxx.com Select the views you want!</div>
          <div style="float:left; width:100%; height:30px;">&nbsp;</div>
          <div class="black_18">We use a quick simplified view change option that you control on what you want to see and It’s super easy to change to either profiles that are close to you or even profiles that you have favorited!</div>
        </div>
        <div id="slide_img_3" style="float:right; width:434px; text-align:right; display:none;"><img src="/images/slides/home_3.png" height="400" /></div>
      </div>
      <!-- end slide -->
      <div id="slide_4">
        <div id="slide_txt_4" style="float:left; width:355px; display:none;">
          <div style="float:left; width:100%; height:100px;">&nbsp;</div>
          <div class="pink_24">xxxxxx.com Your connection to Thailand!</div>
          <div style="float:left; width:100%; height:30px;">&nbsp;</div>
          <div class="black_18">With all the cross platform devices you can seamlessly find new people to become friends, go on a date, hang for a coffee or even just chat online with... !
            Download today and see who’s using xxxxxx!</div>
        </div>
        <div id="slide_img_4" style="float:right; width:645px; text-align:right; display:none;"><img src="/images/slides/home_4.png" height="400" /></div>
      </div>
    </div>
  </div>
</div>
<div style="float:left; width:100%; height:100px; text-align:center;"><a href="http://www.xxxxxx.com/at/stickman" target="_blank"><img src="http://www.xxxxxx.com/at/show/stickman" width="728" height="90" alt="xxxxxx Meet Thai Women" title="xxxxxx Meet Thai Women" /></a></div>
<?php $this->load->view('default_footer'); ?>