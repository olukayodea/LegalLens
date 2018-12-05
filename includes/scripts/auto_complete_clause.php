<?php
	include_once("../functions.php");
	$term = $common->get_prep($_REQUEST['term']);
	$type = $common->get_prep($_REQUEST['type']);
	
	$data = $drafting->quickSearch($term, $type);
	$data2 = $drafting->quickSearchSections($term, $type);
	
	$result = array_merge($data, $data2);	
	echo $raw = json_encode($result);
?>