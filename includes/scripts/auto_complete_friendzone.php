<?php
	include_once("../functions.php");
	$term = $common->get_prep($_REQUEST['term']);
	
	$data = $friendzone->findName($term);
	
	for ($i = 0; $i < count($data); $i++) {
		$row['value'] = $data[$i]['last_name']." ".$data[$i]['other_names'];
		$row['ref'] = $data[$i]['ref'];
		$result[] = $row;
	}
	
	echo $raw = json_encode($result);
?>