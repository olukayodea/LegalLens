<?php
	include_once("includes/functions.php");
	
	if (isset($_POST['submit'])) {
		$sub_data = $subscriptions->getOne($_POST['package']);
		$amount = $sub_data['amount']*$_POST['num_user'];
		$discount = ((1 - ($_POST['total']/$amount)) * 100);
			
		$array['order_owner'] = $_POST['order_owner'];
		$array['order_subscription'] = $_POST['package'];
		$array['order_users'] = $_POST['num_user'];
		$array['order_amount_net'] = $_POST['total'];
		$array['order_subscription_type'] = $_POST['type'];
		$array['payment_type'] = $_POST['payment_type'];
		$array['order_amount_discount'] = $discount;
		$array['order_amount_gross'] = $amount;
		
		$create = $orders->create($array);
		
		//$create = "1_1";
		if ($create) {
			$res = explode("_", $create);
			$data = $orders->getOne($res[0]);
			if ($data['payment_type'] == "Online") {
				//for online Payment
				//$transData = $transactions->getOne($add, "order_id");
				header("location: preview?id=".$res[1]);
				
				//for simulation
				
				/*$orders->updateOne("order_status", "COMPLETE", $data['ref']);
				$transactions->updateOne("transaction_status", "PAID", $res[1]);
				$orders->orderNotification($data['ref']);
				$orders->updateSubscrption($data['ref']);
				
				$array['ResponseCode'] = "00";
				$array['TransactionDate'] = date('l jS \of F Y h:i:s A', $data['create_time']);
				$response = json_encode($array);
				$token = base64_encode($response);
				header("location: confirmation?id=".$data['ref']."&token=".$token);*/
			} else {
				$array['ResponseCode'] = "00";
				$array['TransactionDate'] = date('l jS \of F Y h:i:s A', $data['create_time']);
				$response = json_encode($array);
				$token = base64_encode($response);
				$orders->orderNotification($data['ref']);
				header("location: confirmation?id=".$data['ref']."&token=".$token);
			}
		} else {
			header("location: managesubscription?error=".urlencode("There was an error processing your order, please try again or contact the customer support if error persists"));
		}
	} else {
		header("location: home");
	}
?>