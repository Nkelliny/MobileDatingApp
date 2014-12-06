<?php
function chat_db()
{
	$connection = mysql_connect('localhost', 'hangover_main','pu$$y1369');
	if ($db = mysql_select_db('hangover_love',$connection))
	{
		return $connection;
	}
	else
	{
		return false;
	}
}

function getUserPic($id)
{
	$link = chat_db();
	$sql = mysql_query('SELECT `path` FROM `images` WHERE `uid` = "'.$id.'" AND `ismain` = "1" AND `status` = "1"');
	if(mysql_num_rows($sql) > 0)
	{
		$img = mysql_result($sql,0,'path');
		return $img;
	}
	else
	{
		$img = "na";
		return $img;
	}
}

function getUserInfo($id)
{
	$link = chat_db();
	$sql = mysql_query('SELECT `id`,`nickname`,`url` FROM `users` WHERE `id` = "'.$id.'"');
	$tmp['id'] = mysql_result($sql,0,'id');
	$tmp['name'] = mysql_result($sql,0,'nickname');
	$tmp['url'] = mysql_result($sql,0,'url');
	$tmp['img'] = getUserPic(mysql_result($sql,0,'id'));
	return $tmp;
}
?>