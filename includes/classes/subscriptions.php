<?php
	class subscriptions extends common {
		function add($array) {
			$title = ucfirst(strtolower($this->mysql_prep($array['title'])));
			$amount = $this->mysql_prep($array['amount']);
			$validity = $this->mysql_prep($array['validity']);
			$status = $this->mysql_prep($array['status']);
			$type = $this->mysql_prep($array['type']);
			$create_time = $modify_time = time();
			$ref = $this->mysql_prep($array['ref']);
			
			global $db;
			$value_array = array(
							':title' => $title, 
							':status' => $status,
							':validity' => $validity,
							':amount' => $amount,
							':type' => $type,
							':create_time' => $create_time,
							':modify_time' => $modify_time
							);	
							
			if ($ref != "") {
				$firstpart = "`ref`, ";
				$secondPArt = ":ref, ";
				$value_array[':ref'] = $ref;
				$log = "Modified object ".$title;
			} else {
				$firstpart = "";
				$secondPArt = "";
				$log = "Created object ".$title;
			}	
			
			try {
				$sql = $db->prepare("INSERT INTO `subscriptions` (".$firstpart."`title`, `status`, `validity`, `amount`, `type`, `create_time`, `modify_time`)
				VALUES (".$secondPArt.":title, :status, :validity, :amount, :type, :create_time, :modify_time)
					ON DUPLICATE KEY UPDATE 
						`status` = :status,
						`title` = :title,
						`amount` = :amount,
						`type` = :type,
						`create_time` = :create_time,
						`modify_time` = :modify_time
					");
				$sql->execute($value_array);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
						
			if ($sql) {
				$id = $db->lastInsertId();
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = intval($_SESSION['admin']['id']);
				$logArray['desc'] = "created subscriptions ".$log;
				$logArray['create_date'] = time();
				$system_log = new system_log;
				$system_log->create($logArray);
				return $id;
			} else {
				return false;
			}
		}
		
		function remove($id) {
			$id = $this->mysql_prep($id);
			global $db;
			try {
				$sql = $db->prepare("DELETE FROM `subscriptions` WHERE `ref` =:id");
				$sql->execute(
					array(
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
				$logArray['owner_id'] = intval($_SESSION['admin']['id']);
				$logArray['desc'] = "removed subscriptions Item with Ref ".$id;
				$logArray['create_date'] = time();
				$system_log = new system_log;
				$system_log->create($logArray);
				return true;
			} else {
				return false;
			}
		}
		
		function modifyOne($tag, $value, $id) {
			$value = $this->mysql_prep($value);
			$id = $this->mysql_prep($id);
			
			global $db;
			try {
				$sql = $db->prepare("UPDATE `subscriptions` SET  `".$tag."` = :value, `modify_time` = :modifyTime WHERE `ref`=:id");
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
				return true;
			} else {
				return false;
			}
		}
		
		function listAll() {
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `subscriptions` ORDER BY `ref` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['title'] = ucwords(strtolower($row['title']));
					$result[$count]['validity'] = $row['validity'];
					$result[$count]['amount'] = $row['amount'];
					$result[$count]['type'] = $row['type'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function sortAll($id, $tag, $tag2=false, $id2=false, $tag3=false, $id3=false, $orderby = "ref", $dir="ASC", $limit=false) {
			$token = array(':id' => $id);
			if ($tag2 != false) {
				$sqlTag = " AND `".$tag2."` = :id2";
				$token[':id2'] = $id2;
			} else {
				$sqlTag = "";
			}
			if ($tag3 != false) {
				$sqlTag .= " AND `".$tag3."` = :id3";
				$token[':id3'] = $id3;
			} else {
				$sqlTag .= "";
			}

			if ($limit == true) {
				$limitTag = " LIMIT ".$limit;
			} else {
				$limitTag = "";
			}
			
			if ($orderby == "rand") {
				$order = "RAND()";
			} else {
				$order = "`".$orderby."`".$dir;
			}

			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM `subscriptions` WHERE `".$tag."` = :id".$sqlTag." ORDER BY ".$order.$limitTag);
								
				$sql->execute($token);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				$result = array();
				$count = 0;
				
				foreach($sql->fetchAll(PDO::FETCH_ASSOC) as $row) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['title'] = ucwords(strtolower($row['title']));
					$result[$count]['validity'] = $row['validity'];
					$result[$count]['amount'] = $row['amount'];
					$result[$count]['type'] = $row['type'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function getOne($id, $tag='ref') {
			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM subscriptions WHERE `".$tag."` = :id ORDER BY `ref` DESC LIMIT 1");
				$sql->execute(
					array(
					':id' => $id)
				);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$result = array();
			$row = $sql->fetch(PDO::FETCH_ASSOC);
			$result['ref'] = $row['ref'];
			$result['title'] = ucwords(strtolower($row['title']));
			$result['validity'] = $row['validity'];
			$result['amount'] = $row['amount'];
			$result['type'] = $row['type'];
			$result['status'] = $row['status'];
			$result['create_time'] = $row['create_time'];
			$result['modify_time'] = $row['modify_time'];
			return $this->out_prep($result);
		}
		
		function getOneField($id, $tag="ref", $ref="title") {
			$data = $this->getOne($id, $tag);
			return $data[$ref];
		}

		function autoRenewal() {
			global $users;
			global $orders;
			global $transactions;
			$data = $users->listAllAutoRenew();

			for ($i=0; $i < count($data); $i++) {
				if ($data[$i]['card_token'] != "") {

					$array = $orders->getOne($data[$i]['subscription_order']);

					unset($array['modify_time']);
					unset($array['create_time']);
					unset($array['last_modified_by']);
					unset($array['order_status']);
					unset($array['ref']);
					$create = $orders->create($array);
					//$create = "199_198";

					if ($create) {
						$postData = array();
						$splitOrderData = explode("_", $create);
						$postData['token'] = $data[$i]['card_token'];
						$postData['order_owner'] = $array['order_owner'];
						$postData['total'] = $array['order_amount_net'];
						$postData['order'] = $splitOrderData[0];
						$postData['tx_id'] = $splitOrderData[1];

						$trannsData = json_decode($transactions->postTransactionAuto($postData), true);

						if (($trannsData['status'] == "success") && ($trannsData['data']['chargeResponseCode'] == "00")) {						
							$orders->updateOne("order_status", "COMPLETE", $splitOrderData[0]);
							$transactions->updateOne("transaction_status", "PAID", $splitOrderData[1]);
							$orders->orderNotification($splitOrderData[0]);
							$orders->updateSubscrption($splitOrderData[0]);
							$users->modifyOne("payment_frequency_retry_count", 0, $data[$i]['ref']);
							$error = false;
						} else {
							$users->modifyOne("payment_frequency_retry", time()+(60*60*6), $data[$i]['ref']);
							$users->modifyOne("payment_frequency_retry_count", $data[$i]['payment_frequency_retry_count']+1, $data[$i]['ref']);
							$orders->updateOne("order_status", "CANCELLED", $splitOrderData[0]);
							$orders->updateOne("payment_status", "CANCELLED", $splitOrderData[0]);
							$transactions->updateOne("transaction_status", "CANCELLED", $splitOrderData[1]);
							$message = "The automatic renewal of your subscription was not successful due to the following reasons: ".$result['data']['status'].", we will try this payment again in 6 hours, if you continue to recieve this message, please log into your account and change your payment method";
							$error = true;
						}

						if ($error == true) {
							$this->orderNotification($splitOrderData[0], $message);
						}
					}
				}
			}
		}
		
		function orderNotification($id, $message) {
			global $users;
			global $orders;
			global $alerts;
			$data = $orders->getOne($id);
			$userData = $users->listOne($data['order_owner']);
			
			$client = $userData['last_name']." ".$userData['other_names']." <".$userData['email'].">";
			$subjectToClient = "LegalLens Subscription: New Order # ".$orders->orderID($id);
			
			$contact = "LegalLens <".replyMail.">";
				
			$fields = 'subject='.urlencode($subjectToClient).
			'&id='.urlencode($id).
			'&message='.urlencode($message);
				
			$mailUrl = URL."includes/emails/subscription_notification.php?".$fields;
			$messageToClient = $this->curl_file_get_contents($mailUrl);
						
			$mail['from'] = $contact;
			$mail['to'] = $client;
			$mail['subject'] = $subjectToClient;
			$mail['body'] = $messageToClient;
			
			$alerts->sendEmail($mail);
		}
	}
?>