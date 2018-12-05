<?php
	class sql {
		function connect() {			
			$sqlConnect = mysql_connect (servername,dbusername,dbpassword);
			
			if(!$sqlConnect){
				die("Could not connect to MySQL SERVER");
			}
			mysql_select_db(dbname,$sqlConnect) or die ("could not open db".mysql_error());
		}
	}
?>