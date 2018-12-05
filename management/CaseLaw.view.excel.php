<?php
	$redirect = "CaseLaw.view";
	include_once("../includes/functions.php");
	include_once("session.php");
	
	if (isset($_POST['button2'])) {
		$type = $common->get_prep($_POST['type']);
		$id = $common->get_prep($_POST['id']);		
		$from = $common->get_prep($_POST['from']);
		$to = $common->get_prep($_POST['to']);
		
		if ($type == "case") {
			$list = $caselaw->counter($id, $from, $to);
			
			// create header array here...$myHeaders
			$data = "SN"."\t";
			$data .= "Document"."\t";
			$data .= "User"."\t";
			$data .= "Date"."\t\n";
			
			// create data array here... $myData
			for ($i = 0; $i < count($list); $i++) {
				$sn++;
				$data .= $sn."\t";
				$data .= $list[$i]['title']."\t";
				$data .= $users->getOneField($list[$i]['user_id'], "ref", "kast_name")." ".$users->getOneField($list[$i]['user_id'], "ref", "other_names")."\t";
				$data .= date('l jS \of F Y h:i:s A', $list[$i]['date_time'])."\t\n";
			}
		
		} else if ($type == "article") {
			$list = $articles->counter($id, $from, $to);
			
			// create header array here...$myHeaders
			$data = "SN"."\t";
			$data .= "Document"."\t";
			$data .= "User"."\t";
			$data .= "Date"."\t\n";
			
			// create data array here... $myData
			for ($i = 0; $i < count($list); $i++) {
				$sn++;
				$data .= $sn."\t";
				$data .= $list[$i]['title']."\t";
				$data .= $users->getOneField($list[$i]['user_id'], "ref", "kast_name")." ".$users->getOneField($list[$i]['user_id'], "ref", "other_names")."\t";
				$data .= date('l jS \of F Y h:i:s A', $list[$i]['date_time'])."\t\n";
			}
		
		} else if ($type == "doc") {
			$list = $documents->counter($id, $from, $to);
			
			// create header array here...$myHeaders
			$data = "SN"."\t";
			$data .= "Document"."\t";
			$data .= "Section"."\t";
			$data .= "User"."\t";
			$data .= "Date"."\t\n";
			
			// create data array here... $myData
			for ($i = 0; $i < count($list); $i++) {
				$sn++;
				$data .= $sn."\t";
				$data .= $list[$i]['title']."\t";
				$data .= $list[$i]['section']."\t";
				$data .= $users->getOneField($list[$i]['user_id'], "ref", "kast_name")." ".$users->getOneField($list[$i]['user_id'], "ref", "other_names")."\t";
				$data .= date('l jS \of F Y h:i:s A', $list[$i]['date_time'])."\t\n";
			}
		
		} else if ($type == "reg") {
			$list = $regulations->counter($id, $from, $to);
			
			// create header array here...$myHeaders
			$data = "SN"."\t";
			$data .= "Document"."\t";
			$data .= "User"."\t";
			$data .= "Date"."\t\n";
			
			// create data array here... $myData
			for ($i = 0; $i < count($list); $i++) {
				$sn++;
				$data .= $sn."\t";
				$data .= $list[$i]['title']."\t";
				$data .= $users->getOneField($list[$i]['user_id'], "ref", "kast_name")." ".$users->getOneField($list[$i]['user_id'], "ref", "other_names")."\t";
				$data .= date('l jS \of F Y h:i:s A', $list[$i]['date_time'])."\t\n";
			}
		}
		
		
		header("Content-Type: application/vnd.ms-excel");
		header("Content-disposition: attachment; filename=page_view_sheet.xls");
		header('Content-Length: ' . strlen($data));
		echo $data;
		exit;
	} else {
		header("location: ".$redirect);
	}
?>