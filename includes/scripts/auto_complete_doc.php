<?php
	include_once("../functions.php");
	$term = $common->get_prep($_REQUEST['term']);
	$type = $common->get_prep($_REQUEST['type']);
	$sort = $common->get_prep($_REQUEST['sort']);
	
	$data = $documents->quickSearch($term, $type, $sort, "cat");
	$data2 = $documents->quickSearchSections($term, $type, $sort, "cat");
	
	$result = array_merge($data, $data2);
	echo $raw = json_encode($result);
?>