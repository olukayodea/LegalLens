<?php
	$urlData = explode("?", $_SERVER['REQUEST_URI']);
	$redirect = "helpAndSupport";
	header("location: ./?redirect=".$redirect."&msg=please+login"."&".$urlData[1]);
?>