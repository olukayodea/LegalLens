<?php
    include_once("includes/functions.php");
    $result = array();

    if (isset($_GET['mobile'])) {
        $mobile = "mobile_";
        $subscriptions_url = "mobile_subscription";
    } else {
        $mobile = "";
        $subscriptions_url = "managesubscription";
    }

    $postdata =  array( 
    'txref' => $_REQUEST['txRef'],
    'SECKEY' => SecKey
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, flVerify);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($postdata));  //Post Fields
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $headers = [
    'Content-Type: application/json',
    ];

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/includes/classes/cacert.pem");

    $request = curl_exec ($ch);
    $err = curl_error($ch);

    if($err){
        // there was an error contacting rave
        die('Curl returned error: ' . $err);
    }


    curl_close ($ch);

    $result = json_decode($request, true);

    $txData = explode("_", $_REQUEST['txRef']);

    if('error' == $result['status']){

        $orders->updateOne("order_status", "CANCELLED", $txData[1]);
        $orders->updateOne("payment_status", "CANCELLED", $txData[1]);
        $transactions->updateOne("transaction_status", "CANCELLED", $txData[2]);
        header("location: ".$subscriptions_url."?error=".$result->message);
    }

    if('successful' == $result['data']['status'] && '00' == $result['data']['chargecode']){
        $orderData = $orders->getOne($txData[1]);

		$users->modifyOne("card_exp", $result['data']['card']['expirymonth']."/".$result['data']['card']['expiryyear'], $orderData['order_owner']);
		$users->modifyOne("pan", $result['data']['card']['last4digits'], $orderData['order_owner']);
		$users->modifyOne("card_type", $result['data']['card']['type'], $orderData['order_owner']);
		$users->modifyOne("card_token", $result['data']['card']['card_tokens'][0]['embedtoken'], $orderData['order_owner']);
		$users->modifyOne("payment_frequency", $orderData['payment_frequency'], $orderData['order_owner']);
        
        $orders->updateOne("order_status", "COMPLETE", $txData[1]);
		$transactions->updateOne("transaction_status", "PAID", $txData[2]);
		$orders->orderNotification($txData[1]);
        $orders->updateSubscrption($txData[1]);

        $urlData['ResponseCode'] = "00";
        $urlData['isRenew'] = true;
        $urlData['Amount'] = $result['data']['amount'];
        $urlData['TransactionDate'] = $result['data']['created'];
        $urlData['MerchantReference'] = $result['data']['txref'];
        $urlData['PaymentReference'] = $result['data']['flwref'];
        $urlData['CardNumber'] = $result['data']['card']['last4digits'];
        $urlData['ResponseDescription'] = $result['data']['vbvmessage'];

        $token = base64_encode(json_encode($urlData));
	    header("location: ".$mobile."confirmation?id=".$txData[1]."&token=".$token	);
    }
?>