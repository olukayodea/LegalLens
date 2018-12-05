<?php
	include_once("../functions.php");
	$val = $common->get_prep($_REQUEST['val']); 
	
	$add = $subscriptions->sortAll($val, "type", "status", "active", false, false, "validity");
	
	$array = array();
	for ($i = 0; $i < count($add); $i++) {
		$array[$i][] = $add[$i]['ref'];
		$array[$i][] = $add[$i]['title'];
	}
	echo json_encode(array_values($array));
?>