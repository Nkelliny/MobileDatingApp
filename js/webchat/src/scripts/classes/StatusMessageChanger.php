<?php

	

	require_once "MySQLConnection.php";

	

	class StatusMessageChanger {

		

		private $uid;

		

		public function __construct( $userID ) {

		

			$this->uid = $userID;

		}

		

		public function change( $newMessage ) {

		

			$newMessage = strip_tags( $newMessage );

		

			$newMessage = stripslashes( $newMessage );

		

			$newMessage = mysql_real_escape_string( $newMessage );

		

			//$newMessage = eregi_replace( "`", "", $newMessage );

			

			$connection = new MySQLConnection();

			$connection->connect();

			

			$uid = $this->uid;

			$sql = "UPDATE mymembers SET status_message = '$newMessage' WHERE uid = $uid";

			$query = mysql_query( $sql );

			$connection->close();

			

			if ($query) {

				

				echo ( "result=1" );

			}

			else {

				

				die ( "result=0" );

			}

		}

	}

	

?>