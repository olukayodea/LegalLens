<?php
	$redirect = "order";
	include_once("../includes/functions.php");
	include_once("session.php");
	
	if (isset($_POST['button'])) {
		$from = strtotime($common->mysql_prep($_POST['from']));
		$to = strtotime($common->mysql_prep($_POST['to']))+(60*60*24);
		
		$list = $orders->dataRange($from, $to);
		// create header array here...$myHeaders
		$data = "SN"."\t";
		$data .= "Order ID"."\t";
		$data .= "Subscription"."\t";
		$data .= "Gross Amount"."\t";
		$data .= "Discount"."\t";
		$data .= "Net Amount"."\t";
		$data .= "Payment Type"."\t";
		$data .= "Status"."\t";
		$data .= "Created"."\t";
		$data .= "Last Modified"."\t\n";
		
		// create data array here... $myData
		for ($i = 0; $i < count($list); $i++) {
			$sn++;
			$data .= $sn."\t";
			$data .= strval($orders->orderID($list[$i]['ref']))."\t";
			$data .= $subscriptions->getOneField($list[$i]['order_subscription'])." (".$list[$i]['order_subscription_type'].")"."\t";
			$data .= strval(number_format($list[$i]['order_amount_gross']))."\t";
			$data .= $list[$i]['order_amount_discount']."\t";
			$data .= strval(number_format($list[$i]['order_amount_net']))."\t";
			$data .= $list[$i]['payment_type']."\t";
			$data .= $list[$i]['order_status']."\t";
			$data .= date('l jS \of F Y h:i:s A', $list[$i]['create_time'])."\t";
			$data .= date('l jS \of F Y h:i:s A', $list[$i]['modify_time'])."\t\n";
		}
		header("Content-Type: application/vnd.ms-excel");
		header("Content-disposition: attachment; filename=order_sheet.xls");
		header('Content-Length: ' . strlen($data));
		echo $data;
		exit;
	} else {
		header("location: ".$redirect);
	}
?>