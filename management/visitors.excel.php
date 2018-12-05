<?php
	$redirect = "visitors";
	include_once("../includes/functions.php");
	include_once("session.php");
	
	if (isset($_POST['button'])) {
		$list = $system_log->listAll();
		// create header array here...$myHeaders
						
		$data = "SN"."\t";
		$data .= "Address"."\t";
		$data .= "City"."\t";
		$data .= "Region"."\t";
		$data .= "Country"."\t";
		$data .= "Cordinate"."\t";
		$data .= "Date"."\t\n";
		
		// create data array here... $myData
		for ($i = 0; $i < count($list); $i++) {
			$sn++;
			$data .= $sn."\t";
			$data .= $list[$i]['address']."\t";
			$data .= $list[$i]['loc_city']."\t";
			$data .= $list[$i]['loc_region']."\t";
			$data .= $list[$i]['loc_country']."\t";
			$data .= $list[$i]['loc_lat']."\t";
			$data .= date('l jS \of F Y h:i:s A', $list[$i]['create_time'])."\t\n";
		}
		header("Content-Type: application/vnd.ms-excel");
		header("Content-disposition: attachment; filename=visitors_sheet.xls");
		header('Content-Length: ' . strlen($data));
		echo $data;
		exit;
	} else {
		header("location: ".$redirect);
	}
?>