<?php
	include_once("../functions.php");
	$val = $common->get_prep($_REQUEST['val']);
	$array['title'] = $val;
	$array['users'] = $_SESSION['users']['ref'];
	$searchUsers->add($array);
?>