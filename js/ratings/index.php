<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title></title>
<style type="text/css">
  #Heart1,#Heart2,#Heart3,#Heart4,#Heart5 { margin-top:10px; display:none; float:left; width: 43px; height: 40px; left:-130px; position: relative;opacity:.1; }
  
  #wrap { 
	 width:400px; 
	 height:43px;
	 float:left;
	 padding:5px 4px 0px 4px;
	}
	
	#saveRating{
		width:60px;
		float:left;
		cursor:pointer;
		margin:10px 4px 0px 4px;
		display:none;
	}
	#DoRating{
		width:60px;
		cursor:pointer;
		float:left;
	}
	
	.search-background {
		display: none;
		font-size: 13px;
		font-weight: bold;
		height:47px;
		position: absolute;
		padding-top:20px;
		padding-left:17px;
		-moz-border-radius: 6px; 
		-webkit-border-radius: 6px;
		-moz-box-shadow: 0 1px 3px rgba(0,0,0,0.6);
		-webkit-box-shadow: 0 1px 3px rgba(0,0,0,0.6);
		text-align: left;
		opacity:0.5;filter: alpha(opacity=50) ;
		text-decoration: none;
		 width:373px; 
	}
	
	.search-background {
		 background:#999999;
		color:#FFFFFF;
		text-shadow: #fff 0px 0px 20px;
	}
	
	#CurrentRating{
		float:left;
		border:solid #999 1px; padding:5px;
		-moz-border-radius: 6px; 
		-webkit-border-radius: 6px;
		margin-top:5px;
		margin-left:4px;
		margin-right:5px;
		font-weight:bolder;
		font-family:"Courier New", Courier, monospace;
		color:#ff0000;
	}
	#CurrentRating #note{
	 	font-size:12px;
	 }
	
	#CurrentRating span{
		font-weight:normal;
		color:#006699;
	}

	
</style>
<script type="text/javascript" src="jquery-1.2.6.min.js"></script>
<script type="text/javascript" src="jquery.livequery.js"></script>
<script type="text/javascript">
$( init );
var num =0;
function init() {
	$('#DoRating').click( function() {
		// hide curent rating
		$('#Panel').hide();
		num   = $("#count").val();
		var width = $("#width").val();
		// if you have given 5 hearts then show message
		if(num>5){alert('Maximum Rating ! Please submit now.'); return false;}
		$('#Heart'+num).animate( {
		  left: width+'px',
		  width: '43px',
		  opacity: 1,
		  height: '40px'
		} );
		$("#saveRating").fadeIn('slow');
		num = parseInt(num)+parseInt(1);
		$("#count").val(num);
		width = parseInt(width)-parseInt(75);
		$("#width").val(width);		 
	} );
}
$(document).ready(function() {
	
	$('#saveRating').livequery("click", function(e){
		
		vote = parseInt(num)-parseInt(1);
		
		showLoader();
		$.post("rating.php?value="+vote,{
		}, function(response){
			
			hideLoader();
			$('#saveRating').hide();
			$('#wrap').html(unescape(response));				
		});
		
	});
});
//show loading bar
function showLoader(){
	$('.search-background').fadeIn(200);
}
//hide loading bar
function hideLoader(){
	
	$('.search-background').fadeOut(200);
};
function Load()
{
	$("#count").val('1');
	$("#width").val('180');
}
</script>
</head>
<body onLoad="Load();">
<div >
	<br clear="all" />
	<div style="font-size:30px;">Ajax Heart Rating System with JQuery and PHP</div>
	<br clear="all" /><br clear="all" />
		<input type="hidden" id="count" value="1" />
		<input type="hidden" id="width" value="180" />
		<br clear="all" /><img src="mj.jpg" width="480" alt="" />
		<br clear="all" />
		<div class="search-background">
			<label><img src="loading.gif" alt="" /></label>
		</div>
		<div id="wrap">
			<img src="hearts.png" id="DoRating" title="Click To Show Your Love" />
			<span id="Panel"><?php  include_once('rating.php');?></span>
			<img src="heart.png" id="Heart1" />
			<img src="heart.png" id="Heart2" />
			<img src="heart.png" id="Heart3" />
			<img src="heart.png" id="Heart4" />
			<img src="heart.png" id="Heart5" />
		</div>
		<img src="a.png" id="saveRating" title="Click To Submit Your Rating" />
		<br clear="all" /><br clear="all" /><br clear="all" /><br clear="all" />
	<br clear="all" /><br clear="all" /><br clear="all" />
	<br clear="all" /><br clear="all" /><br clear="all" />
</div>
</body>
</html>