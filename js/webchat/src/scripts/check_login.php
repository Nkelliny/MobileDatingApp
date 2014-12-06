<?php

	

	require_once "classes/LoginManager.php";

	

	session_start();

	

	$login = new LoginManager();

	$login->checkLogin();

	exit();

	

?>