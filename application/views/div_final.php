<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8" />
<title>xxxxxx</title>
<style>
.bcontent {
	margin: 0 auto;
	width: 1000px;
}
body, td, th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
	color: #000;
}
body {
	background-color: #FFF;
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
#top_header {
	float: left;
	width: 100%;
	height: 90px;
	background-color: #e6007e;
}
#slide_content {
	float: left;
	width: 100%;
	height: 400px;
}
#store_links {
	float: left;
	width: 100%;
	background-color: #f7f7f7;
	height: 150px;
}
#content_section {
	float: left;
	width: 100%;
	background-color: #FFF;
	height: 250px;
}
#content_sectionb {
	float: left;
	width: 100%;
	height: 125px;
	background-color: #ec4ca4;
}
.content_text {float:left; width:800px; padding-left:100px; padding-top:25px; color:#FFF; font-size:14px;}
#footer {
	float: left;
	width: 100%;
	height: 186px;
	background-color: #5c5f70;
}
#logo {
	float: left;
	width: 220px;
	height: 52px;
	padding-top: 10px;
}
#top_links {
	float: right;
	width: 380px;
	padding-top: 30px;
}
.topLinksTxt,a{
	float: left;
	width: 70px;
	color: #FFF;
	font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	font-size: 16px;
	text-align: center;
	padding-top: 5px;
	text-decoration:none;
}
.topLinks {
	float: left;
	width: 50px;
	color: #FFF;
	font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
	font-size: 14px;
	text-align: center;
}
.storeLinks {
	float: left;
	width: 33%;
	text-align: center;
	padding-top: 30px;
}
.top_content {
	float: left;
	width: 33%;
	text-align: center;
	height: 220px;
	padding-top: 50px;
}
</style>
<script type="text/javascript">
function openGoogleWin()
{
	myWindow = window.open('https://plus.google.com/share?url=http://www.xxxxxx.com','googleWin','width=500,height=500');
	myWindow.focus();
}

function openFaceBookWin()
{
	myWindow = window.open('https://www.facebook.com/xxxxxx','facebookWin','width=1000,height=800');
	myWindow.focus();
}

</script>

<link href="/js/jsImgSlider/themes/1/js-image-slider.css?cb=<?php echo time(); ?>" rel="stylesheet" type="text/css" />
<script src="/js/jsImgSlider/themes/1/js-image-slider.js?cb=<?php echo time(); ?>" type="text/javascript"></script>
</head>
<body>
<div id="top_header">
  <div class="bcontent">
    <div id="header" style="float:left; width:960px; padding-left:10px;">
      <div id="logo"><img src="/images/design/dev/xxxxxx-logo.jpg" width="220" height="66" alt="xxxxxx" title="xxxxxx" /></div>
      <div id="top_links">
        <div class="topLinksTxt"><a href="/div/xxxx">Home</a></div>
        <div class="topLinksTxt"><a href="/div/featurers">Features</a></div>
        <div class="topLinksTxt"><a href="/div/faq">FAQ</a></div>
        <div class="topLinksTxt"><a href="/div/contact">Contact</a></div>
        <div class="topLinks"><a href="javascript:void(0);" onClick="openGoogleWin();">
    	<img src="/images/design/dev/google.png" width="25" height="25" alt="Google" title="Google" />
        </a>
        </div>
        <div class="topLinks"><a href="javascript:void(0);" onClick="openFaceBookWin();"><img src="/images/design/dev/facebook.png" width="25" height="25" alt="Facebook" title="Facebook" /></a></div>
      </div>
    </div>
  </div>
</div>
<div id="slide_content">
  <div class="bcontent">
    <div id="slide_holder" style="float:left; width:1000px; height:400px;">
      <div id="sliderFrame">
        <div id="slider"> <img src="/images/design/dev/slides/slide_1.jpg?cb=<?php echo time(); ?>" width="1000" height="400" /> <img src="/images/design/dev/slides/slide_2.jpg?cb=<?php echo time(); ?>" width="1000" height="400" /> <img src="/images/design/dev/slides/slide_3.jpg?cb=<?php echo time(); ?>" width="1000" height="400" /> <img src="/images/design/dev/slides/slide_4.jpg?cb=<?php echo time(); ?>" width="1000" height="400" /> 
          <!-- <img src="/images/design/dev/slides/slide_5.png?cb=<?php echo time(); ?>" width="1000" height="400" /> --> 
        </div>
      </div>
    </div>
  </div>
</div>
<div id="store_links">
  <div class="bcontent">
    <div class="storeLinks"><img src="/images/design/dev/appstore.png" width="254" height="81" alt="App Store" title="App Store" /></div>
    <div class="storeLinks"><img src="/images/design/dev/google-play-off.png" width="254" height="84" alt="Google play" title="Google play" /></div>
    <div class="storeLinks"><img src="/images/design/dev/desktop-profile.png" width="254" height="81" alt="Desktop Profile" title="Desktop Profile" /></div>
  </div>
</div>
<div id="content_sectionb">
  <div class="bcontent">
    <div class="content_text">xxxxxx.com © Copyright 2013 Fwends Limited<br />Apple, iPhone, iPod Touch, iPad and iTunes are trademarks of Apple Inc.,registered in the U.S. and other countries. Android is the property of Google Inc and is<br />registered and/or used in the U.S. and countries around the world. Used under license from Google Inc. </div>
  </div>
</div>
</body>
</html>