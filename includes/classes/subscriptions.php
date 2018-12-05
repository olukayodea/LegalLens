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
			
			if ($ref != "") {
				$firstpart = "`ref`, ";
				$secondPArt = "'".$ref."', ";
				$log = "Modified object ".$title;
			} else {
				$firstpart = "";
				$secondPArt = "";
				$log = "Created object ".$title;
			}
			
			$sql = mysql_query("INSERT INTO `subscriptions` (".$firstpart."`title`, `validity`, `amount`,`type`, `status`, `create_time`, `modify_time`) VALUES (".$secondPArt."'".$title."','".$validity."','".$amount."','".$type."','".$status."', '".$create_time."', '".$modify_time."') ON DUPLICATE KEY UPDATE `title` = '".$title."', `status` = '".$status."', `amount` = '".$amount."', `type` = '".$type."', `modify_time` = '".$modify_time."'") or die (mysql_error());
			
			if ($sql) {
				$id = mysql_insert_id();
				
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = $_SESSION['admin']['id'];
				$logArray['desc'] = "created subscriptions ".$title;
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
			$data = $this->getOne($id);
			$media_url = $data['media_url'];
			$sql = mysql_query("DELETE FROM `subscriptions` WHERE ref = '".$id."'") or die (mysql_error());
				
			if ($sql) {
				//add to log
				$logArray['object'] = get_class($this);
				$logArray['object_id'] = $id;
				$logArray['owner'] = "admin";
				$logArray['owner_id'] = $_SESSION['admin']['id'];
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
			$modDate = time();
			$sql = mysql_query("UPDATE `subscriptions` SET `".$tag."` = '".$value."', `modify_time` = '".$modDate."' WHERE ref = '".$id."'") or die (mysql_error());
			
			if ($sql) {
				return true;
			} else {
				return false;
			}
		}
		
		function listAll() {
			$sql = mysql_query("SELECT * FROM `subscriptions` ORDER BY `ref` ASC") or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
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
						
			$sql = mysql_query("SELECT * FROM `subscriptions` WHERE `".$tag."` = '".$id."'".$sqlTag." ORDER BY ".$order.$limitTag) or die (mysql_error());
			
			if ($sql) {
				$result = array();
				$count = 0;
				
				while ($row = mysql_fetch_array($sql)) {
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
			$id = $this->mysql_prep($id);
			$sql = mysql_query("SELECT * FROM `subscriptions` WHERE `".$tag."` = '".$id."' ORDER BY `ref` DESC LIMIT 1") or die (mysql_error());
			if ($sql) {
				$result = array();
				
				if (mysql_num_rows($sql) == 1) {
					$row = mysql_fetch_array($sql);
					$result['ref'] = $row['ref'];
					$result['title'] = ucwords(strtolower($row['title']));
					$result['validity'] = $row['validity'];
					$result['amount'] = $row['amount'];
					$result['type'] = $row['type'];
					$result['status'] = $row['status'];
					$result['create_time'] = $row['create_time'];
					$result['modify_time'] = $row['modify_time'];
					return $this->out_prep($result);
				} else {
					return false;
				}
			}
		}
		
		function getOneField($id, $tag="ref", $ref="title") {
			$data = $this->getOne($id, $tag);
			return $data[$ref];
		}
	}
?>