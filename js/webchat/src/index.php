<?php

	$flashVars = '"room=mainlobby"';
	$image = '"mainlobby.png"';
	
	if ( isset( $_GET['content'] ) ) {
	
		$content = $_GET['content'];
		
		switch ( $content ) {
		
			case "box2dgame" :
				$roomName = "Box2d Game";
				break;
			case "platformgame" :
				$roomName = "Platform Game";
				break;
			case "mainlobby" :
			case "" :
			case null :
			default :
				$roomName = "Main Lobby";
				break;
		}
		
		$flashVars = '"room=' . $roomName . '"';
		$image = '"' . $content . '.png"';
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
	<head>
		<title>My Content Site</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<style type="text/css" media="screen">
		//html, body { height:100%; background-color: #ffffff;}
		//body { margin:0; padding:0; overflow:hidden; }
		#flashContent { width:100%; height:100%; align:middle; }
		</style>
	</head>
	<body>
		
		<div id="myContent">
			<image src=<?php echo $image; ?> alt="game content" align="middle"/>
		</div>
	
		<div id="flashContent">
			<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="100%" height="50%" id="chat" align="middle">
				<param name="movie" value="chat.swf" />
				<param name="quality" value="high" />
				<param name="bgcolor" value="#ffffff" />
				<param name="play" value="true" />
				<param name="loop" value="true" />
				<param name="wmode" value="window" />
				<param name="scale" value="showall" />
				<param name="menu" value="true" />
				<param name="devicefont" value="false" />
				<param name="salign" value="" />
				<param name="allowScriptAccess" value="sameDomain" />
				<param name="flashVars" value=<?php echo $flashVars; ?> />
				<!--[if !IE]>-->
				<object type="application/x-shockwave-flash" data="chat.swf" width="100%" height="50%">
					<param name="movie" value="chat.swf" />
					<param name="quality" value="high" />
					<param name="bgcolor" value="#ffffff" />
					<param name="play" value="true" />
					<param name="loop" value="true" />
					<param name="wmode" value="window" />
					<param name="scale" value="showall" />
					<param name="menu" value="true" />
					<param name="devicefont" value="false" />
					<param name="salign" value="" />
					<param name="allowScriptAccess" value="sameDomain" />
					<param name="flashVars" value=<?php echo $flashVars; ?> />
				<!--<![endif]-->
					<a href="http://www.adobe.com/go/getflash">
						<img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" />
					</a>
				<!--[if !IE]>-->
				</object>
				<!--<![endif]-->
			</object>
		</div>
	</body>
</html>
