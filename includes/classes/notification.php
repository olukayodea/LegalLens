<?php
	class notification extends common {
		function create($array) {
			$type = $this->mysql_prep($array['type']);
			$type_id = $this->mysql_prep($array['type_id']);
			$desc = $this->mysql_prep($array['desc']);
			$create_time = $modify_time = time();
						
			global $db;
			try {
				$sql = $db->prepare("INSERT INTO `notification` (`type`,`type_id`,`desc`,`create_time`,`modify_time`) 
				VALUES (:type, :type_id, :desc, :create_time, :modify_time)");
				$sql->execute(array(
							':type' => $type, 
							':type_id' => $type_id, 
							':desc' => $desc,
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
				$logArray['owner'] = "system";
				$logArray['owner_id'] = 0;
				$logArray['desc'] = "created notification ".$id;
				$logArray['create_time'] = time();
				$system_log = new system_log;
				$system_log->create($logArray);
				return $id;;
			} else {
				return false;
			}
		}
		
		function updateOne($tag, $value, $id) {
			$id = $this->mysql_prep($id);
			$value = $this->mysql_prep($value);
			global $db;
			try {
				$sql = $db->prepare("UPDATE `notification` SET  `".$tag."` = :value, `modify_time` = :modifyTime WHERE `ref`=:id");
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
		
		function listAll($order= "DESC") {
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `notification` ORDER BY `ref`".$order);
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
		
		function sortAll($tag, $id, $limit=false, $id2=false, $tag2=false, $id3=false, $tag3=false, $order="ref", $dir="DESC") {
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
			
			global $db;
			try {
				$sql = $db->prepare("SELECT * FROM `notification` WHERE `".$tag."` = :id".$sqlTag." ORDER BY `".$order."` ".$dir);
								
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
				$sql = $db->prepare("SELECT * FROM `notification` WHERE `".$tag."` = :id ORDER BY `ref` DESC LIMIT 1");
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
		
		function notificationCount() {
			$list = $this->sortAll("status", 0);
			return number_format(count($list));
		}
		
		function notificationCountGroup($group) {
			$list = $this->sortAll("status", 0, "type", $group);
			return number_format(count($list));
		}
		
		function openNotification() {
			$sql = mysql_query("UPDATE notification SET `status` = '1', `modify_time` = '".time()."'") or die (mysql_error());
			global $db;
			try {
				$sql = $db->query("UPDATE `notification` SET `status` = '1', `modify_time` = '".time()."'");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			if ($sql) {
				return true;
			} else {
				return false;
			}
		}
	}
?>