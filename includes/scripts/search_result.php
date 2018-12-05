<?php
	include_once("../functions.php");
	$title = $common->get_prep($_REQUEST['title']);
	$data = $common->get_prep($_REQUEST['data']);
	$array['title'] = $title;
	$array['data'] = $data;
	$array['users'] = $_SESSION['users']['ref'];
	$search_result->add($array);
?>