<?php
	class sql {
		function connect() {			
			/*$sqlConnect = mysql_connect (servername,dbusername,dbpassword);
			
			if(!$sqlConnect){
				die("Could not connect to MySQL SERVER");
			}
			mysql_select_db(dbname,$sqlConnect) or die ("could not open db".mysql_error()); */
		}
	}
	
	class config {
		function connect() {
			$db = new PDO('mysql:host='.servername.';dbname='.dbname.';charset=utf8', dbusername, dbpassword);
			$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			return $db;
		}
	}
?>