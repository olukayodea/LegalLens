<?php
	include_once("../functions.php");
	$term = $common->get_prep($_REQUEST['term']);
	
	$data = $library->quickSearch($term);
	
	for ($i = 0; $i < count($data); $i++) {
		$row['value'] = $data[$i]['title'];
		$result[] = $row;
	}
	
	echo $raw = json_encode($result);
?>