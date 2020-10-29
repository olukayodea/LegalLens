<?php
	session_start();
	date_default_timezone_set("Africa/Lagos");
	
    // ini_set('display_errors', 1);
	// error_reporting(E_ALL);
	
	$pageUR1 = $_SERVER["SERVER_NAME"];
	$curdomain = str_replace("www.", "", $pageUR1);

	if (($curdomain == "legallens.com.ng/") || ($curdomain == "legallens.com.ng")) {
		ini_set("session.cookie_domain", ".legallens.com.ng/");
		$URL = "https://legallens.com.ng/";
		$servername = "localhost";
		$dbusername = "legallen_main";
		$dbpassword = "=uS%2bMuBS+(";
		$dbname = "legallen_main";
	} else if (($curdomain == "dev.legallens.net/") || ($curdomain == "dev.legallens.net") ) {
		ini_set("session.cookie_domain", ".legallens.com.ng/");
		$URL = "http://dev.legallens.net/";
		$servername = "localhost";
		$dbusername = "legallen_main";
		$dbpassword = "=uS%2bMuBS+(";
		$dbname = "legallen_main";
	} else { 
		$URL = "http://127.0.0.1/legallens/";
		$servername = "localhost";
		$dbusername = "root";
		$dbpassword = "root";
		$dbname = "linnkste_legalens";
	}

	define("URL", $URL, true);
	define("servername",  $servername, true);
	define("dbusername",  $dbusername, true);
	define("dbpassword",  $dbpassword, true);
	define("dbname",  $dbname, true);
	
	define("limit", 20, true);
	
	include_once("classes/config.php");
	$config = new config;
	$db = $config->connect();
	
	define("URLAdmin", URL."management/", true);
	define("URLClients", URL."clients/", true);
	define("replyMail", "do-not-reply@legallens.com.ng", true);
	define("NGN", "&#8358;", true);

	//define("PBFPubKey", "FLWPUBK-c5327fab43d186ad575ade63e781a50c-X", true);
	define("PBFPubKey", "FLWPUBK-ea082a65616f96bb1aebdadd3689a394-X", true);
	//define("SecKey", "FLWSECK-6b8179de305467437ac49d99b9c647ce-X", true);
	define("SecKey", "FLWSECK-597a1c26983866efda2996f9e8a6034b-X", true);
	//define("tokenCharge", "https://ravesandboxapi.flutterwave.com/flwv3-pug/getpaidx/api/tokenized/charge", true);
	//define("flVerify", "https://ravesandboxapi.flutterwave.com/flwv3-pug/getpaidx/api/v2/verify", true);
	define("tokenCharge", "https://api.ravepay.co/flwv3-pug/getpaidx/api/tokenized/charge", true);
	define("flVerify", "https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify", true);

	//define("flCharge", "https://ravesandboxapi.flutterwave.com/flwv3-pug/getpaidx/api/charge", true);
	//define("flValidateCharge", "https://ravesandboxapi.flutterwave.com/flwv3-pug/getpaidx/api/validatecharge", true);
	define("flCharge", "https://api.ravepay.co/flwv3-pug/getpaidx/api/charge", true);
	define("flValidateCharge", "https://api.ravepay.co/flwv3-pug/getpaidx/api/validatecharge", true);
	include_once("classes/common.php");
	$common = new common;

	if (($curdomain == "legallens.com.ng/") || ($curdomain == "legallens.com.ng") ) {
		$common->http2https();
	}
	//log and reports
	include_once("classes/system_log.php");
	$system_log = new system_log;
	include_once("classes/visitors.php");
	$visitorData = new visitorData;
	$visitorData->addStat($_SERVER['REMOTE_ADDR'], $_SERVER["SERVER_NAME"]);
	
	//emailing
	include_once("classes/alerts.php");
	$alerts = new alerts;
	
	
	include_once("classes/admin.php");
	include_once("classes/users.php");
	include_once("classes/clients.php");
	include_once("classes/browserDetector.php");
	include_once("classes/usersControl.php");
	$admin = new admin;
	$users = new users;
	$clients = new clients;
	$usersControl = new usersControl;
	
	include_once("classes/documents.php");
	include_once("classes/sections.php");
	include_once("classes/categories.php");
	include_once("classes/list.php");
	include_once("classes/library.php");
	include_once("classes/drafting.php");
	include_once("classes/drafting_sections.php");
	include_once("classes/regulators.php");
	include_once("classes/regulations.php");
	include_once("classes/regulations_sections.php");
	include_once("classes/articles.php");
	include_once("classes/articles_sections.php");
	include_once("classes/caselaw.php");
	include_once("classes/caselaw.area.php");
	include_once("classes/caselaw_sections.php");
	$documents = new documents;
	$sections = new sections;
	$categories = new categories;
	$listItem = new listItem;
	$library = new library;
	$drafting = new drafting;
	$drafting_sections = new drafting_sections;
	$regulators = new regulators;
	$regulations = new regulations;
	$regulations_sections = new regulations_sections;
	$articles = new articles;
	$articles_sections = new articles_sections;
	$caselaw = new caselaw;
	$caselaw_area = new caselaw_area;
	$caselaw_sections = new caselaw_sections;
	
	include_once("classes/orders.php");
	include_once("classes/transactions.php");
	include_once("classes/notification.php");
	$orders = new orders;
	$transactions = new transactions;
	$notification = new notification;
	
	include_once("classes/forum.php");
	$forum_categories = new forum_categories;
	$forum_topics = new forum_topics;
	$forum_posts = new forum_posts;
	$forum_users = new forum_users;
	$forum_login = new forum_login;
	
	include_once("classes/search.php");
	include_once("classes/searchHome.php");
	include_once("classes/search.suggest.php");
	include_once("classes/search.users.php");
	$search = new search;
	$searchHome = new searchHome;
	$search_result = new search_result;
	$searchUsers = new searchUsers;
	
	include_once("classes/news.php");
	include_once("classes/advert.php");
	include_once("classes/subscriptions.php");
	include_once("classes/volume.php");
	include_once("classes/settings.php");
	include_once("classes/page_content.php");
	include_once("classes/knowledge_base.php");
	include_once("classes/help.php");
	include_once("classes/faq.php");
	include_once("classes/friendzone.php");
	include_once("classes/pages.php");
	include_once("classes/adminPages.php");
	include_once("classes/api.php");
	include_once("classes/pagination.php");
	$news = new news;
	$advert = new advert;
	$slider = new slider;
	$subscriptions= new subscriptions;
	$volume = new volume;
	$settings = new settings;
	$knowledge_base = new knowledge_base;
	$knowledge_base_category = new knowledge_base_category;
	$help = new help;
	$faq = new faq;
	$friendzone = new friendzone;
	$api = new api;
	
	define("page_list", $settings->getOne("page_list"));
	define("facebook", $settings->getOne("facebook"));
	define("instagram", $settings->getOne("instagram"));
	define("flickr", $settings->getOne("flickr"));
	define("google", $settings->getOne("google"));
	define("linkedin", $settings->getOne("linkedin"));
	define("rss", $settings->getOne("rss"));
	define("skype", $settings->getOne("skype"));
	define("twitter", $settings->getOne("twitter"));
	define("emailData", $settings->getOne("email"));
	define("phoneData", $settings->getOne("phone"));
	
	$page_content = new page_content;
	$pages = new pages;
	$adminPages = new adminPages;
	$pagination = new pagination;
?>