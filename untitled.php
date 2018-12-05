<?php
include_once("includes/functions.php");
//$list = $users->listAll();
//print_r($list);
//
//for ($i = 0; $i < count($list); $i++) {
//	$username = $users->username($list[$i]['last_name'],$list[$i]['other_names']);
//	$users->modifyOne("username", $username, $list[$i]['ref']);
//}

print_r($categories->getParent(6));
?>