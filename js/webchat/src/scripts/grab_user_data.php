<?php

	

	require_once "classes/MySQLConnection.php";

	

	if ( isset( $_POST['username'] ) ) {

		

		$connection = new MySQLConnection();

		$connection->connect();

		$username = $_POST['username'];

		$sql = "SELECT * FROM mymembers WHERE my_username = '$username' LIMIT 1";

		$query = mysql_query( $sql );

		

		while ( $row = mysql_fetch_array( $query ) ) {

			

			$uid = $row['uid'];

			$xml = '<user id="' . $uid . '">' . "\n";

			$xml .= "	<firstName>" . $row['first_name'] . "</firstName>\n";

			$xml .= "	<lastName>" . $row['last_name'] . "</lastName>\n";

			$xml .= "	<country>" . $row['country'] . "</country>\n";

			$xml .= "	<statusMessage>" . $row['status_message'] . "</statusMessage>\n";

			$xml .= "</user>\n";

		}

		

		echo $xml;

		$connection->close();

		exit();

	}

	

?>

		