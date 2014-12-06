<?php

	

	require_once "MySQLConnection.php";

	

	class LoginManager {

		

		public function __construct() {

			

		}

		

		public function login( $username, $password ) {

			

			$username = strip_tags( $username );

		

			$username = stripslashes( $username );

		

			$username = mysql_real_escape_string( $username );

		

			$passHash = md5( $password ); // Applies MD5 encoded hash to the password

			

			$connection = new MySQLConnection();

			$connection->connect();

			

			$sql = "SELECT * FROM mymembers WHERE my_username = '$username' AND my_password = '$passHash' LIMIT 1";

			$query = mysql_query( $sql );

		

			if ($query) {

			

				$count = mysql_num_rows( $query );

			}

			else {

				

				die ( mysql_error() );

			}

			

			if ( $count > 0 ) {

			

				while ( $row = mysql_fetch_array( $query ) ) {

				

					$_SESSION['username'] = $username;

					$_SESSION['pw'] = $password;

					$uid = $row['uid'];

					session_name( $username . $uid );

					setcookie( session_name(), '', time() + 42000, '/' );

					$connection->close();

					die ( "login=1" );

				}

			

				die ( "login=0&error=Invalid username or password" );



			}

			else {

			

				$connection->close();

				die ( "login=0&error=Invalid username or password" );

			}

		}

		

		public function checkLogin() {

			

			if ( isset ( $_SESSION['username'] ) && isset ( $_SESSION['pw'] ) ) {

				

				$user = $_SESSION['username'];

				$pw = $_SESSION['pw'];

				die ( "login=1&username=$user&password=$pw" );

			}

			else {

			

				die ( "login=0" );

			}

		}

		

		public function logout() {

			

			setcookie(session_name(), '', time() - 42000, '/');

			if ( isset( $_SESSION['username'] ) ) unset( $_SESSION['username'] );

			if ( isset( $_SESSION['pw'] ) ) unset( $_SESSION['pw'] );

			

			//Destroy session

			session_destroy();

			

			//return result to Flash (swf)

			die ("logout=1");

		}

	}

	

?>