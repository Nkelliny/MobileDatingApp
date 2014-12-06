<?php
if($ad->type == "image")
{
	$code = 'http://www.xxxxxx.com'.$ad->path.'';
}
//$img="example.gif"; 
header ('content-type: image/gif'); 
readfile($code); 
?>
