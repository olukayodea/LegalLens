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

			global $db;
			try {
				$sql = $db->prepare("INSERT INTO `orders` (`order_owner`,`order_amount_net`,`order_subscription`,`order_amount_discount`,`order_amount_gross`,`order_subscription_type`,`payment_type`,`order_users`,`create_time`,`modify_time`) 
				VALUES (:order_owner,:order_amount_net,:order_subscription,:order_amount_discount,:order_amount_gross,:order_subscription_type,:payment_type,:order_users,:create_time,:modify_time)");
				$sql->execute(array(
							':order_owner' => $order_owner, 
							':order_amount_net' => $order_amount_net, 
							':order_subscription' => $order_subscription,
							':order_amount_discount' => $order_amount_discount,
							':order_amount_gross' => $order_amount_gross,
							':order_subscription_type' => $order_subscription_type,
							':payment_type' => $payment_type,
							':order_users' => $order_users,
							':create_time' => $create_time,
							':modify_time' => $modify_time));
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				$id = $db->lastInsertId();
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
			
			global $db;
			try {
				$sql = $db->prepare("UPDATE `orders` SET  `".$tag."` = :value, `modify_time` = :modifyTime WHERE `ref`=:id");
				$sql->execute(
					array(
					':value' => $value,
					':modifyTime' => time(),
					':id' => $id)
				);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
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

			global $db;
			try {
				$sql = $db->query("SELECT * FROM `orders` ORDER BY `ref` DESC".$add);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
		
		function dataRange($from, $to) {
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `orders` WHERE `modify_time` BETWEEN '".$from."' AND '".$to."' ORDER BY `modify_time` DESC".$add);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
		
		function sortAll($tag, $id, $limit=false, $id2=false, $tag2=false, $id3=false, $tag3=false, $order="ref") {
			$token = array(':id' => $id);
			if ($tag2 != false) {
				$sqlTag = " AND `".$tag2."` = :id2";
				$token[':id2'] = $id2;
			} else {
				$sqlTag = "";
			}
			if ($tag3 != false) {
				$sqlTag = " AND `".$tag3."` = :id3";
				$token[':id3'] = $id3;
			} else {
				$sqlTag .= "";
			}
			
			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM `orders` WHERE `".$tag."` = :id".$sqlTag." ORDER BY `".$order."` ASC");
								
				$sql->execute($token);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
			return $this->out_prep($row);
		}
		
		function getOne($id, $tag='ref') {
			$id = $this->mysql_prep($id);
			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM orders WHERE `".$tag."` = :id ORDER BY `ref` DESC LIMIT 1");
				$sql->execute(
					array(
					':id' => $id)
				);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$result = array();
			$row = $sql->fetch(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
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