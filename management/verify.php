<?php
	include_once("../includes/functions.php");
	$id = $common->get_prep($_GET['id']);
	$link = $common->get_prep($_GET['data']);
	
	$data = $transactions->getOne($id, "ref");
		
	$mackey = "68466204A33A75724CC43810F794550093969EE824A02F959411D6601051E26D3DBD15C361DB827D4925883F926A455408ACC8E0DFDBAECAF6D4EF3363D4B3BC";
	$product_id = 6699;
	
	$txnref = $data['transaction_id'];
	
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
	
	$headers = array("GET /HTTP/1.1","User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.1) Gecko/2008070208 Firefox/3.0.1","Accept-Language: en-us,en;q=0.5","Keep-Alive: 300","Connection: keep-alive","Hash: $thash " ); // computed hash now added to header of my request
	
	$ch = curl_init(); // initiate the request
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt( $ch, CURLOPT_POST, false );
	$response = curl_exec($ch);
	curl_close($ch);
	
	$rawData = json_decode($response, true);
	
	if ($rawData['ResponseCode'] == "00") {
		$orders->updateOne("order_status", "COMPLETE", $data['order_id']);
		$transactions->updateOne("transaction_status", "PAID", $data['ref']);
		$orders->orderNotification($data['order_id'], "reciept", $code);
		$orders->updateSubscrption($data['order_id']);
		$stat = "done";
	} else {
		$transactions->updateOne("transaction_status", "PENDING", $data['ref']);
		$stat = "error=transaction+not+verified";
	}
	header("location: transactions?".$stat);
?>