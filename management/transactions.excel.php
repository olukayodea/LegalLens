<?php
	$redirect = "transactions";
	include_once("../includes/functions.php");
	include_once("session.php");
	
	if (isset($_POST['button'])) {
		$from = strtotime($common->mysql_prep($_POST['from']));
		$to = strtotime($common->mysql_prep($_POST['to']))+(60*60*24);
		
		$list = $transactions->dataRange($from, $to);
		// create header array here...$myHeaders
		$data = "SN"."\t";
		$data .= "Txn ID"."\t";
		$data .= "Order ID"."\t";
		$data .= "Amount"."\t";
		$data .= "Channel"."\t";
		$data .= "Status"."\t";
		$data .= "Created"."\t";
		$data .= "Last Modified"."\t\n";
		
		// create data array here... $myData
		for ($i = 0; $i < count($list); $i++) {
			$sn++;
			$data .= $sn."\t";
			$data .= $list[$i]['transaction_id']."\t";
			$data .= strval($orders->orderID($list[$i]['order_id']))."\t";
			$data .= strval(number_format($list[$i]['amount']))."\t";
			$data .= $list[$i]['transaction_channel']."\t";
			$data .= $list[$i]['transaction_status']."\t";
			$data .= date('l jS \of F Y h:i:s A', $list[$i]['create_time'])."\t";
			$data .= date('l jS \of F Y h:i:s A', $list[$i]['modify_time'])."\t\n";
		}
		header("Content-Type: application/vnd.ms-excel");
		header("Content-disposition: attachment; filename=transactions_sheet.xls");
		header('Content-Length: ' . strlen($data));
		echo $data;
		exit;
	} else {
		header("location: ".$redirect);
	}
?>