<?php

	

	require_once "classes/LoginManager.php";

	

	if (isset($_POST['username']) && $_POST['password']) {

		

		login();

	}

	

	function login() {

	

		$username = $_POST['username'];

		$password = $_POST['password'];

		unset($_POST['username']);

		unset($_POST['password']);

		

		$login = new LoginManager();

		$login->login( $username, $password) ;

	}

	

?>