<?php
	//ini_set('error_reporting', E_ALL);
	include_once("../includes/functions.php");
	/*==========================================================
						parameters							

	key				apikey+merchantID in sha256
	response
		json
		xml
	mode
		users
			action [GET]
				login.
				getBalance, getDetails, getAccount
				payment
				earnings
			action [POST]
				register.
				updateDetails.
				updateAccount
		advert
			action [GET]
				refresh
			action [POST]
				update
		reward
			getData [GET]
			postRequest [POST]
		report
			action [GET]
				advert, earnings, payouts
		list
			action [GET]
				category, ageRange, gender, state, bank
				
	
	========================================================== */
	/*==========================================================
						error codes							
	101 = unknown Key
	
	103 = invalid mode selected
	
	104 = not allowed
	105 = internal server error
	
	106 = registration error, multiple account
	107 = invalid login
	108 = new user, activation required
	109 = inactive account
	110 = error updating user
	111 = error updating account
	
	116 = unknown user
	117 = missing user account
	
	120	= invalid input type
	
	125 = error processingg request, invalid inputs detected
	126 = input amout out of allowed range for payout allowed range is 100 to 30000
	127 = input amout out of allowed range for topup allowed range is 50 to 50000
	128 = incomplete account details
	129 = insufficient balance
	
	130 = refresh error
	200 = ready
	201 = completed
	404 = unkowm
	500	= inernal server error
	========================================================== */
	//$xml = @simplexml_load_string("sample");
	
	//$xml = @simplexml_load_string(file_get_contents('http://127.0.0.1:8080/rewardArcade/api/requestXml.xml'));

	$date = file_get_contents('php://input');
	//$txt = $common->mysql_prep($date."________________".$_REQUEST['request']."\n");
	//mysql_query("INSERT INTO `dump` (`data`) VALUES ('".$txt."')") or die (mysql_error());
	
	echo $data = $api->prep($_REQUEST['request'], $date);
?>