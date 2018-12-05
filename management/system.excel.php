<?php
	$redirect = "system";
	include_once("../includes/functions.php");
	include_once("session.php");
	
	if (isset($_POST['button'])) {
		$list = $system_log->listAll();
		// create header array here...$myHeaders
		$data = "SN"."\t";
		$data .= "Object"."\t";
		$data .= "Object ID"."\t";
		$data .= "Owner"."\t";
		$data .= "Owner ID"."\t";
		$data .= "Description"."\t";
		$data .= "Date"."\t\n";
		
		// create data array here... $myData
		for ($i = 0; $i < count($list); $i++) {
			$sn++;
			$data .= $sn."\t";
			$data .= $list[$i]['object']."\t";
			$data .= $list[$i]['object_id']."\t";
			$data .= $list[$i]['owner']."\t";
			$data .= $admin->getOneField($list[$i]['owner_id'], "id", "name")."\t";
			$data .= $list[$i]['desc']."\t";
			$data .= date('l jS \of F Y h:i:s A', $list[$i]['create_time'])."\t\n";
		}
		header("Content-Type: application/vnd.ms-excel");
		header("Content-disposition: attachment; filename=system_sheet.xls");
		header('Content-Length: ' . strlen($data));
		echo $data;
		exit;
	} else {
		header("location: ".$redirect);
	}
?>