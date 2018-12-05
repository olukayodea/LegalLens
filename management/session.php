<?php
	$urlData = explode("?", $_SERVER['REQUEST_URI']);
		
	if ((isset($_SESSION['admin']['id'])) && ($_SESSION['admin']['status'] != "NEW") && ($_SESSION['admin']['type'] == "ADMIN")) {
		$username = trim($_SESSION['admin']['username']);
		$full_name = trim($_SESSION['admin']['name']);
		$phone = trim($_SESSION['admin']['phone']);
		$date_time = trim($_SESSION['admin']['date_time']);
		$email = trim($_SESSION['admin']['email']);
		$ref = trim($_SESSION['admin']['id']);
		$adminType = trim($_SESSION['admin']['adminType']);
		$read = trim($_SESSION['admin']['read']);
		$write = trim($_SESSION['admin']['write']);
		$modify = trim($_SESSION['admin']['modify']);
		$level = trim($_SESSION['admin']['level']);				
		$status = trim($_SESSION['admin']['status']);
		$allowedPages = explode(",", trim($_SESSION['admin']['pages']));
		//print_r($allowedPages);
		$timeStamp = trim($_SESSION['admin']['timeStamp']);
		$loginTime = trim($_SESSION['admin']['loginTime']);
		$sessionTime = trim($_SESSION['admin']['sessionTime']);
		
		if ($sessionTime < time()) {
			//add to log
			$logArray['object'] = "users";
			$logArray['object_id'] = $_SESSION['admin']['id'];
			$logArray['owner'] = "admin";
			$logArray['owner_id'] = $_SESSION['admin']['id'];
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
		} else if (!in_array($redirect, $allowedPages)) {
			header("location: default?redirect=".$redirect."&".$urlData[1]);
		} else {
			$_SESSION['admin']['sessionTime'] = time() + 1800;
		}
	} else {
		header("location: login?redirect=".$redirect."&msg=please+login"."&".$urlData[1]);
	}
?>