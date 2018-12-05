<?php
	include_once("includes/functions.php");
	
	$id = $common->get_prep($_REQUEST['ref']);
	
	$usersControl->logout("ref", $id);
	header("location: ./");
?>