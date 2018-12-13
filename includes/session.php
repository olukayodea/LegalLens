<?php
	$urlData = explode("?", $_SERVER['REQUEST_URI']);
	$chkDevice = $usersControl->checkLogin();
	if ($chkDevice != true) {
		$users->logout();
	}
	if (isset($_SESSION['users']['ref'])) {
		$ref = trim($_SESSION['users']['ref']);
		$username = trim($_SESSION['users']['username']);
		$last_name = trim($_SESSION['users']['last_name']);
		$other_names = trim($_SESSION['users']['other_names']);
		$phone = trim($_SESSION['users']['phone']);
		$email = trim($_SESSION['users']['email']);
		$subscription = trim($_SESSION['users']['subscription']);
		$subscription_type = trim($_SESSION['users']['subscription_type']);
		$subscription_group = trim($_SESSION['users']['subscription_group']);
		$subscription_group_onwer = trim($_SESSION['users']['subscription_group_onwer']);
		$loginTime = trim($_SESSION['users']['loginTime']);
		$modify_time = trim($_SESSION['users']['modify_time']);
		$date_time = trim($_SESSION['users']['date_time']);
		$last_login = trim($_SESSION['users']['last_login']);
		
		if (($subscription < time()) && ($redirect != "managesubscription") && ($redirect != "confirmation")) {
			header("location: managesubscription?renew");
		}
	} else {
		header("location: ./?redirect=".$redirect."&msg=please+login"."&".$urlData[1]);
	}
?>