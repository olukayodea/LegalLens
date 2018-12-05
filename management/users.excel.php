<?php
	$redirect = "users";
	include_once("../includes/functions.php");
	include_once("session.php");
	
	if (isset($_POST['button'])) {
		$list = $users->listAll();
		
		// create header array here...$myHeaders
		$data = "SN"."\t";
		$data .= "Name"."\t";
		$data .= "Email"."\t";
		$data .= "Phone"."\t";
		$data .= "Subscription"."\t";
		$data .= "Status"."\t";
		$data .= "Created"."\t";
		$data .= "Last Modified"."\t\n";
		
		// create data array here... $myData
		for ($i = 0; $i < count($list); $i++) {
			$sn++;
			$data .= $sn."\t";
			$data .= $list[$i]['last_name']." ".$list[$i]['other_names']."\t";
			$data .= $list[$i]['email']."\t";
			$data .= $list[$i]['phone']."\t";
			$data .= $common->get_time_stamp($list[$i]['subscription'])."\t";
			$data .= $list[$i]['status']."\t";
			$data .= date('l jS \of F Y h:i:s A', $list[$i]['date_time'])."\t";
			$data .= date('l jS \of F Y h:i:s A', $list[$i]['modify_time'])."\t\n";
		}
		header("Content-Type: application/vnd.ms-excel");
		header("Content-disposition: attachment; filename=users_sheet.xls");
		header('Content-Length: ' . strlen($data));
		echo $data;
		exit;
	} else {
		header("location: ".$redirect);
	}
?>