<?php
	date_default_timezone_set("Africa/Lagos");
	
	
	ini_set("session.cookie_domain", ".legallens.com.ng/");
	define("URL", "http://legallens.com.ng/", true);
	define("servername", "localhost", true);
	define("dbusername", "legallen_main", true);
	define("dbpassword", "=uS%2bMuBS+(", true);
	define("dbname", "legallen_main", true);
	
//	define("URL", "http://127.0.0.1/legallens/", true);
//	define("servername", "localhost", true);
//	define("dbusername", "root", true);
//	define("dbpassword", "mysql", true);
//	define("dbname", "linnkste_legalens", true);

	
	define("limit", 20, true);
	
	include_once("includes/classes/config.php");
	include_once("includes/classes/common.php");
	$config = new config;
	$db = $config->connect();
	
	$common = new common;
	
	//log and reports
	include_once("includes/classes/system_log.php");
	$system_log = new system_log;
	include_once("includes/classes/visitors.php");
	$visitorData = new visitorData;
	$system_log->purge();
	$visitorData->purge();
?>