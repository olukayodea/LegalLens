<?php
	include_once("includes/functions.php");
	
	if (isset($_POST['submit'])) {
		$sub_data = $subscriptions->getOne($_POST['package']);
		$amount = $sub_data['amount']*$_POST['num_user'];
		if ($amount > 0) {
			$discount = ((1 - ($_POST['total']/$amount)) * 100);
		} else {
			$discount = 0;
		}
			
		$array['order_owner'] = $_POST['order_owner'];
		$array['order_subscription'] = $_POST['package'];
		$array['order_users'] = $_POST['num_user'];
		$array['order_amount_net'] = $_POST['total'];
		$array['order_subscription_type'] = $_POST['type'];
		$array['payment_type'] = $_POST['payment_type'];
		$array['payment_frequency'] = $_POST['payment_frequency'];
		$array['order_amount_discount'] = $discount;
		$array['order_amount_gross'] = $amount;
		
		$create = $orders->create($array);
		
		if ($create) {
			$res = explode("_", $create);
			$data = $orders->getOne($res[0]);
			if ($amount < 1) {
				$orders->updateOne("order_status", "COMPLETE", $data['ref']);
				$transactions->updateOne("transaction_status", "PAID", $res[1]);
				$orders->orderNotification($data['ref']);
				$orders->updateSubscrption($data['ref']);
				
				$array['ResponseCode'] = "00";
				$array['TransactionDate'] = date('l jS \of F Y h:i:s A', $data['create_time']);
				$response = json_encode($array);
				$token = base64_encode($response);
				header("location: confirmation?id=".$data['ref']."&token=".$token);
			} elseif ($array['payment_frequency'] == "Renew") {
				global $transactions;
				$_POST['order_owner'] = $data['order_owner'];
				$_POST['order'] = $res[0];
				$_POST['tx_id'] = $res[1];
				$trannsData = $transactions->postTransaction($_POST);
				$result = json_decode($trannsData, true);
				if (($result['status'] == "success") && ($result['message'] == "AUTH_SUGGESTION")) {
					if ($result['data']['suggested_auth'] == "PIN") {
						header("location: flPin?data=".base64_encode(json_encode($_POST)));
					} else if (($result['data']['suggested_auth'] == "AVS_VBVSECURECODE") || ($result['data']['suggested_auth'] == "NOAUTH_INTERNATIONAL") ){
						$_POST['suggested_auth'] = $result['data']['suggested_auth'];
						$trannsData_auth = $transactions->postTransaction($_POST);
						$result_auth = json_decode($trannsData_auth, true);

						if (($result_auth['status'] == "success") && ($result_auth['data']['chargeResponseCode'] == "00")) {
							header("location: flConfirm?txRef=".$result_auth['data']['txRef']);
						} else if (($result_auth['status'] == "success") && ($result_auth['data']['chargeResponseCode'] == "02")) {
							if ($result_auth['data']['authModelUsed'] == "PIN") {
								header("location: flPin?otp&flwRef=".$result_auth['data']['flwRef']."&data=".base64_encode(json_encode($_POST))."&msg=".$result_auth['data']['chargeResponseMessage']);
							} else if ($result_auth['data']['authModelUsed'] == "VBVSECURECODE") {
								header("location: ".$result_auth['data']['authurl']);
							}  else if ($result_auth['data']['authModelUsed'] == "ACCESS_OTP") {
								header("location: ".$result_auth['data']['authurl']);
							} 
						} else {
							$orders->updateOne("order_status", "CANCELLED", $res[0]);
							$orders->updateOne("payment_status", "CANCELLED", $res[0]);
							$transactions->updateOne("transaction_status", "CANCELLED", $res[1]);
							header("location: managesubscription?error=".$result_auth['message']);
						}
					}
				} else if (($result['status'] == "success") && ($result['data']['chargeResponseCode'] == "02")) {
					if ($result['data']['authModelUsed'] == "PIN") {
						header("location: flPin?otp&flwRef=".$result['data']['flwRef']."&data=".base64_encode(json_encode($_POST))."&msg=".$result['data']['chargeResponseMessage']);
					} else if ($result['data']['authModelUsed'] == "VBVSECURECODE") {
						header("location: ".$result['data']['authurl']);
					}  else if ($result['data']['authModelUsed'] == "ACCESS_OTP") {
						header("location: ".$result['data']['authurl']);
					} 
				} else if (($result['status'] == "success") && ($result['data']['chargeResponseCode'] == "00")) {
					header("location: flConfirm?txRef=".$result['data']['txRef']);
				} else {
					$orders->updateOne("order_status", "CANCELLED", $res[0]);
					$orders->updateOne("payment_status", "CANCELLED", $res[0]);
					$transactions->updateOne("transaction_status", "CANCELLED", $res[1]);
					header("location: managesubscription?error=".$result['message']);
				}
			} elseif ($data['payment_type'] == "Online") {
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