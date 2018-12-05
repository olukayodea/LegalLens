<?php
	include_once("includes/functions.php");
		
	$mackey = "68466204A33A75724CC43810F794550093969EE824A02F959411D6601051E26D3DBD15C361DB827D4925883F926A455408ACC8E0DFDBAECAF6D4EF3363D4B3BC";
	$product_id = 6699;
	
	$txnref = $common->mysql_prep($_POST['txnref']);
	//$txnref = "201629039";
	
	$data = $transactions->getOne($txnref, "transaction_id");
	$submittedamt = $data['amount']*100;
	
	$nhash = $product_id.$txnref.$mackey;
	$thash = hash('sha512',$nhash);
	
	$valuesforurl = array(
	"productid"=>$product_id,
	"transactionreference"=>$txnref,
	"amount"=>$submittedamt
	);
	$outvalue = http_build_query($valuesforurl) . "\n";
		
	$url = "https://webpay.interswitchng.com/paydirect/api/v1/gettransaction.json?$outvalue "; // json
	//$url = "https://stageserv.interswitchng.com/test_paydirect/api/v1/gettransaction.json?".$outvalue;
	
	$headers = array("GET /HTTP/1.1","User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.1) Gecko/2008070208 Firefox/3.0.1","Accept-Language: en-us,en;q=0.5","Keep-Alive: 300","Connection: keep-alive","Hash: $thash " ); // computed hash now added to header of my request
	
	$ch = curl_init(); // initiate the request
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
	//curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false );
	//curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 2 );
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt( $ch, CURLOPT_POST, false );
	
	$response = curl_exec($ch);
	if(curl_exec($ch) === false) {
		echo 'Curl error: ' . curl_error($ch);
	}
	curl_close($ch);
	
	$rawData = json_decode($response, true);
	
	//print_r($rawData);
	
	if ($rawData['ResponseCode'] == "00") {
		$orders->updateOne("order_status", "COMPLETE", $data['order_id']);
		$transactions->updateOne("transaction_status", "PAID", $data['ref']);
		$orders->orderNotification($data['order_id'], "reciept", $code);
		$orders->updateSubscrption($data['order_id']);
	}
	
	$token = base64_encode($response);
	header("location: confirmation?id=".$data['order_id']."&token=".$token	);
?>