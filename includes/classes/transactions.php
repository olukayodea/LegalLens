<?php
	class transactions extends common {
		function create($array) {
			$transaction_id = $this->confirmUnique($this->createUnique());
			$amount = $this->mysql_prep($array['amount']);
			$transaction_channel = $this->mysql_prep($array['transaction_channel']);
			$order_id = $this->mysql_prep($array['order_id']);
			$user_id = $this->mysql_prep($array['user_id']);
			$create_time = $modify_time = time();			

			global $db;
			try {
				$sql = $db->prepare("INSERT INTO `transactions` (`transaction_id`,`amount`,`transaction_channel`,`order_id`,`user_id`,`create_time`,`modify_time`) 
				VALUES (:transaction_id, :amount, :transaction_channel, :order_id, :user_id, :create_time, :modify_time)");
				$sql->execute(array(
							':transaction_id' => $transaction_id, 
							':amount' => $amount, 
							':transaction_channel' => $transaction_channel,
							':order_id' => $order_id,
							':user_id' => $user_id,
							':create_time' => $create_time,
							':modify_time' => $modify_time));
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql) {
				$id = $db->lastInsertId();
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "users";
				$logArray['owner_id'] = $_SESSION['users']['ref'];
				$logArray['desc'] = "created transaction ID ".$transaction_id;
				$logArray['create_time'] = time();
				$system_log = new system_log;
				$system_log->create($logArray);
				return $id;;
			} else {
				return false;
			}
		}		
		
		function createUnique() {
			$num = date("Y").rand(11111, 99999);
			return $num;
		}
		
		function confirmUnique($key) {
			$key = $this->mysql_prep($key);
			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM transactions WHERE `transaction_id`= :key");
				$sql->execute(
					array(
					':key' => $key)
				);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			if ($sql->rowCount() == 0) {	
				return $key;
			} else {
				return $this->confirmUnique($this->createUnique());
			}
		}
		
		
		function updateOne($tag, $value, $id) {
			$id = $this->mysql_prep($id);
			$value = $this->mysql_prep($value);
						
			global $db;
			try {
				$sql = $db->prepare("UPDATE `transactions` SET  `".$tag."` = :value, `modify_time` = :modifyTime WHERE `ref`=:id");
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
				$logArray['owner'] = "system";
				$logArray['owner_id'] = 0;
				$logArray['desc'] = "modified field ".$tag." as ".$value." for object";
				$logArray['create_time'] = time();
				$system_log = new system_log;
				$system_log->create($logArray);
				return true;
			} else {
				return false;
			}
		}
		
		function dataRange($from, $to) {
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `transactions` WHERE `modify_time` BETWEEN '".$from."' AND '".$to."' ORDER BY `modify_time` DESC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
		
		function listAll($order= "DESC") {
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `transactions` ORDER BY `modify_time` ".$order);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}

		function sortAll($tag, $id, $limit=false, $id2=false, $tag2=false, $id3=false, $tag3=false, $order="modify_time", $dir="DESC") {
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
				$sql = $db->prepare("SELECT * FROM `transactions` WHERE `".$tag."` = :id".$sqlTag." ORDER BY `".$order."` ".$dir);
								
				$sql->execute($token);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
			return $this->out_prep($row);
		}
		
		function getRange($tag, $from, $to) {
			$tag = $this->mysql_prep($tag);
			$from = $this->mysql_prep($from);
			$to = $this->mysql_prep($to);

			global $db;
			try {
				$sql = $db->query("SELECT * FROM `transactions` WHERE `".$tag."` BETWEEN  ".$from." AND ".$to." ORDER BY `modify_time` DESC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
		
		function getTotal($from=0, $to=0) {
			$from = $this->mysql_prep($from);
			$to = $this->mysql_prep($to);
			global $db;
			try {
				if ($from > 0) {
					$sql = $db->query("SELECT SUM(`amount`) FROM `transactions` WHERE `modify_time` BETWEEN  ".$from." AND ".$to);
				} else {
					$sql = $db->query("SELECT SUM(`amount`) FROM `transactions`");
				}
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			if ($sql) {
				return $sql->fetchColumn();
			}
		}
		
		function getOne($id, $tag='ref') {
			$id = $this->mysql_prep($id);
			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM `transactions` WHERE `".$tag."` = :id ORDER BY `ref` DESC LIMIT 1");
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
                
		function getOneField($id, $tag="ref", $ref="order_id") {
			$data = $this->getOne($id, $tag);
			return $data[$ref];
		}
	}
?>