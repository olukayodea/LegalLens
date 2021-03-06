<?php
	class caselaw_area extends common {
		function add($array) {
			$title = htmlentities($this->mysql_prep($array['title']));
			$status = $this->mysql_prep($array['status']);
			$create_time = $modify_time = time();
			$ref = $this->mysql_prep($array['ref']);

			global $db;
			$value_array = array(
							':title' => $title, 
							':status' => $status, 
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
				$sql = $db->prepare("INSERT INTO `caselaw_area` (".$firstpart."`title`,`status`, `create_time`, `modify_time`)
				VALUES (".$secondPArt.":title, :status, :create_time, :modify_time)
					ON DUPLICATE KEY UPDATE 
						`title` = :title,
						`status` = :status,
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
				$logArray['desc'] = $log;
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
			global $db;
			try {
				$sql = $db->prepare("DELETE FROM `caselaw_area` WHERE `ref` =:id");
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
				$logArray['desc'] = "removed regulator id #".$id;
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
				$sql = $db->prepare("UPDATE `caselaw_area` SET  `".$tag."` = :value, `modify_time` = :modifyTime WHERE `ref`=:id");
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
				$logArray['owner_id'] = intval($_SESSION['admin']['id']);
				$logArray['desc'] = "Modified ".$tag." with ".$value;
				$logArray['create_date'] = time();
				$system_log = new system_log;
				$system_log->create($logArray);
				return true;
			} else {
				return false;
			}
		}
		
		function listAll() {
			global $db;
			try {
				$sql = $db->query("SELECT * FROM `caselaw_area` ORDER BY `title` ASC");
			} catch(PDOException $ex) {
				echo "An Error occured! ".$ex->getMessage(); 
			}
			
			$row = $sql->fetchAll(PDO::FETCH_ASSOC);
				
			return $this->out_prep($row);
		}
		
		function sortAll($id, $tag, $tag2=false, $id2=false, $tag3=false, $id3=false, $order="ref") {
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
					$sql = $db->prepare("SELECT * FROM `caselaw_area` WHERE `".$tag."` = :id".$sqlTag." ORDER BY `".$order."` ASC");
									
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
				$sql = $db->prepare("SELECT * FROM caselaw_area WHERE `".$tag."` = :id ORDER BY `ref` DESC LIMIT 1");
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
		
		function getOneField($id, $tag="ref", $ref="title") {
			$data = $this->getOne($id, $tag);
			return $data[$ref];
		}
	}		
?>