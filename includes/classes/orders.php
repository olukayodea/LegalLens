<?php
	class orders extends common {
		function create($array) {
			$transactions = new transactions;
			
			$order_owner = $this->mysql_prep($array['order_owner']);
			$order_amount_net = $this->mysql_prep($array['order_amount_net']);
			$order_subscription = $this->mysql_prep($array['order_subscription']);
			$order_amount_discount = $this->mysql_prep($array['order_amount_discount']);
			$order_amount_gross = $this->mysql_prep($array['order_amount_gross']);
			$order_subscription_type = $this->mysql_prep($array['order_subscription_type']);
			$order_users = $this->mysql_prep($array['order_users']);
			$payment_type = $this->mysql_prep($array['payment_type']);
			$create_time = $modify_time = time();
						
			$sql = mysql_query("INSERT INTO `orders` (`order_owner`,`order_amount_net`,`order_subscription`,`order_amount_discount`,`order_amount_gross`,`order_subscription_type`,`payment_type`,`order_users`,`create_time`,`modify_time`) VALUES ('".$order_owner."','".$order_amount_net."','".$order_subscription."','".$order_amount_discount."','".$order_amount_gross."','".$order_subscription_type."','".$payment_type."','".$order_users."','".$create_time."','".$modify_time."')") or die (mysql_error());
			
			if ($sql) {
				$id = mysql_insert_id();
				$notification = new notification;
				if ($payment_type != "Online") {
					$notification_array['type'] = "orders";
					$notification_array['type_id'] = $id;
					$notification_array['desc'] = "New Order Notification";
					$notification->create($notification_array);
				}
			
				$array['amount'] = $order_amount_net;
				$array['transaction_channel'] = $payment_type;
				$array['order_id'] = $id;
				$array['user_id'] = $order_owner;
				
				$tx_id = $transactions->create($array);
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "users";
				$logArray['owner_id'] = $_SESSION['users']['ref'];
				$logArray['desc'] = $log;
				$logArray['create_time'] = time();
				$system_log = new system_log;
				$system_log->create($logArray);
				return $id."_".$tx_id;
			} else {
				return false;
			}
		}
		
		function updateOne($tag, $value, $id) {
			$id = $this->mysql_prep($id);
			$value = $this->mysql_prep($value);
			$sql = mysql_query("UPDATE orders SET `".$tag."` = '".$value."', `modify_time` = '".time()."' WHERE ref = '".$id."'") or die (mysql_error());
			
			if ($sql) {
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = $_SESSION['admin']['id'];
				$logArray['desc'] = "modified field ".$tag." as ".$value." for object";
				$logArray['create_time'] = time();
				$system_log = new system_log;
				$system_log->create($logArray);
				return true;
			} else {
				return false;
			}
		}
		
		function listAll($limit=false) {
			if ($limit == true) {
				$add = " LIMIT ".$limit;
			} else {
				$add = "";
			}
			$sql = mysql_query("SELECT * FROM `orders` ORDER BY `ref` DESC".$add) or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['order_owner'] = $row['order_owner'];
					$result[$count]['order_amount_net'] = $row['order_amount_net'];
					$result[$count]['order_subscription'] = $row['order_subscription'];
					$result[$count]['order_amount_discount'] = $row['order_amount_discount'];
					$result[$count]['order_amount_gross'] = $row['order_amount_gross'];
					$result[$count]['order_subscription_type'] = $row['order_subscription_type'];
					$result[$count]['payment_type'] = $row['payment_type'];
					$result[$count]['order_users'] = $row['order_users'];
					$result[$count]['order_status'] = $row['order_status'];
					$result[$count]['last_modified_by'] = $row['last_modified_by'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function dataRange($from, $to) {
			$sql = mysql_query("SELECT * FROM `orders` WHERE `modify_time` BETWEEN '".$from."' AND '".$to."' ORDER BY `modify_time` DESC".$add) or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['order_owner'] = $row['order_owner'];
					$result[$count]['order_amount_net'] = $row['order_amount_net'];
					$result[$count]['order_subscription'] = $row['order_subscription'];
					$result[$count]['order_amount_discount'] = $row['order_amount_discount'];
					$result[$count]['order_amount_gross'] = $row['order_amount_gross'];
					$result[$count]['order_subscription_type'] = $row['order_subscription_type'];
					$result[$count]['payment_type'] = $row['payment_type'];
					$result[$count]['order_users'] = $row['order_users'];
					$result[$count]['order_status'] = $row['order_status'];
					$result[$count]['last_modified_by'] = $row['last_modified_by'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function sortAll($tag, $id, $limit=false, $id2=false, $tag2=false, $id3=false, $tag3=false) {
			$id = $this->mysql_prep($id);
			if ($limit = true) {
				$tag_limit = " LIMIT ".$limit;
			} else {
				$tag_limit = "";
			}
			$id2 = $this->mysql_prep($id2);
			$id3 = $this->mysql_prep($id3);
			if ($tag2 != false) {
				$sqlTag = " AND `".$tag2."` = '".$id2."'";
			} else {
				$sqlTag = "";
			}
			if ($tag3 != false) {
				$sqlTag .= " AND `".$tag3."` = '".$id3."'";
			} else {
				$sqlTag .= "";
			}
				
			$sql = mysql_query("SELECT * FROM `orders` WHERE `".$tag."`  = '".$id."'".$sqlTag."  ORDER BY `ref` DESC".$tag_limit) or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['order_owner'] = $row['order_owner'];
					$result[$count]['order_amount_net'] = $row['order_amount_net'];
					$result[$count]['order_subscription'] = $row['order_subscription'];
					$result[$count]['order_amount_discount'] = $row['order_amount_discount'];
					$result[$count]['order_amount_gross'] = $row['order_amount_gross'];
					$result[$count]['order_subscription_type'] = $row['order_subscription_type'];
					$result[$count]['payment_type'] = $row['payment_type'];
					$result[$count]['order_users'] = $row['order_users'];
					$result[$count]['order_status'] = $row['order_status'];
					$result[$count]['last_modified_by'] = $row['last_modified_by'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function getOne($id, $tag='ref') {
			$id = $this->mysql_prep($id);
			$sql = mysql_query("SELECT * FROM `orders` WHERE `".$tag."` = '".$id."'") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				
				$row = mysql_fetch_array($sql);
				if ($row > 0) {
					$result['ref'] = $row['ref'];
					$result['order_owner'] = $row['order_owner'];
					$result['order_amount_net'] = $row['order_amount_net'];
					$result['order_subscription'] = $row['order_subscription'];
					$result['order_amount_discount'] = $row['order_amount_discount'];
					$result['order_amount_gross'] = $row['order_amount_gross'];
					$result['order_subscription_type'] = $row['order_subscription_type'];
					$result['payment_type'] = $row['payment_type'];
					$result['order_users'] = $row['order_users'];
					$result['order_status'] = $row['order_status'];
					$result['last_modified_by'] = $row['last_modified_by'];
					$result['create_time'] = $row['create_time'];
					$result['modify_time'] = $row['modify_time'];
				}
				
				return $this->out_prep($result);
			}
		}
                
		function getOneField($id, $tag="ref", $ref="order_amount_net") {
			$data = $this->getOne($id, $tag);
			return $data[$ref];
		}
		
		function unWrap($data) {
			$result = json_decode(base64_decode($data), true);
			return $result;
		}
		
		function orderID($ref) {
			return date("Y").(200000000+$ref);
		}
		
		function orderNotification($id) {
			$users = new users;
			$data = $this->getOne($id);
			$userData = $users->listOne($data['order_owner']);
			
			$client = $userData['last_name']." ".$userData['other_names']." <".$userData['email'].">";
			$subjectToClient = "LegalLens Subscription: New Order # ".$this->orderID($id);
			
			$contact = "LegalLens <".replyMail.">";
				
			$fields = 'subject='.urlencode($subjectToClient).
				'&id='.urlencode($id);
				
			$mailUrl = URL."includes/emails/paymentNotification.php?".$fields;
			$messageToClient = $this->curl_file_get_contents($mailUrl);
						
			$mail['from'] = $contact;
			$mail['to'] = $client;
			$mail['subject'] = $subjectToClient;
			$mail['body'] = $messageToClient;
			
			$alerts = new alerts;
			$alerts->sendEmail($mail);
		}
		
		function updateSubscrption($id) {
			$users = new users;
			$subscriptions = new subscriptions;
			$data = $this->getOne($id);
			$order_subscription = $data['order_subscription'];
			$order_subscription_type = $data['order_subscription_type'];
			$order_owner = $data['order_owner'];
			$former_sub = $users->getOneField($order_owner, "ref", "subscription");
			
			$sub_data = $subscriptions->getOne($order_subscription);
			
			if ($former_sub > time()) {
				$a = $former_sub-time();
				$b = ceil($a/(60*60*24));
			} else {
				$b = 0;
			}
			
			$newTime = time()+((60*60*24)*($b+$sub_data['validity']));
			
			$users->modifyOne("subscription",$newTime, $data['order_owner']);
			$users->modifyOne("subscription_type", $order_subscription, $data['order_owner']);
			$users->modifyOne("subscription_group", $data['order_owner'], $data['order_owner']);
			$users->modifyOne("subscription_order", $id, $data['order_owner']);
			$_SESSION['users']['subscription'] = $newTime;
			$_SESSION['users']['subscription_group_onwer'] = $data['order_owner'];
			$_SESSION['users']['subscription_type'] = $order_subscription;
			if ($order_subscription_type == "group") { 
				$users->modifyOne("subscription_group_onwer", 1, $data['order_owner']);
				$_SESSION['users']['subscription_group'] = 1;
			}
		}
	}
?>