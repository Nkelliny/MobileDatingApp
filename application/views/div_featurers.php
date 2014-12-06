<?php $this->load->view('div_header'); ?>
<script type="text/javascript">
var slideCnt = 6;
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
	if(cnt == 6)
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
    <div id="slide_holder" style="float:left; width:100%; height:400px;">
      <div id="slide_1">
        <div id="slide_txt_1" style="float:left; width:440px;">
          <div style="float:left; width:100%; height:100px;">&nbsp;</div>
          <div class="pink_24">First Time On xxxxxx?</div>
          <div style="float:left; width:100%; height:30px;">&nbsp;</div>
          <div class="black_18">Use our tip balloons to help you start connecting and meeting people near you.</div>
          <div style="float:left; width:100%; height:30px;">&nbsp;</div>
          <div class="pink_24">Don't just communicate, connect with xxxxxx </div>
        </div>
        <div id="slide_img_1" style="float:right; width:557px;"><img src="/images/slides/feature_1.png" width="557" height="400" /></div>
      </div>
      <!-- end slide -->
      <div id="slide_2">
        <div id="slide_txt_2" style="float:left; width:440px; display:none;">
          <div style="float:left; width:100%; height:100px;">&nbsp;</div>
          <div class="pink_24">View selection</div>
          <div style="float:left; width:100%; height:30px;">&nbsp;</div>
          <div class="black_18">Easily and quickly choose how you want to view your selected profiles. You can change your view options at any time allowing you to control how you want to use xxxxxx.</div>
          <div style="float:left; width:100%; height:30px;">&nbsp;</div>
          <div class="pink_24">Everyone ,Nearby,Favourites,Recent</div>
        </div>
        <div id="slide_img_2" style="float:right; width:557px; display:none;"><img src="/images/slides/feature_2.png" width="557" height="400" /></div>
      </div>
      <!-- end slide -->
      <div id="slide_3">
        <div id="slide_txt_3" style="float:left; width:440px; display:none;">
          <div style="float:left; width:100%; height:100px;">&nbsp;</div>
          <div class="pink_24">Chat, send your location and photos</div>
          <div style="float:left; width:100%; height:30px;">&nbsp;</div>
          <div class="black_18">You can send more than just words on xxxxxx, add photos or your location to your conversation to enhance your experience.</div>
          <div style="float:left; width:100%; height:30px;">&nbsp;</div>
          <div class="pink_24">Found someone you want to chat with again then set them as a favourite.</div>
        </div>
        <div id="slide_img_3" style="float:right; width:557px; display:none;"><img src="/images/slides/feature_3.png" width="557" height="400" /></div>
      </div>
      <!-- end slide -->
      <div id="slide_4">
        <div id="slide_txt_4" style="float:left; width:440px; display:none;">
          <div style="float:left; width:100%; height:100px;">&nbsp;</div>
          <div class="pink_24">Edit profile</div>
          <div style="float:left; width:100%; height:30px;">&nbsp;</div>
          <div class="black_18">Personalise and bring your profile to life by putting your photograph on your profile using a clear frontal face shot for best results. Say something about yourself to show on your profile.</div>
          <div style="float:left; width:100%; height:30px;">&nbsp;</div>
          <div class="pink_24">Set and change your preferences easily to find who you are looking for.</div>
        </div>
        <div id="slide_img_4" style="float:right; width:557px; display:none;"><img src="/images/slides/feature_4.png" width="557" height="400" /></div>
      </div>
      <!-- end slide -->
      <div id="slide_5">
        <div id="slide_txt_5" style="float:left; width:440px; display:none;">
          <div style="float:left; width:100%; height:100px;">&nbsp;</div>
          <div class="pink_24">With xxxxxx you Don't just communicate, you Connect</div>
          <div style="float:left; width:100%; height:30px;">&nbsp;</div>
          <div class="black_18">Show your distance and set the age filter so you can connect with people near you.</div>
        </div>
        <div id="slide_img_5" style="float:right; width:557px; display:none;"><img src="/images/slides/feature_5.png" width="557" height="400" /></div>
      </div>
      <!-- end slide -->
      <div id="slide_6">
        <div id="slide_txt_6" style="float:left; width:440px; display:none;">
          <div style="float:left; width:100%; height:100px;">&nbsp;</div>
          <div class="pink_24">High resolution Photos</div>
          <div style="float:left; width:100%; height:30px;">&nbsp;</div>
          <div class="black_18">Quickly and easily clear all your chats , unblock everyone,download a high resolution photos and thumbnails</div>
        </div>
        <div id="slide_img_6" style="float:right; width:557px; display:none;"><img src="/images/slides/feature_6.png" width="557" height="400" /></div>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('div_footer'); ?>
