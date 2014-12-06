<?php
function db_connect()
{
	$connection = mysql_connect('localhost', 'xxxxxx_4', 'nn@*ntnp1');
	if ($db = mysql_select_db('xxxxxx_4',$connection)){
		return $connection;
	}
	return false;
}

function checkPath($cur_path)
{
	$link = db_connect();
	$sql = mysql_query('SELECT `new_path` FROM `redirects` WHERE `old_path` = "'.mysql_real_escape_string($cur_path).'"');
	if(mysql_num_rows($sql) > 0)
	{
		header("HTTP/1.1 301 Moved Permanently");
		header("Location: ". strtolower(mysql_result($sql,0,'new_path'))); 
		exit; 
	}
	else
	{
		// auto add redirect
		$pts = explode('/',$cur_path);
		//print_r($pts);
		// /member/profile_kirimanjaro.html
		if($pts[1] == "member")
		{
			if($pts[2] != "photos")
			{
				$nstr = str_replace('profile_','',$pts[2]);
				$nstr = str_replace('.html','',$nstr);
				$nstr = str_replace('?language_id=2','',$nstr);
				$nstr = str_replace('?language_id=1','',$nstr);
				$nstr = str_replace('_','-',$nstr);
				$sql = mysql_query('INSERT INTO `redirects` (`id`,`old_path`,`new_path`) VALUES (NULL,"'.$cur_path.'","/profile/'.strtolower($nstr).'")');
				//mail('xxxxxx@gmail.com','redirect added xxxxxx','old-'.$cur_path.' new - /profile'.$nstr);
				header("HTTP/1.1 301 Moved Permanently");
				header('Location: /profile/'.$nstr);
				exit;
			}
			else if($pts[2] == "photos")
			{
				// /member/photos/Cheekyjanjang/11599?language_id=2
				$pid = $pts[4];
				$pid = str_replace('?language_id=2','',$pid);
				$pid = str_replace('?language_id=1','',$pid);
				$sql = mysql_query('SELECT `profile_id` FROM `skadate_profile_photo` WHERE `photo_id` = "'.$pid.'"');
				if(mysql_num_rows($sql) > 0)
				{
					$profile_id = mysql_result($sql,0,'profile_id');
					$sql = mysql_query('SELECT `id` FROM `users` WHERE `profile_id` = "'.$profile_id.'"');
					if(mysql_num_rows($sql) > 0)
					{
						$uid = mysql_result($sql,0,'id');
						// get main photo
						$sql = mysql_query('SELECT `id` FROM `images` WHERE `ismain` = "1" AND `uid` = "'.$uid.'"');
						if(mysql_num_rows($sql) > 0)
						{
							$photo_id = mysql_result($sql,0,'id');
							$new_link = '/photos/show/'.$photo_id;
							$sql = mysql_query('INSERT INTO `redirects` (`id`,`old_path`,`new_path`) VALUES (NULL,"'.$cur_path.'","'.$new_link.'")');
							header("HTTP/1.1 301 Moved Permanently");
							header('Location: '.$new_link);
							exit;
						}
						else
						{
							//mail('xxxxxx@gmail.com','xxxxxx 404 no photo id',$cur_path);
						}
					}
					else
					{
						//mail('xxxxxx@gmail.com','xxxxxx 404 no uid',$cur_path);
					}
				}
			}
			else
			{
				//mail('xxxxxx@gmail.com','xxxxxx 404',$cur_path);
			}
		}
		else
		{
			//mail('xxxxxx@gmail.com','xxxxxx 404',$cur_path);
		}
	}
}

$cur_path = $_SERVER['REQUEST_URI'];
checkPath($cur_path);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>404 Page Not Found</title>
<style type="text/css">

::selection{ background-color: #E13300; color: white; }
::moz-selection{ background-color: #E13300; color: white; }
::webkit-selection{ background-color: #E13300; color: white; }

body {
	background-color: #fff;
	margin: 40px;
	font: 13px/20px normal Helvetica, Arial, sans-serif;
	color: #4F5155;
}

a {
	color: #003399;
	background-color: transparent;
	font-weight: normal;
}

h1 {
	color: #444;
	background-color: transparent;
	border-bottom: 1px solid #D0D0D0;
	font-size: 19px;
	font-weight: normal;
	margin: 0 0 14px 0;
	padding: 14px 15px 10px 15px;
}

code {
	font-family: Consolas, Monaco, Courier New, Courier, monospace;
	font-size: 12px;
	background-color: #f9f9f9;
	border: 1px solid #D0D0D0;
	color: #002166;
	display: block;
	margin: 14px 0 14px 0;
	padding: 12px 10px 12px 10px;
}

#container {
	margin: 10px;
	border: 1px solid #D0D0D0;
	-webkit-box-shadow: 0 0 8px #D0D0D0;
}

p {
	margin: 12px 15px 12px 15px;
}
</style>
</head>
<body>
	<div id="container">
		<h1><?php echo $heading; ?></h1>
		<?php echo $message; ?>
	</div>
</body>
</html>