<?php
/* 
  captcha.php
  jQuery Fancy Captcha
  www.webdesignbeach.com
  
  Created by Web Design Beach.
  Copyright 2009 Web Design Beach. All rights reserved.
*/
session_start(); /* starts session to save generated random number */

/* this compare captcha's number from POST and SESSION */
if($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['captcha']) && $_POST['captcha'] == $_SESSION['captcha'])
	{
		$message = "New Contact Message: \n\r";
		foreach($_POST as $key=>$value)
		{
			$message .= strtoupper($key).": " . $value . "\n\r";
		}
		//$query = $this->db->query('INSERT INTO `contact_form` (`id`,`message`,`status`) VALUES (NULL,"'.mysql_real_escape_string($message).'","1")');
		// mail message
		
		unset($_SESSION['captcha']); /* this line makes session free, we recommend you to keep it */
		header('Location: http://www.xxxxxx.com/contact/thankyou');
	} 
elseif($_SERVER['REQUEST_METHOD'] == "POST" && !isset($_POST['captcha']))
	{
		echo "Failed!";
	}
/* in case that form isn't submitted this file will create a random number and save it in session */
else
	{
		$rand = mt_rand(0,4);
		$_SESSION['captcha'] = $rand;
		echo $rand;
	}
?>