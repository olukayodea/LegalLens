<?php 
	include_once("includes/functions.php");
	$id = $common->get_prep($_REQUEST['id']);
	$data = $transactions->getOne($id);
	$orderData = $orders->getOne($data['order_id']);
	$product_id = "6699";
	$mackey = trim("68466204A33A75724CC43810F794550093969EE824A02F959411D6601051E26D3DBD15C361DB827D4925883F926A455408ACC8E0DFDBAECAF6D4EF3363D4B3BC");
	$pay_item_id = 101;
	
	$amount = $data['amount']*100;
	$currency = 566;
	$site_redirect_url = URL."response";
	if (isset($_REQUEST['retry'])) {
		$txn_ref = $transaction_id = $transactions->confirmUnique($transactions->createUnique());
		$transactions->updateOne("transaction_id", $txn_ref, $id);
	} else {
		$txn_ref = $data['transaction_id'];
	}
	$cust_id = $orderData['order_owner'];
	$cust_name = $users->getOneField($orderData['order_owner'], "ref", "last_name")." ".$users->getOneField($orderData['order_owner'], "ref", "other_names");
	$string = $txn_ref.$product_id.$pay_item_id.$amount.$site_redirect_url.$mackey;
	$hash = hash("SHA512", $string);
?>
<html>
<head>
<title>Please Wait...</title>
</head>
<body onLoad="document.submit2gtpay_form.submit()">

<div align="center"><img src="images/loading.gif" width="32" height="32"></div>
<form name="submit2gtpay_form" method="post" action="https://webpay.interswitchng.com/paydirect/pay" target="_self">
<!--<form name="submit2gtpay_form" method="post" action="https://stageserv.interswitchng.com/test_paydirect/pay" target="_self">-->

  <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
<input type="hidden" name="amount" value="<?php echo $amount; ?>" />
<input type="hidden" name="currency" value="<?php echo $currency; ?>" />
<input type="hidden" name="site_redirect_url" value="<?php echo $site_redirect_url; ?>" />
<input type="hidden" name="txn_ref" value="<?php echo $txn_ref; ?>" />
<input type="hidden" name="hash" value="<?php echo $hash; ?>" />
<input type="hidden" name="pay_item_id" value="<?php echo $pay_item_id; ?>" />
<input type="hidden" name="cust_id" value="<?php echo $cust_id; ?>" />
<input type="hidden" name="cust_name" value="<?php echo $cust_name; ?>" />
</form>

<script type="text/javascript">
//window.location='response?id=<?php echo $id; ?>';
</script>
</body>
</html>