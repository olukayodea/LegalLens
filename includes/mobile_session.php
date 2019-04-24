<?php
    include_once("functions.php");
    if (isset($_REQUEST['id'])) {
        $ref = $_REQUEST['id'];
    } else if (isset($_COOKIE['ref'])) {
        $ref = $_COOKIE['id'];
    }

    setcookie("ref", $ref, time()+(60*60*24*365), "/");

    $getDetails = $users->listOne($ref);
    $username = trim($getDetails['username']);
    $last_name = trim($getDetails['last_name']);
    $other_names = trim($getDetails['other_names']);
    $phone = trim($getDetails['phone']);
    $email = trim($getDetails['email']);
    $subscription = trim($getDetails['subscription']);
    $subscription_type = trim($getDetails['subscription_type']);
    $subscription_group = trim($getDetails['subscription_group']);
    $subscription_group_onwer = trim($getDetails['subscription_group_onwer']);
    $loginTime = trim($getDetails['loginTime']);
    $modify_time = trim($getDetails['modify_time']);
    $date_time = trim($getDetails['date_time']);
    $last_login = trim($getDetails['last_login']);
		
?>