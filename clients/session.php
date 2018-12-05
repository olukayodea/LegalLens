<?php
	$urlData = explode("?", $_SERVER['REQUEST_URI']);
		
	if ((isset($_SESSION['clients']['id'])) && ($_SESSION['clients']['status'] != "NEW") && ($_SESSION['clients']['type'] == "CLIENTS")) {
		$company = trim($_SESSION['clients']['company']);
		$full_name = trim($_SESSION['clients']['name']);
		$phone = trim($_SESSION['clients']['phone']);
		$create_time = trim($_SESSION['clients']['create_time']);
		$email = trim($_SESSION['clients']['email']);
		$ref = trim($_SESSION['clients']['id']);		
		$status = trim($_SESSION['clients']['status']);
		$loginTime = trim($_SESSION['clients']['loginTime']);
		$sessionTime = trim($_SESSION['clients']['sessionTime']);
		
		if ($sessionTime < time()) {
			//add to log
			$logArray['object'] = "clients";
			$logArray['object_id'] = $_SESSION['clients']['id'];
			$logArray['owner'] = "clients";
			$logArray['owner_id'] = $_SESSION['clients']['id'];
			$logArray['desc'] = "Session timed out";
			$logArray['create_time'] = time();
			$system_log = new system_log;
			$system_log->create($logArray);
			
			$logout_time = date("Y-m-d H:i:s");
			$session_id = session_id();
			$_SESSION = array();
			if(isset($_COOKIE[session_name()])) {
				setcookie(session_name(), '', time()-42000, '/');
			}
			session_destroy();
			header("location: login?redirect=".$redirect."&msg=you+must+login+to+continue"."&".$urlData[1]);
		} else {
			$_SESSION['clients']['sessionTime'] = time() + 1800;
		}
	} else {
		header("location: login?redirect=".$redirect."&msg=please+login"."&".$urlData[1]);
	}
?>