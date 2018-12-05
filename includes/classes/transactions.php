<?php
	class transactions extends common {
		function create($array) {
			$transaction_id = $this->confirmUnique($this->createUnique());
			$amount = $this->mysql_prep($array['amount']);
			$transaction_channel = $this->mysql_prep($array['transaction_channel']);
			$order_id = $this->mysql_prep($array['order_id']);
			$user_id = $this->mysql_prep($array['user_id']);
			$create_time = $modify_time = time();
			
			$sql = mysql_query("INSERT INTO `transactions` (`transaction_id`,`amount`,`transaction_channel`,`order_id`,`user_id`, `create_time`, `modify_time`) VALUES ('".$transaction_id."','".$amount."','".$transaction_channel."','".$order_id."','".$user_id."','".$create_time."','".$modify_time."')") or die (mysql_error());
			
			if ($sql) {
				$id = mysql_insert_id();
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
			$sql = mysql_query("SELECT * FROM transactions WHERE `transaction_id` = '".$key."'") or die (mysql_error()."sch");
			if (mysql_num_rows($sql) == 0) {
				return $key;
			} else {
				return $this->confirmUnique($this->createUnique());
			}
		}
		
		
		function updateOne($tag, $value, $id) {
			$id = $this->mysql_prep($id);
			$value = $this->mysql_prep($value);
			
			$sql = mysql_query("UPDATE transactions SET `".$tag."` = '".$value."', `modify_time` = '".time()."' WHERE ref = '".$id."'") or die (mysql_error());
			
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
			$sql = mysql_query("SELECT * FROM `transactions` WHERE `modify_time` BETWEEN '".$from."' AND '".$to."' ORDER BY `modify_time` DESC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['transaction_id'] = $row['transaction_id'];
					$result[$count]['amount'] = $row['amount'];
					$result[$count]['transaction_channel'] = $row['transaction_channel'];
					$result[$count]['transaction_status'] = $row['transaction_status'];
					$result[$count]['order_id'] = $row['order_id'];
					$result[$count]['user_id'] = $row['user_id'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function listAll($order= "DESC") {
			$sql = mysql_query("SELECT * FROM `transactions` ORDER BY `modify_time` ".$order) or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['transaction_id'] = $row['transaction_id'];
					$result[$count]['amount'] = $row['amount'];
					$result[$count]['transaction_channel'] = $row['transaction_channel'];
					$result[$count]['transaction_status'] = $row['transaction_status'];
					$result[$count]['order_id'] = $row['order_id'];
					$result[$count]['user_id'] = $row['user_id'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function sortAll($tag, $id) {
			$id = $this->mysql_prep($id);
			$sql = mysql_query("SELECT * FROM `transactions` WHERE `".$tag."`  = '".$id."' ORDER BY `modify_time` DESC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['transaction_id'] = $row['transaction_id'];
					$result[$count]['amount'] = $row['amount'];
					$result[$count]['transaction_channel'] = $row['transaction_channel'];
					$result[$count]['transaction_status'] = $row['transaction_status'];
					$result[$count]['order_id'] = $row['order_id'];
					$result[$count]['user_id'] = $row['user_id'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function getRange($tag, $from, $to) {
			$tag = $this->mysql_prep($tag);
			$from = $this->mysql_prep($from);
			$to = $this->mysql_prep($to);
			$sql = mysql_query("SELECT * FROM `transactions` WHERE `".$tag."` BETWEEN  ".$from." AND ".$to." ORDER BY `modify_time` DESC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['transaction_id'] = $row['transaction_id'];
					$result[$count]['amount'] = $row['amount'];
					$result[$count]['transaction_channel'] = $row['transaction_channel'];
					$result[$count]['transaction_status'] = $row['transaction_status'];
					$result[$count]['order_id'] = $row['order_id'];
					$result[$count]['user_id'] = $row['user_id'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function getTotal($from=0, $to=0) {
			$from = $this->mysql_prep($from);
			$to = $this->mysql_prep($to);
			if ($from > 0) {
				$sql = mysql_query("SELECT SUM(`amount`) FROM `transactions` WHERE `modify_time` BETWEEN  ".$from." AND ".$to) or die (mysql_error());
			} else {
				$sql = mysql_query("SELECT SUM(`amount`) FROM `transactions`") or die (mysql_error());
			}
			
			if ($sql) {
				$row = mysql_fetch_array($sql);
				return $row[0];
			}
		}
		
		function getOne($id, $tag='ref') {
			$id = $this->mysql_prep($id);
			$sql = mysql_query("SELECT * FROM `transactions` WHERE `".$tag."` = '".$id."'") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				
				$row = mysql_fetch_array($sql);
				if ($row > 0) {
					$result['ref'] = $row['ref'];
					$result['transaction_id'] = $row['transaction_id'];
					$result['amount'] = $row['amount'];
					$result['transaction_channel'] = $row['transaction_channel'];
					$result['transaction_status'] = $row['transaction_status'];
					$result['order_id'] = $row['order_id'];
					$result['user_id'] = $row['user_id'];
					$result['create_time'] = $row['create_time'];
					$result['modify_time'] = $row['modify_time'];
				}
				
				return $this->out_prep($result);
			}
		}
                
		function getOneField($id, $tag="ref", $ref="order_id") {
			$data = $this->getOne($id, $tag);
			return $data[$ref];
		}
	}
?>