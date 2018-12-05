<?php
	class notification extends common {
		function create($array) {
			$type = $this->mysql_prep($array['type']);
			$type_id = $this->mysql_prep($array['type_id']);
			$desc = $this->mysql_prep($array['desc']);
			$create_time = $modify_time = time();
			
			$sql = mysql_query("INSERT INTO `notification` (`type`,`type_id`,`desc`, `create_time`, `modify_time`) VALUES ('".$type."','".$type_id."','".$desc."','".$create_time."','".$modify_time."')") or die (mysql_error());
			
			if ($sql) {
				$id = mysql_insert_id();
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
			
			$sql = mysql_query("UPDATE notification SET `".$tag."` = '".$value."', `modify_time` = '".time()."' WHERE ref = '".$id."'") or die (mysql_error());
			
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
			$sql = mysql_query("SELECT * FROM `notification` ORDER BY `ref` ".$order) or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['type'] = $row['type'];
					$result[$count]['type_id'] = $row['type_id'];
					$result[$count]['desc'] = $row['desc'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['status_read'] = $row['status_read'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function sortAll($id, $tag, $tag2=false, $id2=false, $tag3=false, $id3=false, $order='ref', $dir="DESC") {
			$id = $this->mysql_prep($id);
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
			$sql = mysql_query("SELECT * FROM `notification` WHERE `".$tag."` = '".$id."' ".$sqlTag." ORDER BY `".$order."` ".$dir) or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
					$result[$count]['ref'] = $row['ref'];
					$result[$count]['type'] = $row['type'];
					$result[$count]['type_id'] = $row['type_id'];
					$result[$count]['desc'] = $row['desc'];
					$result[$count]['status'] = $row['status'];
					$result[$count]['status_read'] = $row['status_read'];
					$result[$count]['create_time'] = $row['create_time'];
					$result[$count]['modify_time'] = $row['modify_time'];
					$count++;
				}
				return $this->out_prep($result);
			}
		}
		
		function getOne($id, $tag='ref') {
			$id = $this->mysql_prep($id);
			$sql = mysql_query("SELECT * FROM `notification` WHERE `".$tag."` = '".$id."'") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				
				$row = mysql_fetch_array($sql);
				if ($row > 0) {
					$result['ref'] = $row['ref'];
					$result['type'] = $row['type'];
					$result['type_id'] = $row['type_id'];
					$result['desc'] = $row['desc'];
					$result['status'] = $row['status'];
					$result['status_read'] = $row['status_read'];
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
		
		function notificationCount() {
			$list = $this->sortAll(0, "status");
			return number_format(count($list));
		}
		
		function notificationCountGroup($group) {
			$list = $this->sortAll(0, "status", "type", $group);
			return number_format(count($list));
		}
		
		function openNotification() {
			$sql = mysql_query("UPDATE notification SET `status` = '1', `modify_time` = '".time()."'") or die (mysql_error());
			
			if ($sql) {
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = $_SESSION['admin']['id'];
				$logArray['desc'] = "opened notifications";
				$logArray['create_time'] = time();
				$system_log = new system_log;
				$system_log->create($logArray);
				return true;
			} else {
				return false;
			}
		}
	}
?>