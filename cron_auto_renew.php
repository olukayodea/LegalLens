<?php
	date_default_timezone_set("Africa/Lagos");
	
	ini_set("session.cookie_domain", ".legallens.com.ng/");
	define("URL", "http://legallens.com.ng/", true);
	define("servername", "localhost", true);
	define("dbusername", "legallen_main", true);
	define("dbpassword", "=uS%2bMuBS+(", true);
	define("dbname", "legallen_main", true);
	
/*	define("URL", "http://127.0.0.1/legallens/", true);
	define("servername", "localhost", true);
	define("dbusername", "root", true);
	define("dbpassword", "mysql", true);
	define("dbname", "linnkste_legalens", true); */

	
	define("limit", 20, true);
	
	//define("PBFPubKey", "FLWPUBK-c5327fab43d186ad575ade63e781a50c-X", true);
	define("PBFPubKey", "FLWPUBK-ea082a65616f96bb1aebdadd3689a394-X", true);
	//define("SecKey", "FLWSECK-6b8179de305467437ac49d99b9c647ce-X", true);
	define("SecKey", "FLWSECK-597a1c26983866efda2996f9e8a6034b-X", true);
	//define("tokenCharge", "https://ravesandboxapi.flutterwave.com/flwv3-pug/getpaidx/api/tokenized/charge", true);
	//define("flVerify", "https://ravesandboxapi.flutterwave.com/flwv3-pug/getpaidx/api/v2/verify", true);
	define("tokenCharge", "https://api.ravepay.co/flwv3-pug/getpaidx/api/tokenized/charge", true);
	define("flVerify", "https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify", true);

	include_once("includes/classes/config.php");
	include_once("includes/classes/common.php");

	$config = new config;
	$db = $config->connect();

	$common = new common;

	//log and reports
	include_once("includes/classes/system_log.php");
	$system_log = new system_log;
	//emailing
	include_once("includes/classes/alerts.php");
	$alerts = new alerts;

	include_once("includes/classes/users.php");
	include_once("includes/classes/subscriptions.php");
	include_once("includes/classes/orders.php");
	include_once("includes/classes/transactions.php");
	include_once("includes/classes/notification.php");
	$users = new users;
	$subscriptions = new subscriptions;
	$orders = new orders;
	$transactions = new transactions;
	$notification = new notification;

	$subscriptions->autoRenewal();
?>