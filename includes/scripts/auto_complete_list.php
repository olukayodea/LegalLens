<?php
	include_once("../functions.php");
	$type = $common->get_prep($_REQUEST['type']);
	$term = $common->get_prep($_REQUEST['term']);
	
	$data = $listItem->quickSearch($term, $type);
	
	for ($i = 0; $i < count($data); $i++) {
		$row['value'] = $data[$i]['title'];
		$result[] = $row;
	}
	
	echo $raw = json_encode($result);
?>