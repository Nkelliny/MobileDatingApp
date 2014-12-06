<?php

	

	class MySQLConnection {

	

		private $db_host = "localhost"; // Your Websites domain

		private $db_user = "root"; // Your databases username

		private $db_pass = ""; // Your databases password

		private $db_name = "mycontentsite"; // The name of your database

		private $connected = 0;

		

		public function connect() {

		

			mysql_connect($this->db_host, $this->db_user, $this->db_pass) or die ( "Error: Script aborted. Could not connect to database." );

			mysql_select_db($this->db_name) or die ( "Error: Script aborted. No database selected." );

			$this->connected = 1;

			session_start();

		}

		

		public function close() {

		

			mysql_close();

			$this->connected = 0;

		}

		

		public function get_connected() {

			

			return $this->connected;

		}

	}

	

?>