<?php 
$app_id = "126370004108212";
$app_secret = "f9e9365d694cdbeedb0b83c67d45b9a7";
$my_url = "http://www.xxxxxx.com/js/fbconfig.php";
session_start();
$code = @$_REQUEST["code"];

if(empty($code)) 
{
	$_SESSION['state'] = md5(uniqid(rand(), TRUE)); // CSRF protection
	$dialog_url = "https://www.facebook.com/dialog/oauth?client_id=".$app_id."&redirect_uri=".urlencode($my_url)."&state=".$_SESSION['state']."&scope=user_birthday,read_stream";
	echo("<script> top.location.href='" . $dialog_url . "'</script>");
}
?>