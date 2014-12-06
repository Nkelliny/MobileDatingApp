<?php

	

	require_once "MySQLConnection.php";

	

	class UserDataGrabber {

		

		private $uid;

		private $xml;

		

		public function __construct( $userID ) {

		

			$this->uid = $userID;

		}

		

		public function grab() {

		

			$connection = new MySQLConnection();

			$connection->connect();

			

			$uid = $this->uid;

			$sql = "SELECT * FROM mymembers WHERE uid = $uid LIMIT 1";

			$query = mysql_query( $sql );

			

			if ( $query ) {

				

				while ( $row = mysql_fetch_array( $query ) ) {

				

					$xml = "<user id='$uid'>\n";

					$xml .= "	<firstName>" . $row['first_name'] . "</firstName>\n";

					$xml .= "	<lastName>" . $row['last_name'] . "</lastName>\n";

					$xml .= "	<email>" . $row['email'] . "</email>\n";

					$xml .= "	<country>" . $row['country'] . "</country>\n";

					$xml .= "	<statusMessage>" . $row['status_message'] . "</statusMessage>\n";

					$xml .= "</user>";

					$this->xml = $xml;

				}

			}

			else {

				

				die ( "<error>Failed to grab user data.</error>" );

			}

		}

		

		public function printData() {

			

			print $this->xml;

		}

	}

	

?>