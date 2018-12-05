<?php
	include_once("../functions.php");
	$val = $common->get_prep($_REQUEST['val']);
	$users = $common->get_prep($_REQUEST['users']);
	
	$sub_data = $subscriptions->getOne($val);
	
	if ($sub_data) {
		$array['unit'] = $sub_data['amount'];
		if ($sub_data['type'] == "single") {
			$array['amount'] = $sub_data['amount']*1;
			$array['discount'] = $discount = 0;
		} else {
			$array['amount'] = $sub_data['amount']*$users;
			$array['discount'] = $volume->getRange($users);
		}
		$array['total'] = $array['amount']*(1-($array['discount']/100));
		
		echo json_encode(array_values($array));
		
	} else {
		echo 0;
	}	
?>