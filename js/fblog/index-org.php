<?php
include_once "fbaccess.php";
function tjconnect()
{
	$connection = mysql_connect('localhost', 'xxxxxx_4', 'nn@*ntnp1');
	if ($db = mysql_select_db('xxxxxx_4',$connection)){
		return $connection;
	}
	return false;
}

function getLocation($city)
{
	$link = tjconnect();
	$sql = mysql_query('SELECT * FROM `skadate_location_city` WHERE `Feature_str_name` = "'.$city.'"');
	if(mysql_num_rows($sql) > 0)
	{
		$tmp['cty'] = mysql_result($sql,0,'Feature_int_id');
		$tmp['sta'] = mysql_result($sql,0,'Admin1_str_code');
		$tmp['con'] = mysql_result($sql,0,'Country_str_code');
	}
	else
	{
		$tmp['cty'] = '';
		$tmp['sta'] = '';
		$tmp['con'] = '';
	}
	return $tmp;
}

function cleanForUrl($str)
{
	$new_string = ereg_replace("[^A-Za-z0-9]", "-", $str);
	$new_string = strtolower($new_string);
	$str_length = strlen($new_string);
	$new_string = preg_replace('{(-)\1+}','$1',$new_string);
	$ck_string_length = strlen($new_string);
	if($ck_string_length > 100)
	{
		$new_string = substr($new_string,0,100);
	}
	$new_string = trim($new_string,'-');
	return $new_string;
}

function time_elapsed($time) {
	sscanf($time,"%u-%u-%uT%u:%u:%u+0000",$year,$month,$day,$hour,$min,$sec);
    $time_seconds = time() - ((int)substr(date('O'),0,3)*60*60) - mktime($hour,$min,$sec,$month,$day,$year);
    
    if($time_seconds < 1) return '0 seconds';
    
    $arr = array(12*30*24*60*60	=> 'year',
                30*24*60*60		=> 'month',
                24*60*60		=> 'day',
                60*60			=> 'hour',
                60				=> 'minute',
                1				=> 'second'
                );
    
    foreach($arr as $secs => $str){
        $d = $time_seconds / $secs;
        if($d >= 1){
            $r = floor($d);
            return $r . ' ' . $str . ($r > 1 ? 's' : '');
        }
    }
}
if(!$user) 
{ 
	header('Location: '.$loginUrl);
	exit;
} 
else 
{ 
	$email = $user_info['email']; // => xxxx@thaihangover.com
	// check for existing user
	if(!$email)
	{
		echo 'no email!';
		print_r($user_info);
		print_r($user);
		exit;
	}
	else
	{
		echo $email;
		exit;
	}
	$dbl = tjconnect();
	$sql = mysql_query('SELECT `id` FROM `users` WHERE `email` = "'.$email.'"');
	if(mysql_num_rows($sql) > 0)
	{
		header('Location: http://www.xxxxxx.com/fblog/auth/'.mysql_result($sql,0,'id'));
		exit;
	}
	else
	{
		$fbid = $user_info['id']; // => 100003626906184
		$fblink = $user_info['link']; // => http://www.facebook.com/xxxx.samui
		$nickname = cleanForUrl($user_info['username']); // => xxxx.samui
		$dob = explode('/',$user_info['birthday']); // => 08/13/1973
		// 1974-05-15
		$ndob = $dob[2].'-'.$dob[0].'-'.$dob[1];
		$loca = $user_info['hometown']['name']; // => Ko Samui
		$locb = $user_info['location']['name']; // => Ko Samui
		if($locb && $locb != "")
		{
			$location = getLocation($locb);
			$city    = $location['cty'];
			$state   = $location['sta'];
			$country = $location['con'];
		}
		else if($loca && $loca != "")
		{
			$location = getLocation($loca);
			$city    = $location['cty'];
			$state   = $location['sta'];
			$country = $location['con'];
		}
		else
		{
			$city    = '';
			$state   = '';
			$country = '';
		}
		$bio = $user_info['bio']; // =>
		$fbgen = $user_info['gender']; // => male
		if($fbgen == "male")
		{
			$gender = 2;
		}
		else if($fbgen == "female")
		{
			$gender = 1;
		}
		else
		{
			$gender = 8;
		}
		$profile_pic = 'https://graph.facebook.com/'.$user.'/picture?type=large';
		// join the user and log them in
		$query = $sql = mysql_query('INSERT INTO `users` 
		(`id`, `url`, `email`, `nickname`, `headline`, `pass`, `gender`, `seeking`, `lookingfor`, `country_now`, `city_now`, `dob`, `joinip`, `status`, `verifycode`,`verified`, `views`, `isaff`, `type`) 
		VALUES 
		(NULL, 
		"'.$nickname.'", 
		"'.$email.'", 
		"'.$user_info['username'].'", 
		"", 
		"", 
		"'.$gender.'", 
		"0", 
		"0", 
		"'.$country.'", 
		"'.$city.'", 
		"'.$ndob.'", 
		"'.$_SERVER['REMOTE_ADDR'].'", 
		"1",
		"",
		"1", 
		"0", 
		"0", 
		"normal")');
		$uid = mysql_insert_id();
		// add to profile data
		$sql = mysql_query('INSERT INTO `profile_data` 
		(`id`,`uid`,`match_gender`,`bio`,`pstatus`) 
		VALUES 
		(NULL,"'.$uid.'","0","'.mysql_real_escape_string($bio).'","2")');
		$query = mysql_query('INSERT INTO `images_albums` (`id`,`uid`,`name`,`url`,`status`) VALUES (NULL,"'.$uid.'","Profile Pics","profile-pics","1")');
		$album_id = mysql_insert_id();
		$query = mysql_query('INSERT INTO `images` (`id`,`uid`,`path`,`ismain`,`views`,`status`,`album_id`,`public`) 
		VALUES 
		(NULL,"'.$uid.'","'.$profile_pic.'","1","0","1","'.$album_id.'","1")');
		// user present log them in by setting cookie.
		header('Location: http://www.xxxxxx.com/fblog/auth/'.$uid);
		exit;
	}
}
?>